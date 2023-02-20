<?php

class Convert
{

    static public function current_date()
    {
        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $formatter->setPattern('d-MM-Y');
        return $formatter->format(new DateTime());
    }

    static public function date_to_fullFR($data = null)
    {
        $data ? $data = date('d-m-Y', $data) : $data;
        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $formatter->setPattern('EEEE d MMMM Y');
        return $formatter->format(new DateTime($data));
    }

    static public function to_datedropper_format($data = null)
    {
        $format = 'm-d-Y';
        return date($format, $data);
    }

    static public function check_year($data)
    {
        return !empty($data) ? date('Y', $data) : date('Y');
    }

}