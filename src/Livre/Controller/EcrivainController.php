<?php

namespace CHRIST\Modules\Livre\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CHRIST\Modules\Livre\Entity\Ecrivain;
use CHRIST\Modules\Livre\Form\EcrivainForm;

/**
 * Saisie controller of application Livres
 *
 * @author Christian ALCON
 */
class EcrivainController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Livre/Resources/views/ecrivain/liste.html.twig', array(
                    'repository' => 'ecrivain',
                    'tri' => 'nomPrenom',
        ));
    }

    protected function listeLivresAction(Request $request, Application $app) {

        return $this->templateRender('src/Livre/Resources/views/ecrivain/listeLivres.html.twig', array(
                    'repository' => 'ecrivain',
                    'tri' => 'nomPrenom',
        ));
    }

    /**
     * Saisie écrivains
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Ecrivain(), 'src/Livre/Resources/views/ecrivain/ajout.html.twig', NULL);
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent($this->request->get('id'));

        return $this->getForm($record, 'src/Livre/Resources/views/ecrivain/ajout.html.twig', $record->getNom() . $record->getPreNom());
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent($this->request->get('id'));
        $nbLivres = $record->getLivres()->count();

        // y a-t-il des livres écrits par cet écrivain ?
        if ($nbLivres == 0) {
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'L\'écrivain "' . $record->getNom() . '" a bien été supprimé');
        } else {
            // oui : liste des 2 premiers en flasbag
            $s = $nbLivres > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbLivres . ' livre' . $s . ' écrit' . $s . ' par cet écrivain : ';

            $data = '';
            $i = 0;
            $limite = 3;        // Nbre maxi de livre à afficher dans le flashBag
            foreach ($record->getLivres() as $livre) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $livre->getTitre() . '"';
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
                new EcrivainForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $entity = $form->getData();

            // Ne pas créer de doublon
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();

            // si création : contrôle direct
            if ($old == null) {
                $doublon = $this->isDoublon($nom . $prenom);
            } else {
                // si màj, on regarde si on a modifié le nom par un qui existe déjà
                $doublon = ( strtolower($nom . $prenom) === strtolower($old) ) ? FALSE : $this->isDoublon($nom . $prenom);
            }

            if ($doublon) {
                $this->app['session']->getFlashBag()->add(
                        'danger', 'l\'écrivain "' . $nom . ' ' . $prenom . '" existe déja, enregistrement non effectué');
            } else {
                $this->app['orm.ems']['christian']->persist($entity);
                $this->app['orm.ems']['christian']->flush();

                $this->app['session']->getFlashBag()->add(
                        'success', 'Les informations ont bien été enregistrées'
                );
            }

            return $this->app->redirect(
                            $this->app['url_generator']->generate('livre-ecrivain')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'mode' => is_null($old) ? 'création' : 'maj',
                        )
        );
    }

    private function getCurrent($id = NULL) {

        $em = $this->app['orm.ems']['christian']
                        ->getRepository("CHRIST\Modules\Livre\Entity\Ecrivain")->find($id);

        return $em;
    }

    private function isDoublon($nom) {

        $q = $this->app['orm.ems']['christian']
                ->getRepository("CHRIST\Modules\Livre\Entity\Ecrivain")
                ->findByNom($nom);

        return empty($q) ? FALSE : TRUE;
    }

}
