<?php
$app->post('/distribuidor/listarColegios', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select sc.id,sc.dane, sc.description, sc.phone, cit.description ciudad, dep.description departamento, sc.address as direccion, cit.id as id_city
	from  schools sc 
		inner join (cities cit 
			INNER JOIN departments dep ON cit.id_departments = dep.id
		)ON sc.id_city = cit.id  order by sc.id asc limit 500 ";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/listarOrderTipe', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select * from order_type limit 500";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/listarDistribuidora', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select * from shipping limit 500";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/listarShippingType', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select * from shipping_type limit 500";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/listarCiudades', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select * from cities order by description asc;";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/filtrarColegio', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $filtro = $app->request->post('filtro', null);
            $sql = "select sc.id,sc.dane, sc.description, sc.phone, cit.description ciudad, dep.description departamento, sc.address as direccion, cit.id as id_city
	from  schools sc 
		inner join (cities cit 
			INNER JOIN departments dep ON cit.id_departments = dep.id
		)ON sc.id_city = cit.id where sc.description  like '%$filtro%' or sc.dane like '%$filtro%'  order by sc.id asc limit 500 ";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/filtrarCiudad', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $filtro = $app->request->post('filtro', null);
            $sql = "select * from cities where description like '%$filtro%';";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/nuevoPedido', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $json = $app->request->post('json', null);
            if ($json != null) {
                $fechaFianl = date('y-m-d');
                $arregloFlecha = explode('-', $fechaFianl);
                $fecha_creacion = date('Y-m-d H:i');
                $parametros = json_decode($json);
                $date_application = (isset($parametros->date_application)) ? $parametros->date_application : null;
                $id_city = (isset($parametros->id_city)) ? $parametros->id_city : null;
                $id_order_type = (isset($parametros->id_order_type)) ? $parametros->id_order_type : null;
                $id_school = (isset($parametros->id_school)) ? $parametros->id_school : null;
                $id_ship = (isset($parametros->id_ship)) ? $parametros->id_ship : null;
                $id_ship_type = (isset($parametros->id_ship_type)) ? $parametros->id_ship_type : null;
                $material = (isset($parametros->material)) ? $parametros->material : null;
                $ship_address = (isset($parametros->ship_address)) ? $parametros->ship_address : null;
                $ship_name = (isset($parametros->ship_name)) ? $parametros->ship_name : null;
                $ship_phone = (isset($parametros->ship_phone)) ? $parametros->ship_phone : null;
                $ship_to = (isset($parametros->ship_to)) ? $parametros->ship_to : null;
                $date_ship = (isset($parametros->date_ship)) ? $parametros->date_ship : null;
                $id_userperorder = (isset($parametros->id_userperorder)) ? $parametros->id_userperorder : null;
                $archivos = (isset($_FILES['archivo'])) ? $_FILES['archivo'] : null;
                if ($archivos != null) {
                    $r = pi_poMoveMultiple('/archivos/listas/', $archivos);
                    $contenido = [
                        'contenido' => $r
                    ];
                    $contenidoJson = json_encode($contenido);
                    $sql = "insert into orders (date_application,id_city,id_order_type,
                        id_school,id_ship,id_ship_type,
                        id_userperorder,material,ship_address,
                        ship_name,ship_phone,ship_to,state,id_seller,guia,contrato,created_at,facturado,date_ship,archivo_listas) values 
                        ('$date_application','$id_city','$id_order_type','$id_school','$id_ship','$id_ship_type',
                        '$id_userperorder','$material','$ship_address','$ship_name','$ship_phone','$ship_to','ALISTAMIENTO',
                        '$id_userperorder','NO',0,'$fecha_creacion','TRUE','$date_ship','$contenidoJson');";
                } else {
                    $sql = "insert into orders (date_application,id_city,id_order_type,
                        id_school,id_ship,id_ship_type,
                        id_userperorder,material,ship_address,
                        ship_name,ship_phone,ship_to,state,id_seller,guia,contrato,created_at,facturado,date_ship) values 
                        ('$date_application','$id_city','$id_order_type','$id_school','$id_ship','$id_ship_type',
                        '$id_userperorder','$material','$ship_address','$ship_name','$ship_phone','$ship_to','ALISTAMIENTO',
                        '$id_userperorder','NO',0,'$fecha_creacion','TRUE','$date_ship');";
                }
                $r = $conexion->consultaSimpleReturnId($sql);
                $codigo = $arregloFlecha[0] . $arregloFlecha[1] . $arregloFlecha[2] . $r;
                $sql = "update orders set code='$codigo' where id='$r'";
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001',
                    'data' => $r
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
$app->post('/distribuidor/listarProductos', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $sql = "select * from products";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/filtrarProducto', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $filtro = $app->request->post('filtro', null);
            $sql = "select pro.* from products pro where pro.code like '%$filtro%' or pro.description like '%$filtro%'";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/nuevoDetalle', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $json = $app->request->post('json', null);
            if ($json != null) {
                $fecha_creacion = date('Y-m-d H:i');
                $parametros = json_decode($json);
                $id_order = (isset($parametros->id_order)) ? $parametros->id_order : null;
                $id_product = (isset($parametros->id_product)) ? $parametros->id_product : null;
                $description = (isset($parametros->description)) ? $parametros->description : null;
                $quantity_order = (isset($parametros->quantity_order)) ? $parametros->quantity_order : null;
                $sql = "insert into order_details (id_order,id_product,description,quantity_order) values 
                        ('$id_order','$id_product','$description','$quantity_order');";
                $r = $conexion->consultaSimpleReturnId($sql);
                $sql = "update orders set state='DISTRIBUIDOR' where id='$id_order'";
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001',
                    'data' => $r
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
$app->post('/distribuidor/listarDetallesPedido', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $idPeido = $app->request->post('id', null);
            $sql = "select orded.*, pr.description as descripcion_producto from orders orde 
                inner join order_details orded on orde.id=orded.id_order 
                inner join products pr on pr.id=orded.id_product  where orded.id_order='$idPeido';";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/listarPedidosDistribuidor', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $usuarioToken = $helper->authCheck($token, true);
            $sql = "select orde.*,sch.description as colegio,sch.dane,sch.phone as telefono_colegio,
                    ci.description as ciudad, ship.description as transportadora,
                    shipt.description as tipo_envio, ordt.description as tipo_orden,
                    cis.id as id_ciudad_colegio, cis.description as ciudad_colegio, 
                    sch.address as direccion_colegio, sch.phone as telefono_colegio
                    from orders orde inner join schools sch on orde.id_school=sch.id 
                    inner join shipping ship on orde.id_ship=ship.id 
                    inner join shipping_type shipt on orde.id_ship_type=shipt.id
                    inner join cities ci on ci.id=orde.id_city
                    inner join order_type ordt on orde.id_order_type=ordt.id 
                    inner join cities cis on cis.id=sch.id_city
                     where orde.id_userperorder = '$usuarioToken->id_usuario_joseluis' 
                    or orde.id_seller ='$usuarioToken->id_usuario_joseluis' order by orde.created_at desc";

            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/actualizarPedido', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $json = $app->request->post('json', null);
            if ($json != null) {
                $fechaFianl = date('y-m-d');
                $arregloFlecha = explode('-', $fechaFianl);
                $fecha_actualizacion = date('Y-m-d H:i');
                $parametros = json_decode($json);
                $date_application = (isset($parametros->date_application)) ? $parametros->date_application : null;
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $sql = "select * from orders orde where orde.id='$id' and orde.state='DISTRIBUIDOR'";
                $r = $conexion->consultaComplejaAso($sql);
                if ($r != 0) {
                    $id_city = (isset($parametros->id_city)) ? $parametros->id_city : null;
                    $id_order_type = (isset($parametros->id_order_type)) ? $parametros->id_order_type : null;
                    $id_school = (isset($parametros->id_school)) ? $parametros->id_school : null;
                    $id_ship = (isset($parametros->id_ship)) ? $parametros->id_ship : null;
                    $id_ship_type = (isset($parametros->id_ship_type)) ? $parametros->id_ship_type : null;
                    $material = (isset($parametros->material)) ? $parametros->material : null;
                    $ship_address = (isset($parametros->ship_address)) ? $parametros->ship_address : null;
                    $ship_name = (isset($parametros->ship_name)) ? $parametros->ship_name : null;
                    $ship_phone = (isset($parametros->ship_phone)) ? $parametros->ship_phone : null;
                    $ship_to = (isset($parametros->ship_to)) ? $parametros->ship_to : null;
                    $id_userperorder = (isset($parametros->id_userperorder)) ? $parametros->id_userperorder : null;
                    $date_ship = (isset($parametros->date_ship)) ? $parametros->date_ship : null;
                    $sql = "update orders orde set  orde.id_city='$id_city', 
                        orde.id_order_type='$id_order_type', orde.id_school='$id_school', orde.id_ship='$id_ship',
                        orde.id_ship_type='$id_ship_type',orde.material='$material',orde.ship_address='$ship_address',
                        orde.ship_name='$ship_name',orde.ship_phone='$ship_phone',orde.ship_to='$ship_to', 
                        orde.date_application='$date_application', orde.updated_at='$fecha_actualizacion', date_ship='$date_ship'
                         where orde.id = '$id'";
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'state' => 'error',
                        'msg' => 'Lo sentimos, Este pedido ya se encuentra procesado.'
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
$app->post('/distribuidor/editarDetalle', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $json = $app->request->post('json', null);
            if ($json != null) {
                $fecha_creacion = date('Y-m-d H:i');
                $parametros = json_decode($json);
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $id_order = (isset($parametros->id_order)) ? $parametros->id_order : null;
                $id_product = (isset($parametros->id_product)) ? $parametros->id_product : null;
                $description = (isset($parametros->description)) ? $parametros->description : null;
                $quantity_order = (isset($parametros->quantity_order)) ? $parametros->quantity_order : null;
                $sql = "update order_details ordt set ordt.description='$description', ordt.quantity_order='$quantity_order' where ordt.id='$id';";
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-001',
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
$app->post('/distribuidor/eliminarDetallePedido', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $json = $app->request->post('json', null);
            if ($json != null) {
                $parametros = json_decode($json);
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $sql = "delete from order_details where id='$id';";

                $conexion->consultaSimple($sql);
                $id_order = (isset($parametros->id_order)) ? $parametros->id_order : null;
                $sql = "select * from order_details ordt where ordt.id_order='$id_order';";
                $r = $conexion->consultaComplejaAso($sql);
                if ($r == 0) {
                    $sql = "update orders orde set orde.state='ALISTAMIENTO' where orde.id ='$id_order';";
                    $conexion->consultaSimple($sql);
                }
                $data = [
                    'code' => 'LTE-001',
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
$app->post('/distribuidor/nuevoArchivos', function () use ($app) {
    $helper = new helper();
    $json = $app->request->post('json', null);
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionToken = $helper->authCheck($token);
            if ($validacionToken == true) {
                $conexion = new conexMsql();
                $usuarioToken = $helper->authCheck($token, true);
                $parametros = json_decode($json);
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $id_archivo = $app->request->post('id_archivo', null);
                $sql = "select orde.* from orders orde where orde.id='$id';";
                $r = $conexion->consultaComplejaNorAso($sql);
                $archivos = (isset($_FILES['archivo'])) ? $_FILES['archivo'] : null;
                $fecha_actualizacion = date('Y-m-d H:i:s');
                $r2 = pi_poMoveMultiple('/archivos/recurso/', $archivos);
                if ($r != 0) {
                    $contenido = json_decode($r['archivo_listas'], true);

                    if ($contenido != null)
                    {
                        for ($j = 0; $j < count($r2); $j++) {
                            array_push($contenido['contenido'], $r2[$j]);
                        }

                        for ($j = 0; $j < count($contenido['contenido']); $j++) {
                            $contenido['contenido'][$j]['id'] = $j;
                        }
                    }else
                    {
                        $contenido = [
                            'contenido' => $r2
                        ];
                    }
                    $nuevo_contenido = json_encode($contenido);
                    $sql = "update orders set archivo_listas='$nuevo_contenido' where id='$id';";
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-003'
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
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/distribuidor/recargarPedidoSeleccionado', function () use ($app) {
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $conexion = new conexMsql();
            $usuarioToken = $helper->authCheck($token, true);
            $id = $app->request->post('id',null);
            $sql = "select orde.*,sch.description as colegio,sch.dane,sch.phone as telefono_colegio,
                    ci.description as ciudad, ship.description as transportadora,
                    shipt.description as tipo_envio, ordt.description as tipo_orden,
                    cis.id as id_ciudad_colegio, cis.description as ciudad_colegio, 
                    sch.address as direccion_colegio, sch.phone as telefono_colegio
                    from orders orde inner join schools sch on orde.id_school=sch.id 
                    inner join shipping ship on orde.id_ship=ship.id 
                    inner join shipping_type shipt on orde.id_ship_type=shipt.id
                    inner join cities ci on ci.id=orde.id_city
                    inner join order_type ordt on orde.id_order_type=ordt.id 
                    inner join cities cis on cis.id=sch.id_city
                     where orde.id ='$id' and (orde.id_userperorder = '$usuarioToken->id_usuario_joseluis' 
                    or orde.id_seller ='$usuarioToken->id_usuario_joseluis')  order by orde.created_at desc";
            $r = $conexion->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
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
$app->post('/distribuidor/eliminarArchivo', function () use ($app) {
    $helper = new helper();
    $json = $app->request->post('json', null);
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionToken = $helper->authCheck($token);
            if ($validacionToken == true) {
                $conexion = new conexMsql();
                $usuarioToken = $helper->authCheck($token, true);
                $parametros = json_decode($json);
                $id = (isset($parametros->id)) ? $parametros->id : null;
                $id_archivo = $app->request->post('id_archivo', null);
                $sql = "select orde.* from orders orde where orde.id='$id';";
                $r = $conexion->consultaComplejaNorAso($sql);
                if ($r != 0) {
                    $contenido = json_decode($r['archivo_listas'], true);
                    for ($i = 0; $i < count($contenido['contenido']); $i++) {
                        if ($contenido['contenido'][$i]['id'] == $id_archivo) {
                            //pi_poEliminarArchivo($contenido['contenido'][$i]['rute']);
                            $contenido['contenido'][$i]['estado']='false';
                        }
                    }
                    $nuevo_contenido = json_encode($contenido);
                    $sql = "update orders set archivo_listas='$nuevo_contenido' where id='$id';";
                    $conexion->consultaSimple($sql);
                    $data = [
                        'code' => 'LTE-001'
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-003'
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
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});