<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractEntity
 *
 * @author glr735
 */
abstract class AbstractEntity {

    /**
     * Name of entity manager
     * @var string
     */
    protected $entityManagerName;

    /**
     * Name of entity
     * @var string
     */
    protected $entityName;

    /**
     * Retourne true si l'entité possède un attribut id et que celui-ci n'est pas null
     * @return boolean
     */
    public function isNew() {
        $reflectionClass = new \ReflectionObject($this);

        if ($reflectionClass->hasProperty('id')) {
            $reflectionProperty = $reflectionClass->getProperty('id');
            $reflectionProperty->setAccessible(true);
            return is_null($reflectionProperty->getValue($this));
        }

        return false;
    }

    /**
     * Return repository
     * @return object
     */
    protected function getRepository() {

        $app = \CHRIST\Common\Kernel\SingleApp::getAppliation();

        return $app['orm.ems'][$this->entityManagerName]->getRepository($this->entityName);
    }

}
