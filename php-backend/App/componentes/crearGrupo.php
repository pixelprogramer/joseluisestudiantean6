<?php

$app->post('/calificacion/crearGrupo', function () use ($app) {
    $json = $app->request->post('json', null);
    $token = $app->request->post('token', null);
    $helper = new helper();
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($token == true) {
            $usuarioToken = $helper->authCheck($token, true);
            $fecha_creacion_menu = date('Y-m-d H:i');
            $parametros = json_decode($json);
            $conexionSeguridad = new conexPGSeguridad();
            $descripcionActividad = '';
            $conexion = new conexPG();
            $cursdescripv = (isset($parametros->cursdescripv)) ? $parametros->cursdescripv : null;
            $cur_cursidn = (isset($parametros->cur_cursidn)) ? $parametros->cur_cursidn : null;
            $sql = "select * from public.curso cur where cur.cursdescripv like '$cursdescripv';";
            $r = $conexion->consultaComplejaNorAso($sql);
            if ($r == 0) {
                if ($cur_cursidn == null || $cur_cursidn == '') {
                    $sql = "INSERT INTO public.curso(
	cursdescripv, cursnombrev)
	VALUES ('$cursdescripv', ' ') returning cursidn;";
                    $descripcionActividad = 'Se creo el grupo: ' . $cursdescripv;
                } else {
                    $sql = "INSERT INTO public.curso(
	 cur_cursidn, cursdescripv, cursnombrev)
	VALUES ('$cur_cursidn',  '$cursdescripv', ' ') returning cursidn;";
                    $descripcionActividad = 'Se creo el curso : ' . $cursdescripv . ' en el grupo con codigo: ' . $cur_cursidn;

                }
                $r = $conexion->consultaComplejaNorAso($sql);
                $idAccion = $r['cursidn'];
                $sql = "INSERT INTO configuracion.log_lte(
                    fecha_creacion_log, descripcion_log, id_accion_log, id_usuario_fk_log)
                    VALUES ('$fecha_creacion_menu', '$descripcionActividad', '$idAccion', '$usuarioToken->sub');";
                $conexionSeguridad->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001'
                ];
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, ya existe este grupo: ' . $cursdescripv
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
$app->post('/calificacion/listarCursos', function () use ($app) {
    $json = $app->request->post('json', null);
    $parametros = json_decode($json);
    $helper = new helper();
    $conexion = new conexPG();
    $sql = "select c.* from curso c where c.cur_cursidn is null order by c.cursidn desc limit 500";
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
    echo $helper->checkCode($data);
});
$app->post('/calificacion/listarCursosHijos', function () use ($app) {
    $json = $app->request->post('json', null);
    $parametros = json_decode($json);
    $helper = new helper();
    $conexion = new conexPG();
    $cursidn = (isset($parametros->cursidn)) ? $parametros->cursidn : null;
    $sql = "select c.* from curso c where c.cur_cursidn='$cursidn'";
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
    echo $helper->checkCode($data);
});
$app->post('/calificacion/actualizarGrupo', function () use ($app) {
    $json = $app->request->post('json', null);
    $token = $app->request()->post('token', null);
    if ($token != null) {
        $helper = new helper();
        $validaciontoken = $helper->authCheck($token);
        if ($validaciontoken == true) {
            $usuarioToken = $helper->authCheck($token, true);
            $fecha_creacion_menu = date('Y-m-d H:i');
            $parametros = json_decode($json);
            $conexion = new conexPG();
            $cursidn = (isset($parametros->cursidn)) ? $parametros->cursidn : null;
            $cursdescripv = (isset($parametros->cursdescripv)) ? $parametros->cursdescripv : null;
            $sql = "UPDATE public.curso
	SET cursdescripv='$cursdescripv'
	WHERE cursidn='$cursidn';";
            $conexion->consultaSimple($sql);
            $descripcionActtividad = 'Se actualizo el grupo con codigo: ' . $cursidn . ' con la nueva descripcion: ' . $cursdescripv;
            $sql = "INSERT INTO configuracion.log_lte(
	 fecha_creacion_log, descripcion_log, id_accion_log, id_usuario_fk_log)
	VALUES ('$fecha_creacion_menu', '$descripcionActtividad', '$cursidn', '$usuarioToken->sub');";
            $conexion = new conexPGSeguridad();
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
$app->post('/calificacion/filtrarGrupo', function () use ($app) {
    $token = $app->request->post('token', null);
    $helper = new helper();
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($token == true) {
            $descripcion = $app->request->post('filtro',null);
            $conexion =  new conexPG();
            $sql = "select * from public.curso cur where cur.cursdescripv like '%$descripcion%' and cur.cur_cursidn is null;";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code'=>'LTE-001',
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


