<?php

class Vehiculos extends ActiveRecord{
    public function initialize()
    {
        //Relaciones
        //Un usuario tiene una unidad de negocio de cada una
        $this->belongs_to('tvehiculos');
        $this->belongs_to('propietario');
        $this->has_many('detalle');
        $this->has_many('ccamiones');
        $this->has_many('recursoc');
        $this->has_many('recursom');
    }
    function listar(){
        return $this->find();
    }
    function crearVehiculo($datosVehiculo){
        $vehiculo = Load::model('vehiculos');        
        $vehiculo->begin();
        
        try{
            $vehiculo->create($datosVehiculo);
            $vehiculo->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $vehiculo->rollback();
            return FALSE;
        }
    }
    function editVehiculo($datosVehiculo){
        $vehiculo = Load::model('vehiculos');
        
        $vehiculo->begin();
        try{
            $vehiculo->update($datosVehiculo);
            $vehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $vehiculo->rollback();
            return FALSE;
        }
    }
    function deletVehiculo($id){
        $vehiculo = Load::model('vehiculos');       
        $vehiculo->begin();
        
        try {
            $vehiculo->delete($id);
            $vehiculo->commit();
            return TRUE;
        } catch (Exception $ex) {
            $vehiculo->rollback();
            return FALSE;
        }
    }
    function crearCarga($datosCarga){
        $dato1 = $datosCarga['rinde'];
        $dato2 = $datosCarga['delta'];
        $condi = $datosCarga['tvehiculos_id'];
        $carga = Load::model('vehiculos');        
        $carga->begin();
        
        try{
            $carga->update_all("rinde=$dato1, delta=$dato2", "tvehiculos_id=$condi");
            $carga->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $carga->rollback();
            return FALSE;
        }
    }
    function crearTm($datosCarga2){
        $dato1 = $datosCarga2['tmarcador'];        
        $condi = $datosCarga2['tvehiculos_id'];
        $carga = Load::model('vehiculos');        
        $carga->begin();
        
        try{
            $carga->update_all("tmarcador='$dato1'", "tvehiculos_id=$condi");
            $carga->commit();
            return TRUE;           
        } catch (Exception $ex) {
            $carga->rollback();
            return FALSE;
        }
    }
    function devuelveLitros($id, $litros) {
        $vehiculo = $this->find_by_id((int) $id);
        $data = array('id' => $id, 'conteo' => '', 'conteotemp' => '');
        if ($vehiculo->conteotemp >= $litros) {
            $restalitros = $vehiculo->conteotemp-$litros;
            $data['conteotemp'] = $restalitros;
        }  else {
            $restalitros = $vehiculo->conteo-$litros;
            $data['conteo'] = $restalitros;
        }
        $this->begin();
        try {
            $this->update($data);
            $this->commit();
            return TRUE;
        } catch (Exception $exc) {
            $this->rollback();
            return FALSE;
        }
    }
    function patente() {
        return $this->find_all_by_sql("select id, concat(patente, ' / ', marca, ' / ', modelo) as patentes from vehiculos");
    }
    public function reseteaConteo($mes) {
        if ($this->find_all_by_sql("update vehiculos set conteo=conteotemp, mes=$mes, conteotemp=0")) {
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    function listado_gps($data) {
        $numero = count($data);
        for ($j=0;$j<$numero;$j++){
            $list_gps["data"][] = $this->find_first("patente='".$data[$j]."'");                
        }
        return json_encode($list_gps);
    }
    
}