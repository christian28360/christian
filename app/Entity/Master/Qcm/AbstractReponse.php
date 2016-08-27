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
abstract class AbstractReponse extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="REP_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Used in the extended class
     * */
    protected $question;

    /**
     * @ORM\Column(name="REP_LIBELLE", type="string")
     */
    protected $libelle;

    /**
     * @ORM\Column(name="REP_CORRECTE", type="boolean")
     */
    protected $correcte;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="REP_CREATE_AT", type="datetime")
     */
    protected $createAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="REP_UPDATE_AT", type="datetime")
     */
    protected $updateAt;

    
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getCorrecte()
    {
        return $this->correcte;
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

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function setCorrecte($correcte)
    {
        $this->correcte = $correcte;
    }

}
