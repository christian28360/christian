<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of BowlingRepository
 *
 * @author Christian Alcon
 */
class BowlingRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

        public function findParNomOuAlias($nom) {

        return $this->createQueryBuilder('b')
                        ->where('b.nom like :nom')
                        ->orWhere('b.alias like :nom')
                        ->setParameter('nom', $nom)
                        ->getQuery()
                        ->getSingleResult();
    }

}
