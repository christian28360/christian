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
abstract class AbstractQuestion extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="QES_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Used in the extended class
     * */
    protected $etape;

    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $reponses;

    /**
     * @ORM\Column(name="QES_LIBELLE", type="string")
     */
    protected $libelle;

    /**
     * @ORM\Column(name="QES_REP_TYPE", type="string")
     */
    protected $repType;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="QES_CREATE_AT", type="datetime")
     */
    protected $createAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="QES_UPDATE_AT", type="datetime")
     */
    protected $updateAt;
    
    
    
    

    public function __construct()
    {
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getEtape()
    {
        return $this->etape;
    }

    public function getReponses()
    {
        return $this->reponses;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getRepType()
    {
        return $this->repType;
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

    public function setEtape($etape)
    {
        $this->etape = $etape;
    }

    public function setReponses($reponses)
    {
        $this->reponses = $reponses;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function setRepType($repType)
    {
        $this->repType = $repType;
    }
    
}
