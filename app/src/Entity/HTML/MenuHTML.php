<?php

class MenuHTML
{
    public static function navBarType($user): array
    {
        if ($user->getType() == 'technicien') {
            $nav_items = "";
            $logo_link = 'technicien';
        } else {
            $nav_items = "
                <li class='nav-item m-0'>
                    <a class='nav-link fw-bold text-primary' id='formClient' role='button'>Prise de RDV</a>
                </li>
            ";
            $logo_link = 'client';
        }
        return array($nav_items, $logo_link);
    }
}