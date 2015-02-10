<?
/**
 * CACHE interface Service
 * Connection Classes can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this interface
 * see http://us2.php.net/manual/en/function.session-set-save-handler.php
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 *
 *
 *
 */
/**
*
*
 * @package		JCORE
 * @subpackage	CACHE
*/

class SESSION implements PHP_SESSION{
	/**
	*
	*/
	public function __construct(){
	}
	/**
	*
	//first is the save path and the second is the session name
	*/	
	public function open($savePath, $sessioneName){
		return false;
	} 
	/**
	*
	*/
	public function close(){
		return false;
	}
	/**
	*
	//must return string value 
	*/
	public function read($key, $data){
		
		return false;
	}
	/**
	*
	//identifier and the data associated with it.
	*/
	public function write($key, $data){
		return false;
	}
	/**
	*
	*/
	public function destroy(){
	
		return false;
	}
	/**
	*
	//max session lifetime as its only parameter.
	*/
	public function gc($ttl){
		return false;
	}

}

?>