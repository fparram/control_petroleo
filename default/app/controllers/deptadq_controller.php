<?php

class DeptadqController extends AppController {

    public function index() {
        if (Auth::is_valid()) {
            
        } else {
            Redirect::to("/");
        }
    }
    //Seccion para el ingreso de solicitudes
    public function solicitud() {
        if (Auth::is_valid()) {
            
        }  else {
            Redirect::to("/");
        }
    }
    public function addProducto() {
        View::select('addProducto', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->producto = Load::model('productos')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }
    public function crearSolicitud() {
        if (Auth::is_valid()) {
            
        }  else {
            Redirect::to("/");
        }
    }
    public function getCcosto() {
        if (Input::isAjax()) {
            View::select('getCcosto', null);
        }
        if (Input::hasPost('unegocio_id')) {

            $this->unegocio_id = Input::post('unegocio_id');
        }
    }
    public function getItems() {
        if (Input::isAjax()) {
            View::select('getItems', null);
        }
        if (Input::hasPost('ccosto_id')) {

            $this->ccosto_id = Input::post('ccosto_id');
        }
    }
    //Fin seccion para el ingreso de solicitudes
    //Seccion para el ingreso de facturas
    public function verFacturas() {
        if (Auth::is_valid()) {
            
        }  else {
            Redirect::to("/");
        }
    }

    public function listadq() {
        View::template(NULL); //Agregado para que no envié todo el html(Beta2). En Beta1 $this->template=NULL
        View::select('listadq');
        if (Auth::is_valid()) {
            $arreglo["data"] = Load::model('solicitud')->find_all_by_sql("select a.id, a.frecep, a.ffact, a.tdoc, a.ndoc, a.proveedor, a.neto, a.iva, a.adicional, une.nombre as unegocio, a.correlativo, u.nombre as usuario, a.observ, a.estado, a.fmerca from adq as a
left join users as u on u.id=a.users_id
left join unegocio une on une.id=a.unegocio_id");
            $this->data = $arreglo;
        }
    }

    public function nuevo() {
        if (Auth::is_valid()) {
            if (Load::model('adq')->crearAdq(Input::post('adq'))) {
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

    public function editar() {
        if (Auth::is_valid()) {
            if (Input::post('adq')) {
                if (Load::model('adq')->editAdq(Input::post('adq'))) {
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
    public function eliminar() {
        if (Auth::is_valid()) {
            if (Input::hasPost('adq')) {
                $data = Input::post('adq');
                $id = $data['id'];
                if (Load::model('adq')->eliminarAdq((int)$id)) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
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

    public function modaleditar() {
        View::select('modaleditar', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->adq = Load::model('adq')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }

    public function modaleliminar() {
        View::select('modaleliminar', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->adq = Load::model('adq')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }
    //Fin seccion ingreso de facturas
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
