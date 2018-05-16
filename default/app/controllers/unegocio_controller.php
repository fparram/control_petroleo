<?php

Load::model('unegocio');

class UnegocioController extends AppController {
    //Seccion para el control de unidades de negocio
    public function index() {
        if (Auth::is_valid()) {
            $this->page = Load::model('unegocio')->find("order: nombre asc");
        } else {
            Redirect::to("/");
        }
    }

    public function nuevo() {
        if (Auth::is_valid()) {
            if (Load::model('unegocio')->crearUnegocio(Input::post('unegocio'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to();
            } else {
                Flash::error('Falló Operación');
                Redirect::to();
            }
        } else {
            Redirect::to("/");
        }
    }

    public function editar($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('unegocio')) {
                if (Load::model('unegocio')->editUnegocio(Input::post('unegocio'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            } else {
                $this->unegocio = Load::model('unegocio')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    public function eliminar($id) {
        if (Auth::is_valid()) {            
                if (Load::model('unegocio')->deletUnegocio($id)) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }            
        } else {
            Redirect::to("/");
        }
    }
    public function cambiaEstado() {        
        if(Auth::is_valid()){
            if (Input::hasPost('estado') && Input::hasPost('id')){
                $id = Input::post('id');                
                if (Input::post('estado') == 'true'){
                    $cambiaEstado = array('id'=> $id, 'estado'=> "ACTIVO");                    
                }else{
                    $cambiaEstado = array('id'=> $id, 'estado'=> "DESACTIVA");                   
                }                 
                if (Load::model('unegocio')->editUnegocio($cambiaEstado)) {                    
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
    public function relacionar($id) {
        if (Auth::is_valid()) {
            $listado = new Ccosto();
            $rela = new Unegocio_ccosto();
            $this->rela = $rela->find("conditions: unegocio_id=" . $id);
            $this->page = $listado->find("conditions: estado='ACTIVO'");
            $this->unegocio = Load::model('unegocio')->find_by_id($id);
        } else {
            Redirect::to("/");
        }
    }

    public function Une() {
        if (Auth::is_valid()) {
            if (Input::hasPost('estado') && Input::hasPost('id')) {
                if (Input::post('estado') == 'false') {
                    $elim = new Unegocio_ccosto();
                    $data = Input::post('id');
                    if ($elim->delete($data)) {
                        
                    } else {
                        
                    }
                } else {
                    $data = array('unegocio_id' => Input::post('idd'), 'ccosto_id' => Input::post('id'));
                    $relacion = new Unegocio_ccosto($data);
                    if ($relacion->create()) {
                        
                    } else {
                        
                    }
                }
            }
        } else {
            Redirect::to("/");
        }
    }    
    //Fin seccion unidades de negocio   
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
