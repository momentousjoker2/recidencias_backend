<?php

/**
 *
 */
class Periodo{
    /**
     * @var
     */
    private $idperiodo;
    /**
     * @var
     */
    private $nombre;
    /**
     * @var
     */
    private $status;

    /**
     * @return mixed
     */
    public function getIdPeriodo()
    {
        return $this->idperiodo;
    }

    /**
     * @param mixed $IdTipoProyecto
     * @return Tipo_Proyecto
     */
    public function setIdPeriodo($idperiodo)
    {
        $this->idperiodo = $idperiodo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $NombreTipo
     * @return Tipo_Proyecto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @param mixed $Descripcion
     * @return Tipo_Proyecto
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return array("Id_Periodo"=>$this->getIdPeriodo(), "Nombre_Periodo"=>$this->getNombre(), "status"=>$this->getStatus());
    }




}