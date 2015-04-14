<?php
/**
 * Very basic auth mechanism to white list API calls from other servers
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	AUTH
 */

namespace JCORE\AUTH;

use JCORE\TRANSPORT\HTTP\HTTP_UTIL as HTTP_UTIL;

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
		echo __METHOD__.__LINE__.'$params['.var_export($params, true).']'.PHP_EOL; 
		if(null == $params){
			return false;
		}
		$remoteAddress = HTTP_UTIL::get_ip_address();
		
		
		echo __METHOD__.__LINE__.'$remoteAddress<pre>['.var_export($remoteAddress, true).']</pre>'.PHP_EOL; 
		
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