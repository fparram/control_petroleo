<?php

//require 'fpdf/fpdf.php';
Load::lib('fpdf/fpdf');

class Solicitudpdf extends FPDF {

    public function Header() {
        $dia = date("d");
        $mes = date("F");
        $ano = date("Y");
        if ($mes == "January")
            $mes = "Enero";
        if ($mes == "February")
            $mes = "Febrero";
        if ($mes == "March")
            $mes = "Marzo";
        if ($mes == "April")
            $mes = "Abril";
        if ($mes == "May")
            $mes = "Mayo";
        if ($mes == "June")
            $mes = "Junio";
        if ($mes == "July")
            $mes = "Julio";
        if ($mes == "August")
            $mes = "Agosto";
        if ($mes == "September")
            $mes = "Septiembre";
        if ($mes == "October")
            $mes = "Octubre";
        if ($mes == "November")
            $mes = "Noviembre";
        if ($mes == "December")
            $mes = "Diciembre";
        $fecha = $dia . '/' . $mes . '/' . $ano;
        $this->Image('img/material-kit/logo_1.png', 10, 15, 20);
        $this->SetFont('Times', '', 8);
        
        
        
        $this->Cell(150);
        $this->Cell(15, 15, utf8_decode('Datos a la fecha: ' . $fecha), 0, 0, 'L');
        $this->Ln(3);
        $this->Cell(26);
        $this->Cell(15, 15, utf8_decode('INVERSIONES SANTA FE S.A.'), 0, 0, 'L');
        $this->Ln(3);
        $this->Cell(27);
        $this->Cell(15, 15, utf8_decode('DEPTO. DE COMBUSTIBLE'), 0, 0, 'L');
        $this->Ln(1);
        $this->Cell(29);
        $this->Line(35, 28, 80, 28);
        $this->Ln(2);
        $this->Cell(29);
        $this->Cell(15, 15, utf8_decode('DIRECCIÓN 4 NORTE #30'), 0, 0, 'L');
        $this->Ln(20);
        

        
    }

    public function contenido() {
        if (Input::post('solicitud')) {
            $tlitros = 0;
            $tlasig = 0;
            $tlcarga = 0;
            $post = Input::post('solicitud');
            $id = $post['id'];
            $solicitud = Load::model('solicitud')->find_by_id((int)$id);
            $detalle = Load::model('detalle')->find("conditions: solicitud_id=$id");
        }
        $this->SetFont('Times', 'B', 16);
        $this->Cell(0, 0, utf8_decode('Solicitud Nº'.$solicitud->id), 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 10, utf8_decode('('.$solicitud->fecha.')'), 0, 0, 'C');
        $this->Ln(20);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(30, 10, utf8_decode('Unidad de Negocio:'), 0, 0, 'C');
        $this->SetFont('Times', '', 8);
        $this->Cell(40, 10, utf8_decode($solicitud->getUnegocio()->nombre), 0, 0, 'R');
        $this->SetFont('Times', 'B', 10);
        $this->Cell(150, 10, utf8_decode('Solicitado por:'), 0, 0, 'C');
        $this->SetFont('Times', '', 8);
        $this->Cell(-30, 10, utf8_decode($solicitud->getUsers()->nombre.' '.$solicitud->getUsers()->apellido), 0, 0, 'R');
        $this->Ln(4);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(28, 10, utf8_decode('Fecha de Entrega:'), 0, 0, 'C');
        $this->SetFont('Times', '', 8);
        $this->Cell(25, 10, utf8_decode($solicitud->fentrega), 0, 0, 'R');
        $this->Ln(4);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(12, 10, utf8_decode('Estado:'), 0, 0, 'C');
        $this->SetFont('Times', 'B', 8);
        $this->Cell(46, 10, utf8_decode($solicitud->estado), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(15, 10, utf8_decode('Detalle:'), 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Times', '', 7);
        
        $this->Cell(10, 6, utf8_decode('Patente'), 1, 0, 'C', 'true');
        $this->Cell(35, 6, utf8_decode('T. de Vehiculo'), 1, 0, 'C', 'true');
        $this->Cell(37, 6, utf8_decode('Marca'), 1, 0, 'C', 'true');
        $this->Cell(35, 6, utf8_decode('Trabajo a Realizar'), 1, 0, 'C', 'true');
        $this->Cell(35, 6, utf8_decode('Ubicacion'), 1, 0, 'C', 'true');
        $this->Cell(15, 6, utf8_decode('L. Solicitados'), 1, 0, 'C', 'true');
        $this->Cell(15, 6, utf8_decode('L. Asignados'), 1, 0, 'C', 'true');       
        $this->Cell(15, 6, utf8_decode('L. Cargados'), 1, 0, 'C', 'true'); 
        $this->Ln();                       
        
        foreach ($detalle as $items) {
            $tlitros = $tlitros + $items->litros;
            $tlasig = $tlasig + $items->lasignados;
            $tlcarga = $tlcarga + $items->lcargados;
            $this->Cell(10, 6, utf8_decode($items->getVehiculos()->patente), 1, 0, 'C');
            $this->Cell(35, 6, utf8_decode($items->getVehiculos()->getTvehiculos()->nombre), 1, 0, 'C');
            $this->Cell(37, 6, utf8_decode($items->getVehiculos()->marca), 1, 0, 'C');
            $this->Cell(35, 6, utf8_decode($items->trabajo), 1, 0, 'C');
            $this->Cell(35, 6, utf8_decode($items->ubicacion), 1, 0, 'C');
            $this->Cell(15, 6, utf8_decode($items->litros), 1, 0, 'C');
            $this->Cell(15, 6, utf8_decode($items->lasginados), 1, 0, 'C');       
            $this->Cell(15, 6, utf8_decode($items->lcargados), 1, 0, 'C'); 
            $this->Ln();
        }
        
        $this->Ln(10);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(305, 10, utf8_decode('Total L. Solicitados:'), 0, 0, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(-265, 10, utf8_decode($tlitros), 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(304, 10, utf8_decode('Total L. Asignados:'), 0, 0, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(-263, 10, utf8_decode($tlasig), 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(303, 10, utf8_decode('Total L. Cargados:'), 0, 0, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(-261, 10, utf8_decode($tlcarga), 0, 0, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '', 0, 0, 'C');
    }

}

$mipdf = new Solicitudpdf('P', 'mm', 'Letter');

$mipdf->AddPage();
$mipdf->SetFillColor('240', '240', '240');
$mipdf->contenido();
$mipdf->Output('solicitud.pdf', 'D');
