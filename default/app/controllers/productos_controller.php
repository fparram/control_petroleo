<?php

class ProductosController extends AppController{
    
    public function index() {
        if (Auth::is_valid()) {
            $this->productos = Load::model('productos')->find();
        }  else {
            Redirect::to("/");
        }
    }
    public function nuevoProducto() {
        if (Auth::is_valid()) {
            if (Input::hasPost('productos')) {
                if (Load::model('productos')->crearProducto(Input::post('productos'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                }  else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            }  else {
                Redirect::to("/");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function editarProducto($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('productos')) {
                if (Load::model('productos')->editarProducto(Input::post('productos'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                }  else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            }  else {
                $this->productos = Load::model('productos')->find_by_id((int) $id);
            }
        }  else {
             Redirect::to("/");
        }
    }
    public function eliminarProducto($id) {
        if (Auth::is_valid()) {
            if (Load::model('productos')->eliminarProducto($id)) {
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
    public function cambiaEstado4() {        
        if(Auth::is_valid()){
            if (Input::hasPost('estado') && Input::hasPost('id')){
                $id = Input::post('id');                
                if (Input::post('estado') == 'true'){
                    $cambiaEstado = array('id'=> $id, 'estado'=> "ACTIVO");                    
                }else{
                    $cambiaEstado = array('id'=> $id, 'estado'=> "DESACTIVA");                   
                }                 
                if (Load::model('productos')->editarProducto($cambiaEstado)) {                    
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