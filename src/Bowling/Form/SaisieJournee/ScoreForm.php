<?php

namespace CHRIST\Modules\Bowling\Form\SaisieJournee;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ScoreForm
 *
 * @author Christiazn ALCON
 */
class ScoreForm extends AbstractType
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
        for ($i = (int) date('Y'); $i > (int) date('Y') - 15; $i--) {
            $annees[$i] = $i;
        }

        $builder->add('score', 'text', array(
            'label' => false,
            'required' => true,
            'attr' => array('size' => 3, 'max_length' => 3),
        ));

        $builder->add('strike', 'text', array(
            'label' => false,
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
        ));

        $builder->add('spare', 'text', array(
            'label' => false,
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
        ));

        $builder->add('gagnee', 'checkbox', array(
            'label' => false,
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('PasCalculMoyenne', 'checkbox', array(
            'label' => false,
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('surCarnetEntrainement', 'checkbox', array(
            'label' => false,
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('split', 'text', array(
            'label' => false,
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
        ));

        $builder->add('septDix', 'checkbox', array(
            'label' => false,
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CHRIST\Modules\Bowling\Entity\Score',
        ));
    }

    public function getName()
    {
        return 'ScoreForm';
    }

}
