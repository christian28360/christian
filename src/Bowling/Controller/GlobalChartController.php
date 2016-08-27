<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Default controller of application
 *
 * @author ALCON Christian
 */
class GlobalChartController extends GlobalController {

    protected function getDataChart($view = '', $parameters = array()) {

        $repo = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Bowling\Entity\\' . $parameters['repository']);

        $records = $repo->findData($this->periodeCourante(), array('data' => array('dateSerie')));

        $data = array();
        $nbParties = 0;
        $totScores = 0;

        foreach ($records as $record) {
            if ($record->getMoyenne() > 0) {
                $nbParties += $record->getNbScores();
                $totScores += $record->getTotScores();
                
                $data['Moyenne'][$record->getDateSerie()->format('y/m/d')] = round($record->getMoyenne(), 2);
                $data['Tendance'][$record->getDateSerie()->format('y/m/d')] = round($totScores / $nbParties, 2);
                $data['Objectif'][$record->getDateSerie()->format('y/m/d')] = rand(165, 165);
//                $data['Moyenne'][$record->getDateSerie()->format('ym') . $record->getNoSerie()] = $record->getMoyenne();
//                $data['Tendance'][$record->getDateSerie()->format('ym') . $record->getNoSerie()] = $totScores / $nbParties;
//                $data['Objectif'][$record->getDateSerie()->format('ym') . $record->getNoSerie()] = rand(165, 165);
            }
        }
/*            
        var_dump(count($data['Moyenne']));
        var_dump(count($data['Tendance']));
        var_dump(count($data['Objectif']));
        var_dump($data);
  */
        return $data;
    }

    protected function findChartData($periodeCourante, $parameters = array()) {

        $record = $parameters['repository']->findData($periodeCourante, $parameters['data']);

        return $record;
    }

}
