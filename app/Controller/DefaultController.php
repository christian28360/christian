<?php

namespace CHRIST\Common\Controller;

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
class DefaultController
{
    public function homeAction(Request $request, Application $app)
    {
        return $app['twig']->render('app/Resources/views/index.html.twig');
    }
    
    public function userBadgeAction(Request $request, Application $app)
    {	
        $user = 'Christian';
//        $user = $app['dtc.user.ntlm']->getEntity();
        
        /*
         * Sélection du template
         ********************************************************/
        return $app['twig']->render(
            'app/Resources/views/fragments/user-badge.html.twig', 
            array(
                'user' => $user,
            )
        );
        /********************************************************/
    }
}
