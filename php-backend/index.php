<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS') {
    die();
}

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$app = new \Slim\Slim();
$yaml = new \Symfony\Component\Yaml\Yaml();

define("SPECIALCONSTANT", true);
//elementos
require 'App/librerias/conexion.php';
require 'App/servicios/jwtAuth.php';
require 'App/servicios/helper.php';
require 'App/librerias/upload.pixel.php';
require 'App/librerias/email/email.pixel.php';
require 'App/librerias/email/plantillas.pixel.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/IReader.php';
require 'App/librerias/tcpdf/tecnickcom/tcpdf/tcpdf.php';
require 'App/librerias/tcpdf/pixelGraficas/pixel_graficas.php';
require 'App/librerias/tcpdf/pixelGraficas/pixel_configuracionInicial.php';


//Componentes
require 'App/componentes/traladoUsuarios.php';
require 'App/componentes/crearGrupo.php';
require 'App/componentes/pruebas.php';
require 'App/componentes/pantallas/comercial.php';
require 'App/componentes/pantallas/logistica.php';
require 'App/componentes/pantallas/calificacion.php';
require 'App/componentes/pantallas/premarcado.php';
require 'App/componentes/calificacion/unificacionEstudiantes.php';
require 'App/componentes/usuario.php';
require 'App/componentes/administradorSistemas/rol.php';
require 'App/componentes/administradorSistemas/menu.php';
require 'App/componentes/minutas/categoria.php';
require 'App/componentes/minutas/acciones.php';
require 'App/componentes/minutas/sub_categoria.php';
require 'App/componentes/minutas/registro_minutas.php';
require 'App/componentes/siscalificacion/colegios.php';
require 'App/componentes/distribuidor/nuevoPedido.php';
require 'App/componentes/calificacion/indicadoresCalificacion.php';
require 'App/componentes/calificacion/almacen.php';
require 'App/componentes/comercial/premarcadovscalificado.php';
require 'App/componentes/calificacion/subir_Archivo/subir_archivos.php';
$app->run();