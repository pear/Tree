<?php
# i think this class should go somewhere in a common PEAR-place,
# because a lot of classes use options, at least PEAR::DB does
# but since it is not very fancy to crowd the PEAR-namespace too much i dont know where to put it yet :-(

//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Wolfram Kriesing <wolfram@kriesing.de>                      |
// +----------------------------------------------------------------------+
//
//  $Id$

require_once('PEAR.php');

/**
*   this class only defines commonly used methods, etc.
*   it is worthless without being extended
*
*   @package  myPEAR
*   @access   public
*   @author   Wolfram Kriesing <wolfram@kriesing.de>
*
*/
class Tree_Options extends PEAR
{
    /**
    *   @var    array   $options    you need to overwrite this array and give the keys, that are allowed
    */
    var $options = array();

    /**
    *   this constructor sets the options, since i normally need this and
    *   in case the constructor doesnt need to do anymore i already have it done :-)
    *
    *   @version    02/01/08
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      boolean true if loggedIn
    */
    function Tree_Options( $options=array() )
    {
        if( is_array($options) && sizeof($options) )
            foreach( $options as $key=>$value )
                $this->setOption( $key , $value );
    }

    /**
    *
    *   @access     public
    *   @author     Stig S. Baaken
    *   @param      
    */
    function setOption( $option , $value )
    {
        if (isset($this->options[$option])) {
            $this->options[$option] = $value;
            return true;
        }
        return false;
# may be better raise a PEAR error here :-)
    }

    /**
    *   set a number of options which are simply given in an array
    *
    *   @access     public
    *   @author
    *   @param
    */
    function setOptions( $options )
    {
        if( is_array($options) && sizeof($options) )
            foreach( $options as $key=>$value )
                $this->setOption( $key , $value );
    }

    /**
    *
    *   @access     public
    *   @author     copied from PEAR: DB/commmon.php
    *   @param      boolean true on success
    */
    function getOption($option)
    {
        if (isset($this->options[$option])) {
            return $this->options[$option];
        }
# may be better raise a PEAR error here :-)
#        return $this->raiseError("unknown option $option");
        return false;
    }

} // end of class
?>