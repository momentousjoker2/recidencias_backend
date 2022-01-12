<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With");
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/BD.php';

$requests = array();

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) {

    $autenticacion = authenticate(apache_request_headers());

    return echoResponse(200, json_encode(array("data" => "Ruta equivocada",), JSON_FORCE_OBJECT), $response, $autenticacion);
});
$app->post('/login', function (Request $request, Response $response) {
        $data= array();
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();

        if(verifyRequiredParams($_POST,2)){
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
        


            $type = $bd->isTypeUser($usuario);
            if ($type == 'Estudiante'){
                $data = $bd->getLoginEstudiantes($usuario,$password);
            }else if($type == 'Empleado'){
                $data = $bd->getLoginEstudiantes($usuario,$password);
            }else{
                $data["error"] = true;
                $data["message"] = "Usuario no encontrado";
                $data["code"] = "2005";
            }

        }else{
            $data["error"] = true;
            $data["message"] = "Datos no aceptados";
            $data["code"] = "2004";
        }
        
        //var_dump($array);

        return echoResponse(200, json_encode($data, JSON_FORCE_OBJECT), $response, $autenticacion);   
    });



$app->group('/Catalagos', function (RouteCollectorProxy $group) use ($app) {
    $group->get('/carreras', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllCarreras()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/departamentos', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllDepartamentos()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/empleados', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllEmpleados()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/empleados/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getEmpleado($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/estudiantes', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllEstudiantes()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
    $group->get('/estudiantes/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getEstudiante($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->post('/tipo_Proyecto', function (Request $request, Response $response, array $args) {
    });

    $group->get('/tipo_Proyecto', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllTipoProyecto()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/tipo_Proyecto/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getTipoProyecto($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
});

$app->group('/Actividades', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/insert', function (Request $request, Response $response, array $args) {
    });

    $group->get('/get', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllActividades()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
});

$app->group('/Solicitud', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/insert', function (Request $request, Response $response, array $args) {
    });

    $group->get('/get', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllSolicitudes()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getSolicitud($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
});

$app->group('/Registar', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/insert', function (Request $request, Response $response, array $args) {
    });
    $group->get('/get', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllRegistar()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getRegistar($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
});

$app->group('/Calificacion', function (RouteCollectorProxy $group) use ($app) {
    $group->get('/insert', function (Request $request, Response $response, array $args) {
    });

    $group->get('/get', function (Request $request, Response $response) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getAllCalificacion()), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });

    $group->get('/get/{id:[0-9]}', function (Request $request, Response $response, array $args) {
        $autenticacion = authenticate(apache_request_headers());
        $bd = new DB();
        try {
            return echoResponse(200, json_encode(array("data" => $bd->getCalificacion($args['id'])), JSON_FORCE_OBJECT), $response, $autenticacion);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        } finally {
            unset($bd);
            unset($autenticacion);
        }
    });
});


//Funciones auxiliares
/**
 * Compueba si al consumidor de la api tiene las credenciales necesarias
 * @param array $hedear Hedear de la peticion http del cual se extrae los atributos el host y el Authorization
 * @return array Regresa un arreglo llamado data el cual espesifica si contiene errores y cual el codigo de este mismo
 */
function authenticate(array $hedear): array {
    $data = array();
    if (isset($hedear['Host'])) {
        $bd = new DB();
        if ($bd->isValidHost($hedear['Host'])) {
            if (isset($hedear['Authorization'])) {
                if ($bd->isValidApiKey($hedear['Authorization'])) {
                    $data["error"] = false;
                    return $data;
                } else {
                    $data["error"] = true;
                    $data["message"] = "Acesso negado";
                    $data["code"] = "2003";
                    return $data;
                }
            } else {
                $data["error"] = true;
                $data["message"] = "Acesso negado";
                $data["code"] = "2002";
                return $data;
            }
        } else {
            $data["error"] = true;
            $data["message"] = "Acesso negado";
            $data["code"] = "2001";
            return $data;
        }
    } else {
        $data["error"] = true;
        $data["message"] = "Acesso negado";
        $data["code"] = "2000";
        return $data;
    }
}


function verifyRequiredParams(array $parametros,int $cont): bool {
    $data = array();
    foreach ($parametros as $field) {
        if(isset($field)){
            if(ctype_print($field)){
                if(!empty($field)){
                    $data[]=$field;
                }
            }
        }
    }

    if(count($data)==$cont)
        return true;
    else
        return false;
}

function echoResponse(int $status_code, string $data, Response $response, array $autheticacion): Response {

    if ($autheticacion['error']) {
        $response->getBody()->write(json_encode($autheticacion, JSON_FORCE_OBJECT));
        $response->withStatus(401);
    } else {

        $response->getBody()->write($data);
    }

    return $response->withStatus($status_code)->withHeader('Content-Type', 'application/json');
}


$app->run();