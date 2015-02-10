<? 
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_DEFAULT_SOA
 */

/**
* JCORE DEFINES
* this is where we define the API
* first we set the basic file path settings
*/
define ("JCORE_BASE_DIR", "/var/www/JCORE/CORE/");
define ("JCORE_CONFIG_DIR", "/var/www/JCORE/CONFIG/");
define ("JCORE_PACKAGES_DIR", "/var/www/JCORE/PACKAGES/");
define ("JCORE_TEMPLATES_DIR", "/var/www/JCORE/TEMPLATES/");
define ("JCORE_LOG_DIR", "/var/log/httpd/"); //make sure you have write access

define ("JCORE_PLUGINS_DIR", "/var/www/JCORE/PLUGINS/");
/**
* no packages in SOA? 
* define ("JCORE_PACKAGES_DIR", "/var/www/JCORE/PACKAGES/");
*/

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
*  JCORE REQUIRED API SETTINGS
* next we define this API
*/
define ("JCORE_API_DIR", "/var/www/HTTP/default_api");

define ("JCORE_API_TRANSPORT_IN", "JSONPRC_1.0"); //	ARG[_ver]/URI/JSONPRC_1.0/XML/SOAP
define ("JCORE_API_TRANSPORT_OUT", "JSONPRC_1.0");


/**
* after setting the basics we'll load up the application
* bootstrap loads the lowest level of the application 
* DATA_UTIL_API 	- very low level functions (cleaning whitespace, trim preceeding "0." from micro time
* CONFIG_MANAGER 	- loads and allows access to all the configuration data (*.ini files loaded to arrays)
* 
*/
#echo __FILE__.'@'.__LINE__.'<br>';

require_once(JCORE_BASE_DIR.'/LOAD/BOOTSTRAP.php');

#echo __FILE__.'@'.__LINE__.'<br>';

/**
* we'll register the plugin
*/
$pluginName = 'EXAMPLE_BASIC';
$pluginName = 'EXAMPLE_SOA';
$CONFIG_MANAGER->registerPlugin($pluginName); //getRegisteredPlugins($pluginName=null)
#echo __FILE__.'@'.__LINE__.'<br>';

#require_once(JCORE_BASE_DIR.'LOAD/AUTOLOAD_PLUGIN.php');
require_once(JCORE_BASE_DIR.'LOAD/AUTOLOAD.php');
#echo __FILE__.'@'.__LINE__.'<br>';







$USER_ID = '';				// what ever the unique identifier of the user is (PK of the user record)
$USER_SESSION_ID = '';		// 













?>