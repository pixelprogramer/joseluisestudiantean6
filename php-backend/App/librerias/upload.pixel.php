<?php
function pi_poMove($rute = '', $file = null, $nameFile = null)
{
    if ($file != null) {
        $data = '';
        $carpetas = explode('/', $rute);
        $rutaCompleta = 'App/public/';
        if (count($carpetas) > 1) {
            for ($i = 1; $i < count($carpetas); $i++) {
                if (file_exists($rutaCompleta . $carpetas[$i])) {
                    $rutaCompleta = $rutaCompleta . $carpetas[$i] . '/';
                } else {
                    mkdir($rutaCompleta . '/' . $carpetas[$i], 0777, true);
                    $rutaCompleta = $rutaCompleta . $carpetas[$i] . '/';
                }
            }
            if ($nameFile != '') {
                $extension = explode('.', $file["name"]);
                move_uploaded_file($file["tmp_name"], $rutaCompleta . $nameFile . '.' . end($extension));
            } else {
                move_uploaded_file($file["tmp_name"], $rutaCompleta . $file["name"]);
            }
        } else {
            die('Error al definir la ruta- ruta null ejemplo de ruta: "/carpeta/archivo"');
        }
    } else {
        die('El archivo es requerido ');
    }
}

function pi_poMoveMultiple($rute = '', $file = null)
{
    $listRutas = array();
    if ($file != null) {
        $data = '';
        $carpetas = explode('/', $rute);
        $rutaCompleta = 'App/public/';
        if (count($carpetas) > 1) {
            for ($i = 1; $i < count($carpetas); $i++) {
                if (file_exists($rutaCompleta . $carpetas[$i])) {
                    $rutaCompleta = $rutaCompleta . $carpetas[$i] . '/';
                } else {
                    mkdir($rutaCompleta . '/' . $carpetas[$i], 0777, true);
                    $rutaCompleta = $rutaCompleta . $carpetas[$i] . '/';
                }
            }
            for ($i = 0; $i < count($file["tmp_name"]); $i++) {
                $extension = explode('.', $file["name"][$i]);
                if (/*strtolower(end($extension)) == 'png'
                    || strtolower(end($extension)) == 'jpg'
                    || strtolower(end($extension)) == 'gif'
                    || strtolower(end($extension)) == 'pptx'
                    || strtolower(end($extension)) == 'ppt'
                    || strtolower(end($extension)) == 'txt' || */
                    strtolower(end($extension)) == 'xls'
                    || strtolower(end($extension)) == 'xlsx'
                    || strtolower(end($extension)) == 'docx'
                    || strtolower(end($extension)) == 'docm'
                    || strtolower(end($extension)) == 'pdf'
                ) {

                    $nameFile = time() . rand(0, 100) . $i;
                    move_uploaded_file($file["tmp_name"][$i], $rutaCompleta . $nameFile . '.' . end($extension));
                    array_push($listRutas, ['name' => $file["name"][$i], 'rute' => $rute . $nameFile . '.' . end($extension), 'tipo' => strtolower(end($extension)), 'id' => $i, 'estado' => 'true']);
                }
            }
            return $listRutas;
        } else {
            die('Error al definir la ruta- ruta null ejemplo de ruta: "/carpeta/archivo"');
        }
    } else {
        die('El archivo es requerido ');
    }
}

function pi_poNombreArchivo($file = null)
{
    if ($file != null) {
        return $file["name"];
    } else {
        die('El archivo es requerido');
    }

}

function pi_poExtenssion($file)
{
    $extension = explode('.', $file["name"]);
    return end($extension);
}

function pi_poEliminarArchivo($ruta)
{

    $rutaRaiz = "App/public" . $ruta;
    if (file_exists($rutaRaiz)) {
        unlink("App/public" . $ruta);
    }
}
