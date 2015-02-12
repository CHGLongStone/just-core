<?php
/**
 * [SUMMARY]
 * DATA BASE CLASS
 * 
 * This class provides access to data databases or files system. 
 * There is an assumption that more than one data source will be required
 * if you want to cache your objects, this object should ALWAYS be referenced 
 * in the global space as
 * $GLOBALS["DATA_API"]
 * OR
 * GLOBAL $DATA_API
 * 
 * if you "internalize" this object in your classes (making it a property of the class)
 * serialization will ONLY store a reference to the object and not the "property" object
 * 
 * [INSTANTIATION]
 * 
 * the contructor requires no arguements as all DB sonnection information is loaded from 
 * JCORE_CONFIG_DIR.'/dataPool.ini'
 * into: 
 * $this->connectorCfg 
 * Databases are referenced by a simple name space or "DSN" (Data Source Name) 
 * i.e. "SERVICES", "USERDATA", "AUTH", "AUTH_SLAVE"...
 * No database connections are set by default, the class automatically checks to see
 * if a valid DB resource exists, if not it tries to establish a connection.
 * a LOG_EMERG message will be recorded if the connection to the DB fails
 * a LOG_ALERT message will be recorded if the query fails
 * 
 * the class is not a singleton so it can have persistent maleable members
 * i.e. the config settings, so any connection can be called @ any time
 * 
 * [CORE METHODS] 
 * 

 * $DATA_API->create($database, $query, $returnArray); 
 * 		$database 		"DSN"
 * 		$query			standard CREATE statement
 * 		$args			array(
 * 							'returnArray' = TRUE/FALSE
 * 						)	
 * 			if TRUE the result is returned as
 *				$resultArray["INSERT_ID"] 		= mysql_insert_id($connection);
 *				$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($connection);
 * 				$resultArray["INFO"] 			= mysql_info($connection);
 * 
 * $DATA_API->retrieve($database, $query, $args=array('returnArray' => true)); 
 * 		$database 		"DSN"
 * 		$query			standard RETRIEVE(SELECT) statement
 * 		$args			array(
 * 							'returnArray' = TRUE/FALSE
 * 						)	
 * 			if TRUE the result is returned as a PHP array
 *  
 * $DATA_API->update($database, $query, $returnArray); 
 * 		$database 		"DSN"
 * 		$query			standard UPDATE statement
 * 		$args			array(
 * 							'returnArray' = TRUE/FALSE
 * 						)	
 * 			if TRUE the result is returned as
 *				$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($connection);
 * 				$resultArray["INFO"] 			= mysql_info($connection);
 * 
 * $DATA_API->delete($database, $query, $args=array('returnArray' => true)); 
 * 		$database 		"DSN"
 * 		$query			standard DELETE statement
 * 		$args			array(
 * 							'returnArray' = TRUE/FALSE
 * 						)	
 * 			if TRUE the result is returned as
 *				$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($connection);
 * 				$resultArray["INFO"] 			= mysql_info($connection);
 * 
 * [SECONDARY METHODS]
 * set_connection($DSN, $settings);
 * 		$DSN			"DSN"
 * 		$settings		array of settings following the format of [JCORE_CONFIG_DIR.'dataPool.ini']
 * 						assumed to be $this->connectorCfg[$DSN]
 * 		SETS/RESETS the connection object itself
 * 
 * verify_connection($DSN);
 * 		$DSN			"DSN"
 * 		- checks if the connection object is set
 * 		- checks if the connection object has a resource and resets if not
 * 		- Passes to the connection object 
 * [KEY PROPERTIES]
 * $connector
 * $dataSchema
 * dbConnection classes must implement dbConnection.interface.php
 * in order to support the base methods of this class
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage DATA
 */
namespace JCORE\DATA\API;
/***
* DATA_API_INTERFACE
*
*/
use JCORE\DATA\API\DATA_API_INTERFACE;
use JCORE\DATA\API\MySQL\MySQL_connector as MySQL_connector;
use JCORE\DATA\MySQL\MySQL_TABLE_META as MySQL_TABLE_META;
#require_once('DATA_API_INTERFACE.interface.php');

/**
 * Interface DATA_API
 *
 * @package JCORE\DATA\API
*/
class DATA_API{

	/**
	 * @access public 
	 * @var array
	 * this is an array of to hold connection objects to each DSN 
	 * that is called by the application
	 */
	public $connector; // = array(); connectionPool
	
	/**
	 * @access public 
	 * @var array
	 */
	public $dataSchema = array(); //tableDefinitions
	/**
	 * @access protected 
	 * @var array
	 * an array to store tables definitions for MySQL/postgres
	 * or the equivelent for NoSQL/File stores
	 */
	protected $connectorCfg = array();  //cfg
	#public $connectorCfg; // = array(); connectionPool
	/**
	 * @access private 
	 * @var string
	 */
	
	private $logger; // = new LOGGER();		
	
	
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	
	/**
	 * Constructor, sets up the logger and loads connection pool data
	 * nothing is set for the connection pool to save resources, 
	 * connections are automatically set/checked on request
	 * 
	 * @param	array $cfg
	 * @param	array $logCfg
	 * @return	null
	 */
	public function __construct($cfg){
		/**/
		#echo __METHOD__.__LINE__.'<br>';
		#FIXIT
		#$logCFG = $GLOBALS['CONFIG_MANAGER']->loadIni($LOAD_ID='JCORE_LOG', $FILE_NAME=JCORE_CONFIG_DIR.'SERVICE/LOG/logServices.ini');
		
		
		#$settings 		= $logCfg;
		#GLOBAL $LOG_DATA;
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger	= $GLOBALS['LOG_DATA'];
		#echo __METHOD__.__LINE__.'<br>';
		#echo(__METHOD__.'<pre>['.var_export($this->logger, true).']</pre>').'<br>'; 
		$this->logger->log(LOG_DEBUG,__METHOD__, '()');
		#echo __METHOD__.__LINE__.'<br>';
		
		# $this->logger	= $GLOBALS['DATA_logger'];
		
		#echo(__METHOD__.'<pre>['.var_export($cfg, true).']</pre>').'<br>'; 
		$this->connectorCfg = $cfg;
		
		#$this->logger->log(LOG_DEBUG,__METHOD__, JCORE_CONFIG_DIR.'/SERVICE/DATA/DATA_API.ini');
		$this->logger->log(LOG_DEBUG,__METHOD__, print_r($this->connectorCfg,true));
		
		$this->connector = array();
		return;
	}
	
	/**
	* DESCRIPTOR: GET A TABLE DEFINITION (only MySQL for now)
	* @param string $database 
	* @param string $tableName 
	* This method passes the connection object to the introspection class
	* @return obj 
	* ***************make this agnoistic, pass to connectiton object
	*/
	public function introspectTable($DSN, $tableName){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', tableName='.$tableName.')');

		//check if the table def exists if it does return it
		if(!isset($this->dataSchema[$DSN][$tableName])){
			#echo __METHOD__.__LINE__.'<br>';

			if(!isset($this->introspectionClass) || !is_object($this->introspectionClass)){
				#echo __METHOD__.__LINE__.'<br>';
				// if the $DSN has been set in config.dbConnectionPool.ini
				if(isset($this->connectorCfg[$DSN]["dbType"])){
					$setByType = TRUE;
				}
				#echo __METHOD__.__LINE__.'<br>';
			}elseif(isset($this->introspectionClass) && $this->introspectionClass->getDbType() != $this->connectorCfg[$DSN]["dbType"]){
				//dbType
				$setByType = TRUE;
				#echo __METHOD__.__LINE__.'<br>';
			}
			//do the CYA and check the connection @ all inputs
			
			#echo __METHOD__.__LINE__.'<br>';
			
			if($setByType === TRUE){
				#echo __METHOD__.'::'.__LINE__.'$this->introspectionClassName['.$this->connectorCfg[$DSN]["dbType"].']'.'<br>';
				$introspectionClassName = $this->connectorCfg[$DSN]["dbType"].'_TABLE_META';
				echo __METHOD__.'::'.__LINE__.'$introspectionClassName['.$introspectionClassName.']'.'<br>';
				if (!class_exists($introspectionClassName)) {
					//log if there is an error
					try{
						#echo __METHOD__.'::'.__LINE__.'$introspectionClassName['.$introspectionClassName.']'.'<br>';
						//this file/class MUST be included in  
						$classPath = $this->connectorCfg[$DSN]["dbType"].'_TABLE_META'.'.class.php';
						if(!class_exists( $classPath)){
							#throw new \Exception('ERROR FAILED DEFINITION '.__METHOD__.'$classPath['.$classPath.']');
						}
						#echo __METHOD__.'::'.__LINE__.'$introspectionClassName['.$introspectionClassName.']'.'<br>';
					}
					catch(Exception $e){
						echo __METHOD__.'::'.__LINE__.'FAILFAILFAILFAILFAILFAILFAILFAILFAILFAILFAIL$this->introspectionClassName['.$this->connectorCfg[$DSN]["dbType"].']'.'<br>';
						$GLOBALS["APPLICATION_logger"]->trace(LOG_EMERG,__METHOD__.__LINE__, 'FATAL Exception '.$e->getMessage().' ['.__METHOD__.']['.__LINE__.'] TRACE['.$e->getTraceAsString().']');
					}
				}
				#echo __METHOD__.'::'.__LINE__.'$introspectionClassName['.$introspectionClassName.']'.'<br>';
				$this->introspectionClass = new $introspectionClassName();
			}else{
				
				#echo __METHOD__.'::'.__LINE__.'$MYSQL_TABLE_META['.$MYSQL_TABLE_META.']'.'<br>';
				$this->introspectionClass = new MYSQL_TABLE_META();
			}
			#echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.'<br>';
			$this->introspectionClass->flushData();

			$this->introspectionClass->initialize($DSN, $tableName, $this->connector[$DSN]);
			
			$this->dataSchema[$DSN][$tableName] = $this->introspectionClass->tableProperties;
			$this->introspectionClass->flushData();
			#echo __METHOD__.'::'.__LINE__.'$introspectionClass['.var_export($this->introspectionClass).']'.'<br>';
		}
		return $this->dataSchema[$DSN][$tableName]; // return pointer??
	}
	
	
	
	/**
	* DESCRIPTOR: (RE)SETS A CONNECTION OBJECT TO THE POOL
	* $settings expected to be $this->connectorCfg[$DSN]
	* this resets the whole connection object
	* the DSN & settings are set in INI/config.dbConnectionPool.ini
	* $settings[persistent] from INI can be over ridden here
	* set it as a boolean or a string 
	* $settings["implementation"] allows a class to be defined @ runtime in config.dbConnection.ini
	* the connection object also has a set_connection method to reset the connection resource
	* @param string $DSN 
	* @param array $settings
	* @return bool true 
	*/
	public function set_connection($DSN, $settings){
		if(!isset($DSN)){
			return false;
		}
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', settings='.var_export($settings,true).')');
		#$settings = this->connectorCfg[$DSN];
		#echo __METHOD__.'::this->connectorCfg['.$DSN.']<pre>'.print_r($this->connectorCfg[$DSN], true).'</pre>'.'<br>';
		#echo __METHOD__.'::settings<pre>'.print_r($settings, true).'</pre>'.'<br>';
		unset($this->connector[$DSN]);
		// check for the override first, if its not set proceeed with regular business
		$settings["logCfg"] = $this->logger;
		if(!isset($settings["implementation"])){
			switch($settings["dbType"]){
				case"REDIS":
					$this->connector[$DSN] = new Redis_connector($DSN, $settings);
					break;
				case"COUCHDB":
					$this->connector[$DSN] = new COUCHDB_connector($DSN, $settings);
					break;
				case"POSTGRES":
					$this->connector[$DSN] = new Postgres_connector($DSN, $settings);
					break;
				case"MYSQL":	//NEW CONFIG
					#$this->connector[$DSN] = new MySQL_connector($DSN, $settings);
					#break;
				default:		//DEFAULT SUPPORT FOR MySQL
					$this->connector[$DSN] = new MySQL_connector($DSN, $settings);
					break;
				//what other types NoSQL
			}
		}else{
			// "implementation" is actually set, lets check it has a value
			if($settings["implementation"] != ''){
				//if the class doesn't exist, svn is out of date or the file is not committed or some other WDF??
				/**
				no direct includes, use the autoloader 
				if(!class_exists($settings["implementation"])){
					require_once $settings["implementation"].'.class.php';
				}
				*/
				########## 
				########## check again or use a try/catch block throw a soft error
				########## and load the default 
				##########
				$this->connector[$DSN] = new $settings["implementation"]($DSN, $settings);			
			}
		}
		return true;		
	}
	
	
	/**
	* DESCRIPTOR: VERIFIES A CONNECTION OBJECT && RESOURCE
	* have to modify this later for NoSQL? couchdb connection will not be a resources
	* @param string $DSN 
	* @return outputErrors 
	** done
	*/
	public function verify_connection($DSN){
		#echo __METHOD__.__LINE__.' $DSN['.$DSN.']<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.')');
		#echo __METHOD__.'@'.__LINE__.'$this->connector<pre>'.var_export($this->connector, true).'</pre><br>';
		//first check if the object is set in the connector
		if(isset($this->connector[$DSN]) && true === is_object($this->connector[$DSN])){
			#echo __METHOD__.__LINE__.'<br>';
			//then we'll check if there is a valid resource
			if(!is_resource($this->connector[$DSN]->connection)){
				#echo __METHOD__.__LINE__.'<br>';
				$this->logger->log(LOG_WARN,__METHOD__, '(DSN='.$DSN.' Connection Failed)');
				unset($this->connector[$DSN]->connection);
				//we have the connection object reset the connection resource
				$this->connector[$DSN]->set_connection($this->connector[$DSN]->persistent);
			}
			#echo __METHOD__.__LINE__.'<br>';
		}else{
			#echo __METHOD__.__LINE__.'$this->connectorCfg['.$DSN.'] <pre>'.var_export($this->connectorCfg[$DSN], true).'<br>'.var_export($this->connectorCfg, true).'</pre><br>';
			//no object is set, we'll dump it from the connector and recreate it
			// this will reset the $this->connector[$DSN]->connection as well
			$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.' Connection Not set)');
			// passing the default settings [$this->connectorCfg[$DSN]] to support open access to the called function "set_connection"
			$this->set_connection($DSN, $this->connectorCfg[$DSN]); 
		}
		#echo __METHOD__.__LINE__.'<br>';
		return;
	}
	/**
	* DESCRIPTOR: CHECKS IF THE DB IS A SLAVE
	* @param string $database 
	* @return bool 
	*/	
	public function checkIsSlave($database){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.')');
		$pos = strrpos($database, "_SLAVE");
		if($pos === FALSE){
			return FALSE;
		}
		return true;
	}
	
	/**
	* DESCRIPTOR: CHOPS "_SLAVE" FROM THE DSN
	* read can be done from slave, writes will go back to master
	* @param string $DSN 
	* @return string DSN 
	*/	
	public function setMaster($DSN){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.')');
		$pos = strrpos($DSN, "_SLAVE");
		if($pos === FALSE){
			return $DSN;
		}else{
			$DSN = substr($DSN,0,$pos);
		}
		return $DSN;
	}
	
	
	/**
	* DESCRIPTOR: EXECUTE A QUERY
	* exception handling and logging dealt with
	* @param string $database 
	* @param string $query 
	* @return $result 
	*/
	public function raw($DSN, $query){//, $args=array('returnArray' => true)
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(database='.$database.', query='.$query.')');
		#echo __METHOD__.__LINE__.'<br>';
		$this->verify_connection($DSN);
		$result = $this->connector[$DSN]->raw($query);

		return $result;
	}
	
	
	/**
	* DESCRIPTOR: EXECUTE A SELECT
	* if $returnArray === true the function will return the result
	* as a PHP array use stdobj to get an object back
	* $args[returnArray] = true
	* @param string $DSN 
	* @param string $query 
	* @param array $args  
	* @return $result 
	*/
	public function retrieve($DSN, $query, $args=array('returnArray' => true)){
		#echo __METHOD__.__LINE__.'<br>';
		#echo __METHOD__.__LINE__.LOG_DEBUG.'::'.__METHOD__.'::'. '(DSN='.$DSN.', query='.$query.' returnArray='.$args["returnArray"].')'.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', query='.$query.' returnArray='.$args["returnArray"].')');
		$this->verify_connection($DSN);
		/**
		* now we pass this down to the connection Object
		*/
		$result = $this->connector[$DSN]->retrieve($query, $args);
		#echo __METHOD__.__LINE__.'<br>';$args=array('returnArray' => true)
		return $result;
	}
	/**
	* DESCRIPTOR: EXECUTE AN UPDATE
	* if $returnArray === true the function will return the number of 
	* affected rows as well as the "mysql_info" from the query
	* @param string $DSN 
	* @param string $query 
	* @param bool $returnArray 
	* @return $result 
	*/
	public function update($DSN, $query, $args=array('returnArray' => true)){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', query='.$query.' returnArray=['.$returnArray.'])');
		#echo '$DSN'.$DSN.' '.'<br>';
		$this->verify_connection($DSN);
		if(true === $this->checkIsSlave($DSN)){
			$DSN = $this->setMaster($DSN);
		}
		#$result = $this->raw($DSN, $query);
		$result = $this->connector[$DSN]->update($query, $args);
		return $result;
	}
	
	/**
	* DESCRIPTOR: EXECUTE AN INSERT
	* if $returnArray === true the function will return the "mysql_insert_id"
	* the number of "mysql_affected_rows" as well as the "mysql_info" from the query
	* @param string $DSN 
	* @param string $query 
	* @param bool $returnArray 
	* @return $result 
	*/
	public function create($DSN, $query, $args=array('returnArray' => true)){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', query='.$query.' returnArray=['.$returnArray.'])');
		$this->verify_connection($DSN);
		if(true === $this->checkIsSlave($DSN)){
			$DSN = $this->setMaster($DSN);
		}
		$result = $this->connector[$DSN]->create($query, $args);
		return $result;
		#return $result;
	}
	/**
	* DESCRIPTOR: EXECUTE A DELETE
	* if $returnArray === true the function will return the number of 
	* affected rows as well as the "mysql_info" from the query
	* @param string $DSN 
	* @param string $query 
	* @param bool $returnArray 
	* @return bool/array $result 
	*/
	public function delete($DSN, $query, $args=array('returnArray' => true)){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(DSN='.$DSN.', query='.$query.' returnArray=['.$returnArray.'])');
		$this->verify_connection($DSN);
		$result = $this->connector[$DSN]->delete($query, $args);
		return $result;
	}

	
	
	/**
	* must be maintained allow passtrough
	* PASSED DOWN TO THE CONNECCTION OBJECT
	* DESCRIPTOR: coverts a SQL result to a PHP array
	* @param resource $result 
	* @param string $DSN 
	* @return array  $result
	* 
	* 
	* HACKING THIS.... for the time being a local mysql only implementation
	* Just to keep the name space consistant from the beginning
	*
	*/
	public function resultToAssoc($result, $DSN){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(result='.$result.')');
		#echo '$resultadasdas<pre>'.var_export($result,true).'</pre>';
		$this->verify_connection($DSN);
		if(is_array($result)){
			return $result;
		}
		if(isset($DSN)){
			$result = $this->connector[$DSN]->resultToAssoc($result, $query);
		}else{
			$result['EXCEPTION']["ID"] = 0;
			$result['EXCEPTION']["MSG"] = 'FAILED TO PROVIDE $DSN i.e. [AUTH] ';
		}
		
		return $resultArray;
	}
	public function SQLResultToAssoc($result, $query=''){ ///, $DSN, $resultType = 'MySQL'
		#echo __METHOD__.__LINE__.'<br>';
		#$this->logger->log(LOG_DEBUG,__METHOD__, '(result='.$result.')');
		//echo __METHOD__.__LINE__.'-------------------NO RESULTS RETURNED-------------------'.'<br>';
		#echo '$resultadasdas<pre>'.var_export($result,true).'</pre>';
		if(is_array($result)){
			echo __METHOD__.__LINE__.'<br>'.'-------------------RESULT IS ARRAY -------------------'.'<br>';
			return $result;
		}
		if( $result === false){
			#return $result;
			echo __METHOD__.__LINE__.'<br>'.'-------------------NO RESULTS RETURNED [RESULT === FALSE]-------------------'.'<br>';
			#$this->logger->log(LOG_NOTICE,'NO RESULTS','DSN['.$this->DSN.'] query['.$query.']');
			return array(); // send an empty array if there is no result  ie "0" rows
			// an error would have already been returned if there was one
		}
		$resultArray = array();
		while($row = mysql_fetch_assoc($result)){
			$resultArray[] = $row;
		}
		try{
			if(count($resultArray) == 0){
				throw new \Exception('SQLResultToAssoc FAILED'); // ONLY BECAUSE WE WANT A TRACE
			}
		}
		catch(\Exception $e){
			#$this->logger->log(LOG_CRIT,__METHOD__, 'SQLResultToAssoc FAILED['.$result.']');
			#$this->logger->log(LOG_NOTICE,$e->getMessage(),$e->getTraceAsString());
			echo $e->getTraceAsString().'<br>';
			#return false;
		}

		#echo '$resultArray<pre>'.var_export($resultArray,true).'</pre>';
		mysql_free_result($result);
		return $resultArray;
	}
	//----------------------------------------------------
	
	//----------------------------------------------------
	function __destruct(){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '()');
		unset($this->logger); // NOT using global logger now
		return;
	}
}
 

#echo __FILE__.'::'.__LINE__.'OUT'.'<br>';

?>