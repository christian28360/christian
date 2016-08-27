<?php

namespace CHRIST\Common\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CustomEntityRepository
 *
 * @author CALC
 */
abstract class CustomEntityRepository extends EntityRepository {

    public function findAllOrderedByTitre() {
        return $this->findAllData('titre');
    }

    public function findAllOrderedByNom() {
        return $this->findAllData('nom');
    }

    public function findAllOrderedByMot($filter = null) {
        return $this->findAllDataFiltered($filter);
    }

    public function findAllOrderedByLibelle() {
        return $this->findAllData('libelle');
    }

    public function findAllOrderedByCode() {
        return $this->findAllData('code');
    }

    protected function findAllData($criteria) {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.' . $criteria, 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findAllOrderedByNomPrenom() {

        return $this->createQueryBuilder('o')
                        ->orderBy('o.nom', 'ASC')
                        ->addOrderBy('o.prenom', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Delate all and return number of line deleted
     * 
     * @return int
     */
    public function deleteAll() {
        return $this->createQueryBuilder('o')
                        ->delete()
                        ->getQuery()
                        ->execute();
    }

    /**
     * Returm number line in table
     * @return int
     */
    public function countAll() {
        return $this->createQueryBuilder('o')
                        ->select('COUNT(o)')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findByCode($code) {

        return $this->createQueryBuilder('o')
                        ->where('o.code = :code')
                        ->getQuery()
                        ->setParameter('code', $code)
                        ->getResult();
    }

    public function findAllDataFiltered($filter) {

        $qb = $this->createQueryBuilder('o');
        switch ($filter) {
            case 'connus':
            case 'inconnus':
                $qb->where('o.aApprendre = :filtre')
                        ->setParameter('filtre', ($filter == 'connus') ? false : true );
                break;
            case 'aChercher':
                $qb->where('o.trouveDansDicos IS empty');
                break;
            case '_absent':
                $qb->where('o.livre = :filtre')
                        ->andWhere('o.extraitLivre IS NULL')
                        ->setParameter('filtre', 207);
                break;
            default:
                break;
        }

        return $qb->orderBy('o.mot', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function findByNom($nom) {

        return $this->createQueryBuilder('o')
                        ->where('o.nom = :nom')
                        ->getQuery()
                        ->setParameter('nom', $nom)
                        ->getResult();
    }

    public function findByLibelle($libelle) {

        return $this->createQueryBuilder('o')
                        ->where('o.libelle = :libelle')
                        ->getQuery()
                        ->setParameter('libelle', $libelle)
                        ->getResult();
    }

}
