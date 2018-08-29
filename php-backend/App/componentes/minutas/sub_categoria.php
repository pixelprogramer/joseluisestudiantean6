<?php
$app->post('/minutas/unix/nuevaSubCategoria', function () use ($app) {
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
                $id_accion_minutas_fk = (isset($parametros->id_accion_minutas_fk)) ? $parametros->id_accion_minutas_fk : null;
                $id_categoria_sub_menu_fk = (isset($parametros->id_categoria_sub_menu_fk)) ? $parametros->id_categoria_sub_menu_fk : null;
                $id_usuario_sub_categoria_minutas_fk = $usuarioToken->sub;
                $fecha_creacion = date('Y-m-d H:i');
                $fecha_actualizacion = date('Y-m-d H:i');
                $sql = "select sub.* from minutas.sub_categoria_minutas sub 
                where sub.id_accion_minutas_fk = '$id_accion_minutas_fk' and sub.id_categoria_sub_menu_fk ='$id_categoria_sub_menu_fk';";

                $r = $conexion->consultaComplejaAso($sql);
                if ($r == 0)
                {
                    $sql = "INSERT INTO minutas.sub_categoria_minutas(
                        fecha_creacion_sub_categoria_minutas, fecha_actualizacion_sub_categoria_minutas, 
                        id_accion_minutas_fk, id_categoria_sub_menu_fk, id_usuario_sub_categoria_minutas_fk)
                        VALUES ('$fecha_creacion', '$fecha_actualizacion', '$id_accion_minutas_fk', '$id_categoria_sub_menu_fk', '$id_usuario_sub_categoria_minutas_fk');";
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                }else
                {
                    $data=[
                        'code'=>'LTE-000',
                        'status'=>'error',
                        'msg'=>'Lo sentimos, Ya existe esta accion en esa categoria'
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
$app->post('/minutas/unix/eliminarSub', function () use ($app) {
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
                $id_sub_menu_minutas = (isset($parametros->id_sub_menu_minutas)) ? $parametros->id_sub_menu_minutas : null;
                $sql = "DELETE FROM minutas.sub_categoria_minutas
                        WHERE id_sub_menu_minutas = '$id_sub_menu_minutas';";
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
$app->post('/minutas/unix/listarCategoriaSubCategorias', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $arregloCategorias = array();
            $sql = "select * from minutas.categoria_minutas";
            $r = $conexion->consultaComplejaAso($sql);
            if ($r !=0)
            {
                for ($i = 0; $i < count($r); $i++) {
                    $id_categoria = $r[$i]['id_categoria_minutas'];
                    $sql = "select subcate.id_accion_minutas_fk,subcate.id_categoria_sub_menu_fk,subcate.id_sub_menu_minutas, 
                        accion.descripcion_acciones_minutas,accion.seleccion_pedido_accion,accion.estado_acciones_minutas from minutas.categoria_minutas cate 
                        join minutas.sub_categoria_minutas subcate on cate.id_categoria_minutas=subcate.id_categoria_sub_menu_fk
                        join minutas.acciones_minutas accion on subcate.id_accion_minutas_fk=accion.id_acciones_minutas
                        where cate.id_categoria_minutas ='$id_categoria'";
                    $r2 = $conexion->consultaComplejaAso($sql);
                    array_push($arregloCategorias, [
                        'id_categoria_minutas' => $id_categoria,
                        'id_tipo_pedido_minutas_fk' => $r[$i]['id_tipo_pedido_minutas_fk'],
                        'descripcion_categoria_minutas' => $r[$i]['descripcion_categoria_minutas'],
                        'sub_categorias'=>$r2
                    ]);
                }
                $data = [
                    'code' => 'LTE-001',
                    'data' => $arregloCategorias
                ];
            }else
            {
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

    echo $helper->checkCode($data);
});
