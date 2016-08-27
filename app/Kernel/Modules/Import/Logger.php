<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CHRIST\Common\Kernel\Modules\Import;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log action in wrapper
 *
 * @ORM\Entity
 * @ORM\Table(name="bowl_log_import")
 * @author glr735/ezs824
 */
class Logger {

    /**
     * @ORM\Id
     * @ORM\Column(name="bowl_log_id", type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Name of entry in import descriptor
     * @ORM\Column(name="bowl_log_fichier_source", type="string")
     * @var string
     */
    private $typeEntry;

    /**
     * Name of physical file
     * @ORM\Column(name="bowl_log_nom_physique", type="string")
     * @var string
     */
    private $physicalName;

    /**
     * Freshness of the imported data 
     * @ORM\Column(name="bowl_log_date_import", type="datetime")
     * @var \DateTime
     */
    private $dateImport;

    /**
     * Date/time when traitement is started
     * @ORM\Column(name="bowl_log_date_debut", type="datetime")
     * @var \DateTime
     */
    private $dateStart;

    /**
     * Date/time when traitement is finished
     * @ORM\Column(name="bowl_log_date_fin", type="datetime")
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * Number row in source file.
     * Used only by ExcelWrapper
     * @ORM\Column(name="bowl_log_nombre_ligne_source", type="integer")
     * @var int
     */
    private $numberDataSource;

    /**
     * Number row deleted in database when data is imported
     * @ORM\Column(name="bowl_log_nombre_ligne_supprimee", type="integer")
     * @var int
     */
    private $numberDataDeleted;

    /**
     * Number row inserted in database when data is imported
     * @ORM\Column(name="bowl_log_nombre_ligne_inseree", type="integer")
     * @var int
     */
    private $numberDataImported;

    /**
     * Import mode (INSERT, CANCEL AND REPLACE, CANCEL AND REPLACE ALL)
     * @ORM\Column(name="bowl_log_mode_import", type="string")
     * @var string
     */
    private $importMode;

    /**
     * Result of import
     * @ORM\Column(name="bowl_log_rapport", type="text")
     * @var string
     */
    private $report;

    /**
     * Additional information being processed (filtered data, ...)
     * @ORM\Column(name="bowl_log_commentaire", type="text")
     * @var string
     */
    private $comment;

    function __construct($typeEntry) {
        $this->typeEntry = $typeEntry;
        $this->dateStart = new \DateTime();
        $this->numberDataSource = 0;
        $this->numberDataDeleted = 0;
        $this->numberDataImported = 0;
        $this->importMode = 'Ajout/remplace';
    }

    public function getId() {
        return $this->id;
    }

    public function getTypeEntry() {
        return $this->typeEntry;
    }

    public function getPhysicalName() {
        return $this->physicalName;
    }

    public function getDateImport() {
        return $this->dateImport;
    }

    public function getDateStart() {
        return $this->dateStart;
    }

    public function getDateEnd() {
        return $this->dateEnd;
    }

    public function getNumberDataSource() {
        return $this->numberDataSource;
    }

    public function getNumberDataDeleted() {
        return $this->numberDataDeleted;
    }

    public function getNumberDataImported() {
        return $this->numberDataImported;
    }

    public function getImportMode() {
        return $this->importMode;
    }

    public function getReport() {
        return $this->report;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTypeEntry($typeEntry) {
        $this->typeEntry = $typeEntry;
    }

    public function setPhysicalName($physicalName) {
        $this->physicalName = $physicalName;
    }

    public function setDateImport(\DateTime $dateImport) {
        $this->dateImport = $dateImport;
    }

    public function setDateStart(\DateTime $dateStart) {
        $this->dateStart = $dateStart;
    }

    public function setDateEnd(\DateTime $dateEnd) {
        $this->dateEnd = $dateEnd;
    }

    public function setNumberDataSource($numberDataSource) {
        $this->numberDataSource = $numberDataSource;
    }

    public function setNumberDataDeleted($numberDataDeleted) {
        $this->numberDataDeleted = $numberDataDeleted;
    }

    public function setNumberDataImported($numberDataImported) {
        $this->numberDataImported = $numberDataImported;
    }

    public function setImportMode($importMode) {
        if (is_numeric($importMode)) {
            switch ($importMode) {

                case 0 :
                    $importMode = 'INSERT';
                    break;

                case 1 :
                    $importMode = 'CANCEL AND REPLACE';
                    break;

                case 2 :
                    $importMode = 'CANCEL AND REPLACE ALL';
                    break;
            }
        }

        $this->importMode = $importMode;
    }

    public function setReport($report) {
        if (is_array($report)) {
            $report = http_build_query($report, '', "\n");
        }

        $this->report = $report;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

}
