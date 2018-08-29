<?php
$app->post('/calificacion/almacen/listar',function ()use($app){
    $helper = new helper();
    $conexion = new conexPGSeguridad();
    $token = $app->request()->post('token',null);
    if ($token != null){
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true){
            $sql = "select ac.* from minutas.almacen_calificacion ac";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code'=>'LTE-001',
                'data'=> $r
            ];
        }else
        {
            $data =[
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
$app->post('/calificacion/almacen/listarStockUsuario',function ()use($app){
    $helper = new helper();
    $conexion = new conexPGSeguridad();
    $token = $app->request()->post('token',null);
    if ($token != null){
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true){
            $id_usuario = $app->request->post('id',null);
            $sql = "select * from minutas.stock_usuario_almacen_calificacion suac 
                    inner join minutas.almacen_calificacion ac on  suac.fk_id_almacen_calificacion=ac.id_almacen_calificacion 
                    where suac.fk_stock_usuario_almacen_usuario_id= '$id_usuario'";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code'=>'LTE-001',
                'data'=> $r
            ];
        }else
        {
            $data =[
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
$app->post('/calificacion/almacen/actualizarCantidadAlmacen',function ()use($app){
    $helper = new helper();
    $conexion = new conexPGSeguridad();
    $token = $app->request()->post('token',null);
    if ($token != null){
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true){
            $json = $app->request->post('json',null);
            $parametros = json_decode($json);
            $id_almacen_calificacion = (isset($parametros->id_almacen_calificacion)) ? $parametros->id_almacen_calificacion : null;
            $total_cantidad_almacen_calificacion = (isset($parametros->total_cantidad_almacen_calificacion)) ? $parametros->total_cantidad_almacen_calificacion: null;
            $sql = "UPDATE minutas.almacen_calificacion
                    SET total_cantidad_almacen_calificacion='$total_cantidad_almacen_calificacion'
                    WHERE id_almacen_calificacion='$id_almacen_calificacion';";
            $conexion->consultaSimple($sql);
            $data = [
                'code'=>'LTE-001'
            ];
        }else
        {
            $data =[
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
$app->post('/calificacion/almacen/listarUsuariosAlmacen',function ()use($app){
    $helper = new helper();
    $conexion = new conexPGSeguridad();
    $token = $app->request()->post('token',null);
    if ($token != null){
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true){
            $sql = "select * from seguridad.usuario usu where usu.id_rol_fk_usuario in (3,5)";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code'=>'LTE-001',
                'data'=>$r
            ];
        }else
        {
            $data =[
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
$app->post('/calificacion/almacen/agregarStockUsuario',function ()use($app){
    $helper = new helper();
    $conexion = new conexPGSeguridad();
    $token = $app->request()->post('token',null);
    if ($token != null){
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true){
            $json = $app->request->post('json',null);
            $parametros = json_decode($json);
            $id_stock_almacen_calificacion = (isset($parametros->id_stock_almacen_calificacion)) ? $parametros->id_stock_almacen_calificacion : null;
            $fk_id_almacen_calificacion = (isset($parametros->fk_id_almacen_calificacion)) ? $parametros->fk_id_almacen_calificacion : null;
            $cantidad_stock = (isset($parametros->cantidad_stock)) ? $parametros->cantidad_stock : null;
            $fk_stock_usuario_almacen_usuario_id = (isset($parametros->fk_stock_usuario_almacen_usuario_id)) ? $parametros->fk_stock_usuario_almacen_usuario_id : null;
            $cantidad_menos_stock = (isset($parametros->cantidad_menos_stock)) ? $parametros->cantidad_menos_stock : null;
            $sql = "select ac.* from minutas.almacen_calificacion ac where ac.id_almacen_calificacion ='$fk_id_almacen_calificacion';";
            $r = $conexion->consultaComplejaNorAso($sql);
            if ($r != 0){
                $cantidadTotalStock = $r['total_cantidad_almacen_calificacion'];
                if ($cantidad_menos_stock<=$cantidadTotalStock ){
                    $nuevaCantidadAlmacen = $cantidadTotalStock-$cantidad_menos_stock;
                    $sql = "UPDATE minutas.almacen_calificacion
                            SET  total_cantidad_almacen_calificacion='$nuevaCantidadAlmacen'
                            WHERE id_almacen_calificacion='$fk_id_almacen_calificacion';";
                    $conexion->consultaSimple($sql);
                    $sql = "select suac.* from minutas.stock_usuario_almacen_calificacion suac 
                    where suac.fk_stock_usuario_almacen_usuario_id = '$fk_stock_usuario_almacen_usuario_id' and suac.fk_id_almacen_calificacion='$fk_id_almacen_calificacion'";
                    $r=$conexion->consultaComplejaNorAso($sql);
                    if ($r != 0){
                        $nuevaCantidadAlmacen = $r['cantidad_stock']+$cantidad_menos_stock;
                        $id_stock_almacen_calificacion= $r['id_stock_almacen_calificacion'];
                        $sql = "UPDATE minutas.stock_usuario_almacen_calificacion
                                SET  cantidad_stock='$nuevaCantidadAlmacen'
                                WHERE id_stock_almacen_calificacion='$id_stock_almacen_calificacion';";
                        $conexion->consultaSimple($sql);
                    }else
                    {
                        $sql = "INSERT INTO minutas.stock_usuario_almacen_calificacion(
                             fk_id_almacen_calificacion, cantidad_stock, fk_stock_usuario_almacen_usuario_id)
                                VALUES ( '$fk_id_almacen_calificacion', '$cantidad_menos_stock', '$fk_stock_usuario_almacen_usuario_id');";
                        $conexion->consultaSimple($sql);
                    }
                    $data = [
                        'code'=>'LTE-001'
                    ];
                }else
                {
                    $data = [
                        'code'=>'LTE-000',
                        'status'=>'error',
                        'msg'=>'Lo sentimos, la nueva cantidad supera la cantidad actual'
                    ];
                }

            }else
            {
                $data = [
                    'code'=>'LTE-000',
                    'status'=>'error',
                    'msg'=>'Lo sentimos algo sucedio, puede ser que ya no exista ese item en el almacen general'
                ];
            }
        }else
        {
            $data =[
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