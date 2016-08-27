<?php

/**
 * Description of Journee
 *      c'est une série de scores portée par un évènement
 *      à une date déterminée
 *
 * @author ALCON Christian
 */

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\JourneeRepository")
 * @ORM\Table(name="bowl_journee")
 * @author Christian Alcon
 */
class Journee extends \CHRIST\Common\Entity\Master\AbstractEntity {

    private $nouvelleJnee;

    function getNouvelleJnee() {
        return $this->nouvelleJnee;
    }

    function setNouvelleJnee($nouvelleJnee) {
        $this->nouvelleJnee = $nouvelleJnee;
    }

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_jnee_id", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Evenement", inversedBy="scores", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_jnee_evt_id", referencedColumnName="bowl_evt_id")
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity="Periode", inversedBy="journees", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_jnee_periode_id", referencedColumnName="bowl_periode_id")
     */
    private $periode;

    /**
     * @ORM\Column(name="bowl_jnee_handicap", type="integer")
     */
    private $handicap;

    /**
     * @ORM\OneToMany(targetEntity="Serie", mappedBy="journee", cascade={"persist", "remove", "merge"})
     * @ORM\OrderBy({"noSerie" = "DESC"})
     */
    protected $series;

    /**
     * @ORM\Column(name="bowl_jnee_date", type="date")
     */
    private $dateJournee;

    /**
     * @ORM\ManyToOne(targetEntity="Bowling", cascade={"persist"})
     * @ORM\JoinColumn(name="bowl_jnee_bowling_id", referencedColumnName="bowl_bowling_id")
     */
    private $bowling;

    /**
     * @ORM\OneToOne(targetEntity="Tournoi")
     * @ORM\JoinColumn(name="bowl_jnee_tournoi_id", referencedColumnName="bowl_tournoi_id")
     */
    private $tournoi;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_jnee_created_on", type="date")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_jnee_updated_on", type="date")
     */
    private $modifieLe;

    function __construct(\CHRIST\Modules\Bowling\Entity\Periode $periode, \CHRIST\Modules\Bowling\Entity\Bowling $bowling = NULL) {
    
        $this->periode = $periode;
        $this->bowling = $bowling;
        $this->tournoi = "";

        // initialiser une série avec n° à 1
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
        $serie = new Serie();
        $serie->setNoSerie(1);
        $this->series->add($serie);
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {

        $metadata->addConstraint(
                new Assert\Callback(function ($object,
                \Symfony\Component\Validator\ExecutionContextInterface $context) {

            // données de la journée :
            if (!_between($object->getHandicap(), 0, 80)) {
                $context->addViolation(
                        'Journée : le handicap (' . $object->getHandicap() . ') doit être compris entre 0 et 80');
            }

            // Contrôle des dates (journée et séries)
            foreach ($object->getSeries() as $sr) {
                $nbJours = $object->getDateJournee()->diff($sr->getDateSerie())->days;
                if ($nbJours > 3) {
                    $context->addViolation(
                            'La date de la série ' .
                            $sr->getNoSerie() . ', ' .
                            $sr->getDateSerie()->format('d/m/Y') .
                            ' a ' . $nbJours . ' jours d\'écart (plus de 3) avec la date de la journée : ' .
                            $object->getDateJournee()->format('d/m/Y')
                    );
                }
                if ($object->getDateJournee() < $sr->getDateSerie()) {
                    $context->addViolation(
                            'La date de la série ' .
                            $sr->getNoSerie() . ', ' .
                            $sr->getDateSerie()->format('d/m/Y') .
                            ' est supérieure à la date de la journée : ' .
                            $object->getDateJournee()->format('d/m/Y')
                    );
                }
            }

            // données séries et scores
            $noSerie = array();  // le n° de série doit être unique

            foreach ($object->getSeries() as $sr) {
                $noSerie[] = $sr->getNoSerie();
                // la série ne doit pas être vide (sans score)
                if (count($sr->getScores()) == 0) {
                    $context->addViolationAt(
                            'série', 'la série (' . $sr->getNoSerie() . ') n\'a aucun score saisi !');
                }
                // contrôle des cumuls de strikes, spares et splits par partie (maxi = 10, 11 ou 12, selon le cas)
                $p = 0; // N° de la partie
                foreach ($sr->getScores() as $sc) {
                    $p++;       // n° de la partie
                    $scr = $sc->getScore();
                    $st = $sc->getStrike();
                    $sp = $sc->getSpare();
                    $spl = $sc->getSplit();

                    // init N° série et partie pour message d'erreur
                    $m = 'Série ' . $sr->getNoSerie() . ', partie ' . $p . ', le ';

                    if ($scr < 1 or $scr > 300) {
                        $context->addViolationAt(
                                'score', $m . 'score saisi (' . $scr . ') doit être compris entre 1 et 300');
                    }
                    if ($st < 0 or $st > 12) {
                        $context->addViolationAt(
                                'strikes', $m . 'nombre de strikes (' . $st . ') doit être compris entre 0 et 12');
                    }
                    if ($sp < 0 or $sp > 10) {
                        $context->addViolationAt(
                                'spares', $m . 'nombre de spares (' . $sp . ') doit être compris entre 0 et 10');
                    }
                    if (($st + $sp) > 11) {
                        $context->addViolationAt(
                                'total', $m . 'total strikes + spares (' . ($st + $sp) . ') ne doit pas dépasser 11');
                    }
                    if (($st + $spl) > 10) {
                        $context->addViolationAt(
                                'splits', $m . 'total strikes + splits (' . ($st + $spl) . ') ne doit pas dépasser 10');
                    }
                    if ($spl < 0 or $spl > 10) {
                        $context->addViolationAt(
                                'splits', $m . 'nombre de splits (' . $spl . ') doit être compris entre 0 et 10');
                    }
                }
            }
            // y a-t-il un doublon dans le n° de la série
            if (count($noSerie) !== count(array_unique($noSerie))) {
                $context->addViolationAt(
                        'n° série', 'Il y a doublon dans un des n° de série');
            }
        }
        ));
    }

    function getNbScores() {
        $s = $i = 0;

        foreach ($this->getSeries() as $serie) {
            $s += $serie->getNbScores();
        }

        return $s;
    }

    function getTotalPoints() {
        $m = 0;

        foreach ($this->getSeries() as $serie) {
            foreach ($serie->getScores() as $score) {
                if (!$score->getPasCalculMoyenne()) {
                    $m += $score->getScore();
                }
            }
        }
        return $m;
    }

    function getNbSeries() {
        return $this->getSeries()->count();
    }

    function getMoyenne() {
        $m = $i = 0;

        foreach ($this->getSeries() as $serie) {
            foreach ($serie->getScores() as $score) {
                if (!$score->getPasCalculMoyenne()) {
                    $m += $score->getScore();
                    $i++;
                }
            }
        }
        return $i == 0 ? 0 : $m / $i;
    }

    function getId() {
        return $this->id;
    }

    function getEvenement() {
        return $this->evenement;
    }

    function getPeriode() {
        return $this->periode;
    }

    function getSeries() {
        return $this->series;
    }

    function getHandicap() {
        return $this->handicap;
    }

    function getDateJournee() {
        return $this->dateJournee;
    }

    function getBowling() {
        return $this->bowling;
    }

    function getTournoi() {
        return $this->tournoi;
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

    function setEvenement($evenement) {
        $this->evenement = $evenement;
    }

    function setPeriode($periode) {
        $this->periode = $periode;
    }

    function setSeries($series) {
        $this->series = $series;
    }

    function setHandicap($handicap) {
        $this->handicap = $handicap;
    }

    function setDateJournee($dateJournee) {
        $this->dateJournee = $dateJournee;
    }

    function setBowling($bowling) {
        $this->bowling = $bowling;
    }

    function setTournoi($tournoi) {
        $this->tournoi = $tournoi;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
