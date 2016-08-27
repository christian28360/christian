<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Vocabulaire\Entity\Mot;
use CHRIST\Modules\Vocabulaire\Form\MotForm;

/**
 * Description of MotController
 *
 * @author Christian Alcon
 */
class MotController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {
        return $this->getTemplate('', 'all');
    }

    protected function listeConnusAction(Request $request, Application $app) {
        return $this->getTemplate('-filtree', 'connus');
    }

    protected function listeInconnusAction(Request $request, Application $app) {
        return $this->getTemplate('-filtree', 'inconnus');
    }

    protected function listeToUpdateAction(Request $request, Application $app) {
        return $this->getTemplate('-postImport', '_absent', '-maj-postImport');
    }

    protected function aChercherAction(Request $request, Application $app) {
        return $this->getTemplate('-aChercher', 'aChercher', '-aChercher');
    }

    /* Paramètre1 : sous-twig
     * Paramètre2 : filtre de recherche
     */

    protected function getTemplate($twig, $filter, $retour = null) {
        return $this->templateRender('src/Vocabulaire/Resources/views/mot-liste' . $twig . '.html.twig', array(
                    'repository' => 'mot',
                    'tri' => 'mot',
                    'filter' => $filter,
                    'retour' => $retour,
        ));
    }
    /**
     * Saisie des types de mots
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Mot(), 'src/Vocabulaire/Resources/views/mot-ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Vocabulaire/Resources/views/mot-ajout.html.twig');
    }

    protected function updateColAction(Request $request, Application $app) {


        $record = $this->getCurrent();
        $upDate = false;
        $em = $this->app['orm.ems']['christian'];

        switch ($this->request->get('col')) {
            case 'aApprendre':
                $record->setAApprendre(!$record->getAApprendre());
                $upDate = true;
                break;
            default:
                break;
        }
        if ($upDate) {
            $this->app['session']->getFlashBag()->add('info', 'La colonne "' .
                    $this->request->get('col') .
                    '" du mot "' . $record->getMot() .
                    '" a bien été inversée');
            $em->persist($record);
            $em->flush();
        }

        return $this->reload();
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        $this->app['orm.ems']['christian']->remove($record);
        $this->app['orm.ems']['christian']->flush();

        $this->app['session']->getFlashBag()->add('info', 'Le mot "' . $record->getMot() . '" a bien été supprimé');

        return $this->reload();
    }

    private function getForm($entity, $view, $retour = null) {

        $form = $this->app["form.factory"]->create(
                new MotForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->writeData($entity);
            // si action bouton valider +
            if ($form->getData()->getNouveauMot()) {
                return $this->app->redirect(
                                $this->app['url_generator']->generate('vocabulaire-mot-ajout'));
            }
            _dump('MotController, getForm, retour = ', 'vocabulaire-mot-liste' .
                    (!is_null($retour) ? '-' . $retour : ''));

            return $this->app->redirect(
                            $this->app['url_generator']->generate('vocabulaire-mot-liste' .
                                    (!is_null($retour) ? '-' . $retour : ''))
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Vocabulaire', 'Mot', $this->request->get('id'));
    }

}
