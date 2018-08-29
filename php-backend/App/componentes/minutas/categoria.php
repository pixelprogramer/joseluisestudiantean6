<?php
$app->post('/minutas/unix/nuevaCategoria', function () use ($app) {
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
                    $sql = "INSERT INTO minutas.categoria_minutas(
                         id_tipo_pedido_minutas_fk, descripcion_categoria_minutas)
                        VALUES ('$id_tipo_pedido_minutas_fk', '$descripcion_categoria_minutas');";
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
$app->post('/minutas/unix/listarCategoriaCrear', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select *  from order_type";
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
$app->post('/minutas/unix/listarCategoria', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $sql = "SELECT id_categoria_minutas, id_tipo_pedido_minutas_fk, descripcion_categoria_minutas
	FROM minutas.categoria_minutas;";
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