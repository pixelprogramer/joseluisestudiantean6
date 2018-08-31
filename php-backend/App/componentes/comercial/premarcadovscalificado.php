<?php
$app->post('/consultaxls', function () use ($app) {
    $helper = new helper();

    $sql = "SELECT sell.id sellid, sch.id schid, sch.dane, sch.description schdescription, orde.id, orde.code, orde.id_order_type, sell.name, sell.surname, pro.id proid, pro.description prodescription, od.quantity_order, od.quantity_premarcado, od.quantity_calificado 
                FROM orders orde 
                    INNER JOIN (order_details od 
                        INNER JOIN products pro ON od.id_product = pro.id
                    )ON orde.id = od.id_order 
                    INNER JOIN users sell ON orde.id_seller = sell.id 
                    INNER JOIN schools sch ON orde.id_school = sch.id 
                WHERE orde.id_order_type IN (3,4) AND orde.state = 'DESPACHO' and 
					 DATE_FORMAT(orde.created_at,'%Y-%m') between '2018-08' and '2018-08'";

    $conexion = new conexMsql();
    $r = $conexion->consultaComplejaAso($sql);

    $premvscali = array();
    # var_dump($r);exit;
    foreach ($r as $key_1 => $val_1) {
        # var_dump($val_1['schdescription']);exit;
        $premvscali[$val_1['sellid']]['nombre'] = $val_1['name'] . ' ' . $val_1['surname'];
        $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']]['coldescription'] = $val_1['schdescription'];
        $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['prodescription'] = $val_1['prodescription'];
        if ($val_1['id_order_type'] == 3) {
            if (!isset($premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['code'])) {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['code'] = $val_1['code'];
            } else {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['code'] = $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['code'] . ', ' . $val_1['code'];
            }

            if (!isset($premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['cantidad'])) {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['cantidad'] = 0 + $val_1['quantity_premarcado'];
            } else {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['premarcado']['cantidad'] += $val_1['quantity_premarcado'];
            }
        }
        if ($val_1['id_order_type'] == 4) {
            if (!isset($premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['code'])) {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['code'] = $val_1['code'];
            } else {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['code'] = $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['code'] . ', ' . $val_1['code'];
            }

            if (!isset($premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['cantidad'])) {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['cantidad'] = 0 + $val_1['quantity_calificado'];
            } else {
                $premvscali[$val_1['sellid']]['colegio'][$val_1['dane']][$val_1['proid']]['calificado']['cantidad'] += $val_1['quantity_calificado'];
            }

        }
    }

    $data = [
        'code' => 'LTE-001',
        'data' => $premvscali
    ];


    echo $helper->checkCode($data);
});

$app->post('/calificacionvspremarcado', function () use ($app) {
    $helper = new helper();
    $conexion = new conexMsql();

    $sql = "select orde.id,orde.id_seller,orde.id_order_type,sch.dane,pro.description as descripcion_producto,ordt.id_product,orde.id_seller,
sch.description as descripcion_colegio,ordt.quantity_calificado, ordt.quantity_premarcado from orders orde 
inner join schools sch on orde.id_school=sch.id 
inner join order_details ordt on orde.id=ordt.id_order
inner join products pro on ordt.id_product=pro.id
where orde.id_order_type in (4,3)  and orde.state = 'DESPACHO' 
and DATE_FORMAT(orde.created_at,'%Y-%m') between '2018-01' and '2018-08'  order by sch.dane, pro.id asc";
    $r = $conexion->consultaComplejaAso($sql);
    $arregloCompleto = array();
    if ($r != 0) {
        for ($i = 0; $i < count($r); $i++) {
            $validacionEntradaColegio=0;
            for ($n = 0; $n < count($arregloCompleto); $n++) {
                if ($arregloCompleto[$n]['dane'] == $r[$i]['dane']) {
                    $validacionEntrada = 0;
                    $validacionEntradaColegio = 1;
                    for ($y = 0; $y < count($arregloCompleto[$n]['cantidad']); $y++) {
                        if ($r[$i]['id_product'] == $arregloCompleto[$n]['cantidad'][$y]['id']) {
                            $calificado = 0;
                            $premarcado = 0;
                            if ($r[$i]['id_order_type'] == '4') {
                                $calificado = $r[$i]['quantity_calificado'];
                            } else if ($r[$i]['id_order_type'] == '3') {
                                $premarcado = $r[$i]['quantity_premarcado'];
                            }
                            $arregloCompleto[$n]['cantidad'][$y]['calificado'] += $calificado;
                            $arregloCompleto[$n]['cantidad'][$y]['premarcado'] += $premarcado;
                            $validacionEntrada = 1;
                        }
                    }
                    if ($validacionEntrada == 0) {
                        $calificado = 0;
                        $premarcado = 0;
                        if ($r[$i]['id_order_type'] == '4') {
                            $calificado = $r[$i]['quantity_calificado'];
                        } else if ($r[$i]['id_order_type'] == '3') {
                            $premarcado = $r[$i]['quantity_premarcado'];
                        }

                        array_push($arregloCompleto[$n]['cantidad'], [
                            'id' => $r[$i]['id_product'],
                            'producto' => $r[$i]['descripcion_producto'],
                            'calificado' => $calificado,
                            'premarcado' => $premarcado
                        ]);
                    }
                }
            }
            if ($validacionEntradaColegio == 0){
                $calificado = 0;
                $premarcado = 0;
                if ($r[$i]['id_order_type'] == '4') {
                    $calificado = $r[$i]['quantity_calificado'];
                } else if ($r[$i]['id_order_type'] == '3') {
                    $premarcado = $r[$i]['quantity_premarcado'];
                }
                array_push($arregloCompleto, [
                    'dane' => $r[$i]['dane'],
                    'colegio' => $r[$i]['descripcion_colegio'],
                    'cantidad' => [[
                        'id' => $r[$i]['id_product'],
                        'producto' => $r[$i]['descripcion_producto'],
                        'calificado' => $calificado,
                        'premarcado' => $premarcado
                    ]
                    ]
                ]);
            }
        }
        $data = [
            'code'=>'LTE-001',
            'data'=>$arregloCompleto
        ];
        generarReporteExcelCalificacionvspremarcado($arregloCompleto);
    } else {
        $data = [
            'code' => 'LTE-000',
            'msg' => 'Lo sentimos, no se encontraron resultados',
            'status' => 'error'
        ];
    }
    echo $helper->checkCode($data);
});
function generarReporteExcelCalificacionvspremarcado($arregloCompleto)
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
    $hoja->setCellValue('A1', 'Calificacion vs premarcado');
    $hoja->setCellValue('A2', 'Dane');
    $hoja->setCellValue('B2', 'Colegio');
    $hoja->setCellValue('C2', 'Producto');
    $hoja->setCellValue('D2', 'Premarcado');
    $hoja->setCellValue('E2', 'Calificado');
    $iterador = 3;

    for ($i = 0; $i < count($arregloCompleto); $i++) {
        $hoja->setCellValue('A' . $iterador, $arregloCompleto[$i]['dane']);
        $hoja->setCellValue('B' . $iterador, $arregloCompleto[$i]['colegio']);
        for ($n = 0; $n<count($arregloCompleto[$i]['cantidad']); $n++){
            $hoja->setCellValue('A' . $iterador, $arregloCompleto[$i]['dane']);
            $hoja->setCellValue('B' . $iterador, $arregloCompleto[$i]['colegio']);
            $hoja->setCellValue('C' . $iterador, $arregloCompleto[$i]['cantidad'][$n]['producto']);
            $hoja->setCellValue('D' . $iterador, $arregloCompleto[$i]['cantidad'][$n]['premarcado']);
            $hoja->setCellValue('E' . $iterador, $arregloCompleto[$i]['cantidad'][$n]['calificado']);
            $iterador++;
        }


    }
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

    $excel = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($libro);

    $excel->save('./App/public/excel/calificacionvspremarcado.xlsx');

    //return '/excel/Reporte_indicadores_premarcado_calificacion.xlsx';
}