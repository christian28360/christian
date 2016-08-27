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
class B
{
    public $publicName = 'PUBLIC CLASS B';
    
    protected $protectedName = 'PROTECTED CLASS B';
    
    private $privateName = 'PRIVATE CLASS B';
    
    public $other = null;

    public function __construct() 
    {
        $this->other = new \CHRIST\Common\Tests\Unit\Fixtures\Mocks\C();
    }
}
