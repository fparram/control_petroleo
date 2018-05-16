<?php

class Tvehiculos extends ActiveRecord{
    public function initialize()
    {
        //Relaciones
        //Un usuario tiene una unidad de negocio de cada una
        $this->has_many('vehiculos');
        $this->belongs_to('macrotipo');
    }
    function listar(){
        return $this->find();
    }
    function crearTvehiculo($datosTvehiculo){
        $tvehiculo = Load::model('tvehiculos');        
        $tvehiculo->begin();
        
        try{
            $tvehiculo->create($datosTvehiculo);
            $tvehiculo->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $tvehiculo->rollback();
            return FALSE;
        }
    }
    function editTvehiculo($datosTvehiculo){
        $tvehiculo = Load::model('tvehiculos');
        
        $tvehiculo->begin();
        try{
            $tvehiculo->update($datosTvehiculo);
            $tvehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $tvehiculo->rollback();
            return FALSE;
        }
    }
    function deletTvehiculo($id){
        $tvehiculo = Load::model('tvehiculos');
        
        $tvehiculo->begin();
        
        try {
            $tvehiculo->delete($id);
            $tvehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $tvehiculo->rollback();
            return FALSE;
        }
    }
}