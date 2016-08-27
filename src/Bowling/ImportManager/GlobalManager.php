<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Modules\Bowling\ImportManager;

/**
 * Description of GlobalManager
 *    Fonction globale à exécuter après import de certains fichiers
 * @author ezs824
 */
abstract class GlobalManager extends \CHRIST\Common\Kernel\Modules\Import\Manager\ImportManager
{
    /**
     * Import parameters
     * @var \DTC\Common\Kernel\Modules\Import\Parameter
     */
    protected $parameters = null;
   
    public function majTotalSimulations()
    {
       $entity = $this->app['orm.ems']['simulateurBudget']
                    ->getRepository("DTC\Modules\SimulateurBudget\Entity\ParametresApplication")
                    ->getParameters();

        $this->app['orm.ems']['simulateurBudget']
         ->getRepository("DTC\Modules\SimulateurBudget\Entity\Simulation")
         ->updateTotauxSimulations($entity->getAnneeSimulation());
        
        return 'OK';
        
    }     
}
