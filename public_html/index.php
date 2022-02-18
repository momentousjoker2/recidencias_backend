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

    $app->get('/', function (Request $request, Response $response) {
        return echoResponse(200, json_encode(array("data" => "Ruta equivocada",), JSON_FORCE_OBJECT), $response);
    });

    $app->post('/', function (Request $request, Response $response) {
        $body = $request->getBody();
        //$contents = json_decode(file_get_contents('php://input'), true);
        var_dump($_POST);
        var_dump($_FILES);
        var_dump(file_get_contents('php://input'));
        //var_dump(file_get_contents('php://input'));
        return echoResponse(200, json_encode(array("data" => "Ruta equivocada",), JSON_FORCE_OBJECT), $response);
    });

    //Login del sistema TERMINADO
    $app->get('/login', function (Request $request, Response $response) {
            try {
            $data = array();
            $bd = new DB();
            if (verifyRequiredParams($_REQUEST, 2)) {
                $usuario = $_REQUEST['usuario'];
                $password = $_REQUEST['password'];
                $data=$bd->login($usuario, $password);
                if($data['error']){
                    $data = array();
                    $data["error"] = true;
                    $data["message"] = "Usuarios no encontrado";
                    $data["code"] = "2001";
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
            return echoResponse(200, json_encode($data, JSON_FORCE_OBJECT), $response);
        }
    });

    $app->group('/Catalagos', function (RouteCollectorProxy $group) use ($app) {
        //Carreras
        //Obtener todos las carreras TERMINADO
        $group->get('/carreras', function (Request $request, Response $response) {
            try {
                $data = array();
                $bd = new DB();
                $data=$bd->getCatalagoCarreras();
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

        //Departamento
        //Obtener todos los departamtnos Terminado
        $group->get('/departamentos', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data=$bd->getCatalagoDepartemento();
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

        //Empleados
        //Obtener todos los empleados
        $group->get('/empleados', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data = $bd->getCatalagoEmpleados();
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

        //Cambiar contrase;a de empleados Terminado
        $group->post('/empleados_password', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data = array();

                if (verifyRequiredParams($_REQUEST, 2)) {
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

        //Empleados
        //Obtener todos los estudiantes Terminado
        $group->get('/estudiantes', function (Request $request, Response $response) {
            try {            
                $bd = new DB();
                $data=$bd->getCatalagoEstudiantes();
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
                if (verifyRequiredParams($_REQUEST, 2)) {
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

        //Categoria
        //Obtener todos las categorias Terminado
        $group->get('/categoria', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data=$bd->getCatalagoCategoria();
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

        //Insertar Categorias Terminado
        $group->post('/categoria', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data = array();
                if (verifyRequiredParams($_REQUEST, 2)) {
                    $nombre = $_REQUEST['nombre'];
                    $description = $_REQUEST['description'];
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

        //Actualizar Categorias Terminado
        $group->post('/categoria_update', function (Request $request, Response $response){
            try {
                if (verifyRequiredParams($_REQUEST, 3)) {
                    $bd = new DB();
                    $id = $_REQUEST['id'];
                    $nombre = $_REQUEST['nombre'];
                    $description = $_REQUEST['description'];
                    $data=$bd->updateCategoria($id,$nombre, $description);
            }
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

        //Categoria
        //Obtener todos las tipoProyecto Terminado
        $group->get('/tipoProyecto', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data=$bd->getCatalagoTipoProyecto();
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

        //Insertar tipoProyecto Terminado
        $group->post('/tipoProyecto', function (Request $request, Response $response) {

            try {
                $bd = new DB();
                $data = array();
                if (verifyRequiredParams($_REQUEST, 3)) {
                    $idCategoria = $_REQUEST['idCategoria'];
                    $nombre = $_REQUEST['nombre'];
                    $description = $_REQUEST['description'];
                    $data=$bd->insertTipoProyecto($idCategoria,$nombre, $description);
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

        //Actualizar tipoProyecto Terminado
        $group->post('/tipoProyecto_update', function (Request $request, Response $response){
            try {
                $bd = new DB();
                $data = array();
                if (verifyRequiredParams($_REQUEST, 4)) {
                    $idtipoproyecto = $_REQUEST['idtipoproyecto'];
                    $idCategoria = $_REQUEST['idCategoria'];
                    $nombretipo = $_REQUEST['nombre'];
                    $descripcion = $_REQUEST['descripcion'];
                    $data=$bd->updateTipoProyecto($idtipoproyecto,$idCategoria,$nombretipo, $descripcion);
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

        //Obtener periodo Terminado
        $group->get('/periodo', function (Request $request, Response $response) {
            try {
                $bd = new DB();
                $data=$bd->getCatalagoPeriodo();
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

        //Insertar Periodo Terminado
        $group->post('/periodo', function (Request $request, Response $response) {
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

    });

    $app->group('/Movimientos', function (RouteCollectorProxy $group) use ($app) {

        $group->get('/Catalagos_Proyecto', function (Request $request, Response $response) {
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





    });











    /* 


    $app->group('/Actividades', function (RouteCollectorProxy $group) use ($app) {
        $group->post('/insert', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            
            $IdDepartamento = $_REQUEST['IdDepartamento'];
            $idJefeDepartamento = $_REQUEST['idJefeDepartamento'];
            $IdPersonalResponsable = $_REQUEST['IdPersonalResponsable'];
            $IdTipoProyecto = $_REQUEST['IdTipoProyecto'];
            $Nombre_Proyecto = $_REQUEST['Nombre_Proyecto'];
            $HorarioInicio = $_REQUEST['HorarioInicio'];
            $HorarioFin = $_REQUEST['HorarioFin'];
            $Periodo = $_REQUEST['Periodo'];
            $FechaInicio = $_REQUEST['FechaInicio'];
            $FechaCierre = $_REQUEST['FechaCierre'];
            $Creditos = $_REQUEST['Creditos'];
            $HorasSemanales = $_REQUEST['HorasSemanales'];
            $noAlumnos = $_REQUEST['noAlumnos'];
            $Estatus = $_REQUEST['Estatus'];
            $OficioAutorizacion="";

            try {
                $request=$bd->insertActividad($IdDepartamento,$idJefeDepartamento,$IdPersonalResponsable,$IdTipoProyecto,$Nombre_Proyecto,$HorarioInicio,$HorarioFin,$Periodo,$OficioAutorizacion,$FechaInicio,$FechaCierre,$Creditos,$HorasSemanales,$noAlumnos,$Estatus);
                return echoResponse(200, json_encode($request, JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                return echoResponse(200, json_encode($e->getMessage(), JSON_FORCE_OBJECT), $response);
            } finally {
                unset($bd);
            }

        });

        $group->get('/All', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getAllActividades()), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });
    });



















    $app->group('/Solicitud', function (RouteCollectorProxy $group) use ($app) {
        $group->post('/insert', function (Request $request, Response $response, array $args) {
        });

        $group->get('/get', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getAllSolicitudes()), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });

        $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getSolicitud($args['id'])), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });
    });

    $app->group('/Registar', function (RouteCollectorProxy $group) use ($app) {
        $group->post('/insert', function (Request $request, Response $response, array $args) {
        });
        $group->get('/get', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getAllRegistar()), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });

        $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getRegistar($args['id'])), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });
    });

    $app->group('/Calificacion', function (RouteCollectorProxy $group) use ($app) {
        $group->get('/insert', function (Request $request, Response $response, array $args) {
        });

        $group->get('/get', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getAllCalificacion()), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });

        $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
            $bd = new DB();
            try {
                return echoResponse(200, json_encode(array("data" => $bd->getCalificacion($args['id'])), JSON_FORCE_OBJECT), $response);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            } finally {
                unset($bd);
            }
        });
    });
    */

    //Funciones auxiliares
    /**
     * Compueba si al consumidor de la api tiene las credenciales necesarias
     * @param array $hedear Hedear de la peticion http del cual se extrae los atributos el host y el Authorization
     * @return array Regresa un arreglo llamado data el cual espesifica si contiene errores y cual el codigo de este mismo
     */


    function verifyRequiredParams(array $parametros, int $cont): bool
    {
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

    function echoResponse(int $status_code, string $data, Response $response): Response
    {

        $response->getBody()->write($data);
        return $response->withStatus($status_code)->withHeader('Content-Type', 'application/json');
    }


    $app->run();
