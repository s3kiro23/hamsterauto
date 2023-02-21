<?php

class PaginationHTML
{
    public static function clientHistory($id): array
    {
        error_log(1);
        $html = "";
        $total_rdv = User::count_history(Security::decrypt($id, false));
        $total_pages = ceil($total_rdv / 5);
        for ($i = 1; $i <= $total_pages; $i++) {
            $html .= "
                <li class='page-item' id='pageMyA" . $i . "'>
                    <a role='button' class='page-link' onclick='loadUserArchives(" . $i . ")'>" . $i . "</a>
                </li>     
            ";
        }

        return array(
            'html' => $html,
            'total_rdv' => $total_rdv
        );
    }

    public static function dashTechPagination($state, $current_date): string
    {
        $html = "";
        $total_rdv = Intervention::count_rdv($state, $current_date);
        error_log($total_rdv);
        $total_pages = ceil($total_rdv / 5);
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($state == 0) {
                $html .= "              
                    <li class='page-item' id='pageH" . $i . "'>
                        <a role='button' class='page-link' onclick='loadAwaiting(" . $i . ", " . $current_date . ")'>" . $i . "</a>
                    </li>              
                ";
            } else if ($state == 1) {
                $html .= "               
                    <li class='page-item' id='pageP" . $i . "'>
                        <a role='button' class='page-link' onclick='loadInProgress(" . $i . ")'>" . $i . "</a>
                    </li>               
                ";
            } else if ($state == 2) {
                $html .= "                
                    <li class='page-item' id='pageA" . $i . "'>
                        <a role='button' class='page-link' onclick='loadArchives(" . $i . ")'>" . $i . "</a>
                    </li>                
                ";
            }
        }

        return $html;
    }

    public static function off7($page): int
    {
        return ($page - 1) * 5;
    }
}