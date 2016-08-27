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
abstract class AbstractRoc
{

    /**
     * @ORM\Id
     * @ORM\Column(name="cd_site_roc")
     * @var string
     */
    protected $cdSiteRoc;

    /**
     * @ORM\Column(name="li_site_roc")
     * @var string
     */
    protected $liSiteRoc;

    /**
     * @ORM\Column(name="cd_type_site")
     * @var string
     */
    protected $cdTypeSite;

    /**
     * @ORM\Column(name="ab_type_site")
     * @var string
     */
    protected $abTypeSite;

    /**
     * @ORM\Column(name="li_type_site")
     * @var string
     */
    protected $liTypeSite;

    /**
     * @ORM\Column(name="st_rattach")
     * @var string
     */
    protected $stRattach;

    /**
     * @ORM\Column(name="cd_ent_regate")
     * @var string
     */
    protected $cdEntRegate;

    /**
     * @ORM\Column(name="cd_ent_metier")
     * @var string
     */
    protected $cdEntMetier;
    
    public function isCourrier()
    {
        return $this->cdEntMetier == '034';
    }
    
    /**
     * @ORM\Column(name="li_ent_regate")
     * @var string
     */
    protected $liEntRegate;

    /**
     * @ORM\Column(name="cd_dotc_regate")
     * @var string
     */
    protected $cdDotcRegate;

    /**
     * @ORM\Column(name="cd_dec_regate")
     * @var string
     */
    protected $cdDecRegate;

    /**
     * @ORM\Column(name="cd_dotc_roc")
     * @var string
     */
    protected $cdDotcRoc;

    /**
     * @ORM\Column(name="cd_site_roc_compt")
     * @var string
     */
    protected $cdSiteRocCompt;

    /**
     * @ORM\Column(name="li_site_roc_compt")
     * @var string
     */
    protected $liSiteRocCompt;

    /**
     * @ORM\Column(name="cd_compt_regate")
     * @var string
     */
    protected $cdComptRegate;

    /**
     * @ORM\Column(name="cd_site_roc_valid")
     * @var string
     */
    protected $cdSiteRocValid;

    /**
     * @ORM\Column(name="li_site_roc_valid")
     * @var string
     */
    protected $liSiteRocValid;

    /**
     * @ORM\Column(name="cd_valid_regate")
     * @var string
     */
    protected $cdValidRegate;

    /**
     * @ORM\Column(name="id_etab_org")
     * @var string
     */
    protected $idEtabOrg;

    /**
     * @ORM\Column(name="cd_site_roc_etab_org")
     * @var string
     */
    protected $cdSiteRocEtabOrg;

    /**
     * @ORM\Column(name="li_site_roc_etab_org")
     * @var string
     */
    protected $liSiteRocEtabOrg;

    /**
     * @ORM\Column(name="cd_etab_org_regate")
     * @var string
     */
    protected $cdEtabOrgRegate;

    public function getCdSiteRoc()
    {
        return $this->cdSiteRoc;
    }

    public function getLiSiteRoc()
    {
        return $this->liSiteRoc;
    }

    public function getCdTypeSite()
    {
        return $this->cdTypeSite;
    }

    public function getAbTypeSite()
    {
        return $this->abTypeSite;
    }

    public function getLiTypeSite()
    {
        return $this->liTypeSite;
    }

    public function getStRattach()
    {
        return $this->stRattach;
    }

    public function getCdEntRegate()
    {
        return $this->cdEntRegate;
    }

    public function getLiEntRegate()
    {
        return $this->liEntRegate;
    }

    public function getCdDotcRegate()
    {
        return $this->cdDotcRegate;
    }

    public function getCdDecRegate()
    {
        return $this->cdDecRegate;
    }

    public function getCdDotcRoc()
    {
        return $this->cdDotcRoc;
    }

    public function getCdSiteRocCompt()
    {
        return $this->cdSiteRocCompt;
    }

    public function getLiSiteRocCompt()
    {
        return $this->liSiteRocCompt;
    }

    public function getCdComptRegate()
    {
        return $this->cdComptRegate;
    }

    public function getCdSiteRocValid()
    {
        return $this->cdSiteRocValid;
    }

    public function getLiSiteRocValid()
    {
        return $this->liSiteRocValid;
    }

    public function getCdValidRegate()
    {
        return $this->cdValidRegate;
    }

    public function getIdEtabOrg()
    {
        return $this->idEtabOrg;
    }

    public function getCdSiteRocEtabOrg()
    {
        return $this->cdSiteRocEtabOrg;
    }

    public function getLiSiteRocEtabOrg()
    {
        return $this->liSiteRocEtabOrg;
    }

    public function getCdEtabOrgRegate()
    {
        return $this->cdEtabOrgRegate;
    }

    public function setCdSiteRoc($cdSiteRoc)
    {
        $this->cdSiteRoc = $cdSiteRoc;
    }

    public function setLiSiteRoc($liSiteRoc)
    {
        $this->liSiteRoc = $liSiteRoc;
    }

    public function setCdTypeSite($cdTypeSite)
    {
        $this->cdTypeSite = $cdTypeSite;
    }

    public function setAbTypeSite($abTypeSite)
    {
        $this->abTypeSite = $abTypeSite;
    }

    public function setLiTypeSite($liTypeSite)
    {
        $this->liTypeSite = $liTypeSite;
    }

    public function setStRattach($stRattach)
    {
        $this->stRattach = $stRattach;
    }

    public function setCdEntRegate($cdEntRegate)
    {
        $this->cdEntRegate = $cdEntRegate;
    }

    public function setLiEntRegate($liEntRegate)
    {
        $this->liEntRegate = $liEntRegate;
    }

    public function setCdDotcRegate($cdDotcRegate)
    {
        $this->cdDotcRegate = $cdDotcRegate;
    }

    public function setCdDecRegate($cdDecRegate)
    {
        $this->cdDecRegate = $cdDecRegate;
    }

    public function setCdDotcRoc($cdDotcRoc)
    {
        $this->cdDotcRoc = $cdDotcRoc;
    }

    public function setCdSiteRocCompt($cdSiteRocCompt)
    {
        $this->cdSiteRocCompt = $cdSiteRocCompt;
    }

    public function setLiSiteRocCompt($liSiteRocCompt)
    {
        $this->liSiteRocCompt = $liSiteRocCompt;
    }

    public function setCdComptRegate($cdComptRegate)
    {
        $this->cdComptRegate = $cdComptRegate;
    }

    public function setCdSiteRocValid($cdSiteRocValid)
    {
        $this->cdSiteRocValid = $cdSiteRocValid;
    }

    public function setLiSiteRocValid($liSiteRocValid)
    {
        $this->liSiteRocValid = $liSiteRocValid;
    }

    public function setCdValidRegate($cdValidRegate)
    {
        $this->cdValidRegate = $cdValidRegate;
    }

    public function setIdEtabOrg($idEtabOrg)
    {
        $this->idEtabOrg = $idEtabOrg;
    }

    public function setCdSiteRocEtabOrg($cdSiteRocEtabOrg)
    {
        $this->cdSiteRocEtabOrg = $cdSiteRocEtabOrg;
    }

    public function setLiSiteRocEtabOrg($liSiteRocEtabOrg)
    {
        $this->liSiteRocEtabOrg = $liSiteRocEtabOrg;
    }

    public function setCdEtabOrgRegate($cdEtabOrgRegate)
    {
        $this->cdEtabOrgRegate = $cdEtabOrgRegate;
    }

    public function getCdEntMetier()
    {
        return $this->cdEntMetier;
    }

    public function setCdEntMetier($cdEntMetier)
    {
        $this->cdEntMetier = $cdEntMetier;
    }

}
