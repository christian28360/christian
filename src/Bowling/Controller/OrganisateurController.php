<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Organisateur;
use CHRIST\Modules\Bowling\Form\OrganisateurForm;

/**
 * Saisie controller of application Bowling
 *
 * @author Christian ALCON
 */
class OrganisateurController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {
        return $this->templateRender('src/Bowling/Resources/views/tournoi/organisateur/liste.html.twig', array(
                    'repository' => 'organisateur',
                    'tri' => 'nom',
        ));
    }

    protected function ajoutAction(Request $request, Application $app) {
        return $this->getForm(new Organisateur(), 'src/Bowling/Resources/views/tournoi/organisateur/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/tournoi/organisateur/ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        // y a-t-il des tournois sur cet organisateur ?
        if ($record->getTournois()->count() == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'L\'organisateur "' . $record->getNom() . '" a bien été supprimé');
        } else {
            // oui : liste des x premiers en flasbag
            $nbRecords = $record->getTournois()->count();
            $a = $nbRecords > 1 ? 'des' : 'un';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbRecords . ' tournoi' . $s . ' associé' . $s . ' à cet organisateur : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi d'évènements à afficher dans le flashBag
            foreach ($record->getTournois() as $r) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $d = is_null($r->getDateTournoi()) ? 'non planifié' : $r->getDateTournoi()->format('d/m/Y');
                $data .= '"' . $d . '"';
            }
            $data .= $nbRecords > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm(Organisateur $entity, $view) {
        $form = $this->app["form.factory"]->create(
                new OrganisateurForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-organisateur')
            );
        }

        return $this->app['twig']->render($view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Organisateur', $this->request->get('id'));
    }

}