<?php 
/***
* ERROR
 * basic error object move to jsonrpc 2.0  compatible errors as default
 * 
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage EXCEPTION
 */

namespace JCORE\EXCEPTION;
use JCORE\LOAD\CONFIG_MANAGER;
use JCORE\TRANSPORT\JSON as JSON;
/**
 * Class ERROR
 *
 * @package JCORE\EXCEPTION
*/
class ERROR {

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
	*
     * @var object $Code
	*/
    private $Code = null;

	/**
     * @var object $Message
	*/
    private $Message = null;

	/**
     * @var object $Data
	*/
    private $Data = null;    
	
    public function __construct($args = null)
    {
		#$this->cfg = $GLOBALS["CONFIG_MANAGER"];//->getSetting("ERROR")
		#echo __METHOD__.'@'.__LINE__.'$GLOBALS["CONFIG_MANAGER"]->getSetting("ERROR",32602 )<pre>'.var_export($GLOBALS["CONFIG_MANAGER"]->getSetting("ERROR",32602 ), true).'</pre>';
		#$configpath = $_SERVER['DOCUMENT_ROOT'].'/../config/autoload/error.global.php';
		#$this->cfg = (require($configpath));
		if(isset($args['Code'])){
			$this->setCode($args['Code']);
		}

		if(isset($args['Message'])){
			$this->setMessage($args['Message']);
		}
		/*
		if(isset($args['Data'])){
			echo __METHOD__.'@'.__LINE__.'$args["Data"]<pre>'.var_export($args['Data'], true).'</pre>';
			echo __METHOD__.'@'.__LINE__.'this->getData()<pre>'.var_export($this->getData(), true).'</pre>';
		}
		*/
		$this->setData($args['Data']);

	}
	
    public function getCode(){ 
		return $this->Code;
	}

    public function setCode($Code = null){ 
		if(null !== $Code){
			$this->Code = $Code;
		}
	}

    public function getMessage(){ 

		return $this->Message;
	}    

	public function getConfigCode($LOAD_ID="ERROR",$SECTION_NAME=null,$SETTING_NAME=null){ 
		return $GLOBALS["CONFIG_MANAGER"]->getSetting("ERROR",$this->Code);
		#$this->Message;
	}

    public function setMessage($Message = null){ 
		if(null !== $Message){
			$this->Message = $Message;
		}
		$msg = "\r\n";
		if(null !== $this->Code){			
			if(is_numeric($this->Code)){
				if(
					false != $this->getConfigCode("ERROR",$this->Code)
				){
					$msg .= $this->getConfigCode("ERROR",$this->Code);
				}else{
					$msg .= $this->Code;
				}
			}
		}else{
			$msg .= $this->getConfigCode("ERROR",0);
		}	
		$this->Message .= $msg;
	}
	


    public function getData(){ 
		return $this->Data;
	}

    public function setData($Data = null){ 
		echo __METHOD__.'@'.__LINE__.'Data<pre>'.var_export($Data, true).'</pre>';
		
		if(null == $Data){
			$backtrace = debug_backtrace();
			#echo __METHOD__.'@'.__LINE__.'backtrace<pre>'.var_export($backtrace, true).'</pre>';
			$this->Data = $backtrace[1]['file'].'@'.$backtrace[1]['line'].':call:'.$backtrace[1]['class'].'->'.$backtrace[1]['function'];
			
			$this->Data = $this->Data.PHP_EOL.var_export($Data, true);
		}else{
			$this->Data = var_export($Data, true);
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

		#return $this;
		return $error;
	}  	
	public function __toString(){
		#$error =  array();
		echo __METHOD__.'@'.__LINE__.'<pre>'.var_export($this->getMessage(), true).'</pre>';
		echo '**************';
		$error['Message'] = '**************';
		$error['Code'] = $this->getCode();
		unset($error['cfg']);
		#$error['Message'] = $this->getMessage();
		#$error['Data'] = $this->getData();
		#$ERROR = new ERROR($error);
		return $error;
		
	}
	public function __sleep(){
		echo __METHOD__.'@'.__LINE__.'$error<pre>'.var_export($error, true).'</pre>';
	}
	
	public static function __set_state($error = array( 'gdmf'=>''))
    {
		unset($error['cfg']);
		#$error = array( 'cfg'=>'');
		echo __METHOD__.'@'.__LINE__.'$error<pre>'.var_export($error, true).'</pre>';
		#$error =  array();
		echo '@@@@@@@@@@@@';
		$error['Message'] = '@@@@@@@@@@@@';
		$error['Code'] = $this->getCode();
		#$error['Message'] = $this->getMessage();
		#$error['Data'] = $this->getData();
		#$ERROR = new ERROR($error);
		return $error;
    }
}

?>