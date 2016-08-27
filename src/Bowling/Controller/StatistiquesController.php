<?php

namespace CHRIST\Modules\Bowling\Controller;

use CHRIST\Common\Kernel\GoogleAPI\GoogChart;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of StatistiquesController
 *
 * @author Christian ALCON
 */
class StatistiquesController extends GlobalController {

    protected function courbeTendanceAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/rapports/courbeTendance.html.twig', array(
                    'records' => $this->getCurent()->getCourbeTendance(),
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function courbeTendanceGraphiqueAction(Request $request, Application $app) {

        // Get data for graph
        $repo = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Bowling\Entity\Serie');

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
        $chart = new GoogChart();
        $chart->setChartAttrs(array(
            'type' => 'ligne',
            'title' => 'Courbe de tendance ' . $this->periodeCourante()->getSaison(),
            'data' => $data,
            'size' => array(750, 300),
            'color' => array('#99C754', '#54C7C5', '#999999',),
            'labelsXY' => true,
                //'fill' => array('#eeeeee', '#aaaaaa'),
        ));

        return $this->templateRender('src/Bowling/Resources/views/rapports/courbeTendanceGraphique.html.twig', array(
                    'periode' => $this->periodeCourante(),
                    'chart' => $chart,
        ));
    }

    protected function moyenneMensuelleAction(Request $request, Application $app) {

        $records = $this->getCurent()->getMoyenneMensuelle();

        $data = array();
        foreach ($records as $mois) {
            foreach ($mois as $values) {
                if ($values['mois']) {
                    $data['score'][$values['mois']] = $values['moyenneScore'];
                    $data['strike'][$values['mois']] = round($values['moyenneStrike'] * 15, 2);
                    $data['spare'][$values['mois']] = round($values['moyenneSpare'] * 15, 2);
                    $data['StrikeAndSpare'][$values['mois']] = round($values['moyenneStrikeAndSpare'] * 15, 2);
                }
            }
        }

        // tri reverse pour le graphique
        foreach ($data as $key => $value) {
            ksort($data[$key]);
        }
        $chart = new GoogChart();
        $chart->setChartAttrs(array(
            'type' => 'ligne',
            'data' => $data,
            'size' => array(750, 300),
            'color' => array('#99C754', '#54C7C5', '#999999',),
            'labelsXY' => true,
                //'fill' => array('#eeeeee', '#aaaaaa'),
        ));

        return $this->templateRender('src/Bowling/Resources/views/rapports/moyenneMensuelle.html.twig', array(
                    'records' => $records,
                    'chart' => $chart,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function recaptitulatifEvenementAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/rapports/recapParEvenement.html.twig', array(
                    'records' => $this->getCurent()->getRecapParEvenement(),
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function moyenneAnnuelleAction(Request $request, Application $app) {

        $records = array();
        foreach ($this->getAll() as $periode) {
            $records[$periode->getSaison()] = ($periode->getMoyenneAnnuelle());
        }
        // Cumuls globaux
        $records['Totaux']['nbJournees'] = array_sum(array_column($records, 'nbJournees'));
        $records['Totaux']['nbParties'] = array_sum(array_column($records, 'nbParties'));
        $records['Totaux']['score'] = array_sum(array_column($records, 'score'));
        $records['Totaux']['strike'] = array_sum(array_column($records, 'strike'));
        $records['Totaux']['spare'] = array_sum(array_column($records, 'spare'));
        $records['Totaux']['strikeAndSpare'] = array_sum(array_column($records, 'strikeAndSpare'));
        // moyennes
        $records['Totaux']['moyenneScore'] = $records['Totaux']['score'] / $records['Totaux']['nbParties'];
        $records['Totaux']['moyenneStrike'] = $records['Totaux']['strike'] / $records['Totaux']['nbParties'];
        $records['Totaux']['moyenneSpare'] = $records['Totaux']['spare'] / $records['Totaux']['nbParties'];
        $records['Totaux']['moyenneStrikeAndSpare'] = $records['Totaux']['strikeAndSpare'] / $records['Totaux']['nbParties'];
        $records['Totaux']['txStrikeAndSpare'] = $records['Totaux']['strikeAndSpare'] / $records['Totaux']['nbParties'] / 11 * 100;

        return $this->templateRender('src/Bowling/Resources/views/rapports/moyenneAnnuelle.html.twig', array(
                    'records' => $records,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function trancheAction(Request $request, Application $app) {

        $records = array();
        foreach ($this->getAll() as $periode) {
            $records[$periode->getSaison()] = ($periode->getVentilationParTranche());
        }

        $chart = new GoogChart();
        $chart->setChartAttrs(array(
            'type' => 'histogramme-v',
            'title' => 'Répartition par tranches ' . $this->periodeCourante()->getSaison(),
            'data' => $this->periodeCourante()->getVentilationParTranche(),
            'size' => array(350, 300),
            'color' => array('#99C754', '#54C7C5', '#999999',),
            'labelsXY' => true,
                //'labelsXY' => array('title' => 'Year', array('titleTextStyle' => array('color' => 'red'))),
        ));

        return $this->templateRender('src/Bowling/Resources/views/rapports/repartitionParTranche.html.twig', array(
                    'chart' => $chart,
                    'records' => $records,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function trancheGraphiqueAction(Request $request, Application $app) {

        $chart = new GoogChart();
        $chart->setChartAttrs(array(
            'type' => 'histogramme-v',
            'title' => 'Répartition par tranches ' . $this->periodeCourante()->getSaison(),
            'data' => $this->periodeCourante()->getVentilationParTranche(),
            'size' => array(750, 300),
            'color' => array('#99C754', '#54C7C5', '#999999',),
            'labelsXY' => true,
                //'labelsXY' => array('title' => 'Year', array('titleTextStyle' => array('color' => 'red'))),
        ));

        return $this->templateRender('src/Bowling/Resources/views/rapports/repartitionParTrancheGraphique.html.twig', array(
                    'chart' => $chart,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function extremesAction(Request $request, Application $app) {

        $records = array();
        foreach ($this->getAll() as $periode) {
            $records[$periode->getSaison()] = ($periode->getLesExtemes());
        }

        return $this->templateRender('src/Bowling/Resources/views/rapports/lesExtremes.html.twig', array(
                    'records' => $records,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function extremesGraphiqueAction(Request $request, Application $app) {

        $data = array();
        foreach ($this->getAll() as $periode) {
            $data['min'][$periode->getSaison()] = round($periode->getLesExtemesMoyenne('min'), 2);
            $data['max'][$periode->getSaison()] = round($periode->getLesExtemesMoyenne('max'), 2);
        }
        // retriage par saison croissante
        array_multisort($data['min'], $data['max']);

        $chart = new GoogChart();
        $chart->setChartAttrs(array(
            'type' => 'ligne',
            'title' => 'Les extrèmes',
            'data' => $data,
            'size' => array(750, 300),
            'color' => array('#99C754', '#54C7C5', '#999999',),
            'labelsXY' => true,
                //'fill' => array('#eeeeee', '#aaaaaa'),
        ));

        return $this->templateRender('src/Bowling/Resources/views/rapports/lesExtremesGraphique.html.twig', array(
                    'chart' => $chart,
                    'periode' => $this->periodeCourante(),
        ));
    }

    protected function templateRender($view = '', $parameters = array()) {
        return $this->app['twig']->render($view, $parameters);
    }

    protected function getAll() {

        $em = $this->app['orm.ems']['christian']
                        ->getRepository('CHRIST\Modules\Bowling\Entity\Periode')->findAllOrderedByDtDeb();

        return $em;
    }

    protected function getCurent() {

        $em = $this->app['orm.ems']['christian']
                ->getRepository('CHRIST\Modules\Bowling\Entity\Periode')
                ->findOnePeriode();

        return $em;
    }

}
