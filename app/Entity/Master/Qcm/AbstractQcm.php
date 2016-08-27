<?php

namespace CHRIST\Common\Entity\Master\Qcm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use CHRIST\Common\Entity\Master\AbstractEntity;

/**
 * @author glr735
 */
abstract class AbstractQcm extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="QCM_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * Used in the extended class
     **/
    protected $application;
    
    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    protected $etapes;

    /**
     * @ORM\Column(name="QCM_LIBELLE", type="string")
     */
    protected $libelle;

    /**
     * @ORM\Column(name="QCM_ETAT", type="boolean")
     */
    protected $etat;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="QCM_CREATE_AT", type="datetime")
     */
    protected $createAt;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="QCM_UPDATE_AT", type="datetime")
     */
    protected $updateAt;
    
    
    
    

    public function __construct()
    {
        $this->etapes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getApplication()
    {
        return $this->application;
    }
    
    public function getEtapes()
    {
        return $this->etapes;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function setEtapes($etapes)
    {
        $this->etapes = $etapes;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }
    
}
