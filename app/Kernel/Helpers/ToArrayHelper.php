<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Kernel\Helpers;

/**
 * Transform an object to associative array.
 * If you want show private or protected attribute, fixe showPrivate parameter at true value
 * 
 * @author glr735
 */
class ToArrayHelper
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
     * Convert $this->object to associative array
     * @param boolean $showPrivate 
     * @return array
     */
    public function toArray($showPrivate = false)
    {
        return $this->processing($this->object, $showPrivate);
    }
    
    /**
     * Can transform an object to an array recursively
     * @param object $object Fixe at true value if you want private / protected attributes.
     * @return array
     */
    private function processing($object, $showPrivate = false)
    {
        $result = array();
	$reflectionClass = new \ReflectionClass(get_class($object));

	foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPublic() || ($showPrivate === true && ($property->isPrivate() || $property->isProtected()))) {

                    $property->setAccessible(true);
                    $value = $property->getValue($object);

                    if (is_object($value)) {
                        if ($this->skipSubobject === false) {
                            $temp[$property->getName()] = $this->processing($value, $showPrivate);
                            $result = array_merge($result, $temp);
                        }
                    } else {
                        $result[$property->getName()] = $value;	
                    }
                    $property->setAccessible(false);
            }
        }

	return $result;
    }
}
