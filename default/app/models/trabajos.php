<?php

class Trabajos extends ActiveRecord{
    public function initialize(){
        $this->has_many('detalle');
    }
    function crearTrabajo($datosTrabajo){
        $trabajo = Load::model('trabajos');
        $trabajo->begin();
        try {
            $trabajo->create($datosTrabajo);
            $trabajo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $trabajo->rollback();
            return FALSE;
        }
    }
    function editTrabajo ($datosTrabajo){
        $trabajo = Load::model('trabajos');        
        $trabajo->begin();
        try{
            $trabajo->update($datosTrabajo);
            $trabajo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $trabajo->rollback();
            return FALSE;
        }
    }
    function deletTrabajo($id){
        $trabajo = Load::model('trabajos');       
        $trabajo->begin();
        
        try {
            $trabajo->delete($id);
            $trabajo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $trabajo->rollback();
            return FALSE;
        }
    }
    function trabajosxpatente($patente_id) {
        return $this->find_all_by_sql("select tb.id, tb.nombre from trabajos as tb
join tvehiculos_trabajos as tvtb on tvtb.trabajos_id=tb.id
join tvehiculos as tv on tv.id=tvtb.tvehiculos_id
join vehiculos as v on v.tvehiculos_id=tv.id
where v.id=$patente_id");
    }
}
