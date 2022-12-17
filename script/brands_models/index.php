<?php
class Database{
    private $db;
    public function __construct(){
        try{
            $this->db = mysqli_connect("localhost", "API_CT", "Db789!@50", "aflauto");
        } catch (RuntimeException $e){
            exit(0);
        }
    }
    public function connexion(){
        return $this->db;
    }
}
$db = new Database();
$GLOBALS['Database'] = $db->connexion();

function majBdd(){
	$startTime = microtime(TRUE);
	$liste_marques = [];
	$list_modelesBdd = [];

	$requete = "SELECT `id_marque`,`nom_marque` FROM `marques` ORDER BY `nom_marque`";
	$result = mysqli_query($GLOBALS['Database'], $requete) or die;
	while ($data = mysqli_fetch_assoc($result)) {
		$liste_marques[$data["id_marque"]] = strtoupper($data['nom_marque']);
	}
	$requeteT = "SELECT * FROM `modeles`
	 INNER JOIN `marques` ON `marques`.`id_marque` = `modeles`.`id_marque`
	 WHERE 1";
	$resultT = mysqli_query($GLOBALS['Database'], $requeteT) or die;
	while ($dataT = mysqli_fetch_array($resultT)) {	
		$list_modelesBdd[$dataT['nom_marque']]['id'] = $dataT['id_marque'];
		$list_modelesBdd[$dataT['nom_marque']]['list'][] = $dataT['nom_modele'];		
	}

	$apiUrl  = "http://applis.matmut.fr/DevisMRSQInternet/devis.mcp/";
	$genre   = array('Voiture'=>'5');
	$resultat  = array();

	foreach ($genre as $typeVehicule => $idType){
		$marques   = JSON_decode(file_get_contents($apiUrl."GetListeMarques?genreVehicule=$idType"),true);
		foreach($marques as $idMarque => $nomMarque){
			if(strtoupper($nomMarque['Text']) != 'AUTRES MARQUES'){
					if(!in_array($nomMarque['Text'], $liste_marques)){
						$requete2 = "INSERT INTO `marques` (`nom_marque`) VALUES  ('" . mysqli_real_escape_string($GLOBALS['Database'], strtoupper($nomMarque['Text'])) . "')";
						mysqli_query($GLOBALS['Database'], $requete2) or die;
						$new_id_marque = $GLOBALS['Database']->insert_id;
						$liste_marques[] = strtoupper($nomMarque['Text']);
						$list_modelesBdd[strtoupper($nomMarque['Text'])]['id'] = $new_id_marque;
						$list_modelesBdd[strtoupper($nomMarque['Text'])]['list'] = array();
						error_log($nomMarque['Text'].' ajouté en base de données.')	;	
					}
					$resultat[$nomMarque['Text']] = array();
					$urlModel = "GetListeModeles?anneeDebut=&anneeFin=".date('Y')."&genreVehicule=$idType&marque=".urlencode($nomMarque['Text']);
					$modeles = JSON_decode(file_get_contents($apiUrl.$urlModel),true);
					foreach ($modeles as $idModele => $nomModele){
						if($nomModele['Text'] != 'AUTRE'){
							if(!in_array($nomModele['Text'],$list_modelesBdd[strtoupper($nomMarque['Text'])]['list'])){
								error_log($nomModele['Text']. " a été ajouté");
								$requete3 = "INSERT INTO `modeles` (`id_marque`,`nom_modele`) VALUES  ('" . mysqli_real_escape_string($GLOBALS['Database'], $list_modelesBdd[strtoupper($nomMarque['Text'])]['id']) . "', '" . mysqli_real_escape_string($GLOBALS['Database'], strtoupper($nomModele['Text'])) . "')";
								mysqli_query($GLOBALS['Database'], $requete3) or die;
								$list_modelesBdd[strtoupper($nomMarque['Text'])]['list'][] = $nomModele['Text'];

							}
						}
					} 
			}		
		}
	}
	$endTime = microtime(TRUE);
	$totalTime = $endTime - $startTime;

	error_log('La base données a été mise à jour en '.round($totalTime, 2).' secondes');
}
majBdd();



