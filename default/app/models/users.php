<?php

class Users extends ActiveRecord{
    public function initialize()
    {        
        $this->has_many('users_unegocio');  
        $this->has_many('solicitud');
        $this->has_many('adq');
        $this->has_many('detalle');
    }    
    function crearUsuario($datosUsuario){
        $usuario = Load::model('users');
        $clave = md5($datosUsuario['password']);
        $datosUsuario['password'] = $clave;
        $usuario->begin();        
        try{
            $usuario->create($datosUsuario);
            $usuario->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $usuario->rollback();
            return FALSE;
        }
    }
    function editUsuario($datosUsuario){
        $usuario = Load::model('users');
        
        $usuario->begin();
        try{
            $usuario->update($datosUsuario);
            $usuario->commit();
            return TRUE;
        } catch (Exception $ex) {
            $usuario->rollback();
            return FALSE;
        }
    }
    function passUsuario($dataUsers){
        $usuario = Load::model('users');                
        $usuario->begin();
        try {            
            $usuario->update($dataUsers);
            $usuario->commit();
            return TRUE;                            
        } catch (Exception $ex) {
            $usuario->rollback();
            return FALSE;
        }
    }
    function deletUsuario($id){
        $usuario = Load::model('users');        
        $usuario->begin();        
        try {
            $usuario->delete($id);
            $usuario->commit();
            return TRUE;
        } catch (Exception $ex) {
            $usuario->rollback();
            return FALSE;
        }
    }
}
