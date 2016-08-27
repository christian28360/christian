<?php

namespace CHRIST\Common\Controller\Administration\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller of application
 *
 * @author glr735
 */
class AccreditationController extends LoginController
{

    protected function indexAction(Request $request, Application $app)
    {
        $application = $this->getApplication();
        $role = $application->getRoles()->first();
        
        return $this->getForm(
            'app/Resources/views/administration/login/manage-accreditation.html.twig'
            , $application
            , $role
        );
    }
    
    protected function manageAction(Request $request, Application $app)
    {
        $application = $this->getApplication();
        $role = $this->getRole();
        
        return $this->getForm(
            'app/Resources/views/administration/login/manage-accreditation.html.twig'
            , $application
            , $role
        );
    }
    
    /**
     * Return page of fragment page
     * @param string $view Path of view used by form
     * @param \CHRIST\Common\Entity\Login\Application $application Login Application to change
     * @param \CHRIST\Common\Entity\Login\Role $role Role to change
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function getForm(
        $view
        ,\CHRIST\Common\Entity\Login\Application $application
        , \CHRIST\Common\Entity\Login\Role $role
        )
    {
        if (is_null($role) || is_null($application) || !$application->getRoles()->offsetExists($role->getId())) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Vous ne pouvez pas modifier ce rôle');
        }
        
        $originalUsers = new \Doctrine\Common\Collections\ArrayCollection();

        // Create an ArrayCollection of the current User objects in the database
        foreach ($role->getUsers() as $user) {
            $originalUsers->add($user);
        }
    
        $form = $this->app["form.factory"]->create(
            new \CHRIST\Common\Form\Login\AccreditationForm(), 
            $role,
            array(
                'method' => 'POST',
            )
        );
        $form->handleRequest($this->request);
        
        if ($form->isValid()) {
            $data = $form->getData();
            
            foreach ($data->getUsers() as $user) {
                $user->addAccreditation($data);
                $data->addUser($user);
                $this->getEntityManager('login')->persist($user);
            }
            
            foreach ($originalUsers as $user) {
                if (false === $role->getUsers()->contains($user)) {
                    // remove the User from the Role
                    $role->removeUser($user);
                    $this->getEntityManager('login')->persist($user);
                }
            }
            
            $this->getEntityManager('login')->persist($role);
            $this->getEntityManager('login')->flush();
            
            $this->app['session']->getFlashBag()->add(
                'success', 
                'Les informations ont bien été enregistrées'
            );
            
            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'portail-si-admin-login-manage-accreditation',
                    array(
                        'application' => $application->getId(),
                        'role' => $role->getId(),
                    )
                )
            );
        }
        
        return $this->app['twig']->render(
            $view
            , array(
                'application'   => $application,
                'role'          => $role,
                'availables'    => $this->getLoginRepository('Role')->getAvailableUser($role),
                'form'          => $form->createView(),
            )
        );
    }
    
}
