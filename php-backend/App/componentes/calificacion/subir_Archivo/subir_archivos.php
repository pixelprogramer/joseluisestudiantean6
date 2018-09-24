<?php

// SUBIR ARCHIVO RAR
$app->post('/calificacion/subirArchivoResultado', function () use ($app) {
    $helper = new helper();
    $conexion = new conexionNubeMysql();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $id = null;
            if ($json != null) {
                $usuarioToken = $helper->authCheck($token, true);
                $parametros = json_decode($json);
                $name = (isset($parametros->name)) ? $parametros->name : null;
                $sql = "select ar.* from archivos ar where ar.name = '$name'";
                $r = $conexion->consultaComplejaNorAso($sql);
                if ($r == 0) {
                    $description = (isset($parametros->description)) ? $parametros->description : null;
                    $fecha_hora_actual = date('Y-m-d');
                    $file = (isset($_FILES['upload'])) ? $_FILES['upload'] : null;
                    $nombreArchivo = time();
                   // pi_poMove('/upload-file/resultados/', $file, $nombreArchivo);
                    $nombre_completo = $nombreArchivo . '.' . pi_poExtenssion($file);
                    $tipo = "Tipo: " . pi_poExtenssion($file);
                    $size = 0;
                    $query = "INSERT INTO archivos (name, description, ruta, tipo, size, datecreate, usercreated) 
                            VALUES ('$name', '$description', '$nombre_completo', '$tipo',
                             '$size', '$fecha_hora_actual', '$usuarioToken->nombre')";
                    $conexion->consultaSimple($query);
                    $r = reporteEvidenciaRepositorio($name);
                    $tipo = 1;
                    //FTP
                    $connection = ftp_connect('138.128.182.138', '21');
                    $login = ftp_login($connection, 'root', 'ApoloSistemas_2016$');
                    $destination_file = "/filesjl/upload/".$nombreArchivo.'.'.pi_poExtenssion($file);
                    $source_file = $file['tmp_name'];
                    ftp_pasv($connection, true);
                    $upload = ftp_put($connection, $destination_file, $source_file, FTP_BINARY);
                } else {
                    $id = $r['id'];
                    $name = $r['name'];
                    $description = $r['description'];
                    $r = reporteEvidenciaRepositorio($name);
                    $tipo = 2;
                }

                $data = [
                    'code' => 'LTE-001',
                    'data' => [
                        'id' => $id,
                        'tipe' => $tipo,
                        'name' => $name,
                        'description' => $description,
                        'ruta' => $r
                    ]
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

$app->post('/calificacion/actualizarArchivoResultado', function () use ($app) {
    $helper = new helper();
    $conexion = new conexionNubeMysql();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            if ($json != null) {
                $usuarioToken = $helper->authCheck($token, true);
                $parametros = json_decode($json);
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $name = (isset($parametros->name)) ? $parametros->name : null;
                $sql = "select ar.* from archivos ar where ar.id = '$id'";
                $re = $conexion->consultaComplejaNorAso($sql);
                if ($re != 0) {
                    $id = $re['id'];
                    $description = (isset($parametros->description)) ? $parametros->description : null;
                    $fecha_hora_actual = date('Y-m-d');
                    $file = (isset($_FILES['upload'])) ? $_FILES['upload'] : null;
                    $nombreArchivo = time();
                    pi_poMove('/upload-file/resultados/', $file, $nombreArchivo);
                    $nombre_completo = $nombreArchivo . '.' . pi_poExtenssion($file);
                    $tipo = "Tipo: " . pi_poExtenssion($file);
                    $query = "update archivos set name='$name', description='$description',ruta='$nombre_completo',tipo='$tipo' where id = '$id';";
                    $conexion->consultaSimple($query);
                    $r = reporteEvidenciaRepositorio($name);
                    $data = [
                        'code' => 'LTE-001',
                        'data' => [
                            'tipe' => $tipo,
                            'name' => $name,
                            'description' => $description,
                            'ruta' => $r
                        ]
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, vulve a ingrsar al sistemas para terminar el proceso actual'
                    ];
                }

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

$app->post('/calificacion/reporteSubirArchivos', function () use ($app) {
    $helper = new helper();
    $conexion = new conexionNubeMysql();
    $token = $app->request->post('token', null);


    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            if ($json != null) {

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

function reporteEvidenciaRepositorio($json)
{

    class generarReporteSubirArchivo extends pixel_TCPDF
    {
        function generar($name)
        {
            $this->nuevaPagina('P', 'Letter', 'mm', 'Letter', true, 'UTF-8', false, false);
            $this->SetFont('', '', 11);
            $dane = $name;

            $fecha = date('Y-m-j');
            //$this->Image('logo.png', 10, 11, -1500);
            $this->SetXY(60, 20);
            $this->Cell(40, 10, 'CERTIFICADO DE ALMACENAMIENTO EN EL REPOSITORIO');
            //$this->SetFont('Arial', '', 9);
            $this->SetXY(10, 46);
            $this->Cell(40, 10, 'Para ingresar a los archivos virtuales de esta carpeta de análisis estadístico debe hacer los siguientes pasos:', 0, 1);

            $this->SetXY(10, 53);
            $this->Cell(40, 10, '1. Ingresar a la página de Los Tres Editores: https://www.lostreseditores.com/');
            $this->SetXY(10, 58);
            $this->Cell(40, 10, '2. En el menú principal de la página se encuentra la opción de productos. Dar clic para que se despliegue');
            $this->SetXY(10, 62);
            $this->Cell(40, 10, '   el siguiente menú:');
            $this->Image(__DIR__ . '../../../../public/imagenes_estandar/22.png', 35, 71, 150, 45, 'png', '');
            //$this->Image('22.png', 35, 71, -350);
            $this->SetXY(10, 120);
            $this->Cell(40, 10, '   Luego de haber desplegado el menú de productos dar clic en la opción RESULTADOS EN LÍNEA');
            $this->SetXY(10, 125);
            $this->Cell(40, 10, '3. Al momento de darle clic en la opción RESULTADOS EN LÍNEA aparece la siguiente plataforma:');
            $this->Image(__DIR__ . '../../../../public/imagenes_estandar/33.png', 37, 133, 150, 40, 'png', '');
            //$this->Image('33.png', 37, 133, -245);
            $this->SetXY(10, 176);
            $this->Cell(40, 10, '  En la casilla "escribe tu código de pedido:" debe escribir el siguiente codigo:');
            $this->SetXY(10, 182);
            $this->Cell(40, 10, '4. Despúes de colocar el código en el campo, se procede a dar clic en el botón CONSULTAR REPORTES.');
            $this->Image(__DIR__ . '../../../../public/imagenes_estandar/34.PNG', 37, 190, 150, 15, 'png', '');


            //$this->Image('34.png', 37, 190, -245);
            $this->SetXY(10, 205);
            $this->Cell(40, 10, '   la siguiente tabla con los datos del colegio que ya fue calificado. Para descargar los archivos dar');
            $this->SetXY(10, 209);
            $this->Cell(40, 10, '   clic donde dice descargar.');


            $this->SetXY(144, 179);
            $this->SetTextColor(255, 0, 0);
            $this->Cell(10, 4, $dane);
            //$this->SetFont('Arial', 'B', 9);
            $this->SetTextColor(0, 0, 0);
            $this->SetXY(60, 25);
            $this->Cell(110, 10, $fecha, 0, 0, 'C');
            $this->SetXY(10, 217);
            $this->Cell(40, 10, '   El repositorio de Los Tres Editores S.A.S. resguardará los archivos por un tiempo de seis meses luego');
            $this->SetXY(10, 220);
            $this->Cell(40, 10, '   de generado este certificado');

        }
    }

    $pdf = new generarReporteSubirArchivo();
    $pdf->generar($json);

    $route = __DIR__ . '../../../../public/pdf/';
    if (!file_exists($route))
        mkdir($route, 0, true);
    $pdf->Output($route . "reporte Repositorio.pdf", "F");
    return '/pdf/reporte Repositorio.pdf';
}