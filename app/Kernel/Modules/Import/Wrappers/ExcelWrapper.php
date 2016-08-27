<?php

namespace CHRIST\Common\Kernel\Modules\Import\Wrappers;

use CHRIST\Common\Kernel\Modules\Import\Interfaces\IImportWrapper;
use CHRIST\Common\Kernel\Modules\Import\Excel\ExcelUtils;
use CHRIST\Common\Kernel\Modules\Import\Wrappers\AbstractWrapper;

/**
 * Description of FtpWrapper
 *
 * @author glr735
 */
class ExcelWrapper extends AbstractWrapper implements IImportWrapper
{
    /**
     * Path of file
     * @var string
     */
    private $file;

    /**
     * Mapping column selected
     * @var array
     */
    private $colMap = array();

    /**
     * Two column (second is optional)
     * [start => begin selection, end => size of selection]
     * @see ExcelUtils
     * @var array
     */
    private $rowRange = array('start' => 1, 'end' => null);

    /**
     * Entity import manager
     * @var string
     */
    private $entityManager = null;

    /**
     * Index sheet
     * @var integer
     */
    protected $sheet = 0;

    /**
     * Constructor
     *
     * @param \CHRIST\Common\Kernel\Modules\Import\Parameter $parameters
     * @throws \Exception 
     */
    public function __construct(\CHRIST\Common\Kernel\Modules\Import\Parameter $parameters)
    {
        $this->parameters   = $parameters;
        try {
            $this->file     = $this->parameters->getSettings('file');
        } catch (\Exception $ex) {
            throw new \Exception(__CLASS__ . ' => "file" should not be empty', 1);
        }
        
        try {
            $this->colMap   = $this->parameters->getSettings('header');
        } catch (\Exception $ex) {
            throw new \Exception(__CLASS__ . ' => "header" should not be empty', 1);
        }
        
        try {
            $this->rowRange = array_merge($this->rowRange, $this->parameters->getSettings('lines'));
        } catch (\Exception $ex) {
            $this->rowRange = array('start' => 1, 'end' => null);
        }

        try {
            $this->entityManager   = $this->parameters->getSettings('entityManager');
        } catch (\Exception $ex) {
            throw new \Exception(__CLASS__ . ' => "entityManager" should not be empty', 1);
        }
        
        try {
            $this->sheet   = $this->parameters->getSettings('sheet');
        } catch (\Exception $ex) {
            $this->sheet   = 0;
        }
    }
    
    /**
     * Import data
     * 
     * @return string
     */
    public function import() 
    {
        $errors = array();

        try {
            $util = new ExcelUtils($this->file, $this->getColumnIndex(), $this->sheet);

            $errorMessage = '';

            if (!$util->validateCells($this->colMap, $errorMessage)) {
                throw new \Exception($errorMessage, 1);
            }

            $arrayData = $util->getArray($this->rowRange['start'], $this->rowRange['end'], $this->getColumnIndex());

        } catch (\Exception $e) {
            return toUTF8($e->getMessage());
        }
        
        $manager = new $this->entityManager($this->parameters);

        try {
            $logger = $this->parameters->getSettings('importLogger');
            $logger->setNumberDataSource(count($arrayData) -1);
        } catch (\Exception $ex) {
            ;
        }

        return $arrayData;
    }

    /**
     * Index of column
     * @return array
     */
    private function getColumnIndex()
    {
        return array_map(function($value) { return substr($value, 0, 1); }, array_keys($this->colMap));
    }

}