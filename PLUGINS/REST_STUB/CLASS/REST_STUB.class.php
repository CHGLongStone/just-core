<?
/**
 * REST_API
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	PLUGINS 
 */
/**
* require the interface before the class definition
*/

class REST_STUB extends REST_API{

	
	/***
	* DESCRIPTOR: 
	* @param param 
	* @return return  
	*/
	public function __construct(){
		#echo __METHOD__.__LINE__.'<br>';
	
		/***
		[hostname][API-dir]?[serviceObjectName]=[serviceObjectMessage]
		?REST_STUB={"serviceMethod":"doSomeStuff", "serviceArgs": [1,2,3]}
		*/
		parent::__construct();
		
		return;
	
	}
	
	/***
	* DESCRIPTOR: an example namespace call 
	* @param param 
	* @return return  
	*/
	public function doSomeStuff(){
		echo __METHOD__.__LINE__.'<br>';
	
	
	}
	/**
	* DESCRIPTOR: 
	* @param mixed raw_data 
	* @return return  
	* 
	*/
	public function parseRequest($raw_data){
		echo __METHOD__.__LINE__.'$raw_data<pre>['.var_export($raw_data, true).']</pre>'.'<br>'; 
	}
	/***
	* DESCRIPTOR: 
	* compile a response 
	* @param mixed dataSet 
	* @return return  
	*/
	public function compileResponse($dataSet){
	
	}
	
	/***
	* DESCRIPTOR: 
	* RETRIEVE 
	* @param param 
	* @return return  
	*/
	public function RETRIEVE($args){
	
	}
	/***
	* DESCRIPTOR: 
	* UPDATE 
	* @param param 
	* @return return  
	*/
	public function UPDATE($args){
	
	}
	/***
	* DESCRIPTOR: 
	* CREATE 
	* @param param 
	* @return return  
	*/
	public function CREATE($args){
	
	}
	/***
	* DESCRIPTOR: 
	* DELETE 
	* @param param 
	* @return return  
	*/
	public function DELETE($args){
	
	}
	
}




?>