<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use CHRIST\Common\Entity\Master\AbstractEntity;

/**
 * Description
 *
 * @author glr735
 */
abstract class AbstractAgentDTC extends AbstractEntity implements \CHRIST\Common\Interfaces\IAgentDTC
{        
    /**
     * @ORM\Id
     * @ORM\Column(name="AGEN_CODE_RH", type="string")
     */
    protected $codeRH;

    /**
     * @ORM\Column(name="AGEN_CIVILITE", type="string")
     */
    protected $civilite;

    /**
     * @ORM\Column(name="AGEN_PRENOM", type="string")
     */
    protected $prenom;

    /**
     * @ORM\Column(name="AGEN_NOM", type="string")
     */
    protected $nom;
    
    /**
     * @ORM\Column(name="AGEN_DATE_ARRIVEE", type="datetime")
     */
    protected $dateArrivee;

    /**
     * @ORM\Column(name="AGEN_DATE_DEPART", type="datetime")
     */
    protected $dateDepart;
    
    /**
     * Used in the extended class
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    protected $affectations;
    
    
    

    public function __construct() {
        $this->affectations = new ArrayCollection();
    }
    
    /**
     * Return url of the photo of the agent
     * @return string
     */
    public function getUrlPhoto()
    {
        $url = 'http://www.dtc.courrier.intra.laposte.fr/atoll/photo/' . trim($this->codeRH) . '.jpg';
        
        
        if (($handle = @fopen($url, 'r')) !== false) {
            fclose($handle);
            return $url;
        }
        
        return '/img/common/anonyme.png';
    }
    
    public function getCodeRH()
    {
        return $this->codeRH;
    }

    public function getCivilite()
    {
        return $this->civilite;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    public function setCodeRH($codeRH)
    {
        $this->codeRH = $codeRH;
    }

    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setDateArrivee($dateArrivee)
    {
        $this->dateArrivee = $dateArrivee;
    }

    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
    }


}
