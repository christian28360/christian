<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use CHRIST\Common\Entity\Master\AbstractEntity;

/**
 * Description
 *
 * @author glr735
 */
abstract class AbstractOrganigrammeDTC extends AbstractEntity implements \CHRIST\Common\Interfaces\IOrganigrammeDTC
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ORGA_ID", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="ORGA_REF", type="integer")
     */
    protected $ref;

    /**
     * @ORM\Column(name="ORGA_CODE", type="string")
     */
    protected $code;

    /**
     * @ORM\Column(name="ORGA_LIBELLE", type="string")
     */
    protected $libelle;

    /**
     * @ORM\Column(name="ORGA_NIVEAU", type="integer")
     */
    protected $niveau;

    /**
     * @ORM\Column(name="ORGA_BORNE_GAUCHE", type="integer")
     */
    protected $borneGauche;

    /**
     * @ORM\Column(name="ORGA_BORNE_DROITE", type="integer")
     */
    protected $borneDroite;

    /**
     * @ORM\Column(name="ORGA_NB_DESCENDANT", type="integer")
     */
    protected $nbDescendant;
    
    /**
     * @ORM\Column(name="ORGA_DATE_DEBUT", type="datetime")
     */
    protected $dateDebut;
    
    /**
     * @ORM\Column(name="ORGA_DATE_FIN", type="datetime")
     */
    protected $dateFin;
    
    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    protected $affectations;
    
    
    
    /**
     * @var \ArrayObject
     **/
    protected $agents = null;
    
    /**
     * @var \ArrayObject
     */
    protected $responsable = null;
    
    


    public function __construct() {
        $this->affectations = new ArrayCollection();
    }
    
    
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getAgents(\DateTime $dateRef = null)
    {
        return is_null($this->agents) ? $this->agents = $this->getRepository()->getAgents($this, $dateRef) : $this->agents;
    }
    
    public function getResponsable(\DateTime $dateRef = null)
    {
        return is_null($this->responsable) ? $this->responsable = $this->getRepository()->getAgentResponsible($this, $dateRef) : $this->responsable;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getNiveau()
    {
        return $this->niveau;
    }

    public function getBorneGauche()
    {
        return $this->borneGauche;
    }

    public function getBorneDroite()
    {
        return $this->borneDroite;
    }

    public function getNbDescendant()
    {
        return $this->nbDescendant;
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

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }

    public function setBorneGauche($borneGauche)
    {
        $this->borneGauche = $borneGauche;
    }

    public function setBorneDroite($borneDroite)
    {
        $this->borneDroite = $borneDroite;
    }

    public function setNbDescendant($nbDescendant)
    {
        $this->nbDescendant = $nbDescendant;
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
