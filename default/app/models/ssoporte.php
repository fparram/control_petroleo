<?php

class Ssoporte extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('unegocio');
    }
    function crearSsoporte($datosSoporte){
        $ssoporte = Load::model('ssoporte');        
        $ssoporte->begin();
        try {
            $ssoporte->create($datosSoporte);
            $ssoporte->commit();
            return TRUE;
        } catch (Exception $ex) {
            $ssoporte->rollback();
            return FALSE;
        }
    }
    function editSsoporte($datosSoporte){
        $ssoporte = Load::model('ssoporte');
        $ssoporte->begin();
        try {
            $ssoporte->update($datosSoporte);
            $ssoporte->commit();
            return TRUE;
        } catch (Exception $ex) {
            $ssoporte->rollback();
            return FALSE;
        }
    }
    function deletSsoporte($id){
        $ssoporte = Load::model('ssoporte');
        $ssoporte->begin();
        try {
            $ssoporte->delete($id);
            $ssoporte->commit();
            return TRUE;
        } catch (Exception $ex) {
            $ssoporte->rollback();
            return FALSE;
        }
    }
    function addRespuesta($dataRespuesta) {
        $respuesta = $dataRespuesta['respuesta'];
        $id = $dataRespuesta['id'];
        $ssoporte = Load::model('ssoporte');
        $ssoporte->begin();
        try {
            $ssoporte->sql("update ssoporte set estado=1, respuesta='$respuesta' where id=$id");
            $ssoporte->commit();            
            return TRUE;
        } catch (Exception $ex) {
            $ssoporte->rollback();
            return FALSE;
        }
    }
}