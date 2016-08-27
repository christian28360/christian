<?php

namespace CHRIST\Common\Entity\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use CHRIST\Common\Entity\Master\AbstractEntity;
use CHRIST\Common\Kernel\SingleApp;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Common\Repository\Login\UserRepository")
 * @ORM\Table(name="T_USER_USR")
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * Name of entity manager
     * @var string
     */
    protected $entityManagerName = 'login';
    
    /**
     * Name of entity
     * @var string
     */
    protected $entityName = '\CHRIST\Common\Entity\Login\User';
    
    /**
     * @ORM\Id
     * @ORM\Column(name="USR_LOGIN", type="string")
     */
    private $login;

    /**
     * @ORM\Column(name="USR_PASSWORD", type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="USR_SALT", type="string")
     */
    private $salt;

    /**
     * @ORM\Column(name="USR_IDENTITY", type="string")
     */
    private $identity;

    /**
     * @ORM\Column(name="USR_CONTACT", type="string")
     */
    private $contact;

    /**
     * @ORM\Column(name="USR_DESCRIPTION", type="text")
     */
    private $description;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="USR_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="USR_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="USR_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="USR_UPDATE_BY", type="string")
     */
    private $updateBy;
    
    /**
     * @ORM\ManyToMany(targetEntity="\CHRIST\Common\Entity\Login\Role", inversedBy="users", fetch="EXTRA_LAZY", cascade={"all"}, indexBy="slug")
     * @ORM\JoinTable(name="T_ACCREDITATION_ACC",
     *      joinColumns={@ORM\JoinColumn(name="ACC_USR_ID", referencedColumnName="USR_LOGIN")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ACC_ROL_ID", referencedColumnName="ROL_ID")}
     *      )
     * @var \Doctrine\Common\Collections\ArrayCollection
     **/
    private $accreditations;
    

    public function __construct()
    {
        $this->accreditations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login', new Assert\NotBlank());
        $metadata->addPropertyConstraint('login', new Assert\NotNull());
        $metadata->addConstraint(new Assert\Callback(function ($object, ExecutionContextInterface $context) {
            $app = SingleApp::getAppliation();
            
            $exist = $app['orm.ems']['login']
                ->getRepository("CHRIST\Common\Entity\Login\User")
                ->userAlreadyExist($object);
                    
            if ($exist === true) {
                $context->addViolation(
                    'Le login "' . $object->getLogin() . '" est déjà utilisé'
                );
            }
        }));
        
        $metadata->addPropertyConstraint('identity', new Assert\NotBlank());
        $metadata->addPropertyConstraint('identity', new Assert\NotNull());
    }
    
    /**
     * Add an role at this user
     * @param \CHRIST\Common\Entity\Login\Role $role
     */
    public function addAccreditation(\CHRIST\Common\Entity\Login\Role $role)
    {
        if (!$this->accreditations->contains($role)) {
            $this->accreditations->add($role);
        }
    }
    
    /**
     * Remove an role at this user
     * @param \CHRIST\Common\Entity\Login\Role $role
     */
    public function removeAccreditation(\CHRIST\Common\Entity\Login\Role $role)
    {
        $this->accreditations->removeElement($role);
    }
    
    /**
     * Remove all role at this user
     */
    public function removeAccreditations()
    {
        foreach ($this->accreditations as $role) {
            $role->removeUser($this);
        }
    }
    
    /**
     * Create a User from self, is initialized with self roles
     * @param string $login New login
     * @return \CHRIST\Common\Entity\Login\User
     */
    public function cloneEntity($login)
    {
        $className = __CLASS__; 
        $user = new $className();
        $user->setLogin($login);
        
        foreach ($this->accreditations as $role) {
            $role->addUser($user);
            $user->addAccreditation($role);
        }
        
        return $user;
    }
    
    /**
     * Generate salt and encode password
     * @param \Symfony\Component\Security\Core\Encoder\BasePasswordEncoder $passwordEncoder
     * @param string $password password
     */
    public function changePassword(\Symfony\Component\Security\Core\Encoder\BasePasswordEncoder $passwordEncoder, $password)
    {
        $salt = \CHRIST\Common\Kernel\Helpers\PhpHelpers::generateRandomString();
        $this->salt = $salt;
        $this->password = $passwordEncoder->encodePassword($password, $salt);
    }
    
    /**
     * Return true if user is accredited 
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->accreditations->containsKey($role);
    }
    
    
    

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->login;
    }
    
    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->accreditations->getKeys();
    }
    

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function getContact()
    {
        return $this->contact;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function getCreateBy()
    {
        return $this->createBy;
    }

    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    public function getUpdateBy()
    {
        return $this->updateBy;
    }

    public function getAccreditations()
    {
        return $this->accreditations;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    }

    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;
    }

    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    public function setUpdateBy($updateBy)
    {
        $this->updateBy = $updateBy;
    }

    public function setAccreditations($accreditations)
    {
        $this->accreditations = $accreditations;
    }

}
