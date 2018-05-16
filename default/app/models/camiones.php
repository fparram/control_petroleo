<?php

class Camiones extends ActiveRecord{
    public function initialize()
    {
        
        $this->has_many('camiones_herramientas');
        $this->has_many('incidentes');
        $this->has_many('mantenimiento');
        $this->has_many('detalle');
    }
    function crearCamion($datosCamion){
        $camion = Load::model('camiones');
        $revisa = Load::model('camiones')->find();
        foreach ($revisa as $revisa):
            if ($revisa->patente == $datosCamion['patente']){
                return FALSE;
            }
        endforeach;
        $camion->begin();
        try {
            $camion->create($datosCamion);
            $camion->commit();
            return TRUE;
        } catch (Exception $ex) {
            $camion->rollback();
            return FALSE;           
        }
    }
    function editCamion($datosCamion){
        $camion = Load::model('camiones');
        $camion->begin();
        try {
            $camion->update($datosCamion);
            $camion->commit();
            return TRUE;
        } catch (Exception $ex) {
            $camion->rollback();
            return FALSE;
        }
    }
    function deletCamion($id){
        $camion = Load::model('camiones');
        $camion->begin();
        try {
            $camion->delete($id);
            $camion->commit();
            return TRUE;
        } catch (Exception $ex) {
            $camion->rollback();
            return FALSE;
        }
    }
    function surtidor() {
        return $this->find_all_by_sql("select id, modelo as nombre from camiones");
    }
}