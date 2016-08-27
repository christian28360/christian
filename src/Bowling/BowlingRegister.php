<?php

namespace CHRIST\Modules\Bowling;

/*
 * PROPERTY OF LA POSTE
 */

use Silex\ServiceProviderInterface;
use Doctrine\ORM\Events;

/**
 * @author glr735
 */
class BowlingRegister implements ServiceProviderInterface
{

    public function boot(\Silex\Application $app)
    {
        
    }

    public function register(\Silex\Application $app)
    {
        $eventManager = $app['dbs']['christian']->getEventManager();
        $eventManager->addEventListener(array(Events::onFlush, Events::preFlush), new Listener\BowlingListener());
    }

}
