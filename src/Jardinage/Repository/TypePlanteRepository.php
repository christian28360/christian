<?php

namespace CHRIST\Modules\Jardinage\Repository;

/**
 * Description of TypePlanteRepository
 *
 * @author Christian Alcon
 */
class TypePlanteRepository extends \CHRIST\Common\Repository\CustomEntityRepository {

        public function getListeType() {

        return $this->createQueryBuilder('o')
                        ->select('o.type')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

}
