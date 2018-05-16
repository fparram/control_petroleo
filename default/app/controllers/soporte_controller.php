<?php

class SoporteController extends AppController {

    public function index($page = 1) {
        if (Auth::is_valid()) {
            $this->page = Load::model('ssoporte')->paginate("page: $page", 'per_page: 10', 'order: estado asc');
        } else {
            Redirect::to("/");
        }
    }

    public function solicitudes($page = 1) {
        if (Auth::is_valid()) {
            $user = Auth::get('id');
            $this->page = Load::model('ssoporte')->paginate("conditions: users_id=$user","page: $page", 'per_page: 10');
        } else {
            Redirect::to("/");
        }
    }

    public function nuevo() {
        if (Auth::is_valid()) {
            if (Load::model('ssoporte')->crearSsoporte(Input::post('ssoporte'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to("soporte/solicitudes");
            } else {
                Flash::error('Solo sentimos, no se pudo guardar esta solicitud.');
                Redirect::to("soporte/solicitudes");
            }
        } else {
            Redirect::to("/");
        }
    }
    public function respuesta() {
        if (Auth::is_valid()) {            
            if (Load::model('ssoporte')->addRespuesta(Input::post('ssoporte'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to();
            }  else {
                Flash::error('Solo sentimos, no se pudo guardar esta respuesta.');
                Redirect::to();
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
