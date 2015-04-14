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
		
		echo __METHOD__.__LINE__.'$authClass['.var_export($authClass, true).']'.PHP_EOL; 
		$authCheck = $this->implementation[$authClass]->authenticate($params);
		/*
		$serviceCall = explode('.', $parsedRequest["method"]);
		echo __METHOD__.__LINE__.'$serviceCall['.var_export($serviceCall, true).']'.PHP_EOL; 
		echo __METHOD__.__LINE__.'class_exists('.$serviceCall[0].')['.var_export(class_exists($serviceCall[0]), true).']'.PHP_EOL; 
		echo __METHOD__.__LINE__.' method_exists('.$serviceCall[0].', '.$serviceCall[1].')['.var_export( method_exists($serviceCall[0], $serviceCall[1]), true).']'.PHP_EOL; 
		
		if(class_exists($serviceCall[0]) && method_exists($serviceCall[0], $serviceCall[1])){
			$this->serviceObject = new $serviceCall[0]();
			$serviceResponse = $this->serviceObject->$serviceCall[1]($parsedRequest["params"]);
			#echo __METHOD__.__LINE__.'$serviceResponse<pre>['.var_export($serviceResponse, true).']</pre>'.PHP_EOL; 
			
		}
		*/
	}

}