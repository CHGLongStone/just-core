<?php
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	LOAD 
 */
namespace JCORE\LOAD;
/**
 * Class CONFIG_MANAGER
 *
 * @package JCORE\LOAD
*/
class CONFIG_MANAGER{
	/**
	 * @access public 
	 * @var string
	 */
	public $A = array();
	protected $LOADED_VALUES = array();
	#protected $PLUGINS = array();
	protected $settings = array();
	private $C = '';
	private $CONFIG_PATH = 'CONFIG/AUTOLOAD/';
	private $CACHE_PATH = 'CACHE/FILE/';
	/**
	 * CACHEABLE CONFIG. 
	 * if we don't have a cache with and API we're looking @ an I/O hit for all the config files
	 * use XDEBUG [or equiv] to VERIFY  your relative execution times [cache vs file load] (these can change under varying types of load)
	 * 
	 * $args["CSN"]
	 * $args["INSTANCE"]
	 * $args["CACHE_SERIALIZATION"]
	 * $args["UNSERIALIZE_TYPE"]
	 * 
	 * if no chache source nothing else to do...
	 * otherwise set defaults and over-ride if the arg is passed
	 * $this->settings["CACHE_SERIALIZATION"] = 'JSON';
	 * $this->settings["UNSERIALIZE_TYPE"] = 'ARRAY';
	 * $this->settings["IMPLEMENTATION"] = 'STATIC';
	 * 		["IMPLEMENTATION"] != 'STATIC' we either use an instantiated class or fail to no cache
	 * 
	 * @param array $args
	 * @ return bool;
	 */
	public function __construct($args=NULL){
		if(isset($args["CONFIG_PATH"]) && is_dir($args["CONFIG_PATH"])){
			$this->CONFIG_PATH = $args["CONFIG_PATH"];
		}
		if(isset($args["CACHE_PATH"]) && is_dir($args["CACHE_PATH"])){
			$this->CACHE_PATH = $args["CACHE_PATH"];
		}else{
			$this->CACHE_PATH = $GLOBALS["APPLICATION_ROOT"].$this->CACHE_PATH;
			
		}
		
		return false;
	}
	/**
	*
	*
	*/
	public function setCache($args=NULL){
		if(is_array($args)){
			if(isset($args["CSN"])){
				$this->settings["CSN"] = $args["CSN"]; //for consistancy
				$this->settings["CACHE_SERIALIZATION"] = 'JSON';
				if(isset($args["CACHE_SERIALIZATION"])){
					$this->settings["CACHE_SERIALIZATION"] = $args["CACHE_SERIALIZATION"];
				}
				$this->settings["UNSERIALIZE_TYPE"] = 'ARRAY';
				if(isset($args["UNSERIALIZE_TYPE"])){
					$this->settings["UNSERIALIZE_TYPE"] = $args["UNSERIALIZE_TYPE"];
				}
				$this->settings["IMPLEMENTATION"] = 'STATIC';				
				if(isset($args["IMPLEMENTATION"]) 
					&& 
					$args["IMPLEMENTATION"] != 'STATIC'
					&& 
					(
						isset($args["INSTANCE"]) 
					) 
				){
					//INSTANCE
					@$this->settings["INSTANCE"] = new $args["INSTANCE"]($args);
					$this->settings["IMPLEMENTATION"] = $args["IMPLEMENTATION"];
				}
				if(!is_object($this->settings["INSTANCE"])){
					echo __METHOD__.'@'.__LINE__.'********** !is_object **********<br>';
					
					return false;
				}		
			}
			#echo __METHOD__.'@'.__LINE__.' ********************<br>';
			return true;
		}
	}

	/*****
	* DESCRIPTOR: Checks the cache if the value is set
	* cache args are set in the constructor
	* @param string $LOAD_ID 
	* @return mixed 
	*/
	protected function preHookCache($LOAD_ID){
		if(isset($this->settings["CSN"])){
			//JCORE_SYSTEM_CACHE
			$args = array();
			if(isset($this->settings["CACHE_SERIALIZATION"])){
				$args["CACHE_SERIALIZATION"] = $this->settings["CACHE_SERIALIZATION"];
			}
			if(isset($this->settings["UNSERIALIZE_TYPE"])){
				$args["UNSERIALIZE_TYPE"] = $this->settings["UNSERIALIZE_TYPE"];
			}
			$methodName = 'getValue';
			$args["KEY"] = $LOAD_ID;
			if(isset($this->settings["IMPLEMENTATION"]) && $this->settings["IMPLEMENTATION"] == 'STATIC'){
				$myCallBackFunction = array($this->settings["CSN"], $methodName);
				$var = call_user_func($myCallBackFunction, $args);
				
			}else{
				$myCallBackFunction = array($this->settings["CSN"],$methodName);
				$var = call_user_func($myCallBackFunction, $args);
			}
			if(null !== $var){
				
				$this->LOADED_VALUES[$LOAD_ID] = $var;
				return true;
			}
		}
		return false;
	}
	/*****
	* DESCRIPTOR: Checks the cache if the value is set
	* $args["KEY"]
	* $args["DATA"]
	* $args["CACHE_SERIALIZATION"]
	* $args["UNSERIALIZE_TYPE"]
	* @return mixed 
	*/
	protected function postHookCache($args){
		
		#echo __METHOD__.__LINE__.'<b>[<pre>'.var_export($args,true).'</pre>]</b><br>';
		
		if(isset($this->settings["CSN"])){
			#$myCallBackFunction = $this->settings["CSN"].'::setSharedValue';
			$methodName = 'setSharedValue';
			#echo __METHOD__.__LINE__.'<b>[<pre>'.var_export($args,true).'</pre>]</b><br>';
			#$args = array();
			#$args = array();
			if(isset($this->settings["CACHE_SERIALIZATION"]) && !isset($args["CACHE_SERIALIZATION"])){
				$args["CACHE_SERIALIZATION"] = $this->settings["CACHE_SERIALIZATION"];
			}
			
			$args["ttl"] = 0;
			#$var = call_user_func ($myCallBackFunction,   $args);
			if(isset($this->settings["IMPLEMENTATION"]) && $this->settings["IMPLEMENTATION"] == 'STATIC'){
				#echo __METHOD__.__LINE__.'<b>class_exists($this->settings["CSN"]['.class_exists($this->settings["CSN"]).']</b><br>';
				$myCallBackFunction = array($this->settings["CSN"], '::'.$methodName);
				$myCallBackFunction = array($this->settings["CSN"], $methodName);
				$var = call_user_func($myCallBackFunction, $args);
				
			}else{
				$myCallBackFunction = array($this->settings["CSN"],$methodName);
				$var = call_user_func($myCallBackFunction, $args);
			}
			return true;
		}
		//log it?
		return false;
	}
	
	/**
	*
	*/
	public function checkCompiled($args=null){
		/**
		* LIST:	 
		* 	ls -lah - list, all, human readable
		* http://unix.stackexchange.com/questions/35832/how-do-i-get-the-md5-sum-of-a-directorys-contents-as-one-sum
		* 
		* find -s somedir -type f -exec md5sum {} \; | md5sum
		* find somedir -type f -exec md5sum {} \; | sort -k 2 | md5sum
		* tar -cf - somedir | md5sum
		$passCMD = 'ls -lah '.$this->CONFIG_PATH;
		*/
		$passCMD = 'tar -cf - '.$this->CONFIG_PATH.' | md5sum';
		
		
		#$passResult = null;
		$passResult = shell_exec($passCMD);
		#echo __METHOD__.'@'.__LINE__.'  passResult<pre>['.var_export($passResult, true).']</pre> '.'<br>'.PHP_EOL; 
		$hashResult = md5($passResult);
		#echo __METHOD__.'@'.__LINE__.'  hashResult<pre>['.var_export($hashResult, true).']</pre> '.'<br>'.PHP_EOL; 
		
		$this->lastHash = '';
		if(is_file($this->CACHE_PATH.'compiled.hash')){
			$this->lastHash = file_get_contents($this->CACHE_PATH.'compiled.hash');
		}
		if($this->lastHash != $hashResult){
			file_put_contents($this->CACHE_PATH.'compiled.hash', $hashResult);
			return $hashResult;
		}
		return false;
	}
	/**
	*
	*/
	public function loadConfigFile($args=null){
		if(isset($args["file"])){
			return include $args["file"];
		}
	}
	/**
	* DESCRIPTOR: loads the config file and returns a value of true if all good 
	*
	* loads everything in $this->CONFIG_PATH/*{global,local}.php  by default
	*
	* @param string $LOAD_ID 
	* @return null 
	* 
	* $LOAD_ID is the directory path 
	*/
	public function loadConfig($LOAD_ID=''){
		if($LOAD_ID ==''){
			if(!isset($this->settings) || 0 == count($this->settings) ){
				
				$checkCompiled = $this->checkCompiled();
				if(false === $checkCompiled){
					if(is_file($this->CACHE_PATH.'compiled.'.$this->lastHash.'.php')){
						$lastConfig = file_get_contents($this->CACHE_PATH.'compiled.'.$this->lastHash.'.php');
						$lastConfig = include $this->CACHE_PATH.'compiled.'.$this->lastHash.'.php';
						#echo __METHOD__.'@'.__LINE__.'  lastConfig<pre>['.var_export(array_keys($lastConfig),true).']</pre> '.'<br>'.PHP_EOL; 
						$this->settings = $lastConfig;
						#return true;
					}
				}
				

				
				$LOAD_ID = 'JCORE';
				$pattern = $this->CONFIG_PATH.'*{global,local}.php';
				$fileList = glob($pattern,GLOB_BRACE);
				$this->saveConfig($fileList);
				$args = array();
				$args["KEY"] = $LOAD_ID;
				$args["DATA"] = $this->settings[$LOAD_ID];
				
				if(true == $checkCompiled){
					#echo __METHOD__.'@'.__LINE__.'  this->settings<pre>['.var_export(array_keys($this->settings),true).']</pre> '.'<br>'.PHP_EOL; 
					//array_keys 
					$parsedSettings = var_export($this->settings,true);
					#echo __METHOD__.'@'.__LINE__.'  parsedSettings<pre>['.var_export(array_keys($parsedSettings),true).']</pre> '.'<br>'.PHP_EOL; 
					
					$compiledSettings = '<?php 
					return '.$parsedSettings.';?>
					';
					
					
					$putResult = file_put_contents($this->CACHE_PATH.'compiled.'.$checkCompiled.'.php', $compiledSettings);
					#echo __METHOD__.'@'.__LINE__.'  putResult<pre>['.var_export($putResult, true).']</pre> '.'<br>'.PHP_EOL; 
				}
				$this->postHookCache($args);
		
		
			
			}else{
				return false;
			}
		}
		//pre hook into cache needs catch all 
		if(true === $this->preHookCache($LOAD_ID)){
			return true;
		}
		
		//post hook set into cache needs catch all 
		if($this->settings[$LOAD_ID] == $this->LOADED_VALUES[$LOAD_ID]){
			$args = array();
			$args["KEY"] = $LOAD_ID;
			$args["DATA"] = $this->settings[$LOAD_ID];
			$this->postHookCache($args);
			return true;
		}
		return false;
	}
	
	/**
	*
	* @param string $LOAD_ID 
	* @return null 
	*/
	public function saveConfig($fileList){
		
		
		foreach($fileList AS $key => $value){
			$args["file"] = $value;
			$config = $this->loadConfigFile($args);
			$this->settings = $this->MergeConfig($this->settings, $config);
		}
		foreach(array_keys ($this->settings) AS $key => $value){
			$this->LOADED_VALUES[$value] = $this->settings[$value];
		}
		
	}
	/***
	* lifted from example by andyidol at gmail dot com
	* here http://php.net/manual/en/function.array-merge-recursive.php
	* to address disfunctionality of array_merge and array_merge_recursive
	*/
	public function MergeConfig($settings, $config)
	{
	  if(is_array($config)){
		foreach($config as $key => $Value){
			#echo __METHOD__.'@'.__LINE__.'$key<pre>['.$key.']</pre>'.'<br>'; 
			if(array_key_exists($key, $settings) && is_array($Value)){
				$settings[$key] = $this->MergeConfig($settings[$key], $config[$key]);
			}else{
				$settings[$key] = $Value;
			}
		}
		  
	  }else{
			/*
		 echo __METHOD__.'@'.__LINE__.'
		  $config is_array ['.is_array($config).']<br>
		  $config is_bool  ['.is_bool($config).']<br>
		  $config is_object  ['.is_object($config).']<br>
		  $config is_scalar  ['.is_scalar($config).']<br>
		  $config ['.$config.']<br>
		  '.PHP_EOL; 
			*/
		  
	  }

	  return $settings;

	}	
	
	/**
	* DEPRECATED :  no more *.ini format
	* 
	* tree is still useful... rework MPTT or basic parent hook back
	* 
	* loads the bases ini internally, then all the subfiles, and returns a value of true if all good 
	* @param string $LOAD_ID 
	* @param string $FILE_NAME 
	* @return null 
	* 
	* $LOAD_ID is the directory path 
	* $FILE_NAME is the file name with ".ini"
	public function loadIniTree($LOAD_ID='', $FILE_NAME=''){
		#echo __METHOD__.__LINE__.'<br>';
		if($LOAD_ID =='' || $FILE_NAME == ''){
			return false;
			//explode on period
				#$PACKAGE = strstr  ( $calledClass, ".", true );
				#$PLUGIN = strstr  ( $calledClass, ".");
		}
		//hook into cache
		if(true === $this->preHookCache($LOAD_ID)){
			return true;
		}
		if($this->LOADED_VALUES[$LOAD_ID] = parse_ini_file($FILE_NAME,true)){

			foreach($this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"] AS $key => $POOL_NAME){
				unset($this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"][$key]);
				$this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"][$POOL_NAME] = $this->LOADED_VALUES[$LOAD_ID][$POOL_NAME];
				if(
					$this->LOADED_VALUES[$LOAD_ID][$POOL_NAME]["ACTIVE"] == "TRUE" 
					&&
					$this->LOADED_VALUES[$LOAD_ID][$POOL_NAME]["LOAD_POOL"] == "TRUE" 
				){
					$subname = JCORE_CONFIG_DIR.'SERVICE/'.$LOAD_ID.'/'.$POOL_NAME.'.ini';
					$SERVER_LIST = parse_ini_file($subname, true);
					if(false !== $SERVER_LIST && count($SERVER_LIST) > 0){
						$this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"][$POOL_NAME]["POOL"] = $SERVER_LIST; 
					}
				}else{
					#fuck off eh
				}
				unset($this->LOADED_VALUES[$LOAD_ID][$POOL_NAME]);
				
			}
			$this->LOADED_VALUES[$LOAD_ID] = $this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"];
			$args = array();
			$args["KEY"] = $LOAD_ID;
			$args["DATA"] = $this->LOADED_VALUES[$LOAD_ID];
			$this->postHookCache($args);
			return true;
		}
		return false;
	}
	*/
	
	
	/**
	* DESCRIPTOR: gets the setting.
	* @param string $LOAD_ID 
	* @param string $SECTION_NAME
	* @param string $SETTING_NAME 
	* @return null 
	* 
	* treat args as $setting[LOAD_ID][SECTION_NAME][SETTING_NAME]
	* $LOAD_ID is the directory path $LOAD_ID only will return all vars defined in the file
	* $SECTION_NAME [section] name in the ".ini" $SECTION_NAME, $LOAD_ID only will return the whole section
	* $SETTING_NAME setting under [section]  in the ".ini"
	* 
	* 
	* 
	* 
	* 
	*/
	public function getSetting($LOAD_ID = null, $SECTION_NAME = null, $SETTING_NAME = NULL){
		if(!isset($this->LOADED_VALUES)){
			//hook into cache	
		}
		if($SETTING_NAME != NULL){
			if(isset($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME])){
				return $this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME];
			}
		}elseif($SECTION_NAME != null){
			if(isset($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME])){
				return $this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME];
			}
		}elseif(isset($this->LOADED_VALUES[$LOAD_ID])){
			return $this->LOADED_VALUES[$LOAD_ID];
		}else{
			return $this->LOADED_VALUES;
		}
		return false;
	}
	//----------------------------------------------------
	/**
	* DESCRIPTOR: .
	* loads the ini internally and returns a value of true if all good 
	* @param string $section 
	* @return array
	* returns the defined constants array with the "categories" flag
	* $section is the "category" returned 
	* "user" is default
	* other options:
	* internal, date, libxml, openssl, pcre, zlib, calendar, hash, filter, ftp, gmp, iconv, standard,
	* sockets, exif, tokenizer, xml, curl,dom, gd, imap, ldap, mbstring, mcrypt, mhash, mysql, mysqli
	* pgsql, posix, snmp, soap
	*/
	public function getConstants($section="user"){
		$defined_constants = get_defined_constants(true);
		if(null === $section || false === $section || $section == ''){
			return $defined_constants;			
		}
		if(isset($defined_constants[$section])){
			return $defined_constants[$section];
		}
		return false;
		#echo 'defined_constants["user"]<pre>'.var_export($defined_constants["user"], true).'</pre><br>';
	}


	
}
 

?>