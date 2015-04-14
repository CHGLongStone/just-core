<?php
/**
 * Auth Harness allows a pass through to a specific auth (authentication/authorization) implementation
 * auth mechanisms are meant to be "plugin based"
 * The harness stores internally any of the implementations loaded till the end of the request if they need to be recalled
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	AUTH
 */

namespace JCORE\AUTH;

use JCORE\TRANSPORT\SOA\SERVICE_VALIDATOR as SERVICE_VALIDATOR;


/**
 * Class AUTH_HARNESS
 *
 * @package JCORE\AUTH
*/
class AUTH_HARNESS {
	/**
	 * @access public 
	 * @var string
	 */
	protected $implementation = array();
	
	/**
	*
	*/
	public function __construct(){
		
		
	}
	
	/**
	 * authClass must be a string of the full\namespace
	 * 
	 * @access public 
	 * @var string $authClass
	*/
	public function register($authClass = null){
		if(null == $authClass){
			return false;
		}
		if(class_exists($authClass)){
			$this->implementation[$authClass] = new $authClass;
			return true;
		}
			
		return false;
	}
	
		
	/**
	*
	*/
	public function authenticate($authClass = null, $params =  null ){
		if(null == $authClass){
			return false;
		}
		
		$authCheck = $this->implementation[$authClass]->authenticate($params);
		echo __METHOD__.__LINE__.'$authCheck['.var_export($authCheck, true).']'.PHP_EOL; 
		return $authCheck;
	}

}