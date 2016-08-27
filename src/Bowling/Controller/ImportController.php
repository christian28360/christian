<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\MasterController;
use CHRIST\Modules\Bowling\ImportManager\Import;

/**
 * Import controller of application
 *
 * @author glr735
 */
class ImportController extends MasterController {

    /**
     * Result of import
     * @var array
     */
    private $report = array();

    /**
     * Insert data
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function insertAction(Request $request, Application $app) {
        $startTreatment = new \DateTime();
        // exécute l'import
        $this->load();
        $parameters = array(
            'mode' => 'insertion',
            'report' => $this->report,
            'duration' => $startTreatment->diff(new \DateTime())->format('%H:%I:%S'),
            'environment' => $this->app['environment'],
        );
        // envoi du mail, déactivé
        // $this->sendReport($parameters);
        // 
        // retour avec résultat de l'import
        return $this->app['twig']->render(
                        'src/Bowling/Resources/views/import/index.html.twig', $parameters
        );
    }

    private function load($delete = true) {

        $manifest = $this->app['dtc.config.manager']->getSettings('config', 'bowling_import_manifests_journee');
        $file = $this->app['dtc.config.manager']->getSettings('config', 'bowling_import_manifests_journee_file');
        $path = $this->app['dtc.config.manager']->getSettings('config', 'bowling_import_configuration_dataPath');
        $saison = $this->app['dtc.config.manager']->getSettings('config', 'bowling_import_manifests_journee_saison');

        $em = $this->app['orm.ems']['christian'];

        $periode = new \CHRIST\Modules\Bowling\Entity\Periode();

        $periode->setDtDeb(new \DateTime('09/01/' . _left($saison, 4)));
        $periode->setDtFin(new \DateTime('08/31/' . _right($saison, 4)));

        // si saison non existante pour cette période => on la créée
        if (!$em->getRepository('CHRIST\Modules\Bowling\Entity\Periode')->findByPeriode($periode)) {
            $em->persist($periode);
            $em->flush();
        }

        // Si données existantes pour cette période => suppression d'office
        $partiesSaison = $em->getRepository('CHRIST\Modules\Bowling\Entity\Journee')->countPartiesSaison($periode);
        if ($partiesSaison > 0) {
            // add here : delete all by jounee
        }
        try {
            // on essaye de faire l'mport
            $manifest['saison'] = $periode;
            $manifest['dataPath'] = $path;
            $import = new Import(
                    $this->app, 
                    $manifest, 
                    array(
                'typeEntry' => 'journee',
                'dataPath' => $path,
                'saison' => $periode,
                    )
            );

            $this->report[$file] = $import->load();
        } catch (\Exception $ex) {

            $this->report[$file] = $ex->getMessage();
        }
    }

    private function sendReport($parameters = array()) {
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'bowling_mail_configuration');
        $message = \Swift_Message::newInstance();

        $message->setSubject('Module de gestion du bowling ' . strtoupper($this->app['environment']) . ' - Rapport d\'import des données')
                ->setFrom($mailConfig['from'])
                ->setTo($mailConfig['to'])
                ->setBody($this->app['twig']->render('src/Bowling/Resources/views/import/mail-report.html.twig', 
                        $parameters
                        ), 'text/html');

        $this->app['mailer']->send($message);
    }

}
