<?php

/**
 * Description of PeriodeForm
 *
 * @author Christian Alcon
 */

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PeriodeForm extends AbstractType
{

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /*  Générer la liste des années */
        $annees = array();
        for ($i = (int) date('Y') + 1; $i > (int) date('Y') - 15; $i--) {
            $annees[$i] = $i;
        }

        $builder->add('dtDeb', 'date', array(
            'label' => 'Début de saison :',
            'required' => true,
            'attr' => array('class' => 'dateDebut datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
        ));

        $builder->add('dtFin', 'date', array(
            'label' => 'Fin de saison :',
            'required' => true,
            'attr' => array('class' => 'dateFin datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
        ));

        $builder->add('isActive', 'checkbox', array(
            'label' => 'Saison courante ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
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
        return 'PeriodeForm';
    }

}
