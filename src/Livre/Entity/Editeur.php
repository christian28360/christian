<?php

namespace CHRIST\Modules\Livre\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Livre\Repository\EditeurRepository")
 * @ORM\Table(name="liv_editeur")
 * @author Christian Alcon
 */
class Editeur extends \CHRIST\Common\Entity\Master\AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="liv_edit_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="liv_edit_nom", type="string")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Livre", mappedBy="editeur")
     * @ORM\OrderBy({"titre" = "ASC"})
     * @var livres[]
     */
    protected $livres;

    function __construct()
    {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function getLivres()
    {
        return $this->livres;
    }

    function setLivres($livres)
    {
        $this->livres = $livres;
    }

    /**
     * @ORM\Column(name="liv_edit_remarques", type="string")
     */
    private $remarques;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="liv_edit_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="liv_edit_updated_on", type="datetime")
     */
    private $modifieLe;

    function getId()
    {
        return $this->id;
    }

    function getNom()
    {
        return toUTF8($this->nom);
    }

    function getRemarques()
    {
        return toUTF8($this->remarques);
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

    function setNom($nom)
    {
        $this->nom = $nom;
    }

    function setRemarques($remarques)
    {
        $this->remarques = $remarques;
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
