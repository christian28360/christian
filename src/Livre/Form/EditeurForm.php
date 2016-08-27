<?php

namespace CHRIST\Modules\Livre\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of EditeurForm
 *
 * @author Christian ALCON <christian.alcon at gmail.com>
 *
 * Cette classe permet de générer un formulaire
 * 
 * Par convention nous utilisons le nom XXXXXForm pour les formulaires
 */
class EditeurForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        /*
         * Construction des champs du formulaire
         * ****************************************************** */
        $builder->add('nom', 'text', array(
            'label' => 'Editeur :',
            'required' => true,
            'max_length' => 100,
            'empty_data' => '--> Nom de l\'éditeur <--',
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\NotNull(),
            ),
        ));

        $builder->add('remarques', 'text', array(
            'label' => 'Remarques :',
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
        return 'EditeurForm';
    }

}
