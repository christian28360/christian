<?php

/**
 * Description of BowlingController
 *
 * @author Christian Alcon
 */

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Tournoi;
use CHRIST\Modules\Bowling\Form\TournoiForm;

class TournoiController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/tournoi/liste.html.twig', array(
                    'repository' => 'tournoi',
                    'tri' => 'dateTournoi',
        ));
    }

    protected function planificationAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/tournoi/aPlanifier.html.twig', array(
                    'repository' => 'tournoi',
                    'tri' => 'allTournoi',
        ));
    }

    /** Saisie tournoi avec retour vers liste tournois */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->ajoutTournoi(NULL);
    }

    /** Saisie tournoi avec retour vers tournoi à planifier */
    protected function ajoutPlanificationAction(Request $request, Application $app) {

        return $this->ajoutTournoi('planification');
    }

    protected function ajoutTournoi($retour) {
        return $this->getForm(new Tournoi(), 'src/Bowling/Resources/views/tournoi/ajout.html.twig', $retour);
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/tournoi/ajout.html.twig');
    }

    protected function updatePlanificationAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/tournoi/ajout.html.twig', 'planification');
    }

    protected function duplicateAction(Request $request, Application $app) {

        $record = $this->getCurrent();
        $duplicate = new Tournoi();

        $duplicate->setJournee($record->getJournee());
        $duplicate->setDateTournoi($record->getDateTournoi());
        $duplicate->setDateDebutTournoi($record->getDateDebutTournoi());
        $duplicate->setCommentaire($record->getCommentaire());
        $duplicate->setDateLimiteInscription($record->getDateLimiteInscription());
        $duplicate->setPrix($record->getPrix());
        $duplicate->setOrganisateur($record->getOrganisateur());
        $duplicate->setBowling($record->getBowling());
        $duplicate->setType($record->getType());
        $duplicate->setNbJoueursOuEquipes($record->getNbJoueursOuEquipes());
        $duplicate->setFinale($record->getFinale());

        return $this->getForm($duplicate, 'src/Bowling/Resources/views/tournoi/ajout.html.twig', 'planification');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // y a-t-il une journée affectée à ce tournoi ?
        if (is_null($record->getJournee())) {
            // non => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le tournoi a bien été supprimé');
        } else {
            // oui : on affiche la date en flasbag
            $message = 'Supression impossible, il existe une journée affectée à ce tournoi le ' .
                    $record->getJournee()->getDateJournee()->format('d/m/Y');
            $this->app['session']->getFlashBag()->add('danger', $message);
        }

        return $this->reload();
    }

    private function getForm($entity, $view, $retour = null) {

        $form = $this->app["form.factory"]->create(
                new TournoiForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);
        if ($form->isValid()) {

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-tournoi' .
                                    (!is_null($retour) ? '-' . $retour : ''))
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                    'retour' => $retour,
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Tournoi', $this->request->get('id'));
    }

}
