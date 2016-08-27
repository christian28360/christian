<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of ScoreForm
 *
 * @author Christiazn ALCON
 */
class ScoreForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        function __construct(\Silex\Application $app, \Doctrine\ORM\EntityRepository $em) {
            $this->app = $app;
            $this->em = $em;
        }

        $r = (function(\Doctrine\ORM\EntityRepository $er) {
            return $er->createQueryBuilder('o')
                            ->orderBy('o.libelle', 'ASC');
        });

        $builder->add('score', 'text', array(
            'label' => 'Score',
            'required' => true,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 1, 'max' => 300,
                    'minMessage' => 'Vous avez saisi un score de {{ value }}, bornes entre 0 et 300 !',
                    'maxMessage' => 'Vous avez saisi un score de {{ value }}, bornes entre 0 et 300 !'))),
        ));

        $builder->add('strike', 'text', array(
            'label' => 'Strikes',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 12,
                    'minMessage' => 'Vous avez saisi {{ value }} pour valeur de strike, bornes entre 0 et 12 !',
                    'maxMessage' => 'Vous avez saisi {{ value }} pour valeur de strike, bornes entre 0 et 12 !'))),
        ));

        $builder->add('spare', 'text', array(
            'label' => 'Nombre de spares :',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 11,
                    'minMessage' => 'Vous avez saisi {{ value }} pour valeur de spare, bornes entre 0 et 11 !',
                    'maxMessage' => 'Vous avez saisi {{ value }} pour valeur de spare, bornes entre 0 et 11 !'))),
        ));

        $builder->add('split', 'text', array(
            'label' => 'Nombre de splits :',
            'required' => false,
            'attr' => array('size' => 2, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric')),
                new Assert\Range(array('min' => 0, 'max' => 11,
                    'minMessage' => 'Vous avez saisi {{ value }} pour valeur de split, bornes entre 0 et 11 !',
                    'maxMessage' => 'Vous avez saisi {{ value }} pour valeur de split, bornes entre 0 et 11 !'))),
        ));

        $builder->add('gagnee', 'checkbox', array(
            'label' => 'Partie gagnée ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('PasCalculMoyenne', 'checkbox', array(
            'label' => 'Exclure de la moyenne ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('surCarnetEntrainement', 'checkbox', array(
            'label' => 'déduire du carnet d\'entrainement ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));
        
        $builder->add('septDix', 'checkbox', array(
            'label' => 'entrainement 7/10 ? ',
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

    public function getName() {
        return 'ScoreForm';
    }

}
