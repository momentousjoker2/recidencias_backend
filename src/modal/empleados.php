<?php

/**
 *
 */
class empleados
{

    /**
     * @var
     */
    private $ID_PERS;
    /**
     * @var
     */
    private $NOM_PERS;
    /**
     * @var
     */
    private $ID_DEPTO;
    /**
     * @var
     */
    private $pwd;
    /**
     * @var
     */
    private $FirmaDigital;
    /**
     * @var
     */
    private $Puesto;

    /**
     * @return mixed
     */
    public function getIDPERS()
    {
        return $this->ID_PERS;
    }

    /**
     * @param mixed $ID_PERS
     * @return Tipo_Proyecto
     */
    public function setIDPERS($ID_PERS)
    {
        $this->ID_PERS = $ID_PERS;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNOMPERS()
    {
        return $this->NOM_PERS;
    }

    /**
     * @param mixed $NOM_PERS
     * @return Tipo_Proyecto
     */
    public function setNOMPERS($NOM_PERS)
    {
        $this->NOM_PERS = $NOM_PERS;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIDDEPTO()
    {
        return $this->ID_DEPTO;
    }

    /**
     * @param mixed $ID_DEPTO
     * @return Tipo_Proyecto
     */
    public function setIDDEPTO($ID_DEPTO)
    {
        $this->ID_DEPTO = $ID_DEPTO;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $pwd
     * @return Tipo_Proyecto
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirmaDigital()
    {
        return $this->FirmaDigital;
    }

    /**
     * @param mixed $FirmaDigital
     * @return Tipo_Proyecto
     */
    public function setFirmaDigital($FirmaDigital)
    {
        $this->FirmaDigital = $FirmaDigital;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPuesto()
    {
        return $this->Puesto;
    }

    /**
     * @param mixed $Puesto
     * @return Tipo_Proyecto
     */
    public function setPuesto($Puesto)
    {
        $this->Puesto = $Puesto;
        return $this;
    }


    /**
     * @return array
     */
    public function getJson()
    {
        return  array($this->getIDPERS(), $this->getNOMPERS(), $this->getIDDEPTO(), $this->getPwd(), $this->getFirmaDigital(), $this->getPuesto());
    }




}
