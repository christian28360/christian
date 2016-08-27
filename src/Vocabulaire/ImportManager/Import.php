<?php

namespace CHRIST\Modules\Vocabulaire\ImportManager;

use CHRIST\Common\Kernel\Modules\Import\AbstractImport;
use CHRIST\Common\Kernel\Helpers\PhpHelpers;
use CHRIST\Modules\Vocabulaire\Entity\Mot;
use CHRIST\Modules\Vocabulaire\Entity\Dictionnaire;
use CHRIST\Modules\Livre\Entity\Livre;

/**
 * Description of Import
 *
 * @author glr735/ezs824
 */
class Import extends AbstractImport {

    private $numRowDeleted = 0;
    protected $activeLogger = true;
    protected $nameEntityManager = 'christian';
    private $nbMots = 0;

    /**
     * @inherit
     */
    public function load() {
// Load new Logger
//        $this->loadNewLogger($this->parameters->getSettings('typeEntry'));
        $this->loadNewLogger('mot');
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
            //      DateCréation    = Col_1
            //      Livre           = col_2
            //      Mot             = col_3
            //      Page            = col_4
            //      a_chercher      = col_5 (traduire "FAUX" ou "VRAI" en booléen)
            //      Explication     = col_6
            //      TrouvéDans      = col_7
            //      PasTrouvéDans   = col_8
            //      a_apprendre     = col_9 (traduire "FAUX" ou "VRAI" en booléen)
            //
            //  NOTA : FAIRE -1 pour l'index, car commence à 0 et col à 1
            //
            $nbRows = $cptr = 0;

            foreach ($rows as $row) {

                $nbRows++;
                $this->nbMots++;

                // enregistrement la ligne
                $mot = new Mot();
                $mot->setCreeLe(PhpHelpers::dateExcel($row[0]));

                $em = $this->app['orm.ems']['christian'];
                // The Livre Entity
                $livre = (!empty($this->findLivre($em, $row[1])) ) ?
                        $this->findLivre($em, $row[1]) :
                        // $this->findLivre($em, '_absent'); anciennement
                        // livre pas trouvé, on mémorise dans le champ origine
                        $mot->setOrigine($row[1]);
                $mot->setLivre($livre[0]);

                $mot->setMot($row[2]);
                $mot->setPage($row[3]);
                $mot->setSignification(is_null($row[5]) ? 'pas de définition fournie' : $row[5] );
                // The Dico Entity
                $dico = $em->getRepository('CHRIST\Modules\Vocabulaire\Entity\Dictionnaire');
                $mot->setTrouveDansDicos($dico->findByNom($row[6]));
                $mot->setPasTrouveDansDicos($dico->findByNom($row[7]));

                $mot->setAApprendre(($row[4] == 'VRAI') ? true : false );

                try {
                    _dump('mot a enregistrer', $mot->getMot() . ' - ' . $mot->getAApprendre());
                    $em->persist($mot);
                    if ($cptr++ == 100) {
                        $cptr = 0;
                        $em->flush();
                    }
                } catch (\Exception $ex) {
                    _dump('on est dans catch ($ex)', 'donc erreur sur le flush');
                    throw $ex;
                }
            }
            // enregistre le dernier lot
            $em->flush();

            // exit();

            $this->finalizeLog('Terminé'); //, $manager->count());

            return $rows;
        } catch (\Exception $ex) {

            $this->finalizeLog($ex->getMessage());

            throw $ex;
        }
    }

    private function finalizeLog($report, $numImported = 0) {
        _dump('finalizeLog', $report, true);
        $this->logger->setDateEnd(new \DateTime());
        $this->logger->setDateImport(new \DateTime());
        $this->logger->setNumberDataDeleted($this->numRowDeleted);
        $this->logger->setNumberDataImported($numImported);
        $this->logger->setReport($report);

        $this->saveLogger();
    }

    private function findLivre($em, $livre) {

        return $em->getRepository('CHRIST\Modules\Livre\Entity\Livre')->findParTitre($livre);
    }

}
