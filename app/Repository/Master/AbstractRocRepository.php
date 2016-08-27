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
 * Description of RocRepository
 *
 * @author glr735
 */
abstract class AbstractRocRepository extends EntityRepository
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
     * List of DOTC
     * @return array
     */
    public function getDotc()
    {
        return $this->findBy(
                        array(
                    'abTypeSite' => 'DOTC'
                        ), array(
                    'liSiteRoc' => 'ASC'
                        )
        );
    }
    
    /**
     * Return an Roc object by regate
     * @param string $regate
     * @return Entity\Roc
     */
    public function getRocByRegate($regate = '')
    {
        $app = SingleApp::getAppliation();
        
        $roc = $app['orm.ems'][$this->entityManagerName]
            ->getRepository($this->namespace . 'Entity\Roc')
            ->findOneBy(
                array(
                    'cdEntRegate'   => str_pad($regate, 6, '0', STR_PAD_LEFT),
                    'stRattach'     => 1,
                )
        );
        
        if (is_null($roc)) {
            $roc = $app['orm.ems'][$this->entityManagerName]
                ->getRepository($this->namespace . 'Entity\Roc')
                ->findOneBy(
                    array(
                        'cdEntRegate'   => str_pad($regate, 6, '0', STR_PAD_LEFT),
                        'stRattach'     => 2,
                    )
            );
        }
        
        return $roc;
    }

}
