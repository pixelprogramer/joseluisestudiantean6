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
            //Datos barras
            $datos = array([
                'label' => 'Matematicas',
                'value' => 3.2
            ], [
                'label' => 'Lectura critica',
                'value' => 4.93
            ], [
                'label' => 'Ciencias Sociales',
                'value' => 4.75
            ], [
                'label' => 'Ciencias naturales',
                'value' => 4.71
            ], [
                'label' => 'Idioma extranjero',
                'value' => 4.73
            ], [
                'label' => 'Etica',
                'value' => 4.47
            ]);
            //Datos linea horizontal
            $datosLineasHorizontales = array([
                'label' => 'Promedio curso',
                'value' => 4.76,
                'color' => [
                    'r' => 0,
                    'g' => 0,
                    'b' => 0
                ]
            ], [
                'label' => 'Promedio grado',
                'value' => 3.37,
                'color' => [
                    'r' => 220,
                    'g' => 20,
                    'b' => 60
                ]
            ]);

            $this->generarGraficaDeBarras(2, $datos, $datosLineasHorizontales, 220, 90, 15, 1, true, 'Calificacion',
                true, 'Asignatura', 'v',
                false, true, true, true, false, 5, 3);
            /*
                        $datos = array([
                            'label'=>'Bajo',
                            'value'=>'0.01'
                        ],[
                            'label'=>'Basico',
                            'value'=>'3.57'
                        ],[
                            'label'=>'Alto',
                            'value'=>'25.00'
                        ],[
                            'label'=>'Superior',
                            'value'=>'71.43'
                        ]);
                        $this->SetXY(200,$this->GetY()+10);
                        $this->generarGraficaDeBarras(1,$datos, null, 90, 60,
                            15,10, true, 'Poxentaje Estudiante por desempeño',
                            true, 'Desempeños', 'v',
                            false, true, true, false,100);
            */
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

    function generarGraficaDeBarras($tipoColorBarras = null, $datosBarras, $datosLineasHorizontales = null, $ancho = 0, $alto, $anchoBarra = 0, $iteradorEjeY = 10, $mostrarTextoEjeY = false, $tituloEjeY, $mostrarTextoEjeX = false, $tituloEjeX, $horientacionTextoEjeY = 'h',
                                    $pintarBordeExternoGrafica = false, $pintarValoresSuperiorxBarra = false, $lineasHorizontales = false, $lineasHorizontalesDatos = false, $definirMinimoMaximo = false, $newDateMax = 0, $newDateMin = 0)
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
            $chartWidht = ($barWidth * 2) * count($datosBarras);
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
        $datosMinimo = $newDateMin;
        $datosMaximo = $this->cacularDatoMaximo($datosBarras);
        if ($newDateMax == 0) {
            $datosMaximo = round($datosMaximo);
        } else {
            if ($datosMaximo > $newDateMax) {
                die('Mensaje de pixelProgramer: El dato maximo ingresado: ' . $newDateMax . ' es menor al dato maximo de la barras: ' . $datosMaximo);
            } else {
                $datosMaximo = $newDateMax;
            }
        }

        //data step
        $dataStep = $iteradorEjeY;
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

        if ($definirMinimoMaximo == false) {
            $yAxisUnits = $chartBoxHeicht / $datosMaximo;
            for ($i = 0; $i <= $datosMaximo; $i += $dataStep) {
                //y position
                $yAxisPos = $chartBoxY + ($yAxisUnits * $i);
                //pintar  linea del eje y
                if ($lineasHorizontales == true) {
                    if ($i != $datosMaximo) {
                        $this->SetDrawColor(236, 236, 236);
                    } else {
                        $this->SetDrawColor(0, 0, 0);
                    }

                    $this->Line($chartBoxX + $chartBoxWidth, $yAxisPos, $chartBoxX, $yAxisPos);
                }
                $this->SetDrawColor(0, 0, 0);
                $this->Line($chartBoxX - 2, $yAxisPos, $chartBoxX, $yAxisPos);
                //Establecemos la posicion de la celdas para los label del eje y
                $this->SetXY($chartBoxX - $chartLeftPadding, $yAxisPos - 2);
                //Pintamos los labels del eje y
                $this->Cell($chartLeftPadding - 4, 5, $datosMaximo - $i, 0, 0, 'R');
            }
        }


        if ($definirMinimoMaximo == true) {
            $yAxisUnitsMin = $chartBoxHeicht / $datosMinimo;
            $yAxisUnits = $chartBoxHeicht / $datosMaximo;
            for ($i = 0; $i <= $datosMaximo - $datosMinimo; $i += $dataStep) {
                //y position
                $yAxisPos = $chartBoxY + ($yAxisUnitsMin * $i);
                //pintar  linea del eje y
                if ($lineasHorizontales == true) {
                    if ($i != $datosMaximo) {
                        $this->SetDrawColor(236, 236, 236);
                    } else {
                        $this->SetDrawColor(0, 0, 0);
                    }

                    $this->Line($chartBoxX + $chartBoxWidth, $yAxisPos, $chartBoxX, $yAxisPos);
                }
                $this->SetDrawColor(0, 0, 0);
                $this->Line($chartBoxX - 2, $yAxisPos, $chartBoxX, $yAxisPos);
                //Establecemos la posicion de la celdas para los label del eje y
                $this->SetXY($chartBoxX - $chartLeftPadding, $yAxisPos - 2);
                //Pintamos los labels del eje y
                if ($datosMinimo > $datosMaximo) {
                    $this->Cell($chartLeftPadding - 4, 5, $datosMaximo, 0, 0, 'R');
                } else {
                    $this->Cell($chartLeftPadding - 4, 5, $datosMaximo - $i, 0, 0, 'R');
                }

            }
        }

        if ($definirMinimoMaximo == false) {
            //Eje horizontal
            $this->SetXY($chartBoxX, $chartBoxY + $chartBoxHeicht);
            //Ancho de la celdas que contendran los labels
            $xLabelWidth = $chartBoxWidth / count($datosBarras);
            //Lazo eje horizontal y pintar las batrras;
            $barXPos = 0;
            for ($i = 0; $i < count($datosBarras); $i++) {
                //Pintamos los label de las barras
                $this->Cell($xLabelWidth, 5, $datosBarras[$i]['label'], 0, 0, 'C');
                //Pintamos las barras
                $this->SetFillColor(255, 255, 255);
                //Ancho de la barra
                $barHeght = $yAxisUnits * $datosBarras[$i]['value'];
                //posicion en x de la barra
                $barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
                $barX = $barX - ($barWidth / 2);
                $barX = $barX + $chartBoxX;
                //posicion en y de la barra
                $barY = $chartBoxHeicht - $barHeght;
                $barY = $barY + $chartBoxY;
                //Pintar la barra
                $arregloColores = $this->colorColumnas($tipoColorBarras);
                if ($i < count($arregloColores)) {
                    $random = $arregloColores[$i];
                } else {
                    $random = end($arregloColores);
                }

                $this->Image(__DIR__ . '../../public/imagenes_estandar/barras_graficas/' . $random . '.jpg', $barX, $barY - 0.2, $barWidth, $barHeght,
                    'jpg', '');
                if ($pintarValoresSuperiorxBarra == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $barY - 5);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, $datosBarras[$i]['value'], 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }

                $barXPos++;
            }
        }else
        {
            //Eje horizontal
            $this->SetXY($chartBoxX, $chartBoxY + $chartBoxHeicht);
            //Ancho de la celdas que contendran los labels
            $xLabelWidth = $chartBoxWidth / count($datosBarras);
            //Lazo eje horizontal y pintar las batrras;
            $barXPos = 0;
            for ($i = 0; $i < count($datosBarras); $i++) {
                //Pintamos los label de las barras
                $this->Cell($xLabelWidth, 5, $datosBarras[$i]['label'], 0, 0, 'C');
                //Pintamos las barras
                $this->SetFillColor(255, 255, 255);
                //Ancho de la barra
                $barHeght = $yAxisUnits * $datosBarras[$i]['value'];
                //posicion en x de la barra
                $barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
                $barX = $barX - ($barWidth / 2);
                $barX = $barX + $chartBoxX;
                //posicion en y de la barra
                $barY = $chartBoxHeicht - $barHeght;
                $barY = $barY + $chartBoxY;
                //Pintar la barra
                $arregloColores = $this->colorColumnas($tipoColorBarras);
                if ($i < count($arregloColores)) {
                    $random = $arregloColores[$i];
                } else {
                    $random = end($arregloColores);
                }

                $this->Image(__DIR__ . '../../public/imagenes_estandar/barras_graficas/' . $random . '.jpg', $barX, $barY - 0.2, $barWidth, $barHeght,
                    'jpg', '');
                if ($pintarValoresSuperiorxBarra == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $barY - 5);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, $datosBarras[$i]['value'], 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }

                $barXPos++;
            }
        }

        //Pintar lineas horizontales datos
        if ($lineasHorizontalesDatos == true && $datosLineasHorizontales != null) {
            $maxDate = $this->cacularDatoMaximo($datosLineasHorizontales);
            if ($maxDate > $datosMaximo) {
                die('Mensaje de pixelProgramer: Lo sentimos el dato con valor: ' . $maxDate . ' en las lineas horizontales(datos) es mayor al valor maximo de las barras.');
            }
            $barXPos = 0;
            for ($i = 0; $i < count($datosLineasHorizontales); $i++) {
                //Ancho de la barra

                $barHeght = $yAxisUnits * $datosLineasHorizontales[$i]['value'];

                //posicion en x de la barra
                $barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
                $barX = $barX - ($barWidth / 2);
                $barX = $barX + $chartBoxX;
                //posicion en y de la barra
                $barY = $chartBoxHeicht - $barHeght;
                $barY = $barY + $chartBoxY;
                //Pintar la linea horizontal
                $x = $this->GetX();
                $y = $this->GetY();
                $contador = 1;
                $indentar = 0;
                for ($n = 0; $n < $i; $n++) {
                    if ($datosLineasHorizontales[$i]['value'] == $datosLineasHorizontales[$n]['value']) {
                        $contador -= 2.5;
                        $indentar = 2;
                    }
                };
                $this->SetXY($barX, $barY + ($contador * -1.5));
                $this->SetDrawColor($datosLineasHorizontales[$i]['color']['r'], $datosLineasHorizontales[$i]['color']['g'], $datosLineasHorizontales[$i]['color']['b']);
                $this->Line($chartBoxX + $chartBoxWidth, $barY, $chartBoxX, $barY);
                $this->SetX($chartBoxX + $chartBoxWidth + 1 + $indentar);
                if ($indentar != 0) {
                    $this->Cell($chartRightPadding - 4, 5, '- ' . $datosLineasHorizontales[$i]['label'] . ': ' . $datosLineasHorizontales[$i]['value'], 0, 0, 'L');
                } else {
                    $this->Cell($chartRightPadding - 4, 5, '* ' . $datosLineasHorizontales[$i]['label'] . ': ' . $datosLineasHorizontales[$i]['value'], 0, 0, 'L');
                }

                $this->SetXY($x, $y);
                $barXPos++;
            }
        }

        //Labels de los ejes
        $this->SetFont('Times', 'B', 12);
        if ($mostrarTextoEjeY == true) {
            if (strtolower($horientacionTextoEjeY) == 'v') {
                $this->SetXY($chartX, $yAxisPos - 2);
                $this->StartTransform();
                $this->Rotate(90);
                $this->Cell($chartBoxHeicht, 10, $tituloEjeY, 0, 0, 'C');
                $this->StopTransform();
            } else {
                $this->SetXY($chartX, $chartY);
                $this->Cell($chartBoxWidth, 10, $tituloEjeY, 0, 0);
            }
        }
        if ($mostrarTextoEjeX == true) {

            $this->SetXY($chartBoxX, $chartY + $chartHeight - ($chartBottomPaddig / 2));
            $this->Cell($chartBoxWidth, 10, $tituloEjeX, 0, 0, 'C');
        }

        // var_dump($datosLineasHorizontales);
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

    function colorColumnas($type)
    {
        switch ($type) {
            case 0:
                $color = array(5, 3, 9, 8, 3, 10);//verde,amarillo,rojo,azul,amarillo,violeta
                return $color;
            case 1:
                $color = array(9, 6, 3, 5);//rojo,naranja,amarillo,verde
                return $color;
            case 2:
                $color = array(11);//gris
                return $color;
            default:
                $color = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);//rosado,fuccia,amarillo,verde claro,verde,naranja,azul claro,azul oscuro,rojo,violeta,gris
                return $color;
        }

    }
}