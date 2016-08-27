<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

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

//        _dump('templateRender, $view', $view);
//        _dump('templateRender, $parameters', $parameters);
//        
        
        $records = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Vocabulaire\Entity\\' . $parameters['repository']);

        // critère de tri selon la table
        switch ($parameters['tri']) {
            case 'libelle':
                $records = $records->findAllOrderedByLibelle();
                break;
            case 'nom':
                $records = $records->findAllOrderedByNom();
                break;
            case 'mot':
                $records = $records->findAllOrderedByMot($parameters['filter']);
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

    /*
     * recharge la page courante, après l'action (créate, delete, ...)
     */
    protected function reload() {
        return new RedirectResponse($this->request->headers->get('referer'));
    }

}
