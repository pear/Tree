<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
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
require_once('Tree/OptionsDB.php');

/**
 * common tree class, implements common functionality
 *
 * this class extends Tree_OptionsDB so every class that extends this one can
 * connect to a db and set options
 *
 * @access     public
 * @author     Wolfram Kriesing <wolfram@kriesing.de>
 * @version    2001/06/27
 * @package    Tree
 */
class Tree_Common extends Tree_OptionsDB
{
    /**
     * put proper value-keys are given in each class, depending
     * on the implementation only some options are needed or allowed,
     * see the classes which extend this one
     *
     * @access public
     * @var    array   saves the options passed to the constructor
     */
    var $options =  array();


    /**
     * @version    2002/01/18
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getChildId( $id )
    {
        $child = $this->getChild( $id );
        return $child['id'];
    }

    /**
     * get the ids of the children of the given element
     *
     * @version 2002/02/06
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   integer ID of the element that the children shall be
     *                  retreived for
     * @param   integer how many levels deep into the tree
     * @return  mixed   an array of all the ids of the children of the element
     *                  with id=$id, or false if there are no children
     */
    function getChildrenIds($id,$levels=1)
    {
        // returns false if no children exist
        if (!($children = $this->getChildren($id,$levels))) {
            return array();
        }
        // return an empty array, if you want to know
        // if there are children, use hasChildren
        if ($children && sizeof($children)) {
            foreach ($children as $aChild) {
                $childrenIds[] = $aChild['id'];
            }
        }

        return $childrenIds;
    }

    /**
     * gets all the children and grand children etc.
     *
     * @version 2002/09/30
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   integer ID of the element that the children shall be
     *                  retreived for
     *
     * @return  mixed   an array of all the children of the element with
     *                  id=$id, or false if there are no children
     */
     // FIXXXME remove this method and replace it by getChildren($id,0)
    function getAllChildren($id)
    {
        $retChildren = false;
        if ($children = $this->hasChildren($id)) {
            $retChildren = $this->_getAllChildren( $id );
        }
        return $retChildren;
    }

    /**
     * this method gets all the children recursively
     *
     * @see getAllChildren()
     * @version 2002/09/30
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   integer ID of the element that the children shall be
     *                  retreived for
     *
     * @return  mixed   an array of all the ids of the children of the element
     *                  with id=$id, or false if there are no children
     */
    function &_getAllChildren($id)
    {
        $retChildren = array();
        if ($children = $this->getChildren($id)) {
            foreach ($children as $key=>$aChild) {
                $retChildren[] = &$children[$key];
                $retChildren = array_merge($retChildren,
                        $this->_getAllChildren( $aChild['id'] ));
            }
        }
        return $retChildren;
    }

    /**
     * gets all the children-ids and grand children-ids
     *
     * @version 2002/09/30
     * @access  public
     * @author  Kriesing <wolfram@kriesing.de>
     * @param   integer ID of the element that the children shall
     *          be retreived for
     *
     * @return  mixed   an array of all the ids of the children of the element
     *                  with id=$id,
     *                  or false if there are no children
     */
    function getAllChildrenIds( $id )
    {
        $childrenIds = array();
        if( $allChildren = $this->getAllChildren($id) )
        {
            $childrenIds = array();
            foreach( $allChildren as $aNode )
                $childrenIds[] = $aNode['id'];
        }
        return $childrenIds;
    }

    /**
     * get the id of the parent for the given element
     *
     * @version 2002/01/18
     * @access  public
     * @param   integer the id of the element for which the parentId
     *                  shall be retreived
     * @author Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getParentId( $id )
    {
        $parent = $this->getParent( $id );
        return $parent['id'];
    }

    /**
     * this gets all the preceeding nodes, the parent and it's parent and so on
     *
     * @version 2002/08/19
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   integer the id of the element for which the parentId shall
     *                  be retreived
     * @return  array   of the parent nodes including the node with id $id
     */
    function getParents( $id )
    {
        $path = $this->getPath($id);
        $parents = array();
        if( sizeof($path) )
            foreach( $path as $aNode )
                $parents[] = $aNode;
        return $parents;
    }

    /**
     * get the ids of the parents and all it's parents and so on
     * it simply returns the ids of the elements returned by getParents()
     *
     * @see getParents()
     * @version 2002/08/19
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   integer $id the id of the element for which the parentId
     *          shall be retreived
     *
     * @return     array   of the ids
     */
    function getParentsIds( $id )
    {
        $parents = $this->getParents($id);
        $parentsIds = array();
        if( sizeof($parents) )
            foreach( $parents as $aNode )
                $parentsIds[] = $aNode['id'];
        return $parentsIds;
    }

    /**
     * @version    2002/01/18
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getNextId( $id )
    {
        $next = $this->getNext( $id );
        return $next['id'];
    }

    /**
     * @version    2002/01/18
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getPreviousId( $id )
    {
        $previous = $this->getPrevious( $id );
        return $previous['id'];
    }

    /**
     * @version    2002/01/18
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getLeftId( $id )
    {
        $left = $this->getLeft( $id );
        return $left['id'];
    }

    /**
     * @version    2002/01/18
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getRightId( $id )
    {
        $right = $this->getRight( $id );
        return $right['id'];
    }

    /**
     * @version    2002/04/16
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getFirstRootId()
    {
        $firstRoot = $this->getFirstRoot();
        return $firstRoot['id'];
    }

    /**
     * @version    2002/04/16
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     */
    function getRootId()
    {
        $firstRoot = $this->getRoot();
        return $firstRoot['id'];
    }

    /**
     * returns the path as a string
     *
     * @access  public
     * @version 2002/03/28
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   mixed   $id     the id of the node to get the path for
     * @param   integer If offset is positive, the sequence will
     *                  start at that offset in the array .  If
     *                  offset is negative, the sequence will start that far
     *                  from the end of the array.
     * @param   integer If length is given and is positive, then
     *                  the sequence will have that many elements in it. If
     *                  length is given and is negative then the
     *                  sequence will stop that many elements from the end of
     *                  the array. If it is omitted, then the sequence will
     *                  have everything from offset up until the end
     *                  of the array.
     * @param   string  you can tell the key the path shall be used to be
     *                  constructed with i.e. giving 'name' (=default) would
     *                  use the value of the $element['name'] for the node-name
     *                  (thanks to Michael Johnson).
     *
     * @return  array   this array contains all elements from the root
     *                  to the element given by the id
     */
    function getPathAsString($id, $seperator='/',
                                $offset=0, $length=0, $key='name')
    {
        $path = $this->getPath($id);
        foreach ($path as $aNode) {
            $pathArray[] = $aNode[$key];
        }

        if ($offset) {
            if ($length) {
                $pathArray = array_slice($pathArray,$offset,$length);
            } else {
                $pathArray = array_slice($pathArray,$offset);
            }
        }

        $pathString = '';
        if( sizeof($pathArray) )
            $pathString = implode($seperator,$pathArray);
        return $pathString;
    } // end of function

    //
    //  abstract methods, those should be overwritten by the implementing class
    //

    /**
     * gets the path to the element given by its id
     *
     * @abstract
     * @version 2001/10/10
     * @access  public
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   mixed   $id     the id of the node to get the path for
     * @return  array   this array contains all elements from the root
     *                  to the element given by the id
     */
    function getPath($id)
    {
        return $this->_raiseError(TREE_ERROR_NOT_IMPLEMENTED,
                __FUNCTION__, __LINE__ );
    } // end of function


    /**
     * gets the path to the element given by its id
     *
     * @version 2003/05/11
     * @access  private
     * @author  Wolfram Kriesing <wolfram@kriesing.de>
     * @param   mixed   $id     the id of the node to get the path for
     * @return  array   this array contains the path elements and the sublevels
     *                  to substract if no $cwd has been given.
     */
    function _preparePath($path, $cwd='/', $separator='/'){
        $elems = explode($separator,$path);
        $cntElems=sizeof($elems);
        // beginning with a slash
        if(empty($elems[0])){
            $beginSlash = true;
            array_shift($elems);
            $cntElems--;
        }
        // ending with a slash
        if (empty($elems[$cntElems-1])) {
            $endSlash = true;
            array_pop($elems);
            $cntElems--;
        }
        // Get the real path, and the levels
        // to substract if required
        $down = 0;
        while($elems[0]=='..'){
            array_shift($elems);
            $down++;
        }
        if ($down>=0 && $cwd=='/') {
            $down = 0;
            $_elems = array();
            $sublevel = 0;
            $_elems = array();
        } else {
            list($_elems, $sublevel) = $this->_preparePath($cwd);
        }
        $i = 0;
        foreach($elems as $val){
            if (trim($val)=='') {
                return $this->_raiseError(TREE_ERROR_INVALID_PATH,
                            __FUNCTION__, __LINE__ );
            }
            if ($val=='..') {
                 if ($i==0) {
                    $down++;
                 } else {
                    $i--;
                 }
            } else {
                $_elems[$i++] = $val;
            }
        }
        if(sizeof($_elems)<1){
            return $this->_raiseError(TREE_ERROR_EMPTY_PATH,
                        __FUNCTION__, __LINE__ );
        }
        return array($_elems, $sublevel);
    }

    /**
     * get the level, which is how far below the root the element
     * with the given id is
     *
     * @abstract
     * @version    2001/11/25
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      mixed   $id     the id of the node to get the level for
     *
     */
    function getLevel($id)
    {
        return $this->_raiseError(TREE_ERROR_NOT_IMPLEMENTED,
                        __FUNCTION__, __LINE__ );
    } // end of function

    /**
     * returns if $childId is a child of $id
     *
     * @abstract
     * @version    2002/04/29
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      int     id of the element
     * @param      int     id of the element to check if it is a child
     * @param      boolean if this is true the entire tree below is checked
     * @return     boolean true if it is a child
     */
    function isChildOf($id, $childId, $checkAll = true)
    {
        return $this->_raiseError(TREE_ERROR_NOT_IMPLEMENTED,
                    __FUNCTION__, __LINE__ );
    } // end of function

    /**
     *
     *
     */
    function getIdByPath($path, $startId=0,
                        $nodeName = 'name', $seperator = '/')
    {
        return $this->_raiseError(TREE_ERROR_NOT_IMPLEMENTED,
                    __FUNCTION__, __LINE__ );
    } // end of function

    /**
     * return the maximum depth of the tree
     *
     * @version    2003/02/25
     * @access     public
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @return     int     the depth of the tree
     */
    function getDepth()
    {
        return $this->_treeDepth;
    } // end of function

    //
    //  PRIVATE METHODS
    //
    /**
     * prepare multiple results
     *
     * @see        _prepareResult()
     * @access     private
     * @version    2002/03/03
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      array   the data to prepare
     * @return     array   prepared results
     */
    function _prepareResults($results)
    {
        $newResults = array();
        foreach ($results as $key=>$aResult) {
            $newResults[$key] = $this->_prepareResult($aResult);
		}
        return $newResults;
    }

    /**
     * map back the index names to get what is expected
     *
     * @access     private
     * @version    2002/03/03
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      array   a result
     * @return     array   the prepared result
     */
    function _prepareResult($result)
    {
        $map = $this->getOption('columnNameMaps');
        if ($map) {
            $new = array();
			foreach ($map as $key=>$columnName) {
				$result[$key] = $result[$columnName];
			}
            return $new;
		}
        return $result;
    }

    /**
     * this method retreives the real column name, as used in the DB
     * since the internal names are fixed, to be portable between different
     * DB-column namings, we map the internal name to the real column name here
     *
     * @access     private
     * @version    2002/03/02
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      string  the internal name used
     * @return     string  the real name of the column
     */
    function _getColName($internalName)
    {
        if ($map = $this->getOption('columnNameMaps')) {
            if (isset($map[$internalName])) {
                return $map[$internalName];
			}
        }
        return $internalName;
    }

    /**
     *
     *
     * @access     private
     * @version    2002/03/02
     * @author     Pierre-Alain Joye <paj@pearfr.org>
     * @param      string  the error message
     * @param      int     the line in which the error occured
     * @param      mixed   the error mode
     * @return     object  a Tree_Error
     */
    function _raiseError($errorCode, $msg='', $line=0)
    {
        include_once 'Tree/Error.php';
        return new Tree_Error(
            $msg , $line, __FILE__, $mode, $this->dbh->last_query);
    }

    /**
     *
     *
     * @access     private
     * @version    2002/03/02
     * @author     Wolfram Kriesing <wolfram@kriesing.de>
     * @param      string  the error message
     * @param      int     the line in which the error occured
     * @param      mixed   the error mode
     * @return     object  a Tree_Error
     */
    function _throwError($msg, $line, $mode=null)
    {
        include_once 'Tree/Error.php';
        if ($mode===null && $this->debug>0) {
            $mode = PEAR_ERROR_PRINT;
		}
        return new Tree_Error(
            $msg , $line, __FILE__, $mode, $this->dbh->last_query);
    }
}

/*
 * Local Variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
?>
