<?php

namespace CHRIST\Modules\Bowling\ImportManager;

use CHRIST\Modules\Bowling\Entity\Journee;

/**
 * Description of JourneeManager
 *
 * Christian Alcon
 */
class JourneeManager {//extends GlobalManager {

    /**
     * Entity
     * @var \CHRIST\Modules\Bowling\Entity\Journeet
     */
    protected $entity = null;
    
    /**
     * Constructor 
     * Init entity
     * @param array $data
     * @param \DTC\Common\Kernel\Modules\Import\Parameter $parameters
     */
    function __construct(\CHRIST\Common\Kernel\Modules\Import\Parameter $parameters, $data = array()) {
        
        $this->parameters = $parameters;
        $this->entity = new Journee($this->parameters->getSettings('saison'));
        $app = $this->parameters->getSettings('app');
        /*
        parent::__construct(
                $app, $app['orm.ems']['christian']
        );
*/
        }

    /**
     * @inherit
     */
    public function getEntity() {
        return $this->entity;
    }

}
