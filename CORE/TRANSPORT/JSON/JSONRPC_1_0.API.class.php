<?php
/**
 * JSONRPC_1_0_API 
 *
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT\JSON
 * 
 */
namespace JCORE\TRANSPORT\JSON;
use JCORE\TRANSPORT\TRANSPORT_INTERFACE as TRANSPORT_INTERFACE;
use JCORE\TRANSPORT\SERIALIZATION_STATIC as SERIALIZATION; #deprecated?
use JCORE\TRANSPORT\JSON\JSON as JSON;

USE JCORE\EXCEPTION\ERROR as ERROR;

use JCORE\TRANSPORT\SOA\SERVICE_VALIDATOR as SERVICE_VALIDATOR;

/**
 * Class JSONRPC_1_0_API
 *
 * @package JCORE\TRANSPORT\JSON
*/
class JSONRPC_1_0_API implements TRANSPORT_INTERFACE {

	/**
	* id
	* 
	* @access public 
	* @var string
	*/
	public $id = NULL;
	/**
	* result
	* 
	* @access public 
	* @var array
	*/
	public $result = NULL;
	/**
	* error
	* 
	* @access public 
	* @var mixed
	*/
	public $error  = NULL;
	
	/**
	* responseData
	* 
	* @access public 
	* @var array
	*/
	public $responseData = array();
	#protected $requestMethod = 'exitNotice';
	/**
	* params
	* 
	* @access protected 
	* @var array
	*/
	protected $params = array();
	/**
	* raw_data
	* 
	* @access protected 
	* @var mixed
	*/
	protected $raw_data = NULL;
	/**
	* parsedRequest
	* 
	* @access protected 
	* @var string
	*/
	protected $parsedRequest = NULL;
	/**
	* serviceObject
	* 
	* @access protected 
	* @var mixed
	*/
	protected $serviceObject = NULL;
	/**
	* serviceResponse
	* 
	* @access protected 
	* @var mixed
	*/
	protected $serviceResponse = NULL;
	/**
	* resultHandler
	* 
	* @access protected 
	* @var mixed
	*/
	protected $resultHandler = NULL;
	
	
	/**
	* DESCRIPTOR: 
	* __construct
	* 
	* @access public
	* @param null
	* @return null
	*/
	public function __construct(){
		/*
		echo __METHOD__.'@'.__LINE__.PHP_EOL;
		echo __METHOD__.'@'.__LINE__.'$_SERVER<pre>['.var_export($_SERVER,true).']</pre>'.PHP_EOL;
		echo __METHOD__.'@'.__LINE__.'$_POST<pre>['.var_export($_POST,true).']</pre>'.PHP_EOL;
		echo __METHOD__.'@'.__LINE__.'$_GET<pre>['.var_export($_GET,true).']</pre>'.PHP_EOL;
		$raw_data = file_get_contents('php://input');
		echo __METHOD__.'@'.__LINE__.'$raw_data<pre>['.var_export($raw_data,true).']</pre>'.PHP_EOL;
		*/
		$rqType = '';
		if('POST' == $_SERVER["REQUEST_METHOD"]){
			$raw_data = $_POST;
			if(0 == count($raw_data)){
				#echo __METHOD__.'@'.__LINE__.'file_get_contents '.PHP_EOL;
				$raw_data = file_get_contents('php://input');
				#echo __METHOD__.'@'.__LINE__.'$raw_data<pre>['.var_export($raw_data,true).']</pre>'.PHP_EOL;
				$raw_data = JSON::json_decode($raw_data);
				$rqType = '_POST RAW';
			}elseif(1 >= count($raw_data)){
				#echo __METHOD__.'@'.__LINE__.'parse _POST '.PHP_EOL;
				#$raw_data = JSON::json_decode($raw_data);
				#$rqType = '_POST Parameter';
				$raw_data = file_get_contents('php://input');
				#echo __METHOD__.'@'.__LINE__.'$raw_data<pre>['.var_export($raw_data,true).']</pre>'.PHP_EOL;
				$raw_data = JSON::json_decode($raw_data);
				$rqType = '_POST RAW';
			}else{
				$rqType = '_POST OTHER';
				#echo __METHOD__.'@'.__LINE__.' OTHER '.PHP_EOL;
				
			}
			if(!is_array($raw_data)){
				#echo __METHOD__.'@'.__LINE__.'count($raw_data)['.count($raw_data).']$raw_data<pre>['.var_export($raw_data,true).']</pre>'.PHP_EOL;
				if($raw_data == ' - Syntax error, malformed JSON'){
					$raw_data = 'Ensure Service Methods, URLs  and similar parameters are URL escaped before JSON encoding ';
				}
				exit('{"result": null, "error": {"code": -300, "message": " Request '.$rqType.' NOT Parsed: '.$raw_data.'"}, "id": null}');
			}
		
		}elseif('GET' == $_SERVER["REQUEST_METHOD"]){
			$raw_data = urldecode($_SERVER["QUERY_STRING"]);
			$rqType = '_GET Parameter';
		}else{
			#
			exit('{"result": null, "error": {"code": -300, "message": "failed to get input '.$rqType.' NOT Parsed: '.$raw_data.'"}, "id": null}');
		}
		/**/
		if(!isset($raw_data['id']) || '' == $raw_data['id']){
			$raw_data['id'] = microtime();
		}
		if(isset($raw_data["params"]) && !is_array($raw_data["params"])){
			#echo __METHOD__.'@'.__LINE__.'$raw_data<pre>['.var_export($raw_data,true).']</pre>'.PHP_EOL;
			$raw_data["params"] = JSON::json_decode($raw_data["params"]);
		}
		/*
		echo __FILE__.__LINE__.'$raw_data['.var_export($raw_data,true).']'.PHP_EOL;
		*/
		
		#exit;
		#if(substr($raw_data, 0, 1) == "&"){
		#	$raw_data = substr($raw_data, 1);
		#}
		/**
		* first up we'll process the result
		echo __METHOD__.'@'.__LINE__.''.PHP_EOL; 
		*/
		$resultTest = $this->parseRequest($raw_data);
		/**
		echo __METHOD__.'@'.__LINE__.'$raw_data<pre>['.var_export($raw_data, true).']</pre>'.PHP_EOL; 
		echo __METHOD__.'@'.__LINE__.'$resultTest<pre>['.var_export($resultTest, true).']</pre>'.PHP_EOL; 
		* if the message was just a notice we'll exit here
		* given notices don't have a response we won't check for errors
		* errors should be logged where they are generated
		echo __METHOD__.'@'.__LINE__.'$raw_data['.$raw_data.']<br>';
		*/
		if(!isset($this->parsedRequest["id"]) && (isset($raw_data) && $raw_data != '') ){
			$this->exitNotice(); //no repsonse for notifications
		}
		$this->responseData["id"] = $this->id;
		#echo __METHOD__.'@'.__LINE__.PHP_EOL;
		/**
		* deal with errors next
		*/
		if(NULL !== $this->error || NULL !== $this->serviceObject->error){
			#echo __METHOD__.'@'.__LINE__.PHP_EOL;
			$this->responseData["result"] = NULL;
			if(isset($this->serviceObject->error) && NULL !== $this->serviceObject->error){
				$this->responseData["error"] = $this->serviceObject->error;
			}else{
				$this->responseData["error"] = $this->error;
			}
		}else{
			/**
			echo __METHOD__.'@'.__LINE__.PHP_EOL;
			* deal with the result
			*/
			if(TRUE == $resultTest ){
				#echo __METHOD__.'@'.__LINE__.PHP_EOL;
				if(NULL !== $this->serviceObject && NULL !== $this->serviceObject->serviceResponse){
					#$this->responseData["result"] = $this->serviceObject->serviceResponse;
					$this->responseData = $this->serviceObject->serviceResponse;
					/*
					echo __METHOD__.'@'.__LINE__.PHP_EOL;
					echo __METHOD__.'@'.__LINE__.'$this->responseData<pre>['.var_export($this->responseData, true).']</pre>'.PHP_EOL; 
					echo __METHOD__.'@'.__LINE__.'$this->serviceObject->serviceResponse<pre>['.var_export($this->serviceObject->serviceResponse, true).']</pre>'.PHP_EOL; 
					*/
					if(NULL !== $this->resultHandler){
						$this->responseData["result"]["resultHandler"] = $this->resultHandler;
					}
					#$this->responseData["error"] = NULL;
				}else{
					/*
					echo __METHOD__.'@'.__LINE__.PHP_EOL;
					$args["code"] 		= "FAILED_CALL";
					$args["message"] 	='NO SEARCHED TERM DEFINED';
					$args["data"] 		= 'no service call made';
					#$this->error = new ERROR($args);
					$ERROR = new ERROR($args);
					$args["obj"]        = TRUE;
					$this->error = $ERROR->getError($args);
					return $ERROR;
					echo __METHOD__.'@'.__LINE__.'$serviceData<pre>['.$serviceData.']</pre>'.PHP_EOL; 
					*/
					if(method_exists($this->serviceObject, 'introspectService')){ //class_exists() && 
						$serviceData = $this->serviceObject->introspectService();
						#echo __METHOD__.'@'.__LINE__.'$serviceData<pre>['.var_export($serviceData,true).']</pre>'.PHP_EOL; 
						return $serviceData;
					}
				}
			}
			
		}
				
		#echo __METHOD__.'@'.__LINE__.'$this->responseData['.var_export($this->responseData,true).']</pre>'.PHP_EOL; 
		$this->compileResponse($this->responseData);
		return;
	}
				
				
	/**
	* DESCRIPTOR: 
	* exitNotice
	* 
	* @access public
	* @param NULL 
	* @return NULL  
	*/
	protected function exitNotice(){
		exit();
	}
	
	/**
	* DESCRIPTOR: 
	* a method to get the called service name
	* 
	* @access public
	* @param null 
	* @return string
	*/
	public function getServiceName(){
		return $this->parsedRequest["method"];
	}
	
	/**
	* DESCRIPTOR: 
	* a method to parse the request
	* 
	* @access public
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function parseRequest($raw_data){
		#echo __METHOD__.'@'.__LINE__.' ['.$raw_data.']<br>';
		#echo __METHOD__.'@'.__LINE__.'$raw_data['.var_export($raw_data,true).']'.PHP_EOL;
		/*
		$args["assoc"] =  TRUE;
		$args["DATA"] = $raw_data;
		
		$parsedRequest = SERIALIZATION::unserializeJSON($args);
		echo __METHOD__.'@'.__LINE__.'$raw_data["id"]['.var_export($raw_data['params']["id"],true).']'.PHP_EOL;
		*/
		$parsedRequest = $raw_data;  //params
		
		if(NULL !== $parsedRequest || FALSE !== $parsedRequest){
			#echo __METHOD__.'@'.__LINE__.'$parsedRequest['.var_export($parsedRequest,true).']'.PHP_EOL;
			#$this->raw_data  = $raw_data;
			if(true === is_string($parsedRequest)){
				echo __METHOD__.'@'.__LINE__.'$parsedRequest['.var_export($parsedRequest,true).']'.PHP_EOL;
				$test = JSON::json_decode($parsedRequest);
				echo __METHOD__.'@'.__LINE__.'$test['.var_export($test,true).']'.PHP_EOL;
				
			}
			$this->parsedRequest  = $parsedRequest;
		}
		
		$this->id = $this->parsedRequest["id"];
		
		return $this->callService($parsedRequest);
	}
	/**
	* DESCRIPTOR: 
	* enforce a method to compile the response in the sub class
	* 
	* @access public
	* @param mixed dataSet 
	* @return return NULL  
	*/
	public function compileResponse($dataSet){
		//{"result": 1, "error": null, "id": 101}
		#echo __METHOD__.'@'.__LINE__.PHP_EOL;
		#echo __METHOD__.'@'.__LINE__.'$args["DATA"]['.var_export($dataSet, true).']'.PHP_EOL; 
		#$args["assoc"] =  TRUE;
		$args["result"] = $dataSet;
		$args['error'] = NULL;
		if(isset($dataSet["error"])){
			$args['error'] = $dataSet["error"];
			$args["result"] = NULL;
		}else{
			$args["result"] = $dataSet;
			$args['error'] = NULL;			
		}
		if('' == $this->id ){

			$args['id'] = microtime();
		}else{
			$args['id'] = $this->id;
		}
		#echo __METHOD__.'@'.__LINE__.'$args["DATA"]<pre>['.var_export($args["DATA"], true).']</pre>'.PHP_EOL; 
		#$preparedResponse = SERIALIZATION::serializeJSON($args);
		$preparedResponse = JSON::json_encode($args);
		//JSON::json_decode
		#echo __METHOD__.'@'.__LINE__.'$preparedResponse<pre>['.$preparedResponse.']</pre>'.PHP_EOL;
		//Content-type: application/json
		header("Content-type: application/json");
		echo $preparedResponse;
		#exit();
		#return $preparedResponse;
	}
		

	
	/**
	* callService
	* 
	* @access public 
	* @param array parsedRequest 
	* @return bool  
	*/
	protected function callService($parsedRequest = null){
		#echo __METHOD__.'@'.__LINE__.PHP_EOL;
		#echo __METHOD__.'@'.__LINE__.'$parsedRequest<pre>['.var_export($parsedRequest, true).']</pre>'.PHP_EOL; 
		if(NULL == $parsedRequest || !is_array($parsedRequest)){
			#echo __METHOD__.'@'.__LINE__.PHP_EOL;
			if(NULL == $this->parsedRequest || !is_array($this->parsedRequest)){
				$parsedRequest = $this->parsedRequest;
				#echo __METHOD__.'@'.__LINE__.PHP_EOL;
			}else{
				#echo __METHOD__.'@'.__LINE__.PHP_EOL;
				return false;
			}
		}
		
		/**
		* basic validation of the service 
		*/
		$serviceTest = SERVICE_VALIDATOR::validateService($parsedRequest["method"]);
		if($serviceTest instanceof ERROR ){
			$this->error["errorType"] = $serviceTest->getCode(); // "FAILED CALL";
			$this->error["errorContext"] = $serviceTest->getData(); //' SERVICE '.$parsedRequest["method"].' NOT AVAILABLE';
			$this->error["errorDescription"] = $serviceTest->getMessage(); //''.$parsedRequest["method"].' is not registered with this API';
			return false; //
		}
		/**
		* validation of the service call 
		*/
		$serviceTest = SERVICE_VALIDATOR::validateServiceCall($parsedRequest["method"]);
		if($serviceTest instanceof ERROR ){
			$this->error["errorType"] = $serviceTest->getCode(); // "FAILED CALL";
			$this->error["errorContext"] = $serviceTest->getData(); //' SERVICE '.$parsedRequest["method"].' NOT AVAILABLE';
			$this->error["errorDescription"] = $serviceTest->getMessage(); //''.$parsedRequest["method"].' is not registered with this API';
			return false; //
		}
		$this->serviceObject = new $serviceTest['object']();
		$serviceResponse = $this->serviceObject->$serviceTest['method']($parsedRequest["params"]);
		
		/*
		$serviceCall = explode('.', $parsedRequest["method"]);
		echo __METHOD__.'@'.__LINE__.'$serviceCall['.var_export($serviceCall, true).']'.PHP_EOL; 
		echo __METHOD__.'@'.__LINE__.'class_exists('.$serviceCall[0].')['.var_export(class_exists($serviceCall[0]), true).']'.PHP_EOL; 
		echo __METHOD__.'@'.__LINE__.' method_exists('.$serviceCall[0].', '.$serviceCall[1].')['.var_export( method_exists($serviceCall[0], $serviceCall[1]), true).']'.PHP_EOL; 
		
		if(class_exists($serviceCall[0]) && method_exists($serviceCall[0], $serviceCall[1])){
			$this->serviceObject = new $serviceCall[0]();
			$serviceResponse = $this->serviceObject->$serviceCall[1]($parsedRequest["params"]);
			#echo __METHOD__.'@'.__LINE__.'$serviceResponse<pre>['.var_export($serviceResponse, true).']</pre>'.PHP_EOL; 
			
		}else{
			#echo __METHOD__.'@'.__LINE__.'$serviceCall['.var_dump($serviceCall, true).']'.PHP_EOL; 
			$this->error["errorType"] = "FAILED CALL";
			$this->error["errorContext"] = ' SERVICE '.$parsedRequest["method"].' NOT AVAILABLE';
			$this->error["errorDescription"] = ''.$parsedRequest["method"].' is not registered with this API';
			return false; //
		}
		*/
			
		if(isset($parsedRequest["params"]["resultHandler"])){
			$this->resultHandler = $parsedRequest["params"]["resultHandler"];
		}
		return $serviceResponse;
			
	} 
			

		
		
	
	
	
}

?>