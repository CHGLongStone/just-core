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
https://auth-dev.deluxebusinessservices.com/AJAX/test.php?{%22method%22:%20%22AJAX_STUB.aServiceMethod%22,%20%22params%22:%20{%22resultHandler%22%20:%20%22returnOrderPK%22%20,%20%22action%22%20:%20%22setAction%22},%20%22id%22:%2067619818192968}
https://auth-dev.deluxebusinessservices.com/AJAX/test.php?{"method": "AJAX_STUB.introspectService", "params": {"resultHandler" : "returnOrderPK" , "action" : "setAction"}, "id": 67619818192968}
*/

#$AJAXObj = new AJAX_STUB();
#echo __FILE__.'@'.__LINE__.'<br>';
$JSONRPC_1_0_API = new JSONRPC_1_0_API();


#echo __FILE__.'@'.__LINE__.'AJAXObj<pre>'.var_export($AJAXObj, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo 'HELLO JOSH!!!';
#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';
echo __FILE__.'@'.__LINE__.'$_SERVER["QUERY_STRING"]<pre>'.print_r($_SERVER["QUERY_STRING"], true).'</pre><br>';
?>