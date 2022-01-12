<?php
require __DIR__ . '/config/BD_Connect.php';

//requiere models
require_once  __DIR__ . '/modal/include.php';

class DB
{
    private $db;
    private $pdo;
    private $pdoITCG;
    private $array;
    private $arrayITCG;

    function __construct() {
        $this->db = new DB_Connect();
        $this->pdo = $this->db->createConnexicon();
        $this->pdoITCG = $this->db->createConnexiconITCG();
        $this->array = array();
    }

    function isValidApiKey(string $key): bool {
        return strcmp($key, key) == 0;
        //return $key==key;
    }

    function isValidHost(string $host): bool {
        return true;
    }

    function isTypeUser(String $username):String{
        $stmt = $this->pdo->prepare('SELECT * FROM Estudiantes where nc = :nc ');
        $stmt->bindParam(":nc", $username);
        $stmt->execute();
        $contar=$stmt->rowCount();
        if($contar==1){
            return "Estudiante";
        }else{
            $stmt = $this->pdo->prepare('SELECT  *  FROM Empleado where id_pers = :id_pers ');
            $stmt->bindParam(":id_pers", $username);
            $stmt->execute();
            $contar=$stmt->rowCount();
            if($contar==1){
                return "Empleado";
            }else{
                return "No existe";
            }
        }
    }

    public function getAllCarreras(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Carreras');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new Carreras();
            $itm->setIdCar($row['idcar']);
            $itm->setNombreCar($row['nombrecar']);
            $itm->setSiglas($row['siglas']);
            $this->array[] = $itm->getJson();
        }
        return $this->array;
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

  /*           foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Carreras(idcar,nombrecar,siglas) values (:idcar,:nombrecar,:siglas)');
                $stmt->bindParam(":idcar", $row . getIdCar());
                $stmt->bindParam(":nombrecar", $row . getNombreCar());
                $stmt->bindParam(":siglas", $row .getSiglas());
                $stmt->execute();
            } */
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function getAllDepartamentos(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Deptos');
        $stmt->execute();
        foreach ($stmt as $row) {
            $itm = new departamentos();
            $itm->setIDDEPTO($row['id_depto']);
            $itm->setNOMDEPTO($row['nom_depto']);
            $this->array[] = $itm->getJson();
        }
        return $this->array;
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

       /*      foreach ($this->arrayITCG as $row) {
                $stmt = $this->pdo->prepare('INSERT INTO Carreras(id_depto,nom_depto) values (:id_depto,:nom_depto)');
                $stmt->bindParam(":id_depto", $row.getIDDEPTO());
                $stmt->bindParam(":nom_depto", $row.getNOMDEPTO());
                $stmt->execute();
            } */
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function getAllEmpleados(): array {
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
            $this->array[] = $itm->getJson();
        }
        return $this->array;
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
    /*
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
            */
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function  getAllEstudiantes(): array {
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
            $this->array[] = $itm->getJson();
        }

        return $this->array;
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

            /* foreach ($this->arrayITCG as $row) {
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
            } */
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function  getAllTipoProyecto(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Tipo_Proyecto');
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

    public function  insertTipoProyecto(int $idtipoproyecto,String $nombretipo,String $descripcion):bool {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO Tipo_Proyecto(idtipoproyecto,nombretipo,descripcion) value (:idtipoproyecto,:nombretipo,:descripcion) ');
            $stmt->bindParam(":idtipoproyecto", $idtipoproyecto);
            $stmt->bindParam(":nombretipo", $nombretipo);
            $stmt->bindParam(":descripcion", $descripcion);
            $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
        return true;
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

    public function insertActividad (int $idactividad,int $iddepartamento,int $idjefepersonal,int $idpersonal,int $idtipoproyecto, String $nombre,DateTime $horario,DateTime $periodo,String $oficioautorizacion,DateTime $fechainicio,DateTime $fechacierre, int $creditos,int $noalumnos,String $estatus):bool{
        try {
            $stmt = $this->pdo->prepare('INSERT INTO Actividades (idactividad,iddepartamento,idjefepersonal,idpersonal,idtipoproyecto,nombre,horario,periodo,oficioautorizacion,fechainicio,fechacierre,creditos,horas,noalumnos,estatus) value (:idactividad,:iddepartamento,:idjefepersonal,:idpersonal,:idtipoproyecto,:nombre,horario,periodo,oficioautorizacion,fechainicio,fechacierre,creditos,horas,noalumnos,estatus) ');
            $stmt->bindParam(":idactividad", $idactividad);
            $stmt->bindParam(":iddepartamento", $iddepartamento);
            $stmt->bindParam(":idjefepersonal", $idjefepersonal);
            $stmt->bindParam(":idpersonal", $idpersonal);
            $stmt->bindParam(":idtipoproyecto", $idtipoproyecto);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":horario", $horario);
            $stmt->bindParam(":periodo", $periodo);
            $stmt->bindParam(":oficioautorizacion", $oficioautorizacion);
            $stmt->bindParam(":fechainicio", $fechainicio);
            $stmt->bindParam(":fechacierre", $fechacierre);
            $stmt->bindParam(":creditos", $creditos);
            $stmt->bindParam(":horas", $horas);
            $stmt->bindParam(":noalumnos", $noalumnos);
            $stmt->bindParam(":estatus", $estatus);
            $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
        return true;
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
    }

    public function getLoginEmpleados(String $id_pers, String $pwd): array {
        $stmt = $this->pdo->prepare('SELECT  *  FROM Empleado where id_pers = :id_pers and pwd = :pwd');
        $stmt->bindParam(":id_pers", $id_pers);
        $stmt->bindParam(":pwd", $pwd);
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

    public function getLoginEstudiantes(String $nc, String $password): array {
        $stmt = $this->pdo->prepare('SELECT * FROM Estudiantes where nc = :nc and password = :password');
        $stmt->bindParam(":nc", $nc);
        $stmt->bindParam(":password", $password);
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
}