<?php

/**
 * Description of Evenement
 *
 * @author Christian Alcon
 */

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\EvenementRepository")
 * @ORM\Table(name="bowl_evenement")
 * @author Christian Alcon
 */
class Evenement extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_evt_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    function __construct($code = NULL) {
        $this->code= $code;
    }

    /**
     * @ORM\Column(name="bowl_evt_code", type="string")
     */
    private $code;

    /**
     * @ORM\Column(name="bowl_evt_libelle", type="string")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="Journee", mappedBy="evenement")
     * @ORM\OrderBy({"dateJournee" = "DESC"})
     * @var arrayCollection
     */
    private $journees;

    /**
     * @ORM\ManyToOne(targetEntity="TypeJeu"), inversedBy="typeJeu", fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_evt_typeJeu_id", referencedColumnName="bowl_type_id")
     */
    private $typeJeu;

    /**
     * @ORM\ManyToOne(targetEntity="Formation"), inversedBy="formation", fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_evt_formation_id", referencedColumnName="bowl_form_id")
     */
    private $formation;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_evt_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_evt_updated_on", type="datetime")
     */
    private $modifieLe;

    function __toString()
    {
        return is_null($this->formation) ? '' : $this->formation;
    }
    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => 'code',
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('code', new Assert\Length(array('min' => 1, 'max' => 10)));
        $metadata->addPropertyConstraint('libelle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('libelle', new Assert\Length(array('min' => 1, 'max' => 50)));
    }

    function getId() {
        return $this->id;
    }

    function getCode() {
        return $this->code;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getJournees() {
        return $this->journees;
    }

    function getEvt() {
        return $this->getTypeJeu() . '-' . $this->libelle;
    }

    function getTypeJeu() {
        return $this->typeJeu;
    }

    function getFormation() {
        return $this->formation;
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

    function setCode($code) {
        $this->code = strtoupper($code);
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setJournees($journees) {
        $this->journees = $journees;
    }

    function setTypeJeu($typeJeu) {
        $this->typeJeu = $typeJeu;
    }

    function setFormation($formation) {
        $this->formation = $formation;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
