<?php
$app->post('/calificacion/listarEstudiantesPaquetes', function () use ($app) {
    $helper = new helper();
    $conexion = new conexPG();
    $cadenaPaquetes = $app->request->post('cadenaPaquetes', null);
    $sql = "SELECT calixestuidn, e.estuidn, caliidn, usuaidn, calixesturespv, examidn, calipreorden, score, overallscore, calificado, e.estunombrev
FROM public.calificacionxestudiante cxe INNER JOIN estudiante e ON cxe.estuidn = e.estuidn
WHERE caliidn IN (" . $cadenaPaquetes . ")
ORDER BY e.estunombrev ASC, cxe.caliidn ASC, e.estuidn ASC, examidn ASC;";
    $r = $conexion->consultaComplejaAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});
$app->post('/calificacion/unificarEstudiantes', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token',null);
    if ($token != null )
    {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true)
        {
            $usuarioToken = $helper->authCheck($token,true);
            $conexion = new conexPG();
            $cadenaEstudiantes = $app->request->post('cadenaEstudiantes', null);
            $sql1 = "SELECT calixestuidn, e.estuidn, caliidn, usuaidn, calixesturespv, examidn, calipreorden, score, overallscore, calificado, e.estunombrev
FROM public.calificacionxestudiante cxe INNER JOIN estudiante e ON cxe.estuidn = e.estuidn
WHERE calixestuidn IN (" . $cadenaEstudiantes . ")
ORDER BY e.estunombrev ASC, cxe.caliidn ASC, e.estuidn ASC, examidn ASC;";
            $r = $conexion->consultaComplejaAso($sql1);
            $arreglocalixestuidn = array();
            if ($r != 0) {
                for ($i = 0; $i < count($r); $i++) {
                    for ($j = 1; $j < count($r); $j++) {
                        if ($r[$i]['examidn'] == $r[$j]['examidn']) {
                            if (trim($r[$i]['calixesturespv']) == '' || $r[$i]['calixesturespv'] == null) {
                                array_push($arreglocalixestuidn, trim($r[$i]['calixestuidn']));
                            } else if (trim($r[$j]['calixesturespv']) == '' || $r[$j]['calixesturespv'] == null) {
                                array_push($arreglocalixestuidn, trim($r[$j]['calixestuidn']));
                            }
                        }
                    }
                }
                $lista_simple = array_values(array_unique($arreglocalixestuidn));
                $cadenaEliminados ='';
                $coma = ',';
                for ($i =0; $i<count($lista_simple); $i++)
                {
                    if ($cadenaEliminados != '')
                        $cadenaEliminados .=$coma;

                    $cadenaEliminados .=$lista_simple[$i];
                }
                if (trim($cadenaEliminados) != '')
                {
                    $sql = "DELETE FROM public.calificacionxestudiante WHERE calixestuidn IN (".$cadenaEliminados.");";
                    $conexion->consultaSimple($sql);
                }
                $r = $conexion->consultaComplejaAso($sql1);
                $cadenaActualizar ='';
                $coma = ',';
                for ($i =0; $i<count($r); $i++)
                {
                    if ($cadenaActualizar != '')
                        $cadenaActualizar .=$coma;

                    $cadenaActualizar .=$r[$i]['calixestuidn'];
                }


                $caliidn=$r[0]['estuidn'];
                $sql = "UPDATE public.calificacionxestudiante
                SET estuidn='$caliidn' WHERE calixestuidn in (".$cadenaActualizar.") ";
                $conexion->consultaSimple($sql);
                $fecha_creacion_menu = date('Y-m-d H:i');

                $descripcionAccion = 'se unificaron los estudiantes: '.$cadenaEstudiantes;
                $sql = "INSERT INTO configuracion.log_lte(
                        fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                        VALUES ('$fecha_creacion_menu', '$descripcionAccion', '$usuarioToken->sub');";
                $conexion = new conexPGSeguridad();
                $conexion->consultaSimple($sql);
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, No se encontraron los estudiantes'
                ];
            }


            $data = [
                'code' => 'LTE-001'
            ];
        }else
        {
            $data = [
                'code'=>'LTE-013'
            ];
        }
    }else
    {
        $data = [
            'code'=>'LTE-013'
        ];
    }

    echo $helper->checkCode($data);
});
$app->post('/calificacion/filtrarEstudianteUnificar', function () use ($app) {
    $helper = new helper();
    $conexion = new conexPG();
    $cadenaPaquetes = $app->request->post('cadenaPaquetes', null);
    $filtro = $app->request->post('filtro',null);
    $sql = "SELECT calixestuidn, e.estuidn, caliidn, usuaidn, calixesturespv, examidn, calipreorden, score, overallscore, calificado, e.estunombrev
FROM public.calificacionxestudiante cxe INNER JOIN estudiante e ON cxe.estuidn = e.estuidn
WHERE caliidn IN (" . $cadenaPaquetes . ") and e.estunombrev like '%$filtro%'
ORDER BY e.estunombrev ASC, cxe.caliidn ASC, e.estuidn ASC, examidn ASC;";
    $r = $conexion->consultaComplejaAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});