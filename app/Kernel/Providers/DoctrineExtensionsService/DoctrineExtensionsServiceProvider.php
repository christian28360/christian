<?php

namespace CHRIST\Common\Kernel\Providers\DoctrineExtensionsService;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\ServiceProviderInterface;

/**
 * Description of DoctrineExtensionsServiceProvider
 *
 * @author glr735
 */
class DoctrineExtensionsServiceProvider implements ServiceProviderInterface
{

    public function boot(\Silex\Application $app)
    {
        foreach (array_keys($app['dbs.options']) as $connectionName) {
            
            $eventManager = $app['dbs'][$connectionName]->getEventManager();
            
            foreach ($app['doctrine_orm.extensions'] as $subscriber) {
                    
                $eventManager->addEventSubscriber($subscriber);
            }
        }
        
        \Gedmo\DoctrineExtensions::registerAnnotations();
    }

    public function register(\Silex\Application $app)
    {
        
    }

}
