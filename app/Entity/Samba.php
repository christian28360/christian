<?php

namespace CHRIST\Common\Entity;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Common\Repository\SambaRepository")
 * @ORM\Table(name="sujets")
 */
class Samba extends Master\AbstractSamba
{
    
}