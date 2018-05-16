<?php

class GpsController extends AppController {

    public function index() {
        if (Auth::is_valid()) {            
            $gps_service = new gps_service();
            $this->listado = $gps_service->tracker_list();
            
        }  else {
            Redirect::to("/");
        }
    }
    public function consulta_gps() {
        if (Auth::is_valid()) {
            $gps_service = new gps_service();
            $this->tracker_list_id = $gps_service->tracker_list_id();
        }  else {
            Redirect::to("/");
        }
    }
    public function listaxtracker_id() {
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $data = Input::post('consulta');
                $tracker_id = $data['id'];
                $desde = $data['desde'];
                $hasta = $data['hasta'];                
                $gps_service = new gps_service();
                $resultado = $gps_service->track_list($tracker_id, $desde, $hasta);
                $this->listado = $resultado;
                $this->sumar = $resultado;
                $this->tracker_id = $tracker_id;
                $this->desde = $desde;
                $this->hasta = $hasta;
            }  else {
                Redirect::to();
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function lista_general() {
        ini_set('max_execution_time', '1000');
        if (Auth::is_valid()) {
            if (Input::hasPost('consulta')) {
                $data = Input::post('consulta');
                $desde = $data['desde'];
                $hasta = $data['hasta'];
                $gps_service = new gps_service();                
                $resultado = $gps_service->track_list_general();
                foreach ($resultado['data'] as $item) {                    
                    $respuesta = $gps_service->track_list_ciclo($item['id'], $desde, $hasta);
                    $arreglo = json_decode($respuesta, TRUE);
                    if (count($arreglo['list']) != 0) {
                        $patente = $item['patente'];
                        $listado["$patente"] = $arreglo['list'];
                    }                                        
                }                
                $this->patentes = $resultado;
                $this->listado = $listado;
                $this->desde = $desde;
                $this->hasta = $hasta;                
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function exporta_id() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL        
        if (Auth::is_valid()) {
            if (Input::hasPost('exporta')) {
                $data = Input::post('exporta');
                $tracker_id = $data['id'];
                $desde = $data['desde'];
                $hasta = $data['hasta'];
                $gps_service = new gps_service();
                $this->listado = $gps_service->track_list($tracker_id, $desde, $hasta);
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function exporta_general() {
        ini_set('max_execution_time', '1000');
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL        
        if (Auth::is_valid()) {
            if (Input::hasPost('exporta')) {
                $data = Input::post('exporta');
                $desde = $data['desde'];
                $hasta = $data['hasta'];
                $gps_service = new gps_service();                
                $resultado = $gps_service->track_list_general();
                foreach ($resultado['data'] as $item) {                    
                    $respuesta = $gps_service->track_list_ciclo($item['id'], $desde, $hasta);
                    $arreglo = json_decode($respuesta, TRUE);
                    if (count($arreglo['list']) != 0) {
                        $patente = $item['patente'];
                        $listado["$patente"] = $arreglo['list'];
                    }                                        
                }                
                $this->patentes = $resultado;
                $this->listado = $listado;
                $this->desde = $desde;
                $this->hasta = $hasta;                
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function listargps() {
        View::template('');
        if (Auth::is_valid()) {
            $gps_service = new gps_service();
            $this->data = $gps_service->tracker_list();
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