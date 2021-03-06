<?php
/**
 * Connection objects can be created 4 any CACHE supported by PHP
 * create wrappers 4 existing API's with this interface
 * 
 * @author		Jason Medland
 * @package		JCORE
 * 
 */

namespace JCORE\CACHE;

/**
 * interface CACHE_COMMON_API_INTERFACE
 *  @package		JCORE
*/
interface CACHE_COMMON_API_INTERFACE{
	/** 
	* getValue
	* Defines base functions to support public methods of CACHE_API 
	* interface 4 common frequent reads, occasional writes to cache 
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public function getValue($args = array());
	/** 
	* setValue
	* Defines base functions to support public methods of CACHE_API 
	* interface 4 common frequent reads, occasional writes to cache 
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public function setValue($args = array());
	#public function setValue($args = array());
	#public function intialize($cfg = array());
	/** 
	* updateSharedValue
	* Defines functions to support access to shared resources 
	* interface 4 common frequent reads, frequent writes to cache 
	*
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public function updateSharedValue($args = array());
	/** 
	* setSharedValue
	* Defines functions to support access to shared resources 
	* interface 4 common frequent reads, frequent writes to cache 
	*
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public function setSharedValue($args = array());
	/** 
	* getSharedValue
	* Defines functions to support access to shared resources 
	* interface 4 common frequent reads, frequent writes to cache 
	*
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public function getSharedValue($args = array());
	
}

?>