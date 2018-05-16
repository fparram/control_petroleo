<?php

class VehiculosController extends AppController {
    //Codigo para gestionar patentes
    public function index($page = 1) {
        if (Auth::is_valid()) {           
            
        }  else {
            Redirect::to("/");
        }
    }
    public function nuevovehi() {
        if (Auth::is_valid()) {
            if (Load::model('vehiculos')->crearVehiculo(Input::post('vehiculos'))) {
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
    public function editarvehi() {
        if (Auth::is_valid()) {
            if (Input::hasPost('vehiculos')) {
                if (Load::model('vehiculos')->editVehiculo(Input::post('vehiculos'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to();
                }
            }
        }  else {
            Redirect::to();
        }
    }
    public function eliminarvehi() {
        if (Auth::is_valid()) {
            if (Input::hasPost('vehiculos')) {
                $data = Input::post('vehiculos');
                $id = $data['id'];
                if (Load::model('vehiculos')->deletVehiculo((int)$id)) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to();
                } else {
                    Flash::error('Lo sentimos, no se pudo eliminar este usuario.');
                    Redirect::to();
                }
            }  else {
                Redirect::to("/");    
            }            
        } else {
            Redirect::to("/");
        }
    }
    public function modaleditarvehi() {
        View::select('modaleditarvehi', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->vehiculos = Load::model('vehiculos')->find_by_id((int) $id);
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function modaleliminarvehi() {
        View::select('modaleliminarvehi', FALSE);
        if (Auth::is_valid()) {
            if (Input::hasPost('id')) {
                $id = Input::post('id');
                $this->vehiculos = Load::model('vehiculos')->find_by_id((int) $id);
            }
        } else {
            Redirect::to("/");
        }
    }
    //Fin gestion patentes
    //Codigo para gestionar tipos de vehiculos
    public function tvehiculos($page = 1) {
        if (Auth::is_valid()) {
            $this->page = Load::model('tvehiculos')->find();
        }  else {
            Redirect::to("/");
        }
    }
    public function nuevotipo() {
        if (Auth::is_valid()) {
            if (Load::model('tvehiculos')->crearTvehiculo(Input::post('tvehiculos'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/tvehiculos");
            }  else {
                Flash::error('Falló Operación');
                Redirect::to("vehiculos/tvehiculos");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function editartipo($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('tvehiculos')) {
                if (Load::model('tvehiculos')->editTvehiculo(Input::post('tvehiculos'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("vehiculos/tvehiculos");
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("vehiculos/tvehiculos");
                }
            } else {
                $this->tvehiculos = Load::model('tvehiculos')->find_by_id((int) $id);
            }
        }  else {
            Redirect::to();
        }
    }
    public function eliminartipo($id) {
        if (Auth::is_valid()) {
            if (Load::model('tvehiculos')->deletTvehiculo($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/tvehiculos");
            } else {
                Flash::error('Lo sentimos, no se pudo eliminar este usuario.');
                Redirect::to("vehiculos/tvehiculos");
            }
        } else {
            Redirect::to("/");
        }
    }
    //Fin gestion tipos de vehiculos
    //Codigo para gestion de propietarios
    public function propietarios() {        
        if (Auth::is_valid()) {
            $this->page = Load::model('propietario')->find();
        }  else {
            Redirect::to();
        }
    }
    public function nuevoprop() {
        if (Auth::is_valid()) {
            if (Load::model('propietario')->crearPvehiculo(Input::post('propietario'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/propietario");
            }  else {
                Flash::error('Falló Operación');
                Redirect::to("vehiculos/propietario");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function editarprop($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('propietario')) {
                if (Load::model('propietario')->editPvehiculo(Input::post('propietario'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("vehiculos/propietario");
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("vehiculos/propietario");
                }
            } else {
                $this->propietario = Load::model('propietario')->find_by_id((int) $id);
            }
        }  else {
            Redirect::to();
        }
    }
    public function eliminarprop($id) {
        if (Auth::is_valid()) {
            if (Load::model('propietario')->deletPvehiculo($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/tvehiculos");
            } else {
                Flash::error('Lo sentimos, no se pudo eliminar este usuario.');
                Redirect::to("vehiculos/tvehiculos");
            }
        } else {
            Redirect::to("/");
        }
    }
    //Fin gestion de propietarios
    public function listarvehiculos() {
        View::template('');
        if (Auth::is_valid()) {
            $arreglo["data"] = Load::model('vehiculos')->find_all_by_sql("select vehi.id, vehi.marca, vehi.modelo, vehi.patente, tv.nombre as tvehiculo, p.nombre as propietario, vehi.rinde, vehi.delta, vehi.tmarcador, vehi.limite, vehi.conteo from vehiculos as vehi
join tvehiculos as tv on tv.id=vehi.tvehiculos_id
join propietario as p on p.id=vehi.propietario_id
order by vehi.patente asc");
            $this->data = $arreglo; 
        }  else {
            Redirect::to("/");
        }
    }
    public function trabajos() {
        if (Auth::is_valid()) {
            $this->page = Load::model('trabajos')->find();
        }  else {
            Redirect::to("/");
        }
    }
    public function nuevotrab() {
        if (Auth::is_valid()) {
            if (Load::model('trabajos')->crearTrabajo(Input::post('trabajos'))) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/trabajos");
            }  else {
                Flash::error('Falló Operación');
                Redirect::to("vehiculos/trabajos");
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function editartrab($id) {
        if (Auth::is_valid()) {
            if (Input::hasPost('trabajos')) {
                if (Load::model('trabajos')->editTrabajo(Input::post('trabajos'))) {
                    Flash::valid('Operación Exitosa');
                    Redirect::to("vehiculos/trabajos");
                } else {
                    Flash::error('Falló Operación');
                    Redirect::to("vehiculos/trabajos");
                }
            } else {
                $this->trabajos = Load::model('trabajos')->find_by_id((int) $id);
            }
        }  else {
            Redirect::to("/");
        }
    }
    public function eliminartrab($id) {
        if (Auth::is_valid()) {
            if (Load::model('trabajos')->deletTrabajo($id)) {
                Flash::valid('Operación Exitosa');
                Redirect::to("vehiculos/trabajos");
            } else {
                Flash::error('Lo sentimos, no se pudo eliminar este usuario.');
                Redirect::to("vehiculos/trabajos");
            }
        } else {
            Redirect::to("/");
        }
    }
    public function relacion_tvtb($id) {
        if (Auth::is_valid()) {
            $listado = new Trabajos();
            $rela = new Tvehiculos_trabajos();
            $this->rela = $rela->find("conditions: tvehiculos_id=" . $id);
            $this->page = $listado->find();                
            $this->tvehiculos = Load::model('tvehiculos')->find_by_id($id);
        }  else {
            Redirect::to("/");
        }
    }
    public function tvtb() {
        if (Auth::is_valid()) {
            if (Input::hasPost('estado') && Input::hasPost('id')) {
                if (Input::post('estado') == 'false') {                    
                    $elim = new Tvehiculos_trabajos();
                    $data = Input::post('id');
                    if ($elim->delete($data)) {                        
                    }  else {                 
                    }
                }  else {
                    $data = array('trabajos_id' => Input::post('id'), 'tvehiculos_id' => Input::post('idd'));
                    $relacion = new Tvehiculos_trabajos($data);
                    if ($relacion->create()) {                        
                    }  else {                       
                    }
                }
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