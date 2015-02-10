<?php
/***
*
*/
ini_set('error_reporting', E_ERROR);//E_STRICT	E_NOTICE	E_WARNING
#ini_set('display_errors', "Off");//E_STRICT	E_NOTICE	E_WARNING
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_extension_funcs( 'xcachez' ), true).'</pre><br>';
#phpinfo();
if(is_array(get_extension_funcs('xcache'))){
	include("xcache.php");
}else{
	phpinfo();
}
/***
*
*/
