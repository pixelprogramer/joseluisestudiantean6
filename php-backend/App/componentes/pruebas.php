<?php

$app->post('/pruebaCharts', function () use ($app) {

    $route = __DIR__ . '../../public/pdf/';

    class reporteCompleto extends pixel_TCPDF
    {
        function nuevo()
        {
            $this->nuevaPagina('L', 'Letter','mm', 'Letter', true, 'UTF-8', false, false);

            $this->SetXY(10, 40);
            //Datos barras
            $datos1 = array([
                'label' => 'Matematicas',
                'value' => 3
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

            $this->generarGraficaDeBarras(0, $datos1, $datosLineasHorizontales, 220, 80, 15, 1, true, 'Calificacion',
                true, 'Asignatura', 'v',
                false, false, true, true, true, true);

            $datos = array([
                'label' => 'Bajo',
                'value' => '0.00'
            ], [
                'label' => 'Basico',
                'value' => '3.57'
            ], [
                'label' => 'Alto',
                'value' => '25.00'
            ], [
                'label' => 'Superior',
                'value' => '71.43'
            ]);

            $this->SetXY(200, $this->GetY()+5);
            $this->generarGraficaDeBarras(1, $datos, null, 90, 48, 15, 10, true, 'Porcentaje',
                true, 'Desempeño', 'v',
                false, true, false,
                true, true, true, false, 100);
            $this->SetXY(40,$this->GetY()-10);
            $this->generarGraficaPastel(1,$datos,15,true,$this->GetX()+20,$this->GetY()-15);
            $this->SetXY(150,150);
            // Rounded rectangle

        }
    }

    $pdf = new reporteCompleto();

    $pdf->nuevo();

    //die();
    if (!file_exists($route))
        mkdir($route, 0, true);
    $pdf->Output($route . "reporteGraficas.pdf", "F");


});
