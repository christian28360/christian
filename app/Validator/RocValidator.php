<?php

namespace CHRIST\Common\Validator;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Validator\ExecutionContextInterface;
use CHRIST\Common\Kernel\SingleApp;

/**
 * Description of RocValidator
 *
 * @author glr735
 */
class RocValidator
{
    public static function validateRocId($rocId, ExecutionContextInterface $context)
    {
        $app = SingleApp::getAppliation();
        
        $roc = $app['orm.ems']['intranet']
            ->getRepository("CHRIST\Common\Entity\Roc")
            ->findOneByCdSiteRoc($rocId);
        
        if (is_null($roc)) {
            $context->addViolation(
                'Identifiant ROC "' . $rocId . '" est inconnu'
            );
        }
    }
    
    public static function validateRegate($regate, ExecutionContextInterface $context)
    {
        $app = SingleApp::getAppliation();
        
        $roc = $app['orm.ems']['intranet']
            ->getRepository("CHRIST\Common\Entity\Roc")
            ->getRocByRegate($regate);
        
        if (is_null($roc)) {
            $context->addViolation(
                'Le code régate "' . $regate . '" est inconnu'
            );
        }
    }
}
