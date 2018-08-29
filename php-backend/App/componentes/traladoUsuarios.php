<?php


$app->post('/listarUsuario', function () use ($app) {

    $helper = new helper();
    $json = $app->request->post('json', null);
    $parametro = json_decode($json);
    $caliidn = (isset($parametro->caliidn)) ? $parametro->caliidn : null;
    $sql = "SELECT calixestuidn, e.estuidn, caliidn, usuaidn,   calipreorden,    e.estunombrev
        FROM public.calificacionxestudiante cxe INNER JOIN estudiante e ON cxe.estuidn = e.estuidn
        WHERE caliidn = '$caliidn'
        ORDER BY e.estuidn ASC;";
    $conexion = new conexPG();
    $r = $conexion->consultaComplejaAso($sql);

    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});
$app->post('/traslado/filtroEstudiante', function () use ($app) {

    $helper = new helper();
    $filtro = $app->request->post('filtro',null);
    $json = $app->request->post('json', null);
    $parametro = json_decode($json);
    $caliidn = (isset($parametro->caliidn)) ? $parametro->caliidn : null;
    $sql = "SELECT calixestuidn, e.estuidn, caliidn, usuaidn,   calipreorden,    e.estunombrev
        FROM public.calificacionxestudiante cxe INNER JOIN estudiante e ON cxe.estuidn = e.estuidn
        WHERE caliidn = '$caliidn' and e.estunombrev like '%$filtro%'
        ORDER BY e.estuidn ASC;";
    $conexion = new conexPG();
    $r = $conexion->consultaComplejaAso($sql);
    $data = [
        'code' => 'LTE-001',
        'data' => $r
    ];
    echo $helper->checkCode($data);
});
$app->post('/actualizar', function () use ($app) {
    $helper = new helper();
    $json = $app->request->post('json', null);
    $token = $app->request->post('token',null);
    if ($token != null)
    {
     $validaciontoken = $helper->authCheck($token);
     if ($validaciontoken == true)
     {
         $usuariotoken = $helper->authCheck($token, true);
         $fecha_creacion_menu = date('Y-m-d H:i');
         $jsonEstudiantes = $app->request->post('estudiantes', null);
         $estudiantes = json_decode($jsonEstudiantes, true);
         $parametro = json_decode($json);
         $caliidnnew = (isset($parametro->caliidnnew)) ? $parametro->caliidnnew : null;
         $conexion = new conexPG();
         $sql = "select c.* from calificacion c where c.caliidn ='$caliidnnew'";
         $r = $conexion->consultaComplejaNorAso($sql);
         $estudiantesCamabiados = '';
         $calidnantiguos='';
         if ($r != 0) {
             for ($i = 0; $i < count($estudiantes); $i++) {
                 $caliidn = $estudiantes[$i]['caliidn'];
                 $calixestuidn = $estudiantes[$i]['calixestuidn'];
                 $estudiantesCamabiados.=$estudiantes[$i]['calixestuidn'].'-';
                 $calidnantiguos.= $estudiantes[$i]['caliidn'].'-';
                 $sql = "UPDATE public.calificacionxestudiante
            SET caliidn='$caliidnnew'
            WHERE caliidn = '$caliidn' AND calixestuidn='$calixestuidn'";
                 $conexion->consultaSimple($sql);
             }
             $descripcionActividad = 'Se cambiaron los estudiantes con calixestudidn: '.$estudiantesCamabiados.' al curso: '.$caliidnnew.' lo cuales estaban en los cursos: '.$calidnantiguos;
                $sql = "INSERT INTO configuracion.log_lte(
                 fecha_creacion_log, descripcion_log, id_usuario_fk_log)
                VALUES ('$fecha_creacion_menu', '$descripcionActividad',  '$usuariotoken->sub');";
                $conexion =  new conexPGSeguridad();
                $conexion->consultaSimple($sql);
             $data = [
                 'code' => 'LTE-001'
             ];
         } else {
             $data = [
                 'code' => 'LTE-000',
                 'status' => 'error',
                 'msg' => 'Lo sentimos el paquete: ' . $caliidnnew . ' no existe'
             ];
         }
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