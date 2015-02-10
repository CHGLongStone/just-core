<?
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_STUB
 */

$profiler_output_name = '-'.$_SERVER["PHP_SELF"].'-%s';
ini_set('error_reporting', E_WARNING);//E_STRICT	E_NOTICE	E_WARNING 	E_ALL
ini_set('display_errors', "On");//display_errors = Off
ini_set('xdebug.trace_options', 0); //overwrite
ini_set('xdebug.auto_trace', 1); 
ini_set('xdebug.scream', 1);	//disable the @ 
ini_set('xdebug.show_local_vars', 1);
ini_set('xdebug.dump_globals', 1);
ini_set('xdebug.collect_assignments', 1);
ini_set('xdebug.collect_includes', 1);
ini_set('xdebug.collect_params', 2);
ini_set('xdebug.collect_return', 2);
ini_set('xdebug.show_mem_delta', 0);
ini_set('xdebug.show_exception_trace', 1);
ini_set('xdebug.trace_output_dir', "/var/log/httpd/");
ini_set('xdebug.profiler_output_name', $profiler_output_name); 
ini_set('xdebug.profiler_enable_trigger', 1);
#
echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';

/**
* config.php is specific for the API, can/should be shared with loadable files in ths directory
* JCORE specific settings are in the JCORE/CORE/CONFIG dir in ini files
* settings in this config file are specific to the files loaded in this directory
*/

require_once('config.php');
echo __FILE__.'@'.__LINE__.'<br>';
/*
* load the transport layer
*/
$filepath = JCORE_BASE_DIR.'TRANSPORT/REST/REST_API.class.php';
#echo '$filepath['.$filepath.']<br>';
require_once($filepath);




echo __FILE__.'@'.__LINE__.'<br>';

#require_once(JCORE_PLUGINS_DIR'config.php');

/*
* load the  service layer
*/

$RESTObj = new REST_STUB();

echo __FILE__.'@'.__LINE__.'<br>';

echo __FILE__.'@'.__LINE__.'RESTObj<pre>'.var_export($RESTObj, true).'</pre><br>';
echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
?>