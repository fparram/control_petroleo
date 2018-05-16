<?php

class Productos extends ActiveRecord{
    public function initialize()
    {
        $this->has_many('items');
    }
    function crearProducto($datosProducto){        
        $revisa = $this->find();
        foreach ($revisa as $revisa):
            if ($revisa->nombre == $datosProducto['nombre']){
                return FALSE;
            }
        endforeach;
        $this->begin();
        try {
            $this->create($datosProducto);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function editarProducto($datosProducto){        
        $this->begin();
        try {
            $this->update($datosProducto);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function eliminarProducto($id){       
        $this->begin();
        try {
            $this->delete($id);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }    
}