<?php

namespace CHRIST\Common\Repository\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\EntityRepository;

/**
 * Description of RocEtablissementRepository
 *
 * @author glr735
 */
abstract class AbstractRocEtablissementRepository extends EntityRepository
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
    
    /**
     * List of ETABLISSEMENT
     * @param string $constraints
     * @return array of \CHRIST\Common\Entity\Roc
     */
    public function getEtablissementBy($constraints = array())
    {
        $dql = '';
        if (is_array($constraints) && !empty($constraints)) {
            foreach ($constraints as $key => $value) {
                $dql .= ((empty($dql)) ? ' WHERE ' : ' AND ') . ' rocE.' . $key . ' = :' . $key;
            }
        }        
        
        $qb = $this->getEntityManager()->createQuery(
            'SELECT roc'
            . ' FROM ' . $this->namespace . 'Entity\Roc roc'
            . ' WHERE roc.cdSiteRoc IN ('
                . ' SELECT rocE.etab'
                . ' FROM ' . $this->namespace . 'Entity\RocEtablissement rocE'
                . $dql
                . ' GROUP BY rocE.etab'
            . ')'
            . ' ORDER BY roc.liSiteRoc'
        );      
                
        $qb->setParameters($constraints);
        
        return $qb->getResult();
    }

}
