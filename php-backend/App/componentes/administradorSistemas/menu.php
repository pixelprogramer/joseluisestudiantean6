<?php
$app->post('/administrador/sistemas/crearMenu', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $descripcion_menu = (isset($parametros->descripcion_menu)) ? $parametros->descripcion_menu : null;
                $nombre_componente_menu = (isset($parametros->nombre_componente_menu)) ? $parametros->nombre_componente_menu : null;
                $ruta_menu = (isset($parametros->ruta_menu)) ? $parametros->ruta_menu : null;
                $estado_menu = (isset($parametros->estado_menu)) ? $parametros->estado_menu : null;
                $fecha_creacion_menu = date('Y-m-d H:i');
                $fecha_actualizacion_menu = date('Y-m-d H:i');
                $icono = (isset($parametros->icono)) ? $parametros->icono : null;
                $pagina_defecto = (isset($parametros->pagina_defecto)) ? $parametros->pagina_defecto : null;
                $sql = "INSERT INTO seguridad.menu(
                        descripcion_menu, nombre_componente_menu, ruta_menu, estado_menu, 
                        fecha_creacion_menu, fecha_actualizacion_menu, icono, pagina_defecto)
                        VALUES ('$descripcion_menu', '$nombre_componente_menu', '$ruta_menu', 
                        '$estado_menu', '$fecha_creacion_menu', '$fecha_actualizacion_menu','$icono','$pagina_defecto');";
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
$app->post('/administrador/sistemas/actualizarMenu', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $id_menu = (isset($parametros->id_menu)) ? $parametros->id_menu : null;
                $descripcion_menu = (isset($parametros->descripcion_menu)) ? $parametros->descripcion_menu : null;
                $nombre_componente_menu = (isset($parametros->nombre_componente_menu)) ? $parametros->nombre_componente_menu : null;
                $ruta_menu = (isset($parametros->ruta_menu)) ? $parametros->ruta_menu : null;
                $estado_menu = (isset($parametros->estado_menu)) ? $parametros->estado_menu : null;
                $fecha_actualizacion_menu = date('Y-m-d H:i');;
                $icono = (isset($parametros->icono)) ? $parametros->icono : null;
                $pagina_defecto = (isset($parametros->pagina_defecto)) ? $parametros->pagina_defecto : null;
                $sql = "UPDATE seguridad.menu
                        SET descripcion_menu='$descripcion_menu', nombre_componente_menu='$nombre_componente_menu', 
                        ruta_menu='$ruta_menu', estado_menu='$estado_menu', 
                        fecha_actualizacion_menu='$fecha_actualizacion_menu', 
                        icono='$icono', pagina_defecto='$pagina_defecto'
                        WHERE id_menu='$id_menu';";
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-007'
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
$app->post('/administrador/sistemas/listarMenu', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $sql = "select * from seguridad.menu order by id_menu desc";
            $r = $conexion->consultaComplejaAso($sql);
            if ($r != 0) {
                $data = [
                    'code' => 'LTE-001',
                    'data' => $r
                ];
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
    echo $helper->checkCode($data);
});

$app->post('/administrador/sistemas/todoMenu', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    $parametros = json_decode($json);
    $id_rol = (isset($parametros->id_rol)) ? $parametros->id_rol : null;
    $conexion = new conexPGSeguridad();
    $sql = "select cabe.*,ro.descripcion_rol from  seguridad.rol ro 
        join seguridad.cabezera cabe on ro.id_rol=cabe.id_rol_fk_cabezera 
        where ro.id_rol='$id_rol' order by cabe.prioridad_cabezera asc;";
    $r = $conexion->consultaComplejaAso($sql);
    if ($r != 0) {
        $permisos = array();
        for ($i = 0; $i < count($r); $i++) {
            $id_cabezera = $r[$i]['id_cabezera'];
            $prioridad_cabezera = $r[$i]['prioridad_cabezera'];
            $sql = "select me.*,ro.descripcion_rol, sub.prioridad_submenu,sub.id_submenu from seguridad.rol ro
                    join seguridad.cabezera cabe on ro.id_rol=cabe.id_rol_fk_cabezera
                    join seguridad.submenu sub on cabe.id_cabezera=sub.id_cabezera_fk_submenu
                    join seguridad.menu me on me.id_menu=sub.id_menu_fk_submenu 
                    where cabe.id_cabezera='$id_cabezera' order by sub.prioridad_submenu asc;";
            $r2 = $conexion->consultaComplejaAso($sql);
            array_push($permisos, [
                'id_cabezera' => $id_cabezera,
                'descripcion_cabezera' => $r[$i]['descripcion_cabezera'],
                'estado_cabezera' => $r[$i]['estado_cabezera'],
                'prioridad_cabezera' => $prioridad_cabezera,
                'subMenus' => $r2
            ]);
            $data = [
                'code' => 'LTE-001',
                'data' => $permisos
            ];

        }
    } else {
        $data = [
            'code' => 'LTE-000',
            'status' => 'error',
            'msg' => 'Lo sentimos, este rol no tiene menu creado'
        ];
    }

    echo $helper->checkCode($data);
});
$app->post('/administrador/sistemas/actualizarPrioridad', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $json = $app->request->post('menu', null);
            $menuCompleto = json_decode($json, true);
            for ($i = 0; $i < count($menuCompleto); $i++) {
                $idCabezera = $menuCompleto[$i]['id_cabezera'];
                $priorida = $menuCompleto[$i]['prioridad_cabezera'];
                $sql = "UPDATE seguridad.cabezera
                SET  prioridad_cabezera='$priorida'
                WHERE id_cabezera='$idCabezera';";
                $conexion->consultaSimple($sql);
                for ($j = 0; $j < count($menuCompleto[$i]['subMenus']); $j++) {
                    $idSubmenu = $menuCompleto[$i]['subMenus'][$j]['id_submenu'];
                    $prioridadSub = $menuCompleto[$i]['subMenus'][$j]['prioridad_submenu'];
                    $sql = "UPDATE seguridad.submenu
                                    SET prioridad_submenu='$prioridadSub'
                                    WHERE id_submenu='$idSubmenu';";
                    $conexion->consultaSimple($sql);
                }
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
$app->post('/administrador/sistemas/nuevoSubMenu', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $id_cabezera_fk_submenu = (isset($parametros->id_cabezera_fk_submenu)) ? $parametros->id_cabezera_fk_submenu : null;
            $id_menu_fk_submenu = (isset($parametros->id_menu_fk_submenu)) ? $parametros->id_menu_fk_submenu : null;
            $estado_submenu = (isset($parametros->estado_submenu)) ? $parametros->estado_submenu : null;
            $sql = "select *  from seguridad.submenu sub where sub.id_cabezera_fk_submenu = '$id_cabezera_fk_submenu';";
            $r = $conexion->consultaComplejaNor($sql);
            $prioridad_submenu = pg_num_rows($r) + 1;
            $sql = "INSERT INTO seguridad.submenu(
	id_cabezera_fk_submenu, id_menu_fk_submenu, estado_submenu, prioridad_submenu)
	VALUES ('$id_cabezera_fk_submenu', '$id_menu_fk_submenu', '$estado_submenu', '$prioridad_submenu');";
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
    echo $helper->checkCode($data);
});
$app->post('/administrador/sistemas/eliminarSubmenuMenu', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $id_submenu = (isset($parametros->id_submenu)) ? $parametros->id_submenu : null;
            $sql = "DELETE FROM seguridad.submenu
                    WHERE id_submenu ='$id_submenu' ;";
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
    echo $helper->checkCode($data);
});
$app->post('/administrador/sistemas/nuevaCabezera', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $descripcion_cabezera = (isset($parametros->descripcion_cabezera)) ? $parametros->descripcion_cabezera : null;
            $estado_cabezera = (isset($parametros->estado_cabezera)) ? $parametros->estado_cabezera : null;
            $id_rol_fk_cabezera = (isset($parametros->id_rol_fk_cabezera)) ? $parametros->id_rol_fk_cabezera : null;
            $fecha_creacion_menu = date('Y-m-d H:i');
            $fecha_actualizacion_menu = date('Y-m-d H:i');
            $sql = "select * from seguridad.cabezera cabe where cabe.id_rol_fk_cabezera = '$id_rol_fk_cabezera'; ";
            $r = $conexion->consultaComplejaNor($sql);
            $prioridad_cabezera = pg_num_rows($r)+1;
            $sql = "INSERT INTO seguridad.cabezera(
                    descripcion_cabezera, estado_cabezera, fecha_creacion_cabezera, fecha_actualizacion_cabezera, id_rol_fk_cabezera, prioridad_cabezera)
                    VALUES ('$descripcion_cabezera', '$estado_cabezera', '$fecha_creacion_menu', '$fecha_actualizacion_menu', '$id_rol_fk_cabezera', '$prioridad_cabezera');";
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
    echo $helper->checkCode($data);
});