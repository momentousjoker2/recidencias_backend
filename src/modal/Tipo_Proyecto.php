<?php

/**
 *
 */
class Tipo_Proyecto{
    /**
     * @var
     */
    private $IdTipoProyecto;
    /**
     * @var
     */
    private $NombreTipo;
    /**
     * @var
     */
    private $Descripcion;

    /**
     * @return mixed
     */
    public function getIdTipoProyecto()
    {
        return $this->IdTipoProyecto;
    }

    /**
     * @param mixed $IdTipoProyecto
     * @return Tipo_Proyecto
     */
    public function setIdTipoProyecto($IdTipoProyecto)
    {
        $this->IdTipoProyecto = $IdTipoProyecto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreTipo()
    {
        return $this->NombreTipo;
    }

    /**
     * @param mixed $NombreTipo
     * @return Tipo_Proyecto
     */
    public function setNombreTipo($NombreTipo)
    {
        $this->NombreTipo = $NombreTipo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * @param mixed $Descripcion
     * @return Tipo_Proyecto
     */
    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return array("Id_Tipo_Proyecto"=>$this->getIdTipoProyecto(), "Nombre_Tipo_Proyecto"=>$this->getNombreTipo(), "Descripcion"=>$this->getDescripcion());
    }




}