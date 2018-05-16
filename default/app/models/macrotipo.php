<?php

class Macrotipo extends ActiveRecord {

    public function initialize() {
        $this->has_many('tvehiculos');
    }

    function report_meses($fInicial, $fFinal) {
        return $this->find_all_by_sql("select mt.id, mt.nombre, sum(lcargados) as litros from macrotipo as mt
                                       join tvehiculos as tvh on mt.id=tvh.macrotipo_id
                                       join vehiculos as vehi on tvh.id=vehi.tvehiculos_id
                                       join detalle as det on vehi.id=det.vehiculos_id
                                       where det.frentrega >='$fInicial' and det.frentrega <= '$fFinal' and det.estado!='ANULADA' group by mt.id, mt.nombre");
    }

    function report_meses2($fInicial, $fFinal, $unegocio) {
        return $this->find_all_by_sql("select mt.id, mt.nombre, sum(det.lcargados) as litros from detalle as det
join vehiculos as v on v.id=det.vehiculos_id
join tvehiculos as tv on tv.id=v.tvehiculos_id
join macrotipo as mt on mt.id=tv.macrotipo_id
where det.frentrega >='$fInicial' and det.frentrega <='$fFinal' and det.unegocio_id=$unegocio and det.estado!='ANULADA' group by mt.id, mt.nombre");
    }

    function listarmacro($idmacro, $fInicial, $fFinal, $idunegocio) {
        if ($idunegocio == 0) {
            return $this->find_all_by_sql("select vehi.patente, tv.nombre, sum(det.lcargados) as litros from vehiculos as vehi
join detalle as det on det.vehiculos_id=vehi.id
join unegocio as u on u.id=det.unegocio_id
join tvehiculos as tv on tv.id=vehi.tvehiculos_id
join macrotipo as m on m.id=tv.macrotipo_id
where m.id=$idmacro and det.fentrega>='$fInicial' and det.fentrega<='$fFinal'
group by vehi.patente, tv.nombre");
        } else {
            return $this->find_all_by_sql("select vehi.patente, tv.nombre, sum(det.lcargados) as litros from vehiculos as vehi
join detalle as det on det.vehiculos_id=vehi.id
join unegocio as u on u.id=det.unegocio_id
join tvehiculos as tv on tv.id=vehi.tvehiculos_id
join macrotipo as m on m.id=tv.macrotipo_id
where m.id=$idmacro and det.fentrega>='$fInicial' and det.fentrega<='$fFinal' and u.id=$idunegocio
group by vehi.patente, tv.nombre");
        }
    }

}
