<?php
/**
 * Connection Classes can be created for any CACHE supported by PHP
 * create wrappers for existing API's with this interface
 * 
 * 
 * @author		Jason Medland
 * @package		JCORE
 * @subpackage	CACHE
 */
namespace JCORE\CACHE;
use JCORE\JCORE_SINGLETON_INTERFACE AS JCORE_SINGLETON_INTERFACE;
/**
 * Interface STATIC_API_INTERFACE
 *
 * @package JCORE\CACHE
*/
interface STATIC_API_INTERFACE extends JCORE_SINGLETON_INTERFACE
{ 
	/** 
	* Defines base functions to support public methods of CACHE_API 
	* interface for common frequent reads, occasional writes to cache 
	* DESCRIPTOR: getValue
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public static function getValue($args = array());
	/** 
	* Defines base functions to support public methods of CACHE_API 
	* interface for common frequent reads, occasional writes to cache 
	* DESCRIPTOR: setValue
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public static function setValue($args = array());
	/** 
	* Defines functions to support access to shared resoures 
	* interface for common frequent reads, frequent writes to cache 
	* DESCRIPTOR: updateSharedValue
	* 
	* @access public
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public static function updateSharedValue($args = array());
	/** 
	* Defines functions to support access to shared resoures 
	* interface for common frequent reads, frequent writes to cache 
	* DESCRIPTOR: setSharedValue
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public static function setSharedValue($args = array());
	/** 
	* Defines functions to support access to shared resoures 
	* interface for common frequent reads, frequent writes to cache 
	* DESCRIPTOR: getSharedValue
	* 
	* @access public
	* @param array args 
	* @return return NULL  
	*/
	public static function getSharedValue($args = array());
	/**
	* 
	*/
	#public static function sissslestones();
	#public static function getSharedValue($args = array());
	#public function setValue($args = array());
	#public function intialize($cfg = array());
}

?>