<?php 
/**
* REST_API
* 
* 
* 
* @author	Jason Medland<jason.medland@gmail.com>
* @package	JCORE\TRANSPORT\REST
* 
*/

namespace JCORE\TRANSPORT\REST;
use JCORE\TRANSPORT\TRANSPORT_INTERFACE as TRANSPORT_INTERFACE;
use JCORE\TRANSPORT\HTTP\HTTP_API as HTTP_API;
/**
 * Class REST_API
 *
 * @package JCORE\TRANSPORT\REST
*/
abstract class REST_API implements TRANSPORT_INTERFACE{
	
	
	/**
	* [hostname][API-dir]?[serviceObjectName]=[serviceObjectMessage]
	* 
	* 
	*/
	
	/**
	*	the request headers 
	*/
	protected $requestHeaders = null;
	/**
	*	the request raw_data 
	*/
	protected $raw_data = null;
	/**
	*	the request raw_data 
	*/
	protected $crudType = 'GET';
	/**
	*	the request raw_data 
	*/
	protected $msgObj = 'HTTP_API';
	
	/**
	* DESCRIPTOR: 
	* will parse the $_SERVER["REQUEST_METHOD"] 
	* and set the raw data from the request
	* 
	* 
	* 
	* @param param bool NULL
	* @return return bool NULL
	*/
	public function __construct(){
		echo __METHOD__.__LINE__.'<br>';
		$msgObj = new  $$this->msgObj();
		'HTTP_API';
		
		$raw_data = $this->request();
		#REST_API::parseRequest($raw_data);
		if(FALSE !== $raw_data){
			$this->raw_data = $raw_data;
		}
		return;
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
	* @param param bool NULL
	* @return return mixed $raw_data 
	*/
	public function request(){
		echo __METHOD__.__LINE__.'<br>';
		$this->requestHeaders = get_headers();
		#echo __METHOD__.__LINE__.'$_SERVER<pre>['.var_export($_SERVER, true).']</pre>'.'<br>'; 
		//$queryString = $_SERVER["QUERY_STRING"];
		#echo __METHOD__.__LINE__.'$raw_data<pre>['.var_export($raw_data, true).']</pre>'.'<br>'; 
		switch($_SERVER["REQUEST_METHOD"]){
			case"GET":  //retrieve Requests a representation of the specified resource.
				$this->crudType = 'RETRIEVE';
				#action
				break;
			case"POST": //update Submits data to be processed
				$this->crudType = 'UPDATE';
				#action
				break;
			case"PUT": //create Uploads a representation of the specified resource.
				$this->crudType = 'CREATE';
				#action
				break;
			case"DELETE": //* Deletes the specified resource.
				#action
				$this->crudType = 'DELETE';
				break;
			default:	///TRACE, OPTIONS, CONNECT, PATCH
				break;
			
		}
		
		$raw_data = file_get_contents('php://input');
		return $raw_data;
	}
	
	/**
	* DESCRIPTOR: 
	* enforce a method to parse the request in the child
	* @param mixed raw_data 
	* @return return NULL  
	*/
	abstract public function parseRequest($raw_data);
	/**
	* DESCRIPTOR: 
	* enforce a method to compile a response (in the transport format)
	* in the child
	* @param mixed dataSet 
	* @return return NULL  
	*/
	abstract public function compileResponse($dataSet);
	
	/**
	* DESCRIPTOR: 
	* map HTTP GET to CRUD operation RETRIEVE 
	* enforce a method in the child to handle it
	*  
	* @param mixed args 
	* @return return NULL  
	*/
	abstract public function RETRIEVE($args);
	
	
	/**
	* DESCRIPTOR: 
	* UPDATE a resource
	* map HTTP POST to CRUD operation UPDATE 
	* enforce a method in the child to handle it
	* 
	* @param mixed args 
	* @return return NULL
	*/
	abstract public function UPDATE($args);
	/**
	* DESCRIPTOR: 
	* map HTTP PUT to CRUD operation CREATE 
	* enforce a method in the child to handle it
	* 
	* @param args 
	* @return return  
	*/
	abstract public function CREATE($args);
	/**
	* DESCRIPTOR: 
	* map HTTP DELETE to CRUD operation DELETE 
	* enforce a method in the child to handle it
	* 
	* DELETE 
	* @param args 
	* @return return  
	*/
	abstract public function DELETE($args);
	
	
}

?>