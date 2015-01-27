<?php 
/***
* DATA_Exception
 * Instances can be created for any DB supported by PHP inc. NoSQL
 * 
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage EXCEPTION
 */
/***
* DATA_Exception
 * Instances can be created for any DB supported by PHP inc. NoSQL
 * this is a stub class just extended for the name space,
 * monitoring/logging could be added
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage EXCEPTION
 */
class ERROR {
	/***
	* 
	*/
	public $code = NULL;
	/***
	* 
	*/
	public $message = NULL;
	/***
	* 
	*/
	public $data  = NULL;
	
	/***
	* DESCRIPTOR: 
	* enforce a method to parse the request in the sub class
	* @param mixed args 
	* @return return NULL  
	*/
	public function __construct($args){
		#echo __METHOD__.__LINE__.'		<pre>['.var_export($args, true).']</pre>'.'<br>		';
		if(isset($args["code"])){
			$this->code = $args["code"];
		}
		if(isset($args["message"])){
			$this->message = $args["message"];
		}
		if(isset($args["data"])){
			$this->data = $args["data"];
		}
		#echo __METHOD__.__LINE__.'this<pre>['.var_export($this, true).']</pre>'.'<br>';
		return;
	}
	/*
		code
		A Number that indicates the error type that occurred.
		This MUST be an integer.
		message
		A String providing a short description of the error.
		The message SHOULD be limited to a concise single sentence.
		data
		A Primitive or Structured value that contains additional information about the error.
		This may be omitted.
		The value of this member is defined by the Server (e.g. detailed error information, nested errors etc.).	
		
		code message data
		get_object_vars();
	*/

	/***
	* DESCRIPTOR: 
	* if $args["obj"] is set to TRUE (bool) the error will be returned 
	* as a object other wise it will be returned as an array
	* @param mixed args 
	* @return return NULL  
	*/
	public function getError($args = null ){
		if(true === $args["obj"]){
			$tempObj = (object) get_object_vars($this);
			return $tempObj; #var_export($this);
		}else{
			return get_object_vars($this);
		}
	}
	public function __get($name)
    {
		if (array_key_exists($name, get_object_vars($this))) {
			return $this->$$name;
		}
		return null;
    }
}

?>