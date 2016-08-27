<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Yaml\Yaml;

/**
 * Load config files (globals and by modules)
 * Load routing files (globals and by modules)
 *
 * @author glr735
 */
class ConfigLoader
{
    /**
     * Application
     * @var Silex\Application
     */
    private $app;
    
    /**
     * Configuration
     * @var type 
     */
    private $config = array();
    
    /**
     * PHP string parser
     * @var type 
     */
    private $parserPhp;
    
    private $cacheDir = '';    
    
    /**
     * Constructor
     * 
     * List files, key is group, value is path
     * @param array $files
     */
    function __construct(\Silex\Application $app)
    {
        $this->app          = $app;
        $this->parserPhp    = new PhpStringParser();
        $this->cacheDir     = __DIR__ . '/../../cache/config/';
    }

    public function load($context = '')
    {
        $this->config = array();
        
        if($this->app['dtc.has.apc'] && ($collection = apc_fetch('PortailSI_config')) !== false && $collection != null) {
            
            return $this->registerConfiguration($context, $collection, false);
        }
        
        // Load global configuration
        if (($file = $this->_findGlobalFile(__DIR__ . '/../Resources/config/', $context)) === false) {
            
            throw new \Exception('Configuration file named "' . $context . '_' . $this->app['environment'] . '.yml" or "' . $context . '" not found');
        }
        
        $files[] = $file;
        
        // Merge with modules configurations
        $files = array_merge($files, $this->app['dtc.modules']->getConfigModule($context, $this->app['dtc.current.module']));
        
        
        foreach ($files as $file) {
            
            $this->loadFile($file);
        }
        
        $this->registerConfiguration($context, $this->config);
        
        if ($this->app['dtc.has.apc']) {
            apc_store('PortailSI_config', $this->app['dtc.config.manager']->getSettings($context));
        }
    }
    
    private function registerConfiguration($context, $config, $overwrite = true)
    {
        $this->app['dtc.config.manager']->addConfiguration($context, $config, $overwrite);
    }
    
    private function loadFile($file = '')
    {
        $partConfig = Yaml::parse($this->parserPhp->parse(file_get_contents($file)));
            
        if (is_array($partConfig)) {

            $this->_addImports($partConfig, $file);

            if (isset($this->config) && is_array($this->config)) {

                $this->config = array_replace_recursive($this->config, $partConfig);
            } else {

                $this->config = $partConfig;
            }
        }
    }
    
    private function _addImports(&$config, $file)
    {
        foreach ($config as $key => $value) {
            
            if ($key == 'imports') {
                
                foreach ($value as $resource) {
                    
                    $base_dir = str_replace(basename($file), '', $file);
                    $this->loadFile($base_dir . $resource['resource']);
                }
                
                unset($config['imports']);
            }
        }
    }
    
    /**
     * Return path of file configuration
     * @param string $path
     * @return string|boolean
     */
    private function _findGlobalFile($path = '', $type = '')
    {
        if (!file_exists($file = $path . $type . '_' . $this->app['environment'] . '.yml')) {
            
           if (!file_exists($file = $path . $type . '.yml')) {
               
               return false;
            }
        }
        
        return $file;
    }
}
