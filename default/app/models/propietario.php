<?php

class Propietario extends ActiveRecord{
    public function initialize()
    {        
        $this->has_many('vehiculos');
    }
    function crearPvehiculo($datosPvehiculo){
        $pvehiculo = Load::model('propietario');        
        $pvehiculo->begin();
        
        try{
            $pvehiculo->create($datosPvehiculo);
            $pvehiculo->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $pvehiculo->rollback();
            return FALSE;
        }
    }
    function editPvehiculo($datosPvehiculo){
        $pvehiculo = Load::model('propietario');
        
        $pvehiculo->begin();
        try{
            $pvehiculo->update($datosPvehiculo);
            $pvehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $pvehiculo->rollback();
            return FALSE;
        }
    }
    function deletPvehiculo($id){
        $pvehiculo = Load::model('propietario');
        
        $pvehiculo->begin();
        
        try {
            $pvehiculo->delete($id);
            $pvehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $pvehiculo->rollback();
            return FALSE;
        }
    }
}