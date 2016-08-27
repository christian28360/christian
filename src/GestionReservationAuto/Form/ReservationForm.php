<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTC\Modules\GestionReservationAuto\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form
 *
 * @author wug870
 */
class ReservationForm extends AbstractType
{

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;
        $minutes = \DTC\Modules\GestionReservationAuto\Entity\Reservation::getMinutesAutorisees();

        $builder->add('dateDebut', 'datetime', array(
            'label' => 'Date de début :',
            'date_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'minutes' => $minutes,
            'required' => true,
        ));

        $builder->add('dateFin', 'datetime', array(
            'label' => 'Date de fin :',
            'date_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'minutes' => $minutes,
            'required' => true,
        ));

        $builder->add('voiture', 'entity', array(
            'label' => 'Voiture :',
            'required' => false,
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\Voiture',
            'em' => 'gestionReservationAuto',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user) {
                return $er->getQBByUserPerimetre($user);
            }
        ));

        $builder->add('depart', 'text', array(
            'label' => 'Départ :'
        ));

        $builder->add('destination', 'text', array(
            'label' => 'Destination :'
        ));

        $builder->add('distance', 'text', array(
            'label' => 'Distance aller/retour Km',
            'max_length' => 3
        ));

        $builder->add('motif', 'text', array(
            'label' => 'Motif :'
        ));

        $builder->add('accompagnant', 'text', array(
            'label' => 'Nom accompagnant :',
            'required' => false,
        ));

        $builder->add('remisage', 'checkbox', array(
            'label' => 'Remisage à domicile ? :',
//            'label' => ' ',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('signatureValidateur', 'text', array(
            'label' => 'Mail du validateur :',
            'required' => false,
        ));

        $builder->add('signatureSuperieur', 'email', array(
            'label' => 'Mail supérieur hiérarchique :',
            'required' => true,
            'disabled' => true,
        ));

//        $builder->add('signatureSuperieur', 'entity', array(
//            'label' => 'Mail du validateur :',
//            'required' => true,
//            'class' => '\DTC\Modules\GestionReservationAuto\Entity\User',
//            'property' => 'MailSuperieur',
//            'em' => 'gestionReservationAuto',
//            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                return $er->createQueryBuilder('o');
//            }
//        ));
//
        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return "Reservation";
    }

}
