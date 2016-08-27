<?php

namespace CHRIST\Common\Kernel\Modules\Import\Manager;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use CHRIST\Common\Kernel\SingleApp;

/**
 * Description of ImportManager
 *
 * @author glr735
 */
abstract class ImportManager {

    /**
     * @var \Silex\Application
     */
    protected $app = null;

    /**
     * Entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;

    abstract public function getEntity();

    function __construct(\Silex\Application $app, \Doctrine\ORM\EntityManager $em) {
        $this->app = $app;
        $this->em = $em;
    }

    /**
     * Entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    /**
     * Filter one entity
     * return false to skip entity
     * @return boolean
     */
    public function filter() {
        return true;
    }

    /**
     * Validate entity
     * @throws \Exception
     */
    public function validate() {
        $errors = $this->app['validator']->validate($this->entity);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $strError = $error->getPropertyPath() . ' - ' . $error->getMessage();

                if (!is_array($error->getInvalidValue()) && !is_object($error->getInvalidValue())) {
                    $strError .= ' Error value: ' . $error->getInvalidValue() . ' (' . getType($error->getInvalidValue()) . ')';
                }
                throw new \Exception($strError, 1);
            }
        }
    }

    public function save() {
        $this->validate();

        $this->em->persist($this->entity);
    }

    public function delete($level = 0) {
        _dump('delete dans import manager, puis exit()', $level);
        exit();
        return $this->em
                        ->getRepository(get_class($this->entity))
                        ->deleteAll();
    }

    public function count($level = 0) {
        _dump('count dans import manager, puis exit()', $level);
        return $this->em
                        ->getRepository(get_class($this->entity))
                        ->countAll();
    }

}
