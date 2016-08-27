<?php

/**
 * Description of Serie
 *      c'est une série de scores portée par un évènement
 *      à une date déterminée pour une journée de bowling
 *
 * @author ALCON Christian
 */

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\SerieRepository")
 * @ORM\Table(name="bowl_serie")
 * @author Christian Alcon
 */
class Serie extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_serie_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_serie_no", type="integer")
     */
    private $noSerie;

    /**
     * @ORM\ManyToOne(targetEntity="Journee", inversedBy="series", fetch="LAZY", cascade={"persist", "remove", "merge"})
     * @ORM\JoinColumn(name="bowl_serie_jnee_id", referencedColumnName="bowl_jnee_id")
     * @ORM\OrderBy({"journee" = "DESC", "noSerie" = "DESC"})
     */
    private $journee;

    /**
     * @ORM\OneToMany(targetEntity="Score", mappedBy="serie", cascade={"persist", "remove", "merge"})
     * RM\JoinColumn(name="bowl_serie_score_id", referencedColumnName="bowl_score_id")
     */
    private $scores;

    /**
     * @ORM\Column(name="bowl_serie_date", type="date")
     */
    private $dateSerie;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_serie_created_on", type="date")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_serie_updated_on", type="date")
     */
    private $modifieLe;

    function __construct() {
        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();

        $score = new Score();
        $this->scores->add($score);
    }

    function getId() {
        return $this->id;
    }

    function getJournee() {
        return $this->journee;
    }

    function getNoSerie() {
        return $this->noSerie;
    }

    function getScores() {
        return $this->scores;
    }

    function getTotScores() {

        $t = 0;

        foreach ($this->getScores() as $score) {
            if (!$score->getPasCalculMoyenne()) {
                $t += $score->getScore();
            }
        }

        return $t;
    }

    function getMoyenne() {

        $m = $i = 0;

        foreach ($this->getScores() as $score) {
            if (!$score->getPasCalculMoyenne()) {
                $m += $score->getScore();
                $i++;
            }
        }

        return $i == 0 ? 0 : $m / $i;
    }

    function getNbScores() {
        return $this->getScores()->count();
    }

    function getDateSerie() {
        return $this->dateSerie;
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

    function setNoSerie($noSerie) {
        $this->noSerie = $noSerie;
    }

    function setJournee($journee) {
        $this->journee = $journee;
    }

    function setScores($scores) {
        $this->scores = $scores;
    }

    function setDateSerie($dateSerie) {
        $this->dateSerie = $dateSerie;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
