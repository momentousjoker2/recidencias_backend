<?php

/**
 *
 */
class Actividades
{

    /**
     * @var
     */
    private $IdActividad;
    /**
     * @var
     */
    private $IdDepartamento;
    /**
     * @var
     */
    private $IdJefePersonal;
    /**
     * @var
     */
    private $IdPersonal;
    /**
     * @var
     */
    private $IdTipoProyecto;
    /**
     * @var
     */
    private $Nombre;
    /**
     * @var
     */
    private $Horario;
    /**
     * @var
     */
    private $Periodo;
    /**
     * @var
     */
    private $OficioAutorizacion;
    /**
     * @var
     */
    private $FechaInicio;
    /**
     * @var
     */
    private $FechaCierre;
    /**
     * @var
     */
    private $Creditos;
    /**
     * @var
     */
    private $Horas;
    /**
     * @var
     */
    private $noAlumnos;
    /**
     * @var
     */
    private $Estatus;

    /**
     * @return mixed
     */
    public function getIdActividad()
    {
        return $this->IdActividad;
    }

    /**
     * @param mixed $IdActividad
     * @return Actividades
     */
    public function setIdActividad($IdActividad)
    {
        $this->IdActividad = $IdActividad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdDepartamento()
    {
        return $this->IdDepartamento;
    }

    /**
     * @param mixed $IdDepartamento
     * @return Actividades
     */
    public function setIdDepartamento($IdDepartamento)
    {
        $this->IdDepartamento = $IdDepartamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdJefePersonal()
    {
        return $this->IdJefePersonal;
    }

    /**
     * @param mixed $IdJefePersonal
     * @return Actividades
     */
    public function setIdJefePersonal($IdJefePersonal)
    {
        $this->IdJefePersonal = $IdJefePersonal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPersonal()
    {
        return $this->IdPersonal;
    }

    /**
     * @param mixed $IdPersonal
     * @return Actividades
     */
    public function setIdPersonal($IdPersonal)
    {
        $this->IdPersonal = $IdPersonal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdTipoProyecto()
    {
        return $this->IdTipoProyecto;
    }

    /**
     * @param mixed $IdTipoProyecto
     * @return Actividades
     */
    public function setIdTipoProyecto($IdTipoProyecto)
    {
        $this->IdTipoProyecto = $IdTipoProyecto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * @param mixed $Nombre
     * @return Actividades
     */
    public function setNombre($Nombre)
    {
        $this->Nombre = $Nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHorario()
    {
        return $this->Horario;
    }

    /**
     * @param mixed $Horario
     * @return Actividades
     */
    public function setHorario($Horario)
    {
        $this->Horario = $Horario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeriodo()
    {
        return $this->Periodo;
    }

    /**
     * @param mixed $Periodo
     * @return Actividades
     */
    public function setPeriodo($Periodo)
    {
        $this->Periodo = $Periodo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOficioAutorizacion()
    {
        return $this->OficioAutorizacion;
    }

    /**
     * @param mixed $OficioAutorizacion
     * @return Actividades
     */
    public function setOficioAutorizacion($OficioAutorizacion)
    {
        $this->OficioAutorizacion = $OficioAutorizacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaInicio()
    {
        return $this->FechaInicio;
    }

    /**
     * @param mixed $FechaInicio
     * @return Actividades
     */
    public function setFechaInicio($FechaInicio)
    {
        $this->FechaInicio = $FechaInicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaCierre()
    {
        return $this->FechaCierre;
    }

    /**
     * @param mixed $FechaCierre
     * @return Actividades
     */
    public function setFechaCierre($FechaCierre)
    {
        $this->FechaCierre = $FechaCierre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditos()
    {
        return $this->Creditos;
    }

    /**
     * @param mixed $Creditos
     * @return Actividades
     */
    public function setCreditos($Creditos)
    {
        $this->Creditos = $Creditos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHoras()
    {
        return $this->Horas;
    }

    /**
     * @param mixed $Horas
     * @return Actividades
     */
    public function setHoras($Horas)
    {
        $this->Horas = $Horas;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoAlumnos()
    {
        return $this->noAlumnos;
    }

    /**
     * @param mixed $noAlumnos
     * @return Actividades
     */
    public function setNoAlumnos($noAlumnos)
    {
        $this->noAlumnos = $noAlumnos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstatus()
    {
        return $this->Estatus;
    }

    /**
     * @param mixed $Estatus
     * @return Actividades
     */
    public function setEstatus($Estatus)
    {
        $this->Estatus = $Estatus;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return array("IdActividad"=>$this->getIdActividad(), "IdDepartamento"=>$this->getIdDepartamento(), "IdJefePersonal"=>$this->getIdJefePersonal(), $this->getIdPersonal(), $this->getIdTipoProyecto(), $this->getNombre(), $this->getHorario(), $this->getPeriodo(), $this->getOficioAutorizacion(), $this->getFechaInicio(), $this->getFechaCierre(), $this->getCreditos(), $this->getHoras(), $this->getNoAlumnos(), $this->getEstatus());
    }




}
