<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Form\UserForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class GestionnaireUserController extends GestionnaireController
{

    protected function homeAction(Request $request, Application $app)
    {
        $rep = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\User");
        $lesUsers = $rep->findByManyPerimetre(
                $this->getGPAUser()->getSourceAgent()->getSourceOrgaAffectectation()->getPerimetres()
        );

        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/user/readByService.html.twig', array("lesUser" => $lesUsers));
    }

    /**
     * Update user
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function updateAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\User");
        $user = $repository->find($id);
        
        //Si pas en méthode Post
        $form = $this->app["form.factory"]->create(new UserForm(true, null, $this->getGPAUser()), $user, array(
            'method' => $request->getMethod(),
        ));

        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($user);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été modifiées.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-user-service')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/user/update.html.twig', array("form" => $form->createView(),
                    "idUser" => $user->getIdUser(),
                    "user" => $user,
                    "mode" => 'advanced'));
    }

    /**
     * Delete user
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function deleteAction(Request $request, Application $app, $id)
    {
        $user = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\User")
                ->findOneByIdUser($request->get('id'));
        $this->app['orm.ems']['gestionReservationAuto']->remove($user);
        $this->app['orm.ems']['gestionReservationAuto']->flush();

        $this->app['session']->getFlashBag()->add(
                'success', 'L\'utilisateur a bien été supprimé'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-user-service')
        );
    }

}
