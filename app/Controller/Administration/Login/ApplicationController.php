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
class ApplicationController extends LoginController
{
    protected function indexAction(Request $request, Application $app)
    {
        return $this->app['twig']->render(
            'app/Resources/views/administration/login/index-application.html.twig',
            array(
                'applications' => $this->getLoginRepository('Application')->findBy(array(), array('name' => 'ASC')),
            )
        );
    }
    
    protected function createAction(Request $request, Application $app)
    {
        return $this->getForm(
            'app/Resources/views/administration/login/create-application.html.twig'
            , new \CHRIST\Common\Entity\Login\Application()
            , 'POST'
        );
    }
    
    protected function updateAction(Request $request, Application $app)
    {
        return $this->getForm(
            'app/Resources/views/administration/login/update-application.html.twig'
            , $this->getApplication()
            , 'PUT'
        );
    }
    
    protected function deleteAction(Request $request, Application $app)
    {
        if (is_null($application = $this->getApplication())) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Vous ne pouvez pas supprimer cette application');
        }
        
        $application->removeRoles();
        $this->getEntityManager('login')->remove($application);            
        $this->getEntityManager('login')->flush();
        
        $this->app['session']->getFlashBag()->add(
            'success', 
            'L\'application a bien été supprimée'
        );

        return $this->app->redirect(
            $this->app['url_generator']->generate(
                'portail-si-admin-login-application'
            )
        );
    }

    /**
     * Return data application form in create or update context
     * @param string $template
     * @return string
     */
    private function getForm(
        $template = ''
        , \CHRIST\Common\Entity\Login\Application $application
        , $method
    )
    {
        $originalRoles = new \Doctrine\Common\Collections\ArrayCollection();

        // Create an ArrayCollection of the current Role objects in the database
        foreach ($application->getRoles() as $role) {
            $originalRoles->add($role);
        }
        
        $form = $this->app["form.factory"]->create(
            new \CHRIST\Common\Form\Login\ApplicationForm(), 
            $application, 
            array(
                'method' => $method,
            )
        );
        $form->handleRequest($this->request);
        
        if ($form->isValid()) {
            
            $this->getEntityManager('login')->persist($application);
            
            foreach ($application->getRoles() as $role) {
                $role->setApplication($application);
                $this->getEntityManager('login')->persist($role);
            }
            
            foreach ($originalRoles as $role) {
                if (false === $application->getRoles()->contains($role)) {
                    
                    // remove the User from the Role
                    $application->removeRole($role);
                    $role->setApplication(null);
                    $this->getEntityManager('login')->persist($role);
                    $this->getEntityManager('login')->remove($role);
                }
            }
                        
            $this->getEntityManager('login')->flush();
            
            $this->app['session']->getFlashBag()->add(
                'success', 
                'Les informations ont bien été enregistrées'
            );
            
            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'portail-si-admin-login-index-accreditation',
                    array(
                        'application' => $application->getId(),
                    )
                )
            );
        }
        
        return $this->app['twig']->render(
            $template
            , array(
                'form' => $form->createView(),
            )
        );
    }
}
