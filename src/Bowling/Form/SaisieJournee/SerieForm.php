<?php

namespace CHRIST\Modules\Bowling\Form\SaisieJournee;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use CHRIST\Modules\Bowling\Form\SaisieJournee\ScoreForm;

/**
 * Description of SaisieSerieForm
 *
 * @author ezs824
 */
class SerieForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('noSerie', 'text', array(
            'label' => 'N° de série',
            'required' => true,
            'attr' => array('size' => 1, 'max_length' => 1),
            'constraints' => array(new Assert\Type(array('type' => 'numeric',
                    'message' => 'N° de série {{ value }} n\'est pas un nombre')),
                new Assert\Range(array('min' => 1, 'max' => 9,
                    'minMessage' => 'N° de série, vous avez saisi {{ value }}, bornes entre 1 et 9 !',
                    'maxMessage' => 'N° de série, vous avez saisi {{ value }}, bornes entre 1 et 9 !'))),
        ));

        $builder->add('dateSerie', 'date', array(
            'label' => 'Date :',
            'required' => true,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateSerie datepicker'),
        ));

        $builder->add('scores', 'collection', array(
            'label' => false,
            'type' => new ScoreForm(),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'CHRIST\Modules\Bowling\Entity\Serie',
        ));
    }

    public function getName() {
        return 'SerieForm';
    }

}
