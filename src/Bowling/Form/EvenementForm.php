<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of EvenementForm
 *
 * @author Christiazn ALCON
 */
class EvenementForm extends AbstractType
{

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('code', 'text', array(
            'label' => 'Code évènement :',
            'required' => true,
            'attr' => array('size' => 10),
            'constraints' => array(new Assert\NotBlank(),
                new Assert\Length(array('min' => 1, 'max' => 10))),
        ));

        $builder->add('typeJeu', 'entity', array(
            'label' => 'Type de jeu :',
            'class' => '\CHRIST\Modules\Bowling\Entity\TypeJeu',
            'required' => true,
            'empty_value' => 'Choisissez un type de jeu',
            'property' => 'getLibelle',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.libelle', 'ASC');
            },
        ));

        $builder->add('formation', 'entity', array(
            'label' => 'Formation :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Formation',
            'required' => false,
            'empty_value' => 'Choisissez une formation',
            'property' => 'getLibelle',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.libelle', 'ASC');
            },
        ));

        $builder->add('libelle', 'text', array(
            'label' => 'Libellé :',
            'required' => false,
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

    public function getName()
    {
        return 'EvenementForm';
    }

}
