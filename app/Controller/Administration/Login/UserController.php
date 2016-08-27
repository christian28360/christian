<?php

namespace CHRIST\Common\Controller\Administration\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

/**
 * Default controller of application
 *
 * @author glr735
 */
class UserController extends LoginController
{
    protected function indexAction(Request $request, Application $app)
    {
        return $this->app['twig']->render(
            'app/Resources/views/administration/login/index-user.html.twig',
            array(
                'users' => $this->getLoginRepository('User')->findBy(array(), array('login' => 'ASC')),
            )
        );
    }
    
    protected function createAction(Request $request, Application $app)
    {
        return $this->getForm(
            'app/Resources/views/administration/login/create-user.html.twig'
            , new \CHRIST\Common\Entity\Login\User()
            , 'POST'
        );
    }
    
    protected function updateAction(Request $request, Application $app)
    {
        $user = $this->getUser();
        
        if (is_null($user)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User doesn\'t exist');
        }
        
        return $this->getForm(
            'app/Resources/views/administration/login/update-user.html.twig'
            , $user
            , 'PUT'
        );
    }
    
    protected function duplicateAction(Request $request, Application $app)
    {
        $user = $this->getUser();
        $login = $this->request->get('login-clone');
        
        if (is_null($user)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User doesn\'t exist');
        }
        
        $userCloned = $user->cloneEntity($login);
        $userCloned->changePassword($this->app['security.encoder.digest'], $login);
        
        $this->getEntityManager('login')->persist($userCloned);            
        $this->getEntityManager('login')->flush();
        
        return $this->app->redirect(
            $this->app['url_generator']->generate(
                'portail-si-admin-login-update-user', array(
                    'user' => $userCloned->getLogin(),
                )
            )
        );
    }
    
    protected function deleteAction(Request $request, Application $app)
    {
        if (is_null($user = $this->getUser())) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Vous ne pouvez pas supprimer cet utilisateur');
        }
        
        $user->removeAccreditations();
        $this->getEntityManager('login')->remove($user);            
        $this->getEntityManager('login')->flush();
        
        $this->app['session']->getFlashBag()->add(
            'success', 
            'L\'utilisateur a bien été supprimée'
        );

        return $this->app->redirect(
            $this->app['url_generator']->generate(
                'portail-si-admin-login-user'
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
        , \CHRIST\Common\Entity\Login\User $user
        , $method
    )
    {
        $form = $this->app["form.factory"]->create(
            new \CHRIST\Common\Form\Login\UserForm($method == 'PUT' ? true : false), 
            $user, 
            array(
                'method' => $method,
            )
        );
        $form->handleRequest($this->request);
        
        // Additionnal constrainte, check the login is not updated
        if ($method == 'PUT' && $form->getData()->getLogin() != $this->request->get('user')) {
            $form->addError(new FormError('Vous n\'êtes pas autorisé à modifier le login de l\'utilisateur'));
        }
        
        if ($form->isValid()) {
            $data = $this->request->get('UserForm');
            
            if (!empty($data['password'])) {
                $user->changePassword($this->app['security.encoder.digest'], $data['password']);
            }            
            
            $this->getEntityManager('login')->persist($user);            
            $this->getEntityManager('login')->flush();
            
            $this->app['session']->getFlashBag()->add(
                'success', 
                'Les informations ont bien été enregistrées'
            );
            
            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'portail-si-admin-login-user'
                )
            );
        }
        
        return $this->app['twig']->render(
            $template
            , array(
                'form' => $form->createView(),
                'applications' => $this->getLoginRepository('Application')->findBy(array(), array('name' => 'ASC')),
            )
        );
    }
    
}
