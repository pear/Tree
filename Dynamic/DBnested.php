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
// | Authors:                                                             |
// +----------------------------------------------------------------------+
//
//  $Id$

/**
*
*
*   @access     public
*   @package    Tree
*/
class Tree_Dynamic_DBnested extends Tree_Common
{

# the defined methods here are proposals for the implementation,
# they are named the same, as the methods in the "Memory" branch, if possible
# it would be cool to keep the same naming and if the same parameters would be possible too
# then it would be even better, so one could easily change from any kind of
# tree-implementation to another, without changing the source code, only the setupXXX would need to be changed

    /**
    *   add a new element to the tree
    *
    *   @access     public
    *   @author
    *   @param      array   $newValues this array contains the values that shall be inserted in the db-table
    *   @return
    */
    function add( $newValues )
    {
# FIXXME to be done :-)
    } // end of function

    /**
    *   remove a tree element
    *
    *   @access     public
    *   @author
    *   @param      integer $id the id of the element to be removed
    *   @return
    */
    function remove( $id )
    {
# FIXXME to be done :-)
    } // end of function

    /**
    *   move a tree element
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function move( ??? )
    {
# FIXXME to be done :-)
    } // end of function

    /**
    *   copy a subtree/node/...
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function copy( ??? )
    {
# FIXXME to be done :-)
    } // end of function


    /**
    *   get the first element in the root
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function getFirstRoot(  )
    {
# FIXXME to be done :-)
    } // end of function

    /**
    *
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function getElement(  )
    {
# FIXXME to be done :-)
    } // end of function

/*
    further more the following methods could/should be implemented too:
    getParent
    getChild
    getChildren
    getPath
    getNext
    getPrevious
    getLeft
    getRight

*/


}