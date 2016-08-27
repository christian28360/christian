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
class ToArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testToArrayWithoutPrivate()
    {
        $a = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\A();
        
        $converterA = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a);
        $converterB = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other);
        $converterC = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other->other);
        
        $array_a = $converterA->toArray();
        $resulA = array(
            'publicName' => 'PUBLIC CLASS A',
            'other' => array(
                'publicName' => 'PUBLIC CLASS B',
                'other' => array(
                    'publicName' => 'PUBLIC CLASS C',
                ),
            ),
        );
        
        $array_b = $converterB->toArray();
        $resulB = array(
            'publicName' => 'PUBLIC CLASS B',
            'other' => array(
                'publicName' => 'PUBLIC CLASS C',
            ),
        );
        
        $array_c = $converterC->toArray();
        $resulC = array(
            'publicName' => 'PUBLIC CLASS C',
        );
        
        $this->assertCount(2, $array_a);
        $this->assertTrue(($array_a == $resulA));
        
        $this->assertCount(2, $array_b);
        $this->assertTrue(($array_b == $resulB));
        
        $this->assertCount(1, $array_c);
        $this->assertTrue(($array_c == $resulC));
    }
    
    public function testToArrayWithPrivate()
    {
        $a = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\A();
        
        $converterA = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a);
        $converterB = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other);
        $converterC = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other->other);
        
        $array_a = $converterA->toArray(true);
        $resulA = array(
            'publicName' => 'PUBLIC CLASS A',
            'protectedName' => 'PROTECTED CLASS A',
            'privateName' => 'PRIVATE CLASS A',
            'other' => array(
                'publicName' => 'PUBLIC CLASS B',
                'protectedName' => 'PROTECTED CLASS B',
                'privateName' => 'PRIVATE CLASS B',
                'other' => array(
                    'publicName' => 'PUBLIC CLASS C',
                    'protectedName' => 'PROTECTED CLASS C',
                    'privateName' => 'PRIVATE CLASS C',
                ),
            ),
        );
        
        $array_b = $converterB->toArray(true);
        $resulB = array(
            'publicName' => 'PUBLIC CLASS B',
            'protectedName' => 'PROTECTED CLASS B',
            'privateName' => 'PRIVATE CLASS B',
            'other' => array(
                'publicName' => 'PUBLIC CLASS C',
                'protectedName' => 'PROTECTED CLASS C',
                'privateName' => 'PRIVATE CLASS C',
            ),
        );
        
        $array_c = $converterC->toArray(true);
        $resulC = array(
            'publicName' => 'PUBLIC CLASS C',
            'protectedName' => 'PROTECTED CLASS C',
            'privateName' => 'PRIVATE CLASS C',
        );
        
        $this->assertCount(4, $array_a);
        $this->assertTrue(($array_a == $resulA));
        
        $this->assertCount(4, $array_b);
        $this->assertTrue(($array_b == $resulB));
        
        $this->assertCount(3, $array_c);
        $this->assertTrue(($array_c == $resulC));
    }
    
    public function testToArrayWithoutPrivateWithoutSubObject()
    {
        $a = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\A();
        
        $converterA = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a, true);
        $converterB = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other, true);
        $converterC = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other->other, true);
        
        $array_a = $converterA->toArray();
        $resulA = array(
            'publicName' => 'PUBLIC CLASS A',
        );
        
        $array_b = $converterB->toArray();
        $resulB = array(
            'publicName' => 'PUBLIC CLASS B',
        );
        
        $array_c = $converterC->toArray();
        $resulC = array(
            'publicName' => 'PUBLIC CLASS C',
        );
        
        $this->assertCount(1, $array_a);
        $this->assertTrue(($array_a == $resulA));
        
        $this->assertCount(1, $array_b);
        $this->assertTrue(($array_b == $resulB));
        
        $this->assertCount(1, $array_c);
        $this->assertTrue(($array_c == $resulC));
    }
    
    public function testToArrayWithPrivateWithoutSubObject()
    {
        $a = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\A();
        
        $converterA = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a, true);
        $converterB = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other, true);
        $converterC = new \CHRIST\Common\Kernel\Helpers\ToArrayHelper($a->other->other, true);
        
        $array_a = $converterA->toArray(true);
        $resulA = array(
            'publicName' => 'PUBLIC CLASS A',
            'protectedName' => 'PROTECTED CLASS A',
            'privateName' => 'PRIVATE CLASS A',
        );
        
        $array_b = $converterB->toArray(true);
        $resulB = array(
            'publicName' => 'PUBLIC CLASS B',
            'protectedName' => 'PROTECTED CLASS B',
            'privateName' => 'PRIVATE CLASS B',
        );
        
        $array_c = $converterC->toArray(true);
        $resulC = array(
            'publicName' => 'PUBLIC CLASS C',
            'protectedName' => 'PROTECTED CLASS C',
            'privateName' => 'PRIVATE CLASS C',
        );
        
        $this->assertCount(3, $array_a);
        $this->assertTrue(($array_a == $resulA));
        
        $this->assertCount(3, $array_b);
        $this->assertTrue(($array_b == $resulB));
        
        $this->assertCount(3, $array_c);
        $this->assertTrue(($array_c == $resulC));
    }
}
