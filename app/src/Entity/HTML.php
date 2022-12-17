<?php

class HTML
{
    public static function messageHamster(): string
    {
        return "
        <div>
            <div class='bg-success bg-opacity-10 text-center text-success border border-1 border-success p-2'>
                Notre hamster se charge de nous transmettre votre message ! Nous reviendrons vers vous très rapidement.
            </div>
           <a class='btn btn-primary btn-sm mt-3' id='to_logIn'><span class='fas fa-chevron-left me-1' data-fa-transform='shrink-4 down-1'></span>Retour au login</a>
        </div>
        ";
    }

    public static function navBarType($user): array
    {
        if ($user->getType() == 'technicien') {
            $nav_items = "";
            $logo_link = 'technicien';
        } else {
            $nav_items = "
                <li class='nav-item m-0'>
                    <a class='nav-link' id='formClient' role='button'>Prise de RDV</a>
                </li>
            ";
            $logo_link = 'client';
        }
        return array($nav_items, $logo_link);
    }

    public static function generateDate($date, $timeStampDate): string
    {
        $currentDate = strtotime(date('Y-m-d'));
        $nextDate = $timeStampDate + 86400;
        $previousDate = $timeStampDate - 86400;
        $dayCase = "
            <div id='flip' class='d-flex position-relative justify-content-center py-3 mt-2 text-white bg-primary rounded-top'>
        ";
        if ($timeStampDate != $currentDate) {
            $dayCase .= "
                <i type='button' onClick='changeDate($previousDate);' class='fa-solid fa-angles-left position-absolute top-50 start-0 translate-middle-y ps-1'></i>
            ";
        }
        $dayCase .= "
            <input type='button' id='datepicker' value='$date' class='text-sm-center currentDate'> 
                <i type='button' onClick='changeDate($nextDate);' class='fa-solid fa-angles-right position-absolute top-50 end-0 translate-middle-y pe-1'></i>
            </div>
            <div id='panel' class='$timeStampDate bg-secondary bg-opacity-10 p-3 text-center rounded-bottom'>
                <!--Génération des créneaux disponible ici-->
            </div>
        ";

        return $dayCase;
    }

    public static function generateDateBackOffice($date, $timeStampDate2): array
    {
        $currentDate = strtotime(date('d-m-Y'));
        $nextDate = $timeStampDate2 + 86400;
        $previousDate = $timeStampDate2 - 86400;
        $btnPrevious = "";
        if ($timeStampDate2 != $currentDate) {
            $btnPrevious = "
                <button
                    id='$previousDate'
                    onClick='generateDateBO($previousDate),previousDayRdv(1);' 
                    class='changerDate previousDate fa-solid fa-circle-left fa-xl align-middle bg-transparent border-0 ms-2 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Jour précédent'>
                </button>
            ";
        }
        $dayCase2 = "
                <span id='$timeStampDate2' class='text-sm-center fs-2 currentDate text-dark'>$date<span/>
            ";
        $btnNext = "
                <button
                    id='$nextDate'
                    onClick='generateDateBO($nextDate),nextDayRdv(1);' 
                    class='changerDate nextDate fa-solid fa-circle-right fa-xl align-middle bg-transparent border-0 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Jour suivant'>
                </button>
            ";
        $btnBack = "
                <button
                    onClick='generateDateBO($currentDate),vehicule_attente(1);' 
                    class='changerDate fa-solid fa-reply fa-xl align-middle bg-transparent border-0 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Revenir au début'>
                </button>
            ";

        return array(
            "daycase2" => $dayCase2,
            "btnBack" => $btnBack,
            "btnNext" => $btnNext,
            "btnPrevious" => $btnPrevious
        );
    }


    public static function timeSlot($timeStampID, $slotInterval): string
    {
        return "
            <input type='radio' class='btn-check' name='timeSlot' id='$timeStampID' autocomplete='off'>
            <label class='btn btn-outline-success border-success my-2 p-2 text-center text-dark' for='$timeStampID'>$slotInterval</label>                
		";
    }

    public static function formAddCar($marque): string
    {
        return "         
            <div class='rounded p-3'>
                <div class='text-dark form-label mb-2' style='font-weight: 500'>Sélectionner le format de votre immatriculation :</div>
                    <div class='form-group d-flex gap-5 mb-1 justify-content-center'>
                        <div class='form-check'>
                            <input class='form-check-input' type='radio' name='radioImmat'
                                   id='newImmat'
                                   value='newImmat' checked>
                            <label class='form-check-label text-dark fw-bold fst-italic' for='newImmat'>
                                Nouveau
                            </label>
                        </div>
                        <div class='form-check'>
                            <input class='form-check-input' type='radio' name='radioImmat'
                                   id='oldImmat'
                                   value='oldImmat'>
                            <label class='form-check-label text-dark fw-bold fst-italic' for='oldImmat'>
                                Ancien
                            </label>
                        </div>
                    </div>
                    <div class='input-group mb-3 licenseplate' id='licence-plate-new' data-country='EU'>
                        <label for='inputImmatNew'
                               class='sr-only'>Immatriculation</label>
                        <input type='text'
                               class='form-control'
                               id='inputImmatNew'
                               aria-describedby='textHelp'
                               placeholder='AA-1234-AA'
                               pattern='^[A-Z]{2} ?- ?\d{3} ?- ?[A-Z]{2}$'>
                    </div>
                    <div class='invalid-feedback'></div>
                    <div class='input-group mb-3 licenseplate' id='licence-plate-old' data-country='EU' hidden>
                        <label for='inputImmatOld'
                               class='sr-only'>Immatriculation</label>
                        <input type='text'
                               class='form-control'
                               id='inputImmatOld'
                               aria-describedby='textHelp'
                               placeholder='1234-AA-0A'
                               pattern='^[0-9]{1,4} ?- ?[A-Z]{1,4} ?- ?[0-9]{1,2}$'>
                    </div>
                    <div class='invalid-feedback'></div>
                    <div class='d-flex flex-row justify-content-between'>
                        <div class='col-5'>
                            <label for='selectMarque' class='form-label text-dark'>Sélectionner une marque</label>
                            <select class='form-select' id='selectMarque'>
                                $marque
                            </select>
                            <div class='invalid-feedback'></div>
                        </div>
                        <div class='col-5'>
                            <label for='selectModele' class='form-label text-dark'>Sélectionner un modèle</label>
                            <select class='form-select' id='selectModele'>
                                <option value=''>-</option>
                            </select>
                            <div class='invalid-feedback'></div>
                        </div>
                    </div>
                <div class='mb-2 mt-4 form-label text-dark' style='font-weight: 500'>Type de carburant</div>
                <div class='d-flex gap-2 form-group'>
                    <div>
                        <div class='form-check'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name='optionsCarbu'
                                id='Essence'
                                value='Essence'/>
                            <label class='form-check-label text-dark fw-bold fst-italic' for='Essence'>Essence</label>
                        </div>
                        <div class='form-check'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name='optionsCarbu'
                                id='Diesel'
                                value='Diesel'/>
                            <label class='form-check-label text-dark fw-bold fst-italic' for='Diesel'>Diesel</label>
                        </div>
                    </div>
                    <div>
                        <div class='form-check'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name='optionsCarbu'
                                id='Electrique'
                                value='Electrique'
                            />
                            <label
                                class='form-check-label text-dark fw-bold fst-italic'
                                for='Electrique'>
                            Electrique
                            </label>
                        </div>
                        <div class='form-check'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name='optionsCarbu'
                                id='Hybride'
                                value='Hybride'
                            />
                            <label class='form-check-label text-dark fw-bold fst-italic' for='Hybride'>Hybride</label>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <label for='inputAnnee' class='form-label mt-4 text-dark'>Année de 1ère mise en circulation</label>
                    <input
                      type='text'
                      class='form-control'
                      id='inputAnnee'
                      placeholder='Année ici'/>        
                <div class='invalid-feedback'></div>          
                </div>
                <div class='d-flex justify-content-center'>
                    <button type='submit' id='validateFormCar'
                        class='btn btn-primary p-2 mt-5 mb-1 rounded w-100'>
                    Ajouter
                    </button>
                </div>
            </div>
    ";
    }

    public static function formAddRDV($car_user): string
    {

        return "
            <div class='rounded p-3'>
                <div class='form-group'>
                    <label for='selectCars' class='form-label text-dark'>Sélectionner un véhicule :</label>
                    <select class='form-select' id='selectCars' name='selectCars' required>
                            $car_user
                    </select>
                </div>
                <div class='form-group mt-4'>
                    <label class='form-label text-dark'>Sélectionner un créneau horaire :</label>
                    <div id='rdvContainer' class='mt-4 shadow rounded'>
                    
                        <!--Contenu des créneaux ici, généré avec class HTML -->
                        
                    </div>
                </div>
                <div class='d-flex justify-content-center'>
                    <button
                        type='submit'
                        class='btn btn-primary p-2 mt-5 rounded w-100'>
                    Réserver !
                    </button>
                </div>
            </div>
        ";

    }

    public static function loadInterventions($interv, $heure, $nomMarque, $modele, $immat, $date): string
    {
        $currentDate = strtotime(date('d-m-Y'));
        $rdv = date('H:i', $heure);
        $load = '
               
            <tr>
                <td>
                    <button id="showInfo" 
                           onclick="showInfo(' . $interv . ')" 
                           class="border-0 bg-transparent font-medium" 
                           type="button" data-toggle="tooltip" 
                           data-placement="bottom" 
                           title="Voir infos">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d = "M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 
                                0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg >
                    </button >
                </td>
                <td>
                    <span class="text-muted font-13">Interv</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Heure</span>
                    <br>
                    <span class="text-muted fs-2">' . $rdv . '</span>
                </td>
                <td class="text-center">
                    <img src="../public/assets/img/logo/' . strtoupper($nomMarque) . '.png" alt="logo_marque">
                </td>                
                <td>
                    <span class="text-muted font-13">Modèle</span>
                    <br>
                    <span class="text-muted fs-2">' . $modele . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $immat . '</span>
                </td>';
        if ($date == $currentDate) {
            $load .= '
                    <td>
                        <button onclick="priseEnCharge(' . $interv . ')" 
                                type="button" class="border-0 bg-transparent" 
                                data-toggle = "tooltip" 
                                data-placement= "bottom" 
                                title = "Prise en charge">
                            <i class="fa-solid fa-circle-arrow-right fa-xl text-info" ></i>
                        </button>
                    </td> 
                ';
        }
        $load .= '
                <td>
                    <button onclick="deleteRdv(' . $interv . ')" 
                            id="deleteRdv" 
                            type="button" 
                            class="border-0 bg-transparent" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="Supprimer intervention">
                        <i class="fa-solid fa-xmark text-danger fa-xl"></i>
                    </button>          
                </td>
            </tr>
        ';

        return $load;
    }

    public static function loadInterventionsEnCours($interv, $heure, $nomTech, $nomMarque, $immat)
    {
        $nomMarque = strtoupper($nomMarque);
        $rdv = date('H:i', $heure);
        return '
            <tr>
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Heure</span>
                    <br>
                    <span class="text-muted fs-2">' . $rdv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Technicien</span>
                    <br>
                    <span class="text-muted fs-2">' . $nomTech . '</span>
                </td>
                <td class="text-center">
                    <img src="../public/assets/img/logo/' . strtoupper($nomMarque) . '.png" alt="logo_marque">
                </td>                
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $immat . '</span>
                </td>
                <td>
                   <a href="checklist.html?intervention=' . $interv . '">
                        <button type="button" class="checklist border-0 bg-transparent" data-toggle="tooltip" data-placement="bottom" title="Checklist CT">
                            <i class="fa-solid fa-clipboard-check fa-xl text-info"></i>
                        </button>
                   </a>
                </td>
                <td>
                   <button onclick="switchToHold(' . $interv . ')" type="button" class="border-0 bg-transparent" data-toggle="tooltip" data-placement="bottom" title="Basculer en attente">
                        <i class="fa-solid fa-xl fa-arrow-rotate-left"></i>
                   </button>
                </td>
            </tr>';
    }

    public static function loadTerminesCTOK($interv, $nomClient, $telClient, $immat): string
    {
        return '
            <tr>
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Client</span>
                    <br>
                    <span class="text-muted fs-2">' . $nomClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Téléphone</span>
                    <br>
                    <span class="text-muted fs-2">' . $telClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $immat . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Status</span>
                    <br>
                    <span onclick="showContreVisite(' . $interv . ')" type="button" class="badge rounded-pill bg-success bg-opacity-25 text-success" >Validé</span>
                </td>
            </tr> 
        ';
    }

    public static function loadTerminesAnnule($interv, $nomClient, $telClient, $immat): string
    {
        return '
            <tr>
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Client</span>
                    <br>
                    <span class="text-muted fs-2">' . $nomClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Téléphone</span>
                    <br>
                    <span class="text-muted fs-2">' . $telClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $immat . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Status</span>
                    <br>
                    <div class="badge rounded-pill bg-danger bg-opacity-25 text-danger fw-bold" >Annulé</div >
                </td>
            </tr> 
        ';
    }

    public static function loadTerminesContreVisite($interv, $nomClient, $telClient, $immat): string
    {
        return '
            <tr>
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-2">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Client</span>
                    <br>
                    <span class="text-muted fs-2">' . $nomClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Téléphone</span>
                    <br>
                    <span class="text-muted fs-2">' . $telClient . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-2">' . $immat . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Status</span>
                    <br>
                    <div 
                        onclick="showContreVisite(' . $interv . ')" 
                        type="button"  
                        class="badge rounded-pill bg-warning bg-opacity-25 text-warning"> 
                        Contre - Visite
                    </div>
                </td>
            </tr> 
        ';
    }

    public static function loadCarsRecap($nomMarque, $modele, $immat, $id_vehicule)
    {
        $nomMarque = strtoupper($nomMarque);
        return '
            <tr>
                <td onclick="showInfoCar(' . $id_vehicule . ')" style="cursor: pointer;" class="text-center">
                    <img src="../public/assets/img/logo/' . strtoupper($nomMarque) . '.png" alt="logo_marque">
                </td>
                <td onclick="showInfoCar(' . $id_vehicule . ')" style="cursor: pointer">
                    <span class="text-muted font-13">Modèle</span>
                    <br>
                    <span class="text-muted fs-1">' . $modele . '</span>
                </td>
                <td onclick="showInfoCar(' . $id_vehicule . ')" style="cursor: pointer">
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-1">' . $immat . '</span>
                </td>
                <td>
                    <a id = "addCG" onclick= "modalCG(' . $id_vehicule . ')" class="border-0 bg-transparent" type="button" data-toggle = "tooltip" data-placement = "bottom" title = "Ajouter une carte grise" >
                        <i class="fa-solid fa-file-circle-plus text-info"></i>
                    </a>
                    <a id = "addCG" onclick= "deleteCar(' . $id_vehicule . ')" class="border-0 bg-transparent ps-lg-1" type = "button" data-toggle = "tooltip" data-placement = "bottom" title = "Supprimer ce véhicule" >
                        <i class="fa-solid fa-trash text-dark"></i>
                    </a>
                </td>
            </tr> 
        ';
    }

    public static function loadRdvRecap($interv, $state, $immat, $idCT): string
    {
        $html = '
            <tr>
                <td>
                    <span class="text-muted font-13">Date</span>
                    <br>
                    <span class="text-muted fs-1">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-1">' . $immat . '</span>
                </td>
                <td>
                <span class="text-muted font-13">Status</span>
                    <br>';
        if ($state == 0) {
            $html .= '
                <div class="badge rounded-pill text-secondary bg-soft-secondary">
                En attente
                </div>
                </td>
            ';
        } else {
            $html .= '
                <div class="badge rounded-pill bg-soft-info text-info">
                Pris en charge
                </div>
                </td>
            ';
        }

        $html .= '
                <td>
                    <button onclick="deleteRdvUser(' . $idCT . ')" 
                            id="deleteRdv" 
                            type="button" 
                            class="border-0 bg-transparent" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="Supprimer intervention">
                        <i class="fa-solid fa-xmark text-danger fa-xl"></i>
                    </button>
                </td>
            </tr> 
        ';

        return $html;
    }

    public static function loadHistory($interv, $heure, $tech, $immat, $state): string
    {
        $html = '
            <tr>
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-1">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Date</span>
                    <br>
                    <span class="text-muted fs-1">' . $heure . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Technicien</span>
                    <br>
                    <span class="text-muted fs-1">' . $tech . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-1">' . $immat . '</span>
                </td>
            ';
        if ($state == 2) {
            error_log(2);
            $html .= '
                    <td>
                        <span class="text-muted font-13">Status</span>
                        <br>
                        <span onclick="showContreVisite(' . $interv . ')" type="button" class="badge rounded-pill bg-success bg-opacity-25 text-success" >Validé</span>
                    </td>
                </tr>
            ';
        } else if ($state == 3) {
            error_log(3);
            $html .= '
                    <td>
                        <span class="text-muted font-13">Status</span>
                        <br>
                        <span onclick="showContreVisite(' . $interv . ')" type="button"  class="badge rounded-pill bg-warning bg-opacity-25 text-warning"> Contre - Visite</span >
                    </td >
                </tr > 
            ';
        } else if ($state == 4) {
            error_log(4);
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

    public static function newPwd(): string
    {
        return "
            <div class='row min-vh-100 flex-center gx-6'>
                <div class='col-lg-9 col-xxl-6 py-3 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-5'>
                                            <img src='../public/assets/img/logoLight.png'
                                                class='ogo_agence img-fluid'
                                                style='width:70%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mt-3 mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='./contact-us.html'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-3'>Mise à jour <br> mot de passe</h4>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form class='b-3 d-flex flex-column gap-3' action='javascript:newPwd();' method='POST'>
                                                    <input
                                                        id='user'
                                                        name='user'
                                                        type='email'
                                                        autocomplete='current-email'
                                                        required
                                                        class='field form-control rounded border border-1'
                                                        placeholder='Login utilisateur(email)'
                                                    />                   
                                                    <input
                                                        id='password'
                                                        name='password'
                                                        type='password'
                                                        autocomplete='current-password'
                                                        class='field form-control rounded border border-1'
                                                        placeholder='Nouveau mot de passe'
                                                    />
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='newPwd' type='submit' name='submit'>Modifier</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public static function secondAuth(): string
    {
        return "
            <div class='row min-vh-100 flex-center gx-6'>
                <div class='col-lg-9 col-xxl-6 py-3 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-5'>
                                            <img src='../public/assets/img/logoLight.png'
                                                class='logo_agence img-fluid'
                                                style='width:70%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mt-3 mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='./contact-us.html'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-3'>Renseignez votre code SMS</h4>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form class='b-3 d-flex flex-column gap-3' action='javascript:smsVerif();'  method='POST'>
                                                    <input
                                                        id = 'sms_verif'
                                                        name = 'sms_verif'
                                                        type = 'text'
                                                        class='field form-control rounded border border-1'
                                                        placeholder = 'Entrez le code sms reçu.'/>
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='sub_sms' type='submit' name='submit'>Valider</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public static function mailSending($mail): string
    {
        return "
            <div class='col-md-7 d-flex flex-center'
                <div class='p-4 p-md-5 flex-grow-1'>
                    <div class='text-center'><img class='d-block mx-auto mb-4' src='../public/assets/img/icons/spot-illustrations/16.png' alt='Email' width='100' />
                        <h3 class='mb-2'>Merci de consulter vos mails!</h3>
                        <p>Un mail a été envoyé à l'adresse <strong>$mail</strong>. Veuillez cliquer sur le lien <br class='d-none d-sm-block d-md-none' />inclus pour réinitialiser votre mot de passe.</p>
                        <a class='btn btn-primary btn-sm mt-3' id='reload'><span class='fas fa-chevron-left me-1' data-fa-transform='shrink-4 down-1'></span>Retour au login</a>
                    </div>
                </div>
            </div>
        ";
    }

    public static function toRequestMail(): string
    {
        return "
            <div class='row min-vh-100 flex-center gx-6'>
                <div class='col-lg-9 col-xxl-6 py-2 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-4'>
                                            <img src='../public/assets/img/logoLight.png'
                                                class='logo_agence img-fluid'
                                                style='width:60%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='./contact-us.html'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-0'> Vous avez oublié votre mot de passe?</h4>
                                            <p class='mb-4'>Renseignez votre mail et nous vous enverrons un lien de réinitialisation.</p>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form class='b-3' action='javascript:genToken();' method='POST'>
                                                    <input id = 'email'
                                                        name = 'email'
                                                        type = 'email'
                                                        autocomplete = 'current-email'
                                                        required
                                                        class='field form-control rounded border border-1'
                                                        placeholder = 'Login utilisateur (email)'/>
                                                    <div class='mb-3'></div>
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='sendToken' type='submit' name='submit'>Envoyer le lien</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    // laissé en commentaire, mais plus trop d'intérêt vu que les mails fonctionnent.
    /*public static function genToken()
    {
        return "
            /*<div>
                <h2 class='mt-6 text-center text-3xl font-extrabold text-success'>
                    Lien vers votre page de <br> modification du mot de passe
                </h2>
            </div>
            <div class='mt-5'>
                <div>
                    <button
                        type = 'submit'
                        class='group relative w-100 d-flex justify-content-center py-2 px-4 border border-success text-sm font-medium rounded text-white bg-success'
                        id = 'tokenLink'> 
                        Token  
                    </button>
                </div>
            </div>*/
    /*";*/
    /* }*/


    public static function listeVideUser(): string
    {
        return '<p class="col text-center py-2 fs-2 text-secondary">Aucun véhicules</p>';
    }

    public static function listeVideRecap(): string
    {
        return '<p class="col text-center py-2 fs-2 text-secondary fst-italic">Aucun rendez-vous programmés</p>';
    }

    public static function listeVideHistory(): string
    {
        return '<p class="col text-center py-2 fs-2 text-secondary fst-italic">Aucun historique disponible</p>';
    }

    public static function rdvVide(): string
    {
        return " <p class='col text-center py-2 fs-2 text-secondary fst-italic' > Pas de rendez - vous programmés </p > ";
    }

    public static function listeVide(): string
    {
        return '<p class="col fst-italic py-2 fs-2 text-secondary text-center fst-italic">Aucun véhicule en attente</p>';
    }

    public static function intervVide(): string
    {
        return " <p class='col text-center py-2 fs-2 text-secondary fst-italic' > Pas d'interventions en cours</p>";
    }

    public static function fermeture(): string
    {
        return "<p class='col text-center py-2 fs-2 text-secondary fst-italic'>Nous sommes fermés</p>";
    }

    public static function rdvPages($page, $numb, $state): string
    {
        $paginationHTML = "";
        if ($state == 0) {
            $paginationHTML = "              
                <li class='page-item' id='pageH$numb'>
                    <a role='button' class='page-link' onclick='pageRefresh($page)'>$numb</a>
                </li>              
            ";
        } else if ($state == 1) {
            $paginationHTML = "               
                <li class='page-item' id='pageP$numb'>
                    <a role='button' class='page-link' onclick='loadIntervEnCours($page)'>$numb</a>
                </li>               
            ";
        } else if ($state >= 2) {
            $paginationHTML = "                
                <li class='page-item' id='pageO$numb'>
                    <a role='button' class='page-link' onclick='loadTermines($page)'>$numb</a>
                </li>                
            ";
        }
        return $paginationHTML;
    }

    public static function historyUserPages($page, $numb): string
    {
        return "       
            <li class='page-item' id='pageMyH$numb'>
                <a role='button' class='page-link' onclick='carsRecap($page)'>$numb</a>
            </li>                
        ";
    }

    /*    public static function cardCar(): string
        {
            return "
            <div id='' class='card border - success mb - 3' style='max - width: 20rem;'>
                <div class='card - header'>Header</div>
                <div class='card - body'>
                  <h4 class='card - title'>Success card title</h4>
                  <p class='card - text'>Some quick example text to build on the card title and make up the bulk of the card's
                    content .</p >
                </div >
            </div >
        ";
        }*/


}