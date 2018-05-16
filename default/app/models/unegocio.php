<?php

class Unegocio extends ActiveRecord{    
    public function initialize()
    {
        $this->has_many('ssoporte');
        $this->has_many('detalle');
        $this->has_many('adq');
        $this->has_many('ccosto');
    }
   function crearUnegocio($datosUnegocio){
        $unegocio = Load::model('unegocio');
        $revisa = Load::model('unegocio')->find();
        foreach ($revisa as $revisa):
            if ($revisa->nombre == $datosUnegocio['nombre']){
                return FALSE;
            }
        endforeach;
        $unegocio->begin();
        try {
            $unegocio->create($datosUnegocio);
            $unegocio->commit();
            return TRUE;
        } catch (Exception $ex) {
            $unegocio->rollback();
            return FALSE;
        }
    }
    function editUnegocio($datosUnegocio){
        $unegocio = Load::model('unegocio');
        $unegocio->begin();
        try {
            $unegocio->update($datosUnegocio);
            $unegocio->commit();
            return TRUE;
        } catch (Exception $ex) {
            $unegocio->rollback();
            return FALSE;
        }
    }
    function deletUnegocio($id){
        $unegocio = Load::model('unegocio');
        $unegocio->begin();
        try {
            $unegocio->delete($id);
            $unegocio->commit();
            return TRUE;
        } catch (Exception $ex) {
            $unegocio->rollback();
            return FALSE;
        }
    }
    function relacion($id) {
        return $this->find_all_by_sql("select uneg.id, uneg.nombre from unegocio as uneg
                            join users_unegocio as relac on uneg.id=relac.unegocio_id
                            join users as us on us.id=relac.users_id
                            where us.id=$id and uneg.estado='ACTIVO' order by uneg.id");
    }
    function relacion2($id) {
        return $this->find_all_by_sql("select uneg.id, uneg.nombre from unegocio as uneg
                            join users_unegocio as relac on uneg.id=relac.unegocio_id
                            join users as us on us.id=relac.users_id
                            where us.id=$id order by uneg.id");
    }
    function report_diferencial($fInicial, $fFinal) {
        return $this->find_all_by_sql("select une.id, une.nombre, sum(lcargados) as litros from unegocio as une
                                       join detalle as det on une.id=det.unegocio_id
                                       where det.frentrega >='$fInicial' and det.frentrega <= '$fFinal' group by une.id, une.nombre");
    }
    function report_diferencial2($fInicial, $fFinal, $unegocio) {
        return $this->find_all_by_sql("select une.id, une.nombre, sum(lcargados) as litros from unegocio as une
                                       join detalle as det on une.id=det.unegocio_id
                                       where det.frentrega >='$fInicial' and det.frentrega <= '$fFinal' and det.unegocio_id=$unegocio group by une.id, une.nombre");
    }
}