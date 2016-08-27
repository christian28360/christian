<?php

namespace CHRIST\Modules\Bowling\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of TournoiForm
 *
 * @author Christiazn ALCON
 */
class TournoiForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('dateDebutTournoi', 'date', array(
            'label' => 'Date du 1er tour :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy HH:mm',
            'attr' => array('class' => 'dateDebutTournoi datepicker'),
        ));

        $builder->add('dateTournoi', 'date', array(
            'label' => 'Date du tournoi :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateTournoi datepicker'),
        ));

        $builder->add('dateLimiteInscription', 'date', array(
            'label' => 'Date limite d\'inscription :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateLimiteInscription datepicker'),
        ));

        $builder->add('dateInscription', 'date', array(
            'label' => 'Date d\'inscription:',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateInscription datepicker'),
        ));

        $builder->add('commentaire', 'text', array(
            'label' => 'Commentaire :',
            'required' => false,
            'constraints' => array(new Assert\Length(array('min' => 1, 'max' => 50))),
        ));

        $builder->add('bowling', 'entity', array(
            'label' => 'Bowling :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Bowling',
            'required' => false,
            'empty_value' => 'Choisissez un bowling',
            'property' => 'getNom',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.nom', 'ASC');
            },
        ));

        $builder->add('organisateur', 'entity', array(
            'label' => 'Organisateur :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Organisateur',
            'required' => false,
            'empty_value' => 'Choisissez un organisateur',
            'property' => 'getNom',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.nom', 'ASC');
            },
        ));

        $builder->add('type', 'text', array(
            'label' => 'Type :',
            'required' => false,
            'attr' => array(
                'size' => 0,
                'max_length' => 5,
                'placeholder' => 'H, H45, S,V2, R3, ...'),
            'constraints' => array(new Assert\Length(array('min' => 0, 'max' => 5))),
        ));

        $builder->add('prix', 'text', array(
            'label' => 'Prix :',
            'required' => false,
            'attr' => array('size' => 6, 'max_length' => 4),
            'constraints' => array(new Assert\Type(array('type' => 'numeric'))),
        ));

        $builder->add('nbJoueursOuEquipes', 'text', array(
            'label' => 'Nb participants :',
            'required' => false,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric', 'message' => 'chiffres !'))),
        ));

        $builder->add('classement', 'text', array(
            'label' => 'Classement :',
            'required' => false,
            'attr' => array('size' => 3, 'max_length' => 3),
            'constraints' => array(new Assert\Type(array('type' => 'numeric', 'message' => 'chiffres !'))),
        ));

        $builder->add('finale', 'checkbox', array(
            'label' => 'Finale ?',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('finaleJouee', 'checkbox', array(
            'label' => 'Jouée ?',
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
        return 'TournoiForm';
    }

}
