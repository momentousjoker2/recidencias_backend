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

    //Login Terminado 
    function login(String $username, String $password):array{
        try{
            $data = array();
            $user['error']=false;

            $stmt = $this->pdo->prepare("SELECT e.nc AS 'username' , CONCAT(e.nomalu,' ',e.ap,' ',e.am) AS 'nombre','Estudiante' AS rol ,d.ID_DEPTO  FROM estudiantes AS e INNER JOIN carreras AS c ON c.idcar = e.Idcar  INNER JOIN deptos AS d ON c.idDeptos = d.ID_DEPTO WHERE e.nc = :username && e.password = :password;");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            $contar=$stmt->rowCount();
            if($contar==1){
                $user = $stmt->fetch();
                $user['error']=false;

            }else{
                $stmt = $this->pdo->prepare("SELECT e.id_pers as 'username' ,e.nom_pers as 'nombre', e.Puesto  as 'rol' ,d.ID_DEPTO  from empleado as e INNER JOIN deptos AS d ON e.id_depto = d.ID_DEPTO where e.id_pers = :username  && e.pwd = :password");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->execute();
                $contar=$stmt->rowCount();
                if($contar==1){
                    $user = $stmt->fetch();
                    $user['error']=false;
                }else{
                    $user['error']=true;
                }
            }
        
            $data['username']=$user['username'];
            $data['nombre']=$user['nombre'];
            $data['rol']=$user['rol'];
            $data['ID_DEPTO']=$user['ID_DEPTO'];
            $data['error']=$user['error'];
        }catch(Exception $e){
                    $data['error']=true;
        }finally{
            return $data;
        }
    }

    //Carreras Terminadas 
    public function getCatalagoCarreras(): array {
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

    public function updateCatalagoCarreras(String $idcar,String $nombcar,String $siglas,String $idDeptos): array {
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
    //Departamentos Terminados 
    public function getCatalagoDepartemento(): array {
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
        //Departamentos Terminados 
    public function getJefesDepartamentos(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT d.*,e.nom_pers,e.id_pers FROM deptos as d INNER JOIN empleado as e on d.ID_DEPTO = e.id_depto WHERE e.puesto = "JefeDepartamento"; ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->id_depto=$row['ID_DEPTO'];
                $datos->nom_depto=$row['NOM_DEPTO'];
                $datos->id_pers=$row['id_pers'];
                $datos->nom_pers=$row['nom_pers'];
                $data[] = $datos;
            }
        }catch(Exception $e){
                $data['error']=true;
                $data['error_mensaje']=$e->getMessage();
        }finally{
            return $data;
        }    
    }


    //Empleados Terminado 
    public function getCatalagoEmpleados(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT em.id_pers as "ID", em.nom_pers as "Nombre",(SELECT d.nom_depto FROM deptos as d WHERE d.id_depto=em.id_depto) as Departamento,em.puesto as "puesto" from empleado as em; ');
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

    //Update Password empleados Termiandos 
    public function updatePasswordEmpleados($ID,$password): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE empleado SET pwd=:pwd WHERE id_pers =:ID_PERS ');
            $stmt->bindParam(":pwd", $password);
            $stmt->bindParam(":ID_PERS", $ID);
            $data["error"]=!$stmt->execute();
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }    
    }

        //Estudiantes Termiandos 
    public function  getCatalagoEstudiantes(): array {
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

    //Update Password Estudiantes Termiandos 
    public function updatePasswordEstudiantes($ID,$password): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE estudiantes SET password=:password WHERE nc =:nc ');
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":nc", $ID);
            $stmt->execute();
            $data["error"]=!$stmt->execute();
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }     
    }

    //Categorias Terminadas probar
    public function  getCatalagoCategoria(): array {
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

    //Insert a categorias terminado
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
    //update a categorias terminado
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

    //categoria probar
    public function  getCatalagoPeriodo(): array {
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

    // insertar periodo probar
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
    
    //update a periodo terminado
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

    //categoria probar
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

    //categoria probar
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

    public function openfile($idCatalago) {
        try{
            $stmt = $this->pdo->prepare('SELECT file FROM catActividades WHERE idCatActividades = :idCatActividades');
            $stmt->bindParam(":idCatActividades", $idCatActividades);
            $stmt->execute();
            foreach ($stmt as $row) {
                $result=$row['file'];
            }
        } catch (PDOException $e) {
        }finally{
            return $result;
        }
    }

    public function getAltaProyectos():array{
        try{
            $data = array();
            $stmt = $this->pdo->prepare("SELECT a.idactivida, (SELECT d.id_depto from departamentos as d WHERE d.id_depto = a.iddepartamento) as 'id_departamento' , (SELECT d.nom_depto from departamentos as d WHERE d.id_depto = a.iddepartamento) as 'nombre_departamento', (SELECT e.id_pers from empleado as e WHERE e.id_pers = a.idjefedepartemnto) as 'id_jefedepartamento', (SELECT e.nom_pers from empleado as e WHERE e.id_pers = a.idjefedepartemnto) as 'nombre_jefedepartamento', (SELECT e.id_pers from empleado as e WHERE e.id_pers = a.idpersonalresponsable) as 'id_responsable', (SELECT e.nom_pers from empleado as e WHERE e.id_pers = a.idpersonalresponsable) as 'nombre_responsable', a.horainicio, a.horafin, (SELECT p.idperiodo FROM periodo as p WHERE p.idperiodo = a.idperiodo) as 'id_periodo', (SELECT p.nombre FROM periodo as p WHERE p.idperiodo = a.idperiodo) as 'nombre_periodo', a.fechainicio, a.fechacierre, a.numeroalumnos, a.estado FROM actividades as a");
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idactivida = $row['idactivida'];
                $datos->departemento = ["id_departamento"=>$row['id_departamento'],"nombre_departamento"=>$row['nombre_departamento']];
                $datos->jefedepartamento = ["id_jefedepartamento"=>$row['id_jefedepartamento'],"nombre_jefedepartamento"=>$row['nombre_jefedepartamento']];
                $datos->responsable = ["id_responsable"=>$row['id_responsable'],"nombre_responsable"=>$row['nombre_responsable']];
                $datos->horainicio = $row['horainicio'];
                $datos->horafin = $row['horafin'];
                $datos->periodo = ["id_periodo"=>$row['id_periodo'],"nombre_periodo"=>$row['nombre_periodo']];
                $datos->fechainicio = $row['fechainicio'];
                $datos->fechacierre = $row['fechacierre'];
                $datos->numeroalumnos = $row['numeroalumnos'];
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
    
    public function insertAltaProyecto(String $iddepartamento, String $idjefedepartemnto, String $idpersonalresponsable, String $horainicio, String $horafin, String $idperiodo, String $fechainicio, String $fechacierre, String $numeroalumnos, String $estado):array{
        try{
            $stmt = $this->pdo->prepare("INSERT INTO actividades(iddepartamento, idjefedepartemnto, idpersonalresponsable, horainicio, horafin, idperiodo, fechainicio, fechacierre, numeroalumnos, estado) VALUES (:iddepartamento, :idjefedepartemnto, :idpersonalresponsable, :horainicio, :horafin, :idperiodo, :fechainicio, :fechacierre, :numeroalumnos, :estado)");
            $stmt->bindParam(":iddepartamento", $iddepartamento);
            $stmt->bindParam(":idjefedepartemnto", $idjefedepartemnto);
            $stmt->bindParam(":idpersonalresponsable", $idpersonalresponsable);
            $stmt->bindParam(":horainicio", $horainicio);
            $stmt->bindParam(":horafin", $horafin);
            $stmt->bindParam(":idperiodo", $idperiodo);
            $stmt->bindParam(":fechainicio", $fechainicio);
            $stmt->bindParam(":fechacierre", $fechacierre);
            $stmt->bindParam(":numeroalumnos", $numeroalumnos);
            $stmt->bindParam(":estado", $estado);


        }catch (PDOException $e) {
            $data["error"]=true;
            $data["error_message"]=$e->getMessage();
        }finally{
            return $data;
        }

    }











    /*  public function insertActividad (int $iddepartamento,int $idjefepersonal,int $idpersonal,int $idtipoproyecto, String $nombre,String $horarioinicio,String $horariofinal,String $periodo,String $oficioautorizacion,DateTime $fechainicio,DateTime $fechacierre, int $creditos,int $horassemanales,int $noalumnos,String $estatus):bool{
        try {
            $stmt = 
            $this->pdo->prepare
            ('INSERT INTO Actividades 
            (iddepartamento,idjefepersonal,idpersonal,idtipoproyecto,nombre,horarioinicio,horariofinal,periodo,oficioautorizacion,fechainicio,fechacierre,creditos,horassemanales,noalumnos,estatus) 
            value
            (:iddepartamento,:idjefepersonal,:idpersonal,:idtipoproyecto,:nombre,:horarioInicio,:horariofinal,:periodo,:oficioautorizacion,:fechainicio,:fechacierre,:creditos,:horassemanales,:noalumnos,:estatus) ');
            $stmt->bindParam(":iddepartamento", $iddepartamento);
            $stmt->bindParam(":idjefepersonal", $idjefepersonal);
            $stmt->bindParam(":idpersonal", $idpersonal);
            $stmt->bindParam(":idtipoproyecto", $idtipoproyecto);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":horarioInicio", $horarioInicio);
            $stmt->bindParam(":horariofinal", $horariofinal);
            $stmt->bindParam(":periodo", $periodo);
            $stmt->bindParam(":oficioautorizacion", $oficioautorizacion);
            $stmt->bindParam(":fechainicio", $fechainicio);
            $stmt->bindParam(":fechacierre", $fechacierre);
            $stmt->bindParam(":creditos", $creditos);
            $stmt->bindParam(":horas", $horassemanales);
            $stmt->bindParam(":noalumnos", $noalumnos);
            $stmt->bindParam(":estatus", $estatus);
            $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }




































    public function insertCarrera(): bool {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM Carreras');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new Carreras();
                $itm->setIdCar($row['idcar']);
                $itm->setNombreCar($row['nombrecar']);
                $itm->setSiglas($row['siglas']);
                $this->array[] = $itm;
            }

            $stmt = $this->pdoITCG->prepare('SELECT * FROM Carreras');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new Carreras();
                $itm->setIdCar($row['idcar']);
                $itm->setNombreCar($row['nombrecar']);
                $itm->setSiglas($row['siglas']);
                if(!in_array($itm,$this->array))
                {
                    $this->arrayITCG[] = $itm;
                }
            }

            foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Carreras(idcar,nombrecar,siglas) values (:idcar,:nombrecar,:siglas)');
                $stmt->bindParam(":idcar", $row . getIdCar());
                $stmt->bindParam(":nombrecar", $row . getNombreCar());
                $stmt->bindParam(":siglas", $row .getSiglas());
                $stmt->execute();
            } 
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }


    public function insertDepartamentos():bool {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM Deptos');
            $stmt->execute();
            foreach ($stmt as $row) 
            {
                $itm = new departamentos();
                $itm->setIDDEPTO($row['id_depto']);
                $itm->setNOMDEPTO($row['nom_depto']);
                $this->array[] = $itm;
            }

            $stmt = $this->pdoITCG->prepare('SELECT * FROM Deptos');
            $stmt->execute();
            foreach ($stmt as $row) 
            {
                $itm = new departamentos();
                $itm->setIDDEPTO($row['id_depto']);
                $itm->setNOMDEPTO($row['nom_depto']);
                if(!in_array($itm,$this->array))
                {
                    $this->arrayITCG[] = $itm;
                }
            }

        foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Carreras(id_depto,nom_depto) values (:id_depto,:nom_depto)');
                $stmt->bindParam(":id_depto", $row.getIDDEPTO());
                $stmt->bindParam(":nom_depto", $row.getNOMDEPTO());
                $stmt->execute();
            } 
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }



    public function getEmpleado(int $id): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Empleado where id_pers = :id');
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new empleados();
            $itm->setIDPERS($row['id_pers']);
            $itm->setNOMPERS($row['nom_pers']);
            $itm->setIDDEPTO($row['id_depto']);
            $itm->setPwd($row['pwd']);
            $itm->setFirmaDigital($row['firmadigital']);
            $itm->setPuesto($row['puesto']);
            $this->array[] = $itm->getJson();
        }
        return $this->array;
    }

    public function insertEmpleado():bool {
        try {
        
            $stmt = $this->pdo->prepare('SELECT * FROM Empleado');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new empleados();
                $itm->setIDPERS($row['id_pers']);
                $itm->setNOMPERS($row['nom_pers']);
                $itm->setIDDEPTO($row['id_depto']);
                $itm->setPwd($row['pwd']);
                $itm->setFirmaDigital($row['firmadigital']);
                $itm->setPuesto($row['puesto']);
                $this->array[] = $itm;
            }

            $stmt = $this->pdoITCG->prepare('SELECT * FROM Empleado');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new empleados();
                $itm->setIDPERS($row['id_pers']);
                $itm->setNOMPERS($row['nom_pers']);
                $itm->setIDDEPTO($row['id_depto']);
                $itm->setPwd($row['pwd']);
                $itm->setFirmaDigital($row['firmadigital']);
                $itm->setPuesto($row['puesto']);
                if(!in_array($itm,$this->array))
                {
                    $this->arrayITCG[] = $itm;
                }
            }
    
            foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Empleado(id_pers,nom_pers,id_depto,pwd,firmadigital,puesto) values (:id_pers,:nom_pers,:id_depto,:pwd,:firmadigital,:puesto)');
                $stmt->bindParam(":id_pers", $row.getIDPERS());
                $stmt->bindParam(":nom_pers", $row.getNOMPERS());            
                $stmt->bindParam(":id_depto", $row.getIDDEPTO());
                $stmt->bindParam(":pwd", $row.getPwd());            
                $stmt->bindParam(":firmadigital", $row.getFirmaDigital());
                $stmt->bindParam(":puesto", $row.getPuesto());
                $stmt->execute();
            }
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }



    public function  getEstudiante(int $nc): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Estudiantes where nc = :nc');
        $stmt->bindParam(":nc", $nc);
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new estudiantes();
            $itm->setNc($row['nc']);
            $itm->setAp($row['ap']);
            $itm->setAm($row['am']);
            $itm->setNomalu($row['nomalu']);
            $itm->setIdCar($row['idcar']);
            $itm->setSemestre($row['semestre']);
            $itm->setFirmaDigital($row['firmadigital']);
            $itm->setPassword($row['password']);
            $this->array[] = $itm->getJson();
        }

        return $this->array;
    }

    public function insertEstudiantes():bool {
        try {
        
            $stmt = $this->pdo->prepare('SELECT * FROM Estudiantes');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new estudiantes();
                $itm->setNc($row['nc']);
                $itm->setAp($row['ap']);
                $itm->setAm($row['am']);
                $itm->setNomalu($row['nomalu']);
                $itm->setIdCar($row['idcar']);
                $itm->setSemestre($row['semestre']);
                $itm->setFirmaDigital($row['firmadigital']);
                $itm->setPassword($row['password']);
                $this->array[] = $itm;
            }

            $stmt = $this->pdoITCG->prepare('SELECT * FROM Estudiantes');
            $stmt->execute();
            foreach ($stmt as $row) {
                $itm = new estudiantes();
                $itm->setNc($row['nc']);
                $itm->setAp($row['ap']);
                $itm->setAm($row['am']);
                $itm->setNomalu($row['nomalu']);
                $itm->setIdCar($row['idcar']);
                $itm->setSemestre($row['semestre']);
                $itm->setFirmaDigital($row['firmadigital']);
                $itm->setPassword($row['password']);
                $this->array[] = $itm;
                if(!in_array($itm,$this->array))
                {
                    $this->arrayITCG[] = $itm;
                }
            }

            foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Empleado(nc,ap,am,nomalu,idcar,semestre,firmadigital,password) values (:nc,:ap,:am,:nomalu,:idcar,:semestre,:firmadigital,:password)');
                $stmt->bindParam(":nc", $row.getNc());
                $stmt->bindParam(":ap", $row.getAp());            
                $stmt->bindParam(":am", $row.getAm());
                $stmt->bindParam(":nomalu", $row.getNomalu());            
                $stmt->bindParam(":idcar", $row.getIdCar());
                $stmt->bindParam(":semestre", $row.getSemestre());
                $stmt->bindParam(":firmadigital", $row.getFirmaDigital());
                $stmt->bindParam(":password", $row.getPassword());
                $stmt->execute();
            } 
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }



    public function  getTipoProyecto(int $id): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Tipo_Proyecto where idtipoproyecto = :id');
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Tipo_Proyecto();
            $itm->setIdTipoProyecto($row['idtipoproyecto']);
            $itm->setNombreTipo($row['nombretipo']);
            $itm->setDescripcion($row['descripcion']);
            $this->array[] = $itm->getJson();
        }

        return $this->array;
    }



    public function  getAllActividades(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Actividades');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Actividades();
            $itm->setIdActividad($row['idactividad']);
            $itm->setIdDepartamento($row['iddepartamento']);
            $itm->setIdJefePersonal($row['idjefepersonal']);
            $itm->setIdPersonal($row['idpersonal']);
            $itm->setIdTipoProyecto($row['idtipoproyecto']);
            $itm->setNombre($row['nombre']);
            $itm->setHorario($row['horario']);
            $itm->setPeriodo($row['periodo']);
            $itm->setOficioAutorizacion($row['oficioautorizacion']);
            $itm->setFechaInicio($row['fechainicio']);
            $itm->setFechaCierre($row['fechacierre']);
            $itm->setCreditos($row['creditos']);
            $itm->setHoras($row['horas']);
            $itm->setNoAlumnos($row['noalumnos']);
            $itm->setEstatus($row['estatus']);
            $this->array[] = $itm->getJson();
        }
        return $this->array;
    }

    public function  getActividade(int $idactividad): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Actividades where idactividad = :idactividad');
        $stmt->bindParam(":idactividad", $idactividad);
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Actividades();
            $itm->setIdActividad($row['idactividad']);
            $itm->setIdDepartamento($row['iddepartamento']);
            $itm->setIdJefePersonal($row['idjefepersonal']);
            $itm->setIdPersonal($row['idpersonal']);
            $itm->setIdTipoProyecto($row['idtipoproyecto']);
            $itm->setNombre($row['nombre']);
            $itm->setHorario($row['horario']);
            $itm->setPeriodo($row['periodo']);
            $itm->setOficioAutorizacion($row['oficioautorizacion']);
            $itm->setFechaInicio($row['fechainicio']);
            $itm->setFechaCierre($row['fechacierre']);
            $itm->setCreditos($row['creditos']);
            $itm->setHoras($row['horas']);
            $itm->setNoAlumnos($row['noalumnos']);
            $itm->setEstatus($row['estatus']);
            $this->array[] = $itm->getJson();
        }
        return $this->array;
    }



    public function  getAllSolicitudes(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Solicitud_Actividad');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Solicitud_Actividad();
            $itm->setIdSolicitud($row['idsolicitud']);
            $itm->setIdEstudiante($row['idestudiante']);
            $itm->setIdActividad($row['idactividad']);
            $itm->setStatus($row['status']);
            $itm->setFechaCreacion($row['fechacreacion']);
            $itm->setMensaje($row['mensaje']);
        }
        return $this->array;
    }

    public function  getSolicitud(int $id): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Solicitud_Actividad');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Solicitud_Actividad();
            $itm->setIdSolicitud($row['IdSolicitud']);
            $itm->setIdEstudiante($row['IdEstudiante']);
            $itm->setIdActividad($row['IdActividad']);
            $itm->setStatus($row['status']);
            $itm->setFechaCreacion($row['fechaCreacion']);
            $itm->setMensaje($row['mensaje']);
        }
        return $this->array;
    }

    public function insertSolicitud(int $IdSolicitud,int $IdEstudiante,int $IdActividad,String $status,DateTime $fechaCreacion,String $mensaje): bool{
        try {
            $stmt = $this->pdo->prepare('INSERT INTO Solicitud_Actividad (IdSolicitud,IdEstudiante,IdActividad,status,fechaCreacion,mensaje) value (:IdSolicitud,:IdEstudiante,:IdActividad,:status,:fechaCreacion,:mensaje)');
            $stmt->bindParam(":IdSolicitud", $IdSolicitud);
            $stmt->bindParam(":IdEstudiante", $IdEstudiante);
            $stmt->bindParam(":IdActividad", $IdActividad);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":fechaCreacion", $fechaCreacion);
            $stmt->bindParam(":mensaje", $mensaje);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function  getAllRegistar(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM RegistroActividades');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new RegistroActividades();
            $itm->setIdRegistro($row['idregistro']);
            $itm->setIdEstudiante($row['idestudiante']);
            $itm->setIdActividad($row['idactividad']);
            $itm->setEstado($row['estado']);
            $itm->setCalificacion($row['calificacion']);
        }
        return $this->array;
    }

    public function  getRegistar(int $id): array {
        $stmt = $this->pdo->prepare('SELECT * FROM RegistroActividades');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new RegistroActividades();
            $itm->setIdRegistro($row['idregistro']);
            $itm->setIdEstudiante($row['idestudiante']);
            $itm->setIdActividad($row['idactividad']);
            $itm->setEstado($row['estado']);
            $itm->setCalificacion($row['calificacion']);
        }
        return $this->array;
    }

    public function insertRegristar(int $idregistro,int $idestudiante,int $idactividad,String $estado,float $calificacion): bool{
        try {
            $stmt = $this->pdo->prepare('INSERT INTO RegistroActividades (idregistro,idestudiante,idactividad,estado,calificacion) value (:idregistro,:idestudiante,:idactividad,:estado,:calificacion)');
            $stmt->bindParam(":idregistro", $idregistro);
            $stmt->bindParam(":idestudiante", $idestudiante);
            $stmt->bindParam(":idactividad", $idactividad);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":calificacion", $calificacion);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function  getAllCalificacion(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Calificacion');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new calificacion();
            $itm->setIdRegistro($row['idregistro']);
            $itm->setIdEstudiante($row['idestudiante']);
            $itm->setPregunta1($row['pregunta1']);
            $itm->setPregunta2($row['pregunta2']);
            $itm->setPregunta3($row['pregunta3']);
            $itm->setPregunta4($row['pregunta4']);
            $itm->setPregunta5($row['pregunta5']);
            $itm->setPregunta6($row['pregunta6']);
            $itm->setPregunta7($row['pregunta7']);
            $itm->setObservaciones($row['observaciones']);
        }
        return $this->array;
    }

    public function  getCalificacion(int $id): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Calificacion');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new calificacion();
            $itm->setIdRegistro($row['idregistro']);
            $itm->setIdEstudiante($row['idestudiante']);
            $itm->setPregunta1($row['pregunta1']);
            $itm->setPregunta2($row['pregunta2']);
            $itm->setPregunta3($row['pregunta3']);
            $itm->setPregunta4($row['pregunta4']);
            $itm->setPregunta5($row['pregunta5']);
            $itm->setPregunta6($row['pregunta6']);
            $itm->setPregunta7($row['pregunta7']);
            $itm->setObservaciones($row['observaciones']);
        }
        return $this->array;
    }
    
    public function  insertCalificacion(int $idregistro,int $idestudiante,String $pregunta1,String $pregunta2,String $pregunta3,String $pregunta4,String $pregunta5,String $pregunta6,String $pregunta7,String $observaciones): bool {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO Calificacion (idregistro,idestudiante,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pregunta6,pregunta7,observaciones) value (:idregistro,:idestudiante,:pregunta1,;pregunta2,;pregunta3,:pregunta4,:pregunta5,:pregunta6,:pregunta7,:observaciones)');
            $stmt->bindParam(":idregistro", $idregistro);
            $stmt->bindParam(":idestudiante", $idestudiante);
            $stmt->bindParam(":pregunta1", $pregunta1);
            $stmt->bindParam(":pregunta2", $pregunta2);
            $stmt->bindParam(":pregunta3", $pregunta3);
            $stmt->bindParam(":pregunta4", $pregunta4);
            $stmt->bindParam(":pregunta5", $pregunta5);
            $stmt->bindParam(":pregunta6", $pregunta6);
            $stmt->bindParam(":pregunta7", $pregunta7);
            $stmt->bindParam(":observaciones", $observaciones);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    } */


}