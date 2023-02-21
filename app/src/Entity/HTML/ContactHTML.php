<?php

class ContactHTML
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

    public static function mapContent($coordinates, $htmlContent): array
    {
        $tab_html = [];

        foreach ($coordinates as $key => $values) {
            $html = '<div id="content">
                <div class="d-flex flex-row gap-2 align-items-center justify-content-around" id="siteNotice">
                    <img class="align-middle" src="../public/assets/img/logoDark.png" style="width: 5rem" alt="logo-contact">
                    <h4 id="firstHeading" class="firstHeading">
                    ';
            $key == 'aflo_bia' ? $html .= '<span style="color:lightskyblue;">Bastia' : $html .= '<span style="color:lightcoral;">Ajaccio';
            $html .= '</span></h4>
                </div>
                <div id="bodyContent">
                    <p>
                    ' . $values['addr'] . '
                    </p>
                    <p><b>Horaires : </b><br>
                    ' . $htmlContent . '
                    dimanche Fermé<br>
                    <p>Site : <a target="_blank" href="https://www.aflokkat.com/">https://www.aflokkat.com/</a>
                </div>
            </div>';

            $tab_html[$key] = $html;
        }

        return $tab_html;
    }
}