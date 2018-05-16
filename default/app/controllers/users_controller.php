<?php

class UsersController extends AppController {

    //Inicio seccion para el control de perfiles de usuario
    public function index($page = 1) {
        if (Auth::is_valid()) {
            if (Input::hasPost('buscar')) {
                //Codigo para buscar en la tabla
                $texto = Input::post('buscar');
                $result = Load::model('users')->find("conditions: nombre=" . $texto['texto'] . " or apellido=" . $texto['texto']);
                $this->page = paginate($result, "page: $page", 'per_page: 10');
                //Fin
            } else {
                $this->page = Load::model('users')->paginate("page: $page", 'per_page: 10');
            }
        } else {
            Redirect::to("/");
        }
    }

    public function editar($id) {
        if (Auth::is_valid()) {
            if (Input::post('users')) {
                if (Load::model('users')->editUsuario(Input::post('users'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            } else {
                $this->users = Load::model('users')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    public function nuevo() {
        if (Auth::is_valid()) {
            if (Load::model('users')->crearUsuario(Input::post('users'))) {
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

    public function password() {
        if (Auth::is_valid()) {
            if (Load::model('users')->passUsuario(Input::post('users'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to();
            } else {
                Flash::error('Ocurrio un problema');
                Redirect::to();
            }
        } else {
            Redirect::to("/");
        }
    }

    public function eliminar($id) {
        if (Auth::is_valid()) {
            if (Load::model('users')->deletUsuario($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to();
            } else {
                Flash::error('Lo sentimos, no se pudo eliminar este usuario.');
                Redirect::to();
            }
        } else {
            Redirect::to("/");
        }
    }

//    public function buscar( $page = 1){
//        if (Auth::is_valid()){
//            if (Input::hasPost('buscar')){
//                $texto = Input::post('buscar');
//                $result = Load::model('users')->find("conditions: nombre=".$texto['texto']." or apellido=".$texto['texto']);
//                $this->page = paginate($result, "page: $page", 'per_page: 10');
//            }            
//        }else{
//            Redirect::to("/");
//        }
//    }
    //Fin seccion control de perfiles de usuario
    //Metodo cerrar sesion
    public function logout() {
        Session::delete('tiempo_cliente');
        Auth::destroy_identity();
        Redirect::to("/");
    }

    //Fin metodo cerrar sesion
    //Relaciones Usuarios con Unidades de Negocio
    public function relacionar($id) {
        if (Auth::is_valid()) {
            $listado = new Unegocio();
            $rela = new Users_unegocio();
            $this->rela = $rela->find("conditions: users_id=" . $id);
            $this->page = $listado->find("conditions: estado='ACTIVO'");
            $this->perfil = Load::model('users')->find_by_id($id);
        } else {
            Redirect::to("/");
        }
    }

    public function userUne() {
        if (Auth::is_valid()) {
            if (Input::hasPost('estado') && Input::hasPost('id')) {
                if (Input::post('estado') == 'false') {
                    $elim = new Users_unegocio();
                    $data = Input::post('id');
                    if ($elim->delete($data)) {
                        
                    } else {
                        
                    }
                } else {
                    $data = array('unegocio_id' => Input::post('id'), 'users_id' => Input::post('idd'));
                    $relacion = new Users_unegocio($data);
                    if ($relacion->create()) {
                        
                    } else {
                        
                    }
                }
            }
        } else {
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
