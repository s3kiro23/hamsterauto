<?php

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

//Load Composer's autoloader
require __DIR__.'/../../vendor/autoload.php';

class PDF extends TCPDF
{
    public function generate_PDF($data, $userHash): string
    {
        $outputFile = "";
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Aflauto');
        $pdf->SetTitle('Procès-verbale');

        // set default header data
        $pdf->SetHeaderData(null, null, null, null, array(0, 0, 0), array(255, 255, 255));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(1, 1, 1, 1);

        // set color for background
        $pdf->SetFillColor(120, 183, 255);

        // Multicell pour générer les tableaux

        //QRCODE
        $style = array(
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $pdf->write2DBarcode('https://hamsterauto.com/', 'QRCODE,H', 4, 0, 30, 30, $style, 'N');

        //Header
        $pdf->writeHTMLCell(85, 2, '62', 10, $data['header_title'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(46, 2, '157', 0, $data['header_logo'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(46, 2, '157', 25, $data['header_sub'], 0, 0, 0, true, 'C', true);

        //Body
        $pdf->writeHTMLCell(75, 2, '5', 30, $data['nature'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(46, 2, '81', 30, $data['date'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(75, 2, '128', 30, $data['nb_pv'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 42, $data['id_center'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 75.5, $data['id_tech'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 104, $data['info_ct_notOK'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 127, $data['id_car'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 189, $data['info_client'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(85, 2, '5', 209.5, $data['result_CT'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(112, 2, '91', 42, $data['todo'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(112, 2, '91', 209.5, $data['measures'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(42, 2, '5.5', 227.5, $data['bg_thumbnail'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(42, 2, '6', 228.5, $data['thumbnail'], 0, 0, 0, true, 'C', true);
        $pdf->writeHTMLCell(15, 2, '53', 228.5, $data['tiny_thumbnail'], 0, 1, 1, true, 'C', true);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // move pointer to last page
        $pdf->lastPage();
        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output($data['path'] . $data['nb_agrement'] . '_' . $data['PV'] . '.pdf', 'F');

        //Encryption file
        $path_file = $data['path'] . $data['nb_agrement'] . '_' . $data['PV'] . '.pdf';
        $pdf_content = file_get_contents($path_file);
        $encrypted_content = Security::encrypt($pdf_content, $userHash);
        file_put_contents($path_file, $encrypted_content);

        //============================================================+
        // END OF FILE
        //============================================================+

        return 'pv_' . $data['nb_agrement'] . '_' . $data['PV'] . '.pdf';
    }

    public function pv($car, $CT, $client): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $report = json_decode($CT->getReport(), true);
        $nb_report = 0;
        $todo_list = "";
        if (!is_null($report)) {
            foreach ($report as $key => $value) {
                $todo_list .= $value . '<br>';
                $nb_report = count($report);
            }
        }

        $date_timestamp = $CT->getTime_slot();
        $nb_agrement = "S654789741";
        $rdv_date = date("d/m/Y", $date_timestamp);
        $next_date = date("d/m/Y", $date_timestamp + 63097119);
        $thumbnail_date = date("m/y", $date_timestamp + 63097119);
        $thumbnail_day = date("d", $date_timestamp + 63097119);
        $PV = $CT->getId_intervention();
        $tech = new User($CT->getId_user());
        $brand = new Brand($car->getId_brand());
        $path = ROOT_DIR().'/var/generate/minutes/pv_';

        $header_title = '
            <table style="text-align: center; font-size: xx-small;" border="0" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="font-size: larger; font-weight: bolder; color: royalblue;">' . mb_strtoupper("procès-verbal de contrôle technique d'un véhicule automobile") . '</td>
                </tr>
            </table>
            ';

        $header_logo = '
            <table style="text-align: center; font-size: xx-small;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <img src="' . ROOT_DIR() . '/public/assets/img/logoDark.png" alt="" width="110" height="90" border="0">
                    </td>
                </tr>
            </table>
            ';

        $header_sub = '
            <table style="text-align: center; font-size: xx-small;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                   <td style="font-weight: bolder; color: #4bbf73;">' . mb_strtoupper("exemplaire remis à l'usager") . '</td> 
                </tr>
            </table>
            ';

        $nature = '
            <table style="text-align: center; font-size: xx-small;" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white;">' . mb_strtoupper("nature du contrôle") . '</td>
                </tr>
                <tr>
                    <td>Visite technique périodique</td>
                </tr>
            </table>
            ';
        $date = '
            <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                    <tr>
                        <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("date du contrôle") . '</td>
                    </tr>
                    <tr>
                        <td>' . $rdv_date . '</td>
                    </tr>
                </table>
            ';
        $nb_pv = '
            <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                    <tr>
                        <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("n° du procès-verbal") . '</td>
                    </tr>
                    <tr>
                        <td>' . $CT->getId_intervention() . '</td>
                    </tr>
                </table>
            ';
        $id_center = '
        <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("identification de l'installation de contrôle") . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <b>' . mb_strtoupper("n° d'agrément") . ':</b> ' . $nb_agrement . '
                        <br><br>
                        <b>RAISON SOCIAL :</b> <span>Centre technique</span>
                        <br><br>
                        <b>ADRESSE:</b>
                        <br>
                        <span>Centre professionnel A Murza</span><br>
                        <span>Chem. de Canale</span><br>
                        <span>20600 Furiani</span>
                        <br>
                    </td>
                </tr>
            </table>
        ';
        $id_tech = '
        <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("identité du contrôleur") . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <b>NOM & ' . mb_strtoupper("prénom") . ' :</b> <span>' . $tech->getLastname_user() . ' ' . $tech->getLastname_user() . '</span>
                        <br><br>
                        <b>' . mb_strtoupper("n° d'agrément") . ' :</b> <span>' . $tech->getId_user() . '</span>
                        <br><br>
                        <b>SIGNATURE :</b>
                        <span></span>
                        <br><br>
                    </td>
                </tr>
            </table>
        ';
        $info_ct_notOK = '
        <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("informations sur la visite technique périodique défavorable") . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <b>' . mb_strtoupper("procès-verbal n°") . ' :</b> <span></span>
                        <br><br>
                        <b>DATE :</b> <span></span>
                        <br><br>
                        <b>' . mb_strtoupper("n° d'agrément de l'installation") . ' :</b> <span></span>
                    </td>
                </tr>
            </table>
        ';
        $id_car = '
        <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; ">' . mb_strtoupper("identification du véhicule") . '</td>
                </tr>
                <tr>
                    <td>
                        <table style="text-align: center; vertical-align: bottom" border="0" cellspacing="3" cellpadding="4">
                            <tr>
                                <td ><b>N° Immatriculation</b></td>
                                <td><b>Date d\'immatriculation</b></td>
                                <td><b>Date de 1ère mise en circulation</b></td>
                            </tr>
                            <tr>
                                <td style="border: 0.5px solid black">' . $car->getRegistration() . '</td>
                                <td style="border: 0.5px solid black">?</td>
                                <td style="border: 0.5px solid black">' . $car->getFirst_release() . '</td>
                            </tr>
                            <tr>
                                <td><b>Genre</b></td>
                                <td><b>Marque</b></td>
                                <td><b>Type</b></td>
                            </tr>
                            <tr>
                                <td style="border: 0.5px solid black">?</td>
                                <td style="border: 0.5px solid black">' . $brand->getBrand_name() . '</td>
                                <td style="border: 0.5px solid black">?</td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>N° dans la série du type</b></td>
                                <td><b>Energie</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 0.5px solid black"> ? </td>
                                <td style="border: 0.5px solid black">' . $car->getFuel() . '</td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Kilométrage inscrit au compteur</b></td>
                                <td><b>Désignation commerciale</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 0.5px solid black">?</td>
                                <td style="border: 0.5px solid black">' . $car->getFirst_release() . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';
        $info_client = '
        <table style="font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; text-align: center;">TITULAIRE DU CERTIFICAT D\'IMMATRICULATION</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <b>NOM, ' . mb_strtoupper("prénom") . ' OU RAISON SOCIAL :</b> <span>' . $client->getLastname_user() . ' ' . $client->getFirstname_user() . '</span>
                        <br><br>
                        <b>ADRESSE :</b> <span>' . $client->getAdress_user() . '</span>
                        <br>
                    </td>
                </tr>
            </table>
        ';

        $result_CT = '
            <table style="text-align: center; font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; text-align: center;">' . mb_strtoupper("résultat du contrôle technique") . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        NATURE ET DATE DU PROCHAIN ' . mb_strtoupper("contrôle") . ' :';
        if (is_null($report)) {
            $result_CT .= '
                        <br><br>
                        <b>VISITE TECHNIQUE ' . mb_strtoupper("périodique") . ' AU PLUS TARD LE ' . $next_date . '</b>
                    </td>
                </tr>
            </table>';
        } else {
            $result_CT .= '
                    </td>
                </tr>
            </table>';
        }

        $todo = '
        <table style="font-size: xx-small; height: 300px" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; text-align: center; ">DEFAUTS OU ANOMALIES ' . mb_strtoupper("constatées") . '</td>
                </tr>
                <tr>
                    <td style="text-align: left; height: 566px">
                        <b style="text-decoration: underline">Procès-verbal</b>
                        <br>
                        Document(s) présenté(s) : Certificat d\'immatriculation
                        <br>
                        Version du logiciel : 1.55
                        <br><br>
                        <b>1 - Défauts à corriger avec contre visite : </b><span>' . $nb_report . '</span>
                        <br><br>
                        <span>' . $todo_list . '</span>
                        <br><br>
                        <b>2 - Défauts à corriger sans contre visite : </b> 0
                        <br><br>
                    </td>
                </tr>
            </table>
        ';

        $thumbnail = '
            <table style="font-size: xx-small;" border="0" cellspacing="0">';
        if (is_null($report)) {
            $thumbnail .= '
                        <tr>
                            <td style="text-align: center;">
                                <br><br>
                                <b style="font-size: larger;">' . $car->getRegistration() . '</b>
                                <br><br><br>
                                <b style="font-size: 10rem;">' . $thumbnail_day . ' </b><b style="font-size: 17rem;">' . $thumbnail_date . '</b>
                                <br><br>
                                <div style="text-align: left;">
                                    <b style="font-size: 5.7rem">N° d\'agrément : ' . $nb_agrement . '</b>
                                    <br>
                                    <b style="font-size: 5.7rem">N° de série : <span>VF 48787987</span></b>
                                    <br>
                                    <b style="font-size: 5.7rem">N° d\'imprimé : . <span>S14554655</span></b>
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                ';
        } else {
            $thumbnail .= '
                    <tr><td></td></tr>
                </table>
                ';
        }

        $bg_thumbnail = '
                <img src="' . ROOT_DIR() . '/public/assets/img/background_thumbnail_ct.png" alt="" width="130" height="130" border="0">
            ';
        $tiny_thumbnail = '
            <table style="font-size: xx-small; background-color: #bdd7ff;" border="0" cellspacing="0" cellpadding="2">';
        if (is_null($report)) {
            $tiny_thumbnail .= '
                    <tr>
                        <td style="text-align: center;">
                            <b style="font-size: medium;">Afl<span style="color: #4bbf73;">A</span>uto</b>
                            <br>
                            <b style="font-size: small;">' . $next_date . '</b>
                            <br>
                            <span style="font-size: small;"> ' . $nb_agrement . '</span>
                        </td>
                    </tr>
                </table>
            ';
        } else {
            $tiny_thumbnail .= '
                    <tr><td></td></tr>
                </table>
            ';
        }

        $measures = '
            <table style="font-size: xx-small" border="0.5" cellspacing="0" cellpadding="4">
                <tr>
                    <td style="background-color: #4bbf73; color: white; text-align: center; ">MESURES</td>
                </tr>
                <tr>
                    <td style="text-align: center; ">
                        <table>
                            <tr>
                                <td style="text-align: center; "></td>
                                <td style="text-align: center; "><b>AVANT</b></td>
                                <td style="text-align: center; "><b>ARRIERE</b></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Forces verticales (daN)</td>
                                <td>652</td>
                                <td>458</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Frein de service</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">Force (daN)</td>
                                <td>G: 261  D: 240</td>
                                <td>G: 136   D: 107</td>
                            </tr>
                             <tr>
                                <td style="text-align: center; ">Déséquilibre (%)</td>
                                <td>8</td>
                                <td>21</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">Force (daN)</td>
                                <td>G: ---  D: ---</td>
                                <td>G: ---  D: ---</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">Efficacité (%)</td>
                                <td colspan="2">67</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Eff. frein stationnement (%)</td>
                                <td colspan="2">67</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Efficacité frein de secours (%)</td>
                                <td colspan="2">--</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Suspension</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">Déséquilibre (%)</td>
                                <td >4</td>
                                <td >4</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Ripage (m/km)</td>
                                <td>-14.0</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Feux de croisement (%)</td>
                                <td >G: -1.9  D: -1.1</td>
                                <td >h : < 0,8m</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Feux antibrouillard (%)</td>
                                <td>G: ---  D: ---</td>
                                <td>---</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; ">Pollution - Teneur en CO :</td>
                                <td>3.33 %</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';

        return array(
            "header_title" => $header_title,
            "header_logo" => $header_logo,
            "header_sub" => $header_sub,
            "nature" => $nature,
            "id_tech" => $id_tech,
            "id_center" => $id_center,
            "info_ct_notOK" => $info_ct_notOK,
            "info_client" => $info_client,
            "result_CT" => $result_CT,
            "id_car" => $id_car,
            "todo" => $todo,
            "date" => $date,
            "nb_pv" => $nb_pv,
            "PV" => $PV,
            "path" => $path,
            "PV_ID" => $CT->getId_intervention(),
            "thumbnail" => $thumbnail,
            "bg_thumbnail" => $bg_thumbnail,
            "tiny_thumbnail" => $tiny_thumbnail,
            "measures" => $measures,
            "nb_agrement" => $nb_agrement
        );
    }
}
