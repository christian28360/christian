<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\OrganisateurRepository")
 * @ORM\Table(name="bowl_organisateur")
 * @author Christian Alcon
 */
class Organisateur extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_org_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_org_nom", type="string")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Tournoi", mappedBy="organisateur")
     * @var arrayCollection
     */
    private $tournois;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_org_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_org_updated_on", type="datetime")
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
        $metadata->addPropertyConstraint('nom', new Assert\Length(array('min' => 1, 'max' => 255)));
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getTournois() {
        return $this->tournois;
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

    function setTournois($tournois) {
        $this->tournois = $tournois;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
