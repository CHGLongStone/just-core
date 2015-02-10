<?php
/**
 * Connection objects can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this objects public methods
 * implementing CACHE_COMMON_API_INTERFACE_ or CACHE_STATIC_API_INTERFACE_
require_once(JCORE_BASE_DIR."CACHE/CACHE_COMMON_API_INTERFACE.interface.php");
 * 
 * 
 * @author		Jason Medland
 * @package		JCORE\CACHE
 * @subpackage	JCORE\CACHE
 */
namespace JCORE\CACHE;
use JCORE\CACHE\CACHE_COMMON_API_INTERFACE as CACHE_COMMON_API_INTERFACE;
use JCORE\LOG\LOG as LOG;
/**
 * Class CACHE_API2
 *
 * @package JCORE\CACHE
*/
class CACHE_API2 implements CACHE_COMMON_API_INTERFACE{

	/**
	 * @access protected 
	 * @var array
	 * an array to store tables definitions for MySQL/postgres
	 * or the equivelent for NoSQL/File stores
	 */
	protected $cacheCfg = array();  //cfg
	/**
	 * @access private 
	 * @var string
	 */
	
	private $logger; // = new LOG();		
	
	
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	
	/**
	 * Constructor, sets up the cache api and loads cache pool data
	 * nothing is set for the cache pool to save resources, 
	 * connections are automatically set/checked on request
	 * cache implementations can be static or concrete 
	 * 
	 * @param	null
	 * @return	null
	 */
	public function __construct(){
		/**/
		echo __METHOD__.__LINE__.LN;
		GLOBAL $logCFG;
		$settings 		= $logCFG["CACHE"];
		$this->logger	= new LOG($settings);
		$this->logger->trace(LOG_DEBUG,__METHOD__, '()');
		
		
		$this->connector = array();
		return;
	}

	/**
	* DESCRIPTOR: VERIFIES A CONNECTION OBJECT && RESOURCE
	* @param string $CSN 
	* @return outputErrors 
	** done
	*/
	public function verify_cache_source($CSN){
		#echo __METHOD__.__LINE__.LN;
		#$this->logger->trace(LOG_DEBUG,__METHOD__, '(CSN='.$CSN.')');
		//first check if the object is set in the connector

		#echo __METHOD__.__LINE__.LN;
		return;
	}
	
	/**
	* DESCRIPTOR: EXECUTE A QUERY
	* exception handling and logging dealt with
	* @param string $database 
	* @param string $query 
	* @return $result 
	*/
	public function raw($CSN, $query){//, $returnArray=false
		$this->logger->trace(LOG_DEBUG,__METHOD__, '(database='.$database.', query='.$query.')');
		#echo __METHOD__.__LINE__.LN;
		$this->verify_connection($CSN);
		$result = $this->connector[$CSN]->raw($query);

		return $result;
	}
	/**
	* $args["CSN"]
	* $args["KEY"]
	* $args["DATA"]
	* $args["CACHE_SERIALIZATION"] 
	* 		JSON
	* 		NATIVE	[serialize()]
	* 		RAW		[string]
	* $args["UNSERIALIZE_TYPE"]  
	* 		ARRAY
	* 		OBJECT
	* 		RAW		[string]
	* @param array $args
	* @return $result 
	* 
	* 
	* 
	*/
	public function getValue($args = array()){
	}
	public function setValue($args = array()){
	}

	public function updateSharedValue($args = array()){
	}
	public function setSharedValue($args = array()){
	}
	public function getSharedValue($args = array()){
	}
	/*
	*/
	
	
	/**
	
	* DESCRIPTOR: EXECUTE A SELECT
	* if $returnArray === true the function will return the result
	* as a PHP array use stdobj to get an object back
	* @param string $CSN 
	* @param string $query 
	* @param bool $returnArray 
	* @return $result 
	*/
	public function retrieve($CSN, $query, $returnArray=false){
		#echo __METHOD__.__LINE__.LN;
		$this->logger->trace(LOG_DEBUG,__METHOD__, '(CSN='.$CSN.', query='.$query.' returnArray='.$returnArray.')');
		$this->verify_connection($CSN);
		/**
		* now we pass this down to the connection Object
		*/
		$result = $this->connector[$CSN]->retrieve($query, $returnArray);
		#echo __METHOD__.__LINE__.LN;
		return $result;
	}

	
	
	/**
	* must be maintained allow passtrough
	* PASSED DOWN TO THE CONNECCTION OBJECT
	* DESCRIPTOR: coverts a SQL result to a PHP array
	* @param resource $result 
	* @param string $CSN 
	* @return array  $result
	*/
	public function unserialize($result, $type, $CSN){ //type [NATIVE, JSON, PLAINTXT]
		#echo __METHOD__.__LINE__.LN;
		$this->logger->trace(LOG_DEBUG,__METHOD__, '(result='.$result.')');
		#echo '$resultadasdas<pre>'.var_export($result,true).'</pre>';
		
		if(is_array($result)){
			return $result;
		}
		if(isset($CSN)){
			$result = $this->connector[$CSN]->SQLResultToAssoc($result, $query);
		}else{
			$result['EXCEPTION']["ID"] = 0;
			$result['EXCEPTION']["MSG"] = 'FAILED TO PROVIDE $CSN i.e. [AUTH] ';
		}
		
		return $resultArray;
	}
	//----------------------------------------------------
	
	//----------------------------------------------------
	function __destruct(){
		#echo __METHOD__.__LINE__.LN;
		$this->logger->trace(LOG_DEBUG,__METHOD__, '()');
		unset($this->logger); // NOT using global logger now
		return;
	}
}
 

#echo __FILE__.'::'.__LINE__.'OUT'.LN;

?>