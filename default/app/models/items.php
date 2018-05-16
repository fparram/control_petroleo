<?php

class Items extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('ccosto');
        $this->has_many('productos');
    }
    function crearItem($datosItem){        
        $revisa = $this->find();
        foreach ($revisa as $revisa):
            if ($revisa->nombre == $datosItem['nombre']){
                return FALSE;
            }
        endforeach;
        $this->begin();
        try {
            $this->create($datosItem);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function editarItem($datosItem){        
        $this->begin();
        try {
            $this->update($datosItem);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function eliminarItem($id){       
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
    function findByItems($ccosto_id = NULL) {
        if((int)$ccosto_id){
            return $this->find("conditions: ccosto_id=$ccosto_id");
	}else{
            return array();
	}
    }
}