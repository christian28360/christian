<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Vocabulaire\Entity\Dictionnaire;
use CHRIST\Modules\Vocabulaire\Form\DictionnaireForm;

/**
 * Saisie controller of application Dictionnaires
 *
 * @author Christian ALCON
 */
class DictionnaireController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Vocabulaire/Resources/views/dictionnaire-liste.html.twig', array(
                    'repository' => 'dictionnaire',
                    'tri' => 'nom',
        ));
    }

    /**
     * Saisie Dictionnaires
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Dictionnaire(), 'src/Vocabulaire/Resources/views/dictionnaire-ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCourant('Dictionnaire', $this->request->get('id'));

        return $this->getForm($record, 'src/Vocabulaire/Resources/views/dictionnaire-ajout.html.twig');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCourant('Dictionnaire', $this->request->get('id'));

        // y a-t-il des mots référencés par ce dictionnaire ?
            $nbMots = 0;
//            $nbMots = $record->getMots()->count();
        if ($nbMots == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le dictionnaire "' . $record->getNom() . '" a bien été supprimé');
        } else {
            // oui : liste des 2 premiers en flasbag
            $a = $nbMots > 1 ? 'des' : 'un';
            $s = $nbMots > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbMots . ' mot' . $s . ' associé' . $s . ' à ce dictionnaire : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi de livre à afficher dans le flashBag
            foreach ($record->getDictionnaires() as $dico) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $dico->getNom() . '"';
            }
            $data .= $nbMots > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm(Dictionnaire $entity, $view) {
        
        $form = $this->app["form.factory"]->create(
                new DictionnaireForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('vocabulaire-dictionnaire-liste')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                        )
        );
    }

}
