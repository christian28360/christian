<?php

namespace CHRIST\Modules\Vocabulaire\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Vocabulaire\Repository\MotRepository")
 * @ORM\Table(name="vocab_mot")
 * @author Christian Alcon
 */
class Mot extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="vocab_mot_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    // pour le bouton valider + nouvelle saisie
    private $nouveauMot;

    function getNouveauMot() {
        return $this->nouveauMot;
    }

    function setNouveauMot($nouveauMot) {
        $this->nouveauMot = $nouveauMot;
    }

    // fin pour le bouton valider + nouvelle saisie

    /**
     * @ORM\ManyToOne(targetEntity="CHRIST\Modules\Livre\Entity\Livre", inversedBy="mots", fetch="LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="vocab_mot_livre", referencedColumnName="livre_id")
     */
    private $livre;

    public function __construct() {
        $this->setCreeLe(new \DateTime);
        $this->setAApprendre(True);
    }

    /**
     * @ORM\Column(name="vocab_mot_mot", type="string")
     */
    private $mot;

    /**
     * @ORM\ManyToOne(targetEntity="TypeMot", inversedBy="typeMot", fetch="LAZY")
     * @ORM\JoinColumn(name="vocab_mot_type", referencedColumnName="vocab_type_id")
     */
    private $typeMot;

    /**
     * @ORM\ManyToMany(targetEntity="dictionnaire", inversedBy="mots")
     * @ORM\JoinTable(name="vocab_trouve_dans_dico",
     *      joinColumns={@ORM\JoinColumn(name="vocab_dico_mot_id", referencedColumnName="vocab_mot_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vocab_dico_dico_id", referencedColumnName="vocab_dico_id")}
     *      )
     * @var trouveDansDicos
     */
    private $trouveDansDicos;

    /**
     * @ORM\ManyToMany(targetEntity="dictionnaire", inversedBy="mots")
     * @ORM\JoinTable(name="vocab_trouve_dans_aucun_dico",
     *      joinColumns={@ORM\JoinColumn(name="vocab_dico_mot_id", referencedColumnName="vocab_mot_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vocab_dico_dico_id", referencedColumnName="vocab_dico_id")}
     *      )
     * @var pasTrouveDansDicos
     */
    private $pasTrouveDansDicos;

    /**
     * @ORM\Column(name="vocab_mot_page", type="integer")
     */
    private $page;

    /**
     * @ORM\Column(name="vocab_mot_a_apprendre", type="boolean")
     */
    private $aApprendre;

    /**
     * @ORM\Column(name="vocab_mot_origine", type="string")
     */
    private $origine;

    /**
     * @ORM\Column(name="vocab_mot_signification", type="string")
     */
    private $signification;

    /**
     * @ORM\Column(name="vocab_mot_synonymes", type="string")
     */
    private $synonymes;

    /**
     * @ORM\Column(name="vocab_mot_extrait_livre", type="string")
     */
    private $extraitLivre;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="vocab_mot_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="vocab_mot_updated_on", type="datetime")
     */
    private $modifieLe;

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {

        $metadata->addPropertyConstraint('mot', new Assert\NotBlank());
        $metadata->addPropertyConstraint('mot', new Assert\Length(array('min' => 1, 'max' => 25)));

        $metadata->addPropertyConstraint('livre', new Assert\NotBlank());
        $metadata->addPropertyConstraint('signification', new Assert\NotBlank());
        $metadata->addPropertyConstraint('signification', new Assert\Length(array('min' => 1, 'max' => 1200)));

        $metadata->addPropertyConstraint('synonymes', new Assert\Length(array('min' => 0, 'max' => 500)));
        $metadata->addPropertyConstraint('origine', new Assert\Length(array('min' => 0, 'max' => 1000)));
        $metadata->addPropertyConstraint('page', new Assert\GreaterThan(array('value' => 0,
            'message' => 'Si saisi, doit être supérieur à zéro')));

        $metadata->addPropertyConstraint('extraitLivre', new Assert\Length(array('min' => 0, 'max' => 1000)));
    }

    function __toString() {
        return $this->mot;
    }

    function getId() {
        return $this->id;
    }

    function getLivre() {
        return $this->livre;
    }

    function getMot() {
        return $this->mot;
    }

    function getTypeMot() {
        return $this->typeMot;
    }

    function getSignification() {
        return $this->signification;
    }

    function getTrouveDansDicos() {
        return $this->trouveDansDicos;
    }

    function getPasTrouveDansDicos() {
        return $this->pasTrouveDansDicos;
    }

    function getPage() {
        return $this->page;
    }

    function getAApprendre() {
        return $this->aApprendre;
    }

    function getOrigine() {
        return $this->origine;
    }

    function getSynonymes() {
        return $this->synonymes;
    }

    function getExtraitLivre() {
        return $this->extraitLivre;
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

    function setLivre($livre) {
        $this->livre = $livre;
    }

    function setMot($mot) {
        $this->mot = $mot;
    }

    function setTypeMot($typeMot) {
        $this->typeMot = $typeMot;
    }

    function setSignification($signification) {
        $this->signification = $signification;
    }

    function setTrouveDansDicos($trouveDansDicos) {
        $this->trouveDansDicos = $trouveDansDicos;
    }

    function setPasTrouveDansDicos($pasTrouveDansDicos) {
        $this->pasTrouveDansDicos = $pasTrouveDansDicos;
    }

    function setPage($page) {
        $this->page = $page;
    }

    function setAApprendre($aApprendre) {
        $this->aApprendre = $aApprendre;
    }

    function setOrigine($origine) {
        $this->origine = $origine;
    }

    function setSynonymes($synonymes) {
        $this->synonymes = $synonymes;
    }

    function setExtraitLivre($extraitLivre) {
        $this->extraitLivre = $extraitLivre;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
