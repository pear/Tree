<?php

    //
    //  $Id$
    //

    ##################################################
    #
    #       init template engine
    #
    // you need the template class from http://sf.net/projects/simpltpl
    if( !@include('SimpleTemplate/Engine.php') )
    {
        print   'sorry, you need the template class from <a href="http://sf.net/projects/simpltpl">sourceforge</a> for this.<br>'.
                'or if i have time i put the examples <a href="http://wolfram.kriesing.de/programming/">here online</a>';
        die();
    }
    require_once('SimpleTemplate/Filter/TagLib.php');
    $options = array(   'templateDir'   => dirname(__FILE__) );
    $tpl = new SimpleTemplate_Engine($options);


    ##################################################
    #
    #       actual tree stuff, using Dynamic_DBnested
    #
    require_once('Tree/Tree.php');
    $tree = Tree::setup( 'Dynamic_DBnested' , 'mysql://root@localhost/test' , array('table'=>'nestedTree') );

    if( $_REQUEST['action_add'] )
    {
        $methodCall = "tree->add( {$_REQUEST['newData']} , {$_REQUEST['parentId']} , {$_REQUEST['prevId']} )";
        $result = $tree->add( $_REQUEST['newData'] , $_REQUEST['parentId'] , $_REQUEST['prevId'] );
    }

    if( $_REQUEST['action_remove'] )
    {
        $methodCall = "$tree->remove( {$_REQUEST['removeId']} )";
        $result = $tree->remove( $_REQUEST['removeId'] );
    }

    if( $_REQUEST['action_update'] )
    {
        $methodCall = "tree->update( {$_REQUEST['updateId']} , {$_REQUEST['updateData']} )";
        $result = $tree->update( $_REQUEST['updateId'] , $_REQUEST['updateData'] );
    }

    if( $_REQUEST['action_move'] )
    {
        $methodCall = "tree->move( {$_REQUEST['move_id']} , {$_REQUEST['move_newParentId']} , {$_REQUEST['move_newPrevId']} )";
        $result = $tree->move( $_REQUEST['move_id'] , $_REQUEST['move_newParentId'] , $_REQUEST['move_newPrevId'] );
    }

    $methodFailed = false;
    if( PEAR::isError($result) )
        $methodFailed = true;

    if( !$fid )
        $fid = $tree->getRootId();

    $path = $tree->getPath( $fid );
    $children = $tree->getChildren( $fid );

    ##################################################
    #
    #       actual tree stuff to show the entire tree using Memory_DBnested
    #
    require_once('Tree/Tree.php');
    $memTree = Tree::setup( 'Memory_DBnested' , 'mysql://root@localhost/test' , array('table'=>'nestedTree') );

    $memTree->setup();
    $entireTree = $memTree->getNode();

    $tpl->compile('index.tpl');
    include($tpl->compiledTemplate);
?>