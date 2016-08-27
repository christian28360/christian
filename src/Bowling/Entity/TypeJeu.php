<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\TypeJeuRepository")
 * @ORM\Table(name="bowl_type_jeu")
 * @author Christian Alcon
 */
class TypeJeu extends \CHRIST\Common\Entity\Master\AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_type_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_type_type", type="string")
     */
    private $typeJeu;

    /**
     * @ORM\Column(name="bowl_type_libelle", type="string")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="Evenement", mappedBy="typeJeu")
     * @ORM\OrderBy({"code" = "ASC"})
     * @var arrayCollection
     */
    private $evenements;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_type_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_type_updated_on", type="datetime")
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
            'fields' => 'typeJeu',
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('typeJeu', new Assert\NotBlank());
        $metadata->addPropertyConstraint('typeJeu', new Assert\Length(array('min' => 1, 'max' => 1)));
        $metadata->addPropertyConstraint('libelle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('libelle', new Assert\Length(array('min' => 1, 'max' => 15)));
    }

    function getId()
    {
        return $this->id;
    }

    function getTypeJeu()
    {
        return $this->typeJeu;
    }

    function getLibelle()
    {
        return $this->libelle;
    }

    function getEvenements()
    {
        return $this->evenements;
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

    function setTypeJeu($typeJeu)
    {
        $this->typeJeu = $typeJeu;
    }

    function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
