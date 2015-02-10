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
	protected $PLUGINS = array();
	protected $settings = array();
	private $C = '';
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
	public function __construct2($args=NULL){
		#echo __METHOD__.__LINE__.'<br>';
		if(is_array($args)){
			/**
			*/
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
					(!isset($args["INSTANCE"]) || !is_object($args["INSTANCE"]) ) 
				){
					$this->settings["INSTANCE"] = $args["INSTANCE"];
					$this->settings["IMPLEMENTATION"] = $args["IMPLEMENTATION"];
				}else{
					return false;
				}		
			}
			return true;
		}
		return false;
	}
	/**
	*
	*
	*/
	public function __construct($args=NULL){
		
		#'config_glob_paths' => array('CONFIG/AUTOLOAD/{,*.}{global,local}.php')
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
			#echo __METHOD__.__LINE__.'$this->settings["CSN"]['.$this->settings["CSN"].']<br>';
			#$this->settings["CSN"]= $args["CSN"];
			
			$args = array();
			if(isset($this->settings["CACHE_SERIALIZATION"])){
				$args["CACHE_SERIALIZATION"] = $this->settings["CACHE_SERIALIZATION"];
			}
			if(isset($this->settings["UNSERIALIZE_TYPE"])){
				$args["UNSERIALIZE_TYPE"] = $this->settings["UNSERIALIZE_TYPE"];
			}
			#echo __METHOD__.__LINE__.'<b>$this->settings(<pre>'.var_export($this->settings,true).'</pre>)<br>';
			#echo __METHOD__.__LINE__.'<pre>'.var_export(get_declared_classes(), true).'</pre><br>';
			$methodName = 'getValue';
			$args["KEY"] = $LOAD_ID;
			if(isset($this->settings["IMPLEMENTATION"]) && $this->settings["IMPLEMENTATION"] == 'STATIC'){
				#echo __METHOD__.__LINE__.'<b>class_exists($this->settings["CSN"]['.class_exists($this->settings["CSN"]).'] method_exists['.method_exists($this->settings["CSN"], $methodName).']</b><br>';
				#$myCallBackFunction = array($this->settings["CSN"], '::'.$methodName);
				$myCallBackFunction = array($this->settings["CSN"], $methodName);
				$var = call_user_func($myCallBackFunction, $args);
				
			}else{
				$myCallBackFunction = array($this->settings["CSN"],$methodName);
				$var = call_user_func($myCallBackFunction, $args);
			}
			#echo __METHOD__.__LINE__.'<b>'.$myCallBackFunction.'(<pre>'.var_export($args,true).'</pre>)--$var['.gettype($var).']['.(false === $var).']['.$var.'][<pre>'.var_export($var,true).'</pre>]</b><br>';
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
	public function loadConfigFile($args=null){
		$settings = scandir ($directory );
		$pattern = array('CONFIG/AUTOLOAD/{,*.}{global,local}.php');
		$settings = glob ( $pattern GLOB_MARK );
		return include 'CONFIG/AUTOLOAD/just-core.global.php';
	}
	/**
	* DESCRIPTOR: loads the ini internally and returns a value of true if all good 
	* @param string $LOAD_ID 
	* @param string $FILE_NAME 
	* @return null 
	* 
	* $LOAD_ID is the directory path 
	* $FILE_NAME is the file name with ".ini"
	*/
	public function loadConfig($LOAD_ID='', $FILE_NAME=''){
		if($LOAD_ID =='' || $FILE_NAME == ''){
			return false;
			//explode on period
			/**
			#'config_glob_paths' => array('CONFIG/AUTOLOAD/{,*.}{global,local}.php')
				$PACKAGE = strstr  ( $calledClass, ".", true );
				$PLUGIN = strstr  ( $calledClass, ".");
			*/
		}
		//pre hook into cache
		if(true === $this->preHookCache($LOAD_ID)){
			return true;
		}
		
		//post hook set into cache 
			foreach (glob("*.txt") as $filename) {
				echo "$filename size " . filesize($filename) . "\n";
			}
		if($this->LOADED_VALUES[$LOAD_ID] = parse_ini_file($FILE_NAME,true)){
			$args = array();
			$args["KEY"] = $LOAD_ID;
			$args["DATA"] = $this->LOADED_VALUES[$LOAD_ID];
			#echo __METHOD__.__LINE__.'$this->LOADED_VALUES[$LOAD_ID]<pre>'.var_export($this->LOADED_VALUES[$LOAD_ID], true).'</pre><br>';
			$this->postHookCache($args);
			return true;
		}
		return false;
	}
	
	/**
	* DESCRIPTOR: 
	* loads the bases ini internally, then all the subfiles, and returns a value of true if all good 
	* @param string $LOAD_ID 
	* @param string $FILE_NAME 
	* @return null 
	* 
	* $LOAD_ID is the directory path 
	* $FILE_NAME is the file name with ".ini"
	*/
	public function loadIniTree($LOAD_ID='', $FILE_NAME=''){
		#echo __METHOD__.__LINE__.'<br>';
		if($LOAD_ID =='' || $FILE_NAME == ''){
			return false;
			//explode on period
			/**
				$PACKAGE = strstr  ( $calledClass, ".", true );
				$PLUGIN = strstr  ( $calledClass, ".");
			*/
		}
		//hook into cache
		if(true === $this->preHookCache($LOAD_ID)){
			return true;
		}
		if($this->LOADED_VALUES[$LOAD_ID] = parse_ini_file($FILE_NAME,true)){
			
			/**
			*/
			#echo('$LOAD_ID['.$LOAD_ID.']<pre>['.var_export($this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"], true).']</pre>').'<br>';
			#$cachePool = parse_ini_file(JCORE_CONFIG_DIR.'/SERVICE/cachePool.ini', true);
			foreach($this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"] AS $key => $POOL_NAME){
				#echo('$key['.$key.'] $POOL_NAME---['.var_export($POOL_NAME,true).']--').'<br>'; 
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
						//parse_ini_file(JCORE_CONFIG_DIR.'/SERVICE/CACHE_POOL/'.$POOL_NAME.'.ini', true);
					}
					#echo __METHOD__.__LINE__.'$subname['.$subname.']<pre>['.var_export($SERVER_LIST, true).']</pre>'.'<br>'; 
					

				}else{
					#echo __METHOD__.__LINE__.'$subname['.$subname.']<pre>['.var_export($CACHECFG, true).']</pre>'.'<br>'; 
					#$this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"][$POOL_NAME] = '';
					#$this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"][$POOL_NAME] = $this->LOADED_VALUES[$LOAD_ID][$POOL_NAME];
				}
				unset($this->LOADED_VALUES[$LOAD_ID][$POOL_NAME]);
				
			}
			#echo __METHOD__.__LINE__.'$subname['.$subname.']<pre>['.var_export($this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"], true).']</pre>'.'<br>'; 
			$this->LOADED_VALUES[$LOAD_ID] = $this->LOADED_VALUES[$LOAD_ID][$LOAD_ID]["CSN"];
			$args = array();
			$args["KEY"] = $LOAD_ID;
			$args["DATA"] = $this->LOADED_VALUES[$LOAD_ID];
			$this->postHookCache($args);
			return true;
		}
		return false;
	}
	
	
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
			if($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME]){
				return $this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME];
			}
		}elseif($SECTION_NAME != null){
			if($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME]){
				return $this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME];
			}
		}elseif($this->LOADED_VALUES[$LOAD_ID]){
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
	//----------------------------------------------------
	/**
	* DESCRIPTOR:.
	* loads the ini internally and returns a value of true if all good 
	* @param string $LOAD_ID 
	* @param string $SECTION_NAME 
	* @param string $SETTING_NAME 
	* @return null 
	* 
	*/
	public function setIniAsConstant($LOAD_ID = null, $SECTION_NAME = null, $SETTING_NAME = NULL){
		if(!isset($this->LOADED_VALUES)){
			//hook into cache	
		}
		if($SETTING_NAME != NULL){
			if($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME]){
				array_walk_recursive($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME][$SETTING_NAME], 'this->wrapDefine');
				return ;
			}
		}elseif($SECTION_NAME != null){
			if($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME]){
				array_walk_recursive($this->LOADED_VALUES[$LOAD_ID][$SECTION_NAME], 'CONFIG_MANAGER::wrapDefine' , $prepend=$LOAD_ID.'_'.$SECTION_NAME.'_');
				return ;
			}
		}elseif($this->LOADED_VALUES[$LOAD_ID]){
			/**
			* you really need to load this much
			*/
			array_walk_recursive($this->LOADED_VALUES[$LOAD_ID], 'CONFIG_MANAGER::wrapDefine', $prepend=$LOAD_ID.'_');
			return ;
		}else{
			/**
			* getting rediculous here
			* array_walk_recursive($this->LOADED_VALUES, '$this->wrapDefine');
			*/
			array_walk_recursive($this->LOADED_VALUES[$LOAD_ID], 'CONFIG_MANAGER::wrapDefine', $prepend=$LOAD_ID.'PUUUKE_');
			return ;
		}
		return false;
	
		#array_walk_recursive($fruits, '$this->wrapDefine');
		return;
	}
	/**
	* DESCRIPTOR:.
	* sets an ini setting as a define
	* used by:
	* 	array_walk_recursive( &$input, $funcname [, $userdata]);
	* 		calls: $funcname($value, $key, $userdata)
	* @param string $defineValue 
	* @param string $defineName 
	* @param string $prepend 
	* @return null 
	* 
	* $defineValue is the obvious 
	* $defineName is the obvious 
	* $prepend is added before the define name 
	*/
	public static function wrapDefine($defineValue, $defineName, $prepend=null){
		if(is_string($defineValue) && is_string($defineName)){
			(null !== $prepend)? define($prepend.$defineName, $defineValue) : define ($defineName, $defineValue);
		}
		return;
	}
	/**
	* DESCRIPTOR: Registers a Plugin
	* @param string $pluginName 
	* @return null 
	*/
	public function registerPlugin($pluginName=null){
		if(is_string($pluginName) && $pluginName != ''){
			$this->PLUGINS[$pluginName] = $pluginName;
		}
		return;
	}
	/**
	* DESCRIPTOR: Registers a Plugin
	* @param string $pluginName 
	* @return null 
	*/
	public function getRegisteredPlugins($pluginName=null){
		if(is_string($pluginName) && $pluginName != ''){
			return $this->PLUGINS[$pluginName];
		}else{
			return $this->PLUGINS;
		
		}
		return;
	}
	
}
 

?>