<?php 
/***
* ERROR
 * basic error object move to jsonrpc compatible errors as default
 * 
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage EXCEPTION
 */

namespace JCORE\EXCEPTION;
/**
 * Class ERROR
 *
 * @package JCORE\EXCEPTION
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
	
	/*

     * @var object $Code

    private $Code = null;

     * @var object $Message

    private $Message = null;

     * @var object $Data

    private $Data = null;    
	

     * @var object $Data

    private $cfg = null;

	
 
    public function __construct($args = null)
    {
		$configpath = $_SERVER['DOCUMENT_ROOT'].'/../config/autoload/error.global.php';
		$this->cfg = (require($configpath));
		
		$this->setCode($args['Code']);
		$this->setMessage($args['Message']);
		$this->setData($args['Data']);


	}
	
    public function getCode(){ 
		return $this->Code;
	}

    public function setCode($Code = null){ 
		if(null !== $Code){			
			if(is_numeric($Code)){
				if(isset($this->cfg['ERROR'][$Code])){
					$this->Code = $this->cfg['ERROR'][$Code];
				}else{
					$this->Code = $Code;
				}
			}else{
				$this->Code = $this->cfg['ERROR'][0];
			}	
		}		
	}

    public function getMessage(){ 
		return $this->Message;
	}

    public function setMessage($Message = null){ 
		if(null !== $Message){
			$this->Message = $Message;
		}
	}
	


    public function getData(){ 
		return $this->Data;
	}

    public function setData($Data = null){ 
		
		$backtrace = debug_backtrace();
		#echo '<pre>'.var_export($backtrace, true).'</pre>';
		$this->Data = $backtrace[1]['class'].'->'.$backtrace[1]['function'];
		if(null !== $Data){
			$this->Data = $this->Data.PHPEOL.var_dump($Data);
		}
	}

    public function getError($AsJSON = false){ 
		
		$error =  array();
		$error['Code'] = $this->getCode();
		$error['Message'] = $this->getMessage();
		$error['Data'] = $this->getData();
		
		if(true == $AsJSON){
			$error = json_encode($error);
		}

		return $error;
	}  	
	*/
}

?>