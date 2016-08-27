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
 * @ORM\Entity(repositoryClass="CHRIST\Common\Repository\Login\ApplicationRepository")
 * @ORM\Table(name="T_APPLICATION_APP")
 */
class Application extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="APP_ID", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="APP_NAME", type="string")
     */
    private $name;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\InversedRelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationClass", value="\CHRIST\Common\Entity\Login\Role"),
     *          @Gedmo\SlugHandlerOption(name="mappedBy", value="application"),
     *          @Gedmo\SlugHandlerOption(name="inverseSlugField", value="slug")
     *      })
     * }, fields={"name"}, separator="_", style="upper")
     * @ORM\Column(name="APP_NAME_SLUG", type="string")
     */
    private $slug;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="APP_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="APP_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="APP_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="APP_UPDATE_BY", type="string")
     */
    private $updateBy;
    
    /**
     * @ORM\OneToMany(targetEntity="\CHRIST\Common\Entity\Login\Role", mappedBy="application", fetch="EXTRA_LAZY", cascade={"all"}, indexBy="id")
     * @ORM\OrderBy({"level" = "ASC"})
     **/
    protected $roles;
    
    
    
    

    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Count unique user accredited on this application
     * @return integer
     */
    public function getNbUsers()
    {
        $users = new \SplObjectStorage();
        
        foreach ($this->roles as $role) {
            foreach ($role->getUsers() as $user) {
                if (!$users->contains($user)) {
                    $users->attach($user);
                }
            }
        }
        
        return $users->count();
    }
    
    /**
     * Remove an role at this application
     * @param \CHRIST\Common\Entity\Login\Role $role
     */
    public function removeRole(\CHRIST\Common\Entity\Login\Role $role)
    {
        $role->removeUsers();
            
        return $this->roles->removeElement($role);
    }
    
    /**
     * Remove all roles at this application
     */
    public function removeRoles()
    {
        foreach ($this->roles as $role) {
            $this->removeRole($role);
        }
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
    
    public function getStatus()
    {
        return $this->status;
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

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
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
    
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
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

}
