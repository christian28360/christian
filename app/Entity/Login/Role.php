<?php

namespace CHRIST\Common\Entity\Login;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use CHRIST\Common\Entity\Master\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Common\Repository\Login\RoleRepository")
 * @ORM\Table(name="T_ROLE_ROL")
 */
class Role extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="ROL_ID", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="ROL_NAME", type="string")
     */
    private $name;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="application"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="_")
     *      })
     * }, fields={"name"}, separator="_", prefix="ROLE_", style="upper")
     * @ORM\Column(name="ROL_NAME_SLUG", type="string")
     */
    private $slug;

    /**
     * @ORM\Column(name="ROL_DESCRIPTION", type="text")
     */
    private $description;

    /**
     * @ORM\Column(name="ROL_LEVEL", type="integer")
     */
    private $level;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ROL_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="ROL_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ROL_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="ROL_UPDATE_BY", type="string")
     */
    private $updateBy;
    
    /**
     * @ORM\ManyToOne(targetEntity="\CHRIST\Common\Entity\Login\Application", inversedBy="roles")
     * @ORM\JoinColumn(name="ROL_APP_ID", referencedColumnName="APP_ID")
     **/
    private $application;
    
    /**
     * @ORM\ManyToMany(targetEntity="\CHRIST\Common\Entity\Login\User", mappedBy="accreditations", fetch="EXTRA_LAZY", indexBy="login", cascade={"all"})
     **/
    private $users;
    
    

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add an user at this role
     * @param \CHRIST\Common\Entity\Login\User $user
     */
    public function addUser($user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }
    
    /**
     * Remove an user at this role
     * @param \CHRIST\Common\Entity\Login\User $user
     */
    public function removeUser(\CHRIST\Common\Entity\Login\User $user)
    {
        $user->removeAccreditation($this);
        
        return $this->users->removeElement($user);
    }
    
    /**
     * Remove all users at this role
     */
    public function removeUsers()
    {
        foreach ($this->users as $user) {
            $this->removeUser($user);
        }
    }

    /**
     * Returns all logins used by this role
     * @return array
     */
    public function getUsersUsed()
    {
        $logins = array();
        
        foreach ($this->users as $user) {
            $logins[] = $user->getLogin();
        }
        
        return $logins;
    }
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLevel()
    {
        return $this->level;
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

    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setLevel($level)
    {
        $this->level = $level;
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

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }


}
