<?php
$app->post('/minutas/unix/nuevaAccion', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $usuarioToken = $helper->authCheck($token,true);
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $descripcion_acciones_minutas = (isset($parametros->descripcion_acciones_minutas)) ? $parametros->descripcion_acciones_minutas : null;
                $seleccion_pedido_accion = (isset($parametros->seleccion_pedido_accion)) ? $parametros->seleccion_pedido_accion : null;
                $estado_acciones_minutas = (isset($parametros->estado_acciones_minutas)) ? $parametros->estado_acciones_minutas : null;
                $id_usuario_acciones_minutas_fk = $usuarioToken->sub;
                $fecha_creacion = date('Y-m-d H:i');
                $fecha_actualizacion = date('Y-m-d H:i');
                $sql = "INSERT INTO minutas.acciones_minutas(
                        descripcion_acciones_minutas, seleccion_pedido_accion, fecha_creacion_acciones_minutas, 
                        fecha_actualizacion_acciones_minutas, estado_acciones_minutas, id_usuario_acciones_minutas_fk)
                        VALUES ('$descripcion_acciones_minutas', '$seleccion_pedido_accion', '$fecha_creacion', 
                        '$fecha_actualizacion', '$estado_acciones_minutas', '$id_usuario_acciones_minutas_fk');";
                $conexion->consultaSimple($sql);
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
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/actualizarAccion', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $id_acciones_minutas = (isset($parametros->id_acciones_minutas)) ? $parametros->id_acciones_minutas: null;
                $descripcion_acciones_minutas = (isset($parametros->descripcion_acciones_minutas)) ? $parametros->descripcion_acciones_minutas : null;
                $seleccion_pedido_accion = (isset($parametros->seleccion_pedido_accion)) ? $parametros->seleccion_pedido_accion : null;
                $estado_acciones_minutas = (isset($parametros->estado_acciones_minutas)) ? $parametros->estado_acciones_minutas : null;
                $fecha_actualizacion = date('Y-m-d H:i');
                $sql = "UPDATE minutas.acciones_minutas
                        SET descripcion_acciones_minutas='$descripcion_acciones_minutas', seleccion_pedido_accion='$seleccion_pedido_accion',
                         fecha_actualizacion_acciones_minutas='$fecha_actualizacion', estado_acciones_minutas='$estado_acciones_minutas'
                        WHERE id_acciones_minutas='$id_acciones_minutas';";
                $conexion->consultaSimple($sql);
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
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/minutas/unix/actualzarCategoria', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    $json = $app->request->post('json', null);
    if ($json != null) {
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $conexion = new conexPGSeguridad();
                $parametros = json_decode($json);
                $id_tipo_pedido_minutas_fk = (isset($parametros->id_tipo_pedido_minutas_fk)) ? $parametros->id_tipo_pedido_minutas_fk : null;
                $sql = "select * from minutas.categoria_minutas where id_tipo_pedido_minutas_fk ='$id_tipo_pedido_minutas_fk'";
                $r = $conexion->consultaComplejaAso($sql);
                if ($r == 0) {
                    $descripcion_categoria_minutas = (isset($parametros->descripcion_categoria_minutas)) ? $parametros->descripcion_categoria_minutas : null;
                    $id_categoria_minutas = (isset($parametros->id_categoria_minutas)) ? $parametros->id_categoria_minutas : null;
                    $sql = "UPDATE minutas.categoria_minutas
                            SET id_tipo_pedido_minutas_fk='$id_tipo_pedido_minutas_fk', descripcion_categoria_minutas='$descripcion_categoria_minutas'
                            WHERE id_categoria_minutas='$id_categoria_minutas';";
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, ya esta creada esta categoria'
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

$app->post('/minutas/unix/listarAccion', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $sql = "SELECT * FROM minutas.acciones_minutas;";
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