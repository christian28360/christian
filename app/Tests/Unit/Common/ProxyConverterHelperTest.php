<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Tests\Unit\Common;

/**
 * Description of ToArrayHelperTest
 *
 * @author glr735
 */
class ProxyConverterHelperTest extends AbstractManagerBase
{
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $proxyLoader;

    /**
     * @var ClassMetadata
     */
    protected $lazyLoadableObjectMetadata;

    /**
     * @var LazyLoadableObject|Proxy
     */
    protected $lazyObject;

    protected $identifier = array(
        'publicIdentifierField' => 'publicIdentifierFieldValue',
        'protectedIdentifierField' => 'protectedIdentifierFieldValue',
    );

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Callable
     */
    protected $initializerCallbackMock;
    
    
    

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->proxyLoader = $loader      = $this->getMock('stdClass', array('load'), array(), '', false);
        $this->initializerCallbackMock    = $this->getMock('stdClass', array('__invoke'));
        $identifier                       = $this->identifier;
        $this->lazyLoadableObjectMetadata = $metadata = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\LazyLoadableObjectClassMetadata();

        // emulating what should happen in a proxy factory
        $cloner = function (LazyLoadableObject $proxy) use ($loader, $identifier, $metadata) {
            /* @var $proxy LazyLoadableObject|Proxy */
            if ($proxy->__isInitialized()) {
                return;
            }

            $proxy->__setInitialized(true);
            $proxy->__setInitializer(null);
            $original = $loader->load($identifier);

            if (null === $original) {
                throw new UnexpectedValueException();
            }

            foreach ($metadata->getReflectionClass()->getProperties() as $reflProperty) {
                $propertyName = $reflProperty->getName();

                if ($metadata->hasField($propertyName) || $metadata->hasAssociation($propertyName)) {
                    $reflProperty->setAccessible(true);
                    $reflProperty->setValue($proxy, $reflProperty->getValue($original));
                }
            }
        };

        $proxyClassName = '__CG__\CHRIST\Common\Tests\Unit\Fixtures\Mocks\LazyLoadableObject';

        // creating the proxy class
        if (!class_exists($proxyClassName, false)) {
            $path = dirname(__DIR__) . '\Fixtures\Generated';
            $proxyGenerator = new \Doctrine\Common\Proxy\ProxyGenerator($path, 'CHRIST\Common\Tests\Unit\Common\Fixtures\Generated');
            $proxyGenerator->generateProxyClass($metadata, $path . '/' . $proxyClassName . '.php');
            require_once $path . '/' . $proxyClassName . '.php';
        }
        
        $proxyClassName = 'CHRIST\Common\Tests\Unit\Common\Fixtures\Generated\\' . $proxyClassName;
        
        $this->lazyObject = new $proxyClassName($this->getClosure($this->initializerCallbackMock), $cloner);

        // setting identifiers in the proxy via reflection
        foreach ($metadata->getIdentifierFieldNames() as $idField) {
            $prop = $metadata->getReflectionClass()->getProperty($idField);
            $prop->setAccessible(true);
            $prop->setValue($this->lazyObject, $identifier[$idField]);
        }

        $this->assertFalse($this->lazyObject->__isInitialized());
    }
    
    
    
    public function testConvert()
    {
        $this->assertTrue($this->lazyObject instanceof \Doctrine\Common\Persistence\Proxy);
        
        $converter = new \CHRIST\Common\Kernel\Helpers\ProxyConverterHelper($this->lazyObject);
        $object = $converter->convert();
        
        $this->assertEquals($object->getPublicIdentifierField(), 'publicIdentifierFieldValue');
        $this->assertEquals($object->getProtectedIdentifierField(), 'protectedIdentifierFieldValue');
        $this->assertEquals($object->getPublicTransientField(), 'publicTransientFieldValue');
        $this->assertEquals($object->getProtectedTransientField(), 'protectedTransientFieldValue');
        $this->assertEquals($object->getProtectedPersistentField(), 'protectedPersistentFieldValue');
        $this->assertEquals($object->getProtectedAssociation(), 'protectedAssociationValue');
    }

    /**
     * Converts a given callable into a closure
     *
     * @param  callable $callable
     * @return \Closure
     */
    public function getClosure($callable) {
        return function () use ($callable) {
            call_user_func_array($callable, func_get_args());
        };
    }
}
