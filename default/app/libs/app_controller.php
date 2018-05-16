<?php

/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller
{

    final protected function initialize()
    {       
//        $acl = new MyAcl('privilegios');  //si no se especifica el archivo a usar, por defecto busca en privilegios.ini
//        $modulo = $this->module_name;$controlador = $this->controller_name;$accion = $this->action_name;                       
//        if (Auth::is_valid()) {
//            $privilegio = Auth::get('privilegio');            
//            if (Session::has('tiempo_cliente')) {
//                $vida_session = time() - Session::get('tiempo_cliente');
//                if ($vida_session > Config::get('config.application.sesion_activa')) {
//                    Session::delete('tiempo_cliente');
//                    Auth::destroy_identity();
//                    Flash::warning('Su sesi&oacute;n ha finalizado, debe ingresar sus datos nuevamente.');
//                    Redirect::to("/");exit();                    
//                }
//            }
//            Session::set('tiempo_cliente', time());
//        }else{ 
//            $privilegio = 'ADMINISTRADOR';            
//        }        
//        if (!$acl->check($privilegio, $modulo, $controlador, $accion)) { //verificamos si tiene ó no privilegios            
//            Flash::error("No posees suficientes PRIVILEGIOS para acceder a: {$modulo}/{$controlador}/{$accion}");          
//            View::select('error_privilegio','administrador');return false;            
//        }
        if (!Auth::is_valid() && $this->controller_name != 'login') {
            Redirect::to("login");
        } 
    }

    final protected function finalize()
    {
        if (Auth::is_valid()) {
            if (Session::has('tiempo_cliente')) {
                $vida_session = time() - Session::get('tiempo_cliente');
                if ($vida_session > Config::get('config.application.sesion_activa')) {
                    Session::delete('tiempo_cliente');
                    Auth::destroy_identity();
                    Flash::warning('Su sesi&oacute;n ha finalizado, debe ingresar sus datos nuevamente.');
                    Redirect::to("/");
                    exit();
                }
            }
            Session::set('tiempo_cliente', time());
        }
    }

}
