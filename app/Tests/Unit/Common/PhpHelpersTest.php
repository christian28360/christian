<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Tests\Unit\Common;

use CHRIST\Common\Kernel\Helpers\PhpHelpers;

/**
 * Description of PhpHelpersTest
 *
 * @author glr735
 */
class PhpHelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testReIndexArray()
    {
        $source = array(
            'a1' => 'foo',
            'b1' => 'bar',
            'c3' => 'temp',
        );
        
        $controle = array(
            0 => 'foo',
            1 => 'bar',
            2 => 'temp',
        );
        
        $this->assertEquals(PhpHelpers::reIndexArray($source), $controle);
    }
    
    public function testStartsWith()
    {
        $this->assertTrue(PhpHelpers::startsWith('Direction Technique du Courrier', 'Direction'));
        $this->assertFalse(PhpHelpers::startsWith('Direction Technique du Courrier', 'Directions'));
        
        // Test with case
        $this->assertFalse(PhpHelpers::startsWith('Direction Technique du Courrier', 'direction'));
    }
    
    public function testEndsWith()
    {
        $this->assertTrue(PhpHelpers::endsWith('DTC Chartres', 'Chartres'));
        $this->assertFalse(PhpHelpers::startsWith('DTC Nantes', 'Chartres'));
        
        // Test with case
        $this->assertFalse(PhpHelpers::startsWith('DTC CHARTRES', 'Chartres'));
    }
    
    public function testArrayToString()
    {
        $this->assertEquals(
            PhpHelpers::arrayToString(array('A1' => 'Test1', 'A2' => 'Test2'))
            , '{A1=Test1, A2=Test2}'
        );
    }

    public function testDeleteNewLine()
    {
        $this->assertEquals(
            PhpHelpers::deleteNewLine("Test\nMulti\nLigne")
            , 'TestMultiLigne'
        );
        
        $this->assertEquals(
            PhpHelpers::deleteNewLine("  Test\nMulti\nLigne\tTab\tEspace   ")
            , 'TestMultiLigneTabEspace'
        );
    }
    
    public function testDeleteDir()
    {
        $fixturePath    = dirname(__DIR__) . '/Fixtures/testDeleteDir';
        $filePath       = $fixturePath . '/temp/';
        $file           = $filePath . 'testDeleteDir.txt';
        
        // Create test tree
        mkdir($filePath, 0777, true);
        // Create test file in tree
        file_put_contents($file, 'TEST: testDeleteDir');
        
        $this->assertTrue(file_exists($fixturePath));
        $this->assertTrue(file_exists($filePath));
        PhpHelpers::deleteDir($fixturePath);
        $this->assertFalse(file_exists($fixturePath));
        $this->assertFalse(file_exists($filePath));
    }
    
    public function testAutoType()
    {
        $this->assertEquals(gettype(PhpHelpers::autoType(3525)), 'integer');
        $this->assertEquals(gettype(PhpHelpers::autoType('1563')), 'integer');
        
        $this->assertEquals(gettype(PhpHelpers::autoType(15.23)), 'double');
        $this->assertEquals(gettype(PhpHelpers::autoType('16.56')), 'double');
        $this->assertEquals(gettype(PhpHelpers::autoType('16,56')), 'double');
        
        $this->assertEquals(gettype(PhpHelpers::autoType('Portail SI')), 'string');
        $this->assertEquals(gettype(PhpHelpers::autoType('DTC, La Poste')), 'string');
        $this->assertEquals(gettype(PhpHelpers::autoType('35, 06')), 'string');
        $this->assertEquals(gettype(PhpHelpers::autoType('35. 06')), 'string');
        
        $this->assertEquals(gettype(PhpHelpers::autoType(true)), 'boolean');
        $this->assertEquals(gettype(PhpHelpers::autoType('false')), 'boolean');
        
        $this->assertEquals(gettype(PhpHelpers::autoType(array('SI', 'INOER'))), 'array');
    }
    
    public function testDateExcel()
    {
        $date = new \DateTime('2014-03-04');
        $excelTimestamp = 25569 + ($date->getTimestamp() / 86400);
        $this->assertEquals(
            PhpHelpers::dateExcel($excelTimestamp)
            , $date
        );
    }
    
    public function testCastObject()
    {
        $classC = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\C();
        
        $classCtl = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\A();
        $classCtl->setPublicName('PUBLIC CLASS C');
        $classCtl->setProtectedName('PROTECTED CLASS C');
        $classCtl->setPrivateName('PRIVATE CLASS C');
        
        $this->assertEquals(
            PhpHelpers::castObject('\CHRIST\Common\Tests\Unit\Fixtures\Mocks\A', $classC)
            , $classCtl
        );
    }
}
