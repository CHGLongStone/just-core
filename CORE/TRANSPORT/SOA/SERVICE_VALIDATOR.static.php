<?php
/**
 * Static implementation to validate service objects and method to be called
 * 
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT\SOA
 * @subpackage	JCORE\TRANSPORT\SOA
 */
namespace JCORE\TRANSPORT\SOA;
use JCORE\EXCEPTION\ERROR as ERROR;

/**
 * Class SOA
 *
 * @package JCORE\TRANSPORT\SOA
*/
class SERVICE_VALIDATOR{ 
	/**
	* 
	* @param param NULL
	* @return return  NULL
	*/
	private function __construct(){
		return;
	}
	
	/**
	* 
	* @param param NULL
	* @return string return  NULL
	*/
	public static function validateService($serviceCall = null ){
		if(null == $serviceCall){
			return false;
		}
		$args = array();
		$args['Code'] = '170';
		$args['Message'] = 'Service Call Failed';
		$args['Data'] = $serviceCall;
		
		$serviceCall = explode('.', $serviceCall);
		
		if(class_exists($serviceCall[0])){
			$serviceObject = new $serviceCall[0]();
		}else{
			$args['Message'] = 'Service Object Could Not Be loaded, verify namespace and composer autoload';
			$error = new ERROR($args);
			return $error;
		}
		
		if(!method_exists($serviceObject, 'introspectService')){
			$args['Message'] = 'Service Class Does not extend CORE\TRANSPORT\SOA\SOA_BASE or provide a introspectService method';
			$error = new ERROR($args);
			return $error;
		}
		
		return true;
	}
	
	/**
	* 
	* @param param NULL
	* @return string return  NULL
	*/
	public static function validateServiceCall($serviceCall = null ){
		if(null == $serviceCall){
			return false;
		}
		$args = array();
		$args['Code'] = '170';
		$args['Message'] = 'Service Call Failed';
		$args['Data'] = $serviceCall;
		$serviceCall = explode('.', $serviceCall);
		
		if(class_exists($serviceCall[0])){
			$serviceObject = new $serviceCall[0]();
		}else{
			$args['Message'] = 'Service Object Could Not Be loaded';
			$error = new ERROR($args);
			return $error;
		}
		
		if(method_exists($serviceObject, $serviceCall[1])){
			$serviceMethod = $serviceCall[1];
		}else{
			$args['Message'] = 'Service method Could Not Be called';
			$error = new ERROR($args);
			return $error;
		}
		
		$service =  array();
		$service['object'] = $serviceObject;
		$service['method'] = $serviceMethod;
		
		return $service;
		
	}
	
}


?>