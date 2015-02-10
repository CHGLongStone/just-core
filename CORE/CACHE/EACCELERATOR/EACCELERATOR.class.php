<?
/**
 * EACCELERATOR CACHE Service
 * Wrapper for the Eaccelerator API
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 *
*/
/**
*
*/	
$CACHE_STATIC_API_INTERFACE = JCORE_BASE_DIR.'CACHE/CACHE_STATIC_API_INTERFACE.interface.php';
require_once($CACHE_STATIC_API_INTERFACE);

/**
* EACCELERATOR class
 * @package		JCORE
 * @subpackage	CACHE
*/
class EACCELERATOR implements CACHE_STATIC_API_INTERFACE{
	
	/**
	* flag
	*/
	private $intialized = false;
	/**
	* DESCRIPTOR: 
	* @param null
	* @return null
	*/
	public function __construct(null){
	
	
	}
	/**
	* DESCRIPTOR: 
	* @param null
	* @return null
	*/
	public function intialize($args){
		if(isset($args) && is_array($args)){
			foreach($args as $key => $value){
				#$keyName = '$this->'.$key;
				$this->cfg[$key] = $value;
			}
		}
		$this->intialized = true;
	}
	/**
	* DESCRIPTOR: 
	* @param null
	* @return bool
	*/
	public function isIntialized(null){
		if($this->intialized === true){
			echo __METHOD__.__LINE__.'<br>';
			return true;
		}
	}
	/**
	* DESCRIPTOR: 
	* result or false on failure
	* $args["KEY"]
	* 
	
	* @param $args array
	* @return mixed
	*/
	public static function getValue($args = array()){
		#mixed eaccelerator_get (string $key) 
		#echo __METHOD__.__LINE__.'<br>';
		
		#echo __METHOD__.__LINE__.'$args<pre>'.var_export($args, true).'</pre><br>';
		
		if(isset($args["KEY"]) && $args["KEY"] != ''){
			#echo __METHOD__.__LINE__.'function_exists-eaccelerator_get-['.function_exists('eaccelerator_get').']<br>';
			$var = eaccelerator_get($args["KEY"]);
			if(is_string($var)){
				#echo __METHOD__.__LINE__.'<pre>'.var_export($var, true).'</pre><br>';
				unset($args);
				$args["DATA"] = $var;
				$args["assoc"] = TRUE;
				$var = SERIALIZATION::unserializeJSON($args);
			}
			#echo __METHOD__.__LINE__.'<pre>'.var_export($var, true).'</pre><br>';
			return $var;
		}
		#echo __METHOD__.__LINE__.'<br>';
		return false;
	}
	/**
	* DESCRIPTOR: 
	* result or false on failure
	* $args["KEY"]
	* $args["DATA"]
	* $args["ttl"]
	* 
	* @param $args array
	* @return mixed
	*/
	public static function setValue($args = array()){
		#boolean eaccelerator_put (string $key, mixed $value, [int $ttl = 0]) 
		if(!isset($args["KEY"]) || $args["KEY"] == ''){
			return false;
		}
		if(!isset($args["DATA"]) || $args["DATA"] === null){
			return false;
		}
		$args["DATA"] = json_encode($args["DATA"]);
		if(isset($args["ttl"]) || $args["ttl"] == ''){
			$args["ttl"] = 0;
		}
		return eaccelerator_put($args["KEY"], $args["DATA"], $args["ttl"]);
	}
	/**
	* DESCRIPTOR: 
	* result or false on failure
	* $args["KEY"]
	* $args["DATA"]
	* $args["ttl"]
	* 
	* @param $args array
	* @return mixed
	*/
	public static function updateSharedValue($args = array()){
		//Warning, you don't need this to lock the keys used with eaccelerator_get and eaccelerator_put
		//boolean eaccelerator_lock (string $key)
		if(!isset($args["KEY"]) || $args["KEY"] == ''){
			return false;
		}
		if(!isset($args["DATA"]) || $args["DATA"] === null){
			return false;
		}
		if(isset($args["ttl"]) || $args["ttl"] == ''){
			$args["ttl"] = 0;
		}
		eaccelerator_lock($args["KEY"]);
		$value =  EACCELERATOR::setValue($args);
		#return eaccelerator_put($key, $value, $ttl=0);
		eaccelerator_unlock($args["KEY"]);
		return $value;
	}
	
	/**
	* DESCRIPTOR: 
	* result or false on failure
	* $args["KEY"]
	* $args["DATA"]
	* $args["ttl"]
	* 
	* @param $args array
	* @return mixed
	*/
	public static function setSharedValue($args = array()){
		#return eaccelerator_put($key, $value, $ttl=0);
		if(!isset($args["KEY"]) || $args["KEY"] == ''){
			return false;
		}
		if(!isset($args["DATA"]) || $args["DATA"] === null){
			return false;
		}
		if(isset($args["ttl"]) || $args["ttl"] == ''){
			$args["ttl"] = 0;
		}
		eaccelerator_lock($args["KEY"]);
		$value =  EACCELERATOR::setValue($args);
		#return eaccelerator_put($key, $value, $ttl=0);
		eaccelerator_unlock($args["KEY"]);
		return $value;
	
	}
	/**
	* DESCRIPTOR: 
	* result or false on failure
	* $args["KEY"]
	* $args["ttl"]
	* 
	* @param $args array
	* @return mixed
	*/
	public static function getSharedValue($args = array()){
		#mixed eaccelerator_get (string $KEY) 
		if(!isset($args["KEY"]) || $args["KEY"] == ''){
			return false;
		}
		eaccelerator_lock($args["KEY"]);
		$value =  EACCELERATOR::getValue($args);
		#return eaccelerator_put($KEY, $value, $ttl=0);
		eaccelerator_unlock($args["KEY"]);
		return $value;

	}
	
	
}
#echo __FILE__.'::'.__LINE__.'--------------/////<br>';
?>