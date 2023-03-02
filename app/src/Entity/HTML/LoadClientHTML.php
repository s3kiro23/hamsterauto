<?php

class LoadClientHTML
{
    public static function carsRecap($brand_name, $model, $registration, $id_vehicle): string
    {
        $brand_name = strtoupper($brand_name);
        return '
            <tr style="cursor: pointer" class="text-center">
                <td>
                    <img src="/public/assets/img/logo/' . $brand_name . '.png" alt="' . $brand_name . '">
                </td>
                <td>
                    <span class="text-muted">' . $model . '</span>
                </td>
                <td>
                    <span class="text-muted">' . $registration . '</span>
                </td>
                <td>
                    <a class="text-decoration-none" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title = "Actions sur le véhicule">
                        <i class="fa-solid fa-ellipsis fa-xl"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button 
                                class="addCG dropdown-item"
                                type="button"
                                data-id=' . $id_vehicle . '>
                                📑 Ajout carte grise
                            </button>
                        </li>
                        <li>
                            <button 
                                class="modifyCar dropdown-item"
                                type="button"
                                data-id=' . $id_vehicle . '>
                                🖊️ Modifier
                            </button>
                        </li>
                        <li>                            
                            <button 
                                class="deleteCar dropdown-item"
                                type="button"
                                data-id=' . $id_vehicle . '>
                                🗑️ Supprimer
                            </button>
                        </li>
                    </ul>
                </td>
            </tr> 
        ';
    }

    public static function rdvRecap($interv, $state, $registration, $idCT): string
    {

        $html = '
            <tr style="cursor: pointer" class="text-center">
                <td>
                    <span class="text-muted">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted">' . $registration . '</span>
                </td>
                <td>
        ';
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
        $idInterv = Security::encrypt($idCT, false);
        $html .= "
                <td class='text-center'>
                    <button  onclick='deleteRdvUser(`$idInterv`)' 
                            id='deleteRdv' 
                            type='button'
                            class='deleteRdv border-0 bg-transparent' 
                            data-toggle='tooltip'
                            data-placement='bottom' 
                            title='Supprimer intervention'>
                        <i class='fa-solid fa-xmark text-danger fa-xl'></i>
                    </button>
                </td>
            </tr> 
        ";

        return $html;
    }

    public static function history($interv, $hour, $tech, $registration, $state): string
    {
        $id_archive = Security::encrypt($interv, false);
        $html = '
            <tr class="interventionModal">
                <td>
                    <span class="text-muted font-13">#</span>
                    <br>
                    <span class="text-muted fs-1">' . $interv . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Date</span>
                    <br>
                    <span class="text-muted fs-1">' . $hour . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Technicien</span>
                    <br>
                    <span class="text-muted fs-1">' . $tech . '</span>
                </td>
                <td>
                    <span class="text-muted font-13">Immatriculation</span>
                    <br>
                    <span class="text-muted fs-1">' . $registration . '</span>
                </td>
            ';
        if ($state == 2) {
            $html .= "
                    <td>
                        <span class='text-muted font-13'>Status</span>
                        <br>
                        <span onclick='showContreVisite(`$id_archive`)' type='button' class='badge rounded-pill bg-success bg-opacity-25 text-success' >Validé</span>
                    </td>
                </tr>
            ";
        } else if ($state == 3) {
            $html .= "
                    <td>
                        <span class='text-muted font-13'>Status</span>
                        <br>
                        <span onclick='showContreVisite(`$id_archive`)' type='button'  class='badge rounded-pill bg-warning bg-opacity-25 text-warning'> Contre - Visite</span >
                    </td >
                </tr > 
            ";
        } else if ($state == 4) {
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
}
