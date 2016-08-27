<?php

namespace CHRIST\Modules\Vocabulaire\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Vocabulaire\Repository\TypeMotRepository")
 * @ORM\Table(name="vocab_type_mot")
 * @author Christian Alcon
 */
class TypeMot extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="vocab_type_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Mot", mappedBy="typeMot")
     * @ORM\OrderBy({"mot" = "ASC"})
     */
    protected $mots;

    /**
     * @ORM\Column(name="vocab_type_type", type="string")
     */
    private $typeMot;

    /**
     * @ORM\Column(name="vocab_type_sous_type", type="string")
     */
    private $sousTypeMot;

    /**
     * @ORM\Column(name="vocab_type_sous_sous_type", type="string")
     */
    private $sousSousTypeMot;

    /**
     * @ORM\Column(name="vocab_type_libelle", type="string")
     */
    private $libelle;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="vocab_type_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="vocab_type_updated_on", type="datetime")
     */
    private $modifieLe;

    function __toString() {

        $str = '';
        $str .= is_null($this->typeMot) ? '' : $this->typeMot;
        $str .= is_null($this->sousTypeMot) ? '' : ', ' . $this->sousTypeMot;
        $str .= is_null($this->sousSousTypeMot) ? '' : ', ' . $this->sousSousTypeMot;

        return $str;
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => array('typeMot', 'sousTypeMot', 'sousSousTypeMot'),
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('typeMot', new Assert\NotBlank());
        $metadata->addPropertyConstraint('typeMot', new Assert\Length(array('min' => 1, 'max' => 20)));
        $metadata->addPropertyConstraint('sousTypeMot', new Assert\Length(array('min' => 0, 'max' => 20)));
        $metadata->addPropertyConstraint('sousSousTypeMot', new Assert\Length(array('min' => 0, 'max' => 20)));
        $metadata->addPropertyConstraint('libelle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('libelle', new Assert\Length(array('min' => 0, 'max' => 200)));
    }

    function getId() {
        return $this->id;
    }

    function getTypeMot() {
        return $this->typeMot;
    }

    function getSousTypeMot() {
        return $this->sousTypeMot;
    }

    function getSousSousTypeMot() {
        return $this->sousSousTypeMot;
    }

    function getLibelle() {
        return $this->libelle;
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

    function setTypeMot($typeMot) {
        $this->typeMot = $typeMot;
    }

    function setSousTypeMot($sousTypeMot) {
        $this->sousTypeMot = $sousTypeMot;
    }

    function setSousSousTypeMot($sousSousTypeMot) {
        $this->sousSousTypeMot = $sousSousTypeMot;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
