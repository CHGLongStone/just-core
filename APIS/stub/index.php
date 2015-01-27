<?
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_STUB
 */


#echo __FILE__.'@'.__LINE__.'_REQUEST<pre>'.var_export($_REQUEST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_POST<pre>'.var_export($_POST, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.var_export($_GET, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'_GET<pre>'.print_r($GLOBALS, true).'</pre><br>';

/**
* config.php is specific for the API, can/should be shared with loadable files in ths directory
* JCORE specific settings are in the JCORE/CORE/CONFIG dir in ini files
* settings in this config file are specific to the files loaded in this directory
*/
require_once('config.php');

/*
* load the transport layer
*/


/*
* load the  service layer
*/



?>