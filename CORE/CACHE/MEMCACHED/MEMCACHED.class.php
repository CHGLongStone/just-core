<?
/**
 * Memcached Class
 * Connection Classes can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this interface
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 * 
 *
 */
/**
*
*/
$filePath = JCORE_BASE_DIR.'CACHE/CACHE_COMMON_API_INTERFACE.interface.php';
require_once($filePath);
/**
 * @package		JCORE
 * @subpackage	CACHE
*/
class MEMCACHED implements CACHE_COMMON_API_INTERFACE{
	
	private $intialized = false;
	/**
	* DESCRIPTOR: 
	* @param null
	* @return null
	*/
	public function __construct(){
	
	
	}
	/**
	* DESCRIPTOR: 
	* @param null
	* @return null
	*/
	public function intialize(){
		$this->intialized = true;
	}
	/**
	* DESCRIPTOR: 
	* @param null
	* @return bool
	*/
	public function isIntialized(){
		if($this->intialized === true){
			return true;
		}
	}
	
	public function getValue($args = array()){
		#mixed eaccelerator_get (string $key) 
		$value = eaccelerator_get($key);
	}
	public function setValue($args = array()){
		#boolean eaccelerator_put (string $key, mixed $value, [int $ttl = 0]) 
		eaccelerator_put();
	}
	public function updateSharedValue($args = array()){
	
	}
	public function setSharedValue($args = array()){
	
	}
	public function getSharedValue($args = array()){
	
	
	}
	
	
}
?>