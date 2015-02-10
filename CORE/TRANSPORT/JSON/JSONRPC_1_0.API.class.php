<?
/**
 * JSONRPC_1_0_API 
 *
$filePath = JCORE_BASE_DIR.'TRANSPORT/TRANSPORT_INTERFACE.interface.php';
require_once($filePath);
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT\JSON
 * @subpackage	JCORE\TRANSPORT\JSON
 */
namespace JCORE\TRANSPORT\JSON;
use JCORE\TRANSPORT\TRANSPORT_INTERFACE as TRANSPORT_INTERFACE;
use JCORE\TRANSPORT\SERIALIZATION_STATIC as SERIALIZATION;

/**
 * Class JSONRPC_1_0_API
 *
 * @package JCORE\TRANSPORT\JSON
*/
class JSONRPC_1_0_API implements TRANSPORT_INTERFACE{

	/**
	* 
	*/
	public $id = NULL;
	/**
	* 
	*/
	public $result = NULL;
	/**
	* 
	*/
	public $error  = NULL;
	
	/**
	* 
	*/
	#protected $requestMethod = 'exitNotice';
	public $responseData = array();
	/**
	* 
	*/
	protected $params = array();
	/**
	* 
	*/
	protected $raw_data = NULL;
	/**
	* 
	*/
	protected $parsedRequest = NULL;
	/**
	* 
	*/
	protected $serviceObject = NULL;
	/**
	* 
	*/
	protected $serviceResponse = NULL;
	/**
	* 
	*/
	protected $resultHandler = NULL;
	
	
	/**
	* DESCRIPTOR: 
	* enforce a method to parse the request in the sub class
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function __construct(){
		#echo __METHOD__.__LINE__.'<br>';
		$raw_data = urldecode($_SERVER["QUERY_STRING"]);
		if(substr($raw_data, 0, 1) == "&"){
			$raw_data = substr($raw_data, 1);
		}
		/**
		* first up we'll process the result
		*/
		$resultTest = $this->parseRequest($raw_data);
		#echo __METHOD__.__LINE__.'$resultTest<pre>['.var_export($resultTest, true).']</pre>'.'<br>'; 
		/**
		* if the message was just a notice we'll exit here
		* given notices don't have a repsponse we won't check for errors
		* errors should be logged where they are generated
		*/
		#echo __METHOD__.__LINE__.'$raw_data['.$raw_data.']<br>';
		if(!isset($this->parsedRequest["id"]) && (isset($raw_data) && $raw_data != '') ){
			$this->exitNotice(); //no repsonse for notifications
		}
		$this->responseData["id"] = $this->id;
		#echo __METHOD__.__LINE__.'<br>';
		/**
		* deal with errors next
		*/
		if(NULL !== $this->error || NULL !== $this->serviceObject->error){
			#echo __METHOD__.__LINE__.'<br>';
			$this->responseData["result"] = NULL;
			if(NULL !== $this->serviceObject->error){
				$this->responseData["error"] = $this->serviceObject->error;
			}else{
				$this->responseData["error"] = $this->error;
			}
		}else{
			#echo __METHOD__.__LINE__.'<br>';
			/**
			* deal with the result
			*/
			if(TRUE === $resultTest ){
				if(NULL !== $this->serviceObject && NULL !== $this->serviceObject->serviceResponse){
					$this->responseData["result"] = $this->serviceObject->serviceResponse;
					#echo __METHOD__.__LINE__.'$this->responseData["result"]<pre>['.var_export($this->responseData["result"], true).']</pre>'.'<br>'; 
					#echo __METHOD__.__LINE__.'$this->serviceObject->serviceResponse<pre>['.var_export($this->serviceObject->serviceResponse, true).']</pre>'.'<br>'; 
					if(NULL !== $this->resultHandler){
						$this->responseData["result"]["resultHandler"] = $this->resultHandler;
					}
					$this->responseData["error"] = NULL;
				}else{
					/*
					$args["code"] 		= "FAILED_CALL";
					$args["message"] 	='NO SEARCHED TERM DEFINED';
					$args["data"] 		= 'no service call made';
					#$this->error = new ERROR($args);
					$ERROR = new ERROR($args);
					$args["obj"]        = TRUE;
					$this->error = $ERROR->getError($args);
					return $ERROR;
					*/
					#echo __METHOD__.__LINE__.'$serviceData<pre>['.$serviceData.']</pre>'.'<br>'; 
					if(class_exists($serviceCall[0]) && method_exists($this->serviceObject, 'introspectService')){
						$serviceData = $this->serviceObject->introspectService();
						#echo __METHOD__.__LINE__.'$serviceData<pre>['.$serviceData.']</pre>'.'<br>'; 
						return $serviceData;
					}
				}
			}
			#echo __METHOD__.__LINE__.'$serviceData<pre>['.$serviceData.']</pre>'.'<br>'; 
			
		}
				
		$this->compileResponse($this->responseData);
		return;
	}
				
				
	/**
	* DESCRIPTOR: 
	* a method to exit the notification
	* @param mixed raw_data 
	* @return return NULL  
	*/
	protected function exitNotice(){
		exit();
	}
	/**
	* DESCRIPTOR: 
	* a method to parse the request
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function parseRequest($raw_data){
		#echo __METHOD__.__LINE__.' ['.$raw_data.']<br>';
		$args["assoc"] =  TRUE;
		$args["DATA"] = $raw_data;
		
		$parsedRequest = SERIALIZATION::unserializeJSON($args);
		if(NULL !== $parsedRequest || FALSE !== $parsedRequest){
			$this->raw_data  = $raw_data;
			$this->parsedRequest  = $parsedRequest;
		}
		$this->id = $this->parsedRequest["id"];
		
		return $this->callService($parsedRequest);
	}
	/**
	* DESCRIPTOR: 
	* enforce a method to compile the response in the sub class
	* @param mixed dataSet 
	* @return return NULL  
	*/
	public function compileResponse($dataSet){
		#echo __METHOD__.__LINE__.'<br>';
		#$args["assoc"] =  TRUE;
		$args["DATA"] = $dataSet;
		#echo __METHOD__.__LINE__.'$args["DATA"]<pre>['.var_export($args["DATA"], true).']</pre>'.'<br>'; 
		$preparedResponse = SERIALIZATION::serializeJSON($args);
		#echo __METHOD__.__LINE__.'$preparedResponse<pre>['.$preparedResponse.']</pre>'.'<br>';
		//Content-type: application/json
		header("Content-type: application/json");
		echo $preparedResponse;
		exit();
		#return $preparedResponse;
	}
		

	

	protected function callService($parsedRequest = null){
		#echo __METHOD__.__LINE__.'<br>';
		#echo __METHOD__.__LINE__.'$parsedRequest<pre>['.var_export($parsedRequest, true).']</pre>'.'<br>'; 
		if(NULL == $parsedRequest || !is_array($parsedRequest)){
			#echo __METHOD__.__LINE__.'<br>';
			if(NULL == $this->parsedRequest || !is_array($this->parsedRequest)){
				$parsedRequest = $this->parsedRequest;
				#echo __METHOD__.__LINE__.'<br>';
			}else{
				echo __METHOD__.__LINE__.'<br>';
				return false;
			}
		}
		$serviceCall = explode('.', $parsedRequest["method"]);
		#echo __METHOD__.__LINE__.'$serviceCall<pre>['.var_export($serviceCall, true).']</pre>'.'<br>'; 
		if(class_exists($serviceCall[0]) && method_exists($serviceCall[0], $serviceCall[1])){
			$this->serviceObject = new $serviceCall[0]();
			$serviceResponse = $this->serviceObject->$serviceCall[1]($parsedRequest["params"]);
			#echo __METHOD__.__LINE__.'$serviceResponse<pre>['.var_export($serviceResponse, true).']</pre>'.'<br>'; 
			
		}else{
			$this->error["errorType"] = "FAILED CALL";
			$this->error["errorContext"] = ' SERVICE '.$serviceCall.' NOT AVAILABLE';
			$this->error["errorDescription"] = ''.$serviceCall.' is not registered with this API';
			return false; //
		}
			
		if(isset($parsedRequest["params"]["resultHandler"])){
			$this->resultHandler = $parsedRequest["params"]["resultHandler"];
		}
		return $serviceResponse;
			
	} 
			

		
		
	
	
	
}

?>