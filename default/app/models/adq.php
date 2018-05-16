<?php
class Adq extends ActiveRecord{
    public function initialize(){
        $this->belongs_to('unegocio');
        $this->belongs_to('users');
    }
    function crearAdq($datosAdq){
        $adq = Load::model('adq');
        $adq->begin();
        try {
            $adq->create($datosAdq);
            $adq->commit();
            return TRUE;
        } catch (Exception $ex) {
            $adq->rollback();
            return FALSE;
        }
    }
    function editAdq($datosAdq){
        $adq = Load::model('adq');
        
        $adq->begin();
        try{
            $adq->update($datosAdq);
            $adq->commit();
            return TRUE;
        } catch (Exception $ex) {
            $adq->rollback();
            return FALSE;
        }
    }
    function eliminarAdq($id){
        $adq = Load::model('adq');
        
        $adq->begin();
        
        try {
            $adq->delete($id);
            $adq->commit();
            return TRUE;
        } catch (Exception $ex) {
            $adq->rollback();
            return FALSE;
        }
    }
}