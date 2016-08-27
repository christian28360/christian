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
abstract class AbstractEtape extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="ETP_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Used in the extended class
     * */
    protected $qcm;
    
    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    protected $questions;

    /**
     * @ORM\Column(name="ETP_LIBELLE", type="string")
     */
    protected $libelle;

    /**
     * @ORM\Column(name="ETP_NUM", type="integer")
     */
    protected $num;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ETP_CREATE_AT", type="datetime")
     */
    protected $createAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ETP_UPDATE_AT", type="datetime")
     */
    protected $updateAt;
    
    
    
    

    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    
    
    public function getId()
    {
        return $this->id;
    }

    public function getQcm()
    {
        return $this->qcm;
    }
    
    public function getQuestions()
    {
        return $this->questions;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getNum()
    {
        return $this->num;
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

    public function setQcm($qcm)
    {
        $this->qcm = $qcm;
    }

    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }
   
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function setNum($num)
    {
        $this->num = $num;
    }

}
