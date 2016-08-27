<?php

namespace CHRIST\Modules\Livre\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Livre\Repository\EcrivainRepository")
 * @ORM\Table(name="liv_ecrivain")
 * @author Christian Alcon
 */
class Ecrivain extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="liv_ecri_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="liv_ecri_nom", type="string")
     */
    private $nom;

    /**
     * @ORM\Column(name="liv_ecri_prenom", type="string")
     */
    private $prenom;

    /**
     * @ORM\Column(name="liv_ecri_nationalite", type="string")
     */
    private $nationalite;

    /**
     * @ORM\Column(name="liv_ecri_remarques", type="string")
     */
    private $remarques;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="liv_ecri_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="liv_ecri_updated_on", type="datetime")
     * @var \DateTime
     */
    private $modifieLe;

    /**
     * @ORM\ManyToMany(targetEntity="Livre", mappedBy="auteurs")
     * @ORM\OrderBy({"titre" = "ASC"})
     * @ORM\JoinTable(name="liv_auteur")
     * @var livres[]
     */
    protected $livres;

    function __construct() {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function __toString() {
        return (is_null($this->getPrenom()) ? '' : $this->getPrenom() . ' ') . $this->getNom();
    }

    function getLivres() {
        return $this->livres;
    }

    function setLivres($livres) {
        $this->livres = $livres;
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return toUTF8($this->nom);
    }

    function getPrenom() {
        return toUTF8($this->prenom);
    }

    function getPrenomNom() {
        return $this->getPrenom() . ' ' . $this->getNom();
    }

    function getNomPrenom() {
        return $this->getNom() . ', ' . $this->getPrenom();
    }

    function getNationalite() {
        return toUTF8($this->nationalite);
    }

    function getRemarques() {
        return toUTF8($this->remarques);
    }

    function getCreeLe() {
        return $this->creeLe;
    }

    function getModifieLe() {
        return $this->modifieLe;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setNationalite($nationalite) {
        $this->nationalite = $nationalite;
    }

    function setRemarques($remarques) {
        $this->remarques = $remarques;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
