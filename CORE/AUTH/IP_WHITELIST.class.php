<?php
/**
 * 
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\AUTH
 */

namespace JCORE\AUTH;

use JCORE\TRANSPORT\HTTP\HTTP_UTIL as HTTP_UTIL;

/**
 * Class IP_WHITELIST
 * Very basic auth mechanism to white list API calls from other servers
 * this is ONLY in place to limit access to an API based on white list
 * there is no other authentication hook behind this fro granular control
 *
 * @package JCORE\AUTH
*/
class IP_WHITELIST implements AUTH_INTERFACE{

	/**
	* __construct
	* 
	* @access public
	* @param null
	* @return null 
	*/
	public function __construct(){
		
		
	}
	
	
	/**
	* authenticate
	* 
	* @access public
	* @param array params
	* @return null 
	*/
	public function authenticate($params = null){
		
		if(null == $params){
			return false;
		}
		$remoteAddress = HTTP_UTIL::get_ip_address();
		if(isset($params['SERVICE_NAME'])){
			$IPList = $this->getIPList($params['AUTH_TYPE'],$params['SERVICE_NAME']);
		}else{
			$IPList = $this->getIPList($params['AUTH_TYPE']);
		}
		/*
		echo __METHOD__.__LINE__.'$IPList<pre>['.var_export($IPList, true).']</pre>'.PHP_EOL; 
		echo __METHOD__.__LINE__.'$remoteAddress<pre>['.var_export($remoteAddress, true).']</pre>'.PHP_EOL; 
		echo __METHOD__.__LINE__.'in_array($remoteAddress, $IPList<pre>['.var_export(in_array($remoteAddress, $IPList), true).']</pre>'.PHP_EOL; 
		*/	
		if(true === in_array($remoteAddress, $IPList)){
			return true;
		}
		return false;		
	}	
	/**
	* authorize
	* 
	* @access public
	* @param array params
	* @return null 
	*/
	public function authorize($params = null){
		return false;
		/**
		the only 
		if(null == $params){
			return false;
		}

		* fill in the implementation
		*/
	}
	/**
	* getIPList
	* 
	* @access public
	* @param mixed params
	* @param mixed params
	* @return null 
	*/
	public function getIPList($AUTH_TYPE=null,$SERVICE_NAME=null){ 
		#echo __METHOD__.__LINE__.'$AUTH_TYPE['.$AUTH_TYPE.']  $SERVICE_NAME['.$SERVICE_NAME.']  '.PHP_EOL; 
		$IPList =  $GLOBALS["CONFIG_MANAGER"]->getSetting("AUTH","IP_WHITELIST",$AUTH_TYPE);
			
		if(null !== $SERVICE_NAME && isset($IPList[$SERVICE_NAME])){
			return $IPList[$SERVICE_NAME];
		}
			
		return $IPList;
	}
	
	
}