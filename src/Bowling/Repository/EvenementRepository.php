<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of EvenementRepository
 *
 * @author Christian Alcon
 */
class EvenementRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

    public function findByEvt($e) {

        return $this->createQueryBuilder('e')
                        ->where('e.code = :evt')
                        ->setParameter('evt', $e)
                        ->getQuery()
                        ->getSingleResult();
    }

}
