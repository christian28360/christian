<?php

namespace CHRIST\Modules\Livre\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cette classe permet de générer un formulaire
 * 
 * Par convention nous utilisons le nom XXXXXForm pour les formulaires
 */
class ThemeForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        /*
         * Construction des champs du formulaire
         * ****************************************************** */

        $builder->add('libelle', 'text', array(
            'label' => 'Libellé :',
            'required' => true,
            'constraints' => array(new Assert\NotBlank()),
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
        return 'ThemeForm';
    }

}
