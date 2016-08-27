<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Tests\Unit\Fixtures\Mocks;

/**
 * @author glr735
 */
class A
{
    public $publicName = 'PUBLIC CLASS A';
    
    protected $protectedName = 'PROTECTED CLASS A';
    
    private $privateName = 'PRIVATE CLASS A';
    
    public $other = null;

    public function __construct() 
    {
        $this->other = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\B();
    }
    
    public function getPublicName()
    {
        return $this->publicName;
    }

    public function getProtectedName()
    {
        return $this->protectedName;
    }

    public function getPrivateName()
    {
        return $this->privateName;
    }

    public function getOther()
    {
        return $this->other;
    }

    public function setPublicName($publicName)
    {
        $this->publicName = $publicName;
    }

    public function setProtectedName($protectedName)
    {
        $this->protectedName = $protectedName;
    }

    public function setPrivateName($privateName)
    {
        $this->privateName = $privateName;
    }

    public function setOther($other)
    {
        $this->other = $other;
    }


}
