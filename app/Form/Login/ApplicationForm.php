<?php

namespace CHRIST\Common\Form\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Default controller of application
 *
 * @author glr735
 */
class ApplicationForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'Nom de l\'application:',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type(array('type' => 'string')),
            ),
        ));
        
        
        $builder->add('roles', 'collection', 
            array(
                'type'         => new Type\RoleType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        );
        
        
        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array('class' => 'btn btn-primary'),
        ));
    }

    public function getName()
    {
        return 'ApplicationForm';
    }

}
