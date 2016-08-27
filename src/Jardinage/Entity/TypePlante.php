<?php

namespace CHRIST\Modules\Jardinage\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Jardinage\Repository\TypePlanteRepository")
 * @ORM\Table(name="jard_type_plante")
 * @author Christian Alcon
 */
class TypePlante extends \CHRIST\Common\Entity\Master\AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="jard_type_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="jard_type_code", type="string")
     */
    private $typePlante;

    /**
     * @ORM\Column(name="jard_type_libelle", type="string")
     */
    private $libelle;
    
    /**
     * @ORM\Column(name="jard_type_commentaire", type="string")
     */
    private $commentaire;

     /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="jard_type_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="jard_type_updated_on", type="datetime")
     */
    private $modifieLe;

    function __toString()
    {
        return $this->libelle;
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => 'typePlante',
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('typePlante', new Assert\NotBlank());
        $metadata->addPropertyConstraint('typePlante', new Assert\Length(array('min' => 1, 'max' => 3)));
        $metadata->addPropertyConstraint('libelle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('libelle', new Assert\Length(array('min' => 1, 'max' => 50)));
    }

    function getId()
    {
        return $this->id;
    }

    function getTypePlante()
    {
        return $this->typePlante;
    }

    function getLibelle()
    {
        return $this->libelle;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function getCreeLe()
    {
        return $this->creeLe;
    }

    function getModifieLe()
    {
        return $this->modifieLe;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setTypePlante($typePlante)
    {
        $this->typePlante = $typePlante;
    }

    function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    function setCreeLe($creeLe)
    {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe)
    {
        $this->modifieLe = $modifieLe;
    }

}
