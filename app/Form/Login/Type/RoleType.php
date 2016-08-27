<?php

namespace CHRIST\Common\Form\Login\Type;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Default controller of application
 *
 * @author glr735
 */
class RoleType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('name', 'text', array(
            'label' => 'Nom :',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(), 
                new Assert\NotNull(),
                new Assert\Type(array('type' => 'string')),
            ),
        ));
            
        
        $builder->add('description', 'textarea', array(
            'label' => 'Description :',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(), 
                new Assert\NotNull(),
                new Assert\Type(array('type' => 'string')),
            ),
        ));
            
        
        $builder->add('level', 'integer', array(
            'label' => 'Niveau :',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(), 
                new Assert\NotNull(),
                new Assert\Type(array('type' => 'integer')),
            ),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CHRIST\Common\Entity\Login\Role',
        ));
    }

    public function getName()
    {
        return 'RoleType';
    }
}