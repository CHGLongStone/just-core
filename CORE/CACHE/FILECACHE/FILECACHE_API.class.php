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
 * @package	JCORE\CACHE
 * 
*/
namespace JCORE\CACHE;

use JCORE\CACHE\CACHE_COMMON_API_INTERFACE as CACHE_COMMON_API_INTERFACE;
use JCORE\EXCEPTION\ERROR as ERROR;
/**
 * class FILECACHE_API
 *
 * @implements JCORE\CACHE\CACHE_COMMON_API_INTERFACE
 * @package JCORE\CACHE
*/
class FILECACHE_API implements CACHE_COMMON_API_INTERFACE {
	
	/**
	 * error
	 * @access public 
	 * @var array 
	 */
	protected $error = array(); // ;
	/**
	 * SOMEVAR
	 * @access public 
	 * @var array 
	 */
	public $SOMEVAR; // = array();
	/**
	* DESCRIPTOR: __construct
	* 
	* 
	* @param array args
	* @return null
	*/
	public function __construct($args=null){
		#echo __METHOD__.'@'.__LINE__.' <pre>'.var_export($args,true).'</pre><br>';
		
		if(!isset($args["CSN"])){
			return false;
		}else{
			#$this->CSN = $args["CSN"];
			$this->CSN = $args; #["CSN"];
			#$this->CSN[$args["CSN"]] = $args; #["CSN"];
		}
		
		#echo __METHOD__.'@'.__LINE__.'GLOBALS <pre>'.var_export($GLOBALS,true).'</pre><br>';
		if(isset($this->CSN["DIRECTORIES"]) && is_array($this->CSN["DIRECTORIES"])){
			foreach($this->CSN["DIRECTORIES"] AS $key => $value){
				//DIRECTORIES
				#echo __METHOD__.'@'. __LINE__ .'key['.$key.'] <pre>'.var_export($value,true).'</pre><br>';
				if($value["RELATIVE"] == TRUE && isset($GLOBALS['APPLICATION_ROOT'])){
					$this->CSN["DIRECTORIES"][$key]["PATH"] = $GLOBALS['APPLICATION_ROOT'].$value["PATH"];
				}
				#echo __METHOD__.'@'. __LINE__ .'$this->CSN["DIRECTORIES"]['.$key.']["PATH"]['.$this->CSN["DIRECTORIES"][$key]["PATH"].'] <br>';
				
				if(!file_exists($this->CSN["DIRECTORIES"][$key]["PATH"])){
					$args = array(
						"Code" => 110,
						"Data" => __FILE__.'@'.__LINE__.'  '.json_encode($args),
						//"Message" => "",
					);
					$error = new ERROR($args);
					$this->error[] = $error->getError();
				}
				
			}
		}
		
		if(1 <= count($this->error)){
			#echo __METHOD__.'@'.__LINE__.'$this->error <pre>'.var_export($this->error,true).'</pre><br>';
			return json_encode($this->error);
		}
		#echo __METHOD__.'@'.__LINE__.'$this->CSN <pre>'.var_export($this->CSN,true).'</pre><br>';
		
		return true;
	}
	
	
	//----------//----------//----------//----------//----------
	//----------//USER RESOURCES 		//----------//----------
	//----------//----------//----------//----------//----------
	/**
	* DESCRIPTOR: Does a "Get" on a Memcached resource 
	* 	args is an array that MUST follow this format
	* 	args["KEY"] 		= [string]; 		- a Memcached asset ID
	* 	args["cas_token"] 	= &cas_token; 		- a var passed by reference to return the cas_token used by: updateSharedResource
	* 	args["cache_cb"] 	= NULL/[string]; 	- the call back function. probably not much use for resetting (big tangle, too many functions) BUT could be useful for logging
	* 
	* 
	* @param array args
	* @return null
	*/
	public function getValue($args = array()){
		echo 'METHOD['.__METHOD__.'] LINE['.__LINE__.']'.'<br>';
		//CACHE_POOL CACHE_KEY
		$CACHED_VAR = FALSE;
		if($this->validateBasicArgs($args) === false){
			echo 'METHOD['.__METHOD__.'] validateBasicArgs'.'<br>';
			$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
			return false;
		}
		#echo 'METHOD['.__METHOD__.'] GOOOD!!!!'.'<br>';
		
		// $args["CACHE_POOL"];
		#echo 'METHOD['.__METHOD__.'] CACHE_POOL_OBJECT[<pre>'.var_export($CACHE_POOL_OBJECT, true).'<pre>]'.'<br>';
		/**
		* execution block
		*/
		$CACHE_POOL_OBJECT = $this->getMemcachedObject($args["CACHE_POOL"]);
		if(true == $CACHE_POOL_OBJECT){
			$CACHED_VAR = $CACHE_POOL_OBJECT->get($args["CACHE_KEY"]);
			$resultCode = $this->checkResultCode($CACHE_POOL_OBJECT, $args["CACHE_KEY"]);
			if(true == $resultCode && is_bool($resultCode)){
				return $CACHED_VAR;
			}
		}
		if(!isset($resultCode) || !is_numeric($resultCode)){
			$resultCode = Memcached::RES_FAILURE;
		}
		$message = $this->getResultCodeString($resultCode);
		$result['EXCEPTION']["ID"] = $resultCode;
		$result['EXCEPTION']["MSG"] = $message;
		echo 'METHOD['.__METHOD__.'] FAILED['.__LINE__.']'.'<br>';
		
		return false;
		#mixed Memcached::get  ( string $key  [, callback $cache_cb  [, float &$cas_token  ]] )

	}

	/**
	* DESCRIPTOR: Does a "Check and Set" on a shared resource 
	* 	$args is an array that MUST follow this format
	* 	$args["KEY"] 		= [string]; 		- a Memcached asset ID
	* 	$args["value"] 		= [mixed]; 			- a asset to be stored in Memcached
	* 	$args["expiration"] = [int]; 			- the call back function. probably not much use for resetting (big tangle, too many functions) BUT could be usefull for logging
	* 
	* 
	* @param array args
	* @return null
	*/
	public function setValue($args = array()){
		echo __METHOD__.'@'.__LINE__.' <pre>'.var_export($args,true).'</pre><br>';
		//CACHE_POOL CACHE_KEY
		if($this->validateBasicArgs($args) === false){
			$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
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
	/**
	* updateSharedValue
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function updateSharedValue($args = array()){
	
	}
	/**
	* setSharedValue
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function setSharedValue($args = array()){
	
	}
	/**
	* getSharedValue
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function getSharedValue($args = array()){
	
	
	}
	
	//----------//----------//----------//----------//----------
	//----------//START UTIL FUNCTIONS 	//----------//----------
	//----------//----------//----------//----------//----------
	/**
	* validateBasicArgs
	* just check that there are args in the array
	* define it here for re-use
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function validateBasicArgs($args = array()){
		echo 'METHOD['.__METHOD__.'] '.'<br>';
		if($this->verifyArgs($args) === false){
			$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT ARGS!!!!');
			return false;
		}
		if($this->validateCachePool($args) === false){
			$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
			return false;
		}
		if($this->validateCacheKey($args) === false){
			$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_KEY!!!!');
			return false;
		}
		
		#echo 'METHOD['.__METHOD__.'] GOOOD'.'<br>';
		return true;
	}
	
		
	/**
	* verifyArgs
	* just check that there are args in the array
	* define it here for re-use
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function verifyArgs($args = array()){
		echo 'METHOD['.__METHOD__.'] count($args)['.count($args).']'.'<br>';
		if(count($args) == 0){
			#$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT ARGS!!!!');
			return false;
		}
		return true;
	}
	/**
	* validateCachePool
	* just check that there are args in the array
	* define it here for re-use
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function validateCachePool($args){
		echo 'METHOD['.__METHOD__.'] $args["CACHE_POOL"]['.$args["CACHE_POOL"].']'.'<br>';
		if(!isset($args["CACHE_POOL"]) || $args["CACHE_POOL"] == ''){
			#$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
			return false;
		}
		return true;
	}
	
	/**
	* validateCacheKey
	* just check that there are args in the array
	* define it here for re-use
	* 
	* @access public
	* @param array args
	* @return bool 
	*/
	public function validateCacheKey($args = array()){	
		echo 'METHOD['.__METHOD__.'] $args["CACHE_KEY"]['.$args["CACHE_KEY"].']'.'<br>';
		if(!isset($args["CACHE_KEY"]) || $args["CACHE_KEY"] == ''){
			#$GLOBALS["LOG_CACHE"]->log(LOG_WARNING,__METHOD__, 'CALLED WITHOUT CACHE_POOL!!!!');
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