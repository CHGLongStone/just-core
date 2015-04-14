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
	* 
	*/
	public function authorize($params = null){
		if(null == $params){
			return false;
		}
		/**
		* fill in the implementation
		*/
	}
	/**
	* 
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