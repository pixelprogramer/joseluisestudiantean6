<?php

// SUBIR ARCHIVO RAR
$app->post('/uploader-file', function ()use($app){

    $conexion = new conexionNubeMysql();

    $file = (isset($_FILES['upload'])) ? $_FILES['upload'] : null;
    $nombreArchivo = time();
    pi_poMove('/upload-file/resultados/', $file, $nombreArchivo);

    $nombre_completo = $nombreArchivo . '.' .pi_poExtenssion($file);
    $tipo = "application/octet-stream";
    $size = 4;



$json = $app->request->post('json', null);
$parametros = json_decode($json);

$json_name = (isset($parametros->name)) ? $parametros->name : null;
$json_description = (isset($parametros->description)) ? $parametros->name : null;
$fecha_hora_actual = date('Y-m-d');
$token = $app->request->post('token', null);

    // ---------INSERT--------

    $query = "INSERT INTO archivos (name, description, ruta, tipo, size, datecreate, usercreated) VALUES ('$json_name', '$json_description', '$nombre_completo', '$tipo', '$siz
e', '$fecha_hora_actual', '$token')";

    $conexion->consultaSimple($query);

});