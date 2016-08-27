<?php

namespace CHRIST\Modules\Bowling\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
/* */
use CHRIST\Modules\Bowling\Entity\Bowling;
use CHRIST\Modules\Bowling\Entity\Formation;
use CHRIST\Modules\Bowling\Entity\Periode;
use CHRIST\Modules\Bowling\Entity\TypeJeu;

/**
 * Update module Bowling beore and/or after flush
 *
 * @author Christian ALCON
 */
class BowlingListener {

    /**
     * @var \Doctrine\ORM\Mapping\ClassMetadata
     */
    private $metadata = null;

    /**
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args) {

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof TypeJeu) {
                $entity->setTypeJeu(strtoupper($entity->getTypeJeu()));
            }
            if ($entity instanceof Bowling) {
                $entity->setCommentaire(strtoupper($entity->getCommentaire()));
            }
            if ($entity instanceof Formation) {
                $entity->setCode(strtoupper($entity->getCode()));
            }
            if ($entity instanceof Periode) {
            // ..    
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args) {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // Traitement de la modification de type jeu
        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof TypeJeu) {
                $entity->setTypeJeu(strtoupper($entity->getTypeJeu()));
                $this->computeChanges($em, $uow, $entity);
            }
            if ($entity instanceof Formation) {
                $entity->setCode(strtoupper($entity->getCode()));
                $this->computeChanges($em, $uow, $entity);
            }
            if ($entity instanceof Periode) {
                
            }
        }
    }

    public function computeChanges($em, $uow, $entity) {
        $em->persist($entity);
        $metadata = $em->getClassMetadata(get_class($entity));
        $uow->computeChangeSet($metadata, $entity);
    }

}
