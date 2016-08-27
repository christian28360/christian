<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of CarnetRepository
 *
 * @author Christian Alcon
 */
class CarnetRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function gfindAllOrderedBydateAchat() {

        return $this->createQueryBuilder('c')
                        ->orderBy('c.dateAchat', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllOrderedBydateAchat() {

        $records = array();
        $carnets = $this->createQueryBuilder('c')
                ->orderBy('c.dateAchat', 'ASC')
                ->getQuery()
                ->getResult();
        // mise à jour de la date de fin, par rapport 
        // à la date de début du carnet suivant (ou  aujourd'hui si c'est le dernier)
        $offSet = count($carnets) - 1;
        for ($i = 0; $i <= $offSet; $i++) {
            $carnets[$i]->setDateFinValidite(($i == $offSet) ? new \DateTime() : $carnets[$i + 1]->getDateAchat());
            $records[$carnets[$i]->getDateAchat()->format('Y/m/d')] = $carnets[$i];
        }

        asort($records);

        return $records;
    }

}
