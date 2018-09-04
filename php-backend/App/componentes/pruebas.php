<?php

$app->post('/pruebaCharts', function () use ($app) {

    $route = __DIR__ . '../../public/pdf/';

    class reporteCompleto extends Graficos
    {
        function nuevo()
        {
            $this->AddPage('L', 'mm', 'Letter', true, 'UTF-8', false, false);
            $this->setPrintHeader(false);
            $this->setPrintFooter(false);
            $this->SetXY(30, 20);
            //Datos
            $datos = array([
                'label' => 'Matematicas',
                'value' => 4.97
            ], [
                'label' => 'Lectura critica',
                'value' => 4.93
            ], [
                'label' => 'Ciencias Sociales',
                'value' => 4.75
            ], [
                'label' => 'Idioma Extranjero',
                'value' => 4.71
            ], [
                'label' => 'Etica',
                'value' => 4.47
            ], );

                $this->generarGraficaDeBarras($datos, 250, 120, 15, true, 'Habitantes',
                    true, 'Ciudades', 'v',
                    false, false,false);

        }
    }

    $pdf = new reporteCompleto();
    $pdf->nuevo();
    //die();
    if (!file_exists($route))
        mkdir($route, 0, true);
    $pdf->Output($route . "reporteGraficas.pdf", "F");


});

class Graficos extends TCPDF
{

    function generarGraficaDeBarras($datos, $ancho = 0, $alto,  $anchoBarra = 0, $mostrarTextoEjeY=false,$tituloEjeY,$mostrarTextoEjeX=false, $tituloEjeX,$horientacionTextoEjeY='h',
                                    $pintarBordeExternoGrafica = false, $pintarValoresSuperiorxBarra = false,$lineasHorizontales=false)
    {

        //Posicion
        $chartY = $this->GetY();
        $chartX = $this->GetX();
        //Ancho de barra
        if ($anchoBarra == 0) {
            $barWidth = 5;
        } else {
            $barWidth = $anchoBarra;
        }

        //Tamaño tabla
        if ($ancho == 0) {
            $chartWidht = ($barWidth * 2) * count($datos);
        } else {
            $chartWidht = $ancho;
        }

        $chartHeight = $alto;
        //padding
        $chartTopPadding = 10;
        $chartLeftPadding = 20;
        $chartBottomPaddig = 10;
        $chartRightPadding = 5;
        //Contenedor Grafica
        $chartBoxX = $chartX + $chartLeftPadding;
        $chartBoxY = $chartY + $chartTopPadding;
        $chartBoxWidth = $chartWidht - $chartLeftPadding - $chartRightPadding;
        $chartBoxHeicht = $chartHeight - $chartTopPadding - $chartBottomPaddig;

        //Color
        $color = array([255, 0, 0], [255, 255, 0], [50, 0, 255], [255, 0, 255], [0, 255, 0], [50, 0, 255], [255, 0, 0], [255, 255, 0], [255, 0, 255], [0, 255, 0], [50, 0, 255]);

        //Calculamos cual es el dato maximo
        $datosMaximo = $this->cacularDatoMaximo($datos);
        $datosMaximo = round($datosMaximo);
        //data step
        $dataStep = 1;
        //Fuente,color,ancho de linea
        $this->SetFont('Times', '', '9');
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0);
        if ($pintarBordeExternoGrafica == true) {
            $this->Rect($chartX, $chartY, $chartWidht, $chartHeight);
        }

        //Linea vertical
        $this->Line($chartBoxX, $chartBoxY, $chartBoxX, $chartBoxY + $chartBoxHeicht);
        //Linea horizontal
        $this->Line($chartBoxX - 2, $chartBoxY + $chartBoxHeicht, $chartBoxX + $chartBoxWidth, $chartBoxY + $chartBoxHeicht);

        //Calcular las unidades de la linea del eje y
        $yAxisUnits = $chartBoxHeicht / $datosMaximo;

        for ($i = 0; $i <= $datosMaximo; $i += $dataStep) {
            //y position
            $yAxisPos = $chartBoxY + ($yAxisUnits * $i);
            //pintar  linea del eje y
            if ($lineasHorizontales == true){
                $this->Line($chartBoxX +$chartBoxWidth, $yAxisPos, $chartBoxX, $yAxisPos);
            }else
            {
                $this->Line($chartBoxX - 2, $yAxisPos, $chartBoxX, $yAxisPos);
            }
            //Establecemos la posicion de la celdas para los label del eje y
            $this->SetXY($chartBoxX - $chartLeftPadding, $yAxisPos - 2);
            //Pintamos los labels del eje y
            $this->Cell($chartLeftPadding - 4, 5, $datosMaximo - $i, 0, 0, 'R');
        }
        //Eje horizontal
        $this->SetXY($chartBoxX, $chartBoxY + $chartBoxHeicht);
        //Ancho de la celdas que contendran los labels
        $xLabelWidth = $chartBoxWidth / count($datos);
        //Lazo eje horizontal y pintar las batrras;
        $barXPos = 0;
        for ($i = 0; $i < count($datos); $i++) {
            //Pintamos los label de las barras
            $this->Cell($xLabelWidth, 5, $datos[$i]['label'], 0, 0, 'C');
            //Pintamos las barras
            $this->SetFillColor(255, 255, 255);
            //Ancho de la barra
            $barHeght = $yAxisUnits * $datos[$i]['value'];
            //posicion en x de la barra
            $barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
            $barX = $barX - ($barWidth / 2);
            $barX = $barX + $chartBoxX;
            //posicion en y de la barra
            $barY = $chartBoxHeicht - $barHeght;
            $barY = $barY + $chartBoxY;
            //Pintar la barra

           // $this->Rect($barX, $barY, $barWidth, $barHeght, 'DF');

            $random = rand(1,7);
            $this->Image(__DIR__ . '../../public/imagenes_estandar/barras_graficas/'.$random.'.jpg', $barX, $barY-0.2, $barWidth, $barHeght,
                'jpg', '');
            if ($pintarValoresSuperiorxBarra == true) {
                $this->StartTransform();
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetXY($barX, $barY - 5);
                //$this->Rotate(360);
                $this->Cell($barWidth, 5, $datos[$i]['value'], 0, 0, 'C');
                $this->Rotate(0);
                $this->StopTransform();
                $this->SetXY($x, $y);
            }

            $barXPos++;
        }
       // die();
        //Labels de los ejes
        $this->SetFont('Times', 'B', 12);
        if ($mostrarTextoEjeY==true){
            if (strtolower($horientacionTextoEjeY)=='v'){
                $this->SetXY($chartX, $yAxisPos - 2);
                $this->StartTransform();
                $this->Rotate(90);
                $this->Cell($chartBoxHeicht, 10, $tituloEjeY, 0, 0,'C');
                $this->StopTransform();
            }else
            {
                $this->SetXY($chartX, $chartY);
                $this->Cell($chartBoxWidth, 10, $tituloEjeY, 0, 0);
            }
        }
        if ($mostrarTextoEjeX == true){

            $this->SetXY(($chartWidht / 2) - 100 + $chartY, $chartY + $chartHeight - ($chartBottomPaddig / 2));
            $this->Cell($chartBoxWidth, 10, $tituloEjeX, 0, 0, 'C');
        }


    }

    function cacularDatoMaximo($arregloDatos)
    {
        $datosMax = 1;
        for ($i = 0; $i < count($arregloDatos); $i++) {
            if ($arregloDatos[$i]['value'] > $datosMax) {
                $datosMax = $arregloDatos[$i]['value'];
            }

        }
        return $datosMax;
    }
}