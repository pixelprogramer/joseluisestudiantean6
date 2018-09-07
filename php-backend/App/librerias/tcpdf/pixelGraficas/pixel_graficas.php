<?php

class Graficos extends TCPDF
{
    function generarGraficaDeBarras($tipoColorBarras = null,
                                    $datosBarras,
                                    $datosLineasHorizontales = null,
                                    $ancho = 0,
                                    $alto,
                                    $anchoBarra = 0,
                                    $iteradorEjeY = 10,
                                    $mostrarTextoEjeY = false,
                                    $tituloEjeY,
                                    $mostrarTextoEjeX = false,
                                    $tituloEjeX,
                                    $horientacionTextoEjeY = 'h',
                                    $pintarBordeExternoGrafica = false,
                                    $pintarValoresSuperiorxBarra = false,
                                    $pintarLeyendaGrafica = false,
                                    $pintarValoresSuperiorGrafica = false,
                                    $lineasHorizontales = false,
                                    $lineasHorizontalesDatos = false,
                                    $definirMinimoMaximo = false,
                                    $newDateMax = 0,
                                    $newDateMin = 0)
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

            $minDate = $this->cacularDatoMinimo($datosBarras);
            if ($minDate < $newDateMin) {
                die('Mensaje de pixelProgramer: El dato minimo ingresado: ' . $newDateMin . ' es menor al dato minimo de la barras: ' . $minDate);
            }
            $yAxisUnits = $chartBoxHeicht / ($newDateMax - $newDateMin);
            for ($i = 0; $i <= $newDateMax - $newDateMin; $i += $dataStep) {
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
                if ($barHeght != 0) {
                    //Pintar la barra
                    $arregloColores = $this->colorColumnas($tipoColorBarras);
                    if ($i < count($arregloColores)) {
                        $random = $arregloColores[$i];
                    } else {
                        $random = end($arregloColores);
                    }

                    $this->Image(__DIR__ . './barras_graficas/' . $random . '.jpg', $barX, $barY - 0.2, $barWidth, $barHeght,
                        'jpg', '');
                }
                if ($pintarValoresSuperiorGrafica == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $chartY);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, number_format($datosBarras[$i]['value'], 2), 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }
                if ($pintarValoresSuperiorxBarra == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $barY - 5);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, number_format($datosBarras[$i]['value'], 2), 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }

                $barXPos++;
            }
        } else if ($definirMinimoMaximo == true) {
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
                $constante = $newDateMin * $yAxisUnits;
                $barHeght = $datosBarras[$i]['value'] * $yAxisUnits - $constante;
                //posicion en x de la barra
                $barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
                $barX = $barX - ($barWidth / 2);
                $barX = $barX + $chartBoxX;
                //posicion en y de la barra
                $barY = $chartBoxHeicht - $barHeght;
                $barY = $barY + $chartBoxY;
                if ($barHeght != 0) {
                    //Pintar la barra
                    $arregloColores = $this->colorColumnas($tipoColorBarras);
                    if ($i < count($arregloColores)) {
                        $random = $arregloColores[$i];
                    } else {
                        $random = end($arregloColores);
                    }

                    $this->Image(__DIR__ . './barras_graficas/' . $random . '.jpg', $barX, $barY - 0.2, $barWidth, $barHeght,
                        'jpg', '');
                }
                if ($pintarValoresSuperiorGrafica == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $chartY);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, number_format($datosBarras[$i]['value'], 2), 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }
                if ($pintarValoresSuperiorxBarra == true) {
                    $this->StartTransform();
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->SetXY($barX, $barY - 5);
                    //$this->Rotate(360);
                    $this->Cell($barWidth, 5, number_format($datosBarras[$i]['value'], 2), 0, 0, 'C');
                    $this->Rotate(0);
                    $this->StopTransform();
                    $this->SetXY($x, $y);
                }

                $barXPos++;
            }
        }

        //Pintar lineas horizontales datos
        if ($lineasHorizontalesDatos == true && $datosLineasHorizontales != null) {
            if ($definirMinimoMaximo == false) {
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
                        $this->Cell($chartRightPadding - 4, 5, '- ' . $datosLineasHorizontales[$i]['label'] . ': ' . number_format($datosLineasHorizontales[$i]['value'], 2), 0, 0, 'L');
                    } else {
                        $this->Cell($chartRightPadding - 4, 5, '* ' . $datosLineasHorizontales[$i]['label'] . ': ' . number_format($datosLineasHorizontales[$i]['value'], 2), 0, 0, 'L');
                    }

                    $this->SetXY($x, $y);
                    $barXPos++;
                }
            } else if ($definirMinimoMaximo == true) {
                $minDate = $this->cacularDatoMinimo($datosLineasHorizontales);
                if ($minDate < $newDateMin) {
                    die('Mensaje de pixelProgramer: Lo sentimos el dato con valor: ' . $minDate . ' en las lineas horizontales(datos) es menor al valor minimo de las barras: ' . $newDateMin . '.');
                }
                $barXPos = 0;
                for ($i = 0; $i < count($datosLineasHorizontales); $i++) {
                    //Ancho de la barra
                    $constante = $newDateMin * $yAxisUnits;
                    $barHeght = $datosLineasHorizontales[$i]['value'] * $yAxisUnits - $constante;
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
                        $this->Cell($chartRightPadding - 4, 5, '- ' . $datosLineasHorizontales[$i]['label'] . ': ' . number_format($datosLineasHorizontales[$i]['value'], 2), 0, 0, 'L');
                    } else {
                        $this->Cell($chartRightPadding - 4, 5, '* ' . $datosLineasHorizontales[$i]['label'] . ': ' . number_format($datosLineasHorizontales[$i]['value'], 2), 0, 0, 'L');
                    }

                    $this->SetXY($x, $y);
                    $barXPos++;
                }
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
        //pintarLeyenda
        if ($pintarLeyendaGrafica == true) {
            $this->pintarLeyenda($datosBarras, $tipoColorBarras, $chartBoxX, $chartY + $chartHeight - ($chartBottomPaddig / 2));
        }

    }


    function pintarLeyenda($datos, $tipo, $x, $y)
    {
        $this->StopTransform();
        $this->SetXY($x, $y + 13);
        $this->SetLineWidth(0.01);
        $this->SetDrawColor(000, 000, 000);
        $color = $this->colorColumnas($tipo);
        for ($i = 0; $i < count($datos); $i++) {
            if ($i < count($color)) {
                $random = $color[$i];
            } else {
                $random = end($color);
            }
            $this->SetFont('Times', '', 12);
            $tamañoTexto1 = $this->GetStringWidth($datos[$i]['label'], 'Times', '', 12) + 2;
            $tamañoTexto2 = $this->GetStringWidth($datos[$i]['value'], 'Times', '', 12) + 2;
            if ($tamañoTexto1 > $tamañoTexto2) {
                $tamañoTexto = $tamañoTexto1;
            } else {
                $tamañoTexto = $tamañoTexto2;
            }

            $this->Image(__DIR__ . './barras_graficas/' . $random . '.jpg', $this->GetX(), $this->GetY(), $tamañoTexto, 3,
                'jpg', '');
            $this->SetXY($this->GetX(), $this->GetY() + 4);
            $this->Cell($tamañoTexto, 5, $datos[$i]['label'], 1, 0, 'C');
            $this->SetXY($this->GetX() - $tamañoTexto, $this->GetY() + 5.01000);
            $this->Cell($tamañoTexto, 5, number_format($datos[$i]['value'], 2), 1, 0, 'C');
            $this->SetXY($this->GetX() + 2, $y + 13);
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

    function cacularDatoMinimo($arregloDatos)
    {
        $datosMax = $arregloDatos[0]['value'];
        for ($i = 0; $i < count($arregloDatos); $i++) {
            if ($arregloDatos[$i]['value'] < $datosMax) {
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
                $color = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);//rosado,fuccia,amarillo,verde claro,verde,naranja,azul claro,azul oscuro,rojo,violeta,gris
                return $color;
        }

    }

    function colorSeccionesPastel($type)
    {
        switch ($type) {
            case 0:
                $color = array([76, 166, 17], [255, 204, 0], [245, 11, 10], [22, 78, 227], [255, 204, 0], [184, 13, 239]);//verde,amarillo,rojo,azul,amarillo,violeta
                return $color;
            case 1:
                $color = array([245, 11, 10], [241, 137, 14], [255, 204, 0], [76, 166, 17]);//rojo,naranja,amarillo,verde
                return $color;
            case 2:
                $color = array([150, 150, 150]);//gris
                return $color;
            default:
                $color = array([241, 76, 145], [238, 17, 221], [255, 204, 0], [13, 241, 204],
                    [76, 166, 17], [241, 137, 14], [75, 200, 232], [22, 78, 227], [245, 11, 10],
                    [184, 13, 239], [150, 150, 150]);//rosado,fuccia,amarillo,verde claro,verde,naranja,azul claro,azul oscuro,rojo,violeta,gris
                return $color;
        }
    }

    function generarGraficaPastel($tipoColorSecciones = null, $datosSecciones = null, $tamañoGraficaPastel = 20, $pintarLeyendaGrafica = false, $posicionLeyendaX = 0, $posicionLeyendaY = 0)
    {
        $pieX = $this->GetX();
        $pieY = $this->GetY();
        $radius = $tamañoGraficaPastel;//radio
        if ($posicionLeyendaX == 0) {
            $legendX = $pieX+$tamañoGraficaPastel;
        } else {
            $legendX = $posicionLeyendaX;
        }
        if ($posicionLeyendaY == 0) {
            $legendY = $pieY;
        } else {
            $legendY = $posicionLeyendaY;
        }
        //calcular la suma total de los valores
        $dataSum = 0;
        for ($i = 0; $i < count($datosSecciones); $i++) {
            $dataSum += $datosSecciones[$i]['value'];
        }
        //Obtener la escala por unidad por cada grado
        $degUnit = 360 / $dataSum;
        //Variable para guardar el angulo actual
        $currentAngle = 0;
        //Pintar grafica pastel
        for ($i = 0; $i < count($datosSecciones); $i++) {
            //Calcular la porcion en la torta
            $deg = $degUnit * $datosSecciones[$i]['value'];
            //Definimos el color de la porcion
            $color = $this->colorSeccionesPastel($tipoColorSecciones);
            $this->SetFillColor($color[$i][0], $color[$i][1], $color[$i][2]);
            //pintamos la porcion
            $this->Sector($pieX, $pieY, $radius, $currentAngle, $currentAngle + $deg);
            $currentAngle += $deg;

        }
        //pintar leyenda
        $currentLegendY = $legendY;
        for ($i = 0; $i < count($datosSecciones); $i++) {
            $this->SetFillColor($color[$i][0], $color[$i][1], $color[$i][2]);
            $this->Rect($legendX, $currentLegendY, 5, 5, 'FD');
            $this->SetXY($legendX + 6, $currentLegendY);
            $this->Cell(20, 5, number_format($datosSecciones[$i]['value'], 2).' %', 1, 0);
            $this->SetXY($legendX + 27, $currentLegendY);
            $this->Cell(50, 5, $datosSecciones[$i]['label'], 1, 0);
            $currentLegendY += 5;
        }
    }

    function Sector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90)
    {
        $d0 = $a - $b;
        if ($cw) {
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        } else {
            $b += $o;
            $a += $o;
        }
        while ($a < 0)
            $a += 360;
        while ($a > 360)
            $a -= 360;
        while ($b < 0)
            $b += 360;
        while ($b > 360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b / 360 * 2 * M_PI;
        $a = $a / 360 * 2 * M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2 * M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d / 2))
            $MyArc = 4 / 3 * (1 - cos($d / 2)) / sin($d / 2) * $r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m', ($xc) * $k, ($hp - $yc) * $k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($a)) * $k, (($hp - ($yc - $r * sin($a))) * $k)));
        //draw the arc
        if ($d < M_PI / 2) {
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        } else {
            $b = $a + $d / 4;
            $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        }
        //terminate drawing
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'b';
        else
            $op = 's';
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k));
    }
}