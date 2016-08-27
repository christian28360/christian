<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\User;
use DTC\Modules\GestionReservationAuto\Entity\Voiture;
use DTC\Modules\GestionReservationAuto\Entity\Service;
use DTC\Modules\GestionReservationAuto\Entity\Reservation;
use Symfony\Component\Form\FormError;
use DTC\Common\Kernel\MasterController;

/**
 * Default controller of application
 *
 * @author wug870
 */
class GlobalController extends MasterController
{

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\User
     */
    private $gpaUser = null;

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent
     */
    private $sourceAgent = null;

    public function __call($func, $args)
    {
        $this->initControllerEnvironement($args);

        $routesAllowed = array(
            'gestion-reservation-auto-grade-menu',
            'gestion-reservation-auto-user-create',
        );
        if (is_null($this->getGPAUser()) && !in_array($this->request->get('_route'), $routesAllowed)) {
            return call_user_func_array(array($this, 'underknowUserRedirect'), $args);
        }
        /*if (!is_null($this->getGPAUser()->getDeletedAt())){
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Access is forbidden');
        }*/
        return parent::__call($func, $args);
    }

    protected function getCurrentAgent()
    {
        if (is_null($this->sourceAgent)) {
            $this->sourceAgent = $this->getRepository(
                            'gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent'
                    )->findOneByAgeCodeDt($this->app['dtc.user.ntlm']->getEntity()->getCodeRh());
        }

        if (is_null($this->sourceAgent)) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Access is forbidden');
        }

        return $this->sourceAgent;
    }

    protected function getGPAUser()
    {
        if (!is_null($this->gpaUser)) return $this->gpaUser;
        

        return $this->gpaUser = $this->getCurrentAgent()->getGpaUser();
    }

    private function underknowUserRedirect(Request $request, Application $app)
    {
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-user-create')
        );
    }

}
