<?php

namespace CHRIST\Common\Controller\Administration\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Controller\AdministrationController;

/**
 * Default controller of application
 *
 * @author glr735
 */
class LoginController extends AdministrationController
{
    protected function indexAction(Request $request, Application $app)
    {
        return $this->app['twig']->render(
            'app/Resources/views/administration/login/index.html.twig'
        );
    }
    
    /**
     * Return current Application entity
     * @return \CHRIST\Common\Entity\Login\Application
     */
    protected function getApplication()
    {
        return $this->getLoginRepository('Application')->find($this->request->get('application'));
    }
    
    /**
     * Return current Role entity
     * @return \CHRIST\Common\Entity\Login\Role
     */
    protected function getRole()
    {
        return $this->getLoginRepository('Role')->find($this->request->get('role'));
    }
    
    /**
     * Return current User entity
     * @return \CHRIST\Common\Entity\Login\Role
     */
    protected function getUser()
    {
        return $this->getLoginRepository('User')->find($this->request->get('user'));
    }
}
