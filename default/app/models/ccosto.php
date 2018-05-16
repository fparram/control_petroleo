<?php

class Ccosto extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('unegocio');
        $this->has_many('items');
    }
    function crearCcosto($datosCcosto){        
        $revisa = $this->find();
        foreach ($revisa as $revisa):
            if ($revisa->nombre == $datosCcosto['nombre']){
                return FALSE;
            }
        endforeach;
        $this->begin();
        try {
            $this->create($datosCcosto);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function editarCcosto($datosCcosto){        
        $this->begin();
        try {
            $this->update($datosCcosto);
            $this->commit();
            return TRUE;
        } catch (Exception $ex) {
            $this->rollback();
            return FALSE;
        }
    }
    function eliminarCcosto($id){       
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
    public function findByCcosto($unegocio_id = null){
	if((int)$unegocio_id){
            return $this->find_all_by_sql("select cc.id, cc.nombre from ccosto as cc
left join unegocio_ccosto as uc on uc.ccosto_id=cc.id
left join unegocio as u on u.id=uc.unegocio_id
where u.id=$unegocio_id order by cc.nombre asc");
	}else{
            return array();
	}	
    }
}