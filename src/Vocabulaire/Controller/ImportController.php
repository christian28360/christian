<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\MasterController;
use CHRIST\Modules\Vocabulaire\ImportManager\Import;

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
                        'src/Vocabulaire/Resources/views/import/index.html.twig', $parameters
        );
    }

    private function load($delete = true) {

        $manifest = $this->app['dtc.config.manager']->getSettings('config', 'vocabulaire_import_manifests_mot');
        $file = $this->app['dtc.config.manager']->getSettings('config', 'vocabulaire_import_manifests_mot_file');
        $path = $this->app['dtc.config.manager']->getSettings('config', 'vocabulaire_import_configuration_dataPath');

        // Si données existantes => suppression d'office
        $motEntity = $this->app['orm.ems']['christian']->getRepository('CHRIST\Modules\Vocabulaire\Entity\Mot');
        $nbMotsActuels = $motEntity->countAll();

        if ($nbMotsActuels > 0) {
//            $motEntity->deleteAll();
        }
        try {
            // on essaye de faire l'mport
            $manifest['dataPath'] = $path;
            $import = new Import(
                    $this->app, $manifest, array(
                'typeEntry' => 'mot',
                'dataPath' => $path,
                    )
            );
            $this->report[$file] = $import->load();
        } catch (\Exception $ex) {

            $this->report[$file] = $ex->getMessage();
        }
    }

    private function sendReport($parameters = array()) {
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'vocabulaire_mail_configuration');
        $message = \Swift_Message::newInstance();

        $message->setSubject('Module de gestion du vocabulaire ' . strtoupper($this->app['environment']) . ' - Rapport d\'import des données')
                ->setFrom($mailConfig['from'])
                ->setTo($mailConfig['to'])
                ->setBody($this->app['twig']->render('src/Vocabulaire/Resources/views/import/mail-report.html.twig', $parameters
                        ), 'text/html');

        $this->app['mailer']->send($message);
    }

}
