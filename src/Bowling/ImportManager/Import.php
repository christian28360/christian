<?php

namespace CHRIST\Modules\Bowling\ImportManager;

use CHRIST\Common\Kernel\Modules\Import\AbstractImport;
use CHRIST\Common\Kernel\Helpers\PhpHelpers;
use CHRIST\Modules\Bowling\Entity\Journee;
use CHRIST\Modules\Bowling\Entity\Serie;
use CHRIST\Modules\Bowling\Entity\Score;
use CHRIST\Modules\Bowling\Entity\Bowling;
use CHRIST\Modules\Bowling\Entity\Evenement;

/**
 * Description of Import
 *
 * @author glr735/ezs824
 */
class Import extends AbstractImport
{

    private $numRowDeleted = 0;
    protected $activeLogger = true;
    protected $nameEntityManager = 'christian';
    private $nbJournee = 0;

    /**
     * @inherit
     */
    public function load()
    {
// Load new Logger
//        $this->loadNewLogger($this->parameters->getSettings('typeEntry'));
        $this->loadNewLogger('journee');
        // add parameters
        try {
// Only import from file
            $dataPath = $this->parameters->getSettings('dataPath');
            $file = $this->parameters->getSettings('file');
            $this->logger->setPhysicalName($file);
            $this->parameters->setSettings('file', $dataPath . $file);
        } catch (\Exception $ex) {
            ;
        }

        try {
            // select wrapper
            $wrappers = $this->parameters->getSettings('wrappers');
            $wrapper = array_shift($wrappers);
            $this->parameters->setSettings('wrappers', $wrappers);
            $wrapper = new $wrapper($this->parameters);

            $this->importMode = 'delete';
            $modeUpdate = 'modeUpdate';
            $entityManager = $this->parameters->getSettings('entityManager');
            $manager = new $entityManager($this->parameters);

            // Import data from file
            $rows = $wrapper->import();

            // Traitement des lignes d'entrée
            // ------------------------------
            //      Nvelle journée = changement de date et de Type et de bowling
            //      Date     = col_1
            //      Hd       = Col_14
            //      NbGagne  = Col_15
            //      Bowling  = Col_16 
            //          remarques : 
            //              * 'Bois paris'      = 'chartres'
            //              * 'nogent rotrou'   = 'nogent le rotrou'
            //      Type     = Col_17
            //      scores   = cols 2, 5, 8, 11 (4 maxi par ligne)
            //      strikes  = cols scores +1
            //      spares   = cols scores +2
            //
                        //  NOTA : FAIRE -1 pour l'index, car commence à 0 et col à 1
            //
            $nbRows = $nbPartie = $type = $bowling = 0;
            $handicap = $dateJnee = 0;
            $entrainement = false;

            // get first ligne
            $this->getRow($rows[0], $dateJnee, $type, $bowling, $handicap, $entrainement);
            $dateOld = $dateJnee;
            $typeOld = $type;
            $bowlingOld = $bowling;
            $handicapOld = $handicap;
            
            // init 1ère série et score
            $scores = new \Doctrine\Common\Collections\ArrayCollection();
            $score = new Score();

            foreach ($rows as $row) {
                $this->getRow($row, $dateJnee, $type, $bowling, $handicap, $entrainement);

                if (!($dateJnee == $dateOld && $type == $typeOld && $bowling == $bowlingOld)) {
                    // journée différente, on enregistre la précédante
                    $this->writeJournee($dateOld, $typeOld, $handicapOld, $bowlingOld, $scores);
                    $dateOld = $dateJnee;
                    $typeOld = $type;
                    $bowlingOld = $bowling;
                    $handicapOld = $handicap;

                    // init nouvelle série et scores
                    $scores = new \Doctrine\Common\Collections\ArrayCollection();
                    $score = new Score();
                }
                $nbRows++;
                // traitement des scores
                $nbGagne = $row[14];        // ventilation de gagné sur les scores de nbgagne à nbgagne -1
                // parcours des 4 zones de score
                for ($scr = 1; $scr < 12; $scr += 3) {
                    if ($row[$scr] > 0) {
                        $score->setScore($row[$scr]);
                        $score->setStrike($row[$scr + 1]);
                        $score->setSpare($row[$scr + 2]);
                        if ($nbGagne > 0) {
                            $score->setGagnee(true);
                            $nbGagne--;
                        }
                        $score->setSurCarnetEntrainement($entrainement);
                        $scores->add($score);
                        $score = new Score($score);
                        //_dump('scores ligne ' . $nbRows, $scores, true);
                    }
                }
            }
            // enregistrement dernière journée
            $this->writeJournee($dateJnee, $type, $handicap, $bowling, $scores);

            exit();

            $this->finalizeLog('Terminé'); //, $manager->count());

            return $rows;
        } catch (\Exception $ex) {

            $this->finalizeLog($ex->getMessage());

            throw $ex;
        }
    }

    private function getRow($row, &$dateJnee, &$type, &$bowling, &$handicap, &$entrainement)
    {

        $dateJnee = PhpHelpers::dateExcel($row[0]);
        $dateJnee->setTime(0, 0, 0);
        $type = $row[16];
        $bowling = $row[15];
        $handicap = $row[13];
        $entrainement = (strtoupper($type) == 'E' && strtolower($bowling) == 'barjouville') ? true : false;
    }

    private function writeJournee($dte, $typ, $hd, $bwl, $scores)
    {

        $this->nbJournee++;

        // The evènement (e, l, t)
        // recherche évènement correspondant
        switch (strtoupper(_left($typ, 1))) {
            case 'E':
                $bl = $bwl;
                switch (strtolower($bl)) {
                    case 'bois paris':
                        $typ = 'EC';
                        break;
                    case 'barjouville':
                        $typ = 'EB';
                        break;
                    default:
                        $typ = 'E';
                        break;
                }
                break;
            case 'L':
                switch ($dte->format('N')) {
                    case 2: // mardi
                        $typ = 'LM19';
                        break;
                    case 4: // jeudi
                        $typ = 'L';
                        break;
                    default: // autre cas à gérer manuellemnt
                        $typ = 'A';
                        break;
                }
                break;
            case 'T':
                $typ = 'T';
                break;
            default:
                // cas non prévu, à affecter manuellement
                $typ = 'A';
                break;
        }

        $em = $this->app['orm.ems']['christian'];

        // The bowling Entity
        $b = new Bowling($em->getRepository('CHRIST\Modules\Bowling\Entity\Bowling')->findParNomOuAlias($bwl));

        // The Evenement Entity
        $e = new Evenement($em->getRepository('CHRIST\Modules\Bowling\Entity\Evenement')->findByEvt($typ));

        $journee = new Journee($this->parameters->getSettings('saison'));
    _dump('jnee (nouvelle) ' , $journee->getSeries()['_elhements']);
        exit();
        $journee->setDateJournee($dte);
        $journee->setEvenement($e);
        $journee->setHandicap($hd);
        $journee->setBowling($b);

        foreach ($journee->getSeries() as $serie) {
            // add scores
            $serie = new \Doctrine\Common\Collections\ArrayCollection();

            $serie->setNoSerie(1);
            $serie->setDateSerie($dte);
            $journee->setSeries($serie);
            
            // ok jusqu'à ci-dessus
            _dump('series updt', $journee->getSeries());

//            $s = array();
//            foreach ($scores as $score) {
//                $s[] = new Score($score);
//            }
            /*
              _dump('type var series', gettype($series));
              _dump('series', $series, true);
              _dump('type var serie', gettype($serie));
              _dump('serie', $serie, true);
             */
        }
        // add scores
//        _dump('jnee ($series)', new Serie($series));
//        $series->remove(0); // suppress. 1er score créer dynamiquement à l'appel de l'entity
//        $series->setScores(new Scores($s));
//        _dump('jnee (series->scores)', $series->getScores());
//        $journee->setSeries(new Serie($series));

// débug recordSet avant flush()
        _dump('la journee a enregistrer :', 'Affichage des divers morceaux');
        _dump('jnee (globalite) ' . $this->nbJournee . ' a enregistrer', $journee);
        _dump('jnee (bowling)', $journee->getBowling());
        _dump('jnee (evt)', $journee->getEvenement(), true);
        _dump('jnee (series)', $journee->getSeries(), true);
        _dump('jnee (series->scores)', $journee->getSeries()->getScores());

        foreach ($journee->getSeries() as $s) {
            _dump('jnee (' . count($s->getScores()) . ' scores)', $s->getScores(), true);
        }
// fin débug
        // write journée to Data Base
        try {
//        \CHRIST\Modules\Bowling\Controller\GlobalController::writeJourneeToDB($journee);
            // la journée
            $this->app['orm.ems']['christian']->persist($journee);

            // les séries
            foreach ($journee->getSeries() as $sr) {
                $sr->setJournee($journee);
                $this->app['orm.ems']['christian']->persist($sr);

                // les scores
                foreach ($sr->getScores() as $sc) {
                    $sc->setSerie($sr);
                    $this->app['orm.ems']['christian']->persist($sc);
                }
            }
            $this->app['orm.ems']['christian']->flush();
            // exit();
            //_dump('jnee ' . $this->nbJournee . ' a enregistrer', $journee, true);
        } catch (\Exception $ex) {
            //    $this->report['scores_bowling_test.s'] = $ex->getFile();   
//            $this->report['scores_bowling_test.s'] = $ex->getMessage();   
            throw $ex;
        }
    }

    private function finalizeLog($report, $numImported = 0)
    {
        _dump('finalizeLog', $report, true);
        $this->logger->setDateEnd(new \DateTime());
        $this->logger->setDateImport(new \DateTime());
        $this->logger->setNumberDataDeleted($this->numRowDeleted);
        $this->logger->setNumberDataImported($numImported);
        $this->logger->setReport($report);

        $this->saveLogger();
    }

}
