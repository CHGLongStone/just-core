<?php
/**
* AUTH_INTERFACE
* 
* @author	Jason Medland<jason.medland@gmail.com>
* @package	JCORE\AUTH
*/
namespace JCORE\AUTH;
/**
* interface AUTH_INTERFACE
* INTERFACE DEFINES BASIC AUTH CALLS 
*
* @package JCORE\AUTH
*/
interface AUTH_INTERFACE{

	/**
	* DESCRIPTOR: authenticate
	* 
	* @access public
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function authenticate($params = null);
	/**
	* DESCRIPTOR: authorize
	* 
	* @access public
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function authorize($params = null);

}


?>