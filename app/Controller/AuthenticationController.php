<?php

namespace CHRIST\Common\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\MasterController;

/**
 * Description of AuthenticationController
 *
 * @author glr735
 */
class AuthenticationController extends MasterController
{
    protected function loginAction(Request $request, Application $app)
    {
        if (!_empty($message = $this->app['translator']->trans($this->app['security.last_error']($request)))) {
            $this->app['session']->getFlashBag()->add('danger', $message);
        }
        
        return $this->app['twig']->render(
            'app/Resources/views/login.html.twig',
             array(
                 'last_username' => $this->app['session']->get('_security.last_username'),
             )
        );
    }
}
