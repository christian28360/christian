<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Evenement;
use CHRIST\Modules\Bowling\Form\EvenementForm;

/**
 * Description of EvenementController
 *
 * @author Christian ALCON
 */
class EvenementController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/evenement/liste.html.twig', array(
                    'repository' => 'evenement',
                    'tri' => 'code',
        ));
    }

    /**
     * Saisie évènements
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Evenement(), 'src/Bowling/Resources/views/evenement/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/evenement/ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // y a-t-il des journées affectées à cette saison ?
        if ($record->getJournees()->count() == 0) {
            // aucune => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'L\'évènement "' . $record->getCode() . '" a bien été supprimé');
        } else {
            // oui : liste des 10 premières en flasbag
            $nbRecords = $record->getJournees()->count();
            $a = $nbRecords > 1 ? 'des' : 'une';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbRecords . ' journée' . $s . ' associée' . $s . ' à cette saison : ';

            $data = '';
            $i = 0;
            $limite = 5;        // Nbre maxi de journées à afficher dans le flashBag
            foreach ($record->getJournees() as $r) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $r->getDateJournee()->format('d/m/Y') . '"';
            }
            $data .= $nbRecords > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    private function getForm($entity, $view) {

        $form = $this->app["form.factory"]->create(
                new EvenementForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->app['orm.ems']['christian']->persist($entity);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées'
            );

            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-evenement')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Evenement', $this->request->get('id'));
    }

}
