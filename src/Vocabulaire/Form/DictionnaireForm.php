<?php

namespace CHRIST\Modules\Vocabulaire\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of DictionnaireForm
 *
 * @author Christian Alcon
 */
class DictionnaireForm extends AbstractType {

    /**
     * Build form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('nom', 'text', array(
            'label' => 'Nom du dictionnaire :',
            'required' => true,
        ));

        $builder->add('commentaire', 'text', array(
            'label' => 'Commentaire :',
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
        return 'DictionnaireForm';
    }

}
