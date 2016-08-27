<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of BowlingForm
 *
 * @author Christian Alcon
 */
class BowlingForm extends AbstractType
{ 

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('nom', 'text', array(
            'label' => 'Nom :',
            'required' => true,
        ));

        $builder->add('alias', 'text', array(
            'label' => 'Alias :',
            'required' => false,
        ));

        $builder->add('commentaire', 'text', array(
            'label' => 'Commentaire :',
            'required' => false,
        ));

        $builder->add('adrNo', 'text', array(
            'label' => 'N° :',
            'required' => false,
        ));

        $builder->add('adrRue', 'text', array(
            'label' => 'Rue :',
            'required' => false,
        ));

        $builder->add('adr1', 'text', array(
            'label' => 'Adresse 1 :',
            'required' => false,
        ));

        $builder->add('adr2', 'text', array(
            'label' => 'Adresse 2 :',
            'required' => false,
        ));

        $builder->add('adr3', 'text', array(
            'label' => 'Adresse 3 :',
            'required' => false,
        ));

        $builder->add('adrCp', 'integer', array(
            'label' => 'Code postal :',
            'required' => false,
        ));

        $builder->add('ville', 'text', array(
            'label' => 'Ville :',
            'required' => false,
        ));

        $builder->add('pays', 'text', array(
            'label' => 'Pays :',
            'required' => false,
        ));

        $builder->add('telephone1', 'text', array(
            'label' => 'Téléphone 1 :',
            'required' => false,
        ));

        $builder->add('telephone2', 'text', array(
            'label' => 'Téléphone 2 :',
            'required' => false,
        ));

        $builder->add('mail', 'text', array(
            'label' => 'Adresse mail :',
            'required' => false,
        ));

        $builder->add('siteWeb', 'text', array(
            'label' => 'Site Web :',
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
        return 'BowlingForm';
    }

}
