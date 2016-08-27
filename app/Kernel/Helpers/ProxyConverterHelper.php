<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Kernel\Helpers;

/**
 * Description of ProxyConverterHelper
 *
 * @author glr735
 */
class ProxyConverterHelper
{
    
    /**
     * @var object
     */
    private $object = null;
    
    /**
     * @var boolean
     */
    private $skipSubobject;
    
    
    /**
     * Constructor
     * @param object $object
     */
    function __construct($object, $skipSubobject = false)
    {
        $this->object = $object;
        $this->skipSubobject = $skipSubobject;
    }

    /**
     * Convert an DoctrineProxy to real object
     * @return array
     */
    public function convert()
    {
        return $this->processing($this->object);
    }
    
    /**
     * Can transform an DoctrineProxy object to an real entity
     * @param object $proxy
     * @return array
     */
    private function processing($proxy)
    {        
        $className = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($proxy));
        $entity = new $className();
	$reflectionClass = new \ReflectionClass($className);
        
	foreach ($reflectionClass->getProperties() as $property) {
            
            $property->setAccessible(true);
            $propertyName = 'get' . ucfirst($property->getName());
            
            if (!method_exists(get_class($proxy), $propertyName)) {
                continue;
            }
            
            $value = $proxy->$propertyName();

            if ($value instanceof \Doctrine\Common\Persistence\Proxy) {
                if ($this->skipSubobject === false) {
                    $property->setValue($entity, $this->processing($value));
                }
            } else {
                $property->setValue($entity, $value);
            }
            
            $property->setAccessible(false);
        }

	return $entity;
    }
}
