<?php

namespace CHRIST\Common\Entity;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Common\Repository\RocEtablissementRepository")
 * @ORM\Table(name="roc_etablissement")
 */
class RocEtablissement extends Master\AbstractRocEtablissement
{

}
