<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\BowlingRepository")
 * @ORM\Table(name="bowl_bowling")
 * @author Christian Alcon
 */
class Bowling extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_bowling_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_bowling_nom", type="string")
     */
    private $nom;

    /**
     * @ORM\Column(name="bowl_bowling_commentaire", type="string")
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity="Journee", mappedBy="bowling")
     * @ORM\OrderBy({"dateJournee" = "DESC"})
     * @var arrayCollection
     */
    private $journees;

    /**
     * @ORM\Column(name="bowl_bowling_alias", type="string")
     */
    private $alias;

    /**
     * @ORM\Column(name="bowl_bowling_adr_no", type="string")
     */
    private $adrNo;

    /**
     * @ORM\Column(name="bowl_bowling_adr_rue", type="string")
     */
    private $adrRue;

    /**
     * @ORM\Column(name="bowl_bowling_adr_adr1", type="string")
     */
    private $adr1;

    /**
     * @ORM\Column(name="bowl_bowling_adr_adr2", type="string")
     */
    private $adr2;

    /**
     * @ORM\Column(name="bowl_bowling_adr_adr3", type="string")
     */
    private $adr3;

    /**
     * @ORM\Column(name="bowl_bowling_adr_cp", type="integer")
     */
    private $adrCp;

    /**
     * @ORM\Column(name="bowl_bowling_adr_ville", type="string")
     */
    private $ville;

    /**
     * @ORM\Column(name="bowl_bowling_adr_pays", type="string")
     */
    private $pays;

    /**
     * @ORM\Column(name="bowl_bowling_tel_1", type="string")
     */
    private $telephone1;

    /**
     * @ORM\Column(name="bowl_bowling_tel_2", type="string")
     */
    private $telephone2;

    /**
     * @ORM\Column(name="bowl_bowling_mail", type="string")
     */
    private $mail;

    /**
     * @ORM\Column(name="bowl_bowling_web", type="string")
     */
    private $siteWeb;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_bowling_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_bowling_updated_on", type="datetime")
     */
    private $modifieLe;

    function __toString() {
        return (is_null($this->nom) || is_object($this->nom) ) ? '' : $this->nom;
    }

    function __construct($nom = NULL) {
        $this->nom = $nom;
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => 'nom',
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('nom', new Assert\NotBlank());
        $metadata->addPropertyConstraint('nom', new Assert\Length(array('min' => 1, 'max' => 100)));
        $metadata->addPropertyConstraint('commentaire', new Assert\Length(array('min' => 0, 'max' => 100)));
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function getJournees() {
        return $this->journees;
    }

    function getAlias() {
        return $this->alias;
    }

    function getAdrNo() {
        return $this->adrNo;
    }

    function getAdrRue() {
        return $this->adrRue;
    }

    function getAdr1() {
        return $this->adr1;
    }

    function getAdr2() {
        return $this->adr2;
    }

    function getAdr3() {
        return $this->adr3;
    }

    function getAdrCp() {
        return $this->adrCp;
    }

    function getVille() {
        return $this->ville;
    }

    function getPays() {
        return $this->pays;
    }

    function getTelephone1() {
        return $this->telephone1;
    }

    function getTelephone2() {
        return $this->telephone2;
    }

    function getMail() {
        return $this->mail;
    }

    function getSiteWeb() {
        return $this->siteWeb;
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

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    function setJournees($journees) {
        $this->journees = $journees;
    }

    function setAlias($alias) {
        $this->alias = $alias;
    }

    function setAdrNo($adrNo) {
        $this->adrNo = $adrNo;
    }

    function setAdrRue($adrRue) {
        $this->adrRue = $adrRue;
    }

    function setAdr1($adr1) {
        $this->adr1 = $adr1;
    }

    function setAdr2($adr2) {
        $this->adr2 = $adr2;
    }

    function setAdr3($adr3) {
        $this->adr3 = $adr3;
    }

    function setAdrCp($adrCp) {
        $this->adrCp = $adrCp;
    }

    function setVille($ville) {
        $this->ville = $ville;
    }

    function setPays($pays) {
        $this->pays = $pays;
    }

    function setTelephone1($telephone1) {
        $this->telephone1 = $telephone1;
    }

    function setTelephone2($telephone2) {
        $this->telephone2 = $telephone2;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setSiteWeb($siteWeb) {
        $this->siteWeb = $siteWeb;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
