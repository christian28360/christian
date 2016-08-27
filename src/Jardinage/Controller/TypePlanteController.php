<?php

namespace CHRIST\Modules\Jardinage\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Jardinage\Entity\TypePlante;
use CHRIST\Modules\Jardinage\Form\TypePlanteForm;

/**
 * Saisie controller of application Jardinage
 *
 * @author Christian ALCON
 */
class TypePlanteController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {
        return $this->templateRender('src/Jardinage/Resources/views/typePlante/liste.html.twig', array(
                    'repository' => 'typePlante',
                    'tri' => 'libelle',
        ));
    }

    protected function ajoutAction(Request $request, Application $app) {
        return $this->getForm(new TypePlante(), 'src/Jardinage/Resources/views/typePlante/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent($this->request->get('id'));

        return $this->getForm($record, 'src/Jardinage/Resources/views/typePlante/ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {
        $record = $this->getCurrent($this->request->get('id'));

        // y a-t-il des évènements sur ce type de jeu ?
        if ($record->getEvenements()->count() == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le type "' . $record->getTypePlante() . ' - ' . $record->getLibelle() . '" a bien été supprimée');
        } else {
            // oui : liste des x premiers en flasbag
            $nbRecords = $record->getEvenements()->count();
            $a = $nbRecords > 1 ? 'des' : 'un';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbRecords . ' évènement' . $s . ' associé' . $s . ' à ce type : ';

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

    private function getForm(TypePlante $entity, $view) {

        $form = $this->app["form.factory"]->create(
                new TypePlanteForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );
        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('jardin-typePlante-liste')
            );
        }

        return $this->app['twig']->render($view, array(
                    'form' => $form->createView(),
                        )
        );
    }

    private function getCurrent($id = NULL) {

        $em = $this->app['orm.ems']['christian']
                        ->getRepository("CHRIST\Modules\Jardinage\Entity\TypePlante")->find($id);

        return $em;
    }

}
