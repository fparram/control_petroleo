<?php

class ConsultasController extends AppController {

    public function index() {
        if (Auth::is_valid()) {
            $this->vehiculos = Load::model('vehiculos')->find();
            $this->vehiculos2 = Load::model('vehiculos')->find();
        } else {
            Redirect::to("/");
        }
    }

    public function xpatente() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $id = Input::post('consulta');                
                $this->patente = Load::model('vehiculos')->find_by_id($id['id']);
                $this->desde = $id['desde'];
                $this->hasta = $id['hasta'];
                //$this->busqueda = Load::model('detalle')->find("conditions: (vehiculos_id='" . $id['id'] . "') and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "')");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function xunegocio() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $id = Input::post('consulta');
                $this->unegocio = Load::model('unegocio')->find_by_id($id['id']);
                $this->desde = $id['desde'];
                $this->hasta = $id['hasta'];
                //$this->page = Load::model('solicitud')->paginate("conditions: (unegocio_id = $unegocio) and (fentrega >='".$id['desde']."') and (fentrega <='".$id['hasta']."') and (estado='CERRADA')", "page=$page", 'per_pege=10');                
            }
        } else {
            Redirect::to("/");
        }
    }

    public function listar() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listar');
        $id = Input::post('id');
        $desde = Input::post('desde');
        $hasta = Input::post('hasta');
        $detalle = new Solicitud();        
        $this->data = $detalle->report_xunegocio($id, $desde, $hasta);
    }
    public function listarxpt() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listarxpt');
        $id = Input::post('id');
        $desde = Input::post('desde');
        $hasta = Input::post('hasta');
        $detalle = new Detalle();        
        $this->data = $detalle->report_xpatente($id, $desde, $hasta);
    }
    public function listarxtipo() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listarxtipo');
        $id = Input::post('id');
        $desde = Input::post('desde');
        $hasta = Input::post('hasta');
        $detalle = new Detalle();        
        $this->data = $detalle->report_xtipo($id, $desde, $hasta);
    }
    public function listarxmacrotipo() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listarxmacrotipo');
        $id = Input::post('id');
        $desde = Input::post('desde');
        $hasta = Input::post('hasta');
        $detalle = new Detalle();        
        $this->data = $detalle->report_xmacrotipo($id, $desde, $hasta);
    }
    public function informe() {
        View::template('imprimir');
        if (Auth::is_valid()) {
            $fechas = new fechas();
            $mes = date('m');
            $this->nm1 = $fechas->traduceMes($mes-1);
            $this->nm2 = $fechas->traduceMes($mes-2);
            $this->nm3 = $fechas->traduceMes($mes-3);            
            $iniMes3 = $fechas->fechaInicial3(date('m'));
            $finMes3 = $fechas->fechaFinal3(date('m'));            
            $iniMes2 = $fechas->fechaInicial2(date('m'));
            $finMes2 = $fechas->fechaFinal2(date('m'));            
            $iniMes1 = $fechas->fechaInicial1(date('m'));
            $finMes1 = $fechas->fechaFinal1(date('m'));
            $fimes = date('01-m-Y');
            $factual = date('d-m-Y');            
            $this->page1 = Load::model('macrotipo')->report_meses($iniMes3, $finMes3);
                $this->page2 = Load::model('macrotipo')->report_meses($iniMes2, $finMes2);
                $this->page3 = Load::model('macrotipo')->report_meses($iniMes1, $finMes1);
                $this->page4 = Load::model('macrotipo')->report_meses($fimes, $factual);

                $this->lisune1 = Load::model('unegocio')->report_diferencial($iniMes2, $finMes2);
                $this->lisune2 = Load::model('unegocio')->report_diferencial($iniMes1, $finMes1);
                $this->lisune3 = Load::model('unegocio')->report_diferencial($fimes, $factual);
            
                $this->totales1 = Load::model('detalle')->report_totales($iniMes3, $finMes3);
                $this->totales2 = Load::model('detalle')->report_totales($iniMes2, $finMes2);
                $this->totales3 = Load::model('detalle')->report_totales($iniMes1, $finMes1);
                $this->totales4 = Load::model('detalle')->report_totales($fimes, $factual);
        } else {
            Redirect::to("/");
        }
    }
    public function xtipo() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $id = Input::post('consulta');                
                $this->tvehiculo = Load::model('tvehiculos')->find_by_id($id['id']);
                $this->desde = $id['desde'];
                $this->hasta = $id['hasta'];
                //$this->busqueda = Load::model('detalle')->find("conditions: (vehiculos_id='" . $id['id'] . "') and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "')");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function xmacrotipo() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $id = Input::post('consulta');                
                $this->macrotipo = Load::model('macrotipo')->find_by_id($id['id']);
                $this->desde = $id['desde'];
                $this->hasta = $id['hasta'];
                //$this->busqueda = Load::model('detalle')->find("conditions: (vehiculos_id='" . $id['id'] . "') and (fentrega >='" . $id['desde'] . "') and (fentrega <='" . $id['hasta'] . "')");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function detmacro() {
        View::template('imprimir');
        if (Auth::is_valid()) {            
            if (Input::hasPost('macrotipo')) {
                $id = Input::post('macrotipo');
                $idmacro = $id['id2'];
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
    //Consulta de salgo litros
    public function saldo_litros() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $data = Input::post('consulta');
                $vehiculos_id = $data['id'];                
                $this->vehiculo = Load::model('vehiculos')->find_by_id((int) $vehiculos_id);
            }  else {
                Redirect::to("/");
            }            
        }  else {
            Redirect::to("/");
        }
    }
    protected function before_filter() {
        $acl = new MyAcl('privilegios');  //si no se especifica el archivo a usar, por defecto busca en privilegios.ini
        $modulo = $this->module_name;
        $controlador = $this->controller_name;
        $accion = $this->action_name;

        $privilegio = Auth::get('privilegio');
        if (!$acl->check($privilegio, $modulo, $controlador, $accion)) { //verificamos si tiene ó no privilegios            
            Flash::error("No posees suficientes PRIVILEGIOS para acceder a: {$modulo}/{$controlador}/{$accion}");
            View::select('error_privilegio');
            return false;
        }
    }

}
