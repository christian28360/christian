<?php

namespace CHRIST\Common\Kernel\Modules\Import\Wrappers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractWrapper
 *
 * @author glr735
 */
abstract class AbstractWrapper
{
    /**
     *
     * @var \CHRIST\Common\Kernel\Modules\Import\Parameter
     */
    protected $parameters = null;
    
    /**
     * Return next wrapper
     * @return \CHRIST\Common\Kernel\Modules\Import\Wrappers\wrapper|null
     */
    protected function getWrapper()
    {
        try {
            $wrappers = $this->parameters->getSettings('wrappers');
        } catch (\Exception $ex) {
            return null;
        }
        
        if (!empty($wrappers)) {
            
            $wrapper = array_shift($wrappers);
            $this->parameters->setSettings('wrappers', $wrappers);

            $wrapper = new $wrapper($this->parameters);
            
            return $wrapper;
        }
        
        return null;
    }
    
    /**
     * Return next callback
     * @return string|null
     */
    protected function getCallback()
    {
        try {
            $callbacks = $this->parameters->getSettings('callbacks');
        } catch (\Exception $ex) {
            return null;
        }
        
        if (!empty($callbacks)) {
            
            $callback = array_shift($callbacks);
            $this->parameters->setSettings('callbacks', $callbacks);

            return $callback;
        }
        
        return null;
    }
    
    /**
     * Execute stack wrapper.
     * On final wrapper stack of callback is executed.
     * @param mixed $result
     * @return mixed
     */
    protected function finalizeImport($result = null)
    {
        $wrapper = $this->getWrapper();
        
        if (!is_null($wrapper)) {
            $result = $wrapper->import();
        } else {
            
            $entityManager = $this->parameters->getSettings('entityManager');
            
            $manager = new $entityManager($this->parameters);

            while (($callback = $this->getCallback()) != null) {

                $result = $manager->$callback();
            }
        }
        
        return $result;
    }
}
