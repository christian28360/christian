<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of ScoreRepository
 *
 * @author Christian ALCON
 */
class ScoreRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function countSaison($periode) {

        return $this->createQueryBuilder('o')
                        ->select('COUNT(o)')
                        ->where('o.dateJournee >= :dtDeb')
                        ->andWhere('o.dateJournee <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findAllOrderedByDtPartie($periode) {

        return $this->createQueryBuilder('o')
                        ->where('o.dateJournee >= :dtDeb')
                        ->andWhere('o.dateJournee <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->groupBy('o.dateJournee')
                        ->addgroupBy('o.serie')
                        ->addgroupBy('o.score')
                        ->orderBy('o.dateJournee', 'DESC')
                        ->addOrderBy('o.serie', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function countPartiesTotales() {

        return $this->createQueryBuilder('s')
                        ->select('COUNT(s)')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

}
