<?php

namespace CHRIST\Common\Kernel\Modules\Import\Wrappers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use CHRIST\Common\Kernel\Modules\Import\Interfaces\IImportWrapper;
use CHRIST\Common\Kernel\Modules\Import\Wrappers\AbstractWrapper;

/**
 * Description of FileImport
 *
 * @author glr735
 */
class DatabaseWrapper extends AbstractWrapper implements IImportWrapper
{   
    /**
     * Constructor
     *
     * @param \CHRIST\Common\Kernel\Modules\Import\Parameter $parameters
     * @throws \Exception 
     */
    public function __construct(\CHRIST\Common\Kernel\Modules\Import\Parameter $parameters)
    {
        $this->parameters   = $parameters;
    }
    
    /**
     * @inherit
     */
    public function import()
    {
        return $this->finalizeImport('OK');
    }
}