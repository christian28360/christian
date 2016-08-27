<?php

namespace CHRIST\Modules\Vocabulaire\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of TypeMotForm
 *
 * @author Christian Alcon
 */
class TypeMotForm extends AbstractType {

    /**
     * Build form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('typeMot', 'text', array(
            'label' => 'Type de mot :',
            'required' => true,
        ));

        $builder->add('sousTypeMot', 'text', array(
            'label' => 'Sous Type de mot :',
            'required' => false,
        ));

        $builder->add('sousSousTypeMot', 'text', array(
            'label' => 'Sous Type complémentaire de mot :',
            'required' => false,
        ));

        $builder->add('libelle', 'text', array(
            'label' => 'Description complète :',
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

    public function getName() {
        return 'TypeMotForm';
    }

}
