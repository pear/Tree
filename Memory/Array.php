<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
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
    *   this is the internal id that will be assigned if no id is given
    *   it simply counts from 1, so we can check if( $id ) i am lazy :-)
    */
    var $_id = 1;
    
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
            $theData = array(&$this->_array);
            $this->_setup($theData);
        }
                              
        return $this->data;
    }
             
    /**
    *   we modify the $this->_array in here, we also add the id
    *   so methods like 'add' etc can find the elements they are searching for,
    *   if you dont like your data to be modified dont pass them as reference!
    */
    function _setup( &$array , $parentId=0 )
    {
        foreach( $array as $nodeKey=>$aNode )
        {
            $newData = $aNode;
            if( !$newData['id'] )
            {
                $newData['id'] = $this->_id++;
                $array[$nodeKey]['id'] = $newData['id'];
            }

            $newData['parentId'] = $parentId;
            $children = null;
            foreach( $newData as $key=>$val ) // remove the 'children' array, since this is only info for this class
            {
                if( $key=='children' )
                {
                    unset($newData[$key]);
                }
            }

            $this->data[] = $newData;
            if( $aNode['children'] )
                $this->_setup( $array[$nodeKey]['children'] , $newData['id'] );
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
                                       
    /**
    *
    */
    function add( $data , $parentId , $previousId=0 )
    {
        $data['id'] = $this->_id++;
        $data['parentId'] = $parentId;
        $this->data[] = $data;

        // add the element itself also in the source array, where we actually read the structure from
        //$path = $this->getPathById($parentId);
        array_walk($this->_array['children'],array(&$this,'_add'),array($data,$parentId));

        //$this->_array
        return $data['id'];
    }

    // this one was a real quicky !!!
    function _add( &$val , $key , $data )
    {
        if( $val['id']==$data[1] )
            $val['children'][] = $data[0];
        else    // if we havent found the new element go on searching
        {
            if( $val['children'] )
                array_walk($val['children'],array(&$this,'_add'),$data);
        }
    }

} // end of class
?>