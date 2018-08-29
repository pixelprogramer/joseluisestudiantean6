<?php

$app->post('/prueba/holaMundo',function ()use($app){
    $json = $app->request->post('json',null);
    $parametros= json_decode($json);
    $id_usuario= (isset($parametros->descripcion_base_examen)) ? $parametros->descripcion_base_examen: null;
    echo $id_usuario;
});