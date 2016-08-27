<?php

namespace CHRIST\Common\Interfaces;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author glr735
 */
interface IOrganigrammeDTC
{
    public function getId();
    public function getCode();
    public function getLibelle();
    public function getNiveau();
    public function getBorneGauche();
    public function getBorneDroite();
    public function getNbDescendant();
    public function getAffectations();
    public function getAbsences();
    
    
    public function setId($id);
    public function setCode($code);
    public function setLibelle($libelle);
    public function setNiveau($niveau);
    public function setBorneGauche($borneGauche);
    public function setBorneDroite($borneDroite);
    public function setNbDescendant($nbDescendant);
}
