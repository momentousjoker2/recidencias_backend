<?php

/**
 *
 */
class calificacion
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
    private $Pregunta1;
    /**
     * @var
     */
    private $Pregunta2;
    /**
     * @var
     */
    private $Pregunta3;
    /**
     * @var
     */
    private $Pregunta4;
    /**
     * @var
     */
    private $Pregunta5;
    /**
     * @var
     */
    private $Pregunta6;
    /**
     * @var
     */
    private $Pregunta7;
    /**
     * @var
     */
    private $Observaciones;

    /**
     * @return mixed
     */
    public function getIdRegistro()
    {
        return $this->IdRegistro;
    }

    /**
     * @param mixed $IdRegistro
     * @return calificacion
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
     * @return calificacion
     */
    public function setIdEstudiante($IdEstudiante)
    {
        $this->IdEstudiante = $IdEstudiante;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta1()
    {
        return $this->Pregunta1;
    }

    /**
     * @param mixed $Pregunta1
     * @return calificacion
     */
    public function setPregunta1($Pregunta1)
    {
        $this->Pregunta1 = $Pregunta1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta2()
    {
        return $this->Pregunta2;
    }

    /**
     * @param mixed $Pregunta2
     * @return calificacion
     */
    public function setPregunta2($Pregunta2)
    {
        $this->Pregunta2 = $Pregunta2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta3()
    {
        return $this->Pregunta3;
    }

    /**
     * @param mixed $Pregunta3
     * @return calificacion
     */
    public function setPregunta3($Pregunta3)
    {
        $this->Pregunta3 = $Pregunta3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta4()
    {
        return $this->Pregunta4;
    }

    /**
     * @param mixed $Pregunta4
     * @return calificacion
     */
    public function setPregunta4($Pregunta4)
    {
        $this->Pregunta4 = $Pregunta4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta5()
    {
        return $this->Pregunta5;
    }

    /**
     * @param mixed $Pregunta5
     * @return calificacion
     */
    public function setPregunta5($Pregunta5)
    {
        $this->Pregunta5 = $Pregunta5;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta6()
    {
        return $this->Pregunta6;
    }

    /**
     * @param mixed $Pregunta6
     * @return calificacion
     */
    public function setPregunta6($Pregunta6)
    {
        $this->Pregunta6 = $Pregunta6;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPregunta7()
    {
        return $this->Pregunta7;
    }

    /**
     * @param mixed $Pregunta7
     * @return calificacion
     */
    public function setPregunta7($Pregunta7)
    {
        $this->Pregunta7 = $Pregunta7;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObservaciones()
    {
        return $this->Observaciones;
    }

    /**
     * @param mixed $Observaciones
     * @return calificacion
     */
    public function setObservaciones($Observaciones)
    {
        $this->Observaciones = $Observaciones;
        return $this;
    }
    /**
     * @return array
     */
    public function getJson()
    {
        return array($this->getIdRegistro(), $this->getIdEstudiante(), $this->getPregunta1(), $this->getPregunta2(), $this->getPregunta3(), $this->getPregunta4(), $this->getPregunta5(), $this->getPregunta6(), $this->getPregunta7(), $this->getObservaciones());
    }
}
