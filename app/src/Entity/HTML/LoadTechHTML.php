<?php

class LoadTechHTML
{

    //    DEBUT Contenus des tableaux

    public static function pending($interv, $hour, $brand_name, $model, $registration, $date): string
    {
        $currentDate = strtotime(date('d-m-Y'));
        $rdv = date('H:i', $hour);
        $load = "
            <tr>
                <td>
                    <button onclick='modalRdvInfo(`$interv`)' 
                        class='modalRdvInfo border-0 bg-transparent font-medium' 
                        type='button' data-toggle='tooltip' 
                        data-placement='bottom' 
                        title='Voir infos'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
                            <path d = 'M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 
                                0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z' />
                        </svg >
                    </button >
                </td>
                <td>
                    <span class='text-muted fs-2'>" . Security::decrypt($interv, false) . "</span>
                </td>
                <td>
                    <span class='text-muted fs-2'>" . $rdv . "</span>
                </td>
                <td class='text-muted'>
                    <img src='../public/assets/img/logo/" . strtoupper($brand_name) . ".png' alt='logo_marque'>
                </td>                
                <td>
                    <span class='text-muted fs-2'>" . $model . "</span>
                </td>
                <td>
                    <span class='text-muted fs-2'>" . $registration . "</span>
                </td>";
        if ($date == $currentDate) {
            $load .= "
                    <td>
                        <button onclick='priseEnCharge(`$interv`)' 
                                type='button' class='priseEnCharge border-0 bg-transparent' 
                                data-toggle= 'tooltip' 
                                data-placement= 'bottom' 
                                title= 'Prise en charge'>
                            <i class='fa-solid fa-circle-arrow-right fa-xl text-info'></i>
                        </button>
                    </td> 
                ";
        }
        $load .= "
                <td>
                    <button  onclick='deleteRdv(`$interv`)' 
                            type='button' 
                            class='deleteRdvTech border-0 bg-transparent' 
                            data-toggle='tooltip' 
                            data-placement='bottom' 
                            title='Supprimer intervention'>
                        <i class='fa-solid fa-xmark text-danger fa-xl'></i>
                    </button>          
                </td>
            </tr>
        ";

        return $load;
    }

    public static function inProgress($interv, $hour, $nomTech, $brand_name, $registration): string
    {
        $brand_name = strtoupper($brand_name);
        $id_interv = Security::encrypt($interv, false);
        $rdv = date('H:i', $hour);
        return "
            <tr>
                <td>
                    <span class='text-muted font-13'>#</span>
                    <br>
                    <span class='text-muted fs-2'>" . $interv . "</span>
                </td>
                <td>
                    <span class='text-muted font-13'>Heure</span>
                    <br>
                    <span class='text-muted fs-2'>" . $rdv . "</span>
                </td>
                <td>
                    <span class='text-muted font-13'>Technicien</span>
                    <br>
                    <span class='text-muted fs-2'>" . $nomTech . "</span>
                </td>
                <td class='text-center'>
                    <img src='../public/assets/img/logo/" . strtoupper($brand_name) . ".png' alt='logo_marque'>
                </td>                
                <td>
                    <span class='text-muted font-13'>Immatriculation</span>
                    <br>
                    <span class='text-muted fs-2'>" . $registration . "</span>
                </td>
                <td>
                   <a href='/checklist?intervention=" . $interv . "'>
                        <button type='button' class='checklist border-0 bg-transparent' data-toggle='tooltip' data-placement='bottom' title='Checklist CT'>
                            <i class='fa-solid fa-clipboard-check fa-xl text-info'></i>
                        </button>
                   </a>
                </td>
                <td>
                   <button onclick='switchToHold(`$id_interv`)' type='button' class='border-0 bg-transparent' data-toggle='tooltip' data-placement='bottom' title='Basculer en attente'>
                        <i class='fa-solid fa-xl fa-arrow-rotate-left'></i>
                   </button>
                </td>
            </tr>";
    }

    public static function techHistory($interv, $client_lastname, $phone_client, $registration, $state): string
    {
        $id_interv = Security::encrypt($interv, false);
        $html = '
            <tr class="interventionModal">
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Client</span>
                    <br>
                    <span class="text-muted fs-2">' . $client_lastname . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Téléphone</span>
                    <br>
                    <span class="text-muted fs-2">' . $phone_client . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $registration . '</span>
                </td>
                ';
        if ($state == 2) {
            $html .= "
                <td>
                    <span class='text-muted font-13'>Status</span>
                    <br>
                    <span onclick='showContreVisite(`$id_interv`)' type='button' class='badge rounded-pill bg-success bg-opacity-25 text-success' >Validé</span>
                </td>
            </tr> 
            ";
        } else if ($state == 3) {
            $html .= "
                <td>
                    <span class='text-muted font-13'>Status</span>
                    <br>
                    <div 
                        onclick='showContreVisite(`$id_interv`)' 
                        type='button'  
                        class='badge rounded-pill bg-warning bg-opacity-25 text-warning'> 
                        Contre - Visite
                    </div>
                </td>
            </tr> 
            ";
        } else if ($state = 4) {
            $html .= '
                <td>
                    <span class="text-muted font-13">Status</span>
                    <br>
                    <div class="badge rounded-pill bg-danger bg-opacity-25 text-danger fw-bold" >Annulé</div >
                </td>
            </tr> 
            ';
        }

        return $html;
    }

    //    FIN Contenus des tableaux

    ///////////////////////////////////////////////////////////////////////////////////////

    //    DEBUT Structure des tableaux et modules

    public static function tabStructure(): string
    {
        return "
            <div id='dashTech' class='content container-xl'>
                <div class='row mt-5'>
                    <!-- 1er TABLEAU VEHICULES EN ATTENTE -->
                    <div class='col-12 col-sm-12 col-md-12 col-lg-12 mb-5'>
                        <div class='card shadow rounded border-0'>
                            <div class='card-header bg-card-gradient bg-opacity-75 rounded-top d-flex justify-content-between align-items-center'>
                                <h5 class='m-0 py-2 start-0 text-dark'>Rendez-vous du:</h5>
                                <div class='btnPrevious position-absolute' style='left: 27%'></div>
                                <div id='dateDuJour' class='position-absolute' style='left: 36%'>
                                    <!-- génération des switch dates-->
                                </div>
                                <div class='btnNext position-absolute' style='left: 72%'></div>
                                <div class='btnBack'></div>                      
                            </div>
                            <div id='filtreImmat' class='d-flex align-items-center mt-2 ms-2'>
                            <!-- génération du filtre immat -->
                            </div>   
                            <div class='card-body'>
                                <div class='table-responsive'>
                                    <table class='table table-centered align-middle table-nowrap table-hover mb-0'>
                                        <thead class='thead-awaiting'>
                                            <tr class='text-center'>
                                                <th></th>
                                                <th>N° INTER</th>
                                                <th>HEURE DU RDV</th>
                                                <th>MARQUE</th>
                                                <th>MODELE</th>
                                                <th>IMMATRICULATION</th>
                                                <th>PRISE EN CHARGE</th>
                                                <th>SUPPRIMER</th>
                                            </tr>
                                        </thead>
                                        <tbody id='vehiculeAttente' class='text-center'>
                                        <!-- génération des interventions en attente ici-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <nav class='d-flex justify-content-center mt-3' aria-label='Page navigation'>
                            <span class='anchor' id='anchorEnCours'>&nbsp;</span>
                            <ul class='pagination justify-content-center' id='pagesHold'></ul>
                        </nav>
                    </div>
                </div>
                <div class='row'>
                    <!-- 2eme TABLEAU INTERVENTIONS EN COURS -->
                    <div class='col-12 col-sm-12 col-md-12 col-lg-12 mt-3 mb-5'>
                        <div class='card shadow rounded border-0'>
                            <div class='card-header bg-card-gradient rounded-top d-flex justify-content-between align-items-center'>
                                <h5 class='m-0 py-2 text-dark'>Interventions en Cours</h5>
                            </div>
                            <div class='card-body'>
                                <div class='table-responsive'>
                                    <table class='table table-centered align-middle table-nowrap table-hover mb-0'>
                                        <tbody id='interventionTab' class='text-center'>
                                        <!-- génération des interventions en cours ici-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <nav class='mt-3' aria-label='Page navigation'>
                            <ul class='pagination justify-content-center' id='pagesInProgress'></ul>
                        </nav>
                    </div>
                </div>
                <!-- 3eme TABLEAU VEHICULES TERMINES -->
                <div class='row'>
                    <div class='col-12 col-sm-12 col-md-12 col-lg-12 mt-3'>
                        <div class='card shadow rounded border-0'>
                            <div class='card-header bg-card-gradient rounded-top d-flex justify-content-between align-items-center'>
                                <h5 class='m-0 py-2 text-dark'>Véhicules terminés</h5>
                                <div class='anchor' id='anchorTermine'>&nbsp;</div>
                            </div>
                            <div class='card-body'>
                                <div class='table-responsive'>
                                    <table class='table table-centered align-middle table-nowrap table-hover mb-0'>
                                        <tbody id='vehiculesTermines' class='text-center'>
                                        <!-- génération de lhistorique ici-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class='mt-3' aria-label='Page navigation'>
                        <ul class='pagination justify-content-center' id='pagesOver'></ul>
                    </nav>
                </div>
            </div>
        ";
    }

    public static function filtre_registration(): string
    {
        return "
    <input id='searchImmat' class='filtre rounded' type='search' placeholder='IMMATRICULATION'>";
    }

    //    FIN Structure des tableaux et modules

    ///////////////////////////////////////////////////////////////////////////////////////

    //    DEBUT Gestion Checklist

    public static function checklist_content(): string
    {
        return '<div class="row flex-column flex-sm-row justify-content-between align-items-center my-3 mb-xl-2 gap-sm-0">
        <a role="button" id="btn-info-clist" class="col d-flex text-decoration-none">
            <i class="fa-solid fa-circle-info fs-5 fs-md-7 text-info"></i>
        </a>
        <h1 class="inter-id col text-center order-0 order-sm-1 fs-3" id="">
            <!--n° dinter généré ici-->
        </h1>
        <div class="col d-flex justify-content-center justify-content-sm-end align-items-center order-last">
            <div class="d-flex justify-content-center align-items-center gap-4">
                <div class="d-flex justify-content-center align-items-center form-check gap-2">
                    <input class="label-check-all-btn-box form-check-input border-2 border-dark position-absolute" type="checkbox" value=""
                           id="checkAllbtn">
                    <label class="label-check-all-btn form-check-label mb-0 position-relative" for="checkAllbtn">
                        Tout cocher
                    </label>
                </div>
                <a role="button" onclick="validationCT()">
                    <i class="fa-solid fa-circle-check text-success text-opacity-75 fs-5"></i>
                </a>
                <a type="button" onclick="window.location.href = `/dashboards/technicien`;" class="ms-4">
                    <i class="fa-solid fa-reply-all text-danger text-opacity-75 fs-5"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center gap-3 mt-7">
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/freins.png  "
                     alt="Card image cap"
                    width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Freinage</h4>
                <div class="row justify-content-center">
                    <div class="form-check col pe-0">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="freinService">
                        <label class="form-check-label fs--2" for="freinService">
                            Frein de service
                        </label>
                    </div>
                    <div class="form-check col pe-0">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="freinStationnement">
                        <label class="form-check-label fs--2" for="freinStationnement">
                            Frein de stationnement
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="pedaleFrein">
                        <label class="form-check-label fs--2" for="pedaleFrein">
                            Frein de service
                        </label>
                    </div>

                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="disqueFrein">
                        <label class="form-check-label fs--2" for="disqueFrein">
                            Disque de frein
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/struct.png"
                     alt="Card image cap" width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Structure</h4>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="plancher">
                        <label class="form-check-label fs--2" for="plancher">
                            Planchers
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="coque">
                        <label class="form-check-label fs--2" for="coque">
                            Carroserie
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="chassis">
                        <label class="form-check-label fs--2" for="chassis">
                            Chassis
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="aile">
                        <label class="form-check-label fs--2" for="aile">
                            Passages de roues
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/visib.png"
                     alt="Card image cap" width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Visibilité</h4>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="pareBrise">
                        <label class="form-check-label fs--2" for="pareBrise">
                            Pare brise
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="retro">
                        <label class="form-check-label fs--2" for="retro">
                            Rétroviseurs
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="essuieGlace">
                        <label class="form-check-label fs--2" for="essuieGlace">
                            Essuie-glaces
                        </label>
                    </div>
                    <div class="form-check col">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/eclairage.png"
                     alt="Card image cap" width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Eclairage</h4>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="feuRoute">
                        <label class="form-check-label fs--2" for="feuRoute">
                            Feux de route
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="feuCroisement">
                        <label class="form-check-label fs--2" for="feuCroisement">
                            Feux de croisement
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="feuDetresse">
                        <label class="form-check-label fs--2" for="feuCroisement">
                            Feux de détresse
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/direction.png"
                     alt="Card image cap" width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Direction</h4>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="volantDirection">
                        <label class="form-check-label fs--2" for="volantDirection">
                            Volant
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="colonneDirection">
                        <label class="form-check-label fs--2" for="colonneDirection">
                            Colonne de direction
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="pompe">
                        <label class="form-check-label fs--2" for="pompe">
                            Pompe d`assistance
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-lg-3 p-0 card overflow-hidden d-flex flex-column align-items-center">
            <div class="card-img-top d-flex flex-column align-items-center">
                <img class="img-fluid"
                     src="../public/assets/img/checklist/moteur.png"
                     alt="Card image cap" width="50%"/>
            </div>
            <div class="card-body container py-0">
                <h4 class="text-center">Mécanique</h4>
                <div class="row justify-content-center">
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="moteur">
                        <label class="form-check-label fs--2" for="moteur">
                            Moteur
                        </label>
                    </div>
                    <div class="form-check col">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="boiteVitesse">
                        <label class="form-check-label fs--2" for="boiteVitesse">
                            Boite de vitesses
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="form-check">
                        <input class="checkBox form-check-input border-2 border-dark" type="checkbox"
                               value=""
                               id="transmission">
                        <label class="form-check-label fs--2" for="transmission">
                            Transmission
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>';

    }

    public static function checklist_info($data, $tech): string
    {
        return '
            <div class="col-12">
                <li class="fw-bold">Technicien: 
                    <span id="numeroTech" class="fw-normal"> ' . $tech->getFirstname_user() . '</span>
                </li>
                <li class="fw-bold">Marque: 
                    <span id="marqueInter" class="fw-normal"> ' . $data['brand_name'] . '</span>
                </li>
                <li class="fw-bold">Modèle: 
                    <span id="modeleInter" class="fw-normal"> ' . $data['model_name'] . '</span>
                </li>
                <li class="fw-bold">Immat: 
                    <span id="immatInter" class="fw-normal"> ' . $data['registration'] . '</span>
                </li>
            </div>';
    }

    //    FIN Gestion Checklist

}