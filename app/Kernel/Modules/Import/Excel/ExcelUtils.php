<?php

namespace CHRIST\Common\Kernel\Modules\Import\Excel;

use CHRIST\Common\Kernel\Modules\Import\Excel\ColumnFilter;
use CHRIST\Common\Kernel\Helpers\PhpHelpers;

/**
 * Description of ExcelUtils
 *
 * @author glr735
 */
class ExcelUtils {

    /**
     * Reader
     * @var \PHPExcel_Reader
     */
    private $reader;

    /**
     * PHPExcel
     * @var \PHPExcel
     */
    private $phpExcel;

    /**
     * Index sheet
     * @var int
     */
    private $sheet;

    /**
     * Constructor
     * @param string  $file
     * @param array   $filter
     * @param integer $sheet
     * @throws \Exception
     */
    public function __construct($file = '', $filter = array(), $sheet = 0) {

        try {

            $this->sheet = $sheet;

            $this->reader = \PHPExcel_IOFactory::createReaderForFile($file);
            if (!empty($filter)) {
                $this->reader->setReadFilter($this->setFilter($filter));
            }

            $this->reader->setReadDataOnly(true);

            $this->phpExcel = $this->reader->load($file);
        } catch (\Exception $e) {

            throw new \Exception(__CLASS__ . ' => The file "' . $file . '" can\'t be loaded : ' . $e->getMessage());
        }
    }

    /**
     * Valid cell
     * @param array $mapping
     * @return boolean
     */
    public function validateCells($mapping = '', &$errorMessage = '') {

        foreach ($mapping as $key => $value) {
            if (preg_match('/^' . $value . '$/', $tmp = PhpHelpers::deleteNewLine($this->phpExcel->getSheet($this->sheet)->getCell($key)->getValue())) === 0) {
                $errorMessage = __CLASS__ . ' => Validation error cell (cell "' . $key . '") : received: "' . $tmp . '", expected: "' . $value . '" => ' . PhpHelpers::arrayToString($mapping);
                return false;
            }
        }

        return true;
    }

    /**
     * Convert file contents to array
     * @param int $start Index begins selection
     * @param int $end Size of array
     *                 null => full
     *                 -n number line removed at the end of file
     * @param array $mapping
     * @return array
     */
    public function getArray($start = 0, $end = null, $mapping = array()) {
        $data = array_slice($this->phpExcel->getSheet($this->sheet)->toArray(null, false, true, false), $start - 1);

        // Remove empty line 
        $data = array_filter(array_map(function($value) {
                    foreach ($value as $val) {
                        if (!empty($val))
                            return $value;
                    }
                    return null;
                }, $data));

        // Remove line at the end of file
        //$data = array_slice($data, 0, $end);
        // Get column select in mapping
        $index = array();
        foreach ($mapping as $value) {
            $index[] = ord(strtoupper($value)) - 65;
        }
        $index = array_flip($index);

        // Filter column
        array_walk($data, function(&$v, $k) use ($index) {
            $v = PhpHelpers::reIndexArray(array_intersect_key($v, $index));
        });

        // Auto type data
        array_walk_recursive($data, function(&$v, $k) {
            $v = PhpHelpers::autoType($v);
        });

        return $data;
    }

    /**
     * Init filter column
     * Column can't be selected has null value
     * @param array $afilter
     */
    private function setFilter($afilter = array()) {

        $filter = new ColumnFilter();
        $filter->setColumnAllowed($afilter);

        return $filter;
    }

}
