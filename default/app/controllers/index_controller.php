<?php

Load::lib('fechas');

class IndexController extends AppController
{

    public function index()
    {
        if (Auth::is_valid()) {
            $fechas = new fechas();
            $mes = date('m');
            $this->nm1 = $fechas->traduceMes($mes-1);
            $this->nm2 = $fechas->traduceMes($mes-2);
            $this->nm3 = $fechas->traduceMes($mes-3);
            
            $iniMes3 = $fechas->fechaInicial3(date('m'));
            $finMes3 = $fechas->fechaFinal3(date('m'));            
            $iniMes2 = $fechas->fechaInicial2(date('m'));
            $finMes2 = $fechas->fechaFinal2(date('m'));            
            $iniMes1 = $fechas->fechaInicial1(date('m'));
            $finMes1 = $fechas->fechaFinal1(date('m'));
            $fimes = date('01-m-Y');
            $factual = date('d-m-Y');
            if ((Auth::get('privilegio') == 'ADMINISTRADOR') or (Auth::get('privilegio') == 'ADMIN_COMBUST')) {
                if (Auth::get('privilegio') == 'ADMINISTRADOR') {
                    View::template('administrador-index');
                } else {
                    View::template('admin-index-combust');
                }
                $this->page1 = Load::model('macrotipo')->report_meses($iniMes3, $finMes3);
                $this->page2 = Load::model('macrotipo')->report_meses($iniMes2, $finMes2);
                $this->page3 = Load::model('macrotipo')->report_meses($iniMes1, $finMes1);
                $this->page4 = Load::model('macrotipo')->report_meses($fimes, $factual);

                $this->lisune1 = Load::model('unegocio')->report_diferencial($iniMes2, $finMes2);
                $this->lisune2 = Load::model('unegocio')->report_diferencial($iniMes1, $finMes1);
                $this->lisune3 = Load::model('unegocio')->report_diferencial($fimes, $factual);
            
                $this->totales1 = Load::model('detalle')->report_totales($iniMes3, $finMes3);
                $this->totales2 = Load::model('detalle')->report_totales($iniMes2, $finMes2);
                $this->totales3 = Load::model('detalle')->report_totales($iniMes1, $finMes1);
                $this->totales4 = Load::model('detalle')->report_totales($fimes, $factual);
            }  else {
                View::template('admin-index-combust');
                $unegocio = Load::model('unegocio')->relacion(Auth::get('id'));
                foreach ($unegocio as $unegocio):
                    $unegocio_id = $unegocio->id;
                endforeach;
                
                $this->page1 = Load::model('macrotipo')->report_meses2($iniMes3, $finMes3, $unegocio_id);
                $this->page2 = Load::model('macrotipo')->report_meses2($iniMes2, $finMes2, $unegocio_id);
                $this->page3 = Load::model('macrotipo')->report_meses2($iniMes1, $finMes1, $unegocio_id);
                $this->page4 = Load::model('macrotipo')->report_meses2($fimes, $factual, $unegocio_id);

                $this->lisune1 = Load::model('unegocio')->report_diferencial2($iniMes2, $finMes2, $unegocio_id);
                $this->lisune2 = Load::model('unegocio')->report_diferencial2($iniMes1, $finMes1, $unegocio_id);
                $this->lisune3 = Load::model('unegocio')->report_diferencial2($fimes, $factual, $unegocio_id);
            
                $this->totales1 = Load::model('detalle')->report_totales2($iniMes3, $finMes3, $unegocio_id);
                $this->totales2 = Load::model('detalle')->report_totales2($iniMes2, $finMes2, $unegocio_id);
                $this->totales3 = Load::model('detalle')->report_totales2($iniMes1, $finMes1, $unegocio_id);
                $this->totales4 = Load::model('detalle')->report_totales2($fimes, $factual, $unegocio_id);
            }
            if (Auth::get('privilegio') == 'ADMIN_ADQ') {
                View::select('index-adq','admin-adq');
            }
            
        } else {
            
        }
    }
    public function reporte(){
        if(Auth::is_valid()){
            
        }else{
            Redirect::to("/");            
        }
    }
    
    public function logout() {
        
        Auth::destroy_identity();
        Redirect::to("/");
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
