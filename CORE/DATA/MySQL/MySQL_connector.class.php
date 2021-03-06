<?php
/**
 * MySQL_connector
 * 
 *
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\DATA\API\MySQL
 */

namespace JCORE\DATA\API\MySQL;
use JCORE\EXCEPTION\DATA_Exception as DATA_Exception;
use JCORE\EXCEPTION\networkException as networkException;
/**
 * Interface MySQL_connector
 *
 * @package JCORE\DATA\API\MySQL
*/
class MySQL_connector implements \JCORE\DATA\API\DATA_API_INTERFACE{
	/**
	 * The data base "dbType" MYSQL/POSTGRES/...
	 * @access private 
	 * @var string
	 */
	private $dbType = 'MYSQL';
	
	/**
	 * The Data Source Name
	 * @access private 
	 * @var string
	 */
	private $DSN = '';
	
	/**
	 * The host name WITH PORT
	 * @access private 
	 * @var string
	 */
	private $host = '';
	
	/**
	 * username 
	 * @access private 
	 * @var string
	 */
	private $username = '';
	
	/**
	 * password
	 * @access private 
	 * @var string
	 */
	private $password = '';
	
	/**
	 * database.name 
	 * @access private 
	 * @var string
	 */
	private $database = '';
	
	/**
	 * stores the connection resource
	 * @access public 
	 * @var string
	 */
	public $connection;
	
	/**
	 * persistent connection 
	 * @access private 
	 * @var bool
	 */
	private $persistent;
	#introspectionClass

	
	/**
	 * Constructor
	 * 
	 * @access public 
	 * @param	array $value (connection info)
	 * @param	bool $persistent
	 * @return	NULL
	 */
	public function __construct($DSN, $config){
		#echo __METHOD__.__LINE__.'<br>';
		#echo('$config<pre>['.var_export($config, true).']</pre>').'<br>'; 
		$this->config		= $config;
		#$this->type 		= $config["type"];
		$this->DSN 			= $DSN;
		$this->host 		= $config["host"];
		$this->port 		= $config["port"];  // not implemented yet "host:port" can be set in the host value
		$this->username 	= $config["username"];
		$this->password 	= $config["password"];
		$this->database 	= $config["database"];
		//set persistance at config or runtime
		if(isset($config["persistent"]) && 'true' == strtolower($config["persistent"])){
			$this->persistent = true; // tring eval of $config["persistent"];
		}else{
			$this->persistent 	= $persistent;
		}
		
		if(is_object($config["logCfg"])){
			#echo __METHOD__.__LINE__.'<br>';
			$this->logger	= $config["logCfg"];
		}else{
			#echo __METHOD__.__LINE__.'<br>';
			$this->logCfg	= $config["logCfg"];
			$this->logger	= new LOGGER($this->logCfg);
		}
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '()');
		
		/**/
		#$this->logger	=& $GLOBALS['LOG_DATA'];
		#echo __METHOD__.__LINE__.'<br>';
		$this->set_connection($this->persistent);
		$this->logger->log(LOG_DEBUG, __METHOD__, '$this->DSN==['.$this->DSN.'] $host=['.$config["host"].']');
		return;
	}
	/**
	* DESCRIPTOR: Get the "private" dbType
	* 
	* @access public 
	* @param	NULL
	* @return string $dbType 
	*/
	public function getDbType(){
		return $this->dbType;
	}
	/**
	* DESCRIPTOR: This sets a connection resource
	* 
	* @access public 
	* @param bool/string $persistent  pass TRUE/FALSE OR 'true' from ini [config.dbConnectionPool.ini]
	* @return NULL 
	*/
	public function set_connection($persistent=NULL){
		#echo __METHOD__.__LINE__.'<br>';
		// set a retry delay value
		#echo __METHOD__.'@'.__LINE__.'$this<pre>'.var_export($this, true).'</pre><br>';
		#$DB_CONNECT_RETRY =  $GLOBALS["SYSTEM_SETTINGS"]["APPLICATION"]["DB_CONNECT_RETRY"];
		#DB_CONNECT_RETRY =  $this->config["DB_CONNECT_RETRY"];
		#echo __METHOD__.'@'.__LINE__.'$DB_CONNECT_RETRY<pre>'.var_export($DB_CONNECT_RETRY, true).'</pre><br>';
		if(!is_bool($persistent)){
			if(is_string($persistent)){
				if('true' == strtolowwer($persistent)){
					$persistent = true;
				}elseif('false' == strtolowwer($persistent)){
					$persistent = false;
				}
			}
			$persistent = $this->persistent;
		}
		
		#echo('$this<pre>['.var_export($this, true).']</pre>').'<br>'; 
		#echo __METHOD__.__LINE__.'<br>';
		try{ //Double Try, we want to be able to rethrow an exception
			try{
				/*
				 * TRY TO SET THE CONNECTION
				*/
				$host = $this->host;
				if($this->port){
					$host = $this->host.':'.$this->port;
				}
				
				if($persistent===true){
					@$connection = mysql_pconnect($host, $this->username, $this->password);
				}else{
					@$connection = mysql_connect($host, $this->username, $this->password);
				}
				/*
				 * VERIFY THE CONNECTION
				*/
				if(!is_resource($connection) ){
					throw new networkException('WARNING CONNECTION FAILED::['.$this->DSN.']['.$this->database.']'.mysql_errno());
				}
				/*
				 * VERIFY THE DATABASE
				*/
				if(!mysql_select_db($this->database, $connection)){
					throw new DATA_Exception('WARNING SELECT DB ['.$this->DSN.']['.$this->database.'] FAILED::'.mysql_errno());
				}
				$this->connection = $connection;
			}
			// could be network/connection issues lets try again after a quick snooze
			catch(DATA_Exception $e){
				$this->logger->log(LOG_CRIT,$e->getMessage(),$e->getTraceAsString());
				#usleep($DB_CONNECT_RETRY);
				if(!mysql_select_db($this->database, $connection)){
					throw new DATA_Exception('CRITICAL SELECT DB ['.$this->DSN.']['.$this->database.'] FAILED:: 2nd attempt'.mysql_errno());
				}
				$this->connection = $connection;
			}
			
			catch(networkException $e){ 
				$this->logger->log(LOG_CRIT,$e->getMessage(),$e->getTraceAsString());
				//2nd Time: could be network/connection issues lets try again after a quick snooze
				#usleep($DB_CONNECT_RETRY);
				if($persistent===true){
					$connection = @mysql_pconnect($this->host, $this->username, $this->password);
				}else{
					$connection = @mysql_connect($this->host, $this->username, $this->password);
				}
				if(!is_resource($connection) ){
					throw new \Exception('CRITICAL CONNECTION FAILED:: 2nd attempt on DB['.$this->DSN.']['.$this->database.']'.mysql_errno());
				}
				if(!mysql_select_db($this->database, $connection)){
					throw new \Exception('CRITICAL SELECT DB ['.$this->DSN.']['.$this->database.'] FAILED:: 3rd attempt'.mysql_errno());
				}
				$this->connection = $connection;
			}
			
		}// END OUTER TRY
		//we couldn't fix it so log it
		catch(Exception $e){
			$this->connection = false;
			$this->logger->log(LOG_EMERG,$e->getMessage(),$e->getTraceAsString());
		}

		
		return;
	}
	
	/**
	* DESCRIPTOR: This checks if a connection resource is persistent
	* 
	* @access public 
	* @param NULL
	* @return bool  $this->persistent
	*/
	public function is_persistent(){
		#echo __METHOD__.__LINE__.'<br>';
		return $this->persistent; //add a getter since this is private and you can't actually change it without resetting the connetion
	}
	//----------------------------------------------------
	/**
	* DESCRIPTOR: VERIFIES A CONNECTION
	*
	* @access public 
	* @param ignored $paramie 
	* @return NULL 
	*/
	public function verify_connection(){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '()');
		if(!is_resource($this->connection)){
			unset($this->connection);
			$this->set_connection($this->persistent);
		}
		return;
	}

	//echo __METHOD__.__LINE__.'<br>'.'****************(query='.$query.' returnArray='.$returnArray.')'.'<br>';
	//----------------------------------------------------
	/**
	* DESCRIPTOR: coverts a MYSQL result to an array
	* @param resource $result 
	* @param string $query
	* would rather not send the query (its unneeded for functionality)
	* but we do want to know what happened if the convert to array failed
	* 
	* @access public 
	* @param string query
	* @param mixed result
	* @return array  $resultArray
	*/
	public function resultToAssoc($result, $query){ ///, $DSN, $resultType = 'MySQL'
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(result='.$result.')');
		//echo LN.__METHOD__.__LINE__.'-------------------NO RESULTS RETURNED-------------------'.'<br>';
		#echo '$resultadasdas<pre>'.var_export($result,true).'</pre>';
		if(is_array($result)){
			#echo LN.__METHOD__.__LINE__.'<br>'.'-------------------RESULT IS ARRAY -------------------'.'<br>';
			return $result;
		}
		if( $result === false){
			#return $result;
			#echo LN.__METHOD__.__LINE__.'<br>'.'-------------------NO RESULTS RETURNED [RESULT === FALSE]-------------------'.'<br>';
			$this->logger->log(LOG_NOTICE,'NO RESULTS','DSN['.$this->DSN.'] query['.$query.']');
			return array(); // send an empty array if there is no result  ie "0" rows
			// an error would have already been returned if there was one
		}
		$resultArray = array();
		while($row = mysql_fetch_assoc($result)){
			$resultArray[] = $row;
		}
		try{
			if(count($resultArray) == 0){
				#throw new DATA_Exception(__METHOD__.' FAILED'); // ONLY BECAUSE WE WANT A TRACE
				$this->logger->log(LOG_NOTICE,'no result',$query);
			}
		}
		catch(DATA_Exception $e){
			#$this->logger->log(LOG_CRIT,__METHOD__, 'resultToAssoc FAILED['.$result.']');
			$this->logger->log(LOG_NOTICE,$e->getMessage(),$e->getTraceAsString());
			#return false;
		}

		#echo '$resultArray<pre>'.var_export($resultArray,true).'</pre>';
		return $resultArray;
	}
	/**
	* DESCRIPTOR: EXECUTE A QUERY
	* raw string
	* 	
	* @param string $query 
	* @return $result 
	*/
	public function raw($query){//, $returnArray=false
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '( query='.$query.')');
		$this->verify_connection(); 
		try{
			$result = @mysql_query($query, $this->connection);
			if(false === $result ){
				throw new DATA_Exception(mysql_errno().'--'.mysql_error().'<pre>'.$query.'</pre>');
			}
		}
		catch(DATA_Exception $e){
			#$e->trace();
			$this->logger->log(LOG_NOTICE,__METHOD__, 'MySQLConnectionException on['.$this->DSN.'] FIRST ATTEMPT');
			#$this->connection = $this->connectionPool[$database]->connection;
			if(!isset($this->connection) || !is_resource($this->connection)){
				$this->verify_connection();
			}
			$result = @mysql_query($query, $this->connection);
			if(false === $result ){
				$dberror = @mysql_error($this->connection);
				if(false === $dberror){
					$message = 'FAILED CONECTION TO ['.$this->DSN.']';
					$result['EXCEPTION']["ID"] = 0;
					$result['EXCEPTION']["MSG"] = $message;
					// CONNECTION FAILED NOTIFY NAGIOS
					$this->logger->log(LOG_EMERG,__METHOD__, 'FATAL Exception '.$message.' ['.$query.'] TRACE['.$e->getTraceAsString().']');
				}else{
					$result['EXCEPTION']["ID"] = mysql_errno($this->connection);
					$result['EXCEPTION']["MSG"] = 'FAILED QUERY ON ['.$this->DSN.']['.$this->dbType.'] ERROR ['.$dberror.']['.$query.']';
					$this->logger->log(LOG_ALERT,__METHOD__, 'FAILED QUERY on['.$this->DSN.']['.$this->dbType.']['.$dberror.']['.$query.'] TRACE['.$e->getTraceAsString().']');
				}
			}
				#echo '!!!gettype(result)'.gettype($result).!is_array($result)."\n";
		}
		#echo __LINE__.'gettype(result='.$result.')'.gettype($returnArray).'::'.gettype($result)."\n";
		#echo LN.__METHOD__.__LINE__.'-------------------the result is['.$result.']['.gettype($result).']#['.mysql_affected_rows($this->connection).']-------------------'.'<br>';
		return $result;
	}
	
	
	/**
	* DESCRIPTOR: EXECUTE A SELECT
	* if $args["returnArray"] === true the function will return the result
	* as a PHP array
	*
	* @access public 
	* @param string $query 
	* @param mixed $args 
	* @return $result 
	*/
	public function retrieve($query, $args=false){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(query='.$query.' args='.print_r($args,true).')');
		#echo __METHOD__.__LINE__.'<br>'.'****************(query='.$query.' args='.var_export($args, true).')'.'<br>';
		#$connection = $this->connectionPool[$database]->connection;
		//$this->verify_connection($database);  CAUGHT IN raw()
		$result = $this->raw($query);
		#echo LN.__METHOD__.__LINE__.'<br>'.'-------------------the result is['.$result.']['.gettype($result).']-------------------'.'<br>';
		#echo __LINE__.'gettype(returnArray='.$returnArray.')gettype(result='.$result.')'.gettype($returnArray).'::'.gettype($result)."\n";
		#echo __METHOD__.'@'.__LINE__.'$result<pre>'.var_export($result, true).'</pre><br>';
		if(true === $args["returnArray"] && is_resource($result)){
			$result = $this->resultToAssoc($result, $query);
			#echo __LINE__.'gettype(result)'.gettype($result).!is_array($result)."\n";
		}
		#echo __METHOD__.'@'.__LINE__.'$result<pre>'.var_export($result, true).'</pre><br>';
		
		return $result;
	}
	/**
	* DESCRIPTOR: EXECUTE AN UPDATE
	* if $args["returnArray"] === true the function will return the number of 
	* affected rows as well as the "mysql_info" from the query
	* 
	* @access public 
	* @param string $query 
	* @param array $args 
	* @return $result 
	*/
	public function update($query, $args=false){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(query='.$query.' returnArray=['.$args["returnArray"].')');
		
		$result = $this->raw($query);
		#echo LN.__METHOD__.__LINE__.'-------------------the result is['.$result.']['.gettype($result).']#['.mysql_affected_rows($this->connection).']-------------------'.'<br>';
		#echo __METHOD__.'```````````````````````gettype(result)'.gettype($result).!is_array($result).'<br>';
		#echo __METHOD__.'```````````````````````gettype(this->connection)'.gettype($this->connection).!is_array($this->connection).'<br>';
		if(true === $args["returnArray"] && is_bool($result) && $result === true){
			$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($this->connection);
			$info_update = mysql_info($this->connection);
			$info_update = explode('  ',$info_update);
			$info_updated = array();
			foreach($info_update AS $key => $value){
				$update = explode(':',$value);
				$info_updated[trim($update[0])] = trim($update[1]);
			}
			$resultArray["INFO"] 			= $info_updated;
			return $resultArray;
		}
		
		return $result;
	}
	
	
	/**
	* DESCRIPTOR: EXECUTE AN INSERT
	* if $args["returnArray"] === true the function will return the "mysql_insert_id"
	* the number of "mysql_affected_rows" as well as the "mysql_info" from the query
	* 
	* @access public 
	* @param string $query 
	* @param array $args 
	* @return $result 
	*/
	public function create($query, $args = false){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(query='.$query.' args=['.json_encode($args).'])');
		
		$result = $this->raw($query);
		#echo LN.__METHOD__.__LINE__.'-------------------the result is['.$result.']['.gettype($result).']#['.mysql_affected_rows($this->connection).']-------------------'.'<br>';
		#echo __METHOD__.'```````````````````````gettype(result)'.gettype($result).!is_array($result).'<br>';
		#echo __METHOD__.'```````````````````````gettype(this->connection)'.gettype($this->connection).!is_array($this->connection).'<br>';
		if(true === $args["returnArray"] && is_bool($result) && $result === true){
			$resultArray["INSERT_ID"] 		= mysql_insert_id($this->connection); // most important do it first
			$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($this->connection);
			$info = mysql_info($this->connection);
			#echo __METHOD__ .'@'. __LINE__ .'info<pre>['.var_export($info,true).']</pre>'.PHP_EOL;
			if(false != $info){
				$info_update = explode('  ',$info);
				$info_updated = array();
				
				foreach($info_update AS $key => $value){
					$update = explode(':',$value);
					$info_updated[trim($update[0])] = trim($update[1]);
				}
				$info = $info_updated;
				
			}
			
			$resultArray["INFO"] 			= $info;
			return $resultArray;
		}
		
		return $result;
	}
	
	/**
	* DESCRIPTOR: EXECUTE A DELETE
	* if $args["returnArray"] === true the function will return the number of 
	* affected rows as well as the "mysql_info" from the query
	* 
	* @access public  
	* @param string $query 
	* @param array $args 
	* @return bool/array $result 
	*/
	public function delete($query, $args = false){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '(query='.$query.' returnArray=['.$args["returnArray"].'])');
		#echo 'DELETE $query['.$query.']'.'<br>';
		$result = $this->raw($query);
		#echo LN.__METHOD__.__LINE__.'-------------------the result is['.$result.']['.gettype($result).']#['.mysql_affected_rows($this->connection).']-------------------'.'<br>';
		#echo __METHOD__.'```````````````````````gettype(result)'.gettype($result).!is_array($result).'<br>';
		#echo __METHOD__.'```````````````````````gettype(this->connection)'.gettype($this->connection).!is_array($this->connection).'<br>';
		if(true === $args["returnArray"] && is_bool($result) && $result === true){
			$resultArray["AFFECTED_ROWS"] 	= mysql_affected_rows($this->connection); 
			$info_update = mysql_info($this->connection);
			$info_update = explode('  ',$info_update);
			$info_updated = array();
			foreach($info_update AS $key => $value){
				$update = explode(':',$value);
				$info_updated[trim($update[0])] = trim($update[1]);
			}
			$resultArray["INFO"] 			= $info_updated;
			return $resultArray;
		}
		
		return $result;
	}
	
	/**
	* DESCRIPTOR: __destruct
	* if $args["returnArray"] === true the function will return the number of 
	* affected rows as well as the "mysql_info" from the query
	* 
	* @param null 
	* @return null
	*/
	function __destruct(){
		#echo __METHOD__.__LINE__.'<br>';
		$this->logger->log(LOG_DEBUG,__METHOD__, '()');
		unset($this->logger); // NOT using global logger now
		return;
	}	
	
}
 

#echo __FILE__.'::'.__LINE__.'OUT'.'<br>';

?>