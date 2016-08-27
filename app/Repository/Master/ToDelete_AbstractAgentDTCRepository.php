<?php

namespace CHRIST\Common\Repository\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Query\Expr\Join;

use CHRIST\Common\Kernel\Extensions\Doctrine\SqlSrv\ORM\CustomEntityRepository;
/**
 * Description of RocRepository
 *
 * @author glr735
 */
abstract class AbstractAgentDTCRepository extends CustomEntityRepository
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
    protected $entityManagerName    = 'optim';
    
    
    /**
     * Return list Organigramme of $agent is responsible
     * @param \CHRIST\Common\Interfaces\IAgentDTC $agent
     * @return array
     */
    public function getOrganigrammeResponsible(\CHRIST\Common\Interfaces\IAgentDTC $agent)
    {   
        return $this->getOrganigrammeResponsibleQuery($agent)->getQuery()->getResult();
    }
    
    /**
     * Return query for getting Organigramme of $agent is responsible
     * @param \CHRIST\Common\Interfaces\IAgentDTC $agent
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getOrganigrammeResponsibleQuery(\CHRIST\Common\Interfaces\IAgentDTC $agent)
    {
        return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('orga')
                ->from($this->namespace . 'Entity\OrganigrammeDTC', 'orga', 'orga.id')
                ->innerJoin($this->namespace . 'Entity\AffectationDTC', 'agor', 'WITH', 'agor.service = orga AND agor.responsable = 1')
                ->where('agor.agent = :agent')
                    ->setParameter('agent', $agent);
    }


    /**
     * Return all Organigramme object where $agent is responsible
     * @param \CHRIST\Common\Interfaces\IAgentDTC $agent
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function getOrganigrammeChildrenResponsible(\CHRIST\Common\Interfaces\IAgentDTC $agent)
    {
        $res = $this->getOrganigrammeChildrenResponsibleQuery($agent)->getQuery()->getResult();
        
        // Index array by service id
        $keys = array();
        foreach ($res as $data) {
            $keys[] = $data->getId();
        }
        
        return array_combine($keys, $res);
    }    
    
    /**
     * Return query for getting all Organigramme object where $agent is responsible
     * @param \CHRIST\Common\Interfaces\IAgentDTC $agent
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getOrganigrammeChildrenResponsibleQuery(\CHRIST\Common\Interfaces\IAgentDTC $agent)
    {
        $qb = $this->getEntityManager()
                ->createQueryBuilder();
        
        $qb = $qb->select('orgaFils')
                ->distinct()
                ->from($this->namespace . 'Entity\OrganigrammeDTC', 'orgaPere')
                ->innerJoin(
                    $this->namespace . 'Entity\OrganigrammeDTC', 
                    'orgaFils', 
                    'WITH', 
                    'orgaFils.id IN ('
                        . ' SELECT DISTINCT sTree'
                        . ' FROM ' . $this->namespace . 'Entity\OrganigrammeDTC sTree'
                        . ' WHERE sTree.borneGauche >= orgaPere.borneGauche'
                        . '     AND sTree.borneDroite <= orgaPere.borneDroite'
                        . ')'
                )
                ->where($qb->expr()->in('orgaPere', $this->getOrganigrammeResponsibleQuery($agent)->getDQL()))
                    ->setParameter('agent', $agent);
        
        return $qb;
    }
}
