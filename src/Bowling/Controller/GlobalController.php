<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CHRIST\Modules\Bowling\Entity\Journee;

/**
 * Default controller of application
 *
 * @author ALCON Christian
 */
class GlobalController extends \CHRIST\Common\Kernel\MasterController {

    /**
     * @return \CHRIST\Common\Entity\Samba
     */
    protected function getCurrentUser() {

        return $this->app['dtc.user.ntlm']->getEntity();
    }

    /**
     * @return CHRIST\Modules\Bowling\Entity\Periode
     */
    protected function periodeCourante() {

        return $this->app['orm.ems']['christian']
                        ->getRepository('CHRIST\Modules\Bowling\Entity\Periode')
                        ->findActivePeriode();
    }

    protected function templateRender($view = '', $parameters = array()) {

        $records = null;
        if ($parameters['repository']) {
            $records = $this->app['orm.ems']['christian']
                    ->getRepository('CHRIST\Modules\Bowling\Entity\\' . $parameters['repository']);

            // critère de tri selon l'entité
            switch ($parameters['tri']) {
                case 'libelle':
                    $records = $records->findAllOrderedByLibelle();
                    break;
                case 'nom':
                    $records = $records->findAllOrderedByNom();
                    break;
                case 'nomPrenom':
                    $records = $records->findAllOrderedByNomPrenom();
                    break;
                case 'code':
                    $records = $records->findAllOrderedByCode();
                    break;
                case 'titre':
                    $records = $records->findAllOrderedByTitre();
                    break;
                case 'dtDeb':
                    $records = $records->findAllOrderedByDtDeb();
                    break;
                case 'dtPartie':
                    $records = $records->findAllOrderedByDtPartie($this->periodeCourante());
                    break;
                case 'dateJournee':
                    $records = $records->findAllOrderedBydateJournee($this->periodeCourante());
                    break;
                case 'entrainementsSeptDix':
                    $records = $records->findAllEntrainementsOrderedBydateJournee($this->periodeCourante(), $setpDix = TRUE);
                    break;
                case 'entrainements':
                    $records = $records->findAllEntrainementsOrderedByDateJournee($this->periodeCourante(), $setpDix = FALSE);
                    break;
                case 'dateTournoi':
                    $records = $records->findAllOrderedBydateTournoi($this->periodeCourante());
                    break;
                case 'dateAchat':
                    $records = $records->findAllOrderedBydateAchat();
                    break;
                case 'allTournoi':
                    $records = $records->findAllToPlanif();
                    break;
                case null:
                    break;
                default:
                    // cas non prévu
                    print_r('cas non géré : ' . $parameters['tri']);
                    $records = NULL;
            }
        }
        $base = array(
            'records' => $records,
            'nbRecords' => count($records),
            'periode' => $this->periodeCourante(),
            'nbParties' => isset($parameters['nbParties']) ? $parameters['nbParties'] : 0,
        );

        return $this->app['twig']->render(
                        $view, array_merge($base, $parameters)
        );
    }

    public static function writeJourneeToDB(Application $app, Journee $jnee) {

        $app['orm.ems']['christian']->persist($jnee);

        foreach ($jnee->getSeries() as $sr) {
            $sr->setJournee($jnee);
            $this->app['orm.ems']['christian']->persist($sr);

            foreach ($sr->getScores() as $sc) {
                $sc->setSerie($sr);
                $this->app['orm.ems']['christian']->persist($sc);
            }
        }
        $this->app['orm.ems']['christian']->flush();
    }

    protected function writeData($entity) {

        $this->app['orm.ems']['christian']->persist($entity);
        $this->app['orm.ems']['christian']->flush();

        $this->app['session']->getFlashBag()->add(
                'succès', 'Les informations ont bien été enregistrées'
        );
    }

    /*
     * recharge la page courante, après l'action (créate, delete, ...)
     */

    protected function reload() {
        return new RedirectResponse($this->request->headers->get('referer'));
    }

}
