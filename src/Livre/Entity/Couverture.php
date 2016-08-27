<?php

namespace CHRIST\Modules\Livre\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Livre\Repository\CouvertureRepository")
 * @ORM\Table(name="liv_couverture")
 * @author Christian Alcon
 */
class Couverture extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="liv_couv_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Livre", mappedBy="couverture")
     * @ORM\OrderBy({"titre" = "ASC"})
     * @var livres[]
     */
    protected $livres;
    
    function __construct()
    {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function getLivres() {
        return $this->livres;
    }

    function setLivres($livres) {
        $this->livres = $livres;
    }

    /**
     * @ORM\Column(name="liv_couv_libelle", type="string")
     */
    private $libelle;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="liv_couv_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="liv_couv_updated_on", type="datetime")
     */
    private $modifieLe;

    function getId() {
        return $this->id;
    }

    function getLibelle() {
        return toUTF8($this->libelle);
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
        $this->libelle = trim($libelle);
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
