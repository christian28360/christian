<?php

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\PeriodeRepository")
 * @ORM\Table(name="bowl_periode")
 * @author Christian Alcon
 */
class Periode {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_periode_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="bowl_periode_dt_deb", type="datetime")
     */
    private $dtDeb;

    /**
     * @ORM\Column(name="bowl_periode_dt_fin", type="datetime")
     */
    private $dtFin;

    /**
     * @ORM\OneToMany(targetEntity="Journee", mappedBy="periode")
     * @ORM\OrderBy({"dateJournee" = "DESC"})
     * @var arrayCollection
     */
    private $journees;

    function getJournees() {
        return $this->journees;
    }

    function getNbParties() {
        $s = 0;
        foreach ($this->getJournees() as $jnee) {
            foreach ($jnee->getSeries() as $serie) {
                $s += $serie->getNbScores();
            }
        }

        return $s;
    }

    /**
     * @ORM\Column(name="bowl_periode_is_active", type="integer")
     */
    private $isActive;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_periode_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_periode_updated_on", type="datetime")
     */
    private $modifieLe;
    private $app, $em, $type;

    public function __construct(\DateTime $getDtDeb = null, \DateTime $getDtFin = null) {
        $this->getDtDeb = $getDtDeb;
        $this->getDtFin = $getDtFin;
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addConstraint(new Assert\Callback(function ($object, \Symfony\Component\Validator\ExecutionContextInterface $context) {

            // pour test de période à cheval sur existante
            $app = \CHRIST\Common\Kernel\SingleApp::getAppliation();
            $doublon = $app['orm.ems']['christian']->getRepository(__CLASS__)
                    ->findDoublons($object->getDtDeb(), $object->getDtFin());

            if ($object->getDtDeb() >= $object->getDtFin()) {
                $context->addViolationAt(
                        'periodeFin', 'La date de fin ne doit pas être < ou = à celle de début'
                );
            } elseif (intval($object->getDtDeb()->diff($object->getDtFin())->format('%R%y')) > 0) {
                $context->addViolationAt(
                        'periodeFin', 'Pas plus d\'un an entre deux dates'
                );
            } elseif (!empty($doublon)) {
                $context->addViolationAt(
                        'periodeFin', 'La saison est à cheval sur une plage de dates existantes'
                );
            }
        }));
    }

    function getId() {
        return $this->id;
    }

    function getDtDeb() {
        return $this->dtDeb;
    }

    function getDtFin() {
        return $this->dtFin;
    }

    public function getCourbeTendance() {

        $moyenne = array();
        $date = array();

        foreach ($this->getJournees() as $jnee) {
            foreach ($jnee->getSeries() as $serie) {
                $moyenne[] = $serie->getMoyenne();
                $date[] = $serie->getDateSerie()->format('d/m/Y') . '-' . $serie->getNoSerie();
            }
        }
        return array_combine($date, $moyenne);
    }

    public function getVentilationParTranche() {

        $result = array();
        foreach ($this->getJournees() as $jnee) {
            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {
                    if (!$score->getPasCalculMoyenne()) {
                        $tranche = (int) ($score->getScore() / 10) * 10;
                        array_key_exists($tranche, $result) ?
                                        $result[$tranche] ++ :
                                        $result[$tranche] = 1;
                    }
                }
            }
        }
        ksort($result);

        return $result;
    }

    public function getRecapParEvenement() {

        // Init tableau ventilation en fonction des types de jeu présents en BdD
        foreach ($this->getJournees() as $jnee) {
            $nbParties[$jnee->getEvenement()->getTypeJeu()->getTypeJeu()] = 0;
            $cumScore[$jnee->getEvenement()->getTypeJeu()->getTypeJeu()] = 0;
            $cumStrike[$jnee->getEvenement()->getTypeJeu()->getTypeJeu()] = 0;
            $cumSpare[$jnee->getEvenement()->getTypeJeu()->getTypeJeu()] = 0;
            $cumStrikeAndSpare[$jnee->getEvenement()->getTypeJeu()->getTypeJeu()] = 0;
        }
        // pour les totaux
        $nbParties["G"] = 0;
        $cumScore["G"] = 0;
        $cumStrike["G"] = 0;
        $cumSpare["G"] = 0;
        $cumStrikeAndSpare["G"] = 0;

        foreach ($this->getJournees() as $jnee) {
            $typeJeu = $jnee->getEvenement()->getTypeJeu()->getTypeJeu();
            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {

                    if (!$score->getPasCalculMoyenne()) {

                        // Cumuls :
                        $nbParties["G"] ++;
                        $nbParties[$typeJeu] ++;

                        $cumScore["G"] += $score->getScore();
                        $cumScore[$typeJeu] += $score->getScore();

                        $cumStrike["G"] += $score->getStrike();
                        $cumStrike[$typeJeu] += $score->getStrike();

                        $cumSpare["G"] += $score->getSpare();
                        $cumSpare[$typeJeu] += $score->getSpare();

                        $cumStrikeAndSpare["G"] += $score->getStrike() + $score->getSpare();
                        $cumStrikeAndSpare[$typeJeu] += $score->getStrike() + $score->getSpare();
                    }
                }
            }
        }

        return array_merge_recursive($nbParties, $cumScore, $cumStrike, $cumSpare, $cumStrikeAndSpare);
    }

    public function getMoyenneMensuelle() {

        $mois = array();

        foreach ($this->getJournees() as $jnee) {
            $m = $jnee->getDateJournee()->format('Y/m');
            $nbParties = 0;
            $cumScore = 0;
            $cumStrike = 0;
            $cumSpare = 0;
            $cumStrikeAndSpare = 0;
            $moyenneScore = 0;
            $moynneStrike = 0;
            $moynneSpare = 0;
            $moynneStrikeAndSpare = 0;

            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {

                    if (!$score->getPasCalculMoyenne()) {
                        // Cumuls :
                        $nbParties++;
                        $cumScore += $score->getScore();
                        $cumStrike += $score->getStrike();
                        $cumSpare += $score->getSpare();
                        $cumStrikeAndSpare += $score->getStrike() + $score->getSpare();
                        // Moyennes :
                        $moyenneScore = $cumScore / $nbParties;
                        $moynneStrike = $cumStrike / $nbParties;
                        $moynneSpare = $cumSpare / $nbParties;
                        $moynneStrikeAndSpare = ( $cumStrike + $cumSpare ) / $nbParties;
                    }
                }
            }
            // Alim. tableau : cumuls
            $mois[$m]["mois"] = $m;
            array_key_exists("score", $mois[$m]) ? $mois[$m]["score"] += $cumScore : $mois[$m]["score"] = $cumScore;
            array_key_exists("strike", $mois[$m]) ? $mois[$m]["strike"] += $cumStrike : $mois[$m]["strike"] = $cumStrike;
            array_key_exists("spare", $mois[$m]) ? $mois[$m]["spare"] += $cumSpare : $mois[$m]["spare"] = $cumSpare;
            array_key_exists("strikeAndSpare", $mois[$m]) ? $mois[$m]["strikeAndSpare"] += $cumStrikeAndSpare : $mois[$m]["strikeAndSpare"] = $cumStrikeAndSpare;
            array_key_exists("nbJournees", $mois[$m]) ? $mois[$m]["nbJournees"] += 1 : $mois[$m]["nbJournees"] = 1;
            array_key_exists("nbParties", $mois[$m]) ? $mois[$m]["nbParties"] += $nbParties : $mois[$m]["nbParties"] = $nbParties;

            // Alim. tableau : moyennes
            $mois[$m]["moyenneScore"] = $moyenneScore;
            $mois[$m]["moyenneStrike"] = $moynneStrike;
            $mois[$m]["moyenneSpare"] = $moynneSpare;
            $mois[$m]["moyenneStrikeAndSpare"] = $moynneStrikeAndSpare;
            $mois[$m]["txStrikeAndSpare"] = (
                    $nbParties != 0 ?
                            $cumStrikeAndSpare / $nbParties / 11 * 100 :
                            0);
        }

        return array($mois, $this->getMoyenneAnnuelle());
    }

    public function getMoyenneAnnuelle() {

        $nbJournees = 0;
        $nb = 0;
        $cumScore = 0;
        $cumStrike = 0;
        $cumSpare = 0;

        foreach ($this->getJournees() as $jnee) {
            $nbJournees++;
            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {
                    if (!$score->getPasCalculMoyenne()) {
                        $nb++;
                        $cumScore += $score->getScore();
                        $cumStrike += $score->getStrike();
                        $cumSpare += $score->getSpare();
                    }
                }
            }
        }

        return array(
            "nbJournees" => $nbJournees,
            "nbParties" => $nb,
            "score" => $cumScore,
            "strike" => $cumStrike,
            "spare" => $cumSpare,
            "strikeAndSpare" => $cumSpare + $cumStrike,
            "moyenneScore" => $nb == 0 ? 0 : $cumScore / $nb,
            "moyenneStrike" => $nb == 0 ? 0 : $cumStrike / $nb,
            "moyenneSpare" => $nb == 0 ? 0 : $cumSpare / $nb,
            "moyenneStrikeAndSpare" => $nb == 0 ? 0 : ( $cumSpare + $cumStrike ) / $nb,
            "txStrikeAndSpare" => ( $nb == 0 ) ? 0 : ( $cumStrike + $cumSpare ) / $nb / 11 * 100,
        );
    }

    public function getLesExtemesMoyenne($p) {

        return $this->getLesExtemes()[$p]["Moyenne"]["score"];
    }

    public function getLesExtemes() {

        $mini = array();
        $maxi = array();

        // les moins bons
        foreach ($this->getJournees() as $jnee) {
            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {
                    if (!$score->getPasCalculMoyenne()) {
                        $mini[] = array(
                            "date" => $jnee->getDateJournee()->format('d/m/Y'),
                            "score" => $score->getScore(),
                            "strike" => $score->getStrike(),
                            "spare" => $score->getSpare(),
                        );
                    }
                }
            }
        }

        // les meilleurs
        foreach ($this->getJournees() as $jnee) {
            foreach ($jnee->getSeries() as $serie) {
                foreach ($serie->getScores() as $score) {
                    if (!$score->getPasCalculMoyenne()) {
                        $maxi[] = array(
                            "date" => $jnee->getDateJournee()->format('d/m/Y'),
                            "score" => $score->getScore(),
                            "strike" => $score->getStrike(),
                            "spare" => $score->getSpare(),
                        );
                    }
                }
            }
        }

        // tri des résultats :
        usort($mini, function ($a, $b) {
            if (isset($a['score']) && isset($b['score'])) {
                if ($a['score'] === $b['score']) {
                    return 0;
                }
                return $a['score'] < $b['score'] ? -1 : 1;
            }
        });

        usort($maxi, function ($a, $b) {
            if (isset($a['score']) && isset($b['score'])) {
                if ($a['score'] === $b['score']) {
                    return 0;
                }
                return $a['score'] > $b['score'] ? -1 : 1;
            }
        });

        // On ne garde que 10 résultats maximum :
        $mini = array_slice($mini, 0, 10);
        $maxi = array_slice($maxi, 0, 10);

        // calcul des moyennes :
        $mini['Moyenne'] = array("score" => 0, "strike" => 0, "spare" => 0);
        $maxi['Moyenne'] = array("score" => 0, "strike" => 0, "spare" => 0);
        // mini :
        for ($i = 0; $i < count($mini) - 1; $i++) {
            $mini['Moyenne']['score'] += $mini[$i]["score"];
            $mini['Moyenne']['strike'] += $mini[$i]["strike"];
            $mini['Moyenne']['spare'] += $mini[$i]["spare"];
        }
        $i > 0 ? $mini['Moyenne']['score'] = $mini['Moyenne']['score'] / $i : 0;
        $i > 0 ? $mini['Moyenne']['strike'] = $mini['Moyenne']['strike'] / $i : 0;
        $i > 0 ? $mini['Moyenne']['spare'] = $mini['Moyenne']['spare'] / $i : 0;
        // maxi :
        for ($i = 0; $i < count($maxi) - 1; $i++) {
            $maxi['Moyenne']['score'] += $maxi[$i]["score"];
            $maxi['Moyenne']['strike'] += $maxi[$i]["strike"];
            $maxi['Moyenne']['spare'] += $maxi[$i]["spare"];
        }
        $i > 0 ? $maxi['Moyenne']['score'] = $maxi['Moyenne']['score'] / $i : 0;
        $i > 0 ? $maxi['Moyenne']['strike'] = $maxi['Moyenne']['strike'] / $i : 0;
        $i > 0 ? $maxi['Moyenne']['spare'] = $maxi['Moyenne']['spare'] / $i : 0;

        return array("min" => $mini, "max" => $maxi);
    }

    function getSaison() {
        return $this->dtDeb->format('Y') . '/' . $this->dtFin->format('Y');
    }

    function getIsActive() {
        return $this->isActive;
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

    function setDtDeb($dtDeb) {
        $this->dtDeb = $dtDeb;
    }

    function setDtFin($dtFin) {
        $this->dtFin = $dtFin;
    }

    function setJournees() {
        $this->journees = $journees;
    }

    function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
