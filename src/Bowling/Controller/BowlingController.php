<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Bowling;
use CHRIST\Modules\Bowling\Form\BowlingForm;

/**
 * Description of BowlingController
 *
 * @author Christian Alcon
 */
class BowlingController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/bowling/liste.html.twig', array(
                    'repository' => 'bowling',
                    'tri' => 'nom',
        ));
    }

    /**
     * Saisie bowlings
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Bowling(), 'src/Bowling/Resources/views/bowling/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/bowling/ajout.html.twig', $record->getNom());
    }

    protected function deleteAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        // y a-t-il des journées associées à ce bowling ?
        if ($record->getJournees()->count() == 0) {
            // aucune => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le bowling "' . $record->getNom() . '" a bien été supprimé');
        } else {
            // oui : liste des x premières en flasbag (paramètre $limite, ci-après)
            $nbRecords = $record->getJournees()->count();
            $a = $nbRecords > 1 ? $nbRecords : 'une';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $a . ' journée' . $s . ' associée' . $s . ' à ce bowling : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi d'évènements à afficher dans le flashBag
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

    /**
     * Return form view
     */
    private function getForm($entity, $view) {

        $form = $this->app["form.factory"]->create(
                new BowlingForm(), $entity, array(
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
                            $this->app['url_generator']->generate('bowling-bowling')
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
        return $this->getCourant('Bowling', 'Bowling', $this->request->get('id'));
    }

}
