<?php

class ServicesController extends RestController {
    protected $fw = array(1 => array(
            "Nombre" => "Api Sistema de Combustible",
            "Descripcion" => "Sistema para el control de Combustibles"
        ),
        array(
            "Metodo" => "unegocioxfecha",
            "Tipo" => "Post",
            "description" => "Devuelve detalle de solicitudes realizadas por la unidad de negocio entre rango de fechas "
        ),
        array(
            "Metodo" => "patentexfecha",
            "Tipo" => "Post",
            "description" => "Devuelve detalle de solicitudes realizadas por patente entre rango de fechas "
        ),
        array(
            "Metodo" => "listado",
            "Tipo" => "Post",
            "description" => "Devuelve listado de entrega. Recive una fecha (fentrega) y el id del surtidor (camion_id). Todo en formato Json."
        ),
        array(
            "Metodo" => "solicitud",
            "Tipo" => "Get",
            "description" => "Devuelve la solicitud y su detalle. El id debe ir en la url, ej:http://petroleo.francisco.com/services/solicitud/50. Devuelve un Json."
        ),
    );
    public function get_users() {
        $users = new Users();
        $this->data = $users->find();
    }
    public function getAll() 
    {
        $this->data = $this->fw;
    }
    public function get_macrotipos() {
        $macrotipos = new Macrotipo();
        $this->data =$macrotipos->find();
    }
    public function post_unegocioxfecha() {
        $json = $this->param();
        $unegocio_id = (int)$json['unegocio_id'];
        $finicial = $json['finicial'];
        $ffinal = $json['ffinal'];
        $detalle = new Detalle();
        $this->data = $detalle->find("conditions: unegocio_id=$unegocio_id and fentrega >='$finicial' and fentrega <='$ffinal'");
    }
    public function post_patentexfecha() {
        $json = $this->param();
        $vehiculos_id = (int)$json['vehiculos_id'];
        $finicial = $json['finicial'];
        $ffinal = $json['ffinal'];
        $detalle = new Detalle();
        $this->data = $detalle->find("conditions: vehiculo_id=$vehiculos_id and fentrega >='$finicial' and fentrega <='$ffinal'");
    }
    public function post_listado(){
        $json = $this->param();
        $fecha = $json['fentrega'];
        $camion_id = $json['camion_id'];       
        $this->data = Load::model('detalle')->hojaRuta($camion_id, $fecha);
    }
    public function get_solicitud($solicitud_id) {
        $solicitud = Load::model('solicitud')->find_by_id((int) $solicitud_id);
        $detalle = Load::model('detalle')->find_all_by_solicitud_id((int) $solicitud_id);
        $resultado = array('solicitud' => '', 'detalle' => '');
        $resultado['solicitud'] = $solicitud;
        $resultado['detalle'] = $detalle;
        $this->data = $resultado;
    }
    public function get_listado($fecha, $camion_id) {
        $this->data = Load::model('detalle')->hojaRuta($camion_id, $fecha);
    }
}