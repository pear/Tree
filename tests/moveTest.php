<?php
//  $Id$

require_once dirname(__FILE__) . '/TreeHelper.php';

class tests_moveTest extends TreeHelper
{
    // check if we get the right ID, for the given path
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $ret = $tree->move(5, 1);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        // and check if the move succeeded, by checking the new parentId
        $parent = $tree->getParent(5);
        $this->assertEquals(1, $parent['id']);
    }

    function test_MemoryMDBnested()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree = $this->getMemoryMDBnested();        
        $ret = $tree->move(5, 1);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        // and check if the move succeeded, by checking the new parentId
        $parent = $tree->getParent(5);
        $this->assertEquals(1, $parent['id']);
    }

    function test_MemoryDBnestedNoAction()
    {
        $tree = $this->getMemoryDBnested();        
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $parent = $tree->getParent(5);
        $ret = $tree->move(5, 5);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        $parent1 = $tree->getParent(5);
        $this->assertEquals($parent['id'], $parent1['id']);
    }

    function test_MemoryMDBnestedNoAction()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree = $this->getMemoryMDBnested();        
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $parent = $tree->getParent(5);
        $ret = $tree->move(5, 5);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        $parent1 = $tree->getParent(5);
        $this->assertEquals($parent['id'], $parent1['id']);
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
    
    // do this for DynamicSQLnested
    function test_DynamicSQLnested()
    {
        $tree =& $this->getDynamicSQLnested();
        $ret = $tree->move(5, 1);

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        // and check if the move succeeded, by checking the new parentId
        $parent = $tree->getParent(5);
        $this->assertEquals(1, $parent['id']);
    }

    function test_DynamicSQLnestedNoAction()
    {
        $tree =& $this->getDynamicSQLnested();
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $parent = $tree->getParent(5);
        $ret = $tree->move(5, 5);

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        $parent1 = $tree->getParent(5);
        $this->assertEquals($parent['id'], $parent1['id']);
    } 
}

?>
