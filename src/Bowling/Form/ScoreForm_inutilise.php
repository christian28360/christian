<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of ScoreForm
 *
 * @author Christiazn ALCON
 */
class ScoreForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        /*  Générer la liste des années */
        $annees = array();
        for ($i = (int) date('Y'); $i > (int) date('Y') - 15; $i--) {
            $annees[$i] = $i;
        }

        function __construct(\Silex\Application $app, \Doctrine\ORM\EntityRepository $em) {
            $this->app = $app;
            $this->em = $em;
        }

        /*
          var_dump($this->app['orm.ems']['christian']
          ->getRepository('CHRIST\Modules\Bowling\Entity\Periode')
          ->findActivePeriode());
         */
        $r = (function(\Doctrine\ORM\EntityRepository $er) {
            return $er->createQueryBuilder('o')
                            ->orderBy('o.libelle', 'ASC');
        });

        $builder->add('evenement', 'entity', array(
            'label' => 'evenement :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Evenement',
            'required' => true,
            'empty_value' => 'Choisissez un evenement',
            'property' => 'getEvt',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.libelle', 'ASC');
            },
//            'constraints' => array(new Assert\NotNull()),
        ));

        $builder->add('datePartie', 'date', array(
            'label' => 'Date de la partie :',
            'required' => true,
          //  'format' => 'dd/MM/yyyy',
            'years' => $annees, // par défaut l'annéee = -5a à +5a
        ));

        $builder->add('serie', 'text', array(
            'label' => 'N° de série de la journée :',
            'required' => true,
            'attr' => array('size' => 1, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 1, 'max' => 15,
                    'minMessage' => 'Vous avez saisi {{ value }}, bornes entre 1 et 15 !',
                    'maxMessage' => 'Vous avez saisi {{ value }}, bornes entre 1 et 15 !'))),
        ));

        $builder->add('score', 'text', array(
            'label' => 'Score de la partie :',
            'required' => true,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 1, 'max' => 300,
                    'minMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 300 !',
                    'maxMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 300 !'))),
        ));

        $builder->add('strike', 'text', array(
            'label' => 'Nombre de strikes :',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 12,
                    'minMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 12 !',
                    'maxMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 12 !'))),
        ));

        $builder->add('spare', 'text', array(
            'label' => 'Nombre de spares :',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 11,
                    'minMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 11 !',
                    'maxMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 11 !'))),
        ));

        $builder->add('gagnee', 'checkbox', array(
            'label' => 'Partie gagnée ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('PasCalculMoyenne', 'checkbox', array(
            'label' => 'Exclure de la moyenne ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('split', 'text', array(
            'label' => 'Nombre de splits :',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 11,
                    'minMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 11 !',
                    'maxMessage' => 'Vous avez saisi {{ value }}, bornes entre 0 et 11 !'))),
        ));

        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary pull-left'),
        ));

        $builder->add('annuler', 'reset', array(
            'attr' => array(
                'class' => 'btn pull-left'),
        ));

        $builder->add('quitter', 'button', array(
            'attr' => array(
                'class' => 'btn btn-danger pull-left'),
        ));
    }

    public function getName() {
        return 'ScoreForm';
    }

}
