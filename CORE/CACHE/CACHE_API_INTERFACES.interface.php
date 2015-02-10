<?
/**
 * CACHE interface Service
 * Connection Classes can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this interface
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE Service
 * 
 * moved to its own file
interface CACHE_COMMON_API_INTERFACE_depricated{
	* 
	* Defines base fuctions to support public methods of CACHE_API 
	* interface for common frequent reads, occasional writes to cache 
	public function setValue($args = array());
	*
	public function intialize($cfg = array());
	public function getValue($args = array());
	public function setValue($args = array());
	* 
	* Defines fuctions to support access to shared resoures 
	* interface for common frequent reads, frequent writes to cache 
	*
	public function updateSharedValue($args = array());
	public function setSharedValue($args = array());
	public function getSharedValue($args = array());
	
}
 */
/**
redundant, no need for separation @  this point
interface CACHE_SHARED_API_INTERFACE{

}
//moved to own file and updated
interface PHP_SESSION_depricated{
	#public function __construct(){}
	public function open($savePath, $session);
	public function close();
	public function read();
	public function write();
	public function destroy();
	public function garbageCollection();

}
*/

?>