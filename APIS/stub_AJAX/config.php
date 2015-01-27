<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DEFAULT_API
 */
/**
* check the environment first
* in order to run multiple instances with out branching the source control
* we'll use an environment flag
* this requires that the environments be run in parallel
* the expected flag is "-DEV" which is appended to the path as needed
* file is stored in the TLD, use relative paths do NOT add env.php to source control
*/
if(!include('../env.php') ){
	$ENVPATH = '';
}
/**
* JCORE DEFINES
* this is where we define the API
* first we set the basic file path settings
*/
define ("JCORE_BASE_DIR", 		"/var/www/JCORE".$ENVPATH."/CORE/");
define ("JCORE_CONFIG_DIR", 	"/var/www/JCORE".$ENVPATH."/CONFIG/"); 
define ("JCORE_CACHE_DIR", 		"/var/www/JCORE".$ENVPATH."/CACHE/");
define ("JCORE_TMP_DIR", 		"/var/www/JCORE".$ENVPATH."/CACHE/FILE/");
define ("JCORE_TEMPLATES_DIR", 	"/var/www/JCORE".$ENVPATH."/TEMPLATES/");
/**
* supporting services 
*/
define ("JCORE_LOG_DIR", "/var/log/httpd/"); //make sure you have write access
define ("JCORE_FILE_CACHE_DIR", "/var/www/JCORE".$ENVPATH."/CACHE/FILE/"); //make sure you have write access

define ("JCORE_PLUGINS_DIR", "/var/www/JCORE".$ENVPATH."/PLUGINS/AUTHSERVER/PLUGINS/");
define ("JCORE_PACKAGES_DIR", "/var/www/JCORE".$ENVPATH."/PACKAGES/");
/**
*  JCORE REQUIRED API SETTINGS
* next we define this API
* <pre>
* ---ingonore this section for now, a session object will be writen using a cache backend-------
* -------------------------------------------------------------------------------------
* JCORE_SESSION_NAME is the common name for the API + the PID of the thread
* catch that? yes the application does use local sessions but they are NOT
* appropriate for user data unless extended with session_set_save_handler()
* common access objects like the CACHE_API and DATA_API
* threads may be reused among different API's 
* session max size [# session objects] X [# of API's on server] X [# of PHP threads]
* "as session data is locked to prevent concurrent writes only one script may operate 
* on a session at any time... When using framesets [AJAX or other multilple requests] 
* together with sessions you will experience the frames loading one by one due to this locking."
* http://ca3.php.net/manual/en/function.session-write-close.php
* with common data tied to the thread there is no locking
* -------------------------------------------------------------------------------------
* </pre>
*/
$JCORE_SESSION_NAME = "DEFAULT-API-".getmypid();
define ("JCORE_SESSION_NAME", $JCORE_SESSION_NAME); //a-z, A-Z, 0-9 and '-,' 
//session_id(JCORE_SESSION_NAME);
/***
* SYSTEM CACHE 
* this is an data cache that must implement CACHE_STATIC_API_INTERFACE
* and the file defining the class will be loaded from:
* JCORE_BASE_DIR/CACHE/[CACHE_NAME]/[CACHE_NAME].static.php
* 
* that can be invoked from a static handler class
*/
#define ("JCORE_SYSTEM_CACHE", "EACCELERATOR");
define ("JCORE_SYSTEM_CACHE", "XCACHE");
define ("JCORE_SYSTEM_CACHE_SERIALIZATION", "JSON"); //JSON/NATIVE/RAW[string]


/**
* basic settings for the API
*/
define ("JCORE_API_DIR", "/var/www/VHOSTS/auth".strtolower($ENVPATH).".deluxebusinessservices.com/AJAX");
#define ("JCORE_API_TRANSPORT_IN", "URI"); //	ARG/URI/JSONPRC/XML/SOAP
#define ("JCORE_API_TRANSPORT_OUT", "HTML");
define ("JCORE_API_TRANSPORT_IN", "JSON"); //	ARG[_ver]/URI/JSONPRC_1_0/XML/SOAP
define ("JCORE_API_TRANSPORT_IN_VER", "RPC_1_0"); 
define ("JCORE_API_TRANSPORT_OUT", "JSON");
define ("JCORE_API_TRANSPORT_OUT_VER", "RPC_1_0");


/**
* after setting the basics we'll load up the application
* BOOTSTRAP.php loads the lowest level of the application in this order:
* 
* DATA_UTIL_API 	- very low level functions (cleaning whitespace, trim preceeding "0." from micro time
* CONFIG_MANAGER 	- loads and allows access to all the configuration data (*.ini files loaded to arrays)
* LOGGER			- loads all of the log instances loaded 
* CACHE_API			- loads any of the cache sources in /JCORE/CONFIG/SERVICE/CACHE_SOURCE.ini
* DATA_API			- 
*/
/*
* passed to CONFIG_MANAGER->__construct();
*/
#$BOOTSTRAP["CSN"] = JCORE_SYSTEM_CACHE;
$BOOTSTRAP["CACHE_SERIALIZATION"] = 'JSON'; //[JSON/NATIVE]
$BOOTSTRAP["UNSERIALIZE_TYPE"] = 'ARRAY'; //[ARRAY/OBJECT]

#echo __FILE__.'@'.__LINE__.'<br>';
#phpinfo();
#die;
#echo __FILE__.'@'.__LINE__.'BOOTSTRAP =='.JCORE_BASE_DIR.'LOAD/BOOTSTRAP.php'.'<br>';
require_once(JCORE_BASE_DIR.'/LOAD/BOOTSTRAP.php');


#echo __FILE__.'@'.__LINE__.'<br>';

/**
* we'll register the plugin
*/
$pluginList = array(
	'AJAX_STUB',
	'THEMEMANAGER'
	
);

foreach($pluginList AS $key => $pluginName){
	$CONFIG_MANAGER->registerPlugin($pluginName); //getRegisteredPlugins($pluginName=null)
}
#echo __FILE__.'@'.__LINE__.'<br>';

#require_once(JCORE_BASE_DIR.'LOAD/AUTOLOAD_PLUGIN.php');
require_once(JCORE_BASE_DIR.'LOAD/AUTOLOAD.php');
#echo __FILE__.'@'.__LINE__.'<br>';

#echo __FILE__.'@'.__LINE__.'<br>';






$USER_ID = '';				// what ever the unique identifier of the user is (PK of the user record)
$USER_SESSION_ID = '';		// 













?>