<?php

namespace CHRIST\Modules\Livre\Repository;

use CHRIST\Common\Repository\CustomEntityRepository;

class LivreRepository extends CustomEntityRepository {

    public function findAllDataFiltered($col, $filter = null) {

        return $this->createQueryBuilder('o')
                        ->where('o.' . $col . ' = ' . $filter)
                        ->orderBy('o.titre', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findParTitre($titre) {

        return $this->createQueryBuilder('b')
                        ->where('LOWER(b.titre) = :titre')
                        ->setParameter('titre', strtolower($titre))
                        ->getQuery()
                        ->getResult();
    }

}
