<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Modules\Vocabulaire\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Vocabulaire\Entity\TypeMot;
use CHRIST\Modules\Vocabulaire\Form\TypeMotForm;

/**
 * Description of TypeMotController
 *
 * @author Christian ALCON
 */
class TypeMotController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Vocabulaire/Resources/views/typeMot-liste.html.twig', array(
                    'repository' => 'typeMot',
                    'tri' => 'libelle',
        ));
    }
    
    /**
     * Saisie des types de mots
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new TypeMot(), 'src/Vocabulaire/Resources/views/typeMot-ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Vocabulaire/Resources/views/typeMot-ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // y a-t-il des mots référencés par ce type ?
//            $nbMots = $record->getMots()->count();
            $nbMots = 0;
        if ($nbMots == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le type de mot "' . $record . '" a bien été supprimé');
        } else {
            // oui : liste des 2 premiers en flasbag
            $a = $nbMots > 1 ? 'des' : 'un';
            $s = $nbMots > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbMots . ' mot' . $s . ' associé' . $s . ' à ce dictionnaire : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi de livre à afficher dans le flashBag
            foreach ($record->getDictionnaires() as $livre) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $livre->getTitre() . '"';
            }
            $data .= $nbMots > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    private function getForm($entity, $view) {

        $form = $this->app["form.factory"]->create(
                new TypeMotForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('vocabulaire-typeMot-liste')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Vocabulaire', 'TypeMot', $this->request->get('id'));
    }

}
