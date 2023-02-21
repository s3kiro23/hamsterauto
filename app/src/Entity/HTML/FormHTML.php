<?php

class FormHTML
{
    public static function addCar($marque): string
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
                            <select class='field form-select' id='selectMarque'>
                                $marque
                            </select>
                            <div class='invalid-feedback'></div>
                        </div>
                        <div class='col-5'>
                            <label for='selectedModel' class='form-label text-dark'>Sélectionner un modèle</label>
                            <select class='field form-select' id='selectedModel'>
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
                <div class='mb-3 mt-2 form-label text-dark' style='font-weight: 500'>Première mise en circulation</div>
                <div class='form-group position-relative'>
                    <input
                        type='text'
                        class='field form-control'
                        id='inputYear'
                        placeholder=' '/>        
                    <label for='inputYear' class='form-label-group m-0'>Année</label>
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

    public static function modifyCar($data): string
    {
        $html = "         
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
                               placeholder='AA-456-AA'
                               pattern='^[A-Z]{2} ?- ?\d{3} ?- ?[A-Z]{2}$'>
                    </div>
                    <div class='invalid-feedback'></div>
                    <div class='input-group mb-3 licenseplate' id='licence-plate-old' data-country='EU' hidden>
                        <label for='inputImmatOld'
                               class='sr-only'>Immatriculation</label>
                        <input type='text'
                               class='form-control'
                               id='inputImmatOld'
                               value=''
                               aria-describedby='textHelp'
                               placeholder='1234-AA-0A'
                               pattern='^[0-9]{1,4} ?- ?[A-Z]{1,4} ?- ?[0-9]{1,2}$'>
                    </div>
                    <div class='invalid-feedback'></div>
                    <div class='d-flex flex-row justify-content-between'>
                        <div class='col-5'>
                            <label for='selectMarque' class='form-label text-dark'>Sélectionner une marque</label>
                            <select class='field form-select' id='selectMarque'>
                                " . $data['brand'] . "
                            </select>
                            <div class='invalid-feedback'></div>
                        </div>
                        <div class='col-5'>
                            <label for='selectedModel' class='form-label text-dark'>Sélectionner un modèle</label>
                            <select class='field form-select' id='selectedModel'>
                                " . $data['model'] . "
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
                                value='Essence'";
        if ($data['fuel'] == 'Essence'){
            $html .= 'checked';
        }
        $html .= " />
                            <label class='form-check-label text-dark fw-bold fst-italic' for='Essence'>Essence</label>
                        </div>
                        <div class='form-check'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name='optionsCarbu'
                                id='Diesel'
                                value='Diesel'";
        if ($data['fuel'] == 'Diesel'){
            $html .= 'checked';
        }
        $html .= " />
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
                                value='Electrique'";
        if ($data['fuel'] == 'Electrique'){
            $html .= 'checked';
        }
        $html .= " />
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
                                value='Hybride'";
        if ($data['fuel'] == 'Hybride'){
            $html .= 'checked';
        }
        $html .= " />
                            <label class='form-check-label text-dark fw-bold fst-italic' for='Hybride'>Hybride</label>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <label for='inputYear' class='form-label mt-4 text-dark'>Année de 1ère mise en circulation</label>
                    <input
                      type='text'
                      class='field form-control'
                      id='inputYear'
                      placeholder='Année ici'/>        
                <div class='invalid-feedback'></div>          
                </div>
                <div class='d-flex justify-content-center'>
                    <button type='submit' id='validateFormCar'
                        class='btn btn-primary p-2 mt-5 mb-1 rounded w-100'>
                    Modifier
                    </button>
                </div>
            </div>
    ";

        return $html;
    }

    public static function addRDV($car_user): string
    {

        return "
            <div class='rounded p-3'>
                <div class='form-group'>
                    <label for='selectCars' class='form-label text-dark'>Sélectionner un véhicule :</label>
                    <select class='field form-select' id='selectCars' name='selectCars' required>
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

}