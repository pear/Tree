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

require_once('Tree/Options.php');

define("TREE_ERROR",                    -1);
define("TREE_ERROR_INVALID_PARENT",     -2);
define("TREE_ERROR_HAS_CHILDREN",       -3);

/**
*
*
*   @access     public
*   @author     Wolfram Kriesing <wolfram@kriesing.de>
*   @version    2001/06/27
*   @package    Tree
*/
class Tree_Common extends Tree_Options
{
# i assume every class needs some options to be set, hope that is ok

    /**
    *   @access public
    *   @var    array   saves the options passed to the constructor
    */
    var $options =  array(
                            // those keyName maps are supposed to name the keys that are used in the internal array (data)
                            // if you prefer different names, or your db-columns have different names
                            // then define them here
                            // be sure to set i.e. the columnNameMaps for the DB so that it works with those maps
                            // the default is as you see given
# FIXXME, finish this and let the user define its own key maps, use that throughout the entire class
# i didnt do it yet, since it requires quite some testing and i didnt need it yet, i just use the columnNameMaps in the DB for now
# but one fine day ...
/*                            'keyNameMaps'   =>  array(
                                                'id'            =>  'id',
                                                'parentId'      =>  'parentId',
                                                'prevId'        =>  'prevId',
                                                'name'          =>  'name'  //
                                                )
*/
                            );


    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getChildId( $id )
    {
        $child = $this->getChild( $id );
        return $child['id'];
    }

    /**
    *   get the ids of the children of the given element
    *
    *   @version    2002/02/06
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      integer $id             ID of the element that the children shall be retreived for
    *   @return     mixed   an array of all the ids of the children of the element with id=$id,
    *                       or false if there are no children
    */
    function getChildrenIds( $id )
    {
        if( !($children = $this->getChildren( $id )) )  // returns false if no children exist
            return array();                         // return an empty array, if you want to know if there are children, use hasChildren

        if( $children && sizeof($children) )
        {
            foreach( $children as $aChild )
                $childrenIds[] = $aChild['id'];
        }

        return $childrenIds;
    }

    /**
    *   get the id of the parent for the given element
    *
    *   @version    2002/01/18
    *   @access     public
    *   @param      integer $id the id of the element for which the parentId shall be retreived
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getParentId( $id )
    {
        $parent = $this->getParent( $id );
        return $parent['id'];
    }

    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getNextId( $id )
    {
        $next = $this->getNext( $id );
        return $next['id'];
    }

    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getPreviousId( $id )
    {
        $previous = $this->getPrevious( $id );
        return $previous['id'];
    }

    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getLeftId( $id )
    {
        $left = $this->getLeft( $id );
        return $left['id'];
    }

    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getRightId( $id )
    {
        $right = $this->getRight( $id );
        return $right['id'];
    }

    /**
    *
    *
    *   @version    2002/01/18
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    */
    function getFirstRootId()
    {
        $firstRoot = $this->getFirstRoot();
        return $firstRoot['id'];
    }




    #
    #   abstract methods, those should be overwritten by the implementing class
    #

    /**
    *   gets the path to the element given by its id
    *
    *   @abstract
    *   @version    2001/10/10
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      mixed   $id     the id of the node to get the path for
    *   @return     array   this array contains all elements from the root to the element given by the id
    *
    */
    function getPath( $id )
    {
    } // end of function

    /**
    *   get the level, which is how far below the root the element with the given id is
    *
    *   @abstract
    *   @version    2001/11/25
    *   @access     public
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      mixed   $id     the id of the node to get the level for
    *
    */
    function getLevel( $id )
    {
    } // end of function



}
?>