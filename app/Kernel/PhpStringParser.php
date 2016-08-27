<?php

namespace CHRIST\Common\Kernel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Eval PHP expression in text file
 *
 * @author glr735
 */
class PhpStringParser
{
    public function __construct($variables = array())
    {
        ;
    }

    protected function eval_block($matches)
    {
        $eval_end = '';

        if( $matches[1] == '<?=' || $matches[1] == '<?php=' )
        {
            if( $matches[2][count($matches[2]-1)] !== ';' )
            {
                $eval_end = ';';
            }
        }

        $return_block = '';

        eval('$return_block = ' . $matches[2] . $eval_end);

        return $return_block;
    }

    public function parse($string)
    {
        return preg_replace_callback('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', array(&$this, 'eval_block'), $string);
    }
}
