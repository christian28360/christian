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
interface IAgentDTC
{
    public function getCodeRH();
    public function getCivilite();
    public function getPrenom();
    public function getNom();
    public function getAffectations();
    public function getDateArrivee();
    public function getDateDepart();

    public function setCodeRH($codeRH);
    public function setCivilite($civilite);
    public function setPrenom($prenom);
    public function setNom($nom);
    public function setAffectations($organigrammes);
    public function setDateArrivee($dateArrivee);
    public function setDateDepart($dateDepart);
}
