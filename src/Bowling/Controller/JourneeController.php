<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CHRIST\Modules\Bowling\Entity\Journee;
use CHRIST\Modules\Bowling\Form\SaisieJournee\JourneeForm;

/**
 * Description of ScoreController
 *
 * @author Christian Alcon
 */
class JourneeController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/score/listeJournee.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'dateJournee',
        ));
    }

    protected function listeReduitAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/score/listeJourneeReduit.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'dateJournee',
        ));
    }

    protected function ajoutAction(Request $request, Application $app) {
        $bowling = $this->getRepository('christian', 'CHRIST\Modules\Bowling\Entity\Bowling')
                ->findOneByNom("Barjouville");

        return $this->getForm(
                        new Journee($this->periodeCourante(), $bowling), 'src/Bowling/Resources/views/score/ajoutJournee.html.twig'
        );
    }

    protected function updateAction(Request $request, Application $app) {

        $record = $this->getCurrent();

        return $this->getForm(
                        $record, 'src/Bowling/Resources/views/score/ajoutJournee.html.twig'
        );
    }

    protected function controleDoublonAction(Request $request, Application $app) {
        $evenement = $this->request->get('evenement');
        $dateJournee = $this->request->get('dateJournee');

        if ($this->getRepository('christian', 'CHRIST\Modules\Bowling\Entity\Journee')
                        ->findIfExist(
                                $this->request->get('evenement'), $this->request->get('dateJournee'))) {
            return $this->returnJsonError('Les données évènement et dates vont être effacées');
        }

        try {
            return json_encode(
                    array(
                        'ETAT' => 'OK',
                    )
            );
        } catch (\Exception $ex) {
            return $this->returnJsonError($ex->getMessage());
        }
    }

    /**
     * Return error json message
     * 
     * @param $message string Error message
     * @return string json error
     */
    private function returnJsonError($message = 'Erreur inconnue') {
        return json_encode(
                array(
                    'ETAT' => 'NOK',
                    'DATA' => $message,
                )
        );
    }

    /**
     * Return form view
     */
    private function getForm(Journee $journee, $view) {
        $form = $this->app["form.factory"]
                ->create(new JourneeForm(), $journee, array('method' => $this->request->getMethod(),
                )
        );
        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $this->app['orm.ems']['christian']->persist($journee);

            foreach ($journee->getSeries() as $sr) {
                $sr->setJournee($journee);
                $this->app['orm.ems']['christian']->persist($sr);

                foreach ($sr->getScores() as $sc) {
                    $sc->setSerie($sr);
                    $this->app['orm.ems']['christian']->persist($sc);
                }
            }
// débug recordSet avant flush()

            _dump('la journee a enregistrer : ', 'dans journeeController');
            _dump('jnee a enregistrer', $journee, true);
            _dump('jnee (bowling)', $journee->getBowling(), true);
            _dump('jnee (evt)', $journee->getEvenement(), true);
            _dump('jnee (series)', $journee->getSeries(), true);
            foreach ($journee->getSeries() as $s) {
                _dump('jnee (' . count($s->getScores()) . ' scores)', $s->getScores(), true);
            }
// fin débug
            exit();

            $this->app['orm.ems']['christian']->flush();

            $this->app['session']->getFlashBag()->add(
                    'succès', 'Les informations ont bien été enregistrées'
            );

            if ($form->getData()->getNouvelleJnee()) {
                return $this->app->redirect(
                                $this->app['url_generator']->generate('bowling-journee-ajout'));
            }
            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-journee-consult'));
        }

        $moyenneSeries[0] = 0;
        foreach ($journee->getSeries() as $serie) {
            $moyenneSeries[$serie->getNoSerie()] = $serie->getMoyenne();
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'moyenne' => $journee->getMoyenne(),
                    'moyenneSeries' => $moyenneSeries,
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    private function getCurrentPeriode() {

        $em = $this->app['orm.ems']['christian']
                        ->getRepository("CHRIST\Modules\Bowling\Entity\Periode")->findActivePeriode();

        return $em;
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();
        // les suppressions des séries et des scores se font par l'attribut de l'entity
        // sur chaque relation avec l'anotation ORM :
        // cascade={"persist", "remove", "merge"}
        try {
            $this->app['orm.ems']['christian']->remove($record);
            $this->app['orm.ems']['christian']->flush();
        } catch (\Exception $ex) {
            return $this->returnJsonError($ex->getMessage());
        }

        return json_encode(
                array(
                    'ETAT' => 'OK',
                )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Journee', $this->request->get('id'));
    }

}
