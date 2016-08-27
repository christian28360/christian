<?php

namespace DTC\Modules\GestionReservationAuto\Repository;

use DTC\Common\Kernel\Extensions\Doctrine\SqlSrv\ORM\CustomEntityRepository;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends CustomEntityRepository
{

    /**
     * Retourne la liste des réservations
     * avec la jointure sur les voitures et les vehicules
     * On exclut donc toutes les réservations sur des véhicules sortie du parc.
     * @return array
     */
    public function findAll()
    {
        return $this->getQBReservation()
                ->getQuery()
                ->getResult();
    }

    /**
     * Retourne la liste des réservations
     * avec la jointure sur les voitures et les vehicules par utilisateur
     * On exclut donc toutes les réservations sur des véhicules sortie du parc.
     * @return array
     */
    public function findByUser(\DTC\Modules\GestionReservationAuto\Entity\User $user)
    {
        return $this->getQBReservation()
                ->where('r.user = :user')
                    ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Return query builder reservation 
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQBReservation()
    {
        return $this->createQueryBuilder('r')
                ->select('r', 'voi', 'veh')
                ->innerJoin('r.voiture', 'voi')
                ->innerJoin('voi.vehicule', 'veh');
    }
    
    /**
     * L'objectif est de savoir si la voiture n'est pas déjà réserver
     * Ou si l'utilisateur n'a pas de réservation dans les dates
     * 
     * @return Reservation
     */
    public function findIfExiste($uneRes)
    {
        $qb = $this->createQueryBuilder('r');
        return $qb->select('r.idReservation')
                        ->where(
                                $qb->expr()->andX(
                                        $qb->expr()->orX(
                                                $qb->expr()->between('r.dateDebut', ':dateDebut', ':dateFin'), $qb->expr()->between('r.dateFin', ':dateDebut', ':dateFin'), $qb->expr()->andX(
                                                        $qb->expr()->lte('r.dateDebut', ':dateDebut'), $qb->expr()->gte('r.dateFin', ':dateFin')
                                                ), $qb->expr()->eq('r.dateDebut', ':dateDebut'), $qb->expr()->eq('r.dateDebut', ':dateFin'), $qb->expr()->eq('r.dateFin', ':dateFin'), $qb->expr()->eq('r.dateFin', ':dateDebut')
                                        ), $qb->expr()->orX(
                                                $qb->expr()->eq('r.user', ':user'), $qb->expr()->eq('r.voiture', ':voiture')
                                        )
                                )
                        )
                        ->setParameter('dateDebut', $uneRes->getDateDebut())
                        ->setParameter('dateFin', $uneRes->getDateFin())
                        ->setParameter('user', $uneRes->getUser())
                        ->setParameter('voiture', $uneRes->getVoiture())
                        ->getQuery()
                        ->getResult();
    }

    public function findByService($service)
    {
        $qb =$this->getQBReservation()
                ->join('r.user', 'u')
                ->join('u.sourceAgent', 'sa')
                ->where('sa.sourceOrgaAffectectation = :service')
                    ->setParameter('service', $service)
                ->getQuery()
                ->getResult();
        
        return $qb;
    }
    public function findByPerimetre($pers)
    {
        $result = array();
        
        foreach($pers as $per) {
            
            $qb = $this->getQBReservation()
                ->innerJoin('r.user', 'u')
                ->where('u.perimetre = :per')
                ->setParameter('per', $per)
                ->getQuery()
                ->getResult();
            
            $result = array_merge($result, $qb);
        }
        
        return $result;
    }
    public function findByDateAndUser($date, $user)
    {
        return $this->getQBReservation()
                ->where('r.dateCreation = :date')
                    ->setParameter('date', $date)
                ->andWhere('r.user = :user')
                    ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
    }

    public function findByRegistre($reg)
    {
        $qb = $this->getQBReservation()
                ->where('r.dateDebut >= :dateDebut')
                ->andWhere('r.dateDebut <= :dateFin')
                ->andWhere('r.user = :user')
                ->andWhere('r.dateCreation <= :dateCreation')
                    ->setParameter('dateDebut', $reg->getDateDebut())
                    ->setParameter('dateFin', $reg->getDateFin())
                    ->setParameter('user', $reg->getUser())
                    ->setParameter('dateCreation', $reg->getDateCreation())
                ->getQuery()
                ->getResult();
        return $qb;
    }

    public function findAllByPerimetre($perimetre)
    {
        return $this->getQBReservation()
                        ->innerJoin('r.user', 'u')
                        ->where('u.perimetre = :perimetre')
                            ->setParameter('perimetre', $perimetre)
                        ->getQuery()
                        ->getResult();
    }

}
