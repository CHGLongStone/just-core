<?
/**
 * Connection Classes can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this interface
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 */
namespace JCORE\CACHE;

/**
 * Interface PHP_SESSION
 *
 * @package JCORE\CACHE
*/
interface PHP_SESSION{
	#public function __construct(){}
	public function open($savePath, $sessionID);
	public function close();
	public function read($key, $data);
	public function write($key, $data);
	public function destroy();
	public function gc($ttl);

}

?>