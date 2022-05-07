<?php
require __DIR__ . '/config/BD_Connect.php';

ini_set('display_errors', 0);

class DB
{
    private $db;
    private $pdo;

    function __construct() {
        $this->db = new DB_Connect();
        $this->pdo = $this->db->createConnexicon();
    }

    function login(String $username, String $password):array{
        try{
            $data = array();
            $user['error']=true;

            $stmt = $this->pdo->prepare("SELECT e.nc AS 'username' , CONCAT(e.nomalu,' ',e.ap,' ',e.am) AS 'nombre','Estudiante' AS rol ,d.ID_DEPTO , '0' as ActiviadesActivas FROM estudiantes AS e INNER JOIN carreras AS c ON c.idcar = e.Idcar  INNER JOIN deptos AS d ON c.idDeptos = d.ID_DEPTO WHERE e.nc = :username && e.password = :password;");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            $contar=$stmt->rowCount();
            if($contar==1){
                $user = $stmt->fetch();
                $user['error']=false;

            }else{
                $stmt = $this->pdo->prepare("SELECT e.id_pers as 'username' ,e.nom_pers as 'nombre', e.Puesto as 'rol' ,d.ID_DEPTO ,(SELECT COUNT(*) FROM actividades as a WHERE a.idPersonalResponsable = e.id_pers) as ActiviadesActivas from empleado as e INNER JOIN deptos AS d ON e.id_depto = d.ID_DEPTO where e.id_pers = :username  && e.pwd = :password");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->execute();
                $contar=$stmt->rowCount();
                if($contar==1){
                    $user = $stmt->fetch();
                    $user['error']=false;
                }
            }
        
            $data['username']=$user['username'];
            $data['nombre']=$user['nombre'];
            $data['rol']=$user['rol'];
            $data['ID_DEPTO']=$user['ID_DEPTO'];
            $data['ActiviadesActivas']=$user['ActiviadesActivas'];
            $data['error']=$user['error'];
        }catch(Exception $e){
                    $data['error']=true;
        }finally{
            return $data;
        }
    }    

    function getAlumnos(){
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT es.nc as "ID", CONCAT(es.nomalu," ",es.ap," ",es.am) as "Nombre" , es.semestre as "semestre", c.nombcar as "nombre_car" FROM estudiantes as es INNER JOIN carreras as c ON es.Idcar = c.idcar ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $categorias = array();
                $datos = new stdClass;
                $datos->ID = $row['ID'];
                $datos->Nombre = $row['Nombre'];
                $datos->semestre = $row['semestre'];
                $datos->Carrera=$row['nombre_car'];
                $stmt2 = $this->pdo->prepare('SELECT c.nombre, (SELECT COUNT(*) as contador FROM registroactividades as r INNER JOIN actividades as a ON r.idactividad = a.idactividaactiva INNER JOIN catActividades as ca ON a.idcatalagoproyectos = ca.idCatActividades INNER JOIN categoria as c ON ca.idcategoria = c.idcategoria WHERE r.idrstudiante = :idAlumno AND r.estado = "Terminado" AND ca.idcategoria = c.idCategoria) as "Contador" FROM categoria as c; ');                    
                $stmt2->bindParam(":idAlumno", $datos->ID);
                $stmt2->execute();
                foreach ($stmt2 as $row2) {
                    $categoria = new stdClass;
                    $categoria->Nombre = $row2['nombre'];
                    $categoria->Contador = $row2['Contador'];
                    $categorias[]=$categoria;
                }
                $datos->categorias = $categorias;
                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
            $data['mensaje']=$e->getMessage();
        }finally{
            return $data;
        } 
    }

    public function getCarreras(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT c.idcar,c.nombcar,c.siglas,c.idDeptos,d.NOM_DEPTO FROM carreras as c INNER JOIN deptos as d on c.idDeptos = d.ID_DEPTO');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->idcar=$row['idcar'];
                $datos->nombrecar=$row['nombcar'];
                $datos->siglas=$row['siglas'];
                $datos->deptos=["id"=>$row['idDeptos'],"NOM_DEPTO"=>$row['NOM_DEPTO']];
                $data[] = $datos;
            } 
        }catch(Exception $e){
                    $data['error']=true;
                    $data['error_mensaje']=$e->getMessage();
        }finally{
            return $data;
        }
    }

    public function updateCarreras(String $idcar,String $nombcar,String $siglas,String $idDeptos): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE carreras SET nombcar = :nombcar , siglas = :siglas , idDeptos = :idDeptos WHERE idcar = :idcar ');
            $stmt->bindParam(":nombcar", $nombcar);
            $stmt->bindParam(":siglas", $siglas);
            $stmt->bindParam(":idDeptos", $idDeptos);
            $stmt->bindParam(":idcar", $idcar);
            $data["error"]=!$stmt->execute();
        }catch(Exception $e){
            $data['error']=true;
            $data["message"] = $e->getMessage();
        }finally{
            return $data;
        }    
    }

    public function getDepartemento(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * FROM deptos');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->id_depto=$row['ID_DEPTO'];
                $datos->nom_depto=$row['NOM_DEPTO'];
                $data[] = $datos;
            }
        }catch(Exception $e){
                $data['error']=true;
                $data['error_mensaje']=$e->getMessage();
        }finally{
            return $data;
        }    
    }

    public function getEmpleatosDepartamento(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * from deptos;  ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $jefedepartamento = array();
                $empleados = array();
                $datos = new stdClass;
                $datos->id_depto=$row['ID_DEPTO'];
                $datos->nom_depto=$row['NOM_DEPTO'];
                $stmt2 = $this->pdo->prepare('SELECT e.id_pers,e.nom_pers,e.puesto FROM empleado as e  WHERE e.puesto != "JefeDepartamento"  AND e.id_depto = :depto');
                $stmt2->bindParam(":depto", $row['ID_DEPTO']);
                $stmt2->execute();
                foreach ($stmt2 as $row2) {
                    $empleado = new stdClass;
                    $empleado->id_pers = $row2['id_pers'];
                    $empleado->nom_pers = $row2['nom_pers'];
                    $empleado->puesto = $row2['puesto'];
                    $empleados[]=$empleado;
                }
                $datos->empleados = $empleados;
                $stmt3 = $this->pdo->prepare('SELECT e.id_pers,e.nom_pers,e.puesto FROM empleado as e  WHERE e.puesto = "JefeDepartamento"  AND e.id_depto = :depto');
                $stmt3->bindParam(":depto", $row['ID_DEPTO']);
                $stmt3->execute();
                $registro = $stmt3->fetch(PDO::FETCH_ASSOC);                
                $empleado = new stdClass;                
                $empleado->id_pers = $registro['id_pers'];
                $empleado->nom_pers = $registro['nom_pers'];
                $empleado->puesto = $registro['puesto'];

                $datos->jefedepartamento = $empleado;
                $data[] = $datos;
            }
        }catch(Exception $e){
                $data['error']=true;
                $data['error_mensaje']=$e->getMessage();
        }finally{
            return $data;
        }    
    }

    public function getEmpleados(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT em.id_pers as "ID", em.nom_pers as "Nombre",(SELECT d.nom_depto FROM deptos as d WHERE d.id_depto=em.id_depto) as Departamento,IF(em.puesto="JefeDepartamento","Jefe de Departamento",em.puesto) as "puesto" from empleado as em;');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;

                $datos->id_pers = $row['ID'];
                $datos->nom_pers = $row['Nombre'];
                $datos->id_depto = $row['Departamento'];
                $datos->puesto = $row['puesto'];

                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
            $data['error_mensaje']=$e->getMessage();
        }finally{
            return $data;
        }    
    }

    public function  getEstudiantes(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT es.nc as "ID",CONCAT(es.nomalu," ",es.ap," ",es.am) as "Nombre" ,es.semestre, (SELECT c.nombcar FROM carreras as c where c.idcar = es.idcar) as "Carrera" FROM estudiantes as es; ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $categorias = array();
                $datos = new stdClass;
                $datos->ID = $row['ID'];
                $datos->Nombre = $row['Nombre'];
                $datos->semestre = $row['semestre'];
                $datos->Carrera = $row['Carrera'];
                $stmt2 = $this->pdo->prepare('SELECT c.nombre, (SELECT COUNT(*) as contador FROM registroactividades as r INNER JOIN actividades as a ON r.idactividad = a.idactividaactiva INNER JOIN catActividades as ca ON a.idcatalagoproyectos = ca.idCatActividades INNER JOIN categoria as c ON ca.idcategoria = c.idcategoria WHERE r.idrstudiante = :idAlumno AND r.estado = "Terminado" AND ca.idcategoria = c.idCategoria) as "Contador" FROM categoria as c; ');                    
                $stmt2->bindParam(":idAlumno", $datos->ID);
                $stmt2->execute();
                foreach ($stmt2 as $row2) {
                    $categoria = new stdClass;
                    $categoria->Nombre = $row2['nombre'];
                    $categoria->Contador = $row2['Contador'];
                    $categorias[]=$categoria;
                }
                $datos->categorias = $categorias;
                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
            $data['mensaje']=$e->getMessage();
        }finally{
            return $data;
        }    
    }

    public function  getCategoria(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * FROM categoria');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idcategoria = $row['idCategoria'];
                $datos->nombre = $row['nombre'];
                $datos->descripcion = $row['descripcion'];
                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }     
    }

    public function  insertCategorias(String $nombre,String $descripcion):array {
        try {

            $stmt = $this->pdo->prepare('INSERT INTO categoria(nombre,descripcion) value (:nombre,:descripcion) ');
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":descripcion", $descripcion);
            $data["error"]=!$stmt->execute();

        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  updateCategoria(String $idCategoria,String $nombre,String $descripcion):array {
        try {
            $stmt = $this->pdo->prepare('UPDATE categoria SET nombre = :nombre , descripcion = :descripcion WHERE idcategoria = :idCategoria');
            $stmt->bindParam(":idCategoria", $idCategoria);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":descripcion", $descripcion);
            $data["error"]=!$stmt->execute();

        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  getPeriodo(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * FROM periodo');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idperiodo = $row['idperiodo'];
                $datos->nombre = $row['nombre'];
                $datos->status=$row['estado'];
                $data[] = $datos;
            }

        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  getPeriodoActivos(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * FROM periodo WHERE estado = "ABIERTO" ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idperiodo = $row['idperiodo'];
                $datos->nombre = $row['nombre'];
                $datos->status=$row['estado'];
                $data[] = $datos;
            }

        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  insertPeriodo(String $nombre,String $status):array {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO periodo(nombre,estado) value (:nombre,:estado) ');
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":status", $status);
            $data["error"]=!$stmt->execute();
        }  catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  updatePeriodo(String $idperiodo,String $nombre,String $status):array {
        try {
            $stmt = $this->pdo->prepare('UPDATE periodo SET nombre = :nombre , estado = :status WHERE idperiodo = :idperiodo');
            $stmt->bindParam(":idperiodo", $idperiodo);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":status", $status);
            $data["error"]=!$stmt->execute();
        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  getCatalagoProyectos(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT ca.idCatActividades as "id",  (SELECT c.idCategoria FROM categoria as c WHERE c.idCategoria = ca.idcategoria) as "idcategoria", (SELECT c.nombre FROM categoria as c WHERE c.idCategoria = ca.idcategoria) as "categoria", ca.nombresproyecto as "nombre",   ca.filename as "filename",ca.numerocredito as "credito",ca.horassemanales as "horassemanales",ca.estado FROM catActividades as ca');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idactividad = $row['id'];
                $datos->categoria = ["id"=>$row['idcategoria'],"nombre"=>$row['categoria']];
                $datos->nombres_proyecto = $row['nombre'];
                $datos->credito = $row['credito'];
                $datos->horassemanales = $row['horassemanales'];
                $datos->estado = $row['estado'];
                $data[] = $datos;
            }
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }
    }

    public function  getCatalagoProyectosActivos(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT ca.idCatActividades as "id", (SELECT c.idCategoria FROM categoria as c WHERE c.idCategoria = ca.idcategoria) as "idcategoria", (SELECT c.nombre FROM categoria as c WHERE c.idCategoria = ca.idcategoria) as "categoria", ca.nombresproyecto as "nombre", ca.filename as "filename",ca.numerocredito as "credito",ca.horassemanales as "horassemanales",ca.estado FROM catActividades as ca WHERE ca.estado = "ACTIVO"; ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idactividad = $row['id'];
                $datos->categoria = ["id"=>$row['idcategoria'],"nombre"=>$row['categoria']];
                $datos->nombres_proyecto = $row['nombre'];
                $datos->credito = $row['credito'];
                $datos->horassemanales = $row['horassemanales'];
                $datos->estado = $row['estado'];
                $data[] = $datos;
            }
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }
    }

    public function  insertCatalagoProyectos(String $idCategoria, String $nombres_proyecto,String $filename,String $contentype, $oficioautorizacion, String $credito, String $horassemanales, String $status) {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('INSERT INTO catActividades ( idcategoria, nombresproyecto,filename,contentype, file, numerocredito, horassemanales,  estado ) VALUES (:idcategoria, :nombres_proyecto,:filename,:contentype, :oficioautorizacion, :credito, :horassemanales, :estatus )');
            $stmt->bindParam(":idcategoria", $idCategoria);
            $stmt->bindParam(":nombres_proyecto", $nombres_proyecto);
            $stmt->bindParam(":filename", $filename);
            $stmt->bindParam(":contentype", $contentype);
            $stmt->bindParam(":oficioautorizacion", $oficioautorizacion);
            $stmt->bindParam(":credito", $credito);
            $stmt->bindParam(":horassemanales", $horassemanales);
            $stmt->bindParam(":estatus", $status);
            $data['error']=!$stmt->execute();
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }
    } 

    public function  updateCatalagoProyecto(String $idCatActividades, String $estado) {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE catActividades SET  estado = :estado WHERE idCatActividades = :idCatActividades');
            $stmt->bindParam(":idCatActividades", $idCatActividades);
            $stmt->bindParam(":estado", $estado);
            $data['error']=!$stmt->execute();
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }
    } 

    public function  insertProyectoActivos(String $idCatalagoProyectos, String $idDeptos,String $idJefeDeptos,String $idPersonalResponsable, $horaInicio, String $horaFin, String $idPeriodo, String $fechaInicio,String $fechaCierre, String $numeroAlumnos, String $estado) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO actividades( idCatalagoProyectos, idDeptos, idJefeDeptos, idPersonalResponsable, horaInicio, horaFin, idPeriodo, fechaInicio, fechaCierre, numeroAlumnos, estado) VALUES (:idCatalagoProyectos, :idDeptos, :idJefeDeptos, :idPersonalResponsable, :horaInicio, :horaFin, :idPeriodo, :fechaInicio, :fechaCierre, :numeroAlumnos, :estado)');
            $stmt->bindParam(":idCatalagoProyectos", $idCatalagoProyectos);
            $stmt->bindParam(":idDeptos", $idDeptos);
            $stmt->bindParam(":idJefeDeptos", $idJefeDeptos);
            $stmt->bindParam(":idPersonalResponsable", $idPersonalResponsable);
            $stmt->bindParam(":horaInicio", $horaInicio);
            $stmt->bindParam(":horaFin", $horaFin);
            $stmt->bindParam(":idPeriodo", $idPeriodo);
            $stmt->bindParam(":fechaInicio", $fechaInicio);
            $stmt->bindParam(":fechaCierre", $fechaCierre);
            $stmt->bindParam(":numeroAlumnos", $numeroAlumnos);
            $stmt->bindParam(":estado", $estado);
        
            //$data["error"]=
            var_dump($stmt->execute());
            
        }  catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    public function  getProyectoActivos(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('Select A.idActividaActiva, cat.nombresproyecto , dep.NOM_DEPTO, jefe.nom_pers as "jefe",personal.nom_pers, A.horaInicio, A.horaFin, p.nombre as "periodo" , A.fechaInicio, A.fechaCierre, A.numeroAlumnos, A.estado from actividades as A INNER JOIN catActividades as cat ON cat.idCatActividades=A.idCatalagoProyectos INNER JOIN deptos as dep ON dep.ID_DEPTO=A.idDeptos INNER JOIN empleado as jefe ON jefe.id_pers=A.idJefeDeptos INNER JOIN empleado as personal ON personal.id_pers=A.idPersonalResponsable INNER JOIN periodo as p ON p.idperiodo = A.idPeriodo WHERE A.estado = "ACTIVO"; ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idActividaActiva= $row['idActividaActiva'];
                $datos->nombresproyecto= $row['nombresproyecto'];
                $datos->NOM_DEPTO= $row['NOM_DEPTO'];
                $datos->jefe= $row['jefe'];
                $datos->nom_pers= $row['nom_pers'];
                $datos->horaInicio= $row['horaInicio'];
                $datos->horaFin= $row['horaFin'];
                $datos->periodo= $row['periodo'];
                $datos->fechaInicio= $row['fechaInicio'];
                $datos->fechaCierre= $row['fechaCierre'];
                $datos->numeroAlumnos= $row['numeroAlumnos'];
                $datos->estado= $row['estado'];
                $data[] = $datos;
            }
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }

    }

    public function  getProyectoActivosInscripcion($iddepto): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('Select A.idActividaActiva, cat.nombresproyecto , dep.NOM_DEPTO, jefe.nom_pers as "jefe",personal.nom_pers, A.horaInicio, A.horaFin, p.nombre as "periodo" , A.fechaInicio, A.fechaCierre, A.numeroAlumnos, A.estado from actividades as A INNER JOIN catActividades as cat ON cat.idCatActividades=A.idCatalagoProyectos INNER JOIN deptos as dep ON dep.ID_DEPTO=A.idDeptos INNER JOIN empleado as jefe ON jefe.id_pers=A.idJefeDeptos INNER JOIN empleado as personal ON personal.id_pers=A.idPersonalResponsable INNER JOIN periodo as p ON p.idperiodo = A.idPeriodo WHERE A.estado = "ACTIVO" AND dep.ID_DEPTO = :iddepto; ');
            $stmt->bindParam(":iddepto", $iddepto);
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idActividaActiva= $row['idActividaActiva'];
                $datos->nombresproyecto= $row['nombresproyecto'];
                $datos->NOM_DEPTO= $row['NOM_DEPTO'];
                $datos->jefe= $row['jefe'];
                $datos->nom_pers= $row['nom_pers'];
                $datos->horaInicio= $row['horaInicio'];
                $datos->horaFin= $row['horaFin'];
                $datos->periodo= $row['periodo'];
                $datos->fechaInicio= $row['fechaInicio'];
                $datos->fechaCierre= $row['fechaCierre'];
                $datos->numeroAlumnos= $row['numeroAlumnos'];
                $datos->estado= $row['estado'];
                $data[] = $datos;
            }
        } catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }

    }
    

}


//   

//