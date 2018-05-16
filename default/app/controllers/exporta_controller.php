<?php

Load::lib('fechas');

class ExportaController extends AppController {

    public function nuevo() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('nuevo');
        if (Input::hasPost('detalle')) {
            $rango = Input::post('detalle');

            $this->busqueda = Load::model('detalle')->find("conditions: (fentrega >='" . $rango['fecha1'] . "') and (fentrega <='" . $rango['fecha2'] . "')");
            $this->unegocio = Load::model('unegocio')->find();
            $this->vehiculos = Load::model('vehiculos')->find();
            $this->camiones = Load::model('camiones')->find();
        }
    }

    public function xunegocio() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('nuevo');
        if (Input::hasPost('consulta')) {
            $id = Input::post('consulta');
            $unegocio = $id['id'];
            $this->busqueda = Load::model('detalle')->find("conditions: (unegocio_id = $unegocio) and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "') and (estado='CERRADA')");
        }
    }

    public function xpatente() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('nuevo');
        if (Input::hasPost('consulta')) {
            $id = Input::post('consulta');
            $this->busqueda = Load::model('detalle')->find("conditions: (vehiculos_id='" . $id['id'] . "') and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "')");
        }
    }

    public function xfechatodo() {
        if (Auth::is_valid()) {
            View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
            View::select('nuevo');
            if (Input::hasPost('consulta')) {
                $id = Input::post('consulta');
                $this->busqueda = Load::model('detalle')->find("conditions: (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "')");
                $this->unegocio = Load::model('unegocio')->find();
                $this->vehiculos = Load::model('vehiculos')->find();
                $this->camiones = Load::model('camiones')->find();
            } elseif (Input::hasPost('consultaxune')) {
                $id = Input::post('consultaxune');
                $unegocio = $id['id'];
                $this->busqueda = Load::model('detalle')->find("conditions: (unegocio_id = $unegocio) and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "') and (estado='ANULADA')");
                $this->unegocio = Load::model('unegocio')->find();
                $this->vehiculos = Load::model('vehiculos')->find();
                $this->camiones = Load::model('camiones')->find();
            } elseif (Input::hasPost('consultaxpat')) {
                $id = Input::post('consultaxpat');
                $patente = $id['id'];
                $this->busqueda = Load::model('detalle')->find("conditions: (vehiculos_id = $patente) and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "') and (estado='ANULADA')");
                $this->unegocio = Load::model('unegocio')->find();
                $this->vehiculos = Load::model('vehiculos')->find();
                $this->camiones = Load::model('camiones')->find();
            } else {
                Redirect::to("/deptcombust/versolicitud");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function xtipo() {
        if (Auth::is_valid()) {
            View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
            View::select('nuevo');
            if (Input::hasPost('consultaxtipo')) {
                $id = Input::post('consultaxtipo');
                $this->busqueda = Load::model('detalle')->report_xtipo_exporta($id['id'], $id['desde'], $id['hasta']);
                $this->unegocio = Load::model('unegocio')->find();
                $this->vehiculos = Load::model('vehiculos')->find();
                $this->camiones = Load::model('camiones')->find();
            } else {
                Redirect::to("/deptcombust/versolicitud");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function xmacrotipo() {
        if (Auth::is_valid()) {
            View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
            View::select('nuevo');
            if (Input::hasPost('consultaxmacro')) {
                $id = Input::post('consultaxmacro');
                $this->busqueda = Load::model('detalle')->report_xmacrotipo_exporta($id['id'], $id['desde'], $id['hasta']);
                $this->unegocio = Load::model('unegocio')->find();
                $this->vehiculos = Load::model('vehiculos')->find();
                $this->camiones = Load::model('camiones')->find();
            } else {
                Redirect::to("/deptcombust/versolicitud");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function facturas() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('facturas');
        if (Input::hasPost('detalle')) {
            $rango = Input::post('detalle');

            $this->busqueda = Load::model('adq')->find("conditions: (frecep >='" . $rango['fecha1'] . "') and (frecep <='" . $rango['fecha2'] . "')");
            $this->unegocio = Load::model('unegocio')->find();
        }
    }

    public function detmacro() {
        if (Auth::is_valid()) {
            View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
            View::select('detmacroex');
            if (Input::hasPost('macrotipo')) {
                $id = Input::post('macrotipo');
                $idmacro = $id['id'];
                $fechas = new fechas();
                $mes = date('m');
                $this->nm1 = $fechas->traduceMes($mes - 1);
                $this->nm2 = $fechas->traduceMes($mes - 2);
                $this->nm3 = $fechas->traduceMes($mes - 3);
                $iniMes3 = $fechas->fechaInicial3(date('m'));
                $finMes3 = $fechas->fechaFinal3(date('m'));
                $iniMes2 = $fechas->fechaInicial2(date('m'));
                $finMes2 = $fechas->fechaFinal2(date('m'));
                $iniMes1 = $fechas->fechaInicial1(date('m'));
                $finMes1 = $fechas->fechaFinal1(date('m'));
                $fimes = date('01-m-Y');
                $factual = date('d-m-Y');
                
                $this->patentes = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $factual, $idune = 0);
                $this->mes3 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $finMes3, $idune = 0);
                $this->mes2 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes2, $finMes2, $idune = 0);
                $this->mes1 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes1, $finMes1, $idune = 0);
                $this->actual = Load::model('macrotipo')->listarmacro($idmacro, $fimes, $factual, $idune = 0);
            }
        } else {
            Redirect::to("/");
        }
    }

}
