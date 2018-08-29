<?php
$app->post('/calificacion/siscalificacion/crearColegio', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            if ($json != null) {
                $usuarioToken = $helper->authCheck($token,true);
                $conexion = new conexPG();
                $parametros = json_decode($json);
                $colecoddanen = (isset($parametros->colecoddanen)) ? $parametros->colecoddanen : null;
                $coledescripv = (isset($parametros->coledescripv)) ? $parametros->coledescripv : null;
                $lugaidn = (isset($parametros->lugaidn)) ? $parametros->lugaidn : null;
                $sql = "INSERT INTO public.colegio(
                         lugaidn, colecoddanen, coledescripv)
                        VALUES ('$lugaidn', '$colecoddanen', '$coledescripv');";
                $conexion->consultaSimple($sql);
                $fecha_creacion = date('Y-m-d H:i');
                $descripcionActividad = 'Se creo el colegio con codigo dane: ' . $colecoddanen;
                $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fecha_creacion', '$descripcionActividad',  '$usuarioToken->sub');";
                $conexion = new conexPGSeguridad();
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001'
                ];
            } else {
                $data = [
                    'code' => 'LTE-009'
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
$app->post('/calificacion/siscalificacion/actualizarColegio', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            if ($json != null) {
                $conexion = new conexPG();
                $parametros = json_decode($json);
                $coleidn=(isset($parametros->coleidn)) ? $parametros->coleidn : null;
                $colecoddanen = (isset($parametros->colecoddanen)) ? $parametros->colecoddanen : null;
                $coledescripv = (isset($parametros->coledescripv)) ? $parametros->coledescripv : null;
                $lugaidn = (isset($parametros->lugaidn)) ? $parametros->lugaidn : null;
                $sql = "UPDATE public.colegio
                        SET   lugaidn='$lugaidn', colecoddanen='$colecoddanen', coledescripv='$coledescripv'
                        WHERE coleidn='$coleidn';";
                $conexion->consultaSimple($sql);
                $fecha_creacion = date('Y-m-d H:i');
                $usuarioToken = $helper->authCheck($token,true);
                $descripcionActividad = 'Se actualizo el colegio con codigo dane: ' . $colecoddanen;
                $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fecha_creacion', '$descripcionActividad',  '$usuarioToken->sub');";
                $conexion = new conexPGSeguridad();
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-007'
                ];
            } else {
                $data = [
                    'code' => 'LTE-009'
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
$app->post('/calificacion/siscalificacion/listarColegio', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {

            $conexion = new conexPG();
            $sql = "select lu.lugadescripv as descripcionlugar, cole.* from colegio cole inner join lugar lu on cole.lugaidn=lu.lugacoddanen order by cole.coleidn desc limit 1000";
            $r=$conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data'=>$r
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
$app->post('/calificacion/siscalificacion/listarLugar', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $conexion = new conexPG();
            $sql = "select * from lugar";
            $r=$conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data'=>$r
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
$app->post('/calificacion/siscalificacion/filtrar', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $conexion = new conexPG();
            $dane = $app->request->post('dane',null);
            $sql = "select lu.lugadescripv as descripcionlugar, cole.* 
                    from colegio cole inner join lugar lu on cole.lugaidn=lu.lugacoddanen 
                    where cole.colecoddanen='$dane'";
            $r=$conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data'=>$r
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