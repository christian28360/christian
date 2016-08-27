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
class JourneeForm extends AbstractType {

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
        ));

        $builder->add('dateJournee', 'date', array(
            'label' => 'Date de la journée :',
            'required' => true,
            'years' => $annees, // par défaut l'annéee = -5a à +5a
        ));

        $builder->add('dateSerie', 'date', array(
            'label' => 'Date de la série :',
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
        return 'JourneeForm';
    }

}
