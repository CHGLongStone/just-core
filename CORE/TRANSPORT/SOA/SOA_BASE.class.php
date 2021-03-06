<?php
/**
 * this object needs to be extended
 * as a primary consideration for SOA, we need to expose the object and it's methods
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT\SOA
 * 
 */
namespace JCORE\TRANSPORT\SOA;

/**
 * Class SOA_BASE
 *
 * @package JCORE\TRANSPORT\SOA
*/
abstract class SOA_BASE { 
	/**
	* empty constructor, this object needs to be extended
	* as a primary consideration for SOA, we need to expose the object and it's methods
	* 
	* @param param NULL
	* @return return  NULL
	*/
	private function __construct(){
		return;
	}
	/**
	* introspectService will provide all of the methods, members and arguements 
	* within scope of the call as well as the document comments assotiatiated 
	* with the extended object. This method can be overridden or extended 
	* to parse this data into a WSDL or similar format 
	* 
	* @param param NULL
	* @return string return  NULL
	*/
	public function introspectService(){
		$calledClass = get_called_class();
		#echo __METHOD__.__LINE__.'$calledClass<pre>['.var_export($calledClass, true).']</pre>'.'<br>'; 
		$reflector = new \ReflectionClass($calledClass);
		#echo __METHOD__.__LINE__.'$reflector<pre>['.var_export($reflector, true).']</pre>'.'<br>'; 
		$barebones = array();
		#$barebones['methods'] = $reflector->getMethods();
		#$barebones['comments'] = $reflector->getDocComment();
		$barebones['serviceDescription'] = $reflector->export($this, true);
		$barebones['serviceDescription'] = '<pre>['.var_export($reflector, true).']</pre>'; //var_export($reflector->export($this, true));
		#$serviceDescription = $reflector->export($this, true);
		#$barebones = Reflection::export($this);
		#$barebones .= Reflection::getDocComment();
		$this->serviceResponse = $barebones;
		#
		#echo __METHOD__.__LINE__.'$barebones<pre>['.var_export($barebones, true).']</pre>'.'<br>'; 
		#return $serviceDescription;
		return TRUE;
	}
	/**
	* encapsulates the handling of a DB error 
	* 
	 * @access public 
	 * @param array $result 
	 * @return array 
	 */
	public function wrapMySQLResultInJSON($result){
		if(
			isset($result[0]['EXCEPTION']['ID']) 
			&& 
			1062 == $result[0]['EXCEPTION']['ID']
		){
			$result['status'] = 'FALED';
			return $result;
		}

		if(1 <= count($this->changeList)){
			$info = implode(',',$this->changeList);
		}else{
			$info = 'update info before re-submitting updates (refresh the page)';
		}
		$response = array(
			'status' => 'OK',
			'info' => $info,
		);
		/*
		if(isset($args['callback'])){
			angular.callbacks._0
			$response[$args['callback']] = 'we did something else with your record';
		}
		*/
		return $response;
	}
	
}


?>