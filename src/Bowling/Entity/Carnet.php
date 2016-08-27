<?php

namespace CHRIST\Modules\Bowling\Entity;

/**
 * Description of Carnet
 *
 * @author ezs824
 */
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\CarnetRepository")
 * @ORM\Table(name="bowl_carnet_entrainement")
 * @author Christian Alcon
 */
class Carnet extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_carn_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    function __construct() {
        //   
    }

    /**
     * @ORM\Column(name="bowl_carn_commentaire", type="string")
     */
    private $commentaire;

    /**
     * @ORM\Column(name="bowl_carn_prix", type="decimal")
     */
    private $prix;

    /**
     * @ORM\Column(name="bowl_carn_nb_parties", type="integer")
     */
    private $nbParties;

    /* colonnes calculées dynamiquement : */
    private $nbPartiesJouees;
    private $series;

    /**
     * @ORM\Column(name="bowl_carn_date_achat", type="date")
     */
    private $dateAchat;

    /**
     * @ORM\Column(name="bowl_carn_date_fin_validite", type="date")
     */
    private $dateFinValidite;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_carn_created_on", type="date")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_carn_updated_on", type="date")
     */
    private $modifieLe;

    function __toString() {
        return $this->commentaire;
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => 'dateAchat',
            'em' => 'christian',
        )));
        $metadata->addPropertyConstraint('commentaire', new Assert\Length(array('min' => 0, 'max' => 50)));
    }

    function getId() {
        return $this->id;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function getPrix() {
        return $this->prix;
    }

    function getNbParties() {
        return $this->nbParties;
    }

    function getNbPartiesJouees() {
        return $this->nbPartiesJouees;
    }

    function getSeries() {
        return $this->series;
    }

    function getDateAchat() {
        return $this->dateAchat;
    }

    function getDateFinValidite() {
        return $this->dateFinValidite;
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

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    function setNbParties($nbParties) {
        $this->nbParties = $nbParties;
    }

    function setNbPartiesJouees($nbPartiesJouees) {
        $this->nbPartiesJouees = $nbPartiesJouees;
    }

    function setSeries($series) {
        $this->series = $series;
    }

    function setPrix($prix) {
        $this->prix = $prix;
    }

    function setDateAchat($dateAchat) {
        $this->dateAchat = $dateAchat;
    }

    function setDateFinValidite($dateFinValidite) {
        $this->dateFinValidite = $dateFinValidite;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
