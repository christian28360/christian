<?php

namespace CHRIST\Modules\Livre\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use CHRIST\Common\Kernel\MasterController;
use CHRIST\Common\Kernel\Helpers\PhpHelpers;

//use CHRIST\Modules\Livre\Entity as Livre;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Livre\Repository\ThemeRepository")
 * @ORM\Table(name="liv_theme")
 * @author Christian Alcon
 */
class Theme extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="liv_them_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Livre", mappedBy="theme")
     * @ORM\OrderBy({"titre" = "ASC"})
     */
    protected $livres;

    function __construct() {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function __toString() {
        return $this->getLibelle();
    }

    /**
     * @ORM\Column(name="liv_them_libelle", type="string")
     * @var string
     */
    private $libelle;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="liv_them_created_on", type="datetime")
     * @var datetime
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="liv_them_updated_on", type="datetime")
     * @var datetime
     */
    private $modifieLe;

    function getId() {
        return $this->id;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getLivres() {
        return $this->livres;
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

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setLivres($livres) {
        $this->livres = $livres;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
