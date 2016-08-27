<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\ScoreRepository")
 * @ORM\Table(name="bowl_score")
 * @author Christian Alcon
 */
class Score extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_score_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Serie", inversedBy="scores", fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_score_serie_id", referencedColumnName="bowl_serie_id")
     */
    private $serie;

    /**
     * @ORM\Column(name="bowl_score_score", type="integer")
     */
    private $score;

    /**
     * @ORM\Column(name="bowl_score_strike", type="integer")
     */
    private $strike;

    /**
     * @ORM\Column(name="bowl_score_spare", type="integer")
     */
    private $spare;

    /**
     * @ORM\Column(name="bowl_score_split", type="integer")
     */
    private $split;

    /**
     * @ORM\Column(name="bowl_score_gagnee", type="boolean")
     */
    private $gagnee;

    /**
     * @ORM\Column(name="bowl_score_comptePasPourMoyenne", type="boolean")
     */
    private $pasCalculMoyenne;

    /**
     * @ORM\Column(name="bowl_score_surCarnetEntrainement", type="boolean")
     */
    private $surCarnetEntrainement;

    /**
     * @ORM\Column(name="bowl_score_7_10", type="boolean")
     */
    private $septDix;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_score_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_score_updated_on", type="datetime")
     */
    private $modifieLe;

    function getId() {
        return $this->id;
    }

    function getSerie() {
        return $this->serie;
    }

    function getScore() {
        return $this->score;
    }

    function getStrike() {
        return $this->strike;
    }

    function getSpare() {
        return $this->spare;
    }

    function getSplit() {
        return $this->split;
    }

    function getGagnee() {
        return $this->gagnee;
    }

    function getPasCalculMoyenne() {
        return $this->pasCalculMoyenne;
    }

    function getSurCarnetEntrainement() {
        return $this->surCarnetEntrainement;
    }

    function getSeptDix() {
        return $this->septDix;
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

    function setSerie($serie) {
        $this->serie = $serie;
    }

    function setScore($score) {
        $this->score = $score;
    }

    function setStrike($strike) {
        $this->strike = $strike;
    }

    function setSpare($spare) {
        $this->spare = $spare;
    }

    function setSplit($split) {
        $this->split = $split;
    }

    function setGagnee($gagnee) {
        $this->gagnee = $gagnee;
    }

    function setPasCalculMoyenne($pasCalculMoyenne) {
        $this->pasCalculMoyenne = $pasCalculMoyenne;
    }

    function setSurCarnetEntrainement($surCarnetEntrainement) {
        $this->surCarnetEntrainement = $surCarnetEntrainement;
    }

    function setSeptDix($septDix) {
        $this->septDix = $septDix;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
