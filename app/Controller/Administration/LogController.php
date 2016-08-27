<?php

namespace CHRIST\Common\Controller\Administration;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Controller\AdministrationController;
/**
 * Default controller of application
 *
 * @author glr735
 */
class LogController extends AdministrationController
{
    protected function homeAction(Request $request, Application $app)
    {
        $iterator = new \RecursiveDirectoryIterator($this->getLogPath());
        
        return $app['twig']->render(
            'app/Resources/views/administration/log/index.html.twig',
            array(
                'files' => new \RecursiveIteratorIterator($iterator),
            )                
        );
    }
    
    protected function downloadAction(Request $request, Application $app)
    {
        
        $finfo = new \Symfony\Component\HttpFoundation\File\File($this->getLogPath() . $this->request->get('file'));
        
        if (!$finfo->isReadable()) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('The "' . $finfo->getFilename() . '" resource doesn\'t exist');
        }
        
        $response = new \Symfony\Component\HttpFoundation\Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $finfo->getFilename() . '";');
        $response->headers->set('Content-length', $finfo->getSize());

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($finfo->getRealPath()));
        
        return $response;
    }
    
    private function getLogPath()
    {
        return dirname($this->app['dtc.config.manager']->getSettings('config', 'monolog_monolog.logfile')) . '/';
    }
}
