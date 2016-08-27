<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Find modules application
 *
 * @author glr735
 */
class ModuleFinder
{
    /**
     * List of modules
     * @var \DirectoryIterator
     */
    private $modules;
    
    /**
     * Environment
     * @var string
     */
    private $environment;
    
    
    
    
    /**
     * Constructor
     */
    function __construct($environment)
    {
        $this->modules = new \DirectoryIterator(__DIR__ . '/../../src');
        $this->environment = $environment;
    }
    
    /**
     * Get modules
     * @return \DirectoryIterator
     */
    public function getModules()
    {
        return $this->modules;
    }
    
    /**
     * Return list path config file
     * @param array $type
     * @return string
     */
    public function getConfigModule($type = '', $moduleName = '')
    {
        $files = array();
        
        foreach ($this->modules as $module) {
            
            if (!$module->isDot()) {
                
                if (empty($moduleName) || $moduleName == $module->getFilename()) {

                    $path = __DIR__ . '/../../src/' . $module->getFilename() . '/Resources/config/';

                    if (file_exists($file = $path . $type . '_' . $this->environment . '.yml')
                            || file_exists($file = $path . $type . '.yml')) {

                        $files[] = $file;
                    }
                }
            }
        }
        
        return $files;
    }
}
