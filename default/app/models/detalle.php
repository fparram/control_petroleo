<?php

class Detalle extends ActiveRecord {

    public function initialize() {
        $this->belongs_to('solicitud');
        $this->belongs_to('camiones');
        $this->belongs_to('unegocio');
        $this->belongs_to('vehiculos');
        $this->belongs_to('trabajos');
        $this->belongs_to('users');
    }

    function listar() {
        return $this->find();
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

    function editDetalle($datosDetalle) {
        $detalle = Load::model('detalle');

        $detalle->begin();
        try {
            $detalle->update($datosDetalle);
            $detalle->commit();
            return TRUE;
        } catch (Exception $ex) {
            $detalle->rollback();
            return FALSE;
        }
    }
    
    function eliminarRegistro($id) {
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

    function asignaLitros($datosSolicitud_id, $datosIds, $datosLasignados, $datosCamiones_id) {
        $datadetalle = array('id' => '', 'lasignados' => '', 'camiones_id' => '', 'estado' => '');
        $datasolicitud = array('id' => $datosSolicitud_id['id'], 'estado' => '');
        $solicitud = Load::model('solicitud');
        $detalle = Load::model('detalle');

        $detalle->begin();
        try {
            $i = count($datosIds);
            $j = 0;
            while ($j < $i) {
                $iddetalle = (int) $datosIds[$j];
                $datadetalle['id'] = $iddetalle;
                $datadetalle['lasignados'] = $datosLasignados[$j];
                $datadetalle['camiones_id'] = $datosCamiones_id[$j];
                $datadetalle['estado'] = $this->revisarForm($datosLasignados) == 1 ? 'APROBADA' : 'EN PROCESO';
                $detalle->update($datadetalle);
                $j++;
            }
            $solicitud->begin();
            try {
                $datasolicitud['estado'] = $this->revisarForm($datosLasignados) == 1 ? 'APROBADA' : 'EN PROCESO';
                $solicitud->update($datasolicitud);                
            } catch (Exception $ex) {
                
            }
            $solicitud->commit();
            $detalle->commit();
            return TRUE;
        } catch (Exception $ex) {
            $solicitud->rollback();
            $detalle->rollback();
            return FALSE;
        }
    }
    function ingresoVales($datosSolc, $datosId, $datosFentrega, $datosLcargados, $datosNvale, $datosHactual, $datosQrecibe, $datosCarga, $loguser, $logfecha, $loghora) {
        $datadetalle = array('id' => '', 'frentrega' => '', 'lcargados' => '', 'nvale' => '', 'hactual' => '', 'qrecibe' => '', 'carga' => '', 'loguser' => '', 'logfecha' => '', 'loghora' => '', 'estado' => '');
        $datasolicitud = array('id' => $datosSolc[0], 'estado' => '');
        $solicitud = Load::model('solicitud');
        $detalle = Load::model('detalle');
        
        $detalle->begin();
        try {
            $i = count($datosId);
            $j = 0;
            while ($j < $i) {
                $iddetalle = (int) $datosId[$j];
                $datadetalle['id'] = $iddetalle;
                $datadetalle['lcargados'] = $datosLcargados[$j];
                $datadetalle['nvale'] = $datosNvale[$j];
                $datadetalle['frentrega'] = $datosFentrega[$j];
                $datadetalle['hactual'] = $datosHactual[$j];
                $datadetalle['qrecibe'] = $datosQrecibe[$j];                
                if ($datosNvale[$j] != '') {
                    $datadetalle['carga'] = 0;                    
                    $datadetalle['estado'] = 'CERRADA';
                    if ($loguser[$j] != '') {
                        $datadetalle['loguser'] = $loguser[$j];
                        $datadetalle['logfecha'] = $logfecha[$j];
                        $datadetalle['loghora'] = $loghora[$j];
                    }  else {
                        $datadetalle['loguser'] = Auth::get('nombre');
                        $datadetalle['logfecha'] = date('d-m-Y');
                        $datadetalle['loghora'] = date('H:i:s');
                    }
                }  else {
                    $datadetalle['carga'] = 1;                    
                    $datadetalle['estado'] = 'CERRADA';
                    if ($loguser[$j] != '') {
                        $datadetalle['loguser'] = $loguser[$j];
                        $datadetalle['logfecha'] = $logfecha[$j];
                        $datadetalle['loghora'] = $loghora[$j];
                    }  else {
                        $datadetalle['loguser'] = Auth::get('nombre');
                        $datadetalle['logfecha'] = date('d-m-Y');
                        $datadetalle['loghora'] = date('H:i:s');
                    }
                    
                }
                $detalle->update($datadetalle);
                $j++;
            }
            $solicitud->begin();
            try {
                $datasolicitud['estado'] = 'CERRADA';
                $solicitud->update($datasolicitud);                
            } catch (Exception $ex) {
                
            }
            $detalle->commit();
            $solicitud->commit();
            return TRUE;
        } catch (Exception $ex) {
            $detalle->rollback();
            $solicitud->rollback();
            return FALSE;
        }
    }

    protected function revisarForm($Ids) {
        $i = count($Ids);
        $j = 0;
        $k = 0;
        while ($j < $i) {
            if ($Ids[$j] != '') {
                $k++;                
            }
            $j++;
        }
        if ($k == $i) {
            return 1;
        } else {
            return 0;
        }
    }
    function report_totales($fInicial, $fFinal) {
        return $this->find_by_sql("select sum(lcargados) as litros from detalle 
                                       where frentrega >= '$fInicial' and frentrega <= '$fFinal'");
    }
    function report_totales2($fInicial, $fFinal, $unegocio) {
        return $this->find_by_sql("select sum(lcargados) as litros from detalle 
                                       where unegocio_id=$unegocio and frentrega >= '$fInicial' and frentrega <= '$fFinal'");
    }
    function report_xpatente($id, $desde, $hasta) {
        $arreglo["data"] = $this->find_all_by_sql("select det.solicitud_id as id, u.nombre, det.fecha, det.fentrega, det.lcargados, c.marca from detalle as det
join vehiculos as v on v.id=det.vehiculos_id
join unegocio as u on u.id=det.unegocio_id
join camiones as c on c.id=det.camiones_id
where v.id=$id and det.fentrega>='$desde' and det.fentrega<='$hasta'");
        return $arreglo;
    }
    function report_xtipo($id, $desde, $hasta) {
        $arreglo["data"] = $this->find_all_by_sql("select det.solicitud_id as id, u.nombre, det.fecha, det.fentrega, det.lcargados, c.marca from detalle as det
left join unegocio as u on det.unegocio_id=u.id
left join vehiculos as v on v.id=det.vehiculos_id
left join tvehiculos as tv on v.tvehiculos_id=tv.id
left join camiones as c on c.id=det.camiones_id
where tv.id=$id and det.fentrega>='$desde' and det.fentrega<='$hasta'");
        return $arreglo;
    }
    function report_xmacrotipo($id, $desde, $hasta) {
        $arreglo["data"] = $this->find_all_by_sql("select det.solicitud_id as id, u.nombre, det.fecha, det.fentrega, det.lcargados, c.marca from detalle as det
left join unegocio as u on det.unegocio_id=u.id
left join vehiculos as v on v.id=det.vehiculos_id
left join tvehiculos as tv on v.tvehiculos_id=tv.id
left join macrotipo as mt on mt.id=tv.macrotipo_id
left join camiones as c on c.id=det.camiones_id
where mt.id=$id and det.fentrega>='$desde' and det.fentrega<='$hasta'");
        return $arreglo;
    }
    function report_xtipo_exporta($id, $desde, $hasta) {
        return $this->find_all_by_sql("select det.solicitud_id, det.unegocio_id, det.fecha, det.fentrega, det.vehiculos_id, det.operador, det.ubicacion, det.trabajo, det.litros, det.lasignados, det.lcargados, det.carga, det.camiones_id, det.frentrega, det.nvale, det.hactual, det.qrecibe from detalle as det
left join unegocio as u on det.unegocio_id=u.id
left join vehiculos as v on v.id=det.vehiculos_id
left join tvehiculos as tv on v.tvehiculos_id=tv.id
left join camiones as c on c.id=det.camiones_id
where tv.id=$id and det.fentrega>='$desde' and det.fentrega<='$hasta'");        
    }
    function report_xmacrotipo_exporta($id, $desde, $hasta) {
        return $this->find_all_by_sql("select det.solicitud_id, det.unegocio_id, det.fecha, det.fentrega, det.vehiculos_id, det.operador, det.ubicacion, det.trabajo, det.litros, det.lasignados, det.lcargados, det.carga, det.camiones_id, det.frentrega, det.nvale, det.hactual, det.qrecibe from detalle as det
left join unegocio as u on det.unegocio_id=u.id
left join vehiculos as v on v.id=det.vehiculos_id
left join tvehiculos as tv on v.tvehiculos_id=tv.id
left join macrotipo as mt on mt.id=tv.macrotipo_id
left join camiones as c on c.id=det.camiones_id
where mt.id=$id and det.fentrega>='$desde' and det.fentrega<='$hasta'");        
    }
    function hojaRuta($camiones_id, $fentrega) {
        return $this->find_all_by_sql("select det.solicitud_id, u.nombre as unegocio, det.fentrega, v.patente, tv.nombre as tvehiculo, det.ubicacion, det.litros, det.lasignados from detalle as det
left join unegocio as u on u.id=det.unegocio_id
left join vehiculos as v on v.id=det.vehiculos_id
left join tvehiculos as tv on tv.id=v.tvehiculos_id
where det.camiones_id=$camiones_id and fentrega='$fentrega'");
    }
}
