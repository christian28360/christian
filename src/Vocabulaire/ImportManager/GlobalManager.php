<?php

namespace CHRIST\Modules\Vocabulaire\ImportManager;

/**
 * Description of GlobalManager
 *    Fonction globale à exécuter après import de certains fichiers
 * @author ezs824
 */
abstract class GlobalManager extends \CHRIST\Common\Kernel\Modules\Import\Manager\ImportManager
{
    /**
     * Import parameters
     * @var \CHRIST\Common\Kernel\Modules\Import\Parameter
     */
    protected $parameters = null;
   
    public function majTotalSimulations()
    {

        return 'OK';
        
    }     
}
