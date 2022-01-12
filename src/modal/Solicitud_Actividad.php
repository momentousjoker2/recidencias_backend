<?php

/**
 *
 */
class Solicitud_Actividad
{
    /**
     * @var
     */
    private $IdSolicitud;
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
    private $status;
    /**
     * @var
     */
    private $fechaCreacion;
    /**
     * @var
     */
    private $mensaje;

    /**
     * @return mixed
     */
    public function getIdSolicitud()
    {
        return $this->IdSolicitud;
    }

    /**
     * @param mixed $IdSolicitud
     * @return Solicitud_Actividad
     */
    public function setIdSolicitud($IdSolicitud)
    {
        $this->IdSolicitud = $IdSolicitud;
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
     * @return Solicitud_Actividad
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
     * @return Solicitud_Actividad
     */
    public function setIdActividad($IdActividad)
    {
        $this->IdActividad = $IdActividad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Solicitud_Actividad
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * @param mixed $fechaCreacion
     * @return Solicitud_Actividad
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * @param mixed $mensaje
     * @return Solicitud_Actividad
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }


    /**
     * @return array
     */
    public function getJson()
    {
        return  array("Id_Solicitud"=>$this->getIdSolicitud(), "Id_Alumno"=>$this->getIdEstudiante(), "Id_Actividad"=>$this->getIdActividad(), "Estatus"=>$this->getStatus(), "Fecha_Creacion"=>$this->getFechaCreacion(), "Mensaje"=>$this->getMensaje());
    }

}