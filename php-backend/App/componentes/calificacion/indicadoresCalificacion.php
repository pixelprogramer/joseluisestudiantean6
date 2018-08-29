<?php
$app->post('/calificacion/generarIndicadores', function () use ($app) { //Metodo para generar el reporte de indicadores de calificacion y premarcado
    $helper = new helper();
    $conexion = new conexMsql();
    $fecha_Inicial = $app->request->post('fecha_inicial', null);
    $fecha_Final = $app->request->post('fecha_final', null);
    $sF = explode('-',$fecha_Final);
    $sI = explode('-',$fecha_Inicial);

    if ($fecha_Final == '' || $fecha_Final == null) {
        $fecha_Final = $sI[0].'-'.$sI[1];;
    }else
    {
        $fecha_Final = $sF[0].'-'.$sF[1];
    }
    $fecha_Inicial = $sI[0].'-'.$sI[1];

    $sql = "select upr.*,userr.name,userr.surname,userr.id, sum(od.quantity_calificado) as total_calificado, sum(od.quantity_premarcado) as total_premarcado, sum(od.quantity_order) as total_escaneo, 
        DATE_FORMAT(upr.created_at,'%Y-%m') as fecha_formateada from users_per_order upr 
        inner join (orders orde inner join (order_details od INNER JOIN products pro ON od.id_product = pro.id)ON orde.id = od.id_order)on upr.id_order=orde.id 
        inner join users userr on upr.id_user=userr.id
        where pro.sesion = 1 and upr.id_process_state IN (5,9,14) and DATE_FORMAT(upr.created_at,'%Y-%m') between '$fecha_Inicial' and '$fecha_Final'
        GROUP BY upr.id order by upr.id_user asc";

    $r = $conexion->consultaComplejaAso($sql);
    if ($r != 0)
    {
        $arregloCompleto = array();
        $totalCalificadoGlobal = 0;
        $totalPremarcadoGlobal = 0;
        for ($i = 0; $i < count($r); $i++) {//Organizar arreglo para pintar el
            if ($r[$i]['id_process_state'] == '9') {//CALIFICACION
                $validacionEntradaMes = 0;
                for ($j = 0; $j < count($arregloCompleto); $j++) {
                    if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                        $validacionEntradaMes = 1;
                        $validacionEntradaUsuario = 0;
                        for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                            if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                                $arregloCompleto[$j]['data'][$n]['totalCalificado'] += $r[$i]['total_calificado'];

                                $validacionEntradaUsuario = 1;
                            }
                        }
                        $arregloCompleto[$j]['totalColegios'] += 1;
                        if ($validacionEntradaUsuario == 0) {
                            array_push($arregloCompleto[$j]['data'], [
                                'id' => $r[$i]['id'],
                                'nombre' => $r[$i]['name'],
                                'apellido' => $r[$i]['surname'],
                                'participacionPremarcado' => 0,
                                'participacionEscaneo' => 0,
                                'productividadCalificacion' => 0,
                                'productividadPremarcado' => 0,
                                'totalCalificado' => $r[$i]['total_calificado'],
                                'totalEscaneado' => 0,
                                'totalPremarcado' => 0
                            ]);
                        }

                    }
                }
                if ($validacionEntradaMes == 0) {
                    array_push($arregloCompleto, [
                        'fecha' => $r[$i]['fecha_formateada'],
                        'totalCalificado' => 0,
                        'totalPremarcado' => 0,
                        'totalEscaneado' => 0,
                        'totalColegios' => 1,
                        'capacidadInstaladaCalificacion' => 10000,
                        'capacidadInstaladaPremarcado' => 10000,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'diferenciaCalificacion' => 0,
                        'diferenciaPremarcado' => 0,
                        'diferenciaNegativaCalificacion' => 0,
                        'diferenciaNegativaPremarcado' => 0,
                        'costoPruebaCalificacion' => 2500,
                        'costoPruebaPremarcado' => 2500,
                        'valorTotalCalificaion' => 0,
                        'valorTotalPremarcado' => 0,
                        'data' => [[
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => $r[$i]['total_calificado'],
                            'totalEscaneado' => 0,
                            'totalPremarcado' => 0]
                        ]
                    ]);
                }
            } else if ($r[$i]['id_process_state'] == '5')//PREMARCADO
            {
                $validacionEntradaMes = 0;
                for ($j = 0; $j < count($arregloCompleto); $j++) {
                    if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                        $validacionEntradaMes = 1;
                        $validacionEntradaUsuario = 0;
                        for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                            if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                                $arregloCompleto[$j]['data'][$n]['totalPremarcado'] += $r[$i]['total_premarcado'];

                                $validacionEntradaUsuario = 1;
                            }
                        }
                        $arregloCompleto[$j]['totalColegios'] += 1;
                        if ($validacionEntradaUsuario == 0) {
                            array_push($arregloCompleto[$j]['data'], [
                                'id' => $r[$i]['id'],
                                'nombre' => $r[$i]['name'],
                                'apellido' => $r[$i]['surname'],
                                'participacionPremarcado' => 0,
                                'participacionEscaneo' => 0,
                                'productividadCalificacion' => 0,
                                'productividadPremarcado' => 0,
                                'totalCalificado' => 0,
                                'totalEscaneado' => 0,
                                'totalPremarcado' => $r[$i]['total_premarcado']
                            ]);
                        }

                    }
                }
                if ($validacionEntradaMes == 0) {
                    array_push($arregloCompleto, [
                        'fecha' => $r[$i]['fecha_formateada'],
                        'totalCalificado' => 0,
                        'totalPremarcado' => 0,
                        'totalEscaneado' => 0,
                        'totalColegios' => 1,
                        'capacidadInstaladaCalificacion' => 10000,
                        'capacidadInstaladaPremarcado' => 10000,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'diferenciaCalificacion' => 0,
                        'diferenciaPremarcado' => 0,
                        'diferenciaNegativaCalificacion' => 0,
                        'diferenciaNegativaPremarcado' => 0,
                        'costoPruebaCalificacion' => 2500,
                        'costoPruebaPremarcado' => 2500,
                        'valorTotalCalificaion' => 0,
                        'valorTotalPremarcado' => 0,
                        'data' => [[
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => 0,
                            'totalEscaneado' => 0,
                            'totalPremarcado' => $r[$i]['total_premarcado']]
                        ]
                    ]);
                }
            } else//Escaneo
            {
                $validacionEntradaMes = 0;
                for ($j = 0; $j < count($arregloCompleto); $j++) {
                    if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                        $validacionEntradaMes = 1;
                        $validacionEntradaUsuario = 0;
                        for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                            if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                                $arregloCompleto[$j]['data'][$n]['totalEscaneado'] += $r[$i]['total_escaneo'];

                                $validacionEntradaUsuario = 1;
                            }
                        }
                        $arregloCompleto[$j]['totalColegios'] += 1;
                        if ($validacionEntradaUsuario == 0) {
                            array_push($arregloCompleto[$j]['data'], [
                                'id' => $r[$i]['id'],
                                'nombre' => $r[$i]['name'],
                                'apellido' => $r[$i]['surname'],
                                'participacionPremarcado' => 0,
                                'participacionEscaneo' => 0,
                                'productividadCalificacion' => 0,
                                'productividadPremarcado' => 0,
                                'totalCalificado' => 0,
                                'totalEscaneado' => $r[$i]['total_escaneo'],
                                'totalPremarcado' => 0
                            ]);
                        }

                    }
                }
                if ($validacionEntradaMes == 0) {
                    array_push($arregloCompleto, [
                        'fecha' => $r[$i]['fecha_formateada'],
                        'totalCalificado' => 0,
                        'totalPremarcado' => 0,
                        'totalEscaneado' => 0,
                        'totalColegios' => 1,
                        'capacidadInstaladaCalificacion' => 10000,
                        'capacidadInstaladaPremarcado' => 10000,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'diferenciaCalificacion' => 0,
                        'diferenciaPremarcado' => 0,
                        'diferenciaNegativaCalificacion' => 0,
                        'diferenciaNegativaPremarcado' => 0,
                        'costoPruebaCalificacion' => 2500,
                        'costoPruebaPremarcado' => 2500,
                        'valorTotalCalificaion' => 0,
                        'valorTotalPremarcado' => 0,
                        'data' => [[
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => 0,
                            'totalEscaneado' => $r[$i]['total_escaneo'],
                            'totalPremarcado' => 0]
                        ]
                    ]);
                }
            }
        }
        for ($i = 0; $i < count($arregloCompleto); $i++) {//Incializar la capcidad instalada calcular los demas valores necesarios
            $arregloFecha = explode('-', $arregloCompleto[$i]['fecha']);
            $sql = "select * from capacidad_instalada ci where ci.anodesc like '$arregloFecha[0]' and ci.mesdesc like '$arregloFecha[1]' 
         and (ci.departamento like 'CALIFICACION' or ci.departamento like 'PREMARCADO') and ci.state like 'ACTIVO'";
            $r = $conexion->consultaComplejaAso($sql);
            $validacionEntradaCalificacion = 0;
            $posicionCalificacion = null;
            $validacionEntradaPremarcado = 0;
            $posicionPremarcado = null;
            for ($j = 0; $j < count($r); $j++) {
                if ($r[$j]['departamento'] == 'PREMARCADO') {
                    $validacionEntradaPremarcado = 1;
                    $posicionPremarcado = $j;

                } else if ($r[$j]['departamento'] == 'CALIFICACION') {
                    $validacionEntradaCalificacion = 1;
                    $posicionCalificacion = $j;
                }
            }
            if ($validacionEntradaCalificacion == 1 && $posicionCalificacion != null) {
                $arregloCompleto[$i]['capacidadInstaladaCalificacion'] = $r[$posicionCalificacion]['cantidad'];
            } else {
                $arregloCompleto[$i]['capacidadInstaladaCalificacion'] = 10000;
            }
            if ($validacionEntradaPremarcado == 1) {
                $arregloCompleto[$i]['capacidadInstaladaPremarcado'] = $r[$posicionPremarcado]['cantidad'];
            } else {
                $arregloCompleto[$i]['capacidadInstaladaPremarcado'] = 10000;
            }

            $totalPremarcado = 0;
            $totalCalificacion = 0;
            $totalEscaneado = 0;
            for ($n = 0; $n < count($arregloCompleto[$i]['data']); $n++) {
                $totalPremarcado += $arregloCompleto[$i]['data'][$n]['totalPremarcado'];
                $totalCalificacion += $arregloCompleto[$i]['data'][$n]['totalCalificado'];
                $totalEscaneado += $arregloCompleto[$i]['data'][$n]['totalEscaneado'];
            }
            $arregloCompleto[$i]['totalCalificado'] = $totalCalificacion;
            $arregloCompleto[$i]['totalPremarcado'] = $totalPremarcado;
            $arregloCompleto[$i]['totalEscaneado'] = $totalEscaneado;
            //Cacular productividad
            $arregloCompleto[$i]['productividadCalificacion'] = ($arregloCompleto[$i]['totalCalificado'] * 100) / ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
            $arregloCompleto[$i]['productividadPremarcado'] = ($arregloCompleto[$i]['totalPremarcado'] * 100) / ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
            //Calcular Diferencia
            $arregloCompleto[$i]['diferenciaCalificacion'] = $arregloCompleto[$i]['productividadCalificacion'] - 100;
            $arregloCompleto[$i]['diferenciaPremarcado'] = $arregloCompleto[$i]['productividadPremarcado'] - 100;
            //Calcular diferencias negativa
            $arregloCompleto[$i]['diferenciaNegativaCalificacion'] = $arregloCompleto[$i]['totalCalificado'] - ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
            $arregloCompleto[$i]['diferenciaNegativaPremarcado'] = $arregloCompleto[$i]['totalPremarcado'] - ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
            // valor total
            $arregloCompleto[$i]['valorTotalCalificaion'] = $arregloCompleto[$i]['diferenciaNegativaCalificacion'] * $arregloCompleto[$i]['costoPruebaCalificacion'];
            $arregloCompleto[$i]['valorTotalPremarcado'] = $arregloCompleto[$i]['diferenciaNegativaPremarcado'] * $arregloCompleto[$i]['costoPruebaPremarcado'];


        }
        $totalCapacidadCalificacion = 0;
        $totalCapacidadPremarcado = 0;
        $totalCalificado = 0;
        $totalPremarcado = 0;
        $totalProductividadCalificacion = 0;
        $totalProductividadPremarcado = 0;
        $totalDiferenciaPremarcado = 0;
        $totalDiferenciaCalificacion = 0;
        $valorTotalCalificacion = 0;
        $valorTotalPremarcado = 0;
        for ($i = 0; $i < count($arregloCompleto); $i++) {
            for ($j = 0; $j < count($arregloCompleto[$i]['data']); $j++) {
                if ($arregloCompleto[$i]['totalPremarcado'] != 0) {
                    $arregloCompleto[$i]['data'][$j]['participacionPremarcado'] = ($arregloCompleto[$i]['data'][$j]['totalPremarcado'] * 100) / $arregloCompleto[$i]['totalPremarcado'];
                }

                if ($arregloCompleto[$i]['totalEscaneado'] != 0) {
                    $arregloCompleto[$i]['data'][$j]['participacionEscaneo'] = ($arregloCompleto[$i]['data'][$j]['totalEscaneado'] * 100) / $arregloCompleto[$i]['totalEscaneado'];
                }
                if ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] != 0) {
                    $arregloCompleto[$i]['data'][$j]['productividadCalificacion'] = ($arregloCompleto[$i]['data'][$j]['totalCalificado'] * 100) / $arregloCompleto[$i]['capacidadInstaladaCalificacion'];
                }
                if ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] != 0) {
                    $arregloCompleto[$i]['data'][$j]['productividadPremarcado'] = ($arregloCompleto[$i]['data'][$j]['totalPremarcado'] * 100) / $arregloCompleto[$i]['capacidadInstaladaPremarcado'];
                }

            }
            $totalCapacidadCalificacion += $arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10;
            $totalCapacidadPremarcado += $arregloCompleto[$i]['capacidadInstaladaPremarcado'];
            $totalCalificado += $arregloCompleto[$i]['totalCalificado'];
            $totalPremarcado += $arregloCompleto[$i]['totalPremarcado'];
            $totalProductividadCalificacion += $arregloCompleto[$i]['productividadCalificacion'];
            $totalProductividadPremarcado += $arregloCompleto[$i]['productividadPremarcado'];
            $totalDiferenciaPremarcado += $arregloCompleto[$i]['diferenciaPremarcado'];
            $totalDiferenciaCalificacion += $arregloCompleto[$i]['diferenciaCalificacion'];
            $valorTotalCalificacion += $arregloCompleto[$i]['valorTotalCalificaion'];
            $valorTotalPremarcado += $arregloCompleto[$i]['valorTotalPremarcado'];
        }

        $resultado=generarReporteExcel($arregloCompleto, $totalCapacidadCalificacion, $totalCapacidadPremarcado, $totalCalificado, $totalPremarcado, $totalProductividadCalificacion, $totalProductividadPremarcado,
            $totalDiferenciaPremarcado, $totalDiferenciaCalificacion, $valorTotalCalificacion, $valorTotalPremarcado, $fecha_Inicial, $fecha_Final);

        $data = [
            'code' => 'LTE-001',
            'data' => $resultado
        ];
    }else
    {
        $data = [
            'code'=>'LTE-000',
            'status'=>'error',
            'msg'=>'Lo sentimos, No se encontraron resultados'
        ];
    }

    echo $helper->checkCode($data);
});

function generarReporteExcel($arregloCompleto, $totalCapacidadCalificacionF, $totalCapacidadPremarcadoF, $totalCalificadoF, $totalPremarcadoF, $totalProductividadCalificacionF, $totalProductividadPremarcadoF,
                             $totalDiferenciaPremarcadoF, $totalDiferenciaCalificacionF, $valorTotalCalificacionF, $valorTotalPremarcadoF, $fechaInicial, $fechaFinal)
{//Funcion para generar el reporte en excel el cual retornara la ruta del mismo
    $libro = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    //Reporte de indicadores I1
    $libro->getActiveSheet()->mergeCells('A1:E1');
    $libro->getActiveSheet()->getStyle('A1:E2')->getFont()->setBold(true);
    $hoja = $libro->getActiveSheet();
    $libro->getSheet(0)->getColumnDimension('A')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('B')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('C')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('D')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('E')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('G')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('H')->setAutoSize(true);
    $libro->getSheet(0)->getColumnDimension('I')->setAutoSize(true);

    $hoja->setTitle('I1');
    $libro->getActiveSheet()->getStyle('A1:E2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('A1:E2')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $hoja->setCellValue('A1', 'Capacidad instalada calificacion');
    $hoja->setCellValue('A2', 'Fecha');
    $hoja->setCellValue('B2', 'Capacidad');
    $hoja->setCellValue('C2', 'Calificado');
    $hoja->setCellValue('D2', 'Productividad');
    $hoja->setCellValue('E2', 'Diferencia');
    $hoja->setCellValue('G2', 'Diferencia negativa');
    $hoja->setCellValue('H2', 'Costo prueba');
    $hoja->setCellValue('I2', 'Valor total');
    $iterador = 3;
    $iteradorPremarcado = count($arregloCompleto) + 6;
    $libro->getActiveSheet()->mergeCells('A' . ($iteradorPremarcado - 1) . ':' . 'E' . ($iteradorPremarcado - 1));
    $hoja->setTitle('I1');
    $libro->getActiveSheet()->getStyle('A' . ($iteradorPremarcado - 1) . ':' . 'E' . ($iteradorPremarcado - 1))->getFont()->setBold(true);
    $libro->getActiveSheet()->getStyle('A' . ($iteradorPremarcado - 1) . ':' . 'E' . ($iteradorPremarcado - 1))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('A' . ($iteradorPremarcado - 1) . ':' . 'E' . ($iteradorPremarcado - 1))
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('A' . ($iteradorPremarcado) . ':' . 'E' . ($iteradorPremarcado))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('A' . ($iteradorPremarcado) . ':' . 'E' . ($iteradorPremarcado))
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('A' . ($iteradorPremarcado) . ':' . 'E' . ($iteradorPremarcado))->getFont()->setBold(true);


    $libro->getActiveSheet()->getStyle('G2:I2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('G2:I2')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('G2:I2')->getFont()->setBold(true);


    $hoja->setCellValue('A' . ($iteradorPremarcado - 1), 'Capacidad instalada Premarcado');
    $hoja->setCellValue('A' . $iteradorPremarcado, 'Fecha');
    $hoja->setCellValue('B' . $iteradorPremarcado, 'Capacidad');
    $hoja->setCellValue('C' . $iteradorPremarcado, 'Calificado');
    $hoja->setCellValue('D' . $iteradorPremarcado, 'Productividad');
    $hoja->setCellValue('E' . $iteradorPremarcado, 'Diferencia');
    $hoja->setCellValue('G' . $iteradorPremarcado, 'Diferencia negativa');
    $hoja->setCellValue('H' . $iteradorPremarcado, 'Costo prueba');
    $hoja->setCellValue('I' . $iteradorPremarcado, 'Valor total');
    $iteradorPremarcado++;

    for ($i = 0; $i < count($arregloCompleto); $i++) {
        $hoja->setCellValue('A' . $iterador, $arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B' . $iterador, $arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
        $hoja->setCellValue('C' . $iterador, $arregloCompleto[$i]['totalCalificado']);
        $hoja->setCellValue('D' . $iterador, $arregloCompleto[$i]['productividadCalificacion']);
        $hoja->setCellValue('E' . $iterador, $arregloCompleto[$i]['diferenciaCalificacion']);
        $hoja->setCellValue('G' . $iterador, $arregloCompleto[$i]['diferenciaNegativaCalificacion']);
        $hoja->setCellValue('H' . $iterador, $arregloCompleto[$i]['costoPruebaCalificacion']);
        $hoja->setCellValue('I' . $iterador, $arregloCompleto[$i]['valorTotalCalificaion']);


        $hoja->setCellValue('A' . $iteradorPremarcado, $arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B' . $iteradorPremarcado, $arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
        $hoja->setCellValue('C' . $iteradorPremarcado, $arregloCompleto[$i]['totalPremarcado']);
        $hoja->setCellValue('D' . $iteradorPremarcado, $arregloCompleto[$i]['productividadPremarcado']);
        $hoja->setCellValue('E' . $iteradorPremarcado, $arregloCompleto[$i]['diferenciaPremarcado']);
        $hoja->setCellValue('G' . $iteradorPremarcado, $arregloCompleto[$i]['diferenciaNegativaPremarcado']);
        $hoja->setCellValue('H' . $iteradorPremarcado, $arregloCompleto[$i]['costoPruebaCalificacion']);
        $hoja->setCellValue('I' . $iteradorPremarcado, $arregloCompleto[$i]['valorTotalPremarcado']);
        $iterador++;
        $iteradorPremarcado++;
    }
    //Totalizado calificacion
    $hoja->setCellValue('A' . $iterador, 'Total');
    $hoja->setCellValue('B' . $iterador, $totalCapacidadCalificacionF);
    $hoja->setCellValue('C' . $iterador, $totalCalificadoF);
    $hoja->setCellValue('D' . $iterador, $totalProductividadCalificacionF);
    $hoja->setCellValue('E' . $iterador, $totalDiferenciaCalificacionF);
    $hoja->setCellValue('I' . $iterador, $valorTotalCalificacionF);
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['arg' => '000'],
            ],

        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $libro->getSheet(0)->getStyle('A1:E' . $iterador)->applyFromArray($styleArray);
    $libro->getSheet(0)->getStyle('G2:I' . $iterador)->applyFromArray($styleArray);

    $libro->getActiveSheet()->getStyle('A' . $iterador . ':E' . $iterador)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('A' . $iterador . ':E' . $iterador)
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('A' . $iterador . ':E' . $iterador)->getFont()->setBold(true);

    //totalizado premarcado
    $hoja->setCellValue('A' . $iteradorPremarcado, 'Total');
    $hoja->setCellValue('B' . $iteradorPremarcado, $totalCapacidadPremarcadoF);
    $hoja->setCellValue('C' . $iteradorPremarcado, $totalPremarcadoF);
    $hoja->setCellValue('D' . $iteradorPremarcado, $totalProductividadPremarcadoF);
    $hoja->setCellValue('E' . $iteradorPremarcado, $totalDiferenciaPremarcadoF);
    $hoja->setCellValue('I' . $iteradorPremarcado, $valorTotalPremarcadoF);
    $libro->getActiveSheet()->getStyle('A' . $iteradorPremarcado . ':E' . $iteradorPremarcado)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('A' . $iteradorPremarcado . ':E' . $iteradorPremarcado)
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('A' . $iteradorPremarcado . ':E' . $iteradorPremarcado)->getFont()->setBold(true);
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['arg' => '000'],
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $libro->getSheet(0)->getStyle('A' . (count($arregloCompleto) + 6) . ':E' . $iteradorPremarcado)->applyFromArray($styleArray);
    $libro->getSheet(0)->getStyle('G' . (count($arregloCompleto) + 6) . ':I' . $iteradorPremarcado)->applyFromArray($styleArray);

    $libro->getActiveSheet()->getStyle('G' . (count($arregloCompleto) + 6) . ':I' . (count($arregloCompleto) + 6))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(0)->getStyle('G' . (count($arregloCompleto) + 6) . ':I' . (count($arregloCompleto) + 6))
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getActiveSheet()->getStyle('G' . (count($arregloCompleto) + 6) . ':I' . (count($arregloCompleto) + 6))->getFont()->setBold(true);
    /*###################################################################################################################################################*/
    //Indicador 2
    $libro->createSheet();
    $libro->getSheet(1)->mergeCells('A1:O1');
    $libro->getSheet(1)->getColumnDimension('A')->setAutoSize(true);
    $libro->getSheet(1)->getColumnDimension('B')->setAutoSize(true);
    $libro->getSheet(1)->getColumnDimension('C')->setAutoSize(true);
    $libro->getSheet(1)->getColumnDimension('D')->setAutoSize(true);
    $libro->getSheet(1)->getColumnDimension('E')->setAutoSize(true);
    //$libro->getActiveSheet()->getColumnDimension('A')->setWidth(21);
    $hoja = $libro->getSheet(1);
    $hoja->setTitle('I2');
    $hoja->setCellValue('A1', 'TRAZABILIDAD COLABORADORES MES A MES');
    //Cabezera tabla
    $arregloLetras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF',
        'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
    $contador = 5;
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['arg' => '000'],
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];


    for ($i = 0; $i < count($arregloCompleto); $i++) {
        $libro->getSheet(1)->mergeCells('A' . ($contador - 2) . ':' . 'E' . ($contador - 2));
        $hoja->setCellValue('A' . ($contador - 2), $arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B' . ($contador - 1), 'Total calificacion');
        $hoja->setCellValue('C' . ($contador - 1), 'Productividad calificacion');
        $hoja->setCellValue('D' . ($contador - 1), 'Total premarcado');
        $hoja->setCellValue('E' . ($contador - 1), 'Productividad premarcado');
        $libro->getSheet(1)->getStyle('A' . ($contador - 1) . ':E' . ($contador - 1))->applyFromArray($styleArray);
        $libro->getSheet(1)->getStyle('A' . ($contador - 2) . ':E' . ($contador - 2))->applyFromArray($styleArray);

        $libro->getActiveSheet()->getStyle('A' . ($contador - 1) . ':E' . ($contador - 1))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(1)->getStyle('A' . ($contador - 1) . ':E' . ($contador - 1))
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . ($contador - 1) . ':E' . ($contador - 1))->getFont()->setBold(true);

        $libro->getSheet(1)->getStyle('A' . ($contador - 2) . ':E' . ($contador - 2))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(1)->getStyle('A' . ($contador - 2) . ':E' . ($contador - 2))
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . ($contador - 2) . ':E' . ($contador - 2))->getFont()->setBold(true);

        for ($j = 0; $j < count($arregloCompleto[$i]['data']); $j++) {
            $hoja->setCellValue('A' . $contador, $arregloCompleto[$i]['data'][$j]['nombre']);
            $hoja->setCellValue('B' . $contador, $arregloCompleto[$i]['data'][$j]['totalCalificado']);
            $hoja->setCellValue('C' . $contador, $arregloCompleto[$i]['data'][$j]['productividadCalificacion']);
            $hoja->setCellValue('D' . $contador, $arregloCompleto[$i]['data'][$j]['totalPremarcado']);
            $hoja->setCellValue('E' . $contador, $arregloCompleto[$i]['data'][$j]['productividadPremarcado']);
            $libro->getSheet(1)->getStyle('A' . $contador . ':E' . $contador)->applyFromArray($styleArray);
            $contador++;
        }
        $libro->getSheet(1)->getStyle('A1000:H1000')
            ->getAlignment()->setWrapText(true);
        $hoja->setCellValue('A' . $contador, 'Total calificado');
        $libro->getSheet(1)->getStyle('A' . $contador)
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $hoja->setCellValue('B' . $contador, $arregloCompleto[$i]['totalCalificado']);
        $hoja->setCellValue('A' . ($contador + 1), 'Total premarcado');
        $hoja->setCellValue('D' . ($contador + 1), $arregloCompleto[$i]['totalPremarcado']);
        $libro->getSheet(1)->getStyle('A' . $contador . ':E' . $contador)->applyFromArray($styleArray);
        $libro->getSheet(1)->getStyle('A' . ($contador + 1) . ':E' . ($contador + 1))->applyFromArray($styleArray);

        $libro->getActiveSheet()->getStyle('A' . $contador . ':E' . $contador)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(1)->getStyle('A' . $contador . ':E' . $contador)
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . $contador . ':E' . $contador)->getFont()->setBold(true);

        $libro->getSheet(1)->getStyle('A' . ($contador + 1) . ':E' . ($contador + 1))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(1)->getStyle('A' . ($contador + 1) . ':E' . ($contador + 1))
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . ($contador + 1) . ':E' . ($contador + 1))->getFont()->setBold(true);
        $contador = $contador + 5;

    }

    /*###################################################################################################################################################*/
    //Indicador 3
    $libro->createSheet();
    $libro->getSheet(2)->mergeCells('A1:I1');
    $libro->getSheet(2)->getColumnDimension('A')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('B')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('C')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('D')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('E')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('F')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('G')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('H')->setAutoSize(true);
    $libro->getSheet(2)->getColumnDimension('I')->setAutoSize(true);
    //$libro->getActiveSheet()->getColumnDimension('A')->setWidth(21);
    $hoja = $libro->getSheet(2);
    $hoja->setTitle('I3');
    $hoja->setCellValue('A1', 'TRAZABILIDAD COLABORADORES MES A MES');
    //Cabezera tabla
    $arregloLetras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF',
        'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
    $contador = 5;
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['arg' => '000'],
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];
    $i = null;
    if ($fechaFinal == '' || $fechaFinal == null) {
        $fechaFinal = $fechaInicial;
    }

    for ($n = 0; $n < count($arregloCompleto); $n++) {
        if ($arregloCompleto[$n]['fecha'] == $fechaFinal) {
            $i = $n;
        }
    }

    //if ($i != null) {

        $libro->getSheet(2)->mergeCells('A' . ($contador - 2) . ':' . 'I' . ($contador - 2));
        $hoja->setCellValue('A' . ($contador - 2), $arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B' . ($contador - 1), 'Total calificacion');
        $hoja->setCellValue('C' . ($contador - 1), 'Capacidad instalada');
        $hoja->setCellValue('D' . ($contador - 1), 'Productividad calificacion');
        $hoja->setCellValue('E' . ($contador - 1), 'Total premarcado');
        $hoja->setCellValue('F' . ($contador - 1), 'Productividad premarcado');
        $hoja->setCellValue('G' . ($contador - 1), 'Participacion premarcado');
        $hoja->setCellValue('H' . ($contador - 1), 'Total escaneado');
        $hoja->setCellValue('I' . ($contador - 1), 'Participacion escaneo');
        $libro->getSheet(2)->getStyle('A' . ($contador - 1) . ':I' . ($contador - 1))->applyFromArray($styleArray);
        $libro->getSheet(2)->getStyle('A' . ($contador - 2) . ':I' . ($contador - 2))->applyFromArray($styleArray);

        $libro->getActiveSheet()->getStyle('A' . ($contador - 1) . ':I' . ($contador - 1))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(2)->getStyle('A' . ($contador - 1) . ':I' . ($contador - 1))
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . ($contador - 1) . ':I' . ($contador - 1))->getFont()->setBold(true);

        $libro->getSheet(2)->getStyle('A' . ($contador - 2) . ':I' . ($contador - 2))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(2)->getStyle('A' . ($contador - 2) . ':I' . ($contador - 2))
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . ($contador - 2) . ':I' . ($contador - 2))->getFont()->setBold(true);

        for ($j = 0; $j < count($arregloCompleto[$i]['data']); $j++) {
            $hoja->setCellValue('A' . $contador, $arregloCompleto[$i]['data'][$j]['nombre']);
            $hoja->setCellValue('B' . $contador, $arregloCompleto[$i]['data'][$j]['totalCalificado']);
            $hoja->setCellValue('C' . $contador, $arregloCompleto[$i]['capacidadInstaladaCalificacion']);
            $hoja->setCellValue('D' . $contador, $arregloCompleto[$i]['data'][$j]['productividadCalificacion']);
            $hoja->setCellValue('E' . $contador, $arregloCompleto[$i]['data'][$j]['totalPremarcado']);
            $hoja->setCellValue('F' . $contador, $arregloCompleto[$i]['data'][$j]['productividadPremarcado']);
            $hoja->setCellValue('G' . $contador, $arregloCompleto[$i]['data'][$j]['participacionPremarcado']);
            $hoja->setCellValue('H' . $contador, $arregloCompleto[$i]['data'][$j]['totalEscaneado']);
            $hoja->setCellValue('I' . $contador, $arregloCompleto[$i]['data'][$j]['participacionEscaneo']);
            $libro->getSheet(2)->getStyle('A' . $contador . ':I' . $contador)->applyFromArray($styleArray);
            $contador++;
        }
        $libro->getSheet(2)->getStyle('A1000:H1000')
            ->getAlignment()->setWrapText(true);
        $hoja->setCellValue('A' . $contador, 'Total');
        $libro->getSheet(2)->getStyle('A' . $contador)
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $hoja->setCellValue('B' . $contador, $arregloCompleto[$i]['totalCalificado']);
        $hoja->setCellValue('E' . ($contador), $arregloCompleto[$i]['totalPremarcado']);
        $hoja->setCellValue('H' . ($contador), $arregloCompleto[$i]['totalEscaneado']);
        $libro->getSheet(2)->getStyle('A' . $contador . ':I' . $contador)->applyFromArray($styleArray);
        $libro->getActiveSheet()->getStyle('A' . $contador . ':I' . $contador)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0070C0');
        $libro->getSheet(2)->getStyle('A' . $contador . ':I' . $contador)
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $libro->getActiveSheet()->getStyle('A' . $contador . ':I' . $contador)->getFont()->setBold(true);


        $contador = $contador + 5;
   // }

    /*###################################################################################################################################################*/
    //Indicador 3
    $libro->createSheet();
    $datosQuemados = [[
        'fecha' => '2017-01',
        'cantidadColegio' => '23',
        'cantidadEstudiantes'=>'1535'
    ],
        [
            'fecha' => '2017-02',
            'cantidadColegio' => '240',
            'cantidadEstudiantes'=>'23026'
        ],
        [
            'fecha' => '2017-03',
            'cantidadColegio' => '626',
            'cantidadEstudiantes'=>'109520'
        ],
        [
            'fecha' => '2017-04',
            'cantidadColegio' => '612',
            'cantidadEstudiantes'=>'111736'
        ],
        [
            'fecha' => '2017-05',
            'cantidadColegio' => '627',
            'cantidadEstudiantes'=>'84103'
        ],
        [
            'fecha' => '2017-06',
            'cantidadColegio' => '575',
            'cantidadEstudiantes'=>'82010'
        ],
        [
            'fecha' => '2017-07',
            'cantidadColegio' => '814',
            'cantidadEstudiantes'=>'83398'
        ],
        [
            'fecha' => '2017-08',
            'cantidadColegio' => '1729',
            'cantidadEstudiantes'=>'212841'
        ],
        [
            'fecha' => '2017-09',
            'cantidadColegio' => '1060',
            'cantidadEstudiantes'=>'164530'
        ],
        [
            'fecha' => '2017-10',
            'cantidadColegio' => '429',
            'cantidadEstudiantes'=>'60514'
        ],
        [
            'fecha' => '2017-11',
            'cantidadColegio' => '480',
            'cantidadEstudiantes'=>'114024'
        ],
        [
            'fecha' => '2017-12',
            'cantidadColegio' => '148',
            'cantidadEstudiantes'=>'25957'
        ]];

    $libro->getSheet(3)->getColumnDimension('A')->setAutoSize(true);
    /*
    $libro->getSheet(3)->getColumnDimension('B')->setAutoSize(true);
    $libro->getSheet(3)->getColumnDimension('C')->setAutoSize(true);
    */
    $libro->getSheet(3)->getColumnDimension('D')->setAutoSize(true);
    $libro->getSheet(3)->getColumnDimension('E')->setAutoSize(true);


    //$libro->getActiveSheet()->getColumnDimension('A')->setWidth(21);

    $hoja = $libro->getSheet(3);
    $hoja->setTitle('I4');
    $libro->getSheet(3)->getStyle('A1:E3')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(3)->getStyle('A1:E3')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getSheet(3)->getStyle('A1:E3')->getFont()->setBold(true);

    $libro->getSheet(3)->mergeCells('A1:E1');
    $libro->getSheet(3)->mergeCells('A2:A3');
    $libro->getSheet(3)->mergeCells('B2:E2');
    $libro->getSheet(3)->getStyle('A1:E3')->applyFromArray($styleArray);

    $hoja->setCellValue('A1','TRAZABILIDAD DE ANÁLISIS ACADÉMICO');
    $hoja->setCellValue('A2','MES');
    $hoja->setCellValue('B2','CANTIDAD DE COLEGIOS');
    $hoja->setCellValue('B3','2017');
    $hoja->setCellValue('C3','2018');
    $hoja->setCellValue('D3','Diferencia');
    $hoja->setCellValue('E3','Crecimiento');


    $libro->getSheet(3)->getStyle('G1:K3')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(3)->getStyle('G1:K3')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getSheet(3)->getStyle('A1:K3')->getFont()->setBold(true);

    $libro->getSheet(3)->mergeCells('G1:K1');
    $libro->getSheet(3)->mergeCells('G2:G3');
    $libro->getSheet(3)->mergeCells('H2:K2');
    $libro->getSheet(3)->getStyle('G1:K3')->applyFromArray($styleArray);

    $hoja->setCellValue('G1','TRAZABILIDAD DE ANÁLISIS ACADÉMICO');
    $hoja->setCellValue('G2','MES');
    $hoja->setCellValue('H2','CANTIDAD DE ESTUDIANTES');
    $hoja->setCellValue('H3','2017');
    $hoja->setCellValue('I3','2018');
    $hoja->setCellValue('J3','Diferencia');
    $hoja->setCellValue('K3','Crecimiento');
    $contador = 4;
    $total17Colegio=0;
    $total18Colegio=0;
    $total17Estu=0;
    $total18Estu=0;
    $totalDiferenciaColegio=0;
    $totalDiferenciaEstudiante=0;
    $totalCrecimientoColegio=0;
    $totalCrecimientoEstudiante=0;
    for($i=0; $i<count($arregloCompleto); $i++){
        $fecha = explode('-',$arregloCompleto[$i]['fecha']);
        $posicion=null;
        for ($n=0; $n<count($datosQuemados);$n++){
            $fecha2 = explode('-',$datosQuemados[$n]['fecha']);
            if ($fecha[1]==$fecha2[1]){
                $posicion=$n;
            }
        }
        if ($posicion!= null){
            $hoja->setCellValue('A'.$contador,$fecha[1]);
            $hoja->setCellValue('B'.$contador,$datosQuemados[$posicion]['cantidadColegio']);
            $hoja->setCellValue('C'.$contador,$arregloCompleto[$i]['totalColegios']);
            $hoja->setCellValue('D'.$contador,($datosQuemados[$posicion]['cantidadColegio']-$arregloCompleto[$i]['totalColegios']));
            $hoja->setCellValue('E'.$contador,(($datosQuemados[$posicion]['cantidadColegio']-$arregloCompleto[$i]['totalColegios'])*100)/$datosQuemados[$posicion]['cantidadColegio']);

            $total17Colegio+=$datosQuemados[$posicion]['cantidadColegio'];
            $total18Colegio+=$arregloCompleto[$i]['totalColegios'];
            $totalDiferenciaColegio+=($datosQuemados[$posicion]['cantidadColegio']-$arregloCompleto[$i]['totalColegios']);
            $totalCrecimientoColegio+=(($datosQuemados[$posicion]['cantidadColegio']-$arregloCompleto[$i]['totalColegios'])*100)/$datosQuemados[$posicion]['cantidadColegio'];

            $hoja->setCellValue('G'.$contador,$fecha[1]);
            $hoja->setCellValue('H'.$contador,$datosQuemados[$posicion]['cantidadEstudiantes']);
            $hoja->setCellValue('I'.$contador,$arregloCompleto[$i]['totalCalificado']);
            $hoja->setCellValue('J'.$contador,($datosQuemados[$posicion]['cantidadEstudiantes']-$arregloCompleto[$i]['totalCalificado']));
            $hoja->setCellValue('K'.$contador,(($datosQuemados[$posicion]['cantidadEstudiantes']-$arregloCompleto[$i]['totalCalificado'])*100)/$datosQuemados[$posicion]['cantidadEstudiantes']);
            $total17Estu+=$datosQuemados[$posicion]['cantidadEstudiantes'];
            $total18Estu+=$arregloCompleto[$i]['totalCalificado'];
            $totalDiferenciaEstudiante+=($datosQuemados[$posicion]['cantidadEstudiantes']-$arregloCompleto[$i]['totalCalificado']);
            $totalCrecimientoEstudiante+=(($datosQuemados[$posicion]['cantidadEstudiantes']-$arregloCompleto[$i]['totalCalificado'])*100)/$datosQuemados[$posicion]['cantidadEstudiantes'];
            $contador++;
        }

    }
    $hoja->setCellValue('B'.$contador,$total17Colegio);
    $hoja->setCellValue('C'.$contador,$total18Colegio);
    $hoja->setCellValue('D'.$contador,$totalDiferenciaColegio);
    $hoja->setCellValue('E'.$contador,$totalCrecimientoColegio);

    $libro->getSheet(3)->getStyle('A'.($contador).':E'.($contador))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(3)->getStyle('A'.($contador).':E'.($contador))
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getSheet(3)->getStyle('A'.($contador).':E'.($contador))->getFont()->setBold(true);


    $libro->getSheet(3)->getStyle('A1:E'.($contador))->applyFromArray($styleArray);




    $hoja->setCellValue('H'.$contador,$total17Estu);
    $hoja->setCellValue('I'.$contador,$total18Estu);
    $hoja->setCellValue('J'.$contador,$totalDiferenciaEstudiante);
    $hoja->setCellValue('K'.$contador,$totalCrecimientoEstudiante);

    $libro->getSheet(3)->getStyle('G'.($contador).':K'.($contador))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0070C0');
    $libro->getSheet(3)->getStyle('G'.$contador.':K'.$contador)
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $libro->getSheet(3)->getStyle('G'.$contador.':K'.$contador)->getFont()->setBold(true);

    $libro->getSheet(3)->getStyle('G1:K'.$contador)->applyFromArray($styleArray);
    $excel = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($libro);

    $excel->save('./App/public/excel/Reporte_indicadores_premarcado_calificacion.xlsx');

    return '/excel/Reporte_indicadores_premarcado_calificacion.xlsx';
}