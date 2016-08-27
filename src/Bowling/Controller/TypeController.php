<?php

namespace DTC\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use DTC\Modules\Bowling\Entity\TypeJeu;
use DTC\Modules\Bowling\Form\TypeJeuForm;

/**
 * Saisie controller of application Bowling
 *
 * @author Christian ALCON
 */
class TypeController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/type/liste.html.twig', array(
                    'repository' => 'typeJeu',
                    'tri' => 'libelle',
        ));
    }
//
//    protected function listeTypesAction(Request $request, Application $app) {
//
//        return $this->templateRender('src/Bowling/Resources/views/type/liste.html.twig', array(
//                    'repository' => 'typeJeu',
//                    'tri' => 'libelle',
//        ));
//    }

    /**
     * Saisie types
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new TypeJeu(), 'src/Bowling/Resources/views/type/ajout.html.twig', NULL);
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/type/ajout.html.twig', $record->getLibelle());
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // y a-t-il des livres affectés à cette couverture ?
        if ($record->getTypes()->count() == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'Le type "' . $record->getLibelle() . '" a bien été supprimée');
        } else {
            // oui : liste des 2 premiers en flasbag
            $nbLivres = $record->getTypes()->count();
            $a = $nbLivres > 1 ? 'des' : 'un';
            $s = $nbLivres > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbLivres . ' activités' . $s . ' associée' . $s . ' à ce type : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi de livre à afficher dans le flashBag
            foreach ($record->getTypes() as $livre) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $livre->getLibelle() . '"';
            }
            $data .= $nbLivres > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm($entity, $view, $old = null) {

        $form = $this->app["form.factory"]->create(
                new TypeJeu(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $entity = $form->getData();

            // Ne pas créer de doublon
            $libelle = $form->get('libelle')->getData();

            // si création : contrôle direct
            if ($old == null) {
                $doublon = $this->isDoublon($libelle);
            } else {
                // si màj, on regarde si on a modifié le libellé par un qui existe déjà
                $doublon = ( strtolower($libelle) === strtolower($old) ) ? FALSE : $this->isDoublon($libelle);
            }
            if ($doublon) {
                $this->app['session']->getFlashBag()->add(
                        'danger', 'le type "' . $libelle . '" existe déja, enregistrement non effectué');
            } else {

                $this->app['orm.ems']['christian']->persist($entity);
                $this->app['orm.ems']['christian']->flush();

                $this->app['session']->getFlashBag()->add(
                        'success', 'Les informations ont bien été enregistrées'
                );
            }
            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-type')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'mode' => is_null($old) ? 'création' : 'maj',
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'TypeJeu', $this->request->get('id'));
    }

    private function isDoublon($libelle) {

        $r = $this->app['orm.ems']['christian']
                ->getRepository("DTC\Modules\Bowling\Entity\TypeJeu")
                ->findByLibelle($libelle);

        return empty($r) ? FALSE : TRUE;
    }

}
