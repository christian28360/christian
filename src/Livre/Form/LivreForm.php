<?php

namespace CHRIST\Modules\Livre\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of LivreForm
 * @author Christian ALCON <christian.alcon at gmail.com>
 * Cette classe permet de générer un formulaire
 * Par convention nous utilisons le nom XXXXXForm pour les formulaires
 */
class LivreForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        /*  Générer la liste des années */
        $annees = array();
        for ($i = (int) date('Y'); $i > (int) date('Y') - 50; $i--) {
            $annees[$i] = $i;
        }

        $builder->add('auteurs', 'entity', array(
            'label' => 'Auteur(s) du livre :',
            'class' => '\CHRIST\Modules\Livre\Entity\Ecrivain',
            'em' => 'christian',
            'property' => 'getNomPrenom',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('ecri')
                                ->orderBy('ecri.nom', 'ASC');
            },
            'multiple' => true,
            'expanded' => false,
            'empty_value' => '-- Un ou plusieurs --',
            'attr' => array('size' => 15),
            'constraints' => array(new Assert\NotBlank()),
        ));

        $builder->add('theme', 'entity', array(
            'label' => 'Thème du livre :',
            'class' => '\CHRIST\Modules\Livre\Entity\Theme',
            'required' => true,
            'em' => 'christian',
            'empty_value' => 'Choisissez un thème',
            'property' => 'getLibelle',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('theme')
                                ->orderBy('theme.libelle', 'ASC');
            },
            'constraints' => array(new Assert\NotBlank()),
        ));

        $builder->add('editeur', 'entity', array(
            'label' => 'Editeur :',
            'class' => '\CHRIST\Modules\Livre\Entity\Editeur',
            'required' => true,
            'empty_value' => 'Choisissez un éditeur',
            'property' => 'getNom',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('editeur')
                                ->orderBy('editeur.nom', 'ASC');
            },
            'constraints' => array(new Assert\NotBlank()),
        ));

        $builder->add('couverture', 'entity', array(
            'label' => 'Type de couverture :',
            'class' => '\CHRIST\Modules\Livre\Entity\Couverture',
            'required' => true,
            'empty_value' => 'Choisissez une couverture',
            'property' => 'getLibelle',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('couverture')
                                ->orderBy('couverture.libelle', 'ASC');
            },
            'constraints' => array(new Assert\NotBlank()),
        ));

        $builder->add('titre', 'text', array(
            'label' => 'Titre :',
            'required' => true,
            'attr' => array(
                'size' => 65,
                'max_length' => 100,
                'placeholder' => 'Le titre écrit sur la couverture'),
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('max' => 255))),
        ));

        $builder->add('anneeCopyright', 'choice', array(
            'choices' => $annees,
            'label' => 'Année du Copyright :',
            'required' => false,
            'empty_value' => '- Sélect. -',
        ));

        $builder->add('dateAchat', 'date', array(
            'label' => 'date d\'achat :',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateAchat'),
        ));

        $builder->add('prixAchat', 'text', array(
            'label' => 'Prix d\'achat :',
            'required' => false,
            'attr' => array('size' => 6, 'max_length' => 4),
            'constraints' => array(new Assert\Type(array('type' => 'numeric'))),
        ));

        $builder->add('nbPages', 'text', array(
            'label' => 'Nombre de pages :',
            'required' => false,
            'attr' => array('size' => 6, 'max_length' => 6),
            'constraints' => array(new Assert\Type(array('type' => 'numeric'))),
        ));

        $builder->add('remarques', 'text', array(
            'label' => 'Remarques :',
            'required' => false,
            'attr' => array('size' => 130, 'max_length' => 300,
                'placeholder' => 'Un bref commentaire, si possible'),
            'constraints' => array(new Assert\Type(array('type' => 'string'))),
        ));

        $builder->add('aLire', 'checkbox', array(
            'label' => 'A lire ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('vocabulaire', 'checkbox', array(
            'label' => 'Vocabulaire nouveau ? ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('enStock', 'checkbox', array(
            'label' => 'Présent dans la bibliothèque ? ',
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
        return 'LivreForm';
    }

}
