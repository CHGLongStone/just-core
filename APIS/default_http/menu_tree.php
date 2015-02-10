<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DEFAULT_API
 */
/***
* XDEBUG SETTINGS
*/
ini_set('error_reporting', E_STRICT);//E_STRICT	E_NOTICE	E_WARNING
ini_set('display_errors', "1");
ini_set('xdebug.trace_options', 0); //overwrite
ini_set('xdebug.scream', 1);	//disable the @ 
ini_set('xdebug.show_local_vars', 1);
ini_set('xdebug.dump_globals', 1);
ini_set('xdebug.collect_assignments', 1);
ini_set('xdebug.collect_includes', 1);
ini_set('xdebug.collect_params', 4);
ini_set('xdebug.collect_return', 4);
ini_set('xdebug.show_mem_delta', 1);
ini_set('xdebug.show_exception_trace', 1);
ini_set('xdebug.trace_output_dir', "/var/log/httpd/");
ini_set('xdebug.profiler_output_name', "%p-%s-%R");
ini_set('xdebug.profiler_enable_trigger', 1);
#phpinfo();
#----include_once('xhprof_head.php');
xdebug_start_trace();
/***
* END XDEBUG SETTINGS
*/



require_once('config.php');

/**
* TEMPLATE SECTION 
*/
require_once(JCORE_BASE_DIR.'TEMPLATER/TEMPLATER.class.php');
	$TEMPLATER = new TEMPLATER();
	/*
	* MUST DEFINE TEMPLATE NAME SPACE
	* subtemplates?
	* 
	*/ 
	$ps_template_header = 'header';
	$ps_template_body 	= 'body';
	$ps_template_body2 	= 'body2';
	$ps_template_footer = 'footer';
	/**
	* whether to render sections of the page
	*/
	$header_footer_set = true;
	/**
	* assign the template namespace to specific file
	*/
	$TEMPLATER->set_filenames(array($ps_template_header => JCORE_TEMPLATES_DIR.'HTML/BASIC/header.html'));
	$TEMPLATER->set_filenames(array($ps_template_body 	=> JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal.html'));
	$TEMPLATER->set_filenames(array($ps_template_footer => JCORE_TEMPLATES_DIR.'HTML/BASIC/footer.html'));
	
	
/**
* we registered this in config.php
* 
* $CONFIG_MANAGER->registerPlugin('EXAMPLE_BASIC');
* instantiating it here will load all of its helper clasees
* 
* we'll also load the tree Data Access Object [DAO_TREE]
*/
$EXAMPLE_BASIC = new EXAMPLE_BASIC($subTemplater);


$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
#echo __FILE__.'@'.__LINE__.'FILE<hr><hr><hr><hr>'.$loadFile.'<br>';
require_once($loadFile);
	

//----------------------------------------------------------------
// TREE SECTION---------------------------------------------------
//----------------------------------------------------------------
//----------------------------------------------------------------
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
$extArgs = array();
/*
* key is the column name, value is column value
* this is how we specify different trees in the same table
* first identifier specifies the client/user
* second identifier specifies the context (multiple menues per user)
* 
* plus a quick check so we can do this by form
*/
//$_REQUEST<pre>'.var_export($_REQUEST,true).'</pre><br>
if( isset($_REQUEST["HID"]) && is_numeric($_REQUEST["HID"]) ){ 
	$HID = $_REQUEST["HID"] ;
}else{
	$HID = 1;
}
if( isset($_REQUEST["menuID"]) && DATA_UTIL_API::scrubString($_REQUEST["menuID"]) ){ 
	$menuID = $_REQUEST["menuID"] ;
}else{
	$menuID = 'MENU_DEF';
}

$extArgs["whereCols"] = array(
	'HID' => $HID,
	'menuID' => $menuID
);
/*
* key is the table name, 
* values are descriptive
* fields required in return must be specified
* extension tables have exactly the same structure (columns)
* but may contain different data sets attached to the same structure
* all values from extension tables are returned [tablename]_[fieldname]
* so more than one table may be selected at one time
*/
$extArgs["extensionTables"] = array(
	'attributes_a' => array(
		'pkField' => 'id',
		'fkField' => 'tree_id',
		'fields' => array('id',	'tree_id', 'DOMName', 'displayName', 'actionType',	'actionArgs', 'AKeys' )
	),
	'attributes_b' => array(
		'pkField' => 'id',
		'fkField' => 'tree_id',
		'fields' => array('id',	'tree_id', 'DOMName', 'displayName', 'actionType',	'actionArgs', 'AKeys' )
	)
);
/**
* here we apply the settings
* the tree style must be EXTENDED to leverage extension tables
*/
$myTree->setTreeStyle('EXTENDED', $extArgs);
/*
after changing the difinition from SIMPLE to EXTENDED 
we need to re select the tree (with the where clause and extended data)
this will set up the basic internal properties and settings
*/
$result = $myTree->select_tree();
$myTree->render_tree_array($result); //caches result and sets as $myTree->treeArray
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
#echo '<b>baseArray<pre>'.var_export($baseArray, true).'</pre></b>';
#echo '<b>myTree->treeArray<pre>'.var_export($myTree->treeArray, true).'</pre></b>';
#echo '<b>DIFF<pre>'.var_export(array_diff($myTree->treeArray, $baseArray), true).'</pre></b>';
$render = new renderNestedTree($config);

###//--------------------------------------

/** dump a simple text representation
$callBackDisplay = array($render, 'renderNode4'); // 
$myTree->setCallBackDisplay($callBackDisplay);
$output = $myTree->render_tree($result);
echo __FILE__.'@'.__LINE__.'$output method renderNode4<hr><hr>
'.$output.'
<hr><hr><br> ';
#echo __FILE__.'@'.__LINE__.'<br>';
*/


$args["id"] = 'id';
$args["class"] = 'class';
$args["title"] = 'id';
$callBackDisplay = array($render, 'renderNode3'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'<br>';
$output = $myTree->render_tree($result);
$output = str_replace("\'", "'", $output);
$SECTION_TITLE = 'HID['.$HID.'] MenuID['.$menuID.']';
$BODY = '
<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">
	'.$output.'
	<br>
	Reload Tree<br>
	<form action="?" method="post" name="main"  enctype="multipart/form-data" >
		HID<input type="text" name="HID" id="HID" value="'.$HID.'">	
		Menu Name<input type="text" name="menuID" id="menuID" value="'.$menuID.'">	
		<input type="submit" name="Reload" id="Reload" value="Reload">	
	</form>
	
	
</div>';
#echo __FILE__.'@'.__LINE__.'$output method renderNode3<hr><hr><div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';


//----------------------------------------------------------------
//END  TREE SECTION---------------------------------------------------
//----------------------------------------------------------------
//----------------------------------------------------------------
//----------------------------------------------------------------

/**
* output section
*/

	/*
	* proceeedural file to apply settings to the header template
	*/
	require_once('header.php');
	$TEMPLATER->assign_vars(array(	
		'SECTION_TITLE' 	=> $SECTION_TITLE,
		'BODY' 	=> $BODY
	));	

	/*
	* proceeedural file to apply settings to the header template
	*/
	require_once('footer.php');

	/*
	* Do the final rendering routine
	*/
	if($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_header, true, $retvar = 'returnString');
	};
	echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	if ($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_footer, true, $retvar = 'returnString');
	};	

#---include_once('xhprof_foot.php');
#echo '<b>xhprof_data<pre>'.var_export($xhprof_data, true).'</pre></b>';
xdebug_stop_trace();
#echo '!!!END!!!';
#phpinfo();
?>
