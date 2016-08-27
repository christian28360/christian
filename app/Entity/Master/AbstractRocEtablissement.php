<?php

namespace CHRIST\Common\Entity\Master;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Roc
 *
 * @author glr735
 */
abstract class AbstractRocEtablissement
{

    /**
     * @ORM\Id
     * @ORM\Column(name="cd_site_roc", type="string")
     * @ORM\GeneratedValue
     */
    protected $cdSiteRoc;

    /**
     * @ORM\Column(name="cd_ent_regate", type="string")
     */
    protected $cdEntRegate;

    /**
     * @ORM\Column(name="etab", type="string")
     */
    protected $etab;

    /**
     * @ORM\Column(name="li_etab", type="string")
     */
    protected $liEtab;

    /**
     * @ORM\Column(name="cd_dotc_regate", type="string")
     */
    protected $cdDotcRegate;

    /**
     * @ORM\Column(name="cd_dotc_roc", type="string")
     */
    protected $cdDotcRoc;

    /**
     * @ORM\Column(name="cd_site_roc_compt", type="string")
     */
    protected $cdSiteRocCompt;

    /**
     * @ORM\Column(name="niveau", type="integer")
     */
    protected $niveau;

    public function getCdSiteRoc()
    {
        return $this->cdSiteRoc;
    }

    public function getCdEntRegate()
    {
        return $this->cdEntRegate;
    }

    public function getEtab()
    {
        return $this->etab;
    }

    public function getLiEtab()
    {
        return $this->liEtab;
    }

    public function getCdDotcRegate()
    {
        return $this->cdDotcRegate;
    }

    public function getCdDotcRoc()
    {
        return $this->cdDotcRoc;
    }

    public function getCdSiteRocCompt()
    {
        return $this->cdSiteRocCompt;
    }

    public function getNiveau()
    {
        return $this->niveau;
    }

    public function setCdSiteRoc($cdSiteRoc)
    {
        $this->cdSiteRoc = $cdSiteRoc;
    }

    public function setCdEntRegate($cdEntRegate)
    {
        $this->cdEntRegate = $cdEntRegate;
    }

    public function setEtab($etab)
    {
        $this->etab = $etab;
    }

    public function setLiEtab($liEtab)
    {
        $this->liEtab = $liEtab;
    }

    public function setCdDotcRegate($cdDotcRegate)
    {
        $this->cdDotcRegate = $cdDotcRegate;
    }

    public function setCdDotcRoc($cdDotcRoc)
    {
        $this->cdDotcRoc = $cdDotcRoc;
    }

    public function setCdSiteRocCompt($cdSiteRocCompt)
    {
        $this->cdSiteRocCompt = $cdSiteRocCompt;
    }

    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }

}
