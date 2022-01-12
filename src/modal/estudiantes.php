<?php

/**
 *
 */
class estudiantes
{

    /**
     * @var
     */
    private $nc;
    /**
     * @var
     */
    private $ap;
    /**
     * @var
     */
    private $am;
    /**
     * @var
     */
    private $nomalu;
    /**
     * @var
     */
    private $IdCar;
    /**
     * @var
     */
    private $semestre;
    /**
     * @var
     */
    private $FirmaDigital;
    /**
     * @var
     */
    private $password;

    /**
     * @return mixed
     */
    public function getNc()
    {
        return $this->nc;
    }

    /**
     * @param mixed $nc
     * @return estudiantes
     */
    public function setNc($nc)
    {
        $this->nc = $nc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAp()
    {
        return $this->ap;
    }

    /**
     * @param mixed $ap
     * @return estudiantes
     */
    public function setAp($ap)
    {
        $this->ap = $ap;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAm()
    {
        return $this->am;
    }

    /**
     * @param mixed $am
     * @return estudiantes
     */
    public function setAm($am)
    {
        $this->am = $am;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomalu()
    {
        return $this->nomalu;
    }

    /**
     * @param mixed $nomalu
     * @return estudiantes
     */
    public function setNomalu($nomalu)
    {
        $this->nomalu = $nomalu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdCar()
    {
        return $this->IdCar;
    }

    /**
     * @param mixed $IdCar
     * @return estudiantes
     */
    public function setIdCar($IdCar)
    {
        $this->IdCar = $IdCar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * @param mixed $semestre
     * @return estudiantes
     */
    public function setSemestre($semestre)
    {
        $this->semestre = $semestre;
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
     * @return estudiantes
     */
    public function setFirmaDigital($FirmaDigital)
    {
        $this->FirmaDigital = $FirmaDigital;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return estudiantes
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return  array("Numero_Control"=>$this->getNc(), "Apellido_Paterno"=>$this->getAp(), "Apellido_Materno"=>$this->getAm(), "Nombre_Alumno"=>$this->getNomalu(), "Id_Carrera"=>$this->getIdCar(), "Semestre"=>$this->getSemestre(), "Firma_Digital"=>$this->getFirmaDigital(),"Contraseña"=>$this->getPassword());
    }




}
