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
interface IAffectationDTC
{
    public function getId();
    public function getService();
    public function getAgent();
    public function getResponsable();
    public function getDateDebut();
    public function getDateFin();
    
    
    public function setId($data);
    public function setResponsable($data);
    public function setDateDebut($data);
    public function setDateFin($data);
}
