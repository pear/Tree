<?php
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

require_once('Tree/Error.php');

/**
*   EXPERIMENTAL
*
*   @access     public
*   @author     Wolfram Kriesing <wolfram@kriesing.de>
*   @version    2002/08/30
*   @package    Tree
*/
class Tree_Memory_Array
{

    var $data = array();

    /**
    *   set up this object
    *
    *   @version    2002/08/30
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      string  $dsn    the path on the filesystem
    *   @param      array   $options  additional options you can set
    */
    function Tree_Memory_Array( &$array , $options=array() )
    {
        $this->_array = &$array;
        $this->_options = $options; // not in use currently
    } // end of function

    /**
    *
    *
    *   @version    2002/08/30
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @return     boolean     true on success
    */
    function setup()
    {
        unset($this->data);                         // unset the data to be sure to get the real data again, no old data
        if( is_array($this->_array) )
        {
            $this->data[0] = null;
            $this->_setup(array($this->_array));
        }

        return $this->data;
    }

    var $_id = 0;
    function _setup( $array , $parentId=0 )
    {
        foreach( $array as $aNode )
        {
            $this->_id++;                                                                          
            $newData = array('id'=>$this->_id,'name'=>$aNode['name'],'parentId'=>$parentId);
            foreach( $aNode as $key=>$val )
                if( !is_array($val) )
                    $newData[$key] = $val;
            $this->data[] = $newData;
            //$this->data[] = array('id'=>$this->_id,'name'=>$aNode['name'],'parentId'=>$parentId);
            if( $aNode['children'] )
                $this->_setup( $aNode['children'] , $this->_id );
        }
    }


    /**
    *
    *
    *   @access     private
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function _throwError( $msg , $line , $mode=null )
    {
        return new Tree_Error( $msg , $line , __FILE__ , $mode , $this->db->last_query );
    }

    /**
    *   prepare multiple results
    *
    *   @see        _prepareResult()
    *   @access     private
    *   @version    2002/03/03
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function _prepareResults( $results )
    {
        $newResults = array();
        foreach( $results as $aResult )
            $newResults[] = $this->_prepareResult($aResult);
        return $newResults;
    }

    /**
    *   map back the index names to get what is expected
    *
    *   @access     private
    *   @version    2002/03/03
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function _prepareResult( $result )
    {
        $map = $this->getOption('columnNameMaps');

        if( $map )
        foreach( $map as $key=>$columnName )
        {
            $result[$key] = $result[$columnName];
            unset($result[$columnName]);
        }
        return $result;
    }


} // end of class
?>
