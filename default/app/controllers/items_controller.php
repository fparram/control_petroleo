<?php

class ItemsController extends AppController {

    public function index($id) {
        if (Auth::is_valid()) {
            $this->items = Load::model('items')->find("conditions: ccosto_id=$id");
            $this->ccosto = Load::model('ccosto')->find_by_id((int) $id);
        } else {
            Redirect::to("/");
        }
    }

    public function nuevoItem() {
        if (Auth::is_valid()) {
            if (Input::hasPost('items')) {
                $data = Input::post('items');
                if (Load::model('items')->crearItem(Input::post('items'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("items/index/".$data['ccosto_id']);
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("items/index/".$data['ccosto_id']);
                }
            } else {
                Redirect::to("/");
            }
        } else {
            Redirect::to("/");
        }
    }

    public function editarItem($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('items')) {
                $data = Input::post('items');
                if (Load::model('items')->editarItem(Input::post('items'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("items/index/".$data['ccosto_id']);
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("items/index/".$data['ccosto_id']);
                }
            } else {
                $this->items = Load::model('items')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    public function eliminarItem($id) {
        if (Auth::is_valid()) {
            $data = Load::model('items')->find_by_id((int) $id);
            if (Load::model('items')->eliminarItem($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to("items/index/".$data->ccosto_id);
            } else {
                Flash::error('Falló Operación');
                Redirect::to("items/index/".$data->ccosto_id);
            }
        } else {
            Redirect::to("/");
        }
    }
    public function cambiaEstado3() {        
        if(Auth::is_valid()){
            if (Input::hasPost('estado') && Input::hasPost('id')){
                $id = Input::post('id');
                $data = Load::model('items')->find_by_id((int) $id);                
                if (Input::post('estado') == 'true'){
                    $cambiaEstado = array('id'=> $id, 'estado'=> "ACTIVO");                    
                }else{
                    $cambiaEstado = array('id'=> $id, 'estado'=> "DESACTIVA");                   
                }                 
                if (Load::model('items')->editarItem($cambiaEstado)) {                    
                    Flash::valid('Operación Exitosa');
                    Redirect::to("items/index/".$data->ccosto_id);
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("items/index/".$data->ccosto_id);
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
