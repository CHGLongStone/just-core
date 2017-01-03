<?php 
/**
 * REST_API
 *
require_once($filePath);
$filePath = JCORE_BASE_DIR.'TRANSPORT/TRANSPORT_INTERFACE.interface.php';
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	TRANSPORT 
 */

namespace JCORE\TRANSPORT\HTTP;

use JCORE\TRANSPORT\TRANSPORT_INTERFACE as TRANSPORT_INTERFACE;
/**
 * Class  HTTP_API
 *
 * @package JCORE\TRANSPORT\HTTP
*/
class HTTP_API implements TRANSPORT_INTERFACE{
	
	
	/**
	* [hostname][API-dir]?[serviceObjectName]=[serviceObjectMessage]
	*	the request headers 
	 * requestHeaders
	 * @access protected 
	 * @var mixed
	 */
	protected $requestHeaders = null;
	/**
	*	the request raw_data 
	 * @access protected 
	 * @var string
	 */
	protected $raw_data = null;
	

	/**
	*	the request parsedData 
	 * @access protected 
	 * @var string
	 */
	protected $parsedData = null;
	
	
	/**
	* DESCRIPTOR: 
	* will parse the $_SERVER["REQUEST_METHOD"] 
	* and set the raw data from the request
	* 
	* 
	* @access public 
	* @param param bool NULL
	* @return return bool NULL
	*/
	public function __construct(){
		#echo __METHOD__.__LINE__.'<br>';
		$raw_data = $this->request();
		#REST_API::parseRequest($raw_data);
		if(FALSE !== $raw_data){
			$this->raw_data = $raw_data;
		}
		#$this->compileResponse($dataSet)($raw_data);
		$this->parsedData = $this->parseRequest($raw_data);
		return $this->parsedData;
	}
	/**
	* DESCRIPTOR: 
	*	HTTP request types handled
	*	GET, POST, PUT, DELETE, 
	*	
	* 	HTTP request extended types
	*	HEAD  Asks for the response identical to the one that would correspond to a GET request, but without the response body
	* 	TRACE, Echoes back the received request,
	* 	OPTIONS, Returns the HTTP methods that the server supports for specified URL
	* 	PATCH Is used to apply partial modifications to a resource
	* 	CONNECT, 
	* 	get_headers()
	* 
	* @access public 
	* @param array args
	* @return mixed raw_data 
	*/
	public function request($args){
		#echo __METHOD__.__LINE__.'<br>';
		if(isset($args["headers"])){
			$this->requestHeaders = $args["headers"];
		}else{
			$this->requestHeaders = get_headers();	//http_parse_headers();
			//array get_headers ( string $url [, int $format = 0 ] )
			
		}
		if(isset($args["raw_data"])){
			if(is_array($args["raw_data"])){
				$this->parsedData = $args["raw_data"];
				$raw_data = rawurlencode ($this->parsedData);
				#$raw_data = urlencode($this->parsedData);
				//rawurldecode 
			}
		}else{
			$raw_data = $_SERVER["QUERY_STRING"];
		}
			
			
		return $raw_data;
	}
	
	/**
	* DESCRIPTOR: 
	* enforce a method to parse the request
	* 
	* @access protected 
	* @param mixed raw_data 
	* @return return NULL  
	*/
	protected function parseRequest($raw_data){
		$requestData  = urldecode ($raw_data);
		//parse_url();
		
		return $requestData;
	}
	/**
	* DESCRIPTOR: 
	* enforce a method to compile a response (in the transport format)
	*
	* @access protected 
	* @param mixed dataSet 
	* @return return NULL  
	*/
	protected function compileResponse($dataSet){
		$responseData  = urldencode ($dataSet);
		return $responseData;
	}
	
	
	
}

?>