<?php

namespace DTC\Modules\GestionReservationAuto;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\ServiceProviderInterface;
use Doctrine\ORM\Events;

/**
 * Description of SimulateurBudgetRegister
 *
 * @author glr735
 */
class GestionReservationAutoRegister implements ServiceProviderInterface
{
    static public $APPLICATION_SLUG = 'GRA';

    public function boot(\Silex\Application $app)
    {
        
    }

    public function register(\Silex\Application $app)
    {
        $app['dtc.user.ntlm.refresh'];
        
        $app['orm.ems']['gestionReservationAuto']->getConfiguration()->addFilter('soft-deleteable', 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter');
        $app['orm.ems']['gestionReservationAuto']->getFilters()->enable('soft-deleteable');
    }

}
