<?php

namespace CHRIST\Common\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use CHRIST\Common\Kernel\MasterController;

/**
 * Default controller of application
 *
 * @author glr735
 */
class AdministrationController extends MasterController
{
    public function __call($func, $args)
    {
        $controllerStatement = parent::__call($func, $args);
        
        try {
            if ($this->app['dtc.user.ntlm']->getSecureEntity()->hasRole('ROLE_PORTAIL_SI_ADMINISTRATEUR') === false) {
                throw new \Exception('Access denied');
            }
        } catch (\Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('You are not authorized');
        }
        
        return $controllerStatement;
    }
}
