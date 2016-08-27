<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\FormationRepository")
 * @ORM\Table(name="bowl_formation")
 * @author Christian Alcon
 */
class Formation extends \CHRIST\Common\Entity\Master\AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_form_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_form_code", type="string")
     */
    private $code;

    /**
     * @ORM\Column(name="bowl_form_libelle", type="string")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="Evenement", mappedBy="formation")
     * @ORM\OrderBy({"code" = "ASC"})
     * @var arrayCollection
     */
    private $evenements;
 
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_form_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_form_updated_on", type="datetime")
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
            'fields' => 'code',
            'em' => 'christian',
        )));

        $metadata->addPropertyConstraint('code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('code', new Assert\Length(array('min' => 1, 'max' => 5)));
        $metadata->addPropertyConstraint('libelle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('libelle', new Assert\Length(array('min' => 1, 'max' => 100)));
    }
    
    function getId()
    {
        return $this->id;
    }

    function getCode()
    {
        return $this->code;
    }

    function getLibelle()
    {
        return $this->libelle;
    }

    function getEvenements() {
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

    function setCode($code)
    {
        $this->code = $code;
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
