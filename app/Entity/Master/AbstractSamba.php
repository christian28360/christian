<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Samba
 *
 * @author glr735
 */
abstract class AbstractSamba
{

    /**
     * @ORM\Id
     * @ORM\Column(name="code_rh")
     * @var string
     */
    protected $id = 'A00000';

    /**
     * Domain
     * @ORM\Column(name="code_domaine")
     * @var string
     */
    protected $domain;

    /**
     * Name
     * @ORM\Column(name="nom")
     * @var string
     */
    protected $name = 'Anonyme';

    /**
     * Last name
     * @ORM\Column(name="prenom")
     * @var string
     */
    protected $lastName;

    /**
     * Regate use
     * @ORM\Column(name="regate_utilisation")
     * @var string
     */
    protected $regateUse;

    /**
     * Roc use
     * @var string
     */
    protected $rocIdUse = '';

    /**
     * True if is id empty
     * @return type
     */
    public function isNew()
    {
        return empty($this->id);
    }
    
    /**
     * True if user is anonymous
     * @return boolean
     */
    public function isAnonymous()
    {
        return $this->id == 'A00000';
    }

    /**
     * Get full name (last name + name)
     * @return string
     */
    public function getFullName()
    {
        return $this->lastName . ' ' . $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getRegateUse()
    {
        return $this->regateUse;
    }

    public function getRocIdUse()
    {
        return $this->rocIdUse;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setRegateUse($regateUse)
    {
        $this->regateUse = $regateUse;
    }

    public function setRocIdUse($rocIdUse)
    {
        $this->rocIdUse = $rocIdUse;
    }

}
