<?php

class Solicitud extends ActiveRecord {

    public function initialize() {
        $this->belongs_to('unegocio');
        $this->belongs_to('users');
        $this->has_many('detalle');
    }

    function listar() {
        return $this->find();
    }

    function crearSolicitud($datosSolicitud, $datosPatente, $datosTrabajo, $datosRinde, $datosOperador, $datosUbicacion, $datosConteo) {
        $data = array('vahiculos_id' => '', 'litros' => '', 'operador' => '', 'trabajo' => '', 'ubicacion' => '', 'solicitud_id' => '', 'fentrega' => $datosSolicitud['fentrega'],
            'unegocio_id' => $datosSolicitud['unegocio_id'], 'fecha' => date('d-m-Y'), 'estado' => 'EN PROCESO');
        $datavehiculos = array('id' => '', 'conteo' => '', 'conteotemp' => '');
        $solicitud = Load::model('solicitud');
        $detalle = Load::model('detalle');
        $vehiculos = Load::model('vehiculos');

        $date = strtotime($datosSolicitud['fentrega']);
        $mesentrega = date("m", $date);
        $anoentrega = date("Y", $date);
        $mesactual = date("m");
        $anoactual = date("Y");

        $solicitud->begin();
        try {
            $solicitud->create($datosSolicitud);
            $solicitud->commit();
        } catch (Exception $ex) {
            $solicitud->rollback();
            return FALSE;
        }
        $detalle->begin();
        $vehiculos->begin();
        try {
            $i = count($datosPatente);
            $j = 0;
            while ($j < $i) {
                $idtrabajo = (int) $datosTrabajo[$j];
                $idvehiculo = (int) $datosPatente[$j];
                $calcular = (int) $datosRinde[$j];
                $factor = Load::model('trabajos')->find_by_id($idtrabajo);
                $rinde = Load::model('vehiculos')->find_by_id($idvehiculo);
                $litros = $rinde->tmarcador == 'KM' ? intval(($calcular / ($rinde->rinde * $factor->factor)) * $rinde->delta) : intval((($rinde->rinde * $calcular) * $factor->factor) * $rinde->delta);                
                $data['trabajo'] = $factor->nombre;
                $data['operador'] = $datosOperador[$j];
                $data['ubicacion'] = $datosUbicacion[$j];
                $data['solicitud_id'] = $solicitud->id;
                $data['vehiculos_id'] = $datosPatente[$j];
                if ($rinde->limite == 0) {
                    $datavehiculos['id'] = $idvehiculo;
                    $datavehiculos['conteotemp'] = 0;
                    $datavehiculos['conteo'] = 0;
                    $data['litros'] = $litros;      
                } else {                    
                    if (($mesentrega > $mesactual) or ($anoentrega > $anoactual)) {
                        if ($litros > $datosConteo[$j]) {
                            $conteotemp = $rinde->conteotemp + $datosConteo[$j];
                            $datavehiculos['id'] = $idvehiculo;
                            $datavehiculos['conteo'] = 0;
                            $datavehiculos['conteotemp'] = $conteotemp;
                            $data['litros'] = $datosConteo[$j];                            
                        }  else {
                            $conteotemp = $rinde->conteotemp + $litros;
                            $datavehiculos['id'] = $idvehiculo;
                            $datavehiculos['conteo'] = 0;
                            $datavehiculos['conteotemp'] = $conteotemp;
                            $data['litros'] = $litros;
                        }                        
                    } else {
                        if ($litros > $datosConteo[$j]) {
                            $conteo = $rinde->conteo + $datosConteo[$j];
                            $datavehiculos['id'] = $idvehiculo;
                            $datavehiculos['conteo'] = $conteo;
                            $datavehiculos['conteotemp'] = 0;
                            $data['litros'] = $datosConteo[$j];
                        }  else {
                            $conteo = $rinde->conteo + $litros;
                            $datavehiculos['id'] = $idvehiculo;
                            $datavehiculos['conteo'] = $conteo;
                            $datavehiculos['conteotemp'] = 0;
                            $data['litros'] = $litros;
                        }                        
                    }
                }

                $vehiculos->update($datavehiculos);
                $detalle->create($data);
                $j++;
            }
            $detalle->commit();
            $vehiculos->commit();
            return $solicitud->id;
        } catch (Exception $ex) {
            $detalle->rollback();
            $vehiculos->rollback();
            return FALSE;
        }
    }

    function crearDetalle($datosDetalle) {
        $detalle = Load::model('detalle');
        $detalle->begin();

        try {
            $detalle->create($datosDetalle);
            $detalle->commit();
            return TRUE;
        } catch (Exception $ex) {
            $detalle->rollback();
            return FALSE;
        }
    }

    function editSolicitud($datosSolicitud) {
        $estado = Load::model('solicitud');

        $estado->begin();
        try {
            $estado->update($datosSolicitud);
            $estado->commit();
            return TRUE;
        } catch (Exception $ex) {
            $estado->rollback();
            return FALSE;
        }
    }

    function anulaSolicitud($id) {
        $solicitud = Load::model('solicitud');
        $detalle = Load::model('detalle');               
        $datos = array('id' => $id, 'estado' => 'ANULADA');

        $patentes = Load::model('detalle')->find_all_by_solicitud_id((int) $id);
        foreach ($patentes as $patente):
            Load::model('vehiculos')->devuelveLitros($patente->vehiculos_id, $patente->litros);            
        endforeach;
        
        $solicitud->begin();
        $detalle->begin();
        try {
            $solicitud->update($datos);
            $detalle->update_all("estado='ANULADA'", "solicitud_id=$id");
            $solicitud->commit();
            $detalle->commit();
            return TRUE;
        } catch (Exception $ex) {
            $solicitud->rollback();
            $detalle->rollback();
            return FALSE;
        }
    }
    function report_xunegocio($id, $desde, $hasta) {
        $arreglo["data"] = $this->find_all_by_sql("select sol.id, une.nombre as nombune, sol.fecha, sol.fentrega, us.nombre as usuario, sol.estado from solicitud as sol
join unegocio as une on sol.unegocio_id=une.id
join users as us on us.id=sol.users_id
where une.id=$id and sol.fentrega>='$desde' and sol.fentrega<='$hasta'");
        return $arreglo;
    }
    function listarsolic($user) {
        $arreglo["data"] = $this->find_all_by_sql("select sol.id, une.nombre as unegocio, sol.fecha, sol.fentrega, use.nombre as usuario, sol.estado from solicitud as sol 
left join users as use on sol.users_id=use.id 
left join unegocio as une on sol.unegocio_id=une.id
where unegocio_id  in (select une.id from unegocio as une join users_unegocio as uu on une.id=uu.unegocio_id join users as use2 on use2.id=uu.users_id where use2.id=$user)");
        return $arreglo;
    }

}
