<?php

namespace CHRIST\Modules\Bowling\Form\SaisieJournee;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use CHRIST\Modules\Bowling\Form\SaisieJournee\SerieForm;

/**
 * Description of JourneeForm
 *
 * @author Christiazn ALCON
 */
class JourneeForm extends AbstractType {

    /**
     * Buid form
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    protected $series;

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('evenement', 'entity', array(
            'label' => 'Evènement :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Evenement',
            'required' => true,
            'empty_value' => 'Choisissez un évènement',
            'property' => 'getEvt',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.libelle', 'ASC')
                                ->orderBy('o.typeJeu', 'ASC')
                ;
            },
        ));

        $builder->add('bowling', 'entity', array(
            'label' => 'Bowling :',
            'class' => '\CHRIST\Modules\Bowling\Entity\Bowling',
            'required' => true,
            'property' => 'getNom',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('o')
                                ->orderBy('o.nom', 'ASC');
            },
        ));

        $builder->add('tournoi', 'entity', array(
            'label' => 'Si tournoi :',
            'empty_value' => 'Ligne à sélectionner si tournoi non affecté ',
            'class' => '\CHRIST\Modules\Bowling\Entity\Tournoi',
            'required' => false,
            'property' => 'getTournoiInfos',
            'em' => 'christian',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use($options) {
                $qb = $er->createQueryBuilder('t')
                        ->leftJoin('t.journee', 'j');
                $qb->where($qb->expr()->isNotNull('t.dateTournoi'));
                $qb->andWhere($qb->expr()->orX(
                                $qb->expr()->isNull('j.tournoi'), $qb->expr()->eq('j', ':journee')
                ));
                $qb->setParameter('journee', $options['data']->getId());
                return $qb->orderBy('t.dateTournoi', 'ASC');
            },
        ));

        $builder->add('dateJournee', 'date', array(
            'label' => 'Date :',
            'required' => true,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'dateJournee datepicker'),
        ));

        $builder->add('handicap', 'text', array(
            'label' => 'Handicap :',
            'required' => false,
            'attr' => array('size' => 3, 'max_length' => 2),
            'constraints' => array(new Assert\Type(array('type' => 'numeric', 'message' => 'chiffres !'))),
        ));

        $builder->add('series', 'collection', array(
            'label' => false,
            'type' => new SerieForm(),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true
        ));

        $builder->add('nouvelleJnee', 'checkbox',
                //['property_path' => 'nouvelleJnee'], 
                array(
            'label' => false,
            'required' => false,
            'attr' => array(
                'class' => 'hidden',
            ),
        ));

        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary pull-left'),
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

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\CHRIST\Modules\Bowling\Entity\Journee',
        ));
    }

    public function getName() {
        return 'JourneeForm';
    }

}
