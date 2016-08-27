<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author glr735
 */
class MasterController {

    /**
     * Application
     * @var \Silex\Application
     */
    protected $app;

    /**
     * Request
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __call($func, $args) {

        if (!method_exists($this, $func)) {
            throw new \Exception('Controller "' . get_class($this) . '::' . $func . '" doesn\'t exist.');
        }
        $this->initControllerEnvironement($args);
        return call_user_func_array(array($this, $func), $args);
    }

    protected function initControllerEnvironement($data) {
        $this->app = $data[1];
        $this->request = $data[0];
    }

    /**
     * Return entity manager object
     * @param string $em Name of entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager($em = '') {
        return $this->app['orm.ems'][$em];
    }

    /**
     * Return an repository
     * @param string $em Name of entity manager
     * @param string $entityName Name of entity (with namespace)
     * @return object
     */
    protected function getRepository($em = '', $entityName = '') {
        return $this->getEntityManager($em)
                        ->getRepository($entityName);
    }

    /**
     * Return an repository in login context
     * @param string $entityName Name of entity ex: 'Application'
     * @return object
     */
    protected function getLoginRepository($entityName = '') {
        return $this->getEntityManager('login')
                        ->getRepository('CHRIST\Common\Entity\Login\\' . $entityName);
    }

    protected function getCourant($module = null, $entity = null, $id = null) {
        return $this->app['orm.ems']['christian']
                        ->getRepository('CHRIST\Modules\\' . $module . '\Entity\\' . $entity)->find($id);
    }

    protected function writeData($entity) {

        $this->app['orm.ems']['christian']->persist($entity);
        $this->app['orm.ems']['christian']->flush();

        $this->app['session']->getFlashBag()->add(
                'success', 'Les informations ont bien été enregistrées'
        );
    }

}
