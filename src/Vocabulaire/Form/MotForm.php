<?php

namespace CHRIST\Modules\Vocabulaire\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of MotForm
 *
 * @author Christian Alcon
 */
class MotForm extends AbstractType {

    /**
     * Build form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('mot', 'text', array(
            'label' => 'Mot :',
            'required' => true,
        ));

        $builder->add('typeMot', 'entity', array(
            'label' => 'Type de mot : :',
            'class' => '\CHRIST\Modules\Vocabulaire\Entity\TypeMot',
            'required' => false,
            'empty_value' => 'Sélectionner un type de mot ',
            'property' => 'getLibelle',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.libelle', 'ASC');
            },
        ));

        $builder->add('livre', 'entity', array(
            'label' => 'Livre :',
            'class' => '\CHRIST\Modules\Livre\Entity\Livre',
            'required' => false,
            'empty_value' => 'Sélectionner un livre de la bibliothèque ',
            'property' => 'getTitre',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.titre', 'ASC');
            },
        ));

        $builder->add('trouveDansDicos', 'entity', array(
            'label' => 'Trouvé dans dictionnaire(s) :',
            'class' => '\CHRIST\Modules\Vocabulaire\Entity\Dictionnaire',
            'em' => 'christian',
            'property' => 'getNom',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('dico')
                                ->orderBy('dico.nom', 'DESC');
            },
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'empty_value' => '-- Aucun, un ou plusieurs --',
            'attr' => array('size' => 11),
//            'constraints' => array(new Assert\NotBlank()),
        ));

        $builder->add('pasTrouveDansDicos', 'entity', array(
            'label' => 'Non trouvé dans dictionnaire(s) :',
            'class' => '\CHRIST\Modules\Vocabulaire\Entity\Dictionnaire',
            'em' => 'christian',
            'property' => 'getNom',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('dico')
                                ->orderBy('dico.nom', 'ASC');
            },
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'empty_value' => '-- Aucun, un ou plusieurs --',
            'attr' => array('size' => 11),
        ));

        $builder->add('page', 'text', array(
            'label' => 'Page :',
            'required' => false,
        ));

        $builder->add('origine', 'textarea', array(
            'label' => 'Origine du mot :',
            'required' => false,
            'attr' => array("rows" => 4, "cols" => 70),
        ));

        $builder->add('signification', 'textarea', array(
            'label' => 'Signification :',
            'required' => true,
            'attr' => array("rows" => 10, "cols" => 70),
        ));

        $builder->add('synonymes', 'textarea', array(
            'label' => 'Synonymes :',
            'required' => false,
            'attr' => array("rows" => 4, "cols" => 70),
        ));

        $builder->add('extraitLivre', 'textarea', array(
            'label' => 'Situation dans le texte :',
            'required' => false,
            'attr' => array("rows" => 4, "cols" => 70),
        ));

        $builder->add('aApprendre', 'checkbox', array(
            'label' => 'à apprendre',
            'required' => false,
        ));

        $builder->add('creeLe', 'date', array(
            'label' => 'Date de saisie :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
        ));


        // les 4 boutons de contrôle :
        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary pull-left'),
        ));

        $builder->add('nouveauMot', 'checkbox', array(
            'label' => false,
            'required' => false,
            'attr' => array(
                'class' => 'hidden',
            ),
        ));

        $builder->add('new', 'submit', array(
            'label' => 'Valider +',
            'attr' => array(
                'class' => 'btn btn-primary pull-left'),
        ));

        $builder->add('quitter', 'button', array(
            'attr' => array(
                'class' => 'btn btn-danger pull-left'),
        ));

        $builder->add('annuler', 'reset', array(
            'attr' => array(
                'class' => 'btn pull-left'),
        ));
    }

    public function getName() {
        return 'MotForm';
    }

}
