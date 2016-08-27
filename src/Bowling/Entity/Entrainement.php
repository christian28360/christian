<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Modules\Bowling\Entity;

/**
 * Description of Entrainement
 *
 * @author Christian ALCON
 */
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\EntrainementRepository")
 * @ORM\Table(name="bowl_entrainement")
 */
class Entrainement extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_entr_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    function __construct() {
        //   $this->getTypeJeu() = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Column(name="bowl_entr_commentaire", type="string")
     */
    private $commentaire;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_entr_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_entr_updated_on", type="datetime")
     */
    private $modifieLe;

    function getId() {
        return $this->id;
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