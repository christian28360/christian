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
abstract class AbstractOrganigrammeDTCRepository extends CustomEntityRepository
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
     * Return children of node
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @param \DateTime $dateRef
     * @return array
     */
    public function getChildren(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme, \DateTime $dateRef = null)
    {
        $qb = $this->createQueryBuilder('pere');
    
        $qb = $qb->select('fils')
            ->from($this->namespace . 'Entity\OrganigrammeDTC', 'fils', 'fils.id')
            ->where('pere.id = :organigramme')
                ->andWhere($qb->expr()->gt('fils.borneGauche', 'pere.borneGauche'))
                ->andWhere($qb->expr()->lt('fils.borneDroite', 'pere.borneDroite'))
            ->orderBy('fils.code');
        
        $this->addDateConstraint($qb, $dateRef);
        
        $qb->setParameter('organigramme', $organigramme->getId());
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Return children of node first level only
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @param \DateTime $dateRef
     * @return array
     */
    public function getFirstChildren(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme, \DateTime $dateRef = null)
    {
        $qb = $this->createQueryBuilder('pere');
    
        $qb = $qb->select('fils')
            ->from($this->namespace . 'Entity\OrganigrammeDTC', 'fils', 'fils.id')
            ->where('pere.id = :organigramme')
                ->andWhere($qb->expr()->gt('fils.borneGauche', 'pere.borneGauche'))
                ->andWhere($qb->expr()->lt('fils.borneDroite', 'pere.borneDroite'))
                ->andWhere($qb->expr()->eq('fils.niveau', 'pere.niveau + 1'))
            ->orderBy('fils.code');
    
        $this->addDateConstraint($qb, $dateRef);
        
        $qb->setParameter('organigramme', $organigramme->getId());
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Return parents of node
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @return array
     */
    public function getParents(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme, \CHRIST\Common\Interfaces\IAgentDTC $agent = null)
    {
        $qb = $this->createQueryBuilder('pere');
    
        $qb = $qb->select('fils')
            ->from($this->namespace . 'Entity\OrganigrammeDTC', 'fils', 'fils.id')
            ->where('pere.id = :organigramme')
                ->andWhere($qb->expr()->lt('fils.borneGauche', 'pere.borneGauche'))
                ->andWhere($qb->expr()->gt('fils.borneDroite', 'pere.borneDroite'))
            ->orderBy('fils.borneGauche');
        
        $qb->setParameter('organigramme', $organigramme->getId());
        
        $orga = $qb->getQuery()->getResult();
        
        
        if (!is_null($agent)) {
            $app = \CHRIST\Common\Kernel\SingleApp::getAppliation();
            $allowed = $app['orm.ems']['optim']
                ->getRepository("CHRIST\Modules\Optim\Entity\AgentDTC")
                ->getOrganigrammeChildrenResponsible($agent);
            
            $diff       = array_diff_key($orga, $allowed);
            $intersect  = array_intersect_key($orga, $allowed);
            $temp       = array_slice($diff, -1, 1, true) + $intersect;
            
            
            array_walk($orga, function(&$item, $key) use ($organigramme, $allowed, $temp) {
                if (in_array($item->getId(), array_keys($temp)) && in_array($organigramme->getId(), array_keys($allowed))) {
                    $item->setLinked(true);
                }
            });
        }
        
        return $orga;
    }
    
    /**
     * Return first parent of node
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @return \CHRIST\Common\Interfaces\IOrganigrammeDTC
     */
    public function getParent(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme)
    {
        $qb = $this->createQueryBuilder('pere');
    
        $qb = $qb->select('fils')
            ->from($this->namespace . 'Entity\OrganigrammeDTC', 'fils', 'fils.id')
            ->where('pere.id = :organigramme')
                ->andWhere($qb->expr()->lt('fils.borneGauche', 'pere.borneGauche'))
                ->andWhere($qb->expr()->gt('fils.borneDroite', 'pere.borneDroite'))
                ->andWhere('pere.niveau = fils.niveau + 1');
        
        $qb->setParameter('organigramme', $organigramme->getId());
        
        return $qb->getQuery()->getSingleResult();
    }
    
    /**
     * Return list of agents responsible
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @param \DateTime $dateRef
     * @return \ArrayObject
     */
    public function getAgentResponsible(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme, \DateTime $dateRef = null)
    {        
        $sql = 'SELECT AGEN.*'
                . ' FROM T_AGENT_ORGANIGRAMME_AGOR AGOR'
                . ' INNER JOIN T_AGENT_AGEN AGEN ON AGEN.AGEN_CODE_RH = AGOR.AGOR_AGEN_ID'
                . ' WHERE AGOR_RESPONSABLE = 1'
                    . ' AND AGOR.AGOR_ORGA_ID = :orga';
        
        if (!is_null($dateRef)) {
            $sql .= ' AND AGOR_DATE_DEBUT <= :dateRef'
                  . ' AND (AGOR_DATE_FIN >= :dateRef OR AGOR_DATE_FIN IS NULL)';
        }
        
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata($this->namespace . 'Entity\AgentDTC', 'AGEN');
        $rsm->addIndexBy('AGEN', 'codeRH');
        
        $query = $this->getEntityManager()
                      ->createNativeQuery($sql, $rsm)
                      ->setParameter('orga', $organigramme->getId());
        
        if (!is_null($dateRef)) {
            $query->setParameter('dateRef', $dateRef);
        }
        
        return new \ArrayObject($query->getResult());
    }
    
    /**
     * Return list of agents
     * @param \CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme
     * @param \DateTime $dateRef
     * @param boolean $subService true if you want agent in sub service
     * @return \ArrayObject
     */
    public function getAgents(\CHRIST\Common\Interfaces\IOrganigrammeDTC $organigramme, \DateTime $dateRef = null, $subService = false)
    {
        if (is_null($dateRef)) {
            $dateRef = new \DateTime();
        }
        
        $sql =    ' SELECT DISTINCT'
                . '     AGEN.*'
                . '     , RESPONSABLE'
                . ' FROM'
                . '     FNC_LISTE_AGENT_SERVICE_DATE( :orga, :dateRef, :subService)'
                . ' INNER JOIN T_AGENT_AGEN AGEN ON AGEN.AGEN_CODE_RH = AGEN_ID'
                . ' ORDER BY RESPONSABLE DESC, AGEN_NOM, AGEN_PRENOM';
        
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata($this->namespace . 'Entity\AgentDTC', 'AGEN');
        $rsm->addIndexBy('AGEN', 'codeRH');
        
        $query = $this->getEntityManager()
                      ->createNativeQuery($sql, $rsm)
                      ->setParameter('orga', $organigramme->getId())
                      ->setParameter('dateRef', $dateRef)
                      ->setParameter('subService', $subService);
              
        return new \ArrayObject($query->getResult());
    }
    
    /**
     * Add date constraint of QueryBuilder object
     * @param \Doctrine\DBAL\Query\QueryBuilder $qb
     * @param \DateTime $dateRef
     */
    private function addDateConstraint(\Doctrine\ORM\QueryBuilder $qb, \DateTime $dateRef = null)
    {
        if (!is_null($dateRef)) {
            $qb->andWhere('fils.dateDebut <= :dateRef')
               ->andWhere('(fils.dateFin >= :dateRef OR fils.dateFin IS NULL)');
            
            $qb->setParameter('dateRef', $dateRef);
        }
    }
}