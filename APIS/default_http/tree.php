<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DEFAULT_API

1	1	CONFIG			1	12
2	1	PACKAGE			2	3
3	1	SERVICE			4	11
4	3	CACHE_SOURCE	5	6
5	3	DATA			7	8
6	3	LOG				9	10
7	2	DEF				4	

								1-CONFIG-16
									|
						-------------------------
						|						|
					2-PACKAGE-7				8-SERVICE-15
						|						|
					-------------			-----------------------------------------
					|			|			|						|				|
				3-DEF-4		5-DEF2-6	9-CACHE_SOURCE-10 		11-DATA-12 		13-LOG-14
------------------------------------------------------------------------------------------------
add peer 
++2 all right > X
++2 all left > X
------------------------------------------------------------------------------------------------
function addpeer(){
								1-CONFIG[1]-17
									|
						-----------------------------------------------------
						|													|
					2-PACKAGE[2]-9										10-SERVICE[3]-16
						|													|
					-------------------------					-----------------------------------------
					|			|X			|					|						|				|
				3-DEF-4		5-DEF2-6	7-DEF3-8			11-CACHE_SOURCE-12 		13-DATA-14 		13-LOG-15

}
------------------------------------------------------------------------------------------------
add peer 
++2 all right > X
++2 all left > X
------------------------------------------------------------------------------------------------
function addpeer(){
								1-CONFIG[1]-17
									|
						---------------------------------------------------------
						|														|
					2-PACKAGE[2]-9-11								12...10-SERVICE[3]-16
						|														|
					-------------------------------------				-----------------------------------------
					|			|X			|			|				|						|				|
				3-DEF-4		5-DEF2-6	7-new1-8	9-new2-10				11-CACHE_SOURCE-12 		13-DATA-14 		13-LOG-15

}
------------------------------------------------------------------------------------------------
add child [end]
++2 all right >= X
++2 all left > X
------------------------------------------------------------------------------------------------

								1-CONFIG[1]-17
									|
						-----------------------------
						|							|
					2-PACKAGE[2]-9				10-SERVICE[3]-16
						|							|
					-------------				-----------------------------------------
					|			|X				|						|				|
				3-DEF-4		5-DEF2[8]-8		11-CACHE_SOURCE-12 		11-DATA-13 		14-LOG-15
								|
							6-DEF3-7

						
------------------------------------------------------------------------------------------------
add child 
++2 all right >= X?
++2 all left > X?
------------------------------------------------------------------------------------------------
								1-CONFIG[1]-17
									|
						-----------------------------
						|							|
					2-PACKAGE[2]-9				10-SERVICE[3]-16
						|							|
					-------------				-----------------------------------------
					|X			|				|						|				|
				3-DEF[7]-6		7-DEF2[8]-8		11-CACHE_SOURCE-12 		11-DATA-13 		14-LOG-15
					|
				4-DEF3-5
------------------------------------------------------------------------------------------------
add child 
++2 all right >= X?
++2 all left > X?
------------------------------------------------------------------------------------------------
								1-CONFIG[1]-17
									|
						-----------------------------
						|							|
					2-PACKAGE[2]-9				10-SERVICE[3]-16
						|							|
					-------------				-----------------------------------------
					|X			|				|						|				|
				3-DEF[7]-6		7-DEF2[8]-8		11-CACHE_SOURCE-12 		11-DATA-13 		14-LOG-15
					|
				4-DEF3-5
					|
				
------------------------------------------------------------------------------------------------

------------------------------------------------------------------------------------------------

								1-CONFIG-16
									|
						-------------------------
						|						|
					2-PACKAGE-7[9]			8-SERVICE-15
						|						|
					-------------			-----------------------------------------
					|			|			|						|				|
				3-DEF-4		5-DEF2-6[8]	9-CACHE_SOURCE-10 		11-DATA-12 		13-LOG-14
								|
							-----		--------
							|			|
						6-DEF3-7	8-DEF4-9

						
LOCK TABLE nested_category WRITE;
UNLOCK TABLES;
						
PEER:
UPDATE nested_category SET rgt = rgt + 2 WHERE rgt > @myRight;
UPDATE nested_category SET lft = lft + 2 WHERE lft > @myRight;

INSERT INTO nested_category(name, lft, rgt) VALUES('FRS', @myRight + 1, @myRight + 2);

CHILD:
UPDATE nested_category SET rgt = rgt + 2 WHERE rgt > @myLeft;
UPDATE nested_category SET lft = lft + 2 WHERE lft > @myLeft;

INSERT INTO nested_category(name, lft, rgt) VALUES('FRS', @myLeft + 1, @myLeft + 2);


------------------------------------------------------------------------------------------------
								1-CONFIG-16
									|
						-------------------------
						|						|
					2-PACKAGE-7				8-SERVICE-15
						|						|
					-------------			-----------------------------------------
					|			|			|						|				|
				3-DEF-4		5-DEF2-6	9-CACHE_SOURCE-10 		11-DATA-12 		13-LOG-14
------------------------------------------------------------------------------------------------
	
								1-CONFIG-17
									|
						-------------------------------------------------
						|												|
					2-PACKAGE-9										10-SERVICE-16
						|												|
					-----------------------------					---------------------------------------------
					|			|				|					|							|				|
				3-DEF-4		5-DEF2-6		7-SET_EXT-8				11-CACHE_SOURCE-12 		13-DATA-14 		15-LOG-15
								|
							
------------------------------------------------------------------------------------------------
								1-CONFIG-20
									|
						-------------------------------------------------
						|												|
					2-PACKAGE-11									12-SERVICE-19
						|												|
					-----------------------------					---------------------------------------------
					|			|				|					|							|				|
				3-DEF-4		5-DEF2-8		9-SET_EXT-10		13-CACHE_SOURCE-14 		15-DATA-16 			17-LOG-18
								|
						6-SET_EXT_CHILD-7
------------------------------------------------------------------------------------------------
								1-CONFIG-24
									|
						-------------------------------------------------------------------------
						|																		|
					2-PACKAGE-15														16-SERVICE-23
						|																		|
					---------------------------------------------					---------------------------------------------
					|			|				|				|					|							|				|
				3-DEF-4		5-DEF2-10		11-SET_EXT-12	13-SET_EXT-14		17-CACHE_SOURCE-18 			19-DATA-20 		21-LOG-22
								|
					----------------------------
					|							|
			6-SET_EXT_CHILD-7			8-SET_EXT_CHILD2-9
------------------------------------------------------------------------------------------------
*/


/**
*
*
*/
function dumpTable($table='base_tree'){
	$query = 'SELECT * FROM '.$table.';';
	#$result = mysql_query($query);
	$result = $GLOBALS["DATA_API"]->retrieve('JCORE', $query, $args=array('returnArray' => true));
	echo __FUNCTION__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre><br>';
	#$result = $GLOBALS["DATA_API"]->SQLResultToAssoc($result);
	foreach($result AS $key => $value){
		echo $value["pk"].'&nbsp;&nbsp;&nbsp;&nbsp;|
		'.$value["parent_pk"].'&nbsp;&nbsp;&nbsp;&nbsp;|
		'.$value["title"].'&nbsp;&nbsp;&nbsp;&nbsp;|
		'.$value["leftBound"].'&nbsp;&nbsp;&nbsp;&nbsp;|
		'.$value["rightBound"].'&nbsp;&nbsp;&nbsp;&nbsp;|
		<br>';
	}
}

$config = array(
		#'DSN' => 'DSN',
		'table' => 'treeStructure',
		#'table' => 'base_tree',
		#'table' => 'filesys_tree',
		#'pkField' => 'pk',
		'pkField' => 'id',
		'parentField' => 'parent_id',
		#'parentField' => 'parent_pk',
		#'leftBound' => 'leftBound',
		#'rightBound' => 'rightBound',
		#'dspShortName' => 'title',
		'pk' => 1,
		'callBackDisplay' => null
	);
$myTree = new DAO_TREE($config);

#echo __FILE__.'$myTree<pre>'.print_r($myTree, true).'</pre>';
echo __FILE__.'$config<pre>'.print_r($config, true).'</pre>';
#echo __FILE__.'@'.__LINE__.'$config<pre>'.$config.'</pre>';
$result = $myTree->select_tree(1);
$myTree->setTreeStyle('TEXT');
$treeVal = 'render_tree<br>'.$myTree->render_tree($result).'<hr>';
#$parentID = 2;
#$leftBound = 3;
#$result = $myTree->rebuild_tree2($parentID, $leftBound);
#echo __FILE__.'$result<pre>'.print_r($result, true).'</pre>';
#echo __FILE__.'$myTree->itCounter<pre>'.print_r($myTree->itCounter, true).'</pre>';

/****
	#ADD/REMOVE TESTS
	$newNode = $myTree->addPeerNode(8);
	$result = $myTree->select_tree(1);
	#$treeVal .= 'addPeerNode<br>'.$myTree->render_tree($result).'<hr>';
	$treeVal = 'addPeerNode<br>'.$myTree->render_tree($result).'<hr>';
	echo __FILE__.'@'.__LINE__.'$newNode['.$newNode.']<pre>'.$treeVal.'</pre>';


	#$myTree->deleteNode($newNode);
	#$result = $myTree->select_tree(1);
	###$treeVal .= 'deleteNode<br>'.$myTree->render_tree($result).'<hr>';
	#$treeVal = 'deleteNode<br>'.$myTree->render_tree($result).'<hr>';
	#echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';


	$newNode = $myTree->addChildNode(8);
	$result = $myTree->select_tree(1);
	$myTree->setTreeStyle('SIMPLE');
	#$treeVal .= 'addChildNode<br>'.$myTree->render_tree($result).'<hr>';
	$treeVal = 'addChildNode<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
	echo __FILE__.'@'.__LINE__.'$newNode['.$newNode.']<pre>'.$treeVal.'</pre>';

	#$myTree->deleteNode($newNode);
	#$result = $myTree->select_tree(1);
	#$myTree->setTreeStyle('FULL');
	##$treeVal .= 'deleteNode<br>'.$myTree->render_tree($result).'<hr>';
	#$treeVal = 'deleteNode<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
	#echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';
	#//END ADD/REMOVE TESTS

*/
	/********************************
	dumpTable();
	********************************/
	$values = array(
		'title' => 'SET_EXT2_'.DATA_UTIL_API::cleanMicrotime()
	);
	#------	$newNode = $myTree->addNode(8, $values);
	#$newNode = $myTree->addPeerNode(8);
	
	#dumpTable();
	$values = array(
		'title' => 'SET_EXT_CHILD2_'.DATA_UTIL_API::cleanMicrotime()
	);
	#------	$newNode = $myTree->addNode(8, $values, true);
	
	$values = array(
		'title' => 'SET_EXT_CHILD'
	);
	
	#$newNode = $myTree->addNode(8,$values, true);
	#$newNode = $myTree->addChildNode(8);
	
$result = $myTree->select_tree(1);
$myTree->setTreeStyle('SIMPLE');
$treeVal = 'SIMPLE<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
#echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';

/**
$result = $myTree->select_tree(1);
$myTree->setTreeStyle('FULL');
$treeVal = 'FULL<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';
*/


$myTree->setTreeStyle('TEXT');
$treeVal = 'TEXT<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
#echo __FILE__.'@'.__LINE__.''.$treeVal.' ';

/**************************************
#echo  '$CONFIG_MANAGER->getConstants()<br><pre>'.var_export($CONFIG_MANAGER->getConstants(), true).'</pre><hr>';
echo $myTree->sortNode($nodeId=6, $sortOrder='UP');
echo $myTree->sortNode($nodeId=6, $sortOrder='UP');
echo $myTree->sortNode($nodeId=6, $sortOrder='UP');

$result = $myTree->select_tree(1);
$treeVal = 'TEXT<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';

$result = $myTree->select_tree(1);
echo $myTree->sortNode($nodeId=6, $sortOrder='DOWN');
echo $myTree->sortNode($nodeId=6, $sortOrder='DOWN');
echo $myTree->sortNode($nodeId=6, $sortOrder='DOWN');

$treeVal = 'TEXT<br><pre>'.var_export($myTree->render_tree($result), true).'</pre><hr>';
echo __FILE__.'@'.__LINE__.'<pre>'.$treeVal.'</pre>';

$nodeId=10, $newParentId=6
$nodeId=10, $newParentId=8
**************************************/

/**
$nodeId = $myTree->changeParent($nodeId=10, $newParentId=6);
echo __FILE__.'@'.__LINE__.'<pre>'.var_export($myTree->render_tree($myTree->select_tree(1)), true).'</pre>';

$myTree->changeParent($nodeId=10, $newParentId=8);
echo __FILE__.'@'.__LINE__.'<pre>'.var_export($myTree->render_tree($myTree->select_tree(1)), true).'</pre>';
*/
$myTree->setTreeStyle('FULL');
$baseArray = $myTree->render_tree_array($result);
#echo __FILE__.'@'.__LINE__.'baseArray<pre>'.var_export($baseArray, true).'</pre>';

#die;

$config = array(
		#'templater' => $TEMPLATER,
		'templates' => array(
			'header' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'footer' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'body' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree.html',
			'parent' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_parent.html',
			'child' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_child.html'
		),
		'baseTree'	=> $myTree->treeArray
	);

#echo __FILE__.'@'.__LINE__.'<br>';
#$EXAMPLE_BASIC = new EXAMPLE_BASIC($TEMPLATER);
#$returnString = $TEMPLATER->sparse($BASEID, true, $retvar = 'returnString');
$render = new renderNestedTree($config);
$myTree->setTreeStyle('FULL');
#$render->renderNode($tree);

###//--------------------------------------


$callBackDisplay = array($render, 'renderNode4'); // 
$myTree->setCallBackDisplay($callBackDisplay);
$output = $myTree->render_tree($result);
echo __FILE__.'@'.__LINE__.'$output<hr><hr>
'.$output.'
<hr><hr><br> ';

#die;
###//--------------------------------------

#echo __FILE__.'@'.__LINE__.'<pre>'.var_export($callBackDisplay, true).'</pre> ';

/**
#echo __FILE__.'@'.__LINE__.'<pre>'.var_export($myTree, true).'</pre> ';
echo '<b>---===START===---['.$callBackDisplay[1].']</b><br>';
echo '<b>---===START===---</b><br>';
echo '<b>---===START===---render_tree2</b><br>';
$output = $myTree->render_tree($result);
#$output = $myTree->render_tree($result);
#echo __FILE__.'@'.__LINE__.'<br> '.'<br> '.$output.'<br> ';
* Strip escaped single quotes for javascript
*/

/**
#echo __FILE__.'@'.__LINE__.'<br>';
$args["id"] = 'id';
$args["class"] = 'class';
$args["title"] = 'id';
$tree = null;
$render->renderSelectTree($tree, $args);
$callBackDisplay = array($render, 'renderNode3'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';
//-------------------
//-------------------

$callBackDisplay = array($render, 'renderSelectTree'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'$result[<pre>'.var_export($result, true).'</pre>]<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';
*/

#echo '<b>myTree<pre>'.var_dump($myTree, true).'</pre></b>';


/*
echo __FILE__.'@'.__LINE__.'$output<hr><hr>
'.$output.'
<hr><hr><br> ';
echo '<b>array_keys($render->templater->_tpldata = array())<pre>'.var_export($render->templater->_tpldata, true).'</pre></b>';
echo '<b>array_keys($render->subTemplater->_tpldata = array())<pre>'.var_export($render->subTemplater->_tpldata, true).'</pre></b>';
["currentNode."]
*/

echo __FILE__.'@'.__LINE__.'output<hr><hr><hr><hr><hr>';


echo '<hr><hr>'.__FILE__.'@'.__LINE__.'
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
<hr><hr>';
/***
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
* EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	EXTENDED	
*/
/*
we apply the settings to allow more than one tree per table
*/
$extArgs = array();
/*
* key is the column name, value is column value
*/
$extArgs["whereCols"] = array(
	'HID' => 1,
	'menuID' => 'MENU_DEF'
);
/*
* key is the table name, value is the FK column name
*/
$extArgs["extensionTables"] = array(
	'attributes_a' => array(
		'pkField' => 'id',
		'fkField' => 'tree_id',
		'fields' => array(
			'id',
			'tree_id',
			'DOMName',
			'displayName',
			'actionType',
			'actionArgs',
			'AKeys'		
		)
	),
	'attributes_b' => array(
		'pkField' => 'id',
		'fkField' => 'tree_id',
		'fields' => array(
			'id',
			'tree_id',
			'DOMName',
			'displayName',
			'actionType',
			'actionArgs',
			'AKeys'		
		)
	)
	
	##'attributes_b' => 'tree_id'
);

$myTree->setTreeStyle('EXTENDED', $extArgs);
#$render->renderNode($tree);
/*
after changing the difinition from SIMPLE to EXTENDED 
we need to re select the tree (with the where clause and extended data)
*/
$result = $myTree->select_tree();
#echo __FILE__.'@'.__LINE__.'$result[<pre>'.var_export($result, true).'</pre>]<br>';
$baseArray = $myTree->render_tree_array($result);
#echo __FILE__.'@'.__LINE__.'$myTree->treeArray[<pre>'.var_export($myTree->treeArray, true).'</pre>]<br>';
/*****
*/
$config = array(
		#'templater' => $TEMPLATER,
		'templates' => array(
			'header' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'footer' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'body' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree.html',
			'parent' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_parent.html',
			'child' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_child.html'
		),
		'baseTree'	=> $myTree->treeArray
	);
#echo __FILE__.'@'.__LINE__.'$myTree->treeArray[<pre>'.var_export($myTree->treeArray, true).'</pre>]<br>';
$render = new renderNestedTree($config);
#echo __FILE__.'@'.__LINE__.'$render->config["baseTree"][<pre>'.var_export($render->config["baseTree"], true).'</pre>]<br>';


###//--------------------------------------

/***/
$callBackDisplay = array($render, 'renderNode4'); // 
$myTree->setCallBackDisplay($callBackDisplay);
$output = $myTree->render_tree($result);
echo __FILE__.'@'.__LINE__.'$output renderNode4<hr><hr>
'.$output.'
<hr><hr><br> ';

echo __FILE__.'@'.__LINE__.'<br>';
$args["id"] = 'id';
$args["class"] = 'class';
$args["title"] = 'id';
#$tree = null;
#$render->renderSelectTree($tree, $args);
$callBackDisplay = array($render, 'renderNode3'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __FILE__.'@'.__LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';



$callBackDisplay = array($render, 'renderSelectTree'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'$result[<pre>'.var_export($result, true).'</pre>]<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';


///----------------------------------------
///----------------------------------------
///----------------------------------------
///----------------------------------------
///----------------------------------------
echo '<hr><hr>'.__FILE__.'@'.__LINE__.'
EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2
<hr><hr>';
/***
* EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	EXTENDED2	
*/
/*
we apply the settings to allow more than one tree per table
*/
$extArgs = array();
$extArgs["whereCols"] = array(
	'HID' => 0,
	'menuID' => 'MENU_DEF'
);

$myTree->setTreeStyle('EXTENDED', $extArgs);
#$render->renderNode($tree);
/*
after changing the difinition from SIMPLE to EXTENDED 
we need to re select the tree (with the where clause and extended data)
*/
$result = $myTree->select_tree();
#echo __FILE__.'@'.__LINE__.'$result[<pre>'.var_export($result, true).'</pre>]<br>';
$baseArray = $myTree->render_tree_array($result);
#echo __FILE__.'@'.__LINE__.'$myTree->treeArray[<pre>'.var_export($myTree->treeArray, true).'</pre>]<br>';
/*****
*/
$config = array(
		#'templater' => $TEMPLATER,
		'templates' => array(
			'header' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'footer' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
			'body' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree.html',
			'parent' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_parent.html',
			'child' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_child.html'
		),
		'baseTree'	=> $myTree->treeArray
	);
$render = new renderNestedTree($config);


###//--------------------------------------

/***/
$callBackDisplay = array($render, 'renderNode4'); // 
$myTree->setCallBackDisplay($callBackDisplay);
$output = $myTree->render_tree($result);
echo __FILE__.'@'.__LINE__.'$output<hr><hr>
'.$output.'
<hr><hr><br> ';

#echo __FILE__.'@'.__LINE__.'<br>';
$args["id"] = 'id';
$args["class"] = 'class';
$args["title"] = 'id';
$tree = null;
$render->renderSelectTree($tree, $args);
$callBackDisplay = array($render, 'renderNode3'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';



$callBackDisplay = array($render, 'renderSelectTree'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'$result[<pre>'.var_export($result, true).'</pre>]<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);

echo __LINE__.'<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';

?>