<?php

class fechas {

    function __construct() {
        
    }

    public function fechaInicial3($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-10-01');
            case 2:
                $year = $year - 1;
                return date($year . '-11-01');
            case 3:
                $year = $year - 1;
                return date($year . '-12-01');
            case 4:
                return date('Y-01-01');
            case 5:
                return date('Y-02-01');
            case 6:
                return date('Y-03-01');
            case 7:
                return date('Y-04-01');
            case 8:
                return date('Y-05-01');
            case 9:
                return date('Y-06-01');
            case 10:
                return date('Y-07-01');
            case 11:
                return date('Y-08-01');
            case 12:
                return date('Y-09-01');
        }
    }

    public function fechaFinal3($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-10-31');
            case 2:
                $year = $year - 1;
                return date($year . '-11-30');
            case 3:
                $year = $year - 1;
                return date($year . '-12-31');
            case 4:
                return date('Y-01-31');
            case 5:
                if (date('Y') == 2020 or date('Y') == 2024 or date('Y') == 2028 or date('Y') == 2032){
                    return date('Y-02-29');
                }  else {
                    return date('Y-02-28');
                }                
            case 6:
                return date('Y-03-31');
            case 7:
                return date('Y-04-30');
            case 8:
                return date('Y-05-31');
            case 9:
                return date('Y-06-30');
            case 10:
                return date('Y-07-31');
            case 11:
                return date('Y-08-31');
            case 12:
                return date('Y-09-30');
        }
    }

    public function fechaInicial2($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-11-01');
            case 2:
                $year = $year - 1;
                return date($year . '-12-01');
            case 3:
                return date('Y-01-01');
            case 4:
                return date('Y-02-01');
            case 5:
                return date('Y-03-01');
            case 6:
                return date('Y-04-01');
            case 7:
                return date('Y-05-01');
            case 8:
                return date('Y-06-01');
            case 9:
                return date('Y-07-01');
            case 10:
                return date('Y-08-01');
            case 11:
                return date('Y-09-01');
            case 12:
                return date('Y-10-01');
        }
    }

    public function fechaFinal2($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-11-30');
            case 2:
                $year = $year - 1;
                return date($year . '-12-31');
            case 3:
                return date('Y-01-31');
            case 4:
                if (date('Y') == 2020 or date('Y') == 2024 or date('Y') == 2028 or date('Y') == 2032){
                    return date('Y-02-29');
                }  else {
                    return date('Y-02-28');
                }
            case 5:
                return date('Y-03-31');
            case 6:
                return date('Y-04-30');
            case 7:
                return date('Y-05-31');
            case 8:
                return date('Y-06-30');
            case 9:
                return date('Y-07-31');
            case 10:
                return date('Y-08-31');
            case 11:
                return date('Y-09-30');
            case 12:
                return date('Y-10-31');
        }
    }

    public function fechaInicial1($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-12-01');
            case 2:
                return date('Y-01-01');
            case 3:
                return date('Y-02-01');
            case 4:
                return date('Y-03-01');
            case 5:
                return date('Y-04-01');
            case 6:
                return date('Y-05-01');
            case 7:
                return date('Y-06-01');
            case 8:
                return date('Y-07-01');
            case 9:
                return date('Y-08-01');
            case 10:
                return date('Y-09-01');
            case 11:
                return date('Y-10-01');
            case 12:
                return date('Y-11-01');
        }
    }

    public function fechaFinal1($mes) {
        $year = date('Y');
        switch ($mes) {
            case 1:
                $year = $year - 1;
                return date($year . '-12-31');
            case 2:
                return date('Y-01-31');
            case 3:
                if (date('Y') == 2020 or date('Y') == 2024 or date('Y') == 2028 or date('Y') == 2032){
                    return date('Y-02-29');
                }  else {
                    return date('Y-02-28');
                }
            case 4:
                return date('Y-03-31');
            case 5:
                return date('Y-04-30');
            case 6:
                return date('Y-05-31');
            case 7:
                return date('Y-06-30');
            case 8:
                return date('Y-07-31');
            case 9:
                return date('Y-08-31');
            case 10:
                return date('Y-09-30');
            case 11:
                return date('Y-10-31');
            case 12:
                return date('Y-11-30');
        }
    }

    public function traduceMes($mes) {        
        switch ($mes) {
            case 1:
                return "Enero";
            case 2:
                return "Febrero";
            case 3:
                return "Marzo";
            case 4:
                return "Abril";
            case 5:
                return "Mayo";
            case 6:
                return "Junio";
            case 7:
                return "Julio";
            case 8:
                return "Agosto";
            case 9:
                return "Septiembre";
            case 10:
                return "Octubre";
            case 11:
                return "Noviembre";
            case 12:
                return "Diciembre";
            case 0:
                return "Diciembre";
            case -1:
                return "Noviembre";
            case -2:
                return "Octubre";
        }        
    }

}
