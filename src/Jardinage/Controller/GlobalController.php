<?php

namespace CHRIST\Modules\Jardinage\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Default controller of application
 *
 * @author ALCON Christian
 */
class GlobalController extends \CHRIST\Common\Kernel\MasterController {

    /**
     * @return \CHRIST\Common\Entity\Samba
     */
    protected function getCurrentUser() {
        
        return $this->app['dtc.user.ntlm']->getEntity();
    }

    protected function templateRender($view = '', $parameters = array()) {

        $records = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Jardinage\Entity\\' . $parameters['repository']);

        // critère de tri selon la table
        switch ($parameters['tri']) {
            case 'libelle':
                $records = $records->findAllOrderedByLibelle();
                break;
            case 'nom':
                $records = $records->findAllOrderedByNom();
                break;
            case 'nomPrenom':
                $records = $records->findAllOrderedByNomPrenom();
                break;
            case 'titre':
                $records = $records->findAllOrderedByTitre();
                break;
            default:
                // cas non prévu
                $records = NULL;
        }

        $base = array(
            'records' => $records,
            'nbRecords' => count($records),
        );

        return $this->app['twig']->render(
                        $view, array_merge($base, $parameters)
        );
    }

    protected function writeData($entity) {

        $this->app['orm.ems']['christian']->persist($entity);
        $this->app['orm.ems']['christian']->flush();

        $this->app['session']->getFlashBag()->add(
                'succès', 'Les informations ont bien été enregistrées'
        );
    }

    /*
     * recharge la page courante, après l'action (créate, delete, ...)
     */

    protected function reload() {
        return new RedirectResponse($this->request->headers->get('referer'));
    }

}
