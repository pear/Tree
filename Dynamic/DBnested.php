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

require_once('Tree/Common.php');
require_once('Tree/Error.php');

/**
*   this class implements methods to work on a tree saved using the nested
*   tree model
*   explaination: http://research.calacademy.org/taf/proceedings/ballew/index.htm
*
*   @access     public
*   @package    Tree
*/
class Tree_Dynamic_DBnested extends Tree_Common
# FIXXME should actually extend Tree_Common, to use the methods provided in there... but we need to connect
# to the db here, so we extend optionsDB for now, may be use "aggregate" function to fix that
{

    var $debug = 0;

    var $options = array(
# FIXXME to be implemented
                            'whereAddOn'=>''    // add on for the where clause, this string is simply added behind the WHERE in the select
                                                // so you better make sure its correct SQL :-), i.e. 'uid=3'
                                                // this is needed i.e. when you are saving many trees in one db-table
                            ,'table'     =>''   //
                            // since the internal names are fixed, to be portable between different
                            // DB tables with different column namings, we map the internal name to the real column name
                            // using this array here, if it stays empty the internal names are used, which are:
                            // id, left, right
                            ,'columnNameMaps'=>array(
                                                //'id'            =>  'node_id',    // use "node_id" as "id"
                                                 'left'          =>  'l'            // since mysql at least doesnt support 'left' ...
                                                ,'right'         =>  'r'            // ...as a column name we set default to the first letter only
                                                //'name'          =>  'nodeName'  //
                                                ,'parentId'       =>  'parent'    // parent id
                                                )

                            );

# the defined methods here are proposals for the implementation,
# they are named the same, as the methods in the "Memory" branch, if possible
# it would be cool to keep the same naming and if the same parameters would be possible too
# then it would be even better, so one could easily change from any kind of
# tree-implementation to another, without changing the source code, only the setupXXX would need to be changed

    /**
    *
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function __construct( $options=array() )
    {
        Tree_Dynamic_DBnested( $options );
    }

    /**
    *
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function Tree_Dynamic_DBnested( $dsn , $options=array() )
    {
        parent::Tree_OptionsDB( $dsn , $options ); // instanciate DB
        $this->table = $this->getOption('table');
    }

    /**
    *   add a new element to the tree
    *   there are three ways to use this method
    *   1.
    *   give only the $parentId and the $newValues will be inserted as the first child of this parent
    *   i.e.    // insert a new element under the parent with the ID=7
    *           $tree->add( array('name'=>'new element name') , 7 );
    *   2.
    *   give the $prevId ($parentId would be optional) and the new element
    *   will be inserted in the tree after the element with the ID=$prevId
    *   the parentId is not necessary because the prevId defines exactly where
    *   the new element has to be place in the tree, and the parent is the same as
    *   for the element with the ID=$prevId
    *   i.e.    // insert a new element after the element with the ID=5
    *           $tree->add( array('name'=>'new') , 0 , 5 );
    *   3.
    *   neither $parentId nor prevId is given, then the root element will be inserted
    *   this requires that programmer is responsible to confirm this
    *   this method does not (yet) check if there is already a root element saved !!!
    *
    *   @access     public
    *   @author
    *   @param      array   $newValues  this array contains the values that shall be inserted in the db-table
    *   @param      integer $parentId   the id of the element which shall be the parent of the new element
    *   @param      integer $prevId     the id of the element which shall preceed the one to be inserted
    *                                   use either 'parentId' or 'prevId'
    *   @return     integer the ID of the element that had been inserted
    */
    function add( $newValues , $parentId=0 , $prevId=0 )
    {
        $lName = $this->_getColName('left');
        $rName = $this->_getColName('right');
        $prevVisited = 0;

        // check the DB-table if the columns which are given as keys
        // in the array $newValues do really exist, if not remove them from the array
# FIXXME do the above described

        if( $parentId || $prevId )                  // if no parent and no prevId is given the root shall be added
        {
            $element = $this->getElement( $prevId ? $prevId : $parentId );
            if( PEAR::isError($element) )
                return $element;

            // get the "visited"-value where to add the new element behind
            // if $prevId is given, we need to use the right-value
            // if only the $parentId is given we need to use the left-value
            // look at it graphically, that made me understand it :-)
            // i.e. at: http://research.calacademy.org/taf/proceedings/ballew/sld034.htm
            $prevVisited = $prevId ? $element['right'] : $element['left'];

    # FIXXME start transaction here
            // update the elements which will be affected by the new insert
            $query = sprintf(   'UPDATE %s SET %s=%s+2 WHERE %s>%s',
                                $this->table,
                                $lName,$lName,
                                $lName,
                                $prevVisited );
            if( DB::isError( $res = $this->dbh->query($query) ) )
            {
    # FIXXME rollback
                return $this->_throwError( $res->getMessage() , __LINE__ );
            }

            $query = sprintf(   'UPDATE %s SET %s=%s+2 WHERE %s>%s',
                                $this->table,
                                $rName,$rName,
                                $rName,
                                $prevVisited );
            if( DB::isError( $res = $this->dbh->query($query) ) )
            {
    # FIXXME rollback
                return $this->_throwError( $res->getMessage() , __LINE__ );
            }
        }

        // inserting _one_ new element in the tree
        $newData = array();
        foreach( $newValues as $key=>$value )       // quote the values, as needed for the insert
        {
            $newData[$this->_getColName($key)] = $this->dbh->quote($value);
        }

        // set the proper right and left values
        $newData[$lName] = $prevVisited+1;
        $newData[$rName] = $prevVisited+2;

        // use sequences to create a new id in the db-table
        $nextId = $this->dbh->nextId($this->table);
        $query = sprintf(   'INSERT INTO %s (%s,%s) VALUES (%s,%s)',
                            $this->table ,
                            $this->_getColName('id'),
                            implode( "," , array_keys($newData) ) ,
                            $nextId,
                            implode( "," , $newData )
                        );
        if( DB::isError( $res = $this->dbh->query($query) ) )
        {
# rollback
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
# commit here

        return $nextId;
    } // end of function

    /**
    *   remove a tree element
    *   this automatically remove all children and their children
    *   if a node shall be removed that has children
    *
    *   @access     public
    *   @author
    *   @param      integer $id the id of the element to be removed
    *   @return     boolean returns either true or throws an error
    */
    function remove( $id )
    {
        $element = $this->getElement( $id );
        if( PEAR::isError($element) )
            return $element;

        $delta = $element['right'] - $element['left'] +1;
        $lName = $this->_getColName('left');
        $rName = $this->_getColName('right');

# FIXXME start transaction
        #$this->dbh->autoCommit(false);
        $query = sprintf(   'DELETE FROM %s WHERE %s BETWEEN %s AND %s',
                            $this->table,
                            $lName,
                            $element['left'],$element['right']);
        if( DB::isError( $res = $this->dbh->query($query) ) )
        {
# FIXXME rollback
            #$this->dbh->rollback();
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }

        // update the elements which will be affected by the new insert
        $query = sprintf(   "UPDATE %s SET %s=%s-$delta, %s=%s-$delta WHERE %s>%s",
                            $this->table,
                            $lName,$lName,
                            $rName,$rName,
                            $lName,$element['left'] );
        if( DB::isError( $res = $this->dbh->query($query) ) )
        {
# FIXXME rollback
            #$this->dbh->rollback();
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }

        $query = sprintf(   "UPDATE %s SET %s=%s-$delta WHERE %s<%s AND %s>%s",
                            $this->table,
                            $rName,$rName,
                            $lName,$element['left'],
                            $rName,$element['right'] );
        if( DB::isError( $res = $this->dbh->query($query) ) )
        {
# FIXXME rollback
            #$this->dbh->rollback();
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
# FIXXME commit
        #$this->dbh->commit();
        return true;
    } // end of function

    /**
    *   move a tree element
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function move()
    {
# FIXXME to be done :-)
    } // end of function

    /**
    *   copy a subtree/node/... under a new parent or/and behind a given element
    *
    *
    *   @access     public
    *   @author
    *   @param
    *   @return
    */
    function copy( $id , $parentId , $prevId )
    {
        # get element tree
        # $this->addTree
    } // end of function


    /**
    *   get the root
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getRoot()
    {
        $query = sprintf(   'SELECT * FROM %s WHERE %s=1',
                            $this->table,
                            $this->_getColName('left'));
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResult( $res );
    } // end of function

    /**
    *
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getElement( $id )
    {
        $query = sprintf(   'SELECT * FROM %s WHERE %s=%s',
                            $this->table,
                            $this->_getColName('id'),
                            $id);
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        if( !$res )
            return $this->_throwError( "Element with id $id does not exist!" , __LINE__ );

        return $this->_prepareResult( $res );
    } // end of function

    /**
    *
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getChild( $id )
    {
        // subqueries would be cool :-)
        $curElement = $this->getElement( $id );
        if( PEAR::isError($curElement) )
            return $curElement;

        $query = sprintf(   'SELECT * FROM %s WHERE %s=%s',
                            $this->table,
                            $this->_getColName('left'),
                            $curElement['left']+1 );
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResult( $res );
    }

    /**
    *   gets the path from the element with the given id down
    *   to the root
    *   the returned array is sorted to start at root
    *   for simply walking through and retreiving the path
    *
    *   @access     public
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getPath( $id )
    {
        // subqueries would be cool :-)
        $curElement = $this->getElement( $id );

        $query = sprintf(   'SELECT * FROM %s WHERE %s<=%s AND %s>=%s ORDER BY %s',
                            $this->table,
                            $this->_getColName('left'),
                            $curElement['left'],
                            $this->_getColName('right'),
                            $curElement['right'],
                            $this->_getColName('left') );
        if( DB::isError( $res = $this->dbh->getAll($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResults( $res );
    }

    /**
    *   gets the element to the left, the left visit
    *
    *   @access     public
    *   @version    2002/03/07
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getLeft( $id )
    {
        $element = $this->getElement( $id );
        if( PEAR::isError($element) )
            return $element;

        $query = sprintf(   'SELECT * FROM %s WHERE %s=%s OR %s=%s',
                            $this->table,
                            $this->_getColName('right'),$element['left']-1,
                            $this->_getColName('left'),$element['left']-1 );
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResult( $res );
    }

    /**
    *   gets the element to the right, the right visit
    *
    *   @access     public
    *   @version    2002/03/07
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function getRight( $id )
    {
        $element = $this->getElement( $id );
        if( PEAR::isError($element) )
            return $element;

        $query = sprintf(   'SELECT * FROM %s WHERE %s=%s OR %s=%s',
                            $this->table,
                            $this->_getColName('left'),$element['right']+1,
                            $this->_getColName('right'),$element['right']+1);
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResult( $res );
    }

    /**
    *   get the parent of the element with the given id
    *
    *   @access     public
    *   @version    2002/04/15
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return     mixed   the array with the data of the parent element
    *                       or false, if there is no parent, if the element is the root
    */
    function getParent( $id )
    {
        $query = sprintf(   'SELECT p.* FROM %s p,%s e WHERE e.%s=p.%s AND e.%s=%s',
                            $this->table,$this->table,
                            $this->_getColName('parentId'),
                            $this->_getColName('id'),
                            $this->_getColName('id'),
                            $id);
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResult( $res );
    }

    /**
    *   get the children of the given element
    *
    *   @access     public
    *   @version    2002/04/15
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return     mixed   the array with the data of all children
    *                       or false, if there are none
    */
    function getChildren( $id )
    {
        $query = sprintf(   'SELECT c.* FROM %s c,%s e WHERE e.%s=c.%s AND e.%s=%s',
                            $this->table,$this->table,
                            $this->_getColName('id'),
                            $this->_getColName('parentId'),
                            $this->_getColName('id'),
                            $id);
        if( DB::isError( $res = $this->dbh->getAll($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        return $this->_prepareResults( $res );
    }

    /**
    *   get the next element on the same level
    *   if there is none return false
    *
    *   @access     public
    *   @version    2002/04/15
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return     mixed   the array with the data of the next element
    *                       or false, if there is no next
    */
    function getNext( $id )
    {
        $query = sprintf(   'SELECT n.* FROM %s n,%s e WHERE e.%s=n.%s-1 AND e.%s=n.%s AND e.%s=%s',
                            $this->table,$this->table,
                            $this->_getColName('right'),
                            $this->_getColName('left'),
                            $this->_getColName('parentId'),
                            $this->_getColName('parentId'),
                            $this->_getColName('id'),
                            $id);
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        if( !$res )
            return false;
        return $this->_prepareResult( $res );
    }

    /**
    *   get the previous element on the same level
    *   if there is none return false
    *
    *   @access     public
    *   @version    2002/04/15
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return     mixed   the array with the data of the previous element
    *                       or false, if there is no previous
    */
    function getPrevious( $id )
    {
        $query = sprintf(   'SELECT p.* FROM %s p,%s e WHERE e.%s=p.%s+1 AND e.%s=p.%s AND e.%s=%s',
                            $this->table,$this->table,
                            $this->_getColName('left'),
                            $this->_getColName('right'),
                            $this->_getColName('parentId'),
                            $this->_getColName('parentId'),
                            $this->_getColName('id'),
                            $id);
        if( DB::isError( $res = $this->dbh->getRow($query) ) )
        {
            return $this->_throwError( $res->getMessage() , __LINE__ );
        }
        if( !$res )
            return false;
        return $this->_prepareResult( $res );
    }



    //
    //  PRIVATE METHODS
    //


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
    *
    *   @access     private
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function _throwError( $msg , $line , $mode=null )
    {
        if( $mode===null && $this->debug>0 )
            $mode = PEAR_ERROR_PRINT;
        return new Tree_Error( $msg , $line , __FILE__ , $mode , $this->dbh->last_query );
    }

    /**
    *   this method retreives the real column name, as used in the DB
    *   since the internal names are fixed, to be portable between different
    *   DB-column namings, we map the internal name to the real column name here
    *
    *   @access     private
    *   @version    2002/03/02
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param
    *   @return
    */
    function _getColName( $internalName )
    {
        if( $map = $this->getOption( 'columnNameMaps' ) )
        {
            if( isset($map[$internalName]) )
                return $map[$internalName];
        }
        return $internalName;
    }


    // for playing ....
    function getFirstRoot()
    {
        return $this->getRoot();
    }
    /**
    *   gets the tree under the given element in one array, sorted
    *   so you can go through the elements from begin to end and list them
    *   as they are in the tree, where every child (until the deepest) is retreived
    *
    *   @see        &_getNode()
    *   @access     public
    *   @version    2001/12/17
    *   @author     Wolfram Kriesing <wolfram@kriesing.de>
    *   @param      integer $startId    the id where to start walking
    *   @param      integer $depth      this number says how deep into the structure the elements shall be retreived
    *   @return     array   sorted as listed in the tree
    */
    function &getNode( $startId=0 , $depth=0 )
    {
    }




}
?>