<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;

use CHRIST\Common\Entity\Master\AbstractEntity;

/**
 * Description
 *
 * @author glr735
 */
abstract class AbstractAffectationDTC extends AbstractEntity implements \CHRIST\Common\Interfaces\IAffectationDTC
{        
    /**
     * @ORM\Id
     * @ORM\Column(name="AGOR_ID", type="integer")
     */
    protected $id;
    
    /**
     * Used in the extended class
     **/
    protected $service;
    
    /**
     * Used in the extended class
     **/
    protected $agent;

    /**
     * @ORM\Column(name="AGOR_RESPONSABLE", type="boolean")
     */
    protected $responsable;

    /**
     * @ORM\Column(name="AGOR_DATE_DEBUT", type="datetime")
     */
    protected $dateDebut;

    /**
     * @ORM\Column(name="AGOR_DATE_FIN", type="datetime")
     */
    protected $dateFin;
    
    
    

    public function getId()
    {
        return $this->id;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }


}
