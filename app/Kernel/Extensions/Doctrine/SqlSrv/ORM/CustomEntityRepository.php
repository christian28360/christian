<?php

namespace CHRIST\Common\Kernel\Extensions\Doctrine\SqlSrv\ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\EntityRepository;

/**
 * Description of EntityRepository
 *
 * @author glr735
 */
abstract class CustomEntityRepository extends EntityRepository {

    public function findAllOrderedByTitre() {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.titre', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllOrderedByNom() {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.nom', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllOrderedByNomPrenom() {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.nom', 'ASC')
                        ->addOrderBy('o.prenom', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllOrderedByLibelle() {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.libelle', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Returm number line deleted
     * @return int
     */
    public function deleteAll() {
        return $this->createQueryBuilder('o')
                        ->delete()
                        ->getQuery()
                        ->execute();
    }

    /**
     * Returm number line in table
     * @return int
     */
    public function countAll() {
        return $this->createQueryBuilder('o')
                        ->select('COUNT(o)')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findByCode($code) {

        return $this->createQueryBuilder('o')
                        ->where('o.code = :code')
                        ->getQuery()
                        ->setParameter('code', $code)
                        ->getResult();
    }

    public function findByNom($nom) {
        
        return $this->createQueryBuilder('o')
                        ->where('o.nom = :nom')
                        ->getQuery()
                        ->setParameter('nom', $nom)
                        ->getResult();
    }

    public function findByLibelle($libelle) {
        
        return $this->createQueryBuilder('o')
                        ->where('o.libelle = :libelle')
                        ->getQuery()
                        ->setParameter('libelle', $libelle)
                        ->getResult();
    }

}
