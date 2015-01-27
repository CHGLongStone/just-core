<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DEFAULT_API
 */
/***
*
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

/****

*/
$args["KEY"] = 'wdf';
$args["DATA"] = '{"some" : "string"}';
$args["ttl"] = 0;
xcache_set($args["KEY"], $args["DATA"], $args["ttl"]);
#echo 'xcache_get['.xcache_get($args["KEY"]).']<br>';
unset($args);


#echo __FILE__.'@'.__LINE__.'<br>';
require_once('config.php');
#echo __FILE__.'@'.__LINE__.'<br>';
/***
$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
require_once($loadFile);
require_once('tree.php');

*/


/**
OUTLINE
this is the default layout for a standard API, in this case a regular website
the base structure loads content based on the URI passed (used mod-rewrite for pretty URL's)
for efficiency and change managements sake use different API's for different "classes" of task
use one for generating HTML content, use another as CDN (static assets), use another as Media delivery
and others for "true" API's (AJAX, SOAP, etc.)

the base logic is assumed to be a "branching" style and each branch is handled by a central switch statement

ie. trunk starts in this file, the first branch is defined in another switch statement in another file.
this may seem a bit cumbersome but the trade off for managability is well worth it.
if your logical "branches" are defined effectively you will have clean separation in your code and will be able 
to change parts of the implementation with impacting the whole, you can also leverage a "switching" mechanism
where you can decide the "branch" implemented at run time.

TEMPLATER


*/

#echo __FILE__.'@'.__LINE__.'['.JCORE_BASE_DIR.']<br>';
require_once(JCORE_BASE_DIR.'TEMPLATER/TEMPLATER.class.php');

#echo __FILE__.'@'.__LINE__.'<br>';

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
	$TEMPLATER->set_filenames(array($ps_template_footer => JCORE_TEMPLATES_DIR.'HTML/BASIC/footer.html'));
	
	/**
	* break out here to call the header routine
	* we'll do the footer @ the bottom
	* first we can call and see if there is some kind of cache set up
	*/
	#if (!isset($TEMPLATER->compiled_code[$ps_template_header]) || empty($TEMPLATER->compiled_code[$ps_template_header])){	}			
	require_once('header.php');
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	$TEMPLATER->assign_vars( array(	
		'FOOTER' => 'FOOTER'
	));	

#echo __FILE__.'@'.__LINE__.'<br><pre>';
	switch($_REQUEST["action"]){
		case "action1":
			#action 
			break;
		case "action2":
			//echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			
			$TEMPLATER->set_filenames(array($ps_template_body 	=> JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal.html'));
			$TEMPLATER->set_filenames(array($ps_template_body2 	=> JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal2.html'));
			
			$subTemplater = new TEMPLATER();
			#$subTemplater->set_filenames(array($ps_template_header => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html'));
			$subTemplater->set_filenames(array('sub_'.$ps_template_body => JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal2.html'));
			#$subTemplater->set_filenames(array($ps_template_footer  => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html'));
			
			
			#echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			#EXAMPLE_BASIC.basicAPI::function1($subTemplater);
			$EXAMPLE_BASIC = new EXAMPLE_BASIC($subTemplater);
			$EXAMPLE_BASIC->function1('sub_'.$ps_template_body);
			#call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] )
			#call_user_func (  $function='EXAMPLE_BASIC.basicAPI::function1', $subTemplater);
			//echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			/*
			*/			
			foreach($GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG') AS $key => $value){
				#echo 'here: '.__FILE__.'@'.__LINE__.' key['.$key.']$value==<pre>'.var_export($value, true).'</pre><br>';
				$subTemplater->assign_block_vars('someData', array(
					'NAME'	=> $key,
					'DESC'	=> $key.'DESC'
				));	
			}
			/*
	$config = array(
		'table' => 'base_tree',
		'pk' => 1,
		'callBackDisplay' => null
	);
$myTree = new DAO_TREE($config);
$callBackDisplay = array($render, 'renderNode3'); // 
$myTree->setCallBackDisplay($callBackDisplay);
#echo __FILE__.'@'.__LINE__.'<br>';
$output = str_replace("\'", "'", $output);

echo '<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">'.$output.'</div>';
			*/
			$BODY = $subTemplater->sparse('sub_'.$ps_template_body, true, $retvar = 'returnString');
			///---------------------------------------------
			#echo __FILE__.'@'.__LINE__.'<br>';
			/*
			*/
			$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
			require_once($loadFile);
			
			$config = array(
				'table' => 'base_tree',
				'pk' => 1,
				'callBackDisplay' => null
			);
			$myTree = new DAO_TREE($config);
			$result = $myTree->select_tree(1);
			$config = array(
				#'templater' => $TEMPLATER,
				'templates' => array(
					'header' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
					'footer' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
					'body' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree.html',
					'parent' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_parent.html',
					'child' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_child.html'
				)
			);
			$render = new renderNestedTree($config);
			$callBackDisplay = array($render, 'renderNode3'); // 
			$myTree->setCallBackDisplay($callBackDisplay);
			$output = $myTree->render_tree($result);
			$output = str_replace("\'", "'", $output);

			$BODY .=  '<div id="tree" style="padding-left: 10px; border: 1px solid #0000FF;">****'.$output.'****</div>';
			///---------------------------------------------
			
			$TEMPLATER->assign_vars(array(	
				#'BODY' 	=> $subTemplater->sparse($ps_template_body, true, $retvar = 'returnString')
				'BODY' 	=> $BODY
			));	

			#echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			break;
		default:
			#action
			break;
	}
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	
	/**
	* break out here to call the footer routine
	*/
	require_once('footer.php');
	$TEMPLATER->assign_vars( array(	
		'FOOTER' => 'FOOTER'
	));	
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	/*
	* Do the final rendering routine
	*/
	if ($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_header, true, $retvar = 'returnString');
	};
	echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	#echo '<hr>'.$TEMPLATER->sparse('sub_'.$ps_template_body2, true, $retvar = 'returnString').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, 'BUFFER').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, 'FLUSH').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, null).'<hr>';
	if ($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_footer, true, $retvar = 'returnString');
	};	
echo 'DONE##########<hr><hr><hr><hr>'.__FILE__.'<br>';

//------------------------------------------------------------------------
//------------------------------------------------------------------------
#echo '<b>array_keys($TEMPLATER->_tpldata = array())<pre>'.var_export(array_keys($TEMPLATER->_tpldata), true).'</pre></b>';


#phpinfo();
#echo __FILE__.'@'.__LINE__.'$link<pre>'.print_r($link, true).'</pre>';
$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
#echo __FILE__.'@'.__LINE__.'FILE<hr><hr><hr><hr>'.$loadFile.'<br>';
require_once($loadFile);

$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
#echo __FILE__.'@'.__LINE__.'FILE<hr><hr><hr><hr>'.$loadFile.'<br>';
require_once($loadFile);
require_once('tree.php');




ini_set('error_reporting', E_STRICT);//E_STRICT	E_NOTICE	E_WARNING


#echo __METHOD__.__LINE__.'<pre>'.var_export($GLOBALS['CONFIG_MANAGER'], true).'</pre><br>';
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_declared_classes(), true).'</pre><br>';
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_class_methods("EACCELERATOR"), true).'</pre><br>';
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_class_methods("XCACHE"), true).'</pre><br>';
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_class_methods("JCORE_SINGLETON"), true).'</pre><br>';
/**
$myObj = EACCELERATOR::singleton();
*/
$myObj = XCACHE::singleton();
$myObj->bark();
echo '<b>myObj<pre>'.var_export($myObj, true).'</pre></b>';

#echo '<b>get_defined_constants<pre>'.var_export(get_defined_constants(), true).'</pre></b>';
#get_declared_classes();
#get_declared_interfaces();
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_extension_funcs( 'eaccelerator' ), true).'</pre><br>';
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_declared_interfaces(), true).'</pre><br>';
#echo unserialize(";i:2;s:3:\"173\";i:3;s:3:\"174\";i:4;s:3:\"175\";i:5;s:3:\"177\";i:7;s:3:\"191\";i:8;s:3:\"192\";i:9;s:3:\"193\";i:10;s:3:\"178\";i:12;s:3:\"194\";i:13;s:3:\"195\";i:14;s:3:\"196\";i:15;s:3:\"183\";i:16;s:3:\"214\";i:17;s:3:\"215\";i:18;s:3:\"216\";i:19;s:3:\"184\";i:20;s:3:\"217\";i:21;s:3:\"218\";i:22;s:3:\"219\";i:23;s:1:\"3\";i:24;s:3:\"160\";i:25;s:3:\"179\";i:28;s:3:\"197\";i:29;s:3:\"198\";i:30;s:3:\"199\";i:31;s:3:\"200\";i:32;s:3:\"201\";i:33;s:3:\"202\";i:34;s:3:\"203\";i:35;s:3:\"204\";i:36;s:3:\"187\";i:37;s:3:\"233\";i:38;s:3:\"241\";i:39;s:2:\"70\";i:40;s:2:\"7");

#---include_once('xhprof_foot.php');
//$xhprof_data = xhprof_disable();
echo '<b>xhprof_data<pre>'.var_export($xhprof_data, true).'</pre></b>';
#xdebug_stop_trace();
echo '!!!END!!!';
#phpinfo();
?>
