<?php
$app->post('/minutas/unix/listarMinutasUsuario', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $usuarioToken = $helper->authCheck($token, true);
            $sql = "select rgm.id_registro_minutas,fecha_creacion_minuta, rgm.id_inicial_registro_minutas,
                    rgm.fecha_hora_inicio_minuta,
                    rgm.fecha_hora_fin_minuta,
                    rgm.horas_totales,
                    rgm.estado_minuta,rgm.observacion_minuta,
                    pm.descripcion_pedido_minutas,
                    am.descripcion_acciones_minutas,
                    am.id_acciones_minutas,
                    am.seleccion_pedido_accion,
                    rgm.id_registro_minutas_fk,
                    rgm.id_pedido_minutas_fk,
                    rgm.descripcion_minuta,
                    pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                    left join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                    join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                    join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                    where usu.id_usuario ='$usuarioToken->sub' and (rgm.estado_minuta like 'TERMINADOF' or rgm.estado_minuta like  
                    'SINTERMINAR' or rgm.estado_minuta like 'DETENIDA') order by rgm.estado_minuta !='DETENIDA',rgm.estado_minuta !='SINTERMINAR',rgm.estado_minuta !='TERMINADOF'  desc limit 50";
            $arregloFinal = array();
            $r = $conexion->consultaComplejaAso($sql);
            for ($i = 0; $i < count($r); $i++) {
                $r2 = null;
                if ($r[$i]['id_inicial_registro_minutas'] != null || $r[$i]['id_inicial_registro_minutas'] != '') {
                    $registorInicial = $r[$i]['id_inicial_registro_minutas'];
                    $idRegistroActual = $r[$i]['id_registro_minutas'];
                    $sql = "select rgm.fecha_creacion_minuta, rgm.id_registro_minutas,
                            rgm.fecha_hora_inicio_minuta,
                            rgm.fecha_hora_fin_minuta,
                            rgm.horas_totales,
                            rgm.estado_minuta,rgm.observacion_minuta,
                            pm.descripcion_pedido_minutas,
                            am.descripcion_acciones_minutas,
                            rgm.id_registro_minutas_fk,
                            pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                            join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                            join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                            join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                            where usu.id_usuario ='$usuarioToken->sub'  and  rgm.estado_minuta like 'TERMINADOC' and rgm.id_inicial_registro_minutas ='$registorInicial' or rgm.id_registro_minutas='$registorInicial' ";
                    $r2 = $conexion->consultaComplejaAso($sql);
                }
                array_push($arregloFinal, ['registro' => $r[$i],
                    'subRegistros' => $r2]);
            }


            $data = [
                'code' => 'LTE-001',
                'data' => $arregloFinal
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/listarPedidosDetalles', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexMsql();
                $parametros = json_decode($json);
                $listaPedido = (isset($parametros->pedidos)) ? $parametros->pedidos : null;
                $id_tipo_pedido_minutas_fk = (isset($parametros->id_tipo_pedido_minutas_fk)) ? $parametros->id_tipo_pedido_minutas_fk : null;
                $sql = "select orde.id as id_orden,orde.code,sch.description, usuario.name,orde.observations,orde.id_order_type from orders orde 
                        join schools sch on orde.id_school=sch.id
                        join users usuario on orde.id_seller=usuario.id 
                        where orde.id_order_type in ('$listaPedido') and orde.state in ('ACTIVO','PROCESO');";
                $r = $conexion->consultaComplejaAso($sql);
                $arregloFinal = array();
                for ($i = 0; $i < count($r); $i++) {
                    $r2 = null;
                    $id_pedido = $r[$i]['id_orden'];
                    $sql = "select ordt.id,ordt.quantity_order,ordt.quantity_calificado,ordt.quantity_planb,ordt.quantity_premarcado
                            ,prod.description as prod_description,ordt.description from order_details ordt join orders orde on ordt.id_order=orde.id 
                            join products prod on ordt.id_product=prod.id where orde.id = '$id_pedido'";
                    $r2 = $conexion->consultaComplejaAso($sql);
                    array_push($arregloFinal, [
                        'pedido' => $r[$i],
                        'cantidad_detalles' => count($r2),
                        'detalle' => $r2
                    ]);
                }
                $data = [
                    'code' => 'LTE-001',
                    'data' => $arregloFinal
                ];
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/crearRegistroMinutas', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $usuarioToken = $helper->authCheck($token, true);
                if (validarEstadoPedido($usuarioToken) == true) {

                    $codigo_pedido_minutas = (isset($parametros->codigo_pedido_minutas)) ? $parametros->codigo_pedido_minutas : null;
                    $validacioExitenciaPedido = validarExistenciaPedido($codigo_pedido_minutas);
                    if ($validacioExitenciaPedido == null) {
                        $descripcion_pedido_minutas = (isset($parametros->descripcion_pedido_minutas)) ? $parametros->descripcion_pedido_minutas : null;
                        $destribuidor_pedido_minutas = (isset($parametros->destribuidor_pedido_minutas)) ? $parametros->destribuidor_pedido_minutas : null;
                        $sql = "INSERT INTO minutas.pedidos_minutas(
                        codigo_pedido_minutas, descripcion_pedido_minutas, destribuidor_pedido_minutas)
                        VALUES ('$codigo_pedido_minutas', '$descripcion_pedido_minutas', '$destribuidor_pedido_minutas') returning id_pedido_minutas;";
                        $r = $conexion->consultaComplejaNorAso($sql);
                        $validacioExitenciaPedido = $r['id_pedido_minutas'];
                    }
                    $fecha_creacion_minuta = date('Y-m-d H:i');
                    $fecha_hora_inicio_minuta = date('Y-m-d H:i');
                    $estado_minuta = 'SINTERMINAR';
                    $id_usuario_minutas_fk = $usuarioToken->sub;
                    $id_acciones_minutas_fk = $app->request->post('idAccion', null);
                    $id_pedido_minutas_fk = $validacioExitenciaPedido;
                    $horas_totales = '0';
                    $detalle = $app->request->post('detalle', null);
                    $sql = "INSERT INTO minutas.registro_minutas(
                             fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, estado_minuta,
                              id_usuario_minutas_fk, id_acciones_minutas_fk, id_pedido_minutas_fk)
                            VALUES (   '$fecha_creacion_minuta', '$fecha_hora_inicio_minuta', '$horas_totales', '$estado_minuta', 
                            '$id_usuario_minutas_fk', '$id_acciones_minutas_fk', '$id_pedido_minutas_fk') returning id_registro_minutas;";

                    $r = $conexion->consultaComplejaNorAso($sql);
                    metodoTiempoMuertoTemporal($usuarioToken->sub);
                    if ($detalle != null) {
                        $sql = "INSERT INTO minutas.detalle_pedido_usuario(
                               json_detalle_pedido, id_pedido_minutas_fk)
                                VALUES ('$detalle', '$validacioExitenciaPedido');";
                        $conexion->consultaSimple($sql);
                    }
                    $descripcionActividad = 'Se creo una nueva minuta con codigo: ' . $r['id_registro_minutas'];
                    $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fecha_creacion_minuta', '$descripcionActividad',  '$usuarioToken->sub');";
                    $conexion = new conexPGSeguridad();
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, Pero ya tienes una minuta con estado: SINTERMINAR'
                    ];
                }


            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/crearRegistroMinutasSimple', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $parametros = json_decode($json);
            $usuarioToken = $helper->authCheck($token, true);
            if (validarEstadoPedido($usuarioToken) == true) {
                $fecha_creacion_minuta = date('Y-m-d H:i');
                $fecha_hora_inicio_minuta = date('Y-m-d H:i');
                $estado_minuta = 'SINTERMINAR';
                $id_usuario_minutas_fk = $usuarioToken->sub;
                $id_acciones_minutas_fk = $app->request->post('idAccion', null);
                $observacion = $app->request->post('descripcion', null);
                if (trim($observacion) == '') {
                    $observacion = '-';
                }
                $horas_totales = '0';
                $sql = "INSERT INTO minutas.registro_minutas(
                             fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, estado_minuta,
                              id_usuario_minutas_fk, id_acciones_minutas_fk,descripcion_minuta)
                            VALUES ('$fecha_creacion_minuta', '$fecha_hora_inicio_minuta', '$horas_totales', '$estado_minuta', 
                            '$id_usuario_minutas_fk', '$id_acciones_minutas_fk','$observacion') returning id_registro_minutas;";
                $r = $conexion->consultaComplejaNorAso($sql);
                metodoTiempoMuertoTemporal($usuarioToken->sub);
                $descripcionActividad = 'Se creo una nueva minuta con codigo: ' . $r['id_registro_minutas'];
                $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fecha_creacion_minuta', '$descripcionActividad',  '$usuarioToken->sub');";
                $conexion = new conexPGSeguridad();
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001'
                ];
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, Pero ya tienes una minuta con estado: SINTERMINAR'
                ];
            }


        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/detenerMinuta', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $usuarioToken = $helper->authCheck($token, true);
                $id_registro_minutas = (isset($parametros->id_registro_minutas)) ? $parametros->id_registro_minutas : null;
                $sql = "select *  from minutas.registro_minutas rm where rm.id_registro_minutas = '$id_registro_minutas';";
                $r = $conexion->consultaComplejaNorAso($sql);
                if ($r != 0) {
                    $fechActual = date('Y-m-d H:i');
                    $fechaInicial = new DateTime($r['fecha_hora_inicio_minuta']);
                    $fechaFianl = new DateTime($fechActual);
                    $diff = $fechaFianl->diff($fechaInicial);
                    $jsonTiempo = json_encode($diff);
                    $horas_totales = get_format($diff);
                    $fecha_hora_fin_minuta = $fechActual;
                    $observacion_minuta = (isset($parametros->observacion_minuta)) ? $parametros->observacion_minuta : null;
                    $estado_minuta = 'DETENIDA';
                    $sql = "UPDATE minutas.registro_minutas
                            SET   fecha_hora_fin_minuta='$fecha_hora_fin_minuta', horas_totales='$horas_totales', json_tiempo='$jsonTiempo',
                            observacion_minuta='$observacion_minuta', estado_minuta='$estado_minuta'
                            WHERE id_registro_minutas='$id_registro_minutas';";
                    $conexion->consultaSimple($sql);
                    agregarTiempoMuertoTemporal($usuarioToken->sub);
                    $descripcionActividad = 'Se detuvo la minuta con codigo: ' . $id_registro_minutas;
                    $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fechActual', '$descripcionActividad',  '$usuarioToken->sub');";
                    $conexion = new conexPGSeguridad();
                    $conexion->consultaSimple($sql);


                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {

                }
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/terminarMinuta', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $usuarioToken = $helper->authCheck($token, true);
                $id_registro_minutas = (isset($parametros->id_registro_minutas)) ? $parametros->id_registro_minutas : null;
                $idPedido = (isset($parametros->id_pedido_minutas_fk)) ? $parametros->id_pedido_minutas_fk : null;
                $validacionStockUsuario = '';
                if ($idPedido != '' && $idPedido != null) {
                    $validacionStockUsuario = calcularStockUsuario($idPedido);
                }
                if ($validacionStockUsuario == '') {
                    $sql = "select *  from minutas.registro_minutas rm where rm.id_registro_minutas = '$id_registro_minutas';";
                    $r = $conexion->consultaComplejaNorAso($sql);
                    if ($r != 0) {
                        $fechActual = date('Y-m-d H:i');
                        $fechaInicial = new DateTime($r['fecha_hora_inicio_minuta']);
                        $fechaFianl = new DateTime($fechActual);
                        $diff = $fechaFianl->diff($fechaInicial);
                        $jsonTiempo = json_encode($diff);
                        $horas_totales = get_format($diff);
                        $fecha_hora_fin_minuta = $fechActual;
                        $estado_minuta = 'TERMINADOF';
                        $sql = "UPDATE minutas.registro_minutas
                            SET   fecha_hora_fin_minuta='$fecha_hora_fin_minuta', horas_totales='$horas_totales', estado_minuta='$estado_minuta', json_tiempo='$jsonTiempo'
                            WHERE id_registro_minutas='$id_registro_minutas';";
                        $conexion->consultaSimple($sql);
                        agregarTiempoMuertoTemporal($usuarioToken->sub);
                        $descripcionActividad = 'Se termino la minuta con codigo: ' . $id_registro_minutas;
                        $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fechActual', '$descripcionActividad',  '$usuarioToken->sub');";
                        $conexion->consultaSimple($sql);
                        $data = [
                            'code' => 'LTE-001'
                        ];
                    } else {

                    }
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => $validacionStockUsuario
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/continuarMinuta', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $id_registro_minutas = (isset($parametros->id_registro_minutas)) ? $parametros->id_registro_minutas : null;
                $sql = "select rm.* from minutas.registro_minutas rm where rm.id_registro_minutas = '$id_registro_minutas';";
                $r = $conexion->consultaComplejaNorAso($sql);
                if ($r != 0) {
                    $usuarioToken = $helper->authCheck($token, true);
                    if (validarEstadoPedido($usuarioToken) == true) {
                        if ($r['id_inicial_registro_minutas'] == '' || $r['id_inicial_registro_minutas'] == null) {
                            $fecha_creacion_minuta = date('Y-m-d H:i');
                            $fecha_hora_inicio_minuta = date('Y-m-d H:i');
                            $estado_minuta = 'SINTERMINAR';
                            $id_usuario_minutas_fk = $usuarioToken->sub;
                            $id_acciones_minutas_fk = $r['id_acciones_minutas_fk'];
                            $id_pedido_minutas_fk = (isset($r['id_pedido_minutas_fk'])) ? $r['id_pedido_minutas_fk'] : null;
                            $id_registro_minutas_fk = $r['id_registro_minutas'];
                            $id_inicial_registro_minutas = $r['id_registro_minutas'];
                            $horas_totales = '0';
                            $detalle = $app->request->post('detalle', null);
                            if ($id_pedido_minutas_fk == '' || $id_pedido_minutas_fk == null) {
                                $sql = "INSERT INTO minutas.registro_minutas(
                                fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, 
                                estado_minuta, id_usuario_minutas_fk, 
                                id_acciones_minutas_fk, id_registro_minutas_fk, id_inicial_registro_minutas)
                                VALUES ( '$fecha_creacion_minuta', '$fecha_hora_inicio_minuta','$horas_totales','$estado_minuta', 
                                '$usuarioToken->sub', '$id_acciones_minutas_fk','$id_registro_minutas_fk', '$id_inicial_registro_minutas');";
                            } else {
                                $sql = "INSERT INTO minutas.registro_minutas(
                                fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, 
                                estado_minuta, id_usuario_minutas_fk, 
                                id_acciones_minutas_fk, id_pedido_minutas_fk, id_registro_minutas_fk, id_inicial_registro_minutas)
                                VALUES ( '$fecha_creacion_minuta', '$fecha_hora_inicio_minuta','$horas_totales','$estado_minuta', 
                                '$usuarioToken->sub', '$id_acciones_minutas_fk', '$id_pedido_minutas_fk', '$id_registro_minutas_fk', '$id_inicial_registro_minutas');";
                            }
                            $conexion->consultaSimple($sql);
                            $estado_minuta = 'TERMINADOC';
                            $fechActual = date('Y-m-d H:i');
                            $fechaInicial = new DateTime($r['fecha_hora_inicio_minuta']);
                            $fechaFianl = new DateTime($fechActual);
                            $diff = $fechaFianl->diff($fechaInicial);
                            $jsonTiempo = json_encode($diff);
                            $horas_totales = get_format($diff);
                            $id_registro_minutas = $r['id_registro_minutas'];
                            $sql = "UPDATE minutas.registro_minutas
                        SET estado_minuta='$estado_minuta'
                        WHERE id_registro_minutas='$id_registro_minutas';";
                            $conexion->consultaSimple($sql);
                            metodoTiempoMuertoTemporal($usuarioToken->sub);
                            $descripcionActividad = 'Se continuo la minuta con codigo: ' . $id_registro_minutas_fk;
                            $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fechActual', '$descripcionActividad',  '$usuarioToken->sub');";
                            $conexion->consultaSimple($sql);

                            $data = [
                                'code' => 'LTE-001'
                            ];
                        } else {
                            $usuarioToken = $helper->authCheck($token, true);
                            $fecha_creacion_minuta = date('Y-m-d H:i');
                            $fecha_hora_inicio_minuta = date('Y-m-d H:i');
                            $estado_minuta = 'SINTERMINAR';
                            $id_usuario_minutas_fk = $usuarioToken->sub;
                            $id_acciones_minutas_fk = $r['id_acciones_minutas_fk'];
                            $id_pedido_minutas_fk = (isset($r['id_pedido_minutas_fk'])) ? $r['id_pedido_minutas_fk'] : null;
                            $id_registro_minutas_fk = $r['id_registro_minutas'];
                            $id_inicial_registro_minutas = $r['id_inicial_registro_minutas'];
                            $horas_totales = '0';
                            $detalle = $app->request->post('detalle', null);
                            if ($id_pedido_minutas_fk == '' || $id_pedido_minutas_fk == null) {
                                $sql = "INSERT INTO minutas.registro_minutas(
                                fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, 
                                estado_minuta, id_usuario_minutas_fk, 
                                id_acciones_minutas_fk, id_registro_minutas_fk, id_inicial_registro_minutas)
                                VALUES ( '$fecha_creacion_minuta', '$fecha_hora_inicio_minuta','$horas_totales','$estado_minuta', 
                                '$usuarioToken->sub', '$id_acciones_minutas_fk','$id_registro_minutas_fk', '$id_inicial_registro_minutas');";
                            } else {
                                $sql = "INSERT INTO minutas.registro_minutas(
                                fecha_creacion_minuta, fecha_hora_inicio_minuta,  horas_totales, 
                                estado_minuta, id_usuario_minutas_fk, 
                                id_acciones_minutas_fk, id_pedido_minutas_fk, id_registro_minutas_fk, id_inicial_registro_minutas)
                                VALUES ( '$fecha_creacion_minuta', '$fecha_hora_inicio_minuta','$horas_totales','$estado_minuta', 
                                '$usuarioToken->sub', '$id_acciones_minutas_fk', '$id_pedido_minutas_fk', '$id_registro_minutas_fk', '$id_inicial_registro_minutas');";
                            }

                            $conexion->consultaSimple($sql);
                            $estado_minuta = 'TERMINADOC';
                            $fechActual = date('Y-m-d H:i');
                            $fechaInicial = new DateTime($r['fecha_hora_inicio_minuta']);
                            $fechaFianl = new DateTime($fechActual);
                            $diff = $fechaFianl->diff($fechaInicial);
                            $horas_totales = get_format($diff);
                            $id_registro_minutas = $r['id_registro_minutas'];
                            $sql = "UPDATE minutas.registro_minutas
                        SET fecha_hora_fin_minuta='$fechActual', horas_totales='$horas_totales',  estado_minuta='$estado_minuta'
                        WHERE id_registro_minutas='$id_registro_minutas';";
                            $conexion->consultaSimple($sql);
                            metodoTiempoMuertoTemporal($usuarioToken->sub);
                            $descripcionActividad = 'Se continuo la minuta con codigo: ' . $id_registro_minutas;
                            $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fechActual', '$descripcionActividad',  '$usuarioToken->sub');";
                            $conexion->consultaSimple($sql);
                            $data = [
                                'code' => 'LTE-001'
                            ];
                        }
                    } else {
                        $data = [
                            'code' => 'LTE-000',
                            'status' => 'error',
                            'msg' => 'Lo sentimos, Pero ya tienes una minuta con estado: SINTERMINAR'
                        ];
                    }
                } else {
                    $data = [
                        'code' => 'LTE-003'
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/reportes/todos', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $usuarioIdentificado = $helper->authCheck($token, true);
            $fechActual = date('Y-m-d');
            $fecha_Inicial = $app->request->post('fecha_inicial', null);
            $fecha_Final = $app->request->post('fecha_final', null);
            if ($fecha_Final == '' || $fecha_Final == null) {
                $fecha_Final = $fecha_Inicial;
            }
            $conexion = new conexPGSeguridad();
            $caantidadFilas = 0;
            $usuarioToken = $helper->authCheck($token, true);
            $totalHoras = array();
            $sql = "select rgm.id_registro_minutas,fecha_creacion_minuta, rgm.id_inicial_registro_minutas,
                    rgm.fecha_hora_inicio_minuta,
                    rgm.fecha_hora_fin_minuta,
                    rgm.horas_totales,
                    rgm.estado_minuta,rgm.observacion_minuta,
                    pm.descripcion_pedido_minutas,
                    am.descripcion_acciones_minutas,
                    am.id_acciones_minutas,
                    am.seleccion_pedido_accion,
                     usu.nombre_usuario,
                       usu.apellido_usuario,
                    rgm.id_registro_minutas_fk,
                    rgm.id_pedido_minutas_fk,
                    rgm.json_tiempo,
                    usu.id_usuario,
                    usu.documento_usuario,
                    pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                    left join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                    join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                    join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                    where  to_char(rgm.fecha_hora_inicio_minuta, 'YYYY-MM-DD')  BETWEEN '$fecha_Inicial' and '$fecha_Final' and (rgm.estado_minuta like 'TERMINADOF' or rgm.estado_minuta like  
                    'SINTERMINAR' or rgm.estado_minuta like 'DETENIDA') 
                    order by usu.id_usuario, rgm.estado_minuta !='DETENIDA',rgm.estado_minuta !='SINTERMINAR',rgm.estado_minuta !='TERMINADOF'  desc";
            $arregloFinal = array();
            $r = $conexion->consultaComplejaAso($sql);

            for ($i = 0; $i < count($r); $i++) {
                $r2 = null;
                if ($r[$i]['id_inicial_registro_minutas'] != null || $r[$i]['id_inicial_registro_minutas'] != '') {
                    if ($r[$i]['seleccion_pedido_accion'] == '1' || $r[$i]['seleccion_pedido_accion'] == true) {
                        $registorInicial = $r[$i]['id_inicial_registro_minutas'];
                        $idRegistroActual = $r[$i]['id_registro_minutas'];
                        $sql = "select rgm.fecha_creacion_minuta, rgm.id_registro_minutas,
                            rgm.fecha_hora_inicio_minuta,
                            rgm.fecha_hora_fin_minuta,
                            rgm.horas_totales,
                            rgm.json_tiempo,
                            usu.id_usuario,
                            usu.documento_usuario,
                            usu.nombre_usuario,
                            usu.apellido_usuario,
                            rgm.estado_minuta,rgm.observacion_minuta,
                            pm.descripcion_pedido_minutas,
                            am.descripcion_acciones_minutas,
                            rgm.id_registro_minutas_fk,
                            pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                            join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                            join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                            join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                            where rgm.estado_minuta like 'TERMINADOC' and rgm.id_inicial_registro_minutas ='$registorInicial' or rgm.id_registro_minutas='$registorInicial' ";
                        $r2 = $conexion->consultaComplejaAso($sql);
                    } else {
                        $registorInicial = $r[$i]['id_inicial_registro_minutas'];
                        $idRegistroActual = $r[$i]['id_registro_minutas'];
                        $sql = "select rgm.fecha_creacion_minuta, rgm.id_registro_minutas,
                            rgm.fecha_hora_inicio_minuta,
                            rgm.fecha_hora_fin_minuta,
                            rgm.horas_totales,
                            rgm.json_tiempo,
                            usu.id_usuario,
                            usu.documento_usuario,
                            usu.nombre_usuario,
                            usu.apellido_usuario,
                            rgm.estado_minuta,rgm.observacion_minuta,
                            am.descripcion_acciones_minutas,
                            rgm.id_registro_minutas_fk from minutas.registro_minutas rgm 
                            join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                            join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                            where rgm.estado_minuta like 'TERMINADOC' and rgm.id_inicial_registro_minutas ='$registorInicial' or rgm.id_registro_minutas='$registorInicial' ";
                        $r2 = $conexion->consultaComplejaAso($sql);
                    }

                }
                array_push($arregloFinal, ['registro' => $r[$i],
                    'subRegistros' => $r2]);
            }
            $pdf = new TCPDF('L', 'mm', 'Letter', true, 'UTF-8', false, false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $validacionEntrada = 0;
            $arregloTiempos = array();
            for ($i = 0; $i < count($arregloFinal); $i++) {
                if ($validacionEntrada == 0) //Agregarmos la primer cabezera del reporte validamos para que solo lo pinte una ves
                {
                    $totalHorasMinutas = '';
                    $validacionEntradaTiempo = 0;
                    for ($f = 0; $f < count($arregloFinal); $f++) {

                        if ($f > 0) //validamos si la variable $i  ya itero mas de 1 ves para validar el registro anterior
                        {
                            if ($arregloFinal[$f - 1]['registro']['id_usuario'] == $arregloFinal[$f]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                            {
                                $validacionEntradaTiempo = 0;
                            }
                        }
                        if ($validacionEntradaTiempo == 0) {
                            array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['registro']['json_tiempo'], true)]);
                            if (count($arregloFinal[$f]['subRegistros']) > 0) {
                                for ($h = 0; $h < count($arregloFinal[$f]['subRegistros']); $h++) {
                                    array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['subRegistros'][$h]['json_tiempo'], true)]);
                                }
                            }
                            $totalHorasMinutas = calcularTotalTiempo($arregloTiempos);
                            $validacionEntradaTiempo = 1;
                        }
                    }
                    $arregloTiempos = array();
                    $pdf->AddPage();
                    $pdf->Cell(40, 45, '', 1, 0);
                    $pdf->Image(__DIR__ . '../../../public/imagenes_estandar/Logo Los Tres Editores-04.png', 10, 10, 40, 45,
                        'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    $pdf->Cell(190, 10, 'Resporte de minutas diario', 1, 0, 'C');
                    $fechActual = date('Y-m-d H:i');
                    $pdf->Cell(40, 10, $fechActual, 1, 0);
                    $pdf->SetXY(50, 20);
                    $pdf->Cell(75, 10, 'Documento: ' . $arregloFinal[$i]['registro']['documento_usuario'], 1, 0, 'L');
                    $pdf->SetXY($pdf->GetX(), 20);
                    $pdf->Cell(155, 10, 'Nombre ' . $arregloFinal[$i]['registro']['nombre_usuario'] . ' ' . $arregloFinal[$i]['registro']['apellido_usuario'], 1, 0, 'L');
                    $pdf->SetXY(50, 30);
                    $pdf->Cell(75, 10, 'Rango: ' . $fecha_Inicial . ' al ' . $fecha_Final, 1, 0, 'L');
                    $pdf->SetXY($pdf->GetX(), 30);
                    $pdf->Cell(155, 10, 'Tiempo total minuta: ' . $totalHorasMinutas, 1, 0, 'L');
                    $route = __DIR__ . '../../../public/pdf/';
                    $pdf->SetFontSize(8);
                    $pdf->SetFillColor(255, 128, 0);
                    $pdf->SetXY(10, 60);
                    $pdf->Cell(15, 5, 'Codigo', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(25, 5, 'Codigo pedido', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                    $pdf->SetXY(10, 65);
                    $caantidadFilas++;
                    $maxFilas = 18;
                    $validacionEntrada = 1;
                }
                if ($caantidadFilas >= $maxFilas) { //Verificamos la cantidad de filas para hacer el salto de pagina.
                    $pdf->AddPage();
                    $pdf->SetXY(10, 10);
                    $caantidadFilas = 1;
                    $maxFilas = 26;
                }
                $validacionEntradaTiempoMuerto = 0;
                if ($i > 0) //validamos si la variable $i  ya itero mas de 1 ves para validar el registro anterior
                {
                    if ($arregloFinal[$i - 1]['registro']['id_usuario'] != $arregloFinal[$i]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                    {
                        $validacionEntradaTiempoMuerto = 1;
                        //
                        $idusuario = $arregloFinal[$i - 1]['registro']['id_usuario'];
                        $sql = "select * from minutas.tiempo_muerto tm where  tm.id_usuario_tiempo_muerto_fk = '$idusuario' 
                                and to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  
                                BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r = $conexion->consultaComplejaAso($sql);
                        $arregloTiempoMuerto = array();
                        for ($m = 0; $m < count($r); $m++) {
                            array_push($arregloTiempoMuerto, json_decode($r[$m]['json_tiempo_muerto'], true));
                        }
                        $arregloFinalTiempoMuerto = array();
                        $arregloCalcularTiempoMuerto = array();
                        for ($n = 0; $n < count($arregloTiempoMuerto); $n++) {
                            for ($b = 0; $b < count($arregloTiempoMuerto[$n]); $b++) {
                                array_push($arregloFinalTiempoMuerto, $arregloTiempoMuerto[$n][$b]);
                            }

                        }

                        if ($r != 0) {
                            $pdf->SetFillColor(255, 128, 0);
                            $pdf->AddPage();
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->SetFontSize(25);
                            $pdf->Cell(270, 10, 'Tiempo muerto', 0, '', 'C');
                            $pdf->SetFontSize(8);
                            $pdf->SetY($pdf->GetY() + 15);
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora inicial', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora final', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(60, 5, 'Total', 1, 0, '', 1);
                            $pdf->SetXY(10, $pdf->GetY() + 5);
                            $pdf->SetFillColor(255, 255, 255);
                            for ($n = 0; $n < count($arregloFinalTiempoMuerto); $n++) {
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_inicial']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_final']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(60, 5, calcularTotalTiempoUnitario($arregloFinalTiempoMuerto[$n]['tiempo']), 1, 0, '', 1);
                                $pdf->SetXY(10, $pdf->GetY() + 5);
                            }
                            $pdf->SetX(90);
                            $pdf->SetFillColor(237, 118, 105);
                            $pdf->Cell(60, 5, calcularTotalTiempo($arregloFinalTiempoMuerto), 1, 0, '', 1);
                            $validacionEntradaTiempoMuerto = 0;
                        }

                        //agregamos una nueva cabezera inicial, por que es un usuario nuevo.
                        $totalHorasMinutas = '';
                        $validacionEntradaTiempo = 0;
                        $entro = 0;
                        $arregloTiempos = array();
                        for ($f = $i; $f < count($arregloFinal); $f++) {

                            if ($entro > 0) {
                                if ($arregloFinal[$f - 1]['registro']['id_usuario'] == $arregloFinal[$f]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                                {
                                    $validacionEntradaTiempo = 0;
                                }
                            }
                            if ($validacionEntradaTiempo == 0) {
                                array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['registro']['json_tiempo'], true)]);
                                if (count($arregloFinal[$f]['subRegistros']) > 0) {
                                    for ($h = 0; $h < count($arregloFinal[$f]['subRegistros']); $h++) {
                                        array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['subRegistros'][$h]['json_tiempo'], true)]);
                                    }
                                }

                                $totalHorasMinutas = calcularTotalTiempo($arregloTiempos);
                                $validacionEntradaTiempo = 1;
                            }
                            $entro++;
                            $validacionEntradaTiempo = 1;
                        }
                        $arregloTiempos = array();

                        $pdf->AddPage();
                        $pdf->Cell(40, 45, '', 1, 0);
                        $pdf->Image(__DIR__ . '../../../public/imagenes_estandar/Logo Los Tres Editores-04.png', 10, 10, 40, 45,
                            'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
                        $pdf->Cell(190, 10, 'Resporte de minutas diario', 1, 0, 'C');
                        $fechActual = date('Y-m-d H:i');
                        $pdf->Cell(40, 10, $fechActual, 1, 0);
                        $pdf->SetXY(50, 20);
                        $pdf->Cell(75, 10, 'Documento: ' . $arregloFinal[$i]['registro']['documento_usuario'], 1, 0, 'L');
                        $pdf->SetXY($pdf->GetX(), 20);
                        $pdf->Cell(155, 10, 'Nombre ' . $arregloFinal[$i]['registro']['nombre_usuario'] . ' ' . $arregloFinal[$i]['registro']['apellido_usuario'], 1, 0, 'L');
                        $pdf->SetXY(50, 30);
                        $pdf->Cell(75, 10, 'Rango: ' . $fecha_Inicial . ' al ' . $fecha_Final, 1, 0, 'L');
                        $pdf->SetXY($pdf->GetX(), 30);
                        $pdf->Cell(155, 10, 'Tiempo total minuta: ' . $totalHorasMinutas, 1, 0, 'L');
                        $route = __DIR__ . '../../../public/pdf/';
                        $pdf->SetFontSize(8);
                        $pdf->SetFillColor(255, 128, 0);
                        $pdf->SetXY(10, 60);
                        $pdf->Cell(15, 5, 'Codigo', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(25, 5, 'Codigo pedido', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                        $pdf->SetXY(10, 65);
                        $caantidadFilas = 1;
                        $maxFilas = 18;
                    }
                }


                if ($arregloFinal[$i]['registro']['estado_minuta'] == 'SINTERMINAR') //pintamos de color la celda de estado, segun su estado.
                {
                    $pdf->SetFillColor(237, 118, 105);
                } else if ($arregloFinal[$i]['registro']['estado_minuta'] == 'DETENIDA') {
                    $pdf->SetFillColor(244, 208, 63);
                } else if ($arregloFinal[$i]['registro']['estado_minuta'] == 'TERMINADOF') {
                    $pdf->SetFillColor(144, 230, 181);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(15, 5, $arregloFinal[$i]['registro']['id_registro_minutas'], 1, 0, 'C');
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(25, 5, $arregloFinal[$i]['registro']['codigo_pedido_minutas'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(32, 5, $arregloFinal[$i]['registro']['fecha_hora_inicio_minuta'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(32, 5, $arregloFinal[$i]['registro']['fecha_hora_fin_minuta'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(30, 5, $arregloFinal[$i]['registro']['estado_minuta'], 1, 0, '', 1);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(22, 5, $arregloFinal[$i]['registro']['descripcion_acciones_minutas'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(40, 5, $arregloFinal[$i]['registro']['horas_totales'], 1, 0);
                if (count($arregloFinal[$i]['subRegistros']) > 0) {

                    $pdf->SetFillColor(252, 184, 132);
                    $pdf->SetXY(25, $pdf->GetY() + 5);
                    $pdf->Cell(25, 5, 'Codigo', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(75, 5, 'No Conformidad', 1, 0, '', 1);
                    $pdf->SetXY(25, $pdf->GetY() + 5);
                    $caantidadFilas++;
                    for ($j = 0; $j < count($arregloFinal[$i]['subRegistros']); $j++) {
                        if ($caantidadFilas >= $maxFilas) {
                            $pdf->AddPage();
                            $pdf->SetXY(10, 10);
                            $caantidadFilas = 1;
                            $maxFilas = 26;
                        }
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(25, 5, $arregloFinal[$i]['subRegistros'][$j]['id_registro_minutas'], 1, 0, 'C');
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(32, 5, $arregloFinal[$i]['subRegistros'][$j]['fecha_hora_inicio_minuta'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(32, 5, $arregloFinal[$i]['subRegistros'][$j]['fecha_hora_fin_minuta'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'SINTERMINAR') {
                            $pdf->SetFillColor(237, 118, 105);
                        } else if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'DETENIDA') {
                            $pdf->SetFillColor(244, 208, 63);
                        } else if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'TERMINADOF') {
                            $pdf->SetFillColor(144, 230, 181);
                        } else {
                            $pdf->SetFillColor(255, 255, 255);
                        }
                        $pdf->Cell(30, 5, $arregloFinal[$i]['subRegistros'][$j]['estado_minuta'], 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(22, 5, $arregloFinal[$i]['subRegistros'][$j]['descripcion_acciones_minutas'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(40, 5, $arregloFinal[$i]['subRegistros'][$j]['horas_totales'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        if (strlen($arregloFinal[$i]['subRegistros'][$j]['observacion_minuta']) > 46) {
                            $puntos = ' ...';
                        } else {
                            $puntos = '';
                        }
                        $pdf->Cell(75, 5, trim(substr($arregloFinal[$i]['subRegistros'][$j]['observacion_minuta'], 0, 45)) . $puntos, 1, 0);
                        $pdf->SetXY(25, $pdf->GetY() + 5);
                        $caantidadFilas++;
                    }
                }// pintando tabla minutas
                $arregloTiempos = array();
                $pdf->SetXY(10, $pdf->GetY() + 5);
                $caantidadFilas++;

                if ($validacionEntradaTiempoMuerto != 1) {
                    if ($i == count($arregloFinal) - 1) {
                        $idusuario = $arregloFinal[$i]['registro']['id_usuario'];
                        $sql = "select * from minutas.tiempo_muerto tm where  tm.id_usuario_tiempo_muerto_fk = '$idusuario' 
                                and to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  
                                BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r = $conexion->consultaComplejaAso($sql);
                        $arregloTiempoMuerto = array();
                        for ($m = 0; $m < count($r); $m++) {
                            array_push($arregloTiempoMuerto, json_decode($r[$m]['json_tiempo_muerto'], true));
                        }
                        $arregloFinalTiempoMuerto = array();
                        $arregloCalcularTiempoMuerto = array();
                        for ($n = 0; $n < count($arregloTiempoMuerto); $n++) {
                            for ($b = 0; $b < count($arregloTiempoMuerto[$n]); $b++) {
                                array_push($arregloFinalTiempoMuerto, $arregloTiempoMuerto[$n][$b]);
                            }

                        }

                        if ($r != 0) {
                            $pdf->SetFillColor(255, 128, 0);
                            $pdf->AddPage();
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->SetFontSize(25);
                            $pdf->Cell(270, 10, 'Tiempo muerto', 0, '', 'C');
                            $pdf->SetFontSize(8);
                            $pdf->SetY($pdf->GetY() + 15);
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora inicial', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora final', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(60, 5, 'Total', 1, 0, '', 1);
                            $pdf->SetXY(10, $pdf->GetY() + 5);
                            $pdf->SetFillColor(255, 255, 255);
                            for ($n = 0; $n < count($arregloFinalTiempoMuerto); $n++) {
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_inicial']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_final']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(60, 5, calcularTotalTiempoUnitario($arregloFinalTiempoMuerto[$n]['tiempo']), 1, 0, '', 1);
                                $pdf->SetXY(10, $pdf->GetY() + 5);
                            }
                            $pdf->SetX(90);
                            $pdf->SetFillColor(237, 118, 105);
                            $pdf->Cell(60, 5, calcularTotalTiempo($arregloFinalTiempoMuerto), 1, 0, '', 1);
                            $validacionEntradaTiempoMuerto = 0;
                        }
                    }
                } //Tiempo Muerto
                //echo $caantidadFilas.'-';
            }
            //die();
            if (!file_exists($route))
                mkdir($route, 0, true);
            $pdf->Output($route . "reporteMinutas.pdf", "F");
            $data = [
                'code' => 'LTE-001',
                'data' => '/pdf/reporteMinutas.pdf'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);

});
$app->post('/minutas/reportes/minutaXusuario', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $usuarioIdentificado = $helper->authCheck($token, true);
            $fechActual = date('Y-m-d');
            $fecha_Inicial = $app->request->post('fecha_inicial', null);
            $fecha_Final = $app->request->post('fecha_final', null);
            if ($fecha_Final == '' || $fecha_Final == null) {
                $fecha_Final = $fecha_Inicial;
            }
            $conexion = new conexPGSeguridad();
            $caantidadFilas = 0;
            $id_usuario = $app->request->post('id_usuario', null);
            $usuarioToken = $helper->authCheck($token, true);
            $totalHoras = array();
            $sql = "select rgm.id_registro_minutas,fecha_creacion_minuta, rgm.id_inicial_registro_minutas,
                    rgm.fecha_hora_inicio_minuta,
                    rgm.fecha_hora_fin_minuta,
                    rgm.horas_totales,
                    rgm.estado_minuta,rgm.observacion_minuta,
                    pm.descripcion_pedido_minutas,
                    am.descripcion_acciones_minutas,
                    am.id_acciones_minutas,
                    am.seleccion_pedido_accion,
                     usu.nombre_usuario,
                       usu.apellido_usuario,
                    rgm.id_registro_minutas_fk,
                    rgm.id_pedido_minutas_fk,
                    rgm.json_tiempo,
                    usu.id_usuario,
                    usu.documento_usuario,
                    pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                    left join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                    join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                    join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                    where  to_char(rgm.fecha_hora_inicio_minuta, 'YYYY-MM-DD')  BETWEEN '$fecha_Inicial' and '$fecha_Final' and usu.id_usuario='$id_usuario' 
                    and (rgm.estado_minuta like 'TERMINADOF' or rgm.estado_minuta like  
                    'SINTERMINAR' or rgm.estado_minuta like 'DETENIDA') 
                    order by usu.id_usuario, rgm.estado_minuta !='DETENIDA',rgm.estado_minuta !='SINTERMINAR',rgm.estado_minuta !='TERMINADOF'  desc";
            $arregloFinal = array();
            $r = $conexion->consultaComplejaAso($sql);

            for ($i = 0; $i < count($r); $i++) {
                $r2 = null;
                if ($r[$i]['id_inicial_registro_minutas'] != null || $r[$i]['id_inicial_registro_minutas'] != '') {
                    if ($r[$i]['seleccion_pedido_accion'] == '1' || $r[$i]['seleccion_pedido_accion'] == true) {
                        $registorInicial = $r[$i]['id_inicial_registro_minutas'];
                        $idRegistroActual = $r[$i]['id_registro_minutas'];
                        $sql = "select rgm.fecha_creacion_minuta, rgm.id_registro_minutas,
                            rgm.fecha_hora_inicio_minuta,
                            rgm.fecha_hora_fin_minuta,
                            rgm.horas_totales,
                            rgm.json_tiempo,
                            usu.id_usuario,
                            usu.documento_usuario,
                            usu.nombre_usuario,
                            usu.apellido_usuario,
                            rgm.estado_minuta,rgm.observacion_minuta,
                            pm.descripcion_pedido_minutas,
                            am.descripcion_acciones_minutas,
                            rgm.id_registro_minutas_fk,
                            pm.codigo_pedido_minutas from minutas.registro_minutas rgm 
                            join minutas.pedidos_minutas pm on rgm.id_pedido_minutas_fk=pm.id_pedido_minutas
                            join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                            join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                            where rgm.estado_minuta like 'TERMINADOC' and rgm.id_inicial_registro_minutas ='$registorInicial' or rgm.id_registro_minutas='$registorInicial' and 
                            to_char(rgm.fecha_hora_inicio_minuta, 'YYYY-MM-DD')  BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r2 = $conexion->consultaComplejaAso($sql);
                    } else {
                        $registorInicial = $r[$i]['id_inicial_registro_minutas'];
                        $idRegistroActual = $r[$i]['id_registro_minutas'];
                        $sql = "select rgm.fecha_creacion_minuta, rgm.id_registro_minutas,
                            rgm.fecha_hora_inicio_minuta,
                            rgm.fecha_hora_fin_minuta,
                            rgm.horas_totales,
                            rgm.json_tiempo,
                            usu.id_usuario,
                            usu.documento_usuario,
                            usu.nombre_usuario,
                            usu.apellido_usuario,
                            rgm.estado_minuta,rgm.observacion_minuta,
                            am.descripcion_acciones_minutas,
                            rgm.id_registro_minutas_fk from minutas.registro_minutas rgm 
                            join minutas.acciones_minutas am on am.id_acciones_minutas=rgm.id_acciones_minutas_fk
                            join seguridad.usuario usu on usu.id_usuario=rgm.id_usuario_minutas_fk
                            where rgm.estado_minuta like 'TERMINADOC' and rgm.id_inicial_registro_minutas ='$registorInicial' or rgm.id_registro_minutas='$registorInicial' and 
                            to_char(rgm.fecha_hora_inicio_minuta, 'YYYY-MM-DD')  BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r2 = $conexion->consultaComplejaAso($sql);
                    }

                }
                array_push($arregloFinal, ['registro' => $r[$i],
                    'subRegistros' => $r2]);
            }
            $pdf = new TCPDF('L', 'mm', 'Letter', true, 'UTF-8', false, false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $validacionEntrada = 0;
            $arregloTiempos = array();
            for ($i = 0; $i < count($arregloFinal); $i++) {
                if ($validacionEntrada == 0) //Agregarmos la primer cabezera del reporte validamos para que solo lo pinte una ves
                {
                    $totalHorasMinutas = '';
                    $validacionEntradaTiempo = 0;
                    for ($f = 0; $f < count($arregloFinal); $f++) {

                        if ($f > 0) //validamos si la variable $i  ya itero mas de 1 ves para validar el registro anterior
                        {
                            if ($arregloFinal[$f - 1]['registro']['id_usuario'] == $arregloFinal[$f]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                            {
                                $validacionEntradaTiempo = 0;
                            }
                        }
                        if ($validacionEntradaTiempo == 0) {
                            array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['registro']['json_tiempo'], true)]);
                            if (count($arregloFinal[$f]['subRegistros']) > 0) {
                                for ($h = 0; $h < count($arregloFinal[$f]['subRegistros']); $h++) {
                                    array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['subRegistros'][$h]['json_tiempo'], true)]);
                                }
                            }
                            $totalHorasMinutas = calcularTotalTiempo($arregloTiempos);
                            $validacionEntradaTiempo = 1;
                        }
                    }
                    $arregloTiempos = array();
                    $pdf->AddPage();
                    $pdf->Cell(40, 45, '', 1, 0);
                    $pdf->Image(__DIR__ . '../../../public/imagenes_estandar/Logo Los Tres Editores-04.png', 10, 10, 40, 45,
                        'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    $pdf->Cell(190, 10, 'Resporte de minutas diario', 1, 0, 'C');
                    $fechActual = date('Y-m-d H:i');
                    $pdf->Cell(40, 10, $fechActual, 1, 0);
                    $pdf->SetXY(50, 20);
                    $pdf->Cell(75, 10, 'Documento: ' . $arregloFinal[$i]['registro']['documento_usuario'], 1, 0, 'L');
                    $pdf->SetXY($pdf->GetX(), 20);
                    $pdf->Cell(155, 10, 'Nombre ' . $arregloFinal[$i]['registro']['nombre_usuario'] . ' ' . $arregloFinal[$i]['registro']['apellido_usuario'], 1, 0, 'L');
                    $pdf->SetXY(50, 30);
                    $pdf->Cell(75, 10, 'Rango: ' . $fecha_Inicial . ' al ' . $fecha_Final, 1, 0, 'L');
                    $pdf->SetXY($pdf->GetX(), 30);
                    $pdf->Cell(155, 10, 'Tiempo total minuta: ' . $totalHorasMinutas, 1, 0, 'L');
                    $route = __DIR__ . '../../../public/pdf/';
                    $pdf->SetFontSize(8);
                    $pdf->SetFillColor(255, 128, 0);
                    $pdf->SetXY(10, 60);
                    $pdf->Cell(15, 5, 'Codigo', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(25, 5, 'Codigo pedido', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, 60);
                    $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                    $pdf->SetXY(10, 65);
                    $caantidadFilas++;
                    $maxFilas = 18;
                    $validacionEntrada = 1;
                }
                if ($caantidadFilas >= $maxFilas) { //Verificamos la cantidad de filas para hacer el salto de pagina.
                    $pdf->AddPage();
                    $pdf->SetXY(10, 10);
                    $caantidadFilas = 1;
                    $maxFilas = 26;
                }
                $validacionEntradaTiempoMuerto = 0;
                if ($i > 0) //validamos si la variable $i  ya itero mas de 1 ves para validar el registro anterior
                {
                    if ($arregloFinal[$i - 1]['registro']['id_usuario'] != $arregloFinal[$i]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                    {
                        $validacionEntradaTiempoMuerto = 1;
                        //
                        $idusuario = $arregloFinal[$i - 1]['registro']['id_usuario'];
                        $sql = "select * from minutas.tiempo_muerto tm where  tm.id_usuario_tiempo_muerto_fk = '$idusuario' 
                                and to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  
                                BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r = $conexion->consultaComplejaAso($sql);
                        $arregloTiempoMuerto = array();
                        for ($m = 0; $m < count($r); $m++) {
                            array_push($arregloTiempoMuerto, json_decode($r[$m]['json_tiempo_muerto'], true));
                        }
                        $arregloFinalTiempoMuerto = array();
                        $arregloCalcularTiempoMuerto = array();
                        for ($n = 0; $n < count($arregloTiempoMuerto); $n++) {
                            for ($b = 0; $b < count($arregloTiempoMuerto[$n]); $b++) {
                                array_push($arregloFinalTiempoMuerto, $arregloTiempoMuerto[$n][$b]);
                            }

                        }

                        if ($r != 0) {
                            $pdf->SetFillColor(255, 128, 0);
                            $pdf->AddPage();
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->SetFontSize(25);
                            $pdf->Cell(270, 10, 'Tiempo muerto', 0, '', 'C');
                            $pdf->SetFontSize(8);
                            $pdf->SetY($pdf->GetY() + 15);
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora inicial', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora final', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(60, 5, 'Total', 1, 0, '', 1);
                            $pdf->SetXY(10, $pdf->GetY() + 5);
                            $pdf->SetFillColor(255, 255, 255);
                            for ($n = 0; $n < count($arregloFinalTiempoMuerto); $n++) {
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_inicial']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_final']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(60, 5, calcularTotalTiempoUnitario($arregloFinalTiempoMuerto[$n]['tiempo']), 1, 0, '', 1);
                                $pdf->SetXY(10, $pdf->GetY() + 5);
                            }
                            $pdf->SetX(90);
                            $pdf->SetFillColor(237, 118, 105);
                            $pdf->Cell(60, 5, calcularTotalTiempo($arregloFinalTiempoMuerto), 1, 0, '', 1);
                            $validacionEntradaTiempoMuerto = 0;
                        }

                        //agregamos una nueva cabezera inicial, por que es un usuario nuevo.
                        $totalHorasMinutas = '';
                        $validacionEntradaTiempo = 0;
                        $entro = 0;
                        $arregloTiempos = array();
                        for ($f = $i; $f < count($arregloFinal); $f++) {

                            if ($entro > 0) {
                                if ($arregloFinal[$f - 1]['registro']['id_usuario'] == $arregloFinal[$f]['registro']['id_usuario']) //validamos si el siguiente registro es diferente al usuario anterior
                                {
                                    $validacionEntradaTiempo = 0;
                                }
                            }
                            if ($validacionEntradaTiempo == 0) {
                                array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['registro']['json_tiempo'], true)]);
                                if (count($arregloFinal[$f]['subRegistros']) > 0) {
                                    for ($h = 0; $h < count($arregloFinal[$f]['subRegistros']); $h++) {
                                        array_push($arregloTiempos, ['tiempo' => json_decode($arregloFinal[$f]['subRegistros'][$h]['json_tiempo'], true)]);
                                    }
                                }

                                $totalHorasMinutas = calcularTotalTiempo($arregloTiempos);
                                $validacionEntradaTiempo = 1;
                            }
                            $entro++;
                            $validacionEntradaTiempo = 1;
                        }
                        $arregloTiempos = array();

                        $pdf->AddPage();
                        $pdf->Cell(40, 45, '', 1, 0);
                        $pdf->Image(__DIR__ . '../../../public/imagenes_estandar/Logo Los Tres Editores-04.png', 10, 10, 40, 45,
                            'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
                        $pdf->Cell(190, 10, 'Resporte de minutas diario', 1, 0, 'C');
                        $fechActual = date('Y-m-d H:i');
                        $pdf->Cell(40, 10, $fechActual, 1, 0);
                        $pdf->SetXY(50, 20);
                        $pdf->Cell(75, 10, 'Documento: ' . $arregloFinal[$i]['registro']['documento_usuario'], 1, 0, 'L');
                        $pdf->SetXY($pdf->GetX(), 20);
                        $pdf->Cell(155, 10, 'Nombre ' . $arregloFinal[$i]['registro']['nombre_usuario'] . ' ' . $arregloFinal[$i]['registro']['apellido_usuario'], 1, 0, 'L');
                        $pdf->SetXY(50, 30);
                        $pdf->Cell(75, 10, 'Tiempo total minuta: ' . $totalHorasMinutas, 1, 0, 'L');
                        $pdf->SetXY(50, 30);
                        $pdf->Cell(75, 10, 'Rango: ' . $fecha_Inicial . ' al ' . $fecha_Final, 1, 0, 'L');
                        $pdf->SetXY($pdf->GetX(), 30);
                        $pdf->Cell(155, 10, 'Tiempo total minuta: ' . $totalHorasMinutas, 1, 0, 'L');

                        $route = __DIR__ . '../../../public/pdf/';
                        $pdf->SetFontSize(8);
                        $pdf->SetFillColor(255, 128, 0);
                        $pdf->SetXY(10, 60);
                        $pdf->Cell(15, 5, 'Codigo', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(25, 5, 'Codigo pedido', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, 60);
                        $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                        $pdf->SetXY(10, 65);
                        $caantidadFilas = 1;
                        $maxFilas = 18;
                    }
                }


                if ($arregloFinal[$i]['registro']['estado_minuta'] == 'SINTERMINAR') //pintamos de color la celda de estado, segun su estado.
                {
                    $pdf->SetFillColor(237, 118, 105);
                } else if ($arregloFinal[$i]['registro']['estado_minuta'] == 'DETENIDA') {
                    $pdf->SetFillColor(244, 208, 63);
                } else if ($arregloFinal[$i]['registro']['estado_minuta'] == 'TERMINADOF') {
                    $pdf->SetFillColor(144, 230, 181);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(15, 5, $arregloFinal[$i]['registro']['id_registro_minutas'], 1, 0, 'C');
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(25, 5, $arregloFinal[$i]['registro']['codigo_pedido_minutas'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(32, 5, $arregloFinal[$i]['registro']['fecha_hora_inicio_minuta'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(32, 5, $arregloFinal[$i]['registro']['fecha_hora_fin_minuta'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(30, 5, $arregloFinal[$i]['registro']['estado_minuta'], 1, 0, '', 1);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(22, 5, $arregloFinal[$i]['registro']['descripcion_acciones_minutas'], 1, 0);
                $x = $pdf->GetX();
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell(40, 5, $arregloFinal[$i]['registro']['horas_totales'], 1, 0);
                if (count($arregloFinal[$i]['subRegistros']) > 0) {

                    $pdf->SetFillColor(252, 184, 132);
                    $pdf->SetXY(25, $pdf->GetY() + 5);
                    $pdf->Cell(25, 5, 'Codigo', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(32, 5, 'Hora de inicio', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(32, 5, 'Hora de finalizacion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(30, 5, 'Estado', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(22, 5, 'Accion', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(40, 5, 'Total', 1, 0, '', 1);
                    $x = $pdf->GetX();
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell(75, 5, 'No Conformidad', 1, 0, '', 1);
                    $pdf->SetXY(25, $pdf->GetY() + 5);
                    $caantidadFilas++;
                    for ($j = 0; $j < count($arregloFinal[$i]['subRegistros']); $j++) {
                        if ($caantidadFilas >= $maxFilas) {
                            $pdf->AddPage();
                            $pdf->SetXY(10, 10);
                            $caantidadFilas = 1;
                            $maxFilas = 26;
                        }
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(25, 5, $arregloFinal[$i]['subRegistros'][$j]['id_registro_minutas'], 1, 0, 'C');
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(32, 5, $arregloFinal[$i]['subRegistros'][$j]['fecha_hora_inicio_minuta'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(32, 5, $arregloFinal[$i]['subRegistros'][$j]['fecha_hora_fin_minuta'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'SINTERMINAR') {
                            $pdf->SetFillColor(237, 118, 105);
                        } else if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'DETENIDA') {
                            $pdf->SetFillColor(244, 208, 63);
                        } else if ($arregloFinal[$i]['subRegistros'][$j]['estado_minuta'] == 'TERMINADOF') {
                            $pdf->SetFillColor(144, 230, 181);
                        } else {
                            $pdf->SetFillColor(255, 255, 255);
                        }
                        $pdf->Cell(30, 5, $arregloFinal[$i]['subRegistros'][$j]['estado_minuta'], 1, 0, '', 1);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(22, 5, $arregloFinal[$i]['subRegistros'][$j]['descripcion_acciones_minutas'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        $pdf->Cell(40, 5, $arregloFinal[$i]['subRegistros'][$j]['horas_totales'], 1, 0);
                        $x = $pdf->GetX();
                        $pdf->SetXY($x, $pdf->GetY());
                        if (strlen($arregloFinal[$i]['subRegistros'][$j]['observacion_minuta']) > 46) {
                            $puntos = ' ...';
                        } else {
                            $puntos = '';
                        }
                        $pdf->Cell(75, 5, trim(substr($arregloFinal[$i]['subRegistros'][$j]['observacion_minuta'], 0, 45)) . $puntos, 1, 0);
                        $pdf->SetXY(25, $pdf->GetY() + 5);
                        $caantidadFilas++;
                    }
                }// pintando tabla minutas
                $arregloTiempos = array();
                $pdf->SetXY(10, $pdf->GetY() + 5);
                $caantidadFilas++;

                if ($validacionEntradaTiempoMuerto != 1) {
                    if ($i == count($arregloFinal) - 1) {
                        $idusuario = $arregloFinal[$i]['registro']['id_usuario'];
                        $sql = "select * from minutas.tiempo_muerto tm where  tm.id_usuario_tiempo_muerto_fk = '$idusuario' 
                                and to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  
                                BETWEEN '$fecha_Inicial' and '$fecha_Final'";
                        $r = $conexion->consultaComplejaAso($sql);
                        $arregloTiempoMuerto = array();
                        for ($m = 0; $m < count($r); $m++) {
                            array_push($arregloTiempoMuerto, json_decode($r[$m]['json_tiempo_muerto'], true));
                        }
                        $arregloFinalTiempoMuerto = array();
                        $arregloCalcularTiempoMuerto = array();
                        for ($n = 0; $n < count($arregloTiempoMuerto); $n++) {
                            for ($b = 0; $b < count($arregloTiempoMuerto[$n]); $b++) {
                                array_push($arregloFinalTiempoMuerto, $arregloTiempoMuerto[$n][$b]);
                            }

                        }

                        if ($r != 0) {
                            $pdf->SetFillColor(255, 128, 0);
                            $pdf->AddPage();
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->SetFontSize(25);
                            $pdf->Cell(270, 10, 'Tiempo muerto', 0, '', 'C');
                            $pdf->SetFontSize(8);
                            $pdf->SetY($pdf->GetY() + 15);
                            $pdf->SetXY(10, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora inicial', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(40, 5, 'Hora final', 1, 0, '', 1);
                            $x = $pdf->GetX();
                            $pdf->SetXY($x, $pdf->GetY());
                            $pdf->Cell(60, 5, 'Total', 1, 0, '', 1);
                            $pdf->SetXY(10, $pdf->GetY() + 5);
                            $pdf->SetFillColor(255, 255, 255);
                            for ($n = 0; $n < count($arregloFinalTiempoMuerto); $n++) {
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_inicial']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(40, 5, $arregloFinalTiempoMuerto[$n]['fecha_final']['date'], 1, 0, '', 1);
                                $pdf->SetXY($pdf->GetX(), $pdf->GetY());
                                $pdf->Cell(60, 5, calcularTotalTiempoUnitario($arregloFinalTiempoMuerto[$n]['tiempo']), 1, 0, '', 1);
                                $pdf->SetXY(10, $pdf->GetY() + 5);
                            }
                            $pdf->SetX(90);
                            $pdf->SetFillColor(237, 118, 105);
                            $pdf->Cell(60, 5, calcularTotalTiempo($arregloFinalTiempoMuerto), 1, 0, '', 1);
                            $validacionEntradaTiempoMuerto = 0;
                        }
                    }
                } //Tiempo Muerto
                //echo $caantidadFilas.'-';
            }
            //die();
            if (!file_exists($route))
                mkdir($route, 0, true);
            $pdf->Output($route . "reporteMinutas.pdf", "F");
            $data = [
                'code' => 'LTE-001',
                'data' => '/pdf/reporteMinutas.pdf'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);

});
$app->post('/noConformes/listarCategoria', function () use ($app) {
    $helper = new helper();
    $conexion = new conexMsql();
    $sql = "select * from category_nc";
    $r = $conexion->consultaComplejaAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});
$app->post('/noConformes/listarNoConformes', function () use ($app) {
    $helper = new helper();
    $conexion = new conexMsql();
    $id_categoria = $app->request->post('id', null);
    $sql = "select noc.* from no_conformes noc where noc.id_category_nc = '$id_categoria'";
    $r = $conexion->consultaComplejaAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});
$app->post('/minutas/calcularTiempoMuerto', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $conexion = new conexPGSeguridad();
            $usuarioToken = $helper->authCheck($token, true);
            $jsonTiempoMuerto = $app->request->post('json');
            $objTiempoMuerto = json_decode($jsonTiempoMuerto, true);
            $fechActualF = date('Y-m-d H:i:s');
            $tiempoMuerto = $objTiempoMuerto['fecha'];
            $sql = "select tm.* from minutas.tiempo_muerto tm where to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  = '$tiempoMuerto' 
                        and tm.id_usuario_tiempo_muerto_fk='$usuarioToken->sub'";
            $r = $conexion->consultaComplejaNorAso($sql);
            if ($r != 0) {
                $fechaTiempoMuerto = new DateTime($objTiempoMuerto['tiempo']);
                $fechaActual = new DateTime($fechActualF);
                $diff = $fechaTiempoMuerto->diff($fechaActual);
                $totalHoras = get_format($diff);
                $jsonArreglo = json_decode($r['json_tiempo_muerto'], true);
                $arregloTiempos = $jsonArreglo;
                array_push($arregloTiempos, ['tiempo' => $diff, 'fecha_inicial' => $fechaTiempoMuerto, 'fecha_final' => $fechaActual]);
                $jsonTiempo = json_encode($arregloTiempos);
                $timepoTotal = calcularTotalTiempo(json_decode($jsonTiempo, true));
                $id_tiemp_muerto = $r['id_tiempo_muerto'];
                $sql = "UPDATE minutas.tiempo_muerto
            SET  json_tiempo_muerto='$jsonTiempo', descripcion_tiempo_muerto='$timepoTotal'
            WHERE id_tiempo_muerto='$id_tiemp_muerto';";
                $conexion->consultaSimple($sql);
            } else {
                $fechaTiempoMuerto = new DateTime($objTiempoMuerto['tiempo']);
                $fechaActual = new DateTime($fechActualF);
                $diff = $fechaTiempoMuerto->diff($fechaActual);
                $totalHoras = get_format($diff);
                $arregloTiempos = array();
                array_push($arregloTiempos, ['tiempo' => $diff, 'fecha_inicial' => $fechaTiempoMuerto, 'fecha_final' => $fechaActual]);
                $jsonTiempo = json_encode($arregloTiempos);
                $sql = "INSERT INTO minutas.tiempo_muerto(
                     fecha_creacion_tiempo_muerto, json_tiempo_muerto, 
                     id_usuario_tiempo_muerto_fk, descripcion_tiempo_muerto)
                    VALUES ('$fechActualF', '$jsonTiempo', '$usuarioToken->sub', '$totalHoras');";
                $conexion->consultaSimple($sql);

            }
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/listarUsuariosConMinutas', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $fecha_inicial = $app->request->post('fecha_inicial', null);
            $fecha_final = $app->request->post('fecha_final', null);
            if ($fecha_final == '' || $fecha_final == null) {
                $fecha_final = $fecha_inicial;
            }
            $sql = "select distinct(usu.id_usuario), usu.* from seguridad.usuario usu 
                    inner join minutas.registro_minutas rm on usu.id_usuario=rm.id_usuario_minutas_fk 
                    where to_char(rm.fecha_hora_inicio_minuta, 'YYYY-MM-DD') BETWEEN '$fecha_inicial' and '$fecha_final'";
            $conexion = new conexPGSeguridad();
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r

            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }
    echo $helper->checkCode($data);
});

$app->post('/prueba', function () use ($app) {
    $R = calcularStockUsuario('97');
    echo $R;
});
function calcularStockUsuario($idPedido)
{
    $conexionPgSegurida = new conexPGSeguridad();
    $conexionJseluis = new conexMsql();
    $conexionCalificacion = new conexPG();
    $sql = "select pm.* from minutas.pedidos_minutas pm where pm.id_pedido_minutas ='$idPedido';";
    $r = $conexionPgSegurida->consultaComplejaNorAso($sql);
    $codigo_Pedido = $r['codigo_pedido_minutas'];
    $sql = "select od.* from orders orde inner join order_details od on orde.id=od.id_order where orde.code = '$codigo_Pedido'";
    $rDetalleOrder = $conexionJseluis->consultaComplejaAso($sql);
    $validacionProcesado = false;
    $coma = ',';
    $cadenCaliidn = '';
    for ($i = 0; $i < count($rDetalleOrder); $i++) {
        if ($rDetalleOrder[$i]['state'] == 'ACTIVO') {
            $validacionProcesado = true;
        }
        if ($cadenCaliidn != '') {
            $cadenCaliidn .= $coma;
        }
        $cadenCaliidn .= $rDetalleOrder[$i]['id_caliidn'];
    }

    if ($validacionProcesado == false) {
        if ($rDetalleOrder != 0) {
            $idPedido = $rDetalleOrder[0]['id_order'];
            $sql = "select us.* from users us inner join users_per_order upo on us.id=upo.id_user 
                        where upo.id_order ='$idPedido' and upo.id_process_state ='9';";
            $usuarioPedido = $conexionJseluis->consultaComplejaNorAso($sql);
            if ($usuarioPedido != 0) {
                $documentoUsuario = $usuarioPedido['doc_number'];
                $sql = "select * from jl_caliidn as jl where jl.id IN ($cadenCaliidn)";
                $r = $conexionJseluis->consultaComplejaAso($sql);
                $cadenCaliidn = '';
                for ($i = 0; $i < count($r); $i++) {
                    if ($cadenCaliidn != '') {
                        $cadenCaliidn .= $coma;
                    }
                    $cadenCaliidn .= "'" . $r[$i]['caliidn'] . "'";
                }
                $sql = "select * from inventario inv where inv.caliidn in ($cadenCaliidn) order by inv.id ASC;";
                $r = $conexionCalificacion->consultaComplejaAso($sql);
                if ($r != 0) {
                    $sql = "select * from seguridad.usuario usu where usu.documento_usuario = '$documentoUsuario';";
                    $r2 = $conexionPgSegurida->consultaComplejaNorAso($sql);
                    $idUsuario = $r2['id_usuario'];
                    for ($n = 0; $n < count($r); $n++) {
                        $idProducto = $r[$n]['id_product'];
                        $sql = "select * from minutas.stock_usuario_almacen_calificacion su 
                        inner join minutas.almacen_calificacion ac on su.fk_id_almacen_calificacion = ac.id_almacen_calificacion 
                        where su.fk_id_almacen_calificacion = '$idProducto' and su.fk_stock_usuario_almacen_usuario_id = '$idUsuario' ";
                        $c = $conexionPgSegurida->consultaComplejaNorAso($sql);
                        if ($c == 0) {
                            return 'Lo sentimos, El usuario no cuenta con el producto con id: ' . $idProducto;
                        }
                    }
                    if ($r2 != 0) {
                        $sql = "select ac.* from minutas.almacen_calificacion ac order by ac.id_almacen_calificacion asc";
                        $r2 = $conexionPgSegurida->consultaComplejaAso($sql);
                        for ($i = 0; $i < count($r2); $i++) {
                            $r2[$i]['total_cantidad_almacen_calificacion'] = 0;
                        }
                        for ($y = 0; $y < count($r); $y++) {
                            $idProducto = $r[$y]['id_product'];
                            $cantidad = $r[$y]['cantidad'];
                            for ($i = 0; $i < count($r2); $i++) {
                                if ($idProducto == $r2[$i]['id_almacen_calificacion']) {
                                    $r2[$i]['total_cantidad_almacen_calificacion'] += $cantidad;
                                }
                            }
                        }
                        $sql = "select * from minutas.stock_usuario_almacen_calificacion su where su.fk_stock_usuario_almacen_usuario_id = '$idUsuario' ";
                        $c = $conexionPgSegurida->consultaComplejaAso($sql);
                        for ($i = 0; $i < count($r2); $i++) {
                            $idProducto = $r2[$i]['id_almacen_calificacion'];
                            $cantidad = $r2[$i]['total_cantidad_almacen_calificacion'];
                            for ($n = 0; $n < count($c); $n++) {
                                if ($c[$n]['fk_id_almacen_calificacion'] == $idProducto) {
                                    $cantidadActual = $c[$n]['cantidad_stock'];
                                    $c[$n]['cantidad_stock'] = $c[$n]['cantidad_stock'] - $cantidad;
                                    if ($c[$n]['cantidad_stock'] < 0) {
                                        return 'Lo sentimos, no tienen la cantidad suficiente del producto con id: ' .
                                            $c[$n]['fk_id_almacen_calificacion'].', cantidad requeridad: '.$cantidad.', cantidad actual: '.$cantidadActual;
                                    }
                                }
                            }
                        }
                        for ($i = 0; $i < count($c); $i++) {
                            $idStockUsuario = $c[$i]['id_stock_almacen_calificacion'];
                            $cantidad = $c[$i]['cantidad_stock'];
                            $sql = "UPDATE minutas.stock_usuario_almacen_calificacion
                                    SET   cantidad_stock='$cantidad'
                                    WHERE id_stock_almacen_calificacion='$idStockUsuario';";
                            $conexionPgSegurida->consultaSimple($sql);
                        }
                        $fecha_creacion = date('Y-m-d H:i');
                        $json = json_encode($r2);
                        $sql = "INSERT INTO minutas.registro_gasto_usuario(
                                cantidad, fecha_creacion_registro_gasto, codigo_pedido, id_usuario_registro_gasto_fk)
                                VALUES ('$json', '$fecha_creacion', '$codigo_Pedido', '$idUsuario');";
                        $conexionPgSegurida->consultaSimple($sql);
                        return '';
                    } else {
                        return 'Lo sentimos, no pudimos emparejar el usuario de LTEsoluciones con joseluis, comunicarse con el departamento de sistemas';
                    }

                } else {
                    return 'Lo sentimos no se a realizado ni una impresion de este pedido, no podemos terminar la minuta';
                }
            } else {
                return 'No se encontro el usuario para asociar el stock';
            }

        }
    } else {
        return 'Lo sentimos, no se encontro el paquete en los detalles';
    }

}

function metodoTiempoMuertoTemporal($id_usuario)
{
    $conexion = new conexPGSeguridad();
    $r1 = validarExistenciaTiempoMinutasTemporal($id_usuario);
    if ($r1 != 0) {
        $tiempo = json_decode($r1['json_tiempo_muerto'], true);
        if (validarFechaTiempoMuertoTemporal($tiempo['fecha']) == true) {
            $fechaActualF = date('Y-m-d H:i:s');
            $tiempoMuerto = $tiempo['fecha'];
            $sql = "select tm.* from minutas.tiempo_muerto tm where to_char(tm.fecha_creacion_tiempo_muerto, 'YYYY-MM-DD')  = '$tiempoMuerto' 
                        and tm.id_usuario_tiempo_muerto_fk='$id_usuario'";
            $r = $conexion->consultaComplejaNorAso($sql);
            if ($r != 0) {
                $fechaTiempoMuerto = new DateTime($tiempo['tiempo']);
                $fechaActual = new DateTime($fechaActualF);
                $diff = $fechaTiempoMuerto->diff($fechaActual);
                $totalHoras = get_format($diff);
                $jsonArreglo = json_decode($r['json_tiempo_muerto'], true);
                $arregloTiempos = $jsonArreglo;
                array_push($arregloTiempos, ['tiempo' => $diff, 'fecha_inicial' => $fechaTiempoMuerto, 'fecha_final' => $fechaActual]);
                $jsonTiempo = json_encode($arregloTiempos);
                $timepoTotal = calcularTotalTiempo(json_decode($jsonTiempo, true));
                $id_tiemp_muerto = $r['id_tiempo_muerto'];
                $sql = "UPDATE minutas.tiempo_muerto
            SET  json_tiempo_muerto='$jsonTiempo', descripcion_tiempo_muerto='$timepoTotal'
            WHERE id_tiempo_muerto='$id_tiemp_muerto';";
                $conexion->consultaSimple($sql);
                eliminarTiempoMuertoTemporal($id_usuario);
            } else {
                $fechaTiempoMuerto = new DateTime($tiempo['tiempo']);
                $fechaActual = new DateTime($fechaActualF);
                $diff = $fechaTiempoMuerto->diff($fechaActual);
                $totalHoras = get_format($diff);
                $arregloTiempos = array();
                array_push($arregloTiempos, ['tiempo' => $diff, 'fecha_inicial' => $fechaTiempoMuerto, 'fecha_final' => $fechaActual]);
                $jsonTiempo = json_encode($arregloTiempos);
                $sql = "INSERT INTO minutas.tiempo_muerto(
                     fecha_creacion_tiempo_muerto, json_tiempo_muerto, 
                     id_usuario_tiempo_muerto_fk, descripcion_tiempo_muerto)
                    VALUES ('$fechaActualF', '$jsonTiempo', '$id_usuario', '$totalHoras');";
                $conexion->consultaSimple($sql);
                eliminarTiempoMuertoTemporal($id_usuario);
            }
        } else {
            eliminarTiempoMuertoTemporal($id_usuario);
        }
    } else {

    }
}

function agregarTiempoMuertoTemporal($idusuario)
{

    eliminarTiempoMuertoTemporal($idusuario);
    $conexion = new conexPGSeguridad();
    $fechActual = date('Y-m-d');
    $tiempoM = date('Y-m-d H:i:s');
    $arregloTiempos = ['tiempo' => $tiempoM, 'fecha' => $fechActual];
    $jsonTMT = json_encode($arregloTiempos);
    $sql = "INSERT INTO minutas.tiempo_muerto_temporal(
	 json_tiempo_muerto, id_usuario_tiempo_muerto_temporal_fk)
	VALUES ('$jsonTMT', '$idusuario');";
    $conexion->consultaSimple($sql);
}

function eliminarTiempoMuertoTemporal($idUsuario)
{
    $conexion = new conexPGSeguridad();
    $sql = "delete from minutas.tiempo_muerto_temporal tm where tm.id_usuario_tiempo_muerto_temporal_fk = '$idUsuario';";
    $conexion->consultaSimple($sql);
}

function validarFechaTiempoMuertoTemporal($fechaTMT)
{
    $fechActual = date('Y-m-d');
    if ($fechActual == $fechaTMT) {
        return true;
    } else {
        return false;
    }
}

function validarExistenciaTiempoMinutasTemporal($id_usuario)
{
    $conexion = new conexPGSeguridad();
    $sql = "select tm.* from minutas.tiempo_muerto_temporal tm 
            where tm.id_usuario_tiempo_muerto_temporal_fk ='$id_usuario';";
    $r = $conexion->consultaComplejaNorAso($sql);
    return $r;
}

function calcularTotalTiempoUnitario($arregloTiempos)
{
    $años = 0;
    $meses = 0;
    $dias = 0;
    $horas = 0;
    $minutos = 0;
    $segundos = 0;
    $cadena = '';


    if ($arregloTiempos['y'] > 0) {
        $años += $arregloTiempos['y'];
    }
    if ($arregloTiempos['m'] > 0) {
        $meses += $arregloTiempos['m'];
    }
    if ($arregloTiempos['d'] > 0) {
        $dias += $arregloTiempos['d'];
    }
    if ($arregloTiempos['h'] > 0) {
        $horas += $arregloTiempos['h'];
    }
    if ($arregloTiempos['i'] > 0) {
        $minutos += $arregloTiempos['i'];
    }
    if ($arregloTiempos['s'] > 0) {
        $segundos += $arregloTiempos['s'];
    }


    //echo $cadena;
    if ($segundos >= 60) {
        $resulSegundos = $segundos / 60;
        $res = explode('.', $resulSegundos);
        $minutos += $res[0];
        $segundos = round(($resulSegundos - $res[0]) * 60);
    }
    if ($minutos >= 60) {
        $resulSegundos = $minutos / 60;
        $res = explode('.', $resulSegundos);
        $horas += $res[0];
        $minutos = round(($resulSegundos - $res[0]) * 60);
    }
    if ($horas >= 24) {
        $resulSegundos = $horas / 24;
        $res = explode('.', $resulSegundos);
        $dias += $res[0];
        $horas = round(($resulSegundos - $res[0]) * 24);
    }

    $cadena .= ($años > 0) ? $años . '-A-' : '';
    $cadena .= ($meses > 0) ? $meses . '-M-' : '';
    $cadena .= ($dias > 0) ? $dias . '-D-' : '';
    $cadena .= ($horas > 0) ? $horas . '-H-' : '';
    $cadena .= ($minutos > 0) ? $minutos . '-MM-' : '';
    $cadena .= ($segundos > 0) ? $segundos . '-S-' : '';

    return $cadena;

}

function calcularTotalTiempo($arregloTiempos)
{
    $años = 0;
    $meses = 0;
    $dias = 0;
    $horas = 0;
    $minutos = 0;
    $segundos = 0;
    $cadena = '';
    if (count($arregloTiempos) > 0) {
        for ($i = 0; $i < count($arregloTiempos); $i++) {

            if ($arregloTiempos[$i]['tiempo']['y'] > 0) {
                $años += $arregloTiempos[$i]['tiempo']['y'];
            }
            if ($arregloTiempos[$i]['tiempo']['m'] > 0) {
                $meses += $arregloTiempos[$i]['tiempo']['m'];
            }
            if ($arregloTiempos[$i]['tiempo']['d'] > 0) {
                $dias += $arregloTiempos[$i]['tiempo']['d'];
            }
            if ($arregloTiempos[$i]['tiempo']['h'] > 0) {
                $horas += $arregloTiempos[$i]['tiempo']['h'];
            }
            if ($arregloTiempos[$i]['tiempo']['i'] > 0) {
                $minutos += $arregloTiempos[$i]['tiempo']['i'];
            }
            if ($arregloTiempos[$i]['tiempo']['s'] > 0) {
                $segundos += $arregloTiempos[$i]['tiempo']['s'];
            }

        }
    }
    //echo $cadena;
    if ($segundos >= 60) {
        $resulSegundos = $segundos / 60;
        $res = explode('.', $resulSegundos);
        $minutos += $res[0];
        $segundos = round(($resulSegundos - $res[0]) * 60);
    }
    if ($minutos >= 60) {
        $resulSegundos = $minutos / 60;
        $res = explode('.', $resulSegundos);
        $horas += $res[0];
        $minutos = round(($resulSegundos - $res[0]) * 60);
    }
    if ($horas >= 24) {
        $resulSegundos = $horas / 24;
        $res = explode('.', $resulSegundos);
        $dias += $res[0];
        $horas = round(($resulSegundos - $res[0]) * 24);
    }

    $cadena .= ($años > 0) ? $años . '-A-' : '';
    $cadena .= ($meses > 0) ? $meses . '-M-' : '';
    $cadena .= ($dias > 0) ? $dias . '-D-' : '';
    $cadena .= ($horas > 0) ? $horas . '-H-' : '';
    $cadena .= ($minutos > 0) ? $minutos . '-MM-' : '';
    $cadena .= ($segundos > 0) ? $segundos . '-S-' : '';

    return $cadena;

}

function validarEstadoPedido($usuario)
{
    $conexion = new conexPGSeguridad();
    $sql = "select rm.* from minutas.registro_minutas rm where rm.id_usuario_minutas_fk = '$usuario->sub' and rm.estado_minuta ='SINTERMINAR';";
    $r = $conexion->consultaComplejaNorAso($sql);
    if ($r == 0) {
        return true;
    } else {
        return false;
    }
}

function validarExistenciaPedido($codigo)
{
    $conexion = new conexPGSeguridad();
    $sql = "select mp.* from minutas.pedidos_minutas mp where mp.codigo_pedido_minutas='$codigo'";

    $r = $conexion->consultaComplejaNorAso($sql);
    if ($r != 0) {

        return $r['id_pedido_minutas'];
    } else {

        return null;
    }
}

function get_format($df)
{

    $str = '';
    $str .= ($df->invert == 1) ? ' ' : '';
    if ($df->y > 0) {
        // years
        $str .= ($df->y > 1) ? $df->y . ' -A- ' : $df->y . ' -A- ';
    }
    if ($df->m > 0) {
        // month
        $str .= ($df->m > 1) ? $df->m . ' -M- ' : $df->m . ' -M- ';
    }
    if ($df->d > 0) {
        // days
        $str .= ($df->d > 1) ? $df->d . ' -D- ' : $df->d . ' -D- ';
    }
    if ($df->h > 0) {
        // hours
        $str .= ($df->h > 1) ? $df->h . ' -H- ' : $df->h . ' -H- ';
    }
    if ($df->i > 0) {
        // minutes
        $str .= ($df->i > 1) ? $df->i . ' -MM- ' : $df->i . ' -MM- ';
    }
    if ($df->s > 0) {
        // seconds
        $str .= ($df->s > 1) ? $df->s . ' -S- ' : $df->s . ' -S- ';
    }

    return $str;
}