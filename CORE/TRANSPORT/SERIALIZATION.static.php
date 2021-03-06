<?php
/**
 * SERIALIZATION
 * 
 * @ignore
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT
 * 
 */

namespace JCORE\TRANSPORT;

/**
 * Interface SERIALIZATION_STATIC
 *
 * @package JCORE\TRANSPORT
*/
class SERIALIZATION_STATIC {

	/**
	* ln -s /var/www/PhpDocumentor-1.4.3 /var/www/HTTP/default_admin_http/PHPDOC 
	//var/www/JCORE/APIS/default_admin_http
	*/
	private function __construct(){
	
	}
	/**
	* CACHE_SERIALIZATION
	* args["DATA"] 					= [STRING/ARRAY/OBJECT] passed to serialization method
	* args["CACHE_SERIALIZATION"] 		= [JSON/NATIVE/RAW]
	* 		JSON						- json_encode($args["DATA"])
	* 		NATIVE						- serialize($args["DATA"])
	* 		RAW							- [string]
	* args["UNSERIALIZE_TYPE"]			= [def:ARRAY/OBJECT/RAW(string)] for implementations that leverage JSON data and use json_decode ( string $json [, bool $assoc = false]...)
	* 
	* 
	* @access public 
	* @param string $args
	* @return bool
	*/
	public static function serialize($args=array()){
		if(!isset($args["DATA"])){
			return false;
		}
		/**
		* Set the CACHE_SERIALIZATION type 
		* if $args["CACHE_SERIALIZATION"] is set process as needed
		* fail back to: constant JCORE_SYSTEM_CACHE_SERIALIZATION set in [API]/config.php
		* default to JSON if no value is provided
		*/
		if(isset($args["CACHE_SERIALIZATION"])){
			switch($args["CACHE_SERIALIZATION"]){
				case"RAW": 
					return $args["DATA"];
				case"NATIVE":
					$CACHE_SERIALIZATION = 'PHP';
					break;
				case"JSON":
				default:
					$CACHE_SERIALIZATION = 'JSON';
					break;
			}
			
		}elseif(defined(JCORE_SYSTEM_CACHE_SERIALIZATION)){
			$CACHE_SERIALIZATION = JCORE_SYSTEM_CACHE_SERIALIZATION;
		}else{ //		if(!isset($CACHE_SERIALIZATION)){
			$CACHE_SERIALIZATION = 'JSON';
		}
		
		/**
		* methods may also be invoked statically using this function by passing array($classname, $methodname) 
		* to this parameter. Additionally methods of an object instance may be called by passing 
		* array($objectinstance, $methodname) to this parameter. 
		* call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] ) $callBackFunction
		*/
		
		$callBackFunction = __CLASS__.'::'.'serialize'.$CACHE_SERIALIZATION;
		switch($CACHE_SERIALIZATION){
			case"PHP":
				return call_user_func ( $callBackFunction, $args["DATA"]);
				break;
			case"JSON":
				return call_user_func ( $callBackFunction, $args["DATA"]);
			default: //JSON
				$e = new Exception('INVALID CACHE_SERIALIZATION TYPE ['.$CACHE_SERIALIZATION.']');
				#return $e->getTraceAsString();
				return $e->getMessage();
				break;
		}
		return false;
	}
	/**
	* CACHE_SERIALIZATION
	* args["DATA"] 					= [STRING/ARRAY/OBJECT] passed to serialization method
	* args["CACHE_SERIALIZATION"] 		= [JSON/NATIVE/RAW]
	* 		JSON						- json_encode($args["DATA"])
	* 		NATIVE						- serialize($args["DATA"])
	* 		RAW							- [string]
	* args["UNSERIALIZE_TYPE"]			= [def:ARRAY/OBJECT/RAW] 
	* 		for implementations that leverage JSON data and use:
	* 			json_decode ( string $json [, bool $assoc = false]...)
	* 
	* 
	* @access public 
	* @param string $args
	* @return bool
	*/
	public static function unserialize($args=array()){
		if(!isset($args["DATA"])){
			return false;
		}
		/**
		* if $args["CACHE_SERIALIZATION"] is set process as needed
		* fail back to: constant JCORE_SYSTEM_CACHE_SERIALIZATION set in [API]/config.php
		*/
		#$callBackFunction = 'serialize';
		if(isset($args["CACHE_SERIALIZATION"])){
			switch($args["CACHE_SERIALIZATION"]){
				case"RAW": 
					return $args["DATA"];
				case"NATIVE":
					$CACHE_SERIALIZATION = 'PHP';
					break;
				case"JSON":
				default:
					$CACHE_SERIALIZATION = 'JSON';		
					/**
					*  defaulting to arrays from JSON
					*/
					$args["assoc"] = TRUE; //'ARRAY'; 
					if(isset($args["UNSERIALIZE_TYPE"]) && $args["UNSERIALIZE_TYPE"] != 'ARRAY'){
						$args["assoc"] = FALSE;//'OBJECT';
						unset($args["UNSERIALIZE_TYPE"]);
					}
				
					break;
			}
			
		}elseif(defined(JCORE_SYSTEM_CACHE_SERIALIZATION)){
			$CACHE_SERIALIZATION = JCORE_SYSTEM_CACHE_SERIALIZATION;
		}
		if(!isset($CACHE_SERIALIZATION)){
			$CACHE_SERIALIZATION = 'JSON';
		}
		
		/**
		* methods may also be invoked statically using this function by passing array($classname, $methodname) 
		* to this parameter. Additionally methods of an object instance may be called by passing 
		* array($objectinstance, $methodname) to this parameter. 
		*/
		#call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] ) $callBackFunction
		
		$callBackFunction = __CLASS__.'::'.'serialize'.$CACHE_SERIALIZATION;
		switch($CACHE_SERIALIZATION){
			case"PHP":
				return call_user_func ( $callBackFunction, $args["DATA"]);
				break;
			case"JSON":
				return call_user_func ( $callBackFunction, $args["DATA"], $args["assoc"]);
				break;
			
			default: //JSON
				$e = new Exception('INVALID CACHE_SERIALIZATION TYPE ['.$CACHE_SERIALIZATION.']');
				#return $e->getTraceAsString();
				return $e->getMessage();
				break;
		}
		return false;
	
	}		
	/**
	* NATIVE
	* @param array $args
	* @param array $args["DATA"]
	* @return string $serialized
	*/			
	public static function serializePHP($args=array()){
		if(!isset($args["DATA"])){
			return false;
		}
		return serialize($args["DATA"]);
	}
	/**
	* json_encode ( mixed $value [, int $options = 0 ] )
	* 5.3 [,options Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_FORCE_OBJECT. ]
	* 
	* @param $args mixed
	* @return string $JSON
	*/
	public static function serializeJSON($args=array()){
		#echo __METHOD__.__LINE__.'$args<pre>'.var_export($args, true).'</pre><br>';
		if(!isset($args["DATA"])){
			return false;
		}
		
		return json_encode($args["DATA"]);		//
	}
	/**
	* default serializer if args are provided they will be execured
	* json_decode ( string $json [, bool $assoc = false [, int $depth = 512 [, int $options = 0 ]]] )
	* $args["assoc"] 		[TRUE/FALSE] bool
	* $args["DATA"]			array|object|string
	* 
	* @param mixed $args
	* @param mixed $args[""]
	* @return array|object|string
	*/
	public static function unserializeJSON($args=array()){
		#echo __METHOD__.__LINE__.'$args<pre>'.var_export($args, true).'</pre><br>';
		if(!isset($args["DATA"])){
			return false;
		}
		$args["assoc"] = TRUE;
		if(isset($args["assoc"]) && $args["assoc"] !== TRUE){
			$args["assoc"] = FALSE;
		}
		/**
		* suppress the error til 5.3 
		* json_last_error() [PHP 5 >= 5.3.0]
		*/
		return @json_decode($args["DATA"],$args["assoc"]);
	}
	/**
	* unserializePHP native
	* 
	* @param mixed $args
	* @return array|object|string
	*/
	public static function unserializePHP($args=array()){
		if(!isset($args["DATA"])){
			return false;
		}
		return unserialize($args["DATA"]);
	}
}

?>