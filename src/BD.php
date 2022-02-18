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

            $stmt = $this->pdo->prepare("SELECT e.nc as 'username' , CONCAT(e.nomalu,' ',e.ap,' ',e.am) as 'nombre','Estudiante' as rol from Estudiantes as e WHERE e.nc = :username && e.password = :password ;");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            $contar=$stmt->rowCount();
            if($contar==1){
                $user = $stmt->fetch();
                $user['error']=false;

            }else{
                $stmt = $this->pdo->prepare("SELECT e.ID_PERS as 'username' ,e.NOM_PERS as 'nombre', e.Puesto  as 'rol' from Empleado as e where e.ID_PERS = :username  && e.pwd = :password");
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
            $stmt = $this->pdo->prepare('SELECT * FROM carreras');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->idcar=$row['idcar'];
                $datos->nombrecar=$row['nombcar'];
                $datos->siglas=$row['siglas'];

                $data[] = $datos;
            } 
        }catch(Exception $e){
                    $data['error']=true;
        }finally{
            return $data;
        }
    }

    //Departamentos Terminados 
    public function getCatalagoDepartemento(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT * FROM Deptos');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->id_depto=$row['id_depto'];
                $datos->nom_depto=$row['nom_depto'];
                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }    
    }

    //Empleados Terminado 
    public function getCatalagoEmpleados(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT em.ID_PERS as "ID", em.NOM_PERS as "Nombre",(SELECT d.NOM_DEPTO FROM Deptos as d WHERE d.ID_DEPTO=em.ID_DEPTO) as Departamento,em.Puesto as "puesto" from Empleado as em');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;

                $datos->id_pers = $row['id'];
                $datos->nom_pers = $row['nombre'];
                $datos->id_depto = $row['departamento'];
                $datos->puesto = $row['puesto'];

                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }    
    }

    //Update Password empleados Termiandos 
    public function updatePasswordEmpleados($ID,$password): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE Empleado SET pwd=:pwd WHERE ID_PERS =:ID_PERS ');
            $stmt->bindParam(":pwd", $password);
            $stmt->bindParam(":ID_PERS", $ID);
            $stmt->execute();
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

            $stmt = $this->pdo->prepare('SELECT es.nc as "ID",CONCAT(es.nomalu," ",es.ap," ",es.am) as "Nombre" , (SELECT c.nombcar FROM carreras as c where c.idcar = es.idcar) as "Carrera", (SELECT COUNT(*) FROM RegistroActividades as r WHERE r.IdEstudiante=es.nc ) as "Actividades" FROM Estudiantes as es; ');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass;
                $datos->ID = $row['id'];
                $datos->Nombre = $row['nombre'];
                $datos->Carrera = $row['carrera'];
                $datos->Actividades = $row['actividades'];
                $data[] = $datos;
            }
        }catch(Exception $e){
            $data['error']=true;
        }finally{
            return $data;
        }    
    }

    //Update Password Estudiantes Termiandos 
    public function updatePasswordEstudiantes($ID,$password): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('UPDATE Estudiantes SET password=:password WHERE nc =:nc ');
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
            $stmt = $this->pdo->prepare('SELECT * FROM Categoria');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idcategoria = $row['idcategoria'];
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

            $stmt = $this->pdo->prepare('INSERT INTO Categoria(nombre,descripcion) value (:nombre,:descripcion) ');
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
            $stmt = $this->pdo->prepare('UPDATE Categoria SET nombre = :nombre , descripcion = :descripcion WHERE idCategoria = :idCategoria');
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
    
    //Tipo de proyecto probar
    public function  getCatalagoTipoProyecto(): array {
        try{
            $data = array();
            $stmt = $this->pdo->prepare('SELECT t.IdTipoProyecto as "id", t.NombreTipo as "nombre",(SELECT c.nombre FROM Categoria as c WHERE c.idCategoria = t.idCategoria) as "Categoria", t.Descripcion as "descripcion" FROM Tipo_Proyecto as t');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->id = $row['id'];
                $datos->nombre = $row['nombre'];
                $datos->descripcion = $row['descripcion'];
                $datos->Categoria = $row['categoria'];
                $data[] = $datos;
            }
        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    // insertar Tipo de proyecto probar
    public function  insertTipoProyecto(String $idCategoria,String $NombreTipo,String $description):array {
        try {

            $stmt = $this->pdo->prepare('INSERT INTO Tipo_Proyecto(idCategoria,NombreTipo,Descripcion) value (:idCategoria,:NombreTipo,:description) ');
            $stmt->bindParam(":idCategoria", $idCategoria);
            $stmt->bindParam(":NombreTipo", $NombreTipo);
            $stmt->bindParam(":description", $description);
            $data["error"]=!$stmt->execute();

        } catch (PDOException $e) {
            $data["error"]=true;
        }finally{
            return $data;
        }
    }

    //update a tipo de proyecto terminado
    public function  updateTipoProyecto(String $idtipoproyecto,String $idCategoria,String $nombretipo,String $descripcion):array {
        try {
            $stmt = $this->pdo->prepare('UPDATE Tipo_Proyecto SET idCategoria = :idCategoria , nombretipo = :nombretipo , descripcion = :descripcion WHERE idtipoproyecto = :idtipoproyecto');
            $stmt->bindParam(":idtipoproyecto", $idtipoproyecto);
            $stmt->bindParam(":idCategoria", $idCategoria);
            $stmt->bindParam(":nombretipo", $nombretipo);
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
            $stmt = $this->pdo->prepare('SELECT * FROM Periodo');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idperiodo = $row['idperiodo'];
                $datos->nombre = $row['nombre'];
                $datos->status=$row['status'];
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
            $stmt = $this->pdo->prepare('INSERT INTO Periodo(nombre,status) value (:nombre,:status) ');
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
            $stmt = $this->pdo->prepare('UPDATE Periodo SET nombre = :nombre , status = :status WHERE idperiodo = :idperiodo');
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
            $stmt = $this->pdo->prepare('SELECT cp.idactividad, (SELECT c.nombre FROM Categoria as c WHERE c.idCategoria = cp.idCategoria) as Categoria, (SELECT t.NombreTipo FROM Tipo_Proyecto as t WHERE t.IdTipoProyecto = cp.idtipoproyecto) as "Tipo_Proyecto", cp.nombres_proyecto, cp.credito, cp.oficioautorizacion, cp.horassemanales, cp.status FROM Catalago_Proyectos  as cp');
            $stmt->execute();
            foreach ($stmt as $row) {
                $datos = new stdClass();
                $datos->idactividad = $row['idactividad'];
                $datos->Categoria = $row['categoria'];
                $datos->Tipo_Proyecto = $row['tipo_proyecto'];
                $datos->nombres_proyecto = $row['nombres_proyecto'];
                $datos->oficioautorizacion = $row['oficioautorizacion'];
                $datos->credito = $row['credito'];
                $datos->horassemanales = $row['horassemanales'];
                $datos->status = $row['status'];
                $data[] = $datos;
            }

        } catch (PDOException $e) {
            $data["error"]=true;
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