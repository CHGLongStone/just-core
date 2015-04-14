<?php
/**


 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	AUTH
*/
namespace JCORE\AUTH;
/**
 * interface AUTH_INTERFACE
 *
 * @package JCORE\AUTH
*/
interface AUTH_INTERFACE{
	/**
	* INTERFACE DEFINES BASIC AUTH CALLS 
	 */
	public function authenticate($params = null);
	public function authorize($params = null);

}


?>