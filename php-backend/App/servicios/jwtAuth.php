<?php

use Firebase\JWT\JWT;

class jwtAuth
{
    public $manager;
    public $key;
    public function __construct()
    {
        $this->key = 'B5170E46BC1412B394CC744F7AE54006C47D7F0A989E28EE7B6B3A761992F387A2806B2CC12B8AF22DFC554D07802FC7D01087C7BCCC88E8022A34F2728478C9';
    }
    public function signIn($usuario, $getHash = false)
    {
        $conexion = new conexPGSeguridad();
        $fecha_ingreso = date('Y-m-d H:i');
        $sql = "update seguridad.usuario set fecha_ultima_ingreso_usuario = '$fecha_ingreso' 
  where correo_usuario='$usuario[correo_usuario]' and documento_usuario ='$usuario[documento_usuario]'";
        $conexion->consultaSimple($sql);
        $sql ="select cabe.*,ro.descripcion_rol from seguridad.usuario usu join seguridad.rol ro on usu.id_rol_fk_usuario=ro.id_rol
        join seguridad.cabezera cabe on ro.id_rol=cabe.id_rol_fk_cabezera 
        where usu.correo_usuario='$usuario[correo_usuario]' and usu.documento_usuario ='$usuario[documento_usuario]' order by cabe.prioridad_cabezera asc;";
        $r = $conexion->consultaComplejaAso($sql);
        $permisos = array();
        $rolDescripcion ='';
        for ($i = 0; $i<count($r); $i++)
        {
            $id_cabezera=$r[$i]['id_cabezera'];
            $sql = "select me.*,ro.descripcion_rol from seguridad.usuario usu join seguridad.rol ro on usu.id_rol_fk_usuario=ro.id_rol
                    join seguridad.cabezera cabe on ro.id_rol=cabe.id_rol_fk_cabezera
                    join seguridad.submenu sub on cabe.id_cabezera=sub.id_cabezera_fk_submenu
                    join seguridad.menu me on me.id_menu=sub.id_menu_fk_submenu 
                    where cabe.id_cabezera='$id_cabezera' and usu.documento_usuario ='$usuario[documento_usuario]' order by sub.prioridad_submenu asc ;";
            $rolDescripcion = $r[$i]['descripcion_rol'];
            $r2 = $conexion->consultaComplejaAso($sql);
            array_push($permisos,[
                'id_cabezera'=>$id_cabezera,
                'descripcion_cabezera'=>$r[$i]['descripcion_cabezera'],
                'estado_cabezera'=>$r[$i]['estado_cabezera'],
                'subMenus'=>$r2
            ]);
        }
        $sql="select me.*,ro.descripcion_rol from seguridad.usuario usu join seguridad.rol ro on usu.id_rol_fk_usuario=ro.id_rol
                join seguridad.cabezera cabe on ro.id_rol=cabe.id_rol_fk_cabezera
                join seguridad.submenu sub on cabe.id_cabezera=sub.id_cabezera_fk_submenu
                join seguridad.menu me on me.id_menu=sub.id_menu_fk_submenu 
                where usu.correo_usuario='$usuario[correo_usuario]' and usu.documento_usuario ='$usuario[documento_usuario]';";
        $r = $conexion->consultaComplejaAso($sql);
        $token = array(
            'sub' => $usuario['id_usuario'],
            'documento' => $usuario['documento_usuario'],
            'nombre' => $usuario['nombre_usuario'],
            'apellido' => $usuario['apellido_usuario'],
            'correo' => $usuario['correo_usuario'],
            'telefono' => $usuario['telefono_usuario'],
            'direccion' => $usuario['direccion_usuario'],
            'id_usuario_joseluis' => $usuario['id_usuario_jeluis'],
            'rol' => $usuario['id_rol_fk_usuario'],
            'permisos'=>$r,
            'menu'=>$permisos,
            'fecha_creacion' => $usuario['fecha_creacion_usuario'],
            'ultimo_ingreso'=>$fecha_ingreso,
            'rol_descripcion' => $rolDescripcion,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60)
        );
        $jwt = JWT::encode($token, $this->key, 'HS512');
        $decoded = JWT::decode($jwt, $this->key, array('HS512'));

        if ($getHash)
            return $decoded;
        else
            return $jwt;
    }
    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS512'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (isset($decoded->sub))
            $auth = true;
        else
            $auth = false;

        if ($getIdentity == true)
            return $decoded;
        else
            return $auth;
    }
}