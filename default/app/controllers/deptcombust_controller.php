<?php

Load::lib('revisacargas');
Load::lib('fechas');

class DeptcombustController extends AppController {

    public function index() {
        if (Auth::is_valid()) {
            
        } else {
            Redirect::to("/");
        }
    }
    //Codigo para Crear solicitud
    public function solicitud() {
        if (Auth::is_valid()) {
            $hora2=strftime("%H:%M");
                $hora1 = $hora2;                
                if ($hora1 <= Auth::get('horario') or (Auth::get('privilegio') == 'ADMINISTRADOR' or Auth::get('privilegio') == 'ADMIN_COMBUST')){
                    View::select('solicitud');
                }else{
                    View::select('porhora');
                    Flash::info('Sistema Cerrado por Horario');                    
                }
            if (Input::hasPost('solicitud')) {
                if (Input::hasPost('patente')) {                   
                    $idsol = Load::model('solicitud')->crearSolicitud(Input::post('solicitud'), Input::post('patente'), Input::post('trabajos'), Input::post('rinde'), Input::post('operador'), Input::post('ubicacion'), Input::post('conteo'));
                    if ($idsol != FALSE) {
                        Flash::valid('Operacion Exitosa');
                        Redirect::to("deptcombust/salida/" . $idsol);
                    }
                } else {
                    Flash::warning('Debe agregar al menos una patente a la solicitud');
                    Redirect::to("deptcombust/solicitud");
                }
            } else {
                $this->vehiculos = Load::model('vehiculos')->find();
            }
        } else {
            Redirect::to("/");
        }
    }

    public function salida($id) {
        if (Auth::is_valid()) {
            $this->solicitud = Load::model('solicitud')->find_by_id($id);
        } else {
            Redirect::to();
        }
    }

    public function addPatente() {
        View::select('addPatente', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $revisapatente = new revisacargas();
                $patente = Load::model('vehiculos')->find_by_id((int) $id);
                $this->litros = $revisapatente->revisapatente($patente);
                $this->patente = Load::model('vehiculos')->find_by_id((int) $id);
                $this->trabajos = Load::model('trabajos')->find();
            }
        } else {
            Redirect::to("/");
        }
    }

    public function imprimir($id) {
        if (Auth::is_valid()) {
            View::select('imprimir', 'imprimir');
            $this->solicitud = Load::model('solicitud')->find_by_id($id);
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo para crear solicitud
    //Codigo para ver solicitudes
    public function versolicitud() {
        if (Auth::is_valid()) {
            
        } else {
            Redirect::to("/");
        }
    }

    public function listarsol() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listarsol');
        if (Auth::is_valid()) {
            $user = Auth::get('id');
        }
        $solicitudes = new Solicitud();        
        $this->data = $solicitudes->listarsolic($user);        
    }

    public function modaldetalle() {
        if (Auth::is_valid()) {
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
            if (Input::hasPost('id') and Input::hasPost('idune')) {                
                $idmacro = Input::post('id');
                $idune = Input::post('idune');
                if ($idune == '1000') {
                    $this->patentes = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $factual, $idune = 0);
                    $this->mes3 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $finMes3, $idune = 0);
                    $this->mes2 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes2, $finMes2, $idune = 0);
                    $this->mes1 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes1, $finMes1, $idune = 0);
                    $this->actual = Load::model('macrotipo')->listarmacro($idmacro, $fimes, $factual, $idune = 0);
                }  else {
                    $this->patentes = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $factual, $idune);
                    $this->mes3 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $finMes3, $idune);
                    $this->mes2 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes2, $finMes2, $idune);
                    $this->mes1 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes1, $finMes1, $idune);
                    $this->actual = Load::model('macrotipo')->listarmacro($idmacro, $fimes, $factual, $idune);
                }                
            }  elseif (Input::hasPost('id')) {
                if (Auth::get('privilegio') == 'AD_TVO_COMBUST'){
                    $unegocio = Load::model('unegocio')->relacion(Auth::get('id'));
                    foreach ($unegocio as $item):
                        $idune = $item->id;
                    endforeach;
                    $idmacro = Input::post('id');
                    $this->patentes = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $factual, $idune);                
                    $this->mes3 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $finMes3, $idune);
                    $this->mes2 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes2, $finMes2, $idune);
                    $this->mes1 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes1, $finMes1, $idune);
                    $this->actual = Load::model('macrotipo')->listarmacro($idmacro, $fimes, $factual, $idune);
                }else{
                    $idmacro = Input::post('id');
                    $this->patentes = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $factual, $idune = 0);                
                    $this->mes3 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes3, $finMes3, $idune = 0);
                    $this->mes2 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes2, $finMes2, $idune = 0);
                    $this->mes1 = Load::model('macrotipo')->listarmacro($idmacro, $iniMes1, $finMes1, $idune = 0);
                    $this->actual = Load::model('macrotipo')->listarmacro($idmacro, $fimes, $factual, $idune = 0);
                }                
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo para ver solicitudes
    public function anular() {
        if (Auth::is_valid()) {
            if (Input::hasPost('solicitud')) {
                $solicitud = Input::post('solicitud');
                $id = $solicitud['id'];
                if (Load::model('solicitud')->anulaSolicitud($id)) {
                    Flash::valid('Solicitud Anula');
                    Redirect::to("deptcombust/versolicitud");
                } else {
                    Flash::warning('Lo sentimos no se pudo anular este registro');
                    Redirect::to("deptcombust/versolicitud");
                }
            }
        } else {
            Redirect::to("/");
        }
    }
    public function anulaParcial($id) {
        if (Auth::is_valid()) {
            $detalle = Load::model('detalle')->find_by_id((int) $id);
            if (Load::model('vehiculos')->devuelveLitros($detalle->vehiculos_id, $detalle->litros)) {
                if (Load::model('detalle')->eliminarRegistro($id)) {
                    Flash::valid('Registro eliminado');
                    Redirect::to("deptcombust/versolicitud");
                }
            }  else {
                Flash::warning('Lo sentimos no se pudo realizar la acción solicitada.');
                Redirect::to("deptcombust/versolicitud");
            }
        }  else {
            Redirect::to("/");
        }
    }

    public function modaleliminar() {
        View::select('modaleliminar', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->solicitud = Load::model('solicitud')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    //Codigo para asignar litros
    public function listasignacion() {
        if (Auth::is_valid()) {
            $this->page = Load::model('solicitud')->find("conditions: estado='EN PROCESO'");
        } else {
            Redirect::to("/");
        }
    }

    public function asigna($id, $page = 1) {
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                if (Load::model('detalle')->asignaLitros(Input::post('solicitud'), Input::post('id'), Input::post('lasignados'), Input::post('camiones_id'))) {
                    Flash::valid('Operacion Exitosa');
                    Redirect::to("deptcombust/listasignacion");
                } else {
                    Flash::error('Operacion Fallida');
                    Redirect::to("deptcombust/listasignacion");
                }
            } else {
                $this->page = Load::model('detalle')->find("conditions: solicitud_id=$id");
                $this->solicitud = Load::model('solicitud')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo para asignar litros
    //Codigo para el ingreso de vales
    public function ingresovales($page = 1) {
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                if (Load::model('detalle')->ingresoVales(Input::post('solc'), Input::post('id'), Input::post('frentrega'), Input::post('lcargados'), Input::post('nvale'), Input::post('hactual'), Input::post('qrecibe'), Input::post('carga'), Input::post('loguser'), Input::post('logfecha'), Input::post('loghora'))) {
                    Flash::valid('Operacion Exitosa');
                    Redirect::to("deptcombust/ingresovales");
                } else {
                    Flash::error('Operacion Fallida');
                    Redirect::to("deptcombust/ingresovales");
                }
            }
        } else {
            Redirect::to("/");
        }
    }

    public function tablavales($page = 1) {
        View::select('tablavales', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->solicitud = $solicitud = Load::model('solicitud')->find_by_id((int) $id);
                if ($solicitud->estado == 'EN PROCESO') {                   
                    Flash::info('Esta solicitud aun pasa por el proceso de asignacion de litros.');
                    Redirect::to("deptcombust/error_vale");
                }  else {
                    $this->page = Load::model('detalle')->find("conditions: solicitud_id=$id");
                    $this->solicitud_id = $id;
                }                
            } else {
                Flash::error('Algo salio mal.');
                Redirect::to("deptcombust/error_vale");
            }
        } else {
            Redirect::to("/");
        }
    }
    public function error_vale() {
        View::template('');
        if (Auth::is_valid()) {
            
        }  else {
            Redirect::to("/");
        }
    }
    function nocarga() {
        if (Auth::is_valid()) {
            if (Input::hasPost('estado') && Input::hasPost('id')) {
                if (Input::post('estado') == 'true') {
                    $data = array('id' => Input::post('id'), 'frentrega' => '', 'lcargados' => '', 'nvale' => '', 'hactual' => '', 'qrecibe' => '', 'carga' => 1, 'estado' => 'CERRADA');
                    $nocarga = new Detalle($data);
                    if ($nocarga->update()) {
                        
                    }
                } else {
                    $data = array('id' => Input::post('id'), 'carga' => 0);
                    $nocarga = new Detalle($data);
                    if ($nocarga->update()) {
                        
                    }
                }
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo para ingresar vales
    //Codigo para mostrar detalle de solicitud en modal
    public function detalle() {
        View::select('detalle', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->solicitud = Load::model('solicitud')->find_by_id((int) $id);
                $this->detalle = Load::model('detalle')->find("conditions: solicitud_id=$id");
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin detalle modal

    public function solicitudpdf() {
        View::template(null);
        if (Auth::is_valid()) {
            if (Input::hasPost('solicitud')) {
                $solicitud = Input::post('solicitud');
                $id = $solicitud['id'];
                $this->solicitud = Load::model('solicitud')->find_by_id($id);
                $this->detalle = Load::model('detalle')->find("conditions: solicitud_id=$id");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function tabla_1() {
        
    }

    public function pedir() {
        View::select('pedir', FALSE);
        $datos['datos'] = Load::model('solicitud')->find();
        echo json_encode($datos);
    }

    //Codigo para gestionar surtidores
    public function surtidores($page = 1) {
        if (Auth::is_valid()) {
            $this->page = Load::model('camiones')->paginate("page: $page", 'per_page: 10');
        } else {
            Redirect::to("/");
        }
    }

    public function editarsurtidor($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('camiones')) {
                if (Load::model('camiones')->editCamion(Input::post('camiones'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("deptcombust/surtidores/");
                }
            } else {
                $this->camiones = Load::model('camiones')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    public function eliminarsurtidor($id) {
        if (Auth::is_valid()) {
            if (Load::model('camiones')->deletCamion($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to("deptcombust/surtidores/");
            } else {
                Flash::error('Lo sentimos, no se pudo eliminar este surtidor');
                Redirect::to("deptcombust/surtidores/");
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo gestiona surtidores
    //Codigo para la asignacion de ruta
    public function asignaruta() {
        if (Auth::is_valid()) {
            
        } else {
            Redirect::to("/");
        }
    }

    public function exportaRuta() {
        if (Auth::is_valid()) {
            View::select('exportaRuta', '');
            if (Input::hasPost('ruta')) {
                $post = Input::post('ruta');
                $this->busqueda = Load::model('detalle')->find("conditions: (fentrega='" . $post['fentrega'] . "') and (camiones_id='" . $post['id'] . "')");
            } else {
                Redirect::to("deptcombust/asignaruta/");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function detmacro() {
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $unegocio_id = Input::post('id');                
                $this->unegocio = $unegocio_id;
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
                $this->macrotipo = Load::model('macrotipo')->find();
                $fimes = date('01-m-Y');
                $factual = date('d-m-Y');
                if ($unegocio_id === '1000') {
                    $this->page1 = Load::model('macrotipo')->report_meses($iniMes3, $finMes3);
                    $this->page2 = Load::model('macrotipo')->report_meses($iniMes2, $finMes2);
                    $this->page3 = Load::model('macrotipo')->report_meses($iniMes1, $finMes1);
                    $this->page4 = Load::model('macrotipo')->report_meses($fimes, $factual);
                }  else {
                    $this->page1 = Load::model('macrotipo')->report_meses2($iniMes3, $finMes3, $unegocio_id);
                    $this->page2 = Load::model('macrotipo')->report_meses2($iniMes2, $finMes2, $unegocio_id);
                    $this->page3 = Load::model('macrotipo')->report_meses2($iniMes1, $finMes1, $unegocio_id);
                    $this->page4 = Load::model('macrotipo')->report_meses2($fimes, $factual, $unegocio_id);
                }                                               
            } else {
                Redirect::to("/");
            }
        } else {
            Redirect::to("/");
        }
    }

    //Fin codigo para asignar ruta

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
