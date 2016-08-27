<?php

namespace CHRIST\Modules\Livre\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CHRIST\Modules\Livre\Entity\Livre;
use CHRIST\Modules\Livre\Form\LivreForm;

/**
 * Saisie controller of application Livres
 *
 * @author Christian ALCON
 */
class LivreController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Livre/Resources/views/livre/liste.html.twig', array(
                    'repository' => 'livre',
                    'tri' => 'titre',
        ));
    }

    /**
     * Saisie éditeurs
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {
        return $this->getForm(new Livre(), 'src/Livre/Resources/views/livre/ajout.html.twig');
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Livre/Resources/views/livre/ajout.html.twig', $record->getTitre());
    }

    protected function updateColAction(Request $request, Application $app) {

        $em = $this->app['orm.ems']['christian'];
        $record = $this->getCurrent();
        $upDate = true;

        switch ($this->request->get('col')) {
            case 'aLire':
                $record->setALire(!$record->getALire());
                break;
            case 'enStock':
                $record->setEnStock(!$record->getEnStock());
                break;
            default:
                $upDate = false;
                break;
        }
        if ($upDate) {
            $this->app['session']->getFlashBag()->add('info', 'La colonne "' .
                    $this->request->get('col') .
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

        $this->app['session']->getFlashBag()->add('info', 'Le livre "' . $record->getTitre() . '" a bien été supprimé');

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm($entity, $view, $old = null) {

        //var_dump($view);exit();

        $form = $this->app["form.factory"]->create(
                new LivreForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $entity = $form->getData();

            // Ne pas créer de doublon
            $titre = $form->get('titre')->getData();

            // si création : contrôle direct
            if ($old == null) {
                $doublon = $this->isDoublon($titre);
            } else {
                // si màj, on regarde si on a modifié le libellé par un qui existe déjà
                $doublon = ( strtolower($titre) === strtolower($old) ) ? FALSE : $this->isDoublon($titre);
            }
            if ($doublon) {
                $this->app['session']->getFlashBag()->add(
                        'danger', 'le livre "' . $titre . '" existe déja, enregistrement non effectué');
            } else {

                $this->app['orm.ems']['christian']->persist($entity);
                $this->app['orm.ems']['christian']->flush();

                $this->app['session']->getFlashBag()->add(
                        'success', 'Les informations ont bien été enregistrées'
                );
            }

            return $this->app->redirect(
                            $this->app['url_generator']->generate('livre-livre')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'mode' => is_null($old) ? 'création' : 'maj',
                        )
        );
    }

    protected function livreALireAction(Request $request, Application $app) {
        return $this->getTemplate('livresALire', '', 'aLire', 'true', null);
    }

    protected function livreEnStockAction(Request $request, Application $app) {
        return $this->getTemplate('livresAbsentsOuPresents', 'présent', 'enStock', 'true', null);
    }

    protected function livrePlusEnStockAction(Request $request, Application $app) {
        return $this->getTemplate('livresAbsentsOuPresents', 'abstent', 'enStock', 'false', null);
    }

    protected function livreAvecVocabulaireAction(Request $request, Application $app) {
        return $this->getTemplate('livresAvecVocabulaire', '', 'vocabulaire', 'true', null);
    }

    protected function getTemplate($twig, $twigFilter, $col, $val, $retour = null) {

        // get data filtered
        $records = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Livre\Entity\livre')
                ->findAllDataFiltered($col, $val);

        return $this->app['twig']->render('src/Livre/Resources/views/livre/' . $twig . '.html.twig', array(
                    'tiwgFilter' => $twigFilter,
                    'retour' => $retour,
                    'records' => $records,
                    'nbRecords' => count($records),
        ));
    }

    protected function getCurrent() {
        return $this->getCourant('Livre', 'Livre', $this->request->get('id'));
    }

    private function isDoublon($titre) {

        $r = $this->app['orm.ems']['christian']
                ->getRepository("CHRIST\Modules\Livre\Entity\Livre")
                ->findByTitre($titre);

        return empty($r) ? FALSE : TRUE;
    }

}
