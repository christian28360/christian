<?php

namespace CHRIST\Modules\Bowling\Repository;

/**
 * Description of TypeJeuRepository
 *
 * @author Christian Alcon
 */
class TypeJeuRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

        public function getListeType() {

        return $this->createQueryBuilder('o')
                        ->select('o.type')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

}