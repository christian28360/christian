<?php

namespace CHRIST\Common\Repository\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\EntityRepository;

use CHRIST\Common\Kernel\SingleApp;

/**
 * Description of SambaRepository
 *
 * @author glr735
 */
abstract class AbstractSambaRepository extends EntityRepository
{
    /**
     * Prefix of namespace
     * @var string
     */
    protected $namespace            = 'CHRIST\\Common\\';
    
    /**
     * Name of entity manager
     * @var string
     */
    protected $entityManagerName    = 'intranet';
    
    public function getObjectBy($constraints = array())
    {
        $object = $this->findOneBy($constraints);
        
        if (is_null($object)) {
            return new $this->_entityName();
        }
        
        $app = SingleApp::getAppliation();
        
        $roc = $app['orm.ems'][$this->entityManagerName]
            ->getRepository($this->namespace . "Entity\Roc")
            ->findOneBy(
                array(
                    'cdEntRegate' => $object->getRegateUse(),
                    'stRattach' => 1,
                )
            );
        
        
        $object->setRocIdUse($roc->getCdSiteRoc());
        
        return $object;
    }

}
