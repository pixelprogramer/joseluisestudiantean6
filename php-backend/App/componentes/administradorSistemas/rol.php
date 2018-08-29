<?php
$app->post('/administrador/sistemas/crearRol', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $descripcion_rol = (isset($parametros->descripcion_rol)) ? $parametros->descripcion_rol : null;
                $estado_rol = (isset($parametros->estado_rol)) ? $parametros->estado_rol : null;
                $fecha_creacion_rol = date('Y-m-d H:i');
                $fecha_actualizacion_rol = date('Y-m-d H:i');
                $sql = "INSERT INTO seguridad.rol(
                descripcion_rol, estado_rol, fecha_creacion_rol, fecha_actualizacion_rol)
                VALUES ('$descripcion_rol', '$estado_rol', '$fecha_creacion_rol', '$fecha_actualizacion_rol');";
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
$app->post('/administrador/sistemas/actualizarRol', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $id_rol = (isset($parametros->id_rol)) ? $parametros->id_rol : null;
                $descripcion_rol = (isset($parametros->descripcion_rol)) ? $parametros->descripcion_rol : null;
                $estado_rol = (isset($parametros->estado_rol)) ? $parametros->estado_rol : null;
                $fecha_actualizacion_rol = date('Y-m-d H:i');
                $sql = "UPDATE seguridad.rol
                        SET descripcion_rol='$descripcion_rol', estado_rol='$estado_rol', fecha_actualizacion_rol='$fecha_actualizacion_rol'
                        WHERE id_rol='$id_rol';";
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
$app->post('/administrador/sistemas/listarRol', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexPGSeguridad();
            $sql = "select * from seguridad.rol order by id_rol desc limit 200";
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
