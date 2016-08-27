<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;

/**
 * Description SingleApp
 *
 * @author glr735
 */
class SingleApp
{
    /**
     * @var \CHRIST\Common\Kernel\SingleApp
     */
    private static $instance;
    
    /**
     * @var \Silex\Application
     */
    private $app = null;
    
    
    /**
     * Constructor
     */
    private function __construct (Application $app)
    {        
        $this->app = $app;
    }
 
    /**
     * Disable clone action
     */
    private function __clone () {}
    
    /**
     * Return silex application
     * @return \Silex\Application
     * @throws \Exception
     */
    public static function getAppliation()
    {
        if (is_null(self::$instance) && is_null(self::$instance->app)) {
            throw new \Exception(__CLASS__ . ' Application is not initialized');
        }
        
        return self::$instance->app;
    }
 
    /**
     * Initialize instance
     * @throws \Exception
     */
    public static function init(Application $app = null)
    {
        if (!(self::$instance instanceof self)) {
            
            self::$instance = new self($app);
        }
    }
}
