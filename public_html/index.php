    <?php

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Routing\RouteCollectorProxy;
    use Slim\Factory\AppFactory;

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../src/BD.php';

    header('Access-Control-Allow-Origin: *');

    $requests = array();

    $app = AppFactory::create();

    $app->addErrorMiddleware(true, true, true);

    //Login del sistema 
    $app->get('/login', function (Request $request, Response $response) {
            try {
            $data = array();
            $bd = new DB();
                $usuario = $_REQUEST['usuario'];
                $password = $_REQUEST['password'];
                $data=$bd->login($usuario, $password);
                if($data['error']){
                    $data = array();
                    $data["error"] = true;
                    $data["message"] = "Usuarios no encontrado";
                    $data["code"] = "2001";
                }


        } catch (Exception $e) {
                $data=array();
                $data["error"] = true;
                $data["message"] = $e->getMessage();
                $data["code"] = "2003";
        } finally {
            unset($bd);
            return echoResponse(200, json_encode($data, JSON_FORCE_OBJECT), $response);
        }
    });

    $app->group('/catalagos', function (RouteCollectorProxy $catalagos) use ($app) {
        $catalagos->group('/alumnos', function (RouteCollectorProxy $alumnos) use ($app) {

            $alumnos->get('/', function (Request $request, Response $response, array $args) {
                try {            
                    $bd = new DB();
                    $data=$bd->getAlumnos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });
        });

        $catalagos->group('/carreras', function (RouteCollectorProxy $carreras) use ($app) {

            $carreras->get('/', function (Request $request, Response $response) {
                try {
                    $data = array();
                    $bd = new DB();
                    $data=$bd->getCarreras();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $carreras->post('/', function (Request $request, Response $response) {
                try {
                    $data = array();
                    $bd = new DB();
                    $idcar= $_REQUEST['idcar'];
                    $nombcar= $_REQUEST['nombrecar'];
                    $siglas= $_REQUEST['siglas'];
                    $idDeptos= $_REQUEST['idDeptos'];
                    $data=$bd->updateCarreras($idcar,$nombcar,$siglas,$idDeptos);
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });
        });

        $catalagos->group('/categoria', function (RouteCollectorProxy $categoria) use ($app) {

            $categoria->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getCategoria();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $categoria->post('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = array();
                    $nombre = $_REQUEST['nombre'];
                    $description = $_REQUEST['descripcion'];
                    $data=$bd->insertCategorias($nombre, $description);
                        if($data['error']){
                            $data = array();
                            $data["error"] = true;
                            $data["message"] = "No update";
                            $data["code"] = "2004";
                        }else{
                            $data = array();
                            $data["error"] = false;
                    }
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $categoria->post('/update', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $id = $_REQUEST['id'];
                    $nombre = $_REQUEST['nombre'];
                    $description = $_REQUEST['description'];
                    $data=$bd->updateCategoria($id,$nombre, $description);
            
                }catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode($data, JSON_FORCE_OBJECT), $response);
                }
            });
        });    

        $catalagos->group('/departamentos', function (RouteCollectorProxy $departamentos) use ($app) {

            $departamentos->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getDepartemento();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });
        });   

        $catalagos->group('/empleados', function (RouteCollectorProxy $empleados) use ($app) {
            $empleados->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = $bd->getEmpleados();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });  

            $empleados->get('/EmpleadosDepartamentales', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getEmpleatosDepartamento();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });  


        });

        $catalagos->group('/periodo', function (RouteCollectorProxy $periodo) use ($app) {

            $periodo->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getPeriodo();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $periodo->post('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = array();
                        if (verifyRequiredParams($_REQUEST, 2)) {
                            $Nombre = $_REQUEST['Nombre'];
                            $Status = $_REQUEST['Status'];
                            $data=$bd->insertPeriodo($Nombre, $Status);
                            if($data['error']){
                                $data = array();
                                $data["error"] = true;
                                $data["message"] = "No update";
                                $data["code"] = "2004";
                            }else{
                                $data = array();
                                $data["error"] = false;
                            }
                        }else {
                            $data["error"] = true;
                            $data["message"] = "Datos no aceptados";
                            $data["code"] = "2000";
                    }
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $periodo->post('/update', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getCatalagoProyectos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $periodo->get('/Activas', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getPeriodoActivos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });
        }); 

    });

    $app->group('/movimientos', function (RouteCollectorProxy $movimientos) use ($app) {
        $movimientos->group('/catalagos_proyecto', function (RouteCollectorProxy $Catalagos_Proyecto) use ($app) {
            $Catalagos_Proyecto->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getCatalagoProyectos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $Catalagos_Proyecto->post('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = array();
                    $idcategoria=$_REQUEST['idcategoria'];
                    $nombres_proyecto=$_REQUEST['nombres_proyecto'];
                    $credito=$_REQUEST['credito'];
                    $horassemanales=$_REQUEST['horassemanales'];
                    $estatus=$_REQUEST['estado'];

                    $binario_nombre_temporal=$_FILES['oficioautorizacion']['tmp_name'] ;
                    $binario_contenido = (file_get_contents($binario_nombre_temporal));
                    $filename=$_FILES['oficioautorizacion']['name'];
                    $typefile=$_FILES['oficioautorizacion']['type'];


                    $data=$bd->insertCatalagoProyectos($idcategoria, $nombres_proyecto,$filename,$typefile, $binario_contenido, $credito, $horassemanales, $estatus);
                    if($data['error']){
                            $data["error"] = true;
                            $data["message"] = "No update";
                            $data["code"] = "2004";
                        }else{
                            $data = array();
                            $data["error"] = false;
                        }
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }    
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            });

            $Catalagos_Proyecto->post('/update', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = array();
                    $idcategoria=$_REQUEST['idactividad'];

                    $estatus=$_REQUEST['estado'];


                    $data=$bd->updateCatalagoProyecto($idcategoria,$estatus);
                    if($data['error']){
                            $data["error"] = true;
                            $data["message"] = "No update";
                            $data["code"] = "2004";
                        }else{
                            $data = array();
                            $data["error"] = false;
                        }
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }    
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            });

            $Catalagos_Proyecto->get('/Activos', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getCatalagoProyectosActivos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });
        });
        
        $movimientos->group('/proyectos_activos', function (RouteCollectorProxy $proyectos_activos) use ($app) {
            $proyectos_activos->get('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data=$bd->getProyectoActivos();
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });

            $proyectos_activos->get('/forDeptos', function (Request $request, Response $response) {
                try {
                    var_dump($_REQUEST);
                    $iddepto=$_REQUEST['iddepto'];
                    $bd = new DB();
                    $data=$bd->getProyectoActivosInscripcion($iddepto);
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }
            });



            $proyectos_activos->post('/', function (Request $request, Response $response) {
                try {
                    $bd = new DB();
                    $data = array();


                    $idCatalagoProyectos =$_REQUEST['idCatalagoProyectos'];
                    $idDeptos =$_REQUEST['idDeptos'];
                    $idJefeDeptos =$_REQUEST['idJefeDeptos'];
                    $idPersonalResponsable =$_REQUEST['idPersonalResponsable'];
                    $horaInicio =$_REQUEST['horaInicio'];
                    $horaFin =$_REQUEST['horaFin'];
                    $idPeriodo =$_REQUEST['idPeriodo'];
                    $fechaInicio =$_REQUEST['fechaInicio'];
                    $fechaCierre =$_REQUEST['fechaCierre'];
                    $numeroAlumnos =$_REQUEST['numeroAlumnos'];
                    $estado =$_REQUEST['estado'];
                    $data=$bd->insertProyectoActivos($idCatalagoProyectos,$idDeptos,$idJefeDeptos,$idPersonalResponsable, $horaInicio,$horaFin,$idPeriodo,$fechaInicio, $fechaCierre,$numeroAlumnos,$estado);
                    if($data['error']){
                            $data["error"] = true;
                            $data["message"] = "No insert";
                            $data["code"] = "2004";
                        }else{
                            $data = array();
                            $data["error"] = false;
                        }
                } catch (Exception $e) {
                    $data=array();
                    $data["error"] = true;
                    $data["message"] = $e->getMessage();
                    $data["code"] = "2003";
                } finally {
                    unset($bd);
                    return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
                }    
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            });
        });
    });

    //Funciones auxiliares
    /**
     * Compueba si al consumidor de la api tiene las credenciales necesarias
     * @param array $hedear Hedear de la peticion http del cual se extrae los atributos el host y el Authorization
     * @return array Regresa un arreglo llamado data el cual espesifica si contiene errores y cual el codigo de este mismo
     */
    function verifyRequiredParams(array $parametros, int $cont): bool{
        $data = array();
        foreach ($parametros as $field) {
            if (isset($field)) {
                if (ctype_print($field)) {
                    if (!empty($field)) {
                        $data[] = $field;
                    }
                }
            }
        }
        if (count($data) == $cont)
            return true;
        else
            return false;
    }

    function echoResponse(int $status_code, string $data, Response $response): Response{

        $response->getBody()->write($data);
        return $response->withStatus($status_code)->withHeader('Content-Type', 'application/json');
    }


    $app->run();


/* 
    $app->group('/Movimientos', function (RouteCollectorProxy $group) use ($app) {

        //Insertar Periodo Terminado
        $group

        $group->get('/Alta_Proyectos', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data=$bd->getAltaProyectos();
            } catch (Exception $e) {
                $data=array();
                $data["error"] = true;
                $data["message"] = $e->getMessage();
                $data["code"] = "2003";
            } finally {
                unset($bd);
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            }
        });
    }); 

    $app->group('/Catalagos', function (RouteCollectorProxy $group) use ($app) {

        //Cambiar contrase;a de empleados VERIFICAR AQUI
        $group->post('/empleados_password', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data = array();

                    $iduser = $_REQUEST['iduser'];
                    $newPassword = $_REQUEST['newpassword'];

                    $data=$bd->updatePasswordEmpleados($iduser, $newPassword);
                    if($data['error']){
                        $data = array();
                        $data["error"] = true;
                        $data["message"] = "No update";
                        $data["code"] = "2004";
                    }else{
                        $data = array();
                        $data["error"] = false;
                    }
            } catch (Exception $e) {
                $data=array();
                $data["error"] = true;
                $data["message"] = $e->getMessage();
                $data["code"] = "2003";
            } finally {
                unset($bd);
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            }
        });

        //Cambiar contrase;a de estudiantes Terminado
        $group->post('/estudiantes_password', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data = array();
                    $iduser = $_REQUEST['iduser'];
                    $newPassword = $_REQUEST['newpassword'];
                    $data=$bd->updatePasswordEstudiantes($iduser, $newPassword);
                    if($data['error']){
                        $data = array();
                        $data["error"] = true;
                        $data["message"] = "No update";
                        $data["code"] = "2004";
                    }else{
                        $data = array();
                        $data["error"] = false;
                    }

            } catch (Exception $e) {
                $data=array();
                $data["error"] = true;
                $data["message"] = $e->getMessage();
                $data["code"] = "2003";
            } finally {
                unset($bd);
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            }
        });


        //Update periodo Terminado
        $group->post('/periodo_update', function (Request $request, Response $response){
            try {
                $bd = new DB();
                $data = array();
                if (verifyRequiredParams($_REQUEST, 3)) {
                    $id = $_REQUEST['id'];
                    $Nombre = $_REQUEST['Nombre'];
                    $Status = $_REQUEST['Status'];
                    $data=$bd->updatePeriodo($id,$Nombre, $Status);
                    if($data['error']){
                        $data = array();
                        $data["error"] = true;
                        $data["message"] = "No update";
                        $data["code"] = "2004";
                    }else{
                        $data = array();
                        $data["error"] = false;
                    }
                }else {
                    $data["error"] = true;
                    $data["message"] = "Datos no aceptados";
                    $data["code"] = "2000";
                }
            } catch (Exception $e) {
                $data=array();
                $data["error"] = true;
                $data["message"] = $e->getMessage();
                $data["code"] = "2003";
            } finally {
                unset($bd);
                return echoResponse(200, json_encode(array("data" => $data), JSON_FORCE_OBJECT), $response);
            }
        });

    }); */