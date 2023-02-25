<?php

require_once ROOT_DIR() . '/src/Entity/Database.php';

$db = new Database('api');
$GLOBALS['Database'] = $db->connexion();


function majBdd()
{
	$startTime = microtime(TRUE);
	$liste_marques = [];
	$list_modelesBdd = [];

	$requete = "SELECT `id_brand`,`brand_name` FROM `brand` ORDER BY `brand_name`";
	$result = mysqli_query($GLOBALS['Database'], $requete) or die;
	while ($data = mysqli_fetch_assoc($result)) {
		$liste_marques[$data["id_brand"]] = strtoupper($data['brand_name']);
	}
	$requeteT = "SELECT * FROM `model`
	 INNER JOIN `brand` ON `brand`.`id_brand` = `model`.`id_brand`
	 WHERE 1";
	$resultT = mysqli_query($GLOBALS['Database'], $requeteT) or die;
	while ($dataT = mysqli_fetch_array($resultT)) {
		$list_modelesBdd[$dataT['brand_name']]['id'] = $dataT['id_brand'];
		$list_modelesBdd[$dataT['brand_name']]['list'][] = $dataT['model_name'];
	}


	$apiUrl  = "http://applis.matmut.fr/DevisMRSQInternet/devis.mcp/";
	$genre   = array('Voiture' => '5');
	$resultat  = array();
	$output = [];

	foreach ($genre as $typeVehicule => $idType) {
		$marques   = JSON_decode(file_get_contents($apiUrl . "GetListeMarques?genreVehicule=$idType"), true);
		foreach ($marques as $idMarque => $nomMarque) {
			if (strtoupper($nomMarque['Text']) != 'AUTRES MARQUES') {
				if (!in_array($nomMarque['Text'], $liste_marques)) {
					$requete2 = "INSERT INTO `brand` (`brand_name`) VALUES  ('" . mysqli_real_escape_string($GLOBALS['Database'], strtoupper($nomMarque['Text'])) . "')";
					mysqli_query($GLOBALS['Database'], $requete2) or die;
					$new_id_brand = $GLOBALS['Database']->insert_id;
					$liste_marques[] = strtoupper($nomMarque['Text']);
					$list_modelesBdd[strtoupper($nomMarque['Text'])]['id'] = $new_id_brand;
					$list_modelesBdd[strtoupper($nomMarque['Text'])]['list'] = array();
					error_log($nomMarque['Text'] . ' ajouté en base de données.');
					$output['brands'][] = $nomMarque['Text'];
				}
				$resultat[$nomMarque['Text']] = array();
				$urlModel = "GetListeModeles?anneeDebut=&anneeFin=" . date('Y') . "&genreVehicule=$idType&marque=" . urlencode($nomMarque['Text']);
				$modeles = JSON_decode(file_get_contents($apiUrl . $urlModel), true);
				foreach ($modeles as $idModele => $nomModele) {
					if ($nomModele['Text'] != 'AUTRE') {
						if (!in_array($nomModele['Text'], $list_modelesBdd[strtoupper($nomMarque['Text'])]['list'])) {
							error_log($nomModele['Text'] . " a été ajouté");
							$output['models'][] = $nomModele['Text'];
							$requete3 = "INSERT INTO `model` (`id_brand`,`model_name`) 
							VALUES  (
								'" . mysqli_real_escape_string($GLOBALS['Database'], $list_modelesBdd[strtoupper($nomMarque['Text'])]['id']) . "', 
								'" . mysqli_real_escape_string($GLOBALS['Database'], strtoupper($nomModele['Text'])) . "'
								)";
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

	error_log('La base données a été mise à jour en ' . round($totalTime, 2) . ' secondes');

	return array(
		'totalTime' => round($totalTime, 2),
		'output' => $output
	);
}
