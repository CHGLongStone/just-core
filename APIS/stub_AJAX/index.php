<?
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_STUB_AJAX
 */

#
#
#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'$_SERVER["QUERY_STRING"]<pre>'.print_r($_SERVER["QUERY_STRING"], true).'</pre><br>';


/**
* config.php is specific for the API, can/should be shared with loadable files in ths directory
* JCORE specific settings are in the JCORE/CORE/CONFIG dir in ini files
* settings in this config file are specific to the files loaded in this directory
*/

require_once('config.php');
#echo __FILE__.'@'.__LINE__.'<br>';
/*
* load the transport layer
*/
#$filepath = JCORE_BASE_DIR.'TRANSPORT/JSON/JSONRPC_1_0_API.class.php';
##echo '$filepath['.$filepath.']<br>';
#require_once($filepath);




#echo __FILE__.'@'.__LINE__.'<br>';

#require_once(JCORE_PLUGINS_DIR'config.php');

/*
* load the  service layer
*/

#$AJAXObj = new AJAX_STUB();
$JSONRPC_1_0_API = new JSONRPC_1_0_API();

#echo __FILE__.'@'.__LINE__.'<br>';

#echo __FILE__.'@'.__LINE__.'AJAXObj<pre>'.var_export($AJAXObj, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo 'HELLO JOSH!!!';
#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';
echo __FILE__.'@'.__LINE__.'$_SERVER["QUERY_STRING"]<pre>'.print_r($_SERVER["QUERY_STRING"], true).'</pre><br>';
?>