<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of JourneeRepository
 *
 * @author Christian Alcon
 */
class JourneeRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function findIfExist($evenement, $dateJournee) {

        list( $jour, $mois, $annee) = sscanf($dateJournee, "%d/%d/%d");
        $d = new \DateTime($annee . '-' . $mois . '-' . $jour);

        return $this->createQueryBuilder('o')
                        ->where('o.evenement = :evt')
                        ->andWhere('o.dateJournee = :dt')
                        ->setParameter('evt', $evenement)
                        ->setParameter('dt', $d)
                        ->getQuery()
                        ->getResult();
    }

    public function findAllEntrainementsSeptDixOrderedByDateJournee($periode) {



        return $this->createQueryBuilder('o')
                        ->join('o.series', 'sr')
                        ->join('sr.scores', 's')
                        ->where('o.dateJournee >= :dtDeb')
                        ->andWhere('o.dateJournee <= :dtFin')
                        ->andWhere('s.surCarnetEntrainement = :entrainement')
                        ->andWhere('s.septDix = :septDix')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->setParameter('entrainement', 1)
                        ->setParameter('septDix', 1)
                        ->orderBy('o.dateJournee', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllEntrainementsOrderedByDateJournee($periode, $septDix = FALSE) {

        $qb = $this->createQueryBuilder('o')
                ->join('o.series', 'sr')
                ->join('sr.scores', 's')
                ->where('o.dateJournee >= :dtDeb')
                ->andWhere('o.dateJournee <= :dtFin')
                ->andWhere('s.surCarnetEntrainement = :entrainement')
                ->andWhere('s.septDix = :septDix')
                ->setParameter('dtDeb', $periode->getDtDeb())
                ->setParameter('dtFin', $periode->getDtFin())
                ->setParameter('entrainement', 1)
                ->orderBy('o.dateJournee', 'DESC');
        if ($septDix) {
            $qb->setParameter('septDix', 1);
        } else {
            $qb->setParameter('septDix', 0);
        }

        return $qb->getQuery()
                        ->getResult();
    }

    public function findAllOrderedByDateJournee($periode) {

        return $this->createQueryBuilder('o')
                        ->where('o.dateJournee >= :dtDeb')
                        ->andWhere('o.dateJournee <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->orderBy('o.dateJournee', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

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

    public function countPartiesSaison($periode) {

        return $this->createQueryBuilder('jnee')
                        ->select('COUNT(s)')
                        ->join('jnee.series', 'sr')
                        ->join('sr.scores', 's')
                        ->where('jnee.dateJournee >= :dtDeb')
                        ->andWhere('jnee.dateJournee <= :dtFin')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->getQuery()
                        ->getSingleScalarResult();
    }

}
