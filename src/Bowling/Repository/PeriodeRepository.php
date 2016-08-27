<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of PeriodeRepository
 *
 * @author Christian ALCON
 */
class PeriodeRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function findAllOrderedByDtDeb() {
        return $this->createQueryBuilder('o')
                        ->orderBy('o.dtDeb', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function findActivePeriode() {
        return $this->createQueryBuilder('o')
                        ->where('o.isActive = true')
                        ->getQuery()
                        ->getSingleResult();
    }

    public function findOnePeriode() {
        return $this->createQueryBuilder('o')
                        ->where('o.isActive = true')
                        ->getQuery()
                        ->getSingleResult();
    }

    public function desactivation() {
        $r = $this->createQueryBuilder('o')
                ->update('o')
                ->set('o.isActive', 'false')
                ->where('o.isActive = true')
                ->getQuery()
                ->getSingleResult();

        return $r;
    }

    public function findByPeriode($periode = null) {

        $qb = $this->createQueryBuilder('o')
                ->where('o.dtDeb = :dtDeb')
                ->andWhere('o.dtFin = :dtFin')
                ->setParameter('dtDeb', $periode->getDtDeb())
                ->setParameter('dtFin', $periode->getDtFin());

        return $this->getResults($qb);
    }

    public function findDoublons($dtDeb = null, $dtFin = null) {

        $qb = $this->createQueryBuilder('o');
        $qb->where(
                        $qb->expr()->orX(
                                $qb->expr()->between(':dtDeb', 'o.dtDeb', 'o.dtFin'), $qb->expr()->between(':dtFin', 'o.dtDeb', 'o.dtFin')
                        )
                )
                ->setParameter('dtDeb', $dtDeb)
                ->setParameter('dtFin', $dtFin);

        return $this->getResults($qb);
    }

    public function getResults($qb) {

        return $qb->getQuery()
                        ->getResult();
    }

}
