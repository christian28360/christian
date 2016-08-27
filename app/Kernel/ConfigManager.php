<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigManager
 *
 * @author glr735
 */
class ConfigManager
{
    /**
     * Global configuration
     * @var array
     */
    private $configuration = array();
    
    
    /**
     * Constructor
     * 
     * @param array $files
     */
    function __construct($config = array())
    {
        $this->configuration = $config;
    }
    
    /**
     * Find property (config, routes, ...) in $app
     * 
     * @param 		string $context (config, route, ...)
     * @param 		string $properties
     * @example 	Syntax: 'niv1_niv2_niv3_finalNiv'
     * @uses 		recursive function _getSettings
     * 
     * @return 		mixed
     */
    public function getSettings($context = '', $properties = '')
    {
        if (empty($context)) {
            
            throw new \Exception('The context should not be empty');
        }
        
        if (empty($properties)) {
            
            return $this->configuration[$context];
        }
        
        return $this->_getSettings($this->configuration[$context], explode('_', $properties));
    }

    /**
     * Browse configuration collection
     *
     * @access  private
     * @param   array $config
     * @param   array $properties
     * 
     * @return 	mixed
     */
    private function _getSettings($config = array(), $properties = array())
    {
        $property = array_shift($properties);
        
        if (array_key_exists($property, $config)) {
			
            if (count($properties) == 0) {

                return $config[$property];
            } else {

                return $this->_getSettings($config[$property], $properties);
            }
        }
        
        throw new \Exception('Property "' . $property . '" not found');
    }
    
    /**
     * Add element configuration
     * 
     * @param string $context
     * @param array $configuration
     */
    public function addConfiguration($context, $configuration, $overwrite = true)
    {
        $this->configuration[$context] = $configuration;
        
        if ($overwrite === true) {
            $this->overwriteConfiguration($context);
        }
    }
    
    /**
     * Overwrite a context
     * @param type $context
     */
    public function  overwriteConfiguration($context)
    {
        $that = $this;
        array_walk_recursive(
            $this->configuration[$context],
            function(&$item) use ($context, $that) {
            
                if (preg_match('#%(.*)%#', $item, $matches)) {
                    
                    $replace = $that->getSettings($context, $matches[1]);
                    
                    if ($item == $matches[0]) {
                        $item = $replace;
                    } else {
                        $item = str_replace($matches[0], $replace, $item);
                    }                    
                }
            }
        );
    }
}
