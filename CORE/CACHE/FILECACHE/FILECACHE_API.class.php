<?php
/**
 * DESCRIPTIVE NAME
 * this just give you the ability to write files
 * this is useful for rendered HTML/XML/JSON or fragments etc.
 * if caching data (writing arrays to files etc) 
 * you are strongly recommended to use some form of opcode caching with it
 * so your server isn't beaten to death on I/O
 * 
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	CACHE

*/
namespace JCORE\CACHE;

use JCORE\CACHE\CACHE_COMMON_API_INTERFACE as CACHE_COMMON_API_INTERFACE;
/**
 * class FILECACHE_API
 *
 * @package JCORE\CACHE
*/
class FILECACHE_API implements CACHE_COMMON_API_INTERFACE {
	/**
	 * @access public 
	 * @var string
	 */
	public $SOMEVAR; // = array();
	private function __construct(){
	
	}
	
	
	//----------//----------//----------//----------//----------
	//----------//USER RESOURCES 		//----------//----------
	//----------//----------//----------//----------//----------
	/**
	* DESCRIPTOR: Does a "Get" on a Memcached resource 
	* 	$args is an array that MUST follow this format
	* 	$args["KEY"] 		= [string]; 		// a Memcached asset ID
	* 	$args["cas_token"] 	= &$cas_token; 		// a var passed by reference to return the cas_token used by: updateSharedResource
	* 	$args["cache_cb"] 	= NULL/[string]; 	// the call back function. probably not much use for resetting (big tangle, too many functions) BUT could be usefull for logging
	*/
	public static function getValue($args = array()){
		echo 'METHOD['.__METHOD__.'] LINE['.__LINE__.']'.'<br>';
		$CACHED_VAR = FALSE;
		if(MEMCACHED_API::validateBasicArgs($args) === false){
			echo 'METHOD['.__METHOD__.'] validateBasicArgs'.'<br>';
			$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
			return false;
		}
		#echo 'METHOD['.__METHOD__.'] GOOOD!!!!'.'<br>';
		
		// $args["CACHE_POOL"];
		#echo 'METHOD['.__METHOD__.'] CACHE_POOL_OBJECT[<pre>'.var_export($CACHE_POOL_OBJECT, true).'<pre>]'.'<br>';
		/**
		* execution block
		*/
		$CACHE_POOL_OBJECT = MEMCACHED_API::getMemcachedObject($args["CACHE_POOL"]);
		if(true == $CACHE_POOL_OBJECT){
			$CACHED_VAR = $CACHE_POOL_OBJECT->get($args["CACHE_KEY"]);
			$resultCode = MEMCACHED_API::checkResultCode($CACHE_POOL_OBJECT, $args["CACHE_KEY"]);
			if(true == $resultCode && is_bool($resultCode)){
				return $CACHED_VAR;
			}
		}
		if(!isset($resultCode) || !is_numeric($resultCode)){
			$resultCode = Memcached::RES_FAILURE;
		}
		$message = MEMCACHED_API::getResultCodeString($resultCode);
		$result['EXCEPTION']["ID"] = $resultCode;
		$result['EXCEPTION']["MSG"] = $message;
		echo 'METHOD['.__METHOD__.'] FAILED['.__LINE__.']'.'<br>';
		
		return false;
		#mixed Memcached::get  ( string $key  [, callback $cache_cb  [, float &$cas_token  ]] )

	}

	/**
	* DESCRIPTOR: Does a "Check and Set" on a shared resource 
	* 	$args is an array that MUST follow this format
	* 	$args["KEY"] 		= [string]; 		// a Memcached asset ID
	* 	$args["value"] 		= [mixed]; 			// a asset to be stored in Memcached
	* 	$args["expiration"] = [int]; 	// the call back function. probably not much use for resetting (big tangle, too many functions) BUT could be usefull for logging
	*/
	public static function setValue($args = array()){
		
		if(MEMCACHED_API::validateBasicArgs($args) === false){
			$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
			return false;
		}
		
		
		#$CACHE_POOL, $CACHE_KEY
		#bool Memcached::set  ( string $key  , mixed $value  [, int $expiration  ] ) Store an item
		#TRUE on success or FALSE on failure. 
		#Use Memcached::getResultCode if necessary. 
	}
	//----------//----------//----------//----------//----------
	//----------//END USER RESOURCES 	//----------//----------
	//----------//----------//----------//----------//----------
	
	//----------//----------//----------//----------//----------
	//----------//START UTIL FUNCTIONS 	//----------//----------
	//----------//----------//----------//----------//----------
	/*
	* just check that there are args in the array
	* define it here for re-use
	*/
	public static function validateBasicArgs($args = array()){
		echo 'METHOD['.__METHOD__.'] '.'<br>';
		if(MEMCACHED_API::verifyArgs($args) === false){
			$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT ARGS!!!!');
			return false;
		}
		if(MEMCACHED_API::validateCachePool($args) === false){
			$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
			return false;
		}
		if(MEMCACHED_API::validateCacheKey($args) === false){
			$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
			return false;
		}
		
		#echo 'METHOD['.__METHOD__.'] GOOOD'.'<br>';
		return true;
	}
	
		
	/*
	* just check that there are args in the array
	* define it here for re-use
	*/
	public static function verifyArgs($args = array()){
		echo 'METHOD['.__METHOD__.'] count($args)['.count($args).']'.'<br>';
		if(count($args) == 0){
			#$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT ARGS!!!!');
			return false;
		}
		return true;
	}
	/*
	* just check that there are args in the array
	* define it here for re-use
	*/
	public static function validateCachePool($args){
		echo 'METHOD['.__METHOD__.'] $args["CACHE_POOL"]['.$args["CACHE_POOL"].']'.'<br>';
		if(!isset($args["CACHE_POOL"]) || $args["CACHE_POOL"] == ''){
			#$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
			return false;
		}
		return true;
	}
	
	/*
	* just check that there are args in the array
	* define it here for re-use
	*/
	public static function validateCacheKey($args = array()){	
		echo 'METHOD['.__METHOD__.'] $args["CACHE_KEY"]['.$args["CACHE_KEY"].']'.'<br>';
		if(!isset($args["CACHE_KEY"]) || $args["CACHE_KEY"] == ''){
			#$GLOBALS["APPLICATION_logger"]->trace(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
			return false;
		}
		#echo 'METHOD['.__METHOD__.'] GOOD!!!!!]'.'<br>';
		return true;
	}

	//----------//----------//----------//----------//----------
	//----------//END UTIL FUNCTIONS 	//----------//----------
	//----------//----------//----------//----------//----------
	
	
}

?>