<?php

namespace CHRIST\Modules\Livre\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CHRIST\Modules\Livre\Entity\Editeur;
use CHRIST\Modules\Livre\Form\EditeurForm;

/**
 * Saisie controller of application Livres
 *
 * @author Christian ALCON
 */
class EditeurController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {
        return $this->templateRender('src/Livre/Resources/views/editeur/liste.html.twig', array(
                    'repository' => 'editeur',
                    'tri' => 'nom',
        ));
    }

    protected function listeLivresAction(Request $request, Application $app)
    {

        return $this->templateRender('src/Livre/Resources/views/editeur/listeLivres.html.twig', array(
                    'repository' => 'editeur',
                    'tri' => 'nom',
        ));
    }

    /**
     * Saisie éditeurs
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Editeur(), 'src/Livre/Resources/views/editeur/ajout.html.twig', NULL);
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent($this->request->get('id'));

        return $this->getForm($record, 'src/Livre/Resources/views/editeur/ajout.html.twig', $record->getNom());
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent($this->request->get('id'));

        // y a-t-il des livres affectés à ce thème ?
        if ($record->getLivres()->count() == 0) {
            // aucun => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'L\'éditeur "' . $record->getNom() . '" a bien été supprimé');
        } else {
            // oui : liste des 2 premiers en flasbag
            $nbLivres = $record->getLivres()->count();
            $a = $nbLivres > 1 ? 'des' : 'un';
            $s = $nbLivres > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbLivres . ' livre' . $s . ' associé' . $s . ' à cet éditeur : ';

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
                new EditeurForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $entity = $form->getData();

            // Ne pas créer de doublon
            $nom = $form->get('nom')->getData();

            // si création : contrôle direct
            if ($old == null) {
                $doublon = $this->isDoublon($nom);
            } else {
                // si màj, on regarde si on a modifié le nom par un qui existe déjà
                $doublon = ( strtolower($nom) === strtolower($old) ) ? FALSE : $this->isDoublon($nom);
            }
            if ($doublon) {
                $this->app['session']->getFlashBag()->add(
                        'danger', 'l\'éditeur "' . $nom . '" existe déja, enregistrement non effectué');
            } else {
                $this->app['orm.ems']['christian']->persist($entity);
                $this->app['orm.ems']['christian']->flush();

                $this->app['session']->getFlashBag()->add(
                        'success', 'Les informations ont bien été enregistrées'
                );
            }

            return $this->app->redirect(
                            $this->app['url_generator']->generate('livre-editeur')
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
                        ->getRepository("CHRIST\Modules\Livre\Entity\Editeur")->find($id);

        return $em;
    }

    private function isDoublon($nom) {

        $r = $this->app['orm.ems']['christian']
                ->getRepository("CHRIST\Modules\Livre\Entity\Editeur")
                ->findByNom($nom);

        return empty($r) ? FALSE : TRUE;
    }

}
