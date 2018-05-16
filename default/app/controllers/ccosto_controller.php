<?php

class CcostoController extends AppController {

    public function index() {
        if (Auth::is_valid()) {
            $this->ccosto = Load::model('ccosto')->find();
        } else {
            Redirect::to("/");
        }
    }

    public function nuevo() {
        if (Auth::is_valid()) {
            if (Input::hasPost('ccosto')) {
                if (Load::model('ccosto')->crearCcosto(Input::post('ccosto'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            } else {
                Redirect::to("/");
            }
        } else {
            Redirect::to("/");
        }
    }
    public function editarCcosto($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('ccosto')) {
                if (Load::model('ccosto')->editarCcosto(Input::post('ccosto'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                }  else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            }  else {
                $this->ccosto = Load::model('ccosto')->find_by_id((int) $id);
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function eliminarCcosto($id) {
        if (Auth::is_valid()) {
            if (Load::model('ccosto')->eliminarCcosto($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to();
            }  else {
                Flash::error('Falló Operación');
                Redirect::to();
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function cambiaEstado2() {        
        if(Auth::is_valid()){
            if (Input::hasPost('estado') && Input::hasPost('id')){
                $id = Input::post('id');                
                if (Input::post('estado') == 'true'){
                    $cambiaEstado = array('id'=> $id, 'estado'=> "ACTIVO");                    
                }else{
                    $cambiaEstado = array('id'=> $id, 'estado'=> "DESACTIVA");                   
                }                 
                if (Load::model('ccosto')->editarCcosto($cambiaEstado)) {                    
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            }
        }else{
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
