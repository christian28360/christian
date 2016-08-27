<?php

namespace CHRIST\Common\Kernel\Modules\Import;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use CHRIST\Common\Kernel\Modules\Import\Parameter;

/**
 * Description of AbstractImport
 *
 * @author glr735
 */
abstract class AbstractImport
{
    /**
     * Silex Application
     * @var \Silex\Application
     */
    protected $app;
    
    /**
     * True if logger is activate, false otherwise
     * @var boolean
     */
    protected $activeLogger = false;

    /**
     * Silex Application
     * @var \CHRIST\Common\Kernel\Modules\Import\Logger
     */
    protected $logger;
    
    /**
     * @var \CHRIST\Common\Kernel\Modules\Import\Parameter
     */
    protected $parameters = null;
    
    /**
     * Name of entity manager
     * @var string
     */
    protected $nameEntityManager = null;

    /**
     * Load import data
     */
    abstract public function load();


    /**
     * Constructor
     * @param \Silex\Application $app
     * @param array $manifest
     * @param array $parameters
     */
    function __construct(\Silex\Application $app, $parameters)
    {
        $parameters['app'] = $this->app = $app;
        $this->parameters   = new Parameter(array_merge($parameters));
    }
    
    /**
     * Load a empty Logger
     * @param string $typeEntry
     */
    public function loadNewLogger($file = 'unknow')
    {
        $this->logger = new Logger($file);
        $this->parameters->setSettings('importLogger', $this->logger);
    }
    
    /**
     * Save Logger in database
     * @throws \Exception
     */
    public function saveLogger()
    {
        if ($this->activeLogger === false) {
            $this->app['logger']->info('Import logger is not actived');
            return ;
        }
        
        if (is_null($this->nameEntityManager)) {
            $this->app['logger']->warning('Import logger is not saved (entityManager is not initialized)');
            return ;
        }
        // renvoie 'christian', car mono base chez moi
        $em = $this->app['orm.ems'][$this->nameEntityManager];
        
        $em->persist($this->logger);
        $em->flush();
        $em->clear();
    }
}
