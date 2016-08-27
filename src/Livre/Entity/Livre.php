<?php

namespace CHRIST\Modules\Livre\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CHRIST\Modules\Livre\Repository\LivreRepository")
 * @ORM\Table(name="liv_livre")
 * @author Christian Alcon
 */
class Livre extends \CHRIST\Common\Entity\Master\AbstractEntity {
    // jointure ManyToMany bi directionnel :
    // http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html

    /**
     * @ORM\Id
     * @ORM\Column(name="livre_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="CHRIST\Modules\Vocabulaire\Entity\Mot", inversedBy="livres", fetch="LAZY", cascade={"persist"})
     * RM\JoinTable(name="vocab_mot",
     *      @ORM\JoinColumn(name="vocab_mot_id", referencedColumnName="livre_id")
     * @var mots
     */
    private $mots;

    function getMots() {
        return $this->mots;
    }

    function setMots($mots) {
        $this->mots = $mots;
    }

    /**
     * @ORM\ManyToMany(targetEntity="ecrivain", inversedBy="livres")
     * @ORM\JoinTable(name="liv_auteur",
     *      joinColumns={@ORM\JoinColumn(name="liv_aut_livre_id", referencedColumnName="livre_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="liv_aut_ecri_id", referencedColumnName="liv_ecri_id")}
     *      )
     * @var auteurs
     */
    private $auteurs;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="livres", fetch="LAZY")
     * @ORM\JoinColumn(name="livre_theme_id", referencedColumnName="liv_them_id")
     * @ORM\OrderBy({"nom" = "ASC", "prenom" = "ASC"})
     * @var Theme
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity="Editeur", inversedBy="livres", fetch="LAZY")
     * @ORM\JoinColumn(name="livre_editeur_id", referencedColumnName="liv_edit_id")
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity="Couverture"), inversedBy="couverture", fetch="LAZY")
     * @ORM\JoinColumn(name="livre_couverture_id", referencedColumnName="liv_couv_id")
     */
    private $couverture;

    public function __construct() {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function __toString() {
        return is_null($this->titre) ? '' : $this->titre;
    }

    /**
     * @ORM\Column(name="livre_titre", type="string")
     */
    private $titre;

    /**
     * @ORM\Column(name="livre_anneeCopyright", type="integer")
     */
    private $anneeCopyright;

    /**
     * @ORM\Column(name="livre_date_achat", type="datetime")
     */
    private $dateAchat;

    /**
     * @ORM\Column(name="livre_prix_achat", type="decimal")
     */
    private $prixAchat;

    /**
     * @ORM\Column(name="livre_nb_pages", type="integer")
     */
    private $nbPages;

    /**
     * @ORM\Column(name="livre_remarques", type="text")
     */
    private $remarques;

    /**
     * @ORM\Column(name="livre_a_lire", type="boolean")
     */
    private $aLire = true;

    /**
     * @ORM\Column(name="livre_vocabulaire", type="boolean")
     */
    private $vocabulaire;

    /**
     * @ORM\Column(name="livre_en_stock", type="boolean")
     */
    private $enStock = true;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="livre_created_on", type="datetime")
     */
    private $creeLe;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="livre_updated_on", type="datetime")
     */
    private $modifieLe;

    function getId() {
        return $this->id;
    }

    function getAuteurs() {
        return $this->auteurs;
    }

    function getTheme() {
        return $this->theme;
    }

    function getEditeur() {
        return $this->editeur;
    }

    function getCouverture() {
        return $this->couverture;
    }

    function getTitre() {
        return toUTF8($this->titre);
    }

    function getAnneeCopyright() {
        return $this->anneeCopyright;
    }

    function getDateAchat() {
        return $this->dateAchat;
    }

    function getPrixAchat() {
        return $this->prixAchat;
    }

    function getNbPages() {
        return $this->nbPages;
    }

    function getRemarques() {
        return toUTF8($this->remarques);
    }

    function getALire() {
        return $this->aLire;
    }

    function getVocabulaire() {
        return $this->vocabulaire;
    }

    function getEnStock() {
        return $this->enStock;
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

    function setAuteurs($auteurs)
    {
        $this->auteurs = $auteurs;
    }

    function setTheme(Theme $theme) {
        $this->theme = $theme;
    }

    function setEditeur($editeur) {
        $this->editeur = $editeur;
    }

    function setCouverture($couverture) {
        $this->couverture = $couverture;
    }

    function setTitre($titre) {
        $this->titre = $titre;
    }

    function setAnneeCopyright($anneeCopyright) {
        $this->anneeCopyright = $anneeCopyright;
    }

    function setDateAchat($dateAchat) {
        $this->dateAchat = $dateAchat;
    }

    function setPrixAchat($prixAchat) {
        $this->prixAchat = $prixAchat;
    }

    function setNbPages($nbPages) {
        $this->nbPages = $nbPages;
    }

    function setRemarques($remarques) {
        $this->remarques = $remarques;
    }

    function setALire($aLire) {
        $this->aLire = $aLire;
    }

    function setVocabulaire($vocabulaire) {
        $this->vocabulaire = $vocabulaire;
    }

    function setEnStock($enStock) {
        $this->enStock = $enStock;
    }

    function setCreeLe($creeLe) {
        $this->creeLe = $creeLe;
    }

    function setModifieLe($modifieLe) {
        $this->modifieLe = $modifieLe;
    }

//
//    public function loadValidator(ClassMetadata $metadata, $attribute) {
//        switch ($attribute) {
//            case 'indexNouv':
//                $metadata->addPropertyConstraint('indexNouv', new Assert\NotNull());
//                $metadata->addPropertyConstraint('indexNouv', new Assert\NotBlank());
//                $metadata->addPropertyConstraint('indexNouv', new Assert\Type(array('type' => 'integer',
//                    'message' => ' : Vous avez saisi "{{ value }}"<br /><br />Veuillez ne saisir que des chiffres,<br />éventuellement séparés par des espaces')));
//                $metadata->addPropertyConstraint('indexNouv', new Assert\GreaterThan(array('value' => 0,
//                    'message' => '<br /><br />L\'index saisi doit être supérieur à zéro')));
//                $metadata->addPropertyConstraint('indexNouv', new Assert\LessThan(array('value' => 250000,
//                    'message' => '<br /><br />Le kilométrage saisi doit être inférieur à 250 000 kms<br />Vous pouvez néanmoins enregistrer votre saisie sous forme de commentaires')));
//// l'index saisi doit être > à l'ancien
//                $metadata->addConstraint(new Assert\Callback(function ($object, ExecutionContextInterface $context) {
//                    if ($object->getIndexNouv() < $object->getIndexAnc()) {
//                        $context->addViolation(
//                                $object->getErrorMessageGFE('Le kilométrage saisi est inférieur à celui fourni lors du dernier enregistrement')
//                        );
//                    }
//// l'index saisi doit être > à la limite journalière :
//// RG : 200 * NbJour entre deux saisies
//                    $aujourdhui = new \DateTime;
//                    $nbJours = $aujourdhui->diff($object->getDateAnc())->days;
//                    $kmMax = ( 200 * $nbJours ) + $object->getIndexAnc();
//
//                    if ($object->getIndexNouv() > $kmMax) {
//                        $context->addViolation(
//                                $object->getErrorMessageGFE('Il ne doit pas y avoir en moyenne plus de 200 Km par jour entre 2 saisies')
//                        );
//                    }
//                }));
//                break;
//        }
//    }
//
//    /**
//     * Contrôles secondaies (RG application)
//     * message d'info sur les gestionnaires de flotte
//     * @param string $typeError Prefix message
//     * @return string
//     */
//    private function getErrorMessageGFE($typeError = null) {
//        $messInfoGestionnaire = is_null($typeError) ? '' : '<br /><b>' . $typeError . '</b>';
//        $messInfoGestionnaire .= '<br /><br /><br /><b>Remarque</b>';
//        $messInfoGestionnaire .= '<br />Si vous êtes sûr de cet index, vous pouvez demander une correction à votre gestionnaire en téléphonant :';
//        $messInfoGestionnaire .= '<br />pour <b>OVL</b> au 0825 832 863';
//        $messInfoGestionnaire .= '<br />pour <b>ALD</b> au 0800 371 371 + choix 2';
//        $messInfoGestionnaire .= '<br /><br /><br />ATTENTION : pour que cet appel soit pris en compte vous devez appeler pendant la période de saisie des index kilométriques qui se situe en général entre le 25 du mois et le 5 du mois suivant.';
//        $messInfoGestionnaire .= '<br /><br />Ainsi, dès le mois prochain vous pourrez saisir sans difficulté. ';
//        $messInfoGestionnaire .= '<hr class="separeMess">';
//
//        return $messInfoGestionnaire;
//    }
}
