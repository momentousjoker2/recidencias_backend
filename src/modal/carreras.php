<?php

/**
 *
 */
class Carreras
{
    /**
     * @var
     */
    private $IdCar;
    /**
     * @var
     */
    private $NombreCar;
    /**
     * @var
     */
    private $Siglas;

    /**
     * @return mixed
     */
    public function getIdCar()
    {
        return $this->IdCar;
    }

    /**
     * @param mixed $IdCar
     * @return Carreras
     */
    public function setIdCar($IdCar)
    {
        $this->IdCar = $IdCar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreCar()
    {
        return $this->NombreCar;
    }

    /**
     * @param mixed $NombreCar
     * @return Carreras
     */
    public function setNombreCar($NombreCar)
    {
        $this->NombreCar = $NombreCar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiglas()
    {
        return $this->Siglas;
    }

    /**
     * @param mixed $Siglas
     * @return Carreras
     */
    public function setSiglas($Siglas)
    {
        $this->Siglas = $Siglas;
        return $this;
    }


    /**
     * @return array
     */
    public function getJson() {
        return array("id_Carrera"=>$this->getIdCar(),"Nombre_Carrera"=>$this->getNombreCar(),"Siglas_Carrera"=>$this->getSiglas());
    }

}
