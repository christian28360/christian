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
abstract class AbstractApplication extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="APP_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    protected $qcms;

    /**
     * @ORM\Column(name="APP_NOM", type="string")
     */
    protected $nom;

    /**
     * @ORM\Column(name="APP_ETAT", type="boolean")
     */
    protected $etat;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="APP_CREATE_AT", type="datetime")
     */
    protected $createAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="APP_UPDATE_AT", type="datetime")
     */
    protected $updateAt;
    
    
    
    

    public function __construct()
    {
        $this->qcms = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getQcms()
    {
        return $this->qcms;
    }
    
    public function getNom()
    {
        return $this->nom;
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

    public function setQcms($qcms)
    {
        $this->qcms = $qcms;
    }
   
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

}
