<?php

/**
 *
 */
class departamentos
{
    /**
     * @var
     */
    private $ID_DEPTO;
    /**
     * @var
     */
    private $NOM_DEPTO;

    /**
     * @return mixed
     */
    public function getIDDEPTO()
    {
        return $this->ID_DEPTO;
    }

    /**
     * @param mixed $ID_DEPTO
     * @return departamentos
     */
    public function setIDDEPTO($ID_DEPTO)
    {
        $this->ID_DEPTO = $ID_DEPTO;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNOMDEPTO()
    {
        return $this->NOM_DEPTO;
    }

    /**
     * @param mixed $NOM_DEPTO
     * @return departamentos
     */
    public function setNOMDEPTO($NOM_DEPTO)
    {
        $this->NOM_DEPTO = $NOM_DEPTO;
        return $this;
    }


    /**
     * @return array
     */
    public function getJson()
    {
        return array("id_Departamento"=>$this->getIDDEPTO(),"Nombre_Departamento"=> $this->getNOMDEPTO());
    }
}
