<?php
/**
 * LOGGER (JCORE) CLASS
 * 
 * LOGGER CLASS
 * this can be instantiated @ the global level or applied as a class property (unless caching the parent object)
 * The constructor argument $settings is an array, this is assumed to be returned from 
 * $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID="JCORE", $SECTION_NAME="FOUNDATION", $SETTING_NAME = "SETTING_1");
 * 
 * if no settings are supplied the logger will default to FILE logging in the ("[logName]-[date].log")
 * 
 *  	- all the member of the log are private
 * 
 * trace() is for application trace/log (FILE/SYSLOG)
 * log() is for BI logging (UDP/DATABASE) 
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	LOG
 */
/**
 * @package	JCORE
 * @subpackage	LOG
*/
namespace JCORE\LOG;

/**
 * Class CACHE_API2
 *
 * @package JCORE\CACHE
*/
class LOGGER{
	/**
	 * @access public 
	 * @var string
	 */
	protected $errors = array();
	protected $traceString = '';
	
	private $settings = '';
	
	private $logFacility; //serviceType
	#public $fileLoggingMode;
	
	/**
	 * Constructor
	 * make this as dynamic as possible
	 * @param array $settings
	 */
	function __construct($settings=NULL){
		#action
		if(NULL!==$settings){
			$this->settings = $settings;
		}else{
			$this->settings = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG', $SECTION_NAME = 'JCORE');
		}
		
			
		
		if($settings["logFacility"]){
			$this->logFacility = $settings["logFacility"];
			#echo 'use logging service['.$logFacility.']'.var_export($settings,false)."\n";
			switch($this->logFacility){ // THIS MAPS TO THE SETTINGS IN appliactionLogServices.php
				case "UDP":
					$this->initUDP();
					break;
				case"SYSLOG":
					/**
					* send it to syslog and forget about it
					* http://us.php.net/manual/en/function.syslog.php
					*/
					break;
				case"FILE":
					$this->initFILE();					
					break;
				
				default;
					break;
			}
		}else{
			/*
			$this->logFacility = 'FILE';
			$defaultSettings = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'JCORE_LOG', $SECTION_NAME = 'JCORE');
			echo __METHOD__.'@'.__LINE__.'$defaultSettings<pre>'.print_r($defaultSettings, true).'</pre>';
			echo __METHOD__.'@'.__LINE__.'$this->settings['.gettype($this->settings).']<pre>'.print_r($this->settings, true).'</pre>';
			
			foreach($defaultSettings AS $key => $value){
				echo __METHOD__.'@'.__LINE__.'$key['.$key.']['.gettype($key).']$value['.gettype($value).']<pre>'.print_r($value, true).'</pre>';
				if(!in_array($key, $this->settings) || ($this->settings[$key] != $value)){
					$this->settings[$key] =  $value;
				}
			}
			*/
				#$this->"init".$this->logFacility();			
		}
		return;
	}
	/**
	* DESCRIPTOR: sets up the class for file output
	* @param ignored $paramie 
	* @return outputErrors 
	*/
	private function initFILE(){
		/**
		echo( __METHOD__.'@'.__LINE__.'$this->settings<pre>['.var_export($this->settings, true).']</pre>').'<br>'; 
		echo( __METHOD__.'@'.__LINE__.'stream_get_wrappers<pre>['.var_export(stream_get_wrappers(), true).']</pre>').'<br>'; 
		$this->settings;
		echo( __METHOD__.'@'.__LINE__.'$this->fullWritePath<pre>['.var_export($this->fullWritePath, true).']</pre>').'<br>'; 
		*/
		
		$this->fullWritePath = 'file://'.$this->settings['writePath'].$this->settings['logName'].date($this->settings['dateFormatFile']).'.'.$this->settings['logSuffix'];
		//stream_set_write_buffer ( resource $stream , int $buffer )
		$opts = array('file' =>array('encoding' =>'utf-8','mode' =>'a') );
		$this->writeStreamContext = stream_context_create($opts);
		#$this->writeStreamContext = stream_context_create();
		/*
		echo( __METHOD__.'@'.__LINE__.'$this->writeStreamContext['.$this->writeStreamContext.']<pre>['.var_export($this->writeStreamContext, true).']</pre>').'<br>'; 
		echo( __METHOD__.'@'.__LINE__.'stream_context_get_default<pre>['.var_export(stream_context_get_default($this->writeStreamContext), true).']</pre>').'<br>'; 
		echo( __METHOD__.'@'.__LINE__.'stream_context_get_options<pre>['.var_export(stream_context_get_options($this->writeStreamContext), true).']</pre>').'<br>'; 
		echo( __METHOD__.'@'.__LINE__.'stream_is_local<pre>['.var_export(stream_is_local($this->writeStreamContext), true).']</pre>').'<br>'; 
		#echo( __METHOD__.'@'.__LINE__.'stream_context_get_params<pre>['.var_export(stream_context_get_params($this->writeStreamContext), true).']</pre>').'<br>'; 
		array("file", "/tmp/ens/a.html","w")
		$opts = array('http' => array('proxy' => 'tcp://127.0.0.1:8080', 'request_fulluri' => true));
		file_put_contents ( $this->fullWritePath , $data, FILE_APPEND , $this->writeStreamContext );
		*/
		return;
	}	
	/**
	* DESCRIPTOR: sets up the class for file output
	* @param ignored $paramie 
	* @return outputErrors 
	*/
	private function initUDP(){
		$this->serverhost 	= $this->settings["serverhost"];
		$this->serverport 	= $this->settings["serverport"];
		if($settings["persist"] == 1){
			$this->persist 		= TRUE;
		}else{
			$this->persist 		= FALSE;
		}
	
	}
	/**
	* DESCRIPTOR: IE: This always returns a myclass
	* @param ignored $paramie 
	* @return outputErrors 
	*/
	function writeToFile($args){
		func_get_args();
		/*
			logFacility="FILE" 
			writePath="/var/log/"
			logName="JCORE_"
			dateFormat="Y-m-d H:i:s"
			logSuffix="log"
			stripWhitespace=TRUE
			bufferWrite=FALSE
			blockSize=[4096]
		*/
		$linePrepend = '';
		$Error = DATA_UTIL_API::scrubWhitespace($args["Error"]);
		$Desc = DATA_UTIL_API::scrubWhitespace($args["Desc"]);
		$usec = DATA_UTIL_API::cleanMicrotime();
		if(isset($args["debugLevel"])){
			$debugLevel = $args["debugLevel"];
		}else{
			$debugLevel = E_WARNING;
		}
		
		
		//dateFormatFile  timeStampFormat
		$this->traceString .= $linePrepend.' '.date($this->settings["dateFormatFile"]).'.'.$usec.' ['.$Error.']['.$debugLevel.']::'.$Desc."\n";
		if($this->settings["bufferWrite"] === true){ //(strlen($this->traceString)+1) >= FILE_LOG_PACKET_SIZE)
			echo 'TRUE write it to internal buffer<br>';
		}else{
			#echo 'FALSE bufferWrite['.$this->settings["bufferWrite"].'] Do it now!<br>';
			/**
			*/
				#$logDate = '.'.date('Y-m-d').'.log';
					if(isset($this->settings["fileLoggingMode"])){
						#file_put_contents($this->settings["writePath"].$logDate, $this->traceString, FILE_APPEND | LOCK_EX);
						file_put_contents ( $this->fullWritePath , $this->traceString, $this->settings["fileLoggingMode"] , $this->writeStreamContext ) or die ("Can't open segment.[".$this->fullWritePath."]");
						#file_put_contents ( $this->fullWritePath , $this->traceString, $this->settings["fileLoggingMode"] ) or die("Can't open segment.");
						#echo __METHOD__.'@'.__LINE__.' WRITE LOG ['.$this->fullWritePath.']<br>';
					}else{
						#file_put_contents($this->settings["writePath"].$logDate, $this->traceString);
						file_put_contents ( $this->fullWritePath , $this->traceString,0 ,$this->writeStreamContext);
						#echo __METHOD__.'@'.__LINE__.' WRITE LOG ['.$this->fullWritePath.']<br>';
					}
					unset($this->traceString);
		}
	}
	/**
	* DESCRIPTOR: IE: This always returns a myclass
	* @param int $debugLevel 
	* @param string $Error 
	* @param string $Desc 
	* @param mixed $CC
	* 
	* $debugLevel see: JCORE/CONFIG/SERVICES/LOG/logServices.ini or http://us.php.net/manual/en/function.syslog.php
	* $Error: error name or ID
	* $Desc: long description
	* $CC: "Carbon Copy" send the log to another logger as well
	* 		accepts:
	* 			string [one of the Loggers defined in JCORE/LOAD/BOOTSTRAP.php]
	* 			OR
	* 			object an instantiated instance of this class or one 
	* 				that uses a "log" method with the same signature
	* 				($debugLevel=LOG_DEBUG, $Error ='', $Desc ='')
	* 
	* @return outputErrors 
	*/
	function log($debugLevel=LOG_DEBUG, $Error ='', $Desc ='', $CC=null){
		if(null !== $CC){
			switch($CC){
				case is_string($CC):
					$GLOBALS[$CC]->log($debugLevel, $Error, $Desc);
					break;
				case is_object($CC):
					$CC->log($debugLevel, $Error, $Desc);
					break;
				default:
					break;
			}
		}
		switch($this->logFacility){
			case'FILE':
				$this->writeToFile($debugLevel, $Error, $Desc);
				break;
			case'SYSLOG':
				syslog($debugLevel,$this->traceString);
				break;
			case'UDP':
				break;
			case'DATABASE':
				break;
			default:
				break;
		}
		return;
	}
	
	/**
	 * DESCRIPTOR: writes errors to log on destruction
	 * @param NULL
	 * @return NULL
	 */
	public function __destruct(){
		#$this->KILL = TRUE;
		#$this->outputTrace(LOG_INFO);
		if($this->logFacility == 'FILE'){
			$this->settings["bufferWrite"] = FALSE;
			$this->writeToFile(null);
			unset($this->traceString);
		}
		return;
	}
	//----------------------------------------------------
	
	//----------------------------------------------------
}
 

?>