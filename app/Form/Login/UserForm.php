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
class UserForm extends AbstractType
{
    /**
     * @var boolean
     */
    private $updateMode;
    
    function __construct($updateMode)
    {
        $this->updateMode = $updateMode;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('login', 'text', array(
            'label' => '* Login :',
            'required' => true,
            'read_only' => $this->updateMode ? true : false,
            'attr' => array(
                'class' => 'form-control'
            ),
        ));
        
        $builder->add('password', 'password', array(
            'label' => 'Mot de passe :',
            'required' => $this->updateMode ? false : true,
            'mapped' => false,
            'attr' => array(
                'class' => 'form-control'
            ),
        ));
            
        
        $builder->add('identity', 'text', array(
            'label' => '* Identité :',
            'required' => true,
            'attr' => array(
                'class' => 'form-control'
            ),
        ));
        
        $builder->add('contact', 'text', array(
            'label' => 'Contact :',
            'required' => false,
            'attr' => array(
                'class' => 'form-control'
            ),
        ));
        
        $builder->add('description', 'textarea', array(
            'label' => 'Description :',
            'required' => false,
            'attr' => array(
                'class' => 'form-control'
            ),
        ));
        
        $builder->add('accreditations', 'entity', array(
            'label' => 'Accréditation(s) :',
            'class' => '\CHRIST\Common\Entity\Login\Role',
            'em' => 'login',
            'property' => 'name',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->innerJoin('r.application', 'a', \Doctrine\ORM\Query\Expr\Join::WITH)
                    ->orderBy('a.name', 'ASC')
                    ->addOrderBy('r.level', 'ASC');
            },
            'multiple' => true,
            'expanded' => true,
        ));
        
        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary'
            ),
        ));
    }

    public function getName()
    {
        return 'UserForm';
    }

}
