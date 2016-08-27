<?php

namespace CHRIST\Common\Kernel\Modules\Import\Excel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColumnFilter
 *
 * @author glr735
 */
class ColumnFilter implements \PHPExcel_Reader_IReadFilter 
{ 
    /**
     * List of column is allowed
     * @var array
     */
    private $columnAllowed = array();
    
    /**
     * Start row filter
     * @var int
     */
    private $rowStart = 0;

    /**
     * Return false if $column isn't in columns allowed
     * @param string $column
     * @param int $row
     * @param string $worksheetName
     * @return boolean
     */
    public function readCell($column, $row, $worksheetName = '')
    { 
        if ($row >= $this->rowStart && in_array($column, $this->columnAllowed)) { 

            return true; 
        }
        
        return false; 
    }

    /**
     * Set columns allowed
     * 
     * @param array $columnAllowed
     */
    public function setColumnAllowed($columnAllowed = array())
    {
    	$this->columnAllowed = $columnAllowed;
    }

    /**
     * Set start row
     * 
     * @param integer $rowAllowed
     */
    public function setRowAllowed($rowAllowed = 0)
    {
    	$this->rowStart = $rowAllowed;
    }
} 
