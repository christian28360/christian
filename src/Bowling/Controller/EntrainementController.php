<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Form\CarnetForm;
use CHRIST\Modules\Bowling\Entity\Carnet;

/**
 * Description of EntrainementController
 *
 * @author Christian ALCON
 */
class EntrainementController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/entrainements/listeEntrainements.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'entrainements',
        ));
    }

    protected function listeSeptDixAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/entrainements/listeEntrainementsSeptDix.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'entrainementsSeptDix',
        ));
    }

    protected function carnetListeAction(Request $request, Application $app) {

        $em = $this->app['orm.ems']['christian'];

        // les carnets par date :
        $carnets = $em->getRepository('CHRIST\Modules\Bowling\Entity\carnet')
                ->findAllOrderedBydateAchat();

        // les parties par tranche de carnet
        $series = $em->getRepository('CHRIST\Modules\Bowling\Entity\serie')
                ->finAllByCarnet($carnets);

        return $this->templateRender('src/Bowling/Resources/views/entrainements/listeCarnets.html.twig', array(
                    'repository' => null,
                    'records' => $series,
        ));
    }

    /**
     * Saisie évènements
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function carnetAjoutAction(Request $request, Application $app) {

        return $this->getForm(new Carnet(), 'src/Bowling/Resources/views/entrainements/ajoutCarnet.html.twig');
    }

    protected function carnetUpdateAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/entrainements/ajoutCarnet.html.twig');
    }

    private function getForm($entity, $view) {

        $form = $this->app["form.factory"]->create(
                new CarnetForm(), $entity, array(
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
                            $this->app['url_generator']->generate('bowling-entrainement-carnet-liste')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Carnet', $this->request->get('id'));
    }

}
