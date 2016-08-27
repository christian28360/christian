<?php

namespace CHRIST\Modules\Jardinage\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cette classe permet de générer un formulaire
 * 
 * Par convention nous utilisons le nom XXXXXForm pour les formulaires
 */
class TypePlanteForm extends AbstractType
{

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('typePlante', 'text', array(
            'label' => 'Type de plante :',
            'required' => true,
        ));

        $builder->add('libelle', 'text', array(
            'label' => 'Libellé :',
            'required' => true,
        ));

        $builder->add('commentaire', 'textarea', array(
            'label' => 'Commentaire :',
        ));
//
//        $builder->add('origine', 'textarea', array(
//            'label' => 'Origine du mot :',
//            'required' => false,
//            'attr' => array("rows" => 4, "cols" => 70),
//        ));

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
        return 'TypePlanteForm';
    }

}
