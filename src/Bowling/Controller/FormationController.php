<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Formation;
use CHRIST\Modules\Bowling\Form\FormationForm;

/**
 * Saisie controller of application Bowling
 *
 * @author Christian ALCON
 */
class FormationController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {
        return $this->templateRender('src/Bowling/Resources/views/formation/liste.html.twig', array(
                    'repository' => 'formation',
                    'tri' => 'code',
        ));
    }

    protected function ajoutAction(Request $request, Application $app) {
        return $this->getForm(new Formation(), 'src/Bowling/Resources/views/formation/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/formation/ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // y a-t-il des évènements sur cette formation ?
        if ($record->getEvenements()->count() == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'La formation "' . $record->getLibelle() . '" a bien été supprimée');
        } else {
            // oui : liste des x premiers en flasbag
            $nbRecords = $record->getEvenements()->count();
            $a = $nbRecords > 1 ? 'des' : 'un';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbRecords . ' évènement' . $s . ' associé' . $s . ' à cette formation : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi d'évènements à afficher dans le flashBag
            foreach ($record->getEvenements() as $r) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $r->getLibelle() . '"';
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
                new FormationForm(), $entity, array(
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
                            $this->app['url_generator']->generate('bowling-formation')
            );
        }

        return $this->app['twig']->render($view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Formation', $this->request->get('id'));
    }

}