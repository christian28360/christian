<?php

namespace CHRIST\Modules\Vocabulaire\ImportManager;

use CHRIST\Modules\Vocabulaire\Entity\Mot;

/**
 * Description of MotManager
 *
 * Christian Alcon
 */
class MotManager {//extends GlobalManager {

    /**
     * Entity
     * @var \CHRIST\Modules\Vocabulaire\Entity\Mot
     */
    protected $entity = null;
    
    /**
     * Constructor 
     * Init entity
     * @param array $data
     * @param \CHRIST\Common\Kernel\Modules\Import\Parameter $data
     */
    function __construct(\CHRIST\Common\Kernel\Modules\Import\Parameter $parameters, $data = array()) {

        $this->parameters = $parameters;
        $this->entity = new Mot();
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
