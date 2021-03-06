<?php
/**
 * BOOT STRAP (Proceedure)
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\LOAD
 * 
 */
#echo __FILE__.'::'.__LINE__.' '.JCORE_SESSION_NAME.'getmypid['.getmypid().']<br>';

/**
VERY HANDY FUNC get_extension_funcs( 'eaccelerator' )
#echo __METHOD__.__LINE__.'<pre>'.var_export(get_extension_funcs( 'eaccelerator' ), true).'</pre><br>';
#require_once(JCORE_BASE_DIR.'/LOAD/CONFIG_MANAGER.class.php');
require_once(JCORE_PLUGINS_DIR.'EXAMPLE_BASIC/CLASS/EXAMPLE_BASIC.class.php');
session_start();
if(isset($_SESSION["sessionObject"])){
	echo '<hr>'.__FILE__.'@'.__LINE__.' <b>$_SESSION["sessionObject"] ID['.session_id().']</b><pre>'.var_export($_SESSION["sessionObject"],true).'</pre>';
}else{
	echo __FILE__.'::'.__LINE__.'<br>';
	$somevar = 'string';
	$_SESSION["sessionObject"] = new EXAMPLE_BASIC($somevar);

}
echo __FILE__.'::'.__LINE__.'<br>';
if(isset($_SESSION["CONFIG_MANAGER"])){
	echo '<hr>'.__FILE__.'@'.__LINE__.' <b>$_SESSION["CONFIG_MANAGER"] </b><pre>'.var_export($_SESSION["CONFIG_MANAGER"],true).'</pre>';
}
*/

/**

tie the session to the PID

* load all the basic stuff we need before the transport layer
* -*.ini access
* - logging
*
these are from [API]/config.php
define ("JCORE_BASE_DIR", "/var/www/JCORE/");
define ("JCORE_CONFIG_DIR", "/var/www/JCORE/CONFIG/");
define ("JCORE_PACKAGES_DIR", "/var/www/JCORE/PACKAGES/");
define ("JCORE_TEMPLATES_DIR", "/var/www/JCORE/TEMPLATES/");
*/

/**
* load low level helpers and libs
* load the files 
*/
require_once(JCORE_BASE_DIR.'JCORE_SINGLETON_INTERFACE.interface.php');
require_once(JCORE_BASE_DIR.'JCORE_SINGLETON.singleton.php');
require_once(JCORE_BASE_DIR.'DATA/DATA_UTIL_API.class.php');
require_once(JCORE_BASE_DIR.'LOAD/CONFIG_MANAGER.class.php');
require_once(JCORE_BASE_DIR.'LOG/LOGGER.class.php');
require_once(JCORE_BASE_DIR.'CACHE/CACHE_API.class.php');
require_once(JCORE_BASE_DIR.'TRANSPORT/SERIALIZATION.class.php');
#echo __FILE__.'::'.__LINE__.'##########################<br>';
//var/www/JCORE/CORE//CACHE/EACCELERATOR/EACCELERATOR.class.php
//var/www/JCORE/CORE/CACHE/EACCELERATOR/EACCELERATOR.class.php
//var/www/JCORE/CORE/CACHE/EACCELERATOR		//echo __METHOD__.__LINE__.'<br>';
#echo __FILE__.'::'.__LINE__.'<br>';
$filePath = JCORE_BASE_DIR.'CACHE/'.JCORE_SYSTEM_CACHE.'/'.JCORE_SYSTEM_CACHE.'.static.php';
#echo __FILE__.'::'.__LINE__.'$filePath['.$filePath.']<br>';
require_once($filePath);
#echo __FILE__.'::'.__LINE__.'<br>';
/*
if(defined(JCORE_SYSTEM_CACHE_SERIALIZATION)){
	
	$SYSTEM_CACHE = JCORE_SYSTEM_CACHE;
	#$SYSTEM_CACHE = new $$SYSTEM_CACHE();
}
*/
#echo __FILE__.'::'.__LINE__.'JCORE_SYSTEM_CACHE<br>['.$filePath.']<br>[/var/www/JCORE/CORE/CACHE/EACCELERATOR/EACCELERATOR.class.php]<br>';
#require_once('/var/www/JCORE/CORE/CACHE/EACCELERATOR/EACCELERATOR.class.php');
#$BOOT_CACHE = JCORE_SYSTEM_CACHE;
#echo __FILE__.'::'.__LINE__.'--------------/////<br>';
require_once(JCORE_BASE_DIR.'EXCEPTION/DATA_Exception.class.php');
require_once(JCORE_BASE_DIR.'EXCEPTION/networkException.class.php');
require_once(JCORE_BASE_DIR.'EXCEPTION/ERROR.class.php');
require_once(JCORE_BASE_DIR.'DATA/DATA_API.class.php');
require_once(JCORE_BASE_DIR.'DATA/DATA_API_INTERFACE.interface.php');
require_once(JCORE_BASE_DIR.'DATA/MySQL/MySQL_connector.class.php');
#echo __FILE__.'::'.__LINE__.'<br>';


/**
* start the real stuff
*/

/**
* this is our configuration manager
* the config is done via ini files for the production team
* ini is common language and translates into simple coherent structures
* rather than all settings in the global space "justified left"
* because of the file I/O cost we want to cache these settings in opcode
* - cache JCORE separately from your packages for maximum flexibility
* constructor takes array $BOOTSTRAP as optional arg, set in [API]/config.php
* these are the same args used by CACHE_API
* if you do not have opcode cache make sure the constant JCORE_SYSTEM_CACHE is not set
* 
* $BOOTSTRAP["CSN"]						JCORE_SYSTEM_CACHE[def:JCORE_SYSTEM_CACHE=EACCELERATOR] in this instance
* $BOOTSTRAP["CACHE_SERIALIZATION"]		[def:JSON/NATIVE/RAW(string)] 
* $BOOTSTRAP["UNSERIALIZE_TYPE"]		[def:ARRAY/OBJECT/RAW(string)] for implementations that leverage JSON data and use json_decode ( string $json [, bool $assoc = false]...)
* $BOOTSTRAP["IMPLEMENTATION"]			[STATIC/CONCRETE] to be used for bootstrap the CSN object MUST be STATIC or already loaded and instantiated [API]/config.php
* $---------["KEY"]						only used via CACHE_API - determined from $LOAD_ID
* $---------["DATA"]					[ARRAY/OBJECT/STRING] stored contents of loadIni($LOAD_ID, $FILE_NAME[FILE_NAME]); passed to serialization method 
* this should be a cache object defined in [JCORE_CONFIG_DIR]/SERVICE/CACHE_SOURCE.ini
* and needs to implement CACHE_STATIC_API_INTERFACE//IMPLEMENTATION
* @tutorial JCORE.pkg, JCORE/LOAD.pkg 
*/
if(!isset($BOOTSTRAP)){
	
	/**
	* --- make this value is not set in the API/[name]/config.php 
	* if you do not have an opcode cache installed
	*
	*/
	$BOOTSTRAP["CSN"] = JCORE_SYSTEM_CACHE;		
	$BOOTSTRAP["CACHE_SERIALIZATION"] = 'JSON'; //
	$BOOTSTRAP["UNSERIALIZE_TYPE"] = 'ARRAY'; //CACHE_SERIALIZATION-JSON[OBJECT/ARRAY] for json_decode
}

#echo __FILE__.__LINE__.'---------\---\---/assd<br>';
$GLOBALS['CONFIG_MANAGER'] = new CONFIG_MANAGER($args=$BOOTSTRAP);
#$_SESSION["CONFIG_MANAGER"] = $GLOBALS['CONFIG_MANAGER'];

$GLOBALS['CONFIG_MANAGER']->loadIni($LOAD_ID='JCORE', $FILE_NAME=JCORE_CONFIG_DIR.'jcore.ini');
$GLOBALS['CONFIG_MANAGER']->loadIni($LOAD_ID='JCORE_LOG', $FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/LOG/logServices.ini');

#echo __FILE__.__LINE__.'---------\---\---/assd<br>';
#$GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE', $SECTION_NAME = 'FOUNDATION');

#$GLOBALS['CONFIG_MANAGER']->setIniAsConstant($LOAD_ID = 'JCORE', $SECTION_NAME = 'FOUNDATION');
foreach($GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE', $SECTION_NAME = 'FOUNDATION') AS $key => $value ){
	CONFIG_MANAGER::wrapDefine($value, $key, $prepend='JCORE_'); // define ($key, $value);
}
#echo __FILE__.__LINE__.'---------\---\---/assd<br>';

/****************************************************************************/
/****************************************************************************/
/**
* BEGIN the logging section
* @tutorial JCORE.pkg, JCORE/LOAD.pkg
*/
#set_error_handler ( callback $error_handler [, int $error_types = E_ALL | E_STRICT ] )
#trigger_error ( string $error_msg [, int $error_type = E_USER_NOTICE ] )
#restore_error_handler()

$GLOBALS['LOG_ERROR'] = new LOGGER(
	$GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG', $SECTION_NAME = 'JCORE')
);
$GLOBALS['LOG_DATA'] = new LOGGER(
	$GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG', $SECTION_NAME = 'JCORE_DATA_LOG')
);
$GLOBALS['LOG_CACHE'] = new LOGGER(
	$GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG', $SECTION_NAME = 'JCORE_CACHE_LOG')
);

/****
* END the logging section
* @tutorial JCORE.pkg, JCORE/LOAD.pkg
*/

/****************************************************************************/
/****************************************************************************/
/****
* BEGIN the cache section
* @tutorial JCORE.pkg, JCORE/LOAD.pkg
*/
$GLOBALS['CONFIG_MANAGER']->loadIniTree($LOAD_ID='CACHE_SOURCE', $FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/CACHE_SOURCE.ini');

$CACHECFG = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'CACHE_SOURCE'); //, $SECTION_NAME = 'FOUNDATION'
#echo('$CACHECFG<pre>['.var_export($CACHECFG, true).']</pre>').'<br>'; 

/****
* END the cache section
*/
/****************************************************************************/
/****************************************************************************/
/****
* BEGIN Data Layer
* @tutorial JCORE.pkg, JCORE/LOAD.pkg
* look for the real file, load the default if it doens't exist
*/
switch(true){
	case file_exists(JCORE_CONFIG_DIR.'SERVICE/DATA/DATA.ini'): 
		$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA/DATA.ini';
		break;
	case file_exists(JCORE_CONFIG_DIR.'SERVICE/DATA/DATA.default.ini'): 
		$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA.default.ini';
		break;
	case file_exists(JCORE_CONFIG_DIR.'SERVICE/DATA.ini'):
		$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA.ini';
		break;
	
	default:	
		$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA.default.ini';
		break;
}
/*
if(true === file_exists(JCORE_CONFIG_DIR.'SERVICE/DATA.ini')){
	$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA.ini';
}else{
	$FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/DATA.default.ini';
}
*/
//DATA.default.ini
$GLOBALS['CONFIG_MANAGER']->loadIni($LOAD_ID='DATA_API', $FILE_NAME);


$DATA_API_CFG = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'DATA_API'); //, $SECTION_NAME = 'FOUNDATION'
#echo('$DATA_API_CFG<pre>['.var_export($DATA_API_CFG, true).']</pre>').'<br>'; 
$GLOBALS['DATA_API'] = new DATA_API($DATA_API_CFG);


/****
* END Data Layer
*/



/****************************************************************************/
/****************************************************************************/
/****
* BEGIN the transport section
*/
require_once(JCORE_BASE_DIR.'TRANSPORT/TRANSPORT_FILTER.php');
require_once(JCORE_BASE_DIR.'TRANSPORT/TRANSPORT_INTERFACE.interface.php');
require_once(JCORE_BASE_DIR.'TRANSPORT/SOA/SOA_BASE.class.php');


/****
* END the transport section
*/
/****************************************************************************/
/****************************************************************************/
/****
* BEGIN the plugin section, this moves to another file?
if API TYPE == SOA
	load the auto load structure
	otherwise load on demand
*/


/**
*
this will get loaded when the plugins are ready to be called,
* do this in the API config
require_once(JCORE_BASE_DIR.'/LOAD/AUTOLOAD.php');
*/
unset($BOOTSTRAP);
?>
