<?php

namespace CHRIST\Common\Kernel\Helpers;

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
class PhpHelpers
{

    /**
     * Reindex an array
     * @static
     * @param  array
     * @return array
     */
    public static function reIndexArray($array)
    {
        return array_values($array);
    }

    /**
     * True if $haystack begins by $needle
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    /**
     * True if $haystack ends by $needle
     * @static
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * Convert an array to string
     * @static
     * @param  array  $array
     * @return string
     */
    public static function arrayToString($elt = array())
    {
        return '{' . implode(', ', array_map(function ($v, $k) {
                            return $k . '=' . $v;
                        }, $elt, array_keys($elt))) . '}';
    }

    /**
     * Remove line 
     * @example \n \r\n
     * @param string $str
     * @return string
     */
    public static function deleteNewLine($str = '')
    {
        return trim(preg_replace("/(\r\n|\n|\r|\t|\\r\\n|\\n|\\r|\\t)/", '', $str));
    }

    /**
     * Delete an directory even if the folder is not empty
     * @param string $dir
     */
    public static function deleteDir($dir)
    {
        $iterator = new \RecursiveDirectoryIterator($dir);

        foreach (new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
            if ($file->isDir()) {
                @rmdir($file->getPathname());
            } else {
                @unlink($file->getPathname());
            }
        }

        @rmdir($dir);
    }

    /**
     * Force type of variable
     * @param mixed $value
     * @return mixed
     */
    public static function autoType($value)
    {
        if ($value === true || $value === 'true' || $value === false || $value === 'false') {

            return (bool) $value;
        }

        if (is_array($value))
            return $value;

        if (preg_match('/^[+-]?\d+[\.\,]?\d*$/', $value)) {

            $value = str_replace(',', '.', $value);
        }

        if (is_numeric($value)) {
            // pour tansformer une variable issue d'un input en numérique, car stockée en chaîne
            return $value + 0;
        }

        return $value;
    }

    /**
     * Return DateTime object from timestamp Excel
     * @param int $timestamp
     * @return \DateTime
     */
    public static function dateExcel($timestamp)
    {
        if ($timestamp <= 0 || !preg_match('/^[-+]?(\d*[.])?\d+$/', $timestamp))
            return null;

        $timestamp = ($timestamp - 25569) * 86400;
        
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        
        return $date;
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    public static function castObject($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    /**
     * Generate one random string, used mainly generate salt password
     * @param int $length 6 by default
     * @return string
     */
    public static function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
