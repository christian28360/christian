<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CarnetForm extends AbstractType
{

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('commentaire', 'text', array(
            'label' => 'Commentaire :',
            'required' => FALSE,
        ));

        $builder->add('prix', 'text', array(
            'label' => 'Prix d\'achat :',
            'required' => TRUE,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric'))),
        ));

        $builder->add('nbParties', 'text', array(
            'label' => 'Nombre de parties :',
            'required' => TRUE,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric'))),
        ));

        $builder->add('dateAchat', 'date', array(
            'label' => 'Date d\'achat :',
            'required' => TRUE,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateAchat datepicker'),
        ));

        $builder->add('dateFinValidite', 'date', array(
            'label' => 'Date de fin  de validité :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateFinValidite datepicker'),
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
        return 'CarnetForm';
    }

}
