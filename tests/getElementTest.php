<?php
//  $Id$

require_once 'TreeHelper.php';

class tests_getElementTest extends TreeHelper
{
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $tree->update(3, array('comment' => 'PEAR rulez'));
        $tree->setup();
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez', $actual['comment']);
    }

    function test_MemoryMDBnested()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree = $this->getMemoryMDBnested();        
        $tree->update(3, array('comment' => 'PEAR rulez'));
        $tree->setup();
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez', $actual['comment']);

        $tree->setOption('fields', array('comment' => array('type' => 'text', 'name' => 'myComment')));
        $tree->setup();
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez', $actual['comment']);
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
    
    // do this for Dynamic[M]DB[2]nested
    function test_DynamicDBnested()
    {
        $tree =& $this->getDynamicSQLnested('DB');
        $this->_test_DynamicSQLnested($tree);
    }

    function test_DynamicMDBnested()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree =& $this->getDynamicSQLnested('MDB');
        $this->_test_DynamicSQLnested($tree);
    }

    function test_DynamicMDB2nested()
    {
        if (!$this->has_mdb2) {
            $this->markTestSkipped('MDB2 is not installed');
        }

        $tree =& $this->getDynamicSQLnested('MDB2');
        $this->_test_DynamicSQLnested($tree);
    }

    function _test_DynamicSQLnested(&$tree)
    {
        $tree->update(3, array('comment' => 'PEAR rulez'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez', $actual['comment']);
    }

    /**
    * Empty the tree and add an element, retreive it and check if it is the one we added.
    *
    *
    */
    function test_DynamicSQLnestedEmptyTree()
    {
        $config = array(
            'container' => 'Dynamic',
            'type' => 'Nested',
            'storage' => array(
                'name' => 'DB',
                'dsn' => $this->dsn,
                // 'connection' =>
            ),
            'options' => array(
                'table' => TABLE_TREENESTED,
                'order' =>  'id',
                'fields' => array(),
            ),
        );

        $tree = Tree::factory($config);
        $root = $tree->getRoot();
        $tree->remove($root['id']);

        $config['container'] = 'Memory';
        $tree = Tree::factory($config);
        $tree->setup();
        $id = $tree->add(array('name' => 'Start'));
        $tree->setup();
        $el = $tree->getElement($id);
        $this->assertEquals('Start', $el['name']);
        $root = $tree->getRoot();
        $tree->remove($root['id']);

        $config['container'] = 'Dynamic';
        $tree = Tree::factory($config);
        $id = $tree->add(array('name' => 'StartDyn'));
        $el = $tree->getElement($id);
        $this->assertEquals('StartDyn', $el['name']);
    }
}

?>
