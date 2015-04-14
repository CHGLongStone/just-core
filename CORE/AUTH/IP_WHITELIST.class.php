<?php
/**
 * Very basic auth mechanism to white list API calls from other servers
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	AUTH
 */

namespace JCORE\AUTH;

/**
 * Class IP_WHITELIST
 *
 * @package JCORE\AUTH
*/
class IP_WHITELIST implements AUTH_INTERFACE{

	/**
	* 
	*/
	public function __construct(){
		
		
	}
	
	
	/**
	* 
	*/
	public function authenticate($params = null){
		if(null == $params){
			return false;
		}
		
	}	
	/**
	* 
	*/
	public function authorize($params = null){
		if(null == $params){
			return false;
		}
		
	}
	/**
	* 
	*/
	public function getConfigCode($LOAD_ID="ERROR",$SECTION_NAME=null,$SETTING_NAME=null){ 
		return $GLOBALS["CONFIG_MANAGER"]->getSetting("ERROR",$this->Code);
		#$this->Message;
	}
}