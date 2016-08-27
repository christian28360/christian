<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of SerieRepository
 *
 * @author Christian Alcon
 */
class SerieRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function findData($periode) {

        return $this->createQueryBuilder('s')
                        ->where('s.dateSerie >= :dtDeb')
                        ->andWhere('s.dateSerie <= :dtFin')
                        ->orderBy('s.dateSerie', 'ASC', 's.noSerie', 'ASC')
                        ->setParameter('dtDeb', $periode->getDtDeb())
                        ->setParameter('dtFin', $periode->getDtFin())
                        ->getQuery()
                        ->getResult();
    }

    public function finAllByCarnet($carnets) {

        foreach ($carnets as $value) {
            $value->setNbPartiesJouees($this->getTotalParties($value->getDateAchat(), $value->getDateFinValidite())[1]);
            $value->setSeries($this->getParties($value->getDateAchat(), $value->getDateFinValidite()));
        }

        return $carnets;
    }

    public function getTotalParties($deb, $fin) {

        return $this->createQueryBuilder('s')
                        ->select('COUNT(s)')
                        ->join('s.scores', 'scr')
                        ->where('s.dateSerie >= :dtDeb')
                        ->andWhere('s.dateSerie < :dtFin')
                        ->andWhere('scr.surCarnetEntrainement = :entrainement')
                        ->setParameter('dtDeb', $deb)
                        ->setParameter('dtFin', $fin)
                        ->setParameter('entrainement', 1)
                        ->getQuery()
                        ->getSingleResult();
    }

    public function getParties($deb, $fin) {

        return $this->createQueryBuilder('s')
                        ->select('s.dateSerie, COUNT(scr)')
                        ->join('s.scores', 'scr')
                        ->groupBy('s.dateSerie')
                        ->where('s.dateSerie >= :dtDeb')
                        ->andWhere('s.dateSerie < :dtFin')
                        ->andWhere('scr.surCarnetEntrainement = :entrainement')
                        ->orderBy('s.dateSerie', 'ASC', 's.noSerie', 'ASC')
                        ->setParameter('dtDeb', $deb)
                        ->setParameter('dtFin', $fin)
                        ->setParameter('entrainement', 1)
                        ->getQuery()
                        ->getResult();
    }

}
