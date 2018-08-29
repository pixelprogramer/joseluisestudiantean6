<?php
$app->post('/pantallas/premarcado', function () use ($app) {
    $helper = new helper();
    $conexion = new conexMsql();
    $sql = "SELECT ord.id,
ord.code, ord.created_at as createdAt,  
ord.state,
sell.name, sell.surname, sch.dane schdane,
sch.description as schdescription,
st.description as stdescription,
ot.description as otdescription, (SELECT GROUP_CONCAT(pro.code) odid FROM order_details ordet INNER JOIN products pro ON ordet.id_product = pro.id WHERE ordet.id_order = ord.id) as od
        FROM orders ord INNER JOIN users sell ON ord.id_seller = sell.id INNER JOIN schools sch ON ord.id_school = sch.id INNER JOIN shipping shi ON ord.id_ship = shi.id INNER JOIN shipping_type st ON ord.id_ship_type = st.id INNER JOIN order_type ot ON ord.id_order_type = ot.id 
        WHERE ord.state IN ('ACTIVO','PROCESO') AND ord.id_order_type = 3
ORDER BY `ord`.`id`  ASC";
    $r = $conexion->consultaComplejaAso($sql);
    $arregloFinal = array();

    for ($i = 2; $i < count($r); $i++) {
        $arreglood = explode(',', $r[$i]['od']);
        $arregloDetalles = array();
        $contado = 0;
        $contadorCompleto = 0;
        $detalle = '';
        if (count($arreglood) > 7) {
            for ($j = 0; $j < count($arreglood); $j++) {

                if ($contado < 7) {
                    $detalle .= $arreglood[$j] . '-';

                } else {
                    $parrafo = "<p style='margin: 0px'>" . $detalle . "</p>";
                    array_push($arregloDetalles, $parrafo);
                    $contado = 0;
                    $detalle = '';
                    $contadorCompleto = $contadorCompleto + 7;
                }
                $contado++;

            }
        }
        if ($contadorCompleto < count($arreglood)) {
            $detalle = '';
            for ($n = $contadorCompleto; $n < count($arreglood); $n++) {
                $detalle .= $arreglood[$n] . '-';
            }
            $parrafo = "<p style='margin: 0px'>" . $detalle . "</p>";
            array_push($arregloDetalles, $parrafo);
            $contadorCompleto = 0;
        }

        array_push($arregloFinal, ['id' => $r[$i]['id'], 'code' => $r[$i]['code'],
            'createdAt' => $r[$i]['createdAt'], 'state' => $r[$i]['state'],
            'name' => $r[$i]['name'], 'surname' => $r[$i]['surname'],
            'schdane' => $r[$i]['schdane'], 'schdescription' => $r[$i]['schdescription'],
            'stdescription' => $r[$i]['stdescription'], 'otdescription' => $r[$i]['otdescription'],
            'od' => $r[$i]['od'], 'detalles' => $arregloDetalles]);
    }

    $data = [
        'code' => 'LTE-001',
        'data' => $arregloFinal
    ];
    echo $helper->checkCode($data);
});
$app->post('/premarcado/datosOrders', function () use ($app) {
    $helper = new helper();
    $idOrderType = "3";
    $conexion = new conexMsql();
    $sql = "SELECT COUNT(ord.id) countCol FROM orders ord WHERE ord.id_order_type = " . $idOrderType . " AND ord.state IN ('ACTIVO', 'PROCESO')";
    $contadorColegio = $conexion->consultaComplejaNorAso($sql);
    $sql = "SELECT SUM(A.countOrder) as countEst FROM (
                    SELECT SUM(od.quantity_order) countOrder
                    FROM orders ord
                        INNER JOIN (order_details od 
                            INNER JOIN (products pro 
                                LEFT JOIN products_type pt ON pro.id_product_type = pt.id
                            )ON od.id_product = pro.id
                        )ON ord.id = od.id_order
                    WHERE ord.id_order_type = " . $idOrderType . " AND ord.state IN ('ACTIVO', 'PROCESO')
                    GROUP BY pro.id_product_type) as A";
    $cantidadEstudiantes= $conexion->consultaComplejaNorAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => [
            'cantidadColegios'=>$contadorColegio['countCol'],
            'cantidadEstudiantes'=>$cantidadEstudiantes['countEst']
        ]
    ];
    echo $helper->checkCode($data);
});