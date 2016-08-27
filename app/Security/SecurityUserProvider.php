<?php

namespace CHRIST\Common\Security;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Description of UserProvider
 *
 * @author glr735
 */
class SecurityUserProvider implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $app = \CHRIST\Common\Kernel\SingleApp::getAppliation();        
        
        $user = $app['orm.ems']['login']
            ->getRepository("CHRIST\Common\Entity\Login\User")
            ->find($username);
        
        if (is_null($user)) {
            throw new UsernameNotFoundException(sprintf('User "%s" Doesn\'t exist.', $username));
        }
        
        return $user;
    }
 
    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof \CHRIST\Common\Entity\Login\User) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" is not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'CHRIST\Common\Entity\Login\User';
    }
}
