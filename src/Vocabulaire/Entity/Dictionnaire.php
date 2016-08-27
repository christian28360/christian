<?php

namespace CHRIST\Modules\Vocabulaire\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Vocabulaire\Repository\DictionnaireRepository")
 * @ORM\Table(name="vocab_dictionnaire")
 * @author Christian Alcon
 */
class Dictionnaire extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="vocab_dico_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="vocab_dico_nom", type="string")
     */
    private $nom;

    /**
     * @ORM\Column(name="vocab_dico_commentaire", type="string")
     */
    private $commentaire;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="vocab_dico_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="vocab_dico_updated_on", type="datetime")
     */
    private $modifieLe;

    function __toString() {
        return $this->nom;
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

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
