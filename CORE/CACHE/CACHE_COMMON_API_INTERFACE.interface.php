<?php
/**
 * Connection objects can be created 4 any CACHE supported by PHP
 * create wrappers 4 existing API's with this interface
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 */
namespace JCORE\CACHE;

/**
 * Interface CACHE_COMMON_API_INTERFACE
 *
 * @package JCORE\CACHE
*/
interface CACHE_COMMON_API_INTERFACE{
	/** 
	* Defines base functions to support public methods of CACHE_API 
	* interface 4 common frequent reads, occasional writes to cache 
	*/
	#public function setValue($args = array());
	#public function intialize($cfg = array());
	public function getValue($args = array());
	public function setValue($args = array());
	/** 
	* Defines functions to support access to shared resources 
	* interface 4 common frequent reads, frequent writes to cache 
	*/
	public function updateSharedValue($args = array());
	public function setSharedValue($args = array());
	public function getSharedValue($args = array());
	
}

?>