<?php

namespace CHRIST\Modules\Bowling\Repository;

//use CHRIST\Modules\Bowling\Entity\Journee;

/**
 * Description of TournoiRepository
 *
 * @author Christian Alcon
 */
class TournoiRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function findAllOrderedByDateTournoi($periode) {

        return $this->createQueryBuilder('t')
                        //->select('jnee')
                        ->leftJoin('t.journee', 'jnee')
                        ->where('t.dateTournoi >= :dtDeb')
                        ->andWhere('t.dateTournoi <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->orderBy('t.dateTournoi', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllToPlanif() {

        return $this->createQueryBuilder('t')
                        ->where('t.dateTournoi is NULL')
                        ->orderBy('t.type', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function countSaison($periode) {

        return $this->createQueryBuilder('o')
                        ->select('COUNT(o)')
                        ->where('o.dateTournoi >= :dtDeb')
                        ->andWhere('o.dateTournoi <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->getQuery()
                        ->getSingleScalarResult();
    }

}
