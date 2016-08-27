<?php

/**
 * Description of Tournoi
 *      C'est le suivi des tournois et Championnats
 *
 * @author ALCON Christian
 */

namespace CHRIST\Modules\Bowling\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Bowling\Repository\TournoiRepository")
 * @ORM\Table(name="bowl_tournoi")
 * @author Christian Alcon
 */
class Tournoi extends \CHRIST\Common\Entity\Master\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_tournoi_id", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Journee", mappedBy="tournoi")
     * @var arrayCollection
     */
    private $journee;

    /**
     * @ORM\Column(name="bowl_tournoi_date_tournoi", type="datetime")
     */
    private $dateTournoi;

    /**
     * @ORM\Column(name="bowl_tournoi_date_debut", type="datetime")
     */
    private $dateDebutTournoi;

    /**
     * @ORM\Column(name="bowl_tournoi_commentaire", type="string")
     */
    private $commentaire;

    /**
     * @ORM\Column(name="bowl_tournoi_date_limite_inscription", type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(name="bowl_tournoi_date_inscription", type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(name="bowl_tournoi_prix", type="decimal")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="Organisateur"), inversedBy="organisateur", fetch="LAZY")
     * @ORM\JoinColumn(name="bowl_tournoi_organisateur_id", referencedColumnName="bowl_org_id")
     */
    private $organisateur;

    /**
     * @ORM\OneToOne(targetEntity="Bowling")
     * @ORM\JoinColumn(name="bowl_tournoi_bowling_id", referencedColumnName="bowl_bowling_id")
     */
    private $bowling;

    /**
     * @ORM\Column(name="bowl_tournoi_type", type="string")
     */
    private $type;

    /**
     * @ORM\Column(name="bowl_tournoi_nb_participants", type="integer")
     */
    private $nbJoueursOuEquipes;

    /**
     * @ORM\Column(name="bowl_tournoi_classement", type="integer")
     */
    private $classement;

    /**
     * @ORM\Column(name="bowl_tournoi_finale", type="boolean")
     */
    private $finale;

    /**
     * @ORM\Column(name="bowl_tournoi_finale_jouee", type="boolean")
     */
    private $finaleJouee;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="bowl_tournoi_created_on", type="date")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="bowl_tournoi_updated_on", type="date")
     */
    private $modifieLe;

    function __construct() {
        $this->bowling = "";
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata) {

        $metadata->addConstraint(
                new Assert\Callback(function ($object,
                \Symfony\Component\Validator\ExecutionContextInterface $context) {

            // nBJoueurs compris entre 0 et 200
            if (!_between($object->getNbJoueursOuEquipes(), 0, 80)) {
                $context->addViolation(
                        'Tournois : le nombre de joueurs (' .
                        $object->getNbJoueursOuEquipes() . ') doit être compris entre 0 et 200');
            }

            // Classement <= à nBJoueurs
            if ($object->getClassement() > $object->getNbJoueursOuEquipes()) {
                $context->addViolation(
                        'Tournois : On ne peut être plus mal classé que le nombre de joueurs (' .
                        $object->getClassement() . '/' . $object->getNbJoueursOuEquipes() . ')');
            }

            // dates tournois,1er tour et inscriptions
            // faut avoir les 2 dates de renseignées (1er tour et début)
            if (!($object->getDateDebutTournoi() == null || $object->getDateTournoi() == null)) {
                $nbJours = $object->getDateDebutTournoi()->diff($object->getDateTournoi())->days;
                if ($nbJours > 3) {
                    $context->addViolation(
                            'La date du 1er tour ' .
                            $object->getDateDebutTournoi()->format('d/m/Y') .
                            ' a ' . ($nbJours + 1) . ' jours d\'écart (plus de 4) avec la date du tournoi : ' .
                            $object->getDateTournoi()->format('d/m/Y')
                    );
                }
            }
            // date limite d'inscription
            if (!($object->getDateLimiteInscription() == null || $object->getDateTournoi() == null) && $object->getDateLimiteInscription() > $object->getDateTournoi()) {
                $context->addViolation(
                        'La date limite d\'inscription (' .
                        $object->getDateLimiteInscription()->format('d/m/Y') .
                        ') ne doit pas être supérieure à la date du tournoi (' .
                        $object->getDateTournoi()->format('d/m/Y') . ')'
                );
            }
            // date d'inscription
            if (!($object->getDateInscription() == null || $object->getDateTournoi() == null) && $object->getDateInscription() > $object->getDateTournoi()) {
                $context->addViolation(
                        'La date d\'inscription (' .
                        $object->getDateInscription()->format('d/m/Y') .
                        ') ne doit pas être supérieure à la date du tournoi (' .
                        $object->getDateTournoi()->format('d/m/Y') . ')'
                );
            }
        }
        ));
    }

    function getId() {
        return $this->id;
    }

    function getJournee() {
        return $this->journee;
    }

    function getTournoiInfos() {
        $tournoiInfos = '';
        $tournoiInfos .= ( is_null($this->getDateTournoi()) ? '' : $this->getDateTournoi()->format('d/m/Y'));
        $tournoiInfos .= ( is_null($this->commentaire) ? '' : ', ' . $this->commentaire);
        $tournoiInfos .= ( is_null($this->organisateur) ? '' : ', ' . $this->organisateur);
        $tournoiInfos .= ( is_null($this->bowling) ? '' : ', ' . $this->bowling);
        return $tournoiInfos;
    }

    function getDateTournoi() {
        return $this->dateTournoi;
    }

    function getDateDebutTournoi() {
        return $this->dateDebutTournoi;
    }

    function getDateLimiteInscription() {
        return $this->dateLimiteInscription;
    }

    function getDateInscription() {
        return $this->dateInscription;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function getPrix() {
        return $this->prix;
    }

    function getBowling() {
        return $this->bowling;
    }

    function getOrganisateur() {
        return $this->organisateur;
    }

    function getType() {
        return $this->type;
    }

    function getNbJoueursOuEquipes() {
        return $this->nbJoueursOuEquipes;
    }

    function getClassement() {
        return $this->classement;
    }

    function getFinale() {
        return $this->finale;
    }

    function getFinaleJouee() {
        return $this->finaleJouee;
    }

    function getCreeLe() {
        return $this->creeLe;
    }

    function getModifieLe() {
        return $this->modifieLe;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setJournee($journee) {
        $this->journee = $journee;
    }

    function setDateTournoi($dateTournoi) {
        $this->dateTournoi = $dateTournoi;
    }

    function setDateDebutTournoi($dateDebutTournoi) {
        $this->dateDebutTournoi = $dateDebutTournoi;
    }

    function setDateLimiteInscription($dateLimiteInscription) {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    function setDateInscription($dateInscription) {
        $this->dateInscription = $dateInscription;
    }

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    function setPrix($prix) {
        $this->prix = $prix;
    }

    function setBowling($bowling) {
        $this->bowling = $bowling;
    }

    function setOrganisateur($organisateur) {
        $this->organisateur = $organisateur;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setNbJoueursOuEquipes($nbJoueursOuEquipes) {
        $this->nbJoueursOuEquipes = $nbJoueursOuEquipes;
    }

    function setClassement($classement) {
        $this->classement = $classement;
    }

    function setFinale($finale) {
        $this->finale = $finale;
    }

    function setFinaleJouee($finaleJouee) {
        $this->finaleJouee = $finaleJouee;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

}
