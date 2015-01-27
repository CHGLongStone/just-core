<?
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_DEFAULT_SOA
 */
/**
* PHP ini settings, for error & XDEBUG
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
#xdebug_start_trace();

#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';


#phpinfo();
/**
* config.php is specific for the API, can/should be shared with loadable files in ths directory
* JCORE specific settings are in the JCORE/CORE/CONFIG dir in ini files
* settings in this config file are specific to the files loaded in this directory
*/
require_once('config.php');

/**
* we preload files specific to the implementation here
* in this case, the DAO_TREE class
*/
$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
/**
* load the file
*/
require_once($loadFile);

#echo __FILE__.'@'.__LINE__.'END-----------<br>';
/****
echo '<b>get_defined_constants<pre>'.var_export(get_defined_constants(), true).'</pre></b>';
echo '<b>get_declared_classes<pre>'.var_export(get_declared_classes(), true).'</pre></b>';
echo '<b>get_declared_interfaces<pre>'.var_export(get_declared_interfaces(), true).'</pre></b>';
*/


/**
* Set a default param if none is given
*
*/
if($_REQUEST["pk"] && is_numeric($_REQUEST["pk"])){
	$calledPK = $_REQUEST["pk"];
}else{
	$calledPK = 1;
}
/**
* this configuration determines the settings for the DAO_TREE
*/
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
		'pk' => $calledPK,
		'callBackDisplay' => null
	);
#echo __FILE__.'@'.__LINE__.'$config<pre>'.print_r($config, true).'</pre>';

$myTree = new DAO_TREE($config);

$extArgs = array();
/*
* key is the column name, value is column value
*/
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
$extArgs["whereCols"] = array(
	'HID' => $HID,
	'menuID' => $menuID
);
$myTree->setTreeStyle('TEXT', $extArgs);

####
$result = $myTree->select_tree($_POST["parent_pk"]);
####echo __FILE__.'@'.__LINE__.'$result<pre>'.print_r($result, true).'</pre>';


#$myTree->setTreeStyle('TEXT');
$treeVal = 'render_tree<br>'.$myTree->render_tree($result).'<hr>';

/**
* 
array (
  'nodeNameA-AA-AA_9' => 'A-AA-AA_9 ',
  'NEW_PARENT_9' => '9',
  'adminAction' => 'changeParent',
  'pk' => '9',
  'parent_pk' => '9',
  'leftBound' => '1',
  'rightBound' => '16',
  'nodeDepth' => '0',
)
*/
$response = '';

/**
* processing the request
*/
switch($_POST["adminAction"]){
	case "changeParent":
		#echo '$_POST["adminAction"]==['.$_POST["adminAction"].']<br>';
		if(isset($_POST['NEW_PARENT_'.$_POST["pk"]]) && is_numeric($_POST['NEW_PARENT_'.$_POST["pk"]])){
			if($_POST['NEW_PARENT_'.$_POST["pk"]] == $_POST["pk"]){
				echo 'SAME PARENT:: NOTHING TO DO<br>';
			}else{
				echo 'DIFF PARENT:: ACTION TO DO<br>';
				#$myTree->changeParent($nodeId=10, $newParentId=8);
				$response =  $myTree->changeParent($_POST["pk"], $_POST['NEW_PARENT_'.$_POST["pk"]]);
			}
		
		}
		break;
	case "sortNode:UP":
		$sortVals = explode (':', $_POST["adminAction"] );
		$response = $myTree->sortNode($_POST["pk"], $sortVals[1]);
		break;
	case "sortNode:DOWN":
		$sortVals = explode (':', $_POST["adminAction"] );
		$response = $myTree->sortNode($_POST["pk"], $sortVals[1]);
		break;
	case "addNode":
		#echo '$_POST["adminAction"]==['.$_POST["adminAction"].']<br>';
		/**
		* @param int $nodeId 
		* @param array $values  $values[$this->config["dspShortName"]]
		* @param bool $child 
		*/
		$values = null;
		if($_REQUEST["addNodeType"] == 'Child'){
			$child = true;
		}else{
			$child = false;
		}
		$response = $myTree->addNode($_POST["pk"], $values, $child);
		break;
	case "deleteNode":
		#echo '$_POST["adminAction"]==['.$_POST["adminAction"].']';
		$response = $myTree->deleteNode($_POST["pk"]);
		break;
	default:
		break;
}
#echo '$_POST["adminAction"]==['.$_POST["adminAction"].']<br>';
#echo __FILE__.'@'.__LINE__.'PROCESSED<br>';
$result = $myTree->select_tree($_POST["parent_pk"]);

#echo __FILE__.'@'.__LINE__.'$result<pre>'.print_r($result, true).'</pre>';


#$myTree->setTreeStyle('TEXT');
$treeVal = 'render_tree<br>'.$myTree->render_tree($result).'<hr>';

#echo __FILE__.'@'.__LINE__.'PREVIEW:: $treeVal<pre>'.print_r($treeVal, true).'</pre>';
#xdebug_stop_trace();
$ReturnURL = 'http://thesun.selfip.org/default_api/menu_tree.php?HID='.$HID.'&menuID='.$menuID.'';
echo '
'.$response.'
<a href="'.$ReturnURL.'">back</a>'; 
if(!is_string($response)){
	header('Location: '.$ReturnURL.'');
}
?>