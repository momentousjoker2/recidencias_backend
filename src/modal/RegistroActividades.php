<?php

/**
 *
 */
class RegistroActividades
{
    /**
     * @var
     */
    private $IdRegistro;
    /**
     * @var
     */
    private $IdEstudiante;
    /**
     * @var
     */
    private $IdActividad;
    /**
     * @var
     */
    private $Estado;
    /**
     * @var
     */
    private $Calificacion;

    /**
     * @return mixed
     */
    public function getIdRegistro()
    {
        return $this->IdRegistro;
    }

    /**
     * @param mixed $IdRegistro
     * @return RegistroActividades
     */
    public function setIdRegistro($IdRegistro)
    {
        $this->IdRegistro = $IdRegistro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdEstudiante()
    {
        return $this->IdEstudiante;
    }

    /**
     * @param mixed $IdEstudiante
     * @return RegistroActividades
     */
    public function setIdEstudiante($IdEstudiante)
    {
        $this->IdEstudiante = $IdEstudiante;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdActividad()
    {
        return $this->IdActividad;
    }

    /**
     * @param mixed $IdActividad
     * @return RegistroActividades
     */
    public function setIdActividad($IdActividad)
    {
        $this->IdActividad = $IdActividad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->Estado;
    }

    /**
     * @param mixed $Estado
     * @return RegistroActividades
     */
    public function setEstado($Estado)
    {
        $this->Estado = $Estado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCalificacion()
    {
        return $this->Calificacion;
    }

    /**
     * @param mixed $Calificacion
     * @return RegistroActividades
     */
    public function setCalificacion($Calificacion)
    {
        $this->Calificacion = $Calificacion;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return  array($this->getIdRegistro(), $this->getIdEstudiante(), $this->getIdActividad(), $this->getEstado(), $this->getCalificacion());
    }

}
