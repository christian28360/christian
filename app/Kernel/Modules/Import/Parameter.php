<?php

namespace CHRIST\Common\Kernel\Modules\Import;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parameter
 *
 * @author glr735
 */
class Parameter {

    /**
     * Parameter for import file
     * @var array
     */
    private $parameters = array();

    /**
     * Constructor
     * @param array $parameters
     */
    function __construct($parameters = array()) {
        $this->parameters = $parameters;
    }

    /**
     * Find parameters
     * 
     * @param 		string $parameter
     * @example 	Syntax: 'niv1_niv2_niv3_finalNiv'
     * @uses 		recursive function _getSettings
     * 
     * @return 		mixed
     */
    public function getSettings($properties = '') {

        if (empty($properties)) {

            return $this->parameters;
        }

        return $this->_getSettings($this->parameters, explode('_', $properties));
    }

    /**
     * Overwrite parameters
     * 
     * @param string $properties 	
     * @param mixed $value
     * 
     * @example 	Syntax: 'niv1_niv2_niv3_finalNiv'
     * @uses 		recursive function _setSettings
     * 
     * @return 		mixed
     */
    public function setSettings($properties = '', $value = null) {
        if (!empty($properties)) {
            $this->_setSettings($this->parameters, explode('_', $properties), $value);

            return true;
        }

        return false;
    }

    /**
     * Browse parameter collection
     *
     * @access  private
     * @param   array $parameters
     * @param   array $properties
     * 
     * @return 	mixed
     */
    private function _getSettings($parameters = array(), $properties = array()) {
        $property = array_shift($properties);

        if (array_key_exists($property, $parameters)) {

            if (count($properties) == 0) {

                return $parameters[$property];
            } else {

                return $this->_getSettings($parameters[$property], $properties);
            }
        }

        throw new \Exception(__CLASS__ . ' => Property "' . $property . '" non trouvée');
    }

    /**
     * Overwrite parameter
     * @param array $config
     * @param array $properties
     * @param mixed $value
     * @return mixed
     */
    private function _setSettings(&$config = array(), $properties = array(), $value = null) {
        $property = array_shift($properties);

        if (array_key_exists($property, $config) && count($properties) > 0) {

            return $this->_setSettings($config[$property], $properties, $value);
        } else {
            return $config[$property] = $value;
        }
    }

}
