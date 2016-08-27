<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Modules\Bowling\Entity\Periode;
use CHRIST\Modules\Bowling\Form\PeriodeForm;

/**
 * Description of PeriodeController
 *
 * @author Christian ALCON
 */
class PeriodeController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/periode/liste.html.twig', array(
                    'repository' => 'periode',
                    'tri' => 'dtDeb',
        ));
    }

    protected function ajoutAction(Request $request, Application $app) {

        return $this->getForm(new Periode(), 'src/Bowling/Resources/views/periode/ajout.html.twig');
    }

    protected function activateAction(Request $request, Application $app) {

        // on désactive la période courante
        $this->periodeCourante()->setIsActive(false);
        // on active la période sélectionnée
        $this->getCurrent()->setIsActive(true);
        // on écrit le tout dans la BdD
        $this->app['orm.ems']['christian']->flush();

        return $this->reload();
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        // Période à supprimer est active ?
        if ($record->getIsActive()) {
            $this->app['session']->getFlashBag()
                    ->add('danger', 'On ne peut pas supprimer la saison courante');
        } elseif ($record->getJournees()->count() == 0) {
            // aucune => suppression possible
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add('info', 'la saison "'
                    . $record->getDtDeb()->format('d/m/Y')
                    . ' => '
                    . $record->getDtFin()->format('d/m/Y')
                    . '" a bien été supprimée');
        } else {
            // oui : liste des 10 premières en flasbag
            $nbRecords = $record->getJournees()->count();
            $a = $nbRecords > 1 ? 'des' : 'une';
            $s = $nbRecords > 1 ? 's' : '';
            $message = 'Supression impossible, il existe ' . $nbRecords . ' journée' . $s . ' associée' . $s . ' à cette saison : ';

            $data = '';
            $i = 0;
            $limite = 5;        // Nbre maxi de journées à afficher dans le flashBag
            foreach ($record->getJournees() as $r) {
                $data .= $i > 0 ? ', ' : '';
                if ($i++ >= $limite) {
                    break;
                }
                $data .= '"' . $r->getDateJournee()->format('d/m/Y') . '"';
            }
            $data .= $nbRecords > $limite ? ' ...' : '';

            $this->app['session']->getFlashBag()->add('danger', $message . $data); //, $code, NULL);
        }

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm(Periode $entity, $view) {

        $form = $this->app["form.factory"]->create(
                new PeriodeForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            // si on a coché la nouvelle période comme active, on désactivee l'ancienne
            if ($form->getData()->getIsActive()) {
                $this->periodeCourante()->setIsActive(false);
            }
            // puis on écrit les nouvelles données
            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-periode')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Periode', $this->request->get('id'));
    }

}
