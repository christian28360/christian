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
class AccreditationForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $role = $options['data'];
        $application = $role->getApplication();
        
        $builder->add('role', 'entity', array(
            'label' => 'Rôle :',
            'class' => '\CHRIST\Common\Entity\Login\Role',
            'em' => 'login',
            'property' => 'name',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($application) {
                return $er->createQueryBuilder('r')
                    ->where('r.application = :application')
                        ->setParameter('application', $application)
                    ->orderBy('r.level', 'ASC');
            },
            'multiple' => false,
            'mapped' => false,
            'data' => $role,
            'attr' => array(
                'class' => 'form-control',
            ),
        ));
            
        $builder->add('filter', 'text', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Recherche',
            ),
            'required' => false,
            'mapped' => false,
        ));
        
        $builder->add('allow', 'button', array(
            'label' => '>>',
            'attr' => array(
                'class' => 'btn btn-default allow-deny-button',
            ),
        ));
        
        $builder->add('deny', 'button', array(
            'label' => '<<',
            'attr' => array(
                'class' => 'btn btn-default allow-deny-button',
            ),
        ));
        
        $builder->add('users', 'collection', 
            array(
                'label'         => 'Accrédité(s) :',
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'type'          => 'entity',
                'options'       => array(
                    'label' => false,
                    'class' => '\CHRIST\Common\Entity\Login\User',
                    'em' => 'login',
                    'property' => 'login',
                    'attr' => array(
                        'class' => 'hide',
                    ),
                ),
            )
        );
    }

    public function getName()
    {
        return 'AccreditationForm';
    }

}
