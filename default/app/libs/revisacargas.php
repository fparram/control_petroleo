<?php
Load::model('vehiculos');

class revisacargas {

    function __construct() {
        
    }
    public function revisapatente($dataPatente){
        $mesactual = date('m');
        $vehiculos = new Vehiculos();
        if ($dataPatente->mes < $mesactual) {
            
            $vehiculos->reseteaConteo($mesactual);
            if ($dataPatente->limite == 0) {
                return 0;
            }  else {
                return $dataPatente->limite - $dataPatente->conteo;
            }            
        }  else {
            if ($dataPatente->limite == 0) {
                return 0;
            }  else {
                return $dataPatente->limite - $dataPatente->conteo;
            }
        }
    }
    public function fechaInicial($mes) {
        $year = date('Y');
        switch ($mes){
            case 1:                
                return date('31-01-Y');               
            case 2:               
                return date('28-02-Y');
            case 3:                
                return date('31-03-Y');              
            case 4:                
                return date('30-04-Y');              
            case 5:               
                return date('31-05-Y');              
            case 6:
                return date('30-06-Y');               
            case 7:
                return date('31-07-Y');                
            case 8:
                return date('31-08-Y');             
            case 9:
                return date('30-09-Y');               
            case 10:
                return date('31-10-Y');               
            case 11:
                return date('30-11-Y');               
            case 12:
                return date('31-12-Y');                    
        }
    }

}