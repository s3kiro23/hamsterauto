<?php

namespace HTML;
use Security;

class GenerateDateHTML
{
    public static function formClient($date, $timeStampDate): string
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
            <input type='button' id='date-input' value='$date' class='text-sm-center currentDate'> 
                <i type='button' onClick='changeDate($nextDate);' class='fa-solid fa-angles-right position-absolute top-50 end-0 translate-middle-y pe-1'></i>
            </div>
            <div id='panel' class='$timeStampDate bg-secondary bg-opacity-10 p-3 text-center rounded-bottom'>
                <!--Génération des créneaux disponible ici-->
            </div>
        ";

        return $dayCase;
    }

    public static function dashTech($date, $timestampDate): array
    {
        $currentDate = strtotime(date('d-m-Y'));
        $nextDate = Security::encrypt($timestampDate + 86400, false);
        $previousDate = Security::encrypt($timestampDate - 86400, false);
        $btnPrevious = "";
        if ($timestampDate != $currentDate) {
            $btnPrevious = "
                <button
                    onClick='switchDayRdv(`$previousDate`);' 
                    class='changeDate fa-solid fa-circle-left fa-xl align-middle bg-transparent border-0 ms-2 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Jour précédent'>
                </button>
            ";
        }
        $currentDay = "
                <span id='$timestampDate' class='currentDate text-sm-center fs-2 text-dark'>$date<span/>
            ";
        $btnNext = "
                <button
                    onClick='switchDayRdv(`$nextDate`);' 
                    class='changeDate fa-solid fa-circle-right fa-xl align-middle bg-transparent border-0 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Jour suivant'>
                </button>
            ";
        $btnBack = "
                <button
                    onClick='switchDayRdv($currentDate);' 
                    class='changerDate currentDate fa-solid fa-reply fa-xl align-middle bg-transparent border-0 text-dark' 
                    data-toggle='tooltip' 
                    data-placement='bottom' 
                    title='Revenir au début'>
                </button>
            ";
        return array(
            "currentDay" => $currentDay,
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

}