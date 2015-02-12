<?php
/**
 * Class
 * @author		Jason Medland<jason.medland@gmail.com>
 * @subpackage	webkinz 2.0 - data
 * 
 */
namespace JCORE\DATA\API\MySQL;

/**
 * Interface MYSQL_TABLE_META
 *
 * @package JCORE\DATA\API\MySQL
*/
 class MYSQL_TABLE_META{
	
	/**
	 * @access private 
	 * @var string $dbType
	 */
	private $dbType = 'MYSQL';
	
	/**
	 * @access public 
	 * @var string
	 */
	public $tableName = NULL;
	
	/**
	 * @access public 
	 * @var string
	 */
	public $DSN = NULL;
	
	/**
	 * @access protected 
	 * @var string
	 */
	public $tableProperties = NULL; //array();
	
	/**
	 * @access protected 
	 * @var string
	 */
	public $connectionObject = NULL; //array();
	
	
	/**
	 * Constructor
	 * 
	 * @return	NULL
	 */
	public function __construct(){
		#action
		/*
		GLOBAL $logCFG;
		$settings 		= $logCFG["DATA"];
		$this->logger	= new logInternal($settings);
		*/
		$this->logger	=& $GLOBAL['DATA_logger'];
		return;
	}
	/**
	* DESCRIPTOR: unsets internal properties
	* @param	NULL
	* @return NULL 
	*/
	public function flushData(){
		echo __METHOD__.'::'.__LINE__.'-------================()()()())()()())()()()()()()()()()()()()()()('.LN;
		$this->connectionObject	= NULL;
		$this->tableName 		= '';
		$this->DSN 				= '';
		#$this->setProperties 	= '';
		$this->tableProperties	= array();
		return;
	}
	
	/**
	* DESCRIPTOR: Get the "private" dbType
	* @param	NULL
	* @return string $dbType 
	*/
	public function getDbType(){
		return $this->dbType;
	}
	
	/**
	* DESCRIPTOR: IE: Stores meta data for table in a traversable form
	* @param	String $tableName
	* @param	String $tableName
	* @param	String $tableName
	* @return NULL 
	*/
	public function initialize($DSN, $tableName, $connectionObject=NULL){
		#echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.LN;
		#echo __METHOD__.'::'.__LINE__.'$tableName['.$tableName.']'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject['.gettype($connectionObject).']'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject<pre>'.var_export($connectionObject, true).'</pre>'.LN;
		#GLOBAL $dbInterface;
		#echo __FILE__.'::'.__LINE__.'$introspectionClass['.$connectionObject.']---------[$connectionObject]['.gettype($connectionObject).']<pre>'.var_export($connectionObject,true).'</pre>'.LN;
		/**
		* always pass the connection object 
		*/
		#echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.LN;
		if(!isset($connectionObject) || !is_object($connectionObject)){
			#echo __METHOD__.'::'.__LINE__.'FAIL NO CONN OBJECT $DSN['.$DSN.']['.$tableName.']'.gettype($connectionObject).'$data<pre>'.print_r($data, true).'</pre>'.LN;
			return FALSE;
		}
		#echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.LN;
		
		#$this->connectionObject = $connectionObject;
		
		unset($this->tableProperties);
		$this->tableProperties	= array();
		#$caller=__CLASS__.'->'.__FUNCTION__;
		#echo '['.__CLASS__.'->'.__FUNCTION__.'] DSN=['.$DSN.'] tableName=['.$tableName.']'."\n";
		echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.LN;
		$this->DSN = $DSN;
		$this->tableName = $tableName;
		echo __METHOD__.'::'.__LINE__.'$DSN['.$DSN.']'.LN;
		if(FALSE === $this->validateConnectionObject($connectionObject)){
			echo __METHOD__.'::'.__LINE__.'-----------------------------RESET $connectionObject['.gettype($connectionObject).']'.LN;
			$this->connectionObject = $connectionObject;
		}		
		#SQLResultToAssoc($result, $query)
		$query = 'SHOW COLUMNS FROM '.$this->tableName.';  ';
		echo __METHOD__.'::'.__LINE__.'$query['.query.']'.LN;
		$result = $this->connectionObject->SQL_select($query, $returnArray=false);///, $returnArray=true
		echo __METHOD__.'::'.__LINE__.'$result<pre>'.var_export($result, true).'</pre>'.LN;
		$result = $this->connectionObject->SQLResultToAssoc($result, $query);
		#echo __METHOD__.'::'.__LINE__.'$result<pre>'.var_export($result, true).'</pre>'.LN;
		#echo __METHOD__.'::'.__LINE__.'$data<pre>'.print_r($data, true).'</pre>'.LN;
		$this->setProperties($result);
		/*
		*/
		return;
	}
	/**
	* DESCRIPTOR: check if the DB connection is valid to what we want to do
	* @param mixed $data 
	* @return bool $valid 
	*/
	private function validateConnectionObject($connectionObject = NULL){
		echo __METHOD__.'::'.__LINE__.'$connectionObject['.gettype($connectionObject).']'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject<pre>'.var_export($connectionObject, true).'</pre>'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject<pre>'.var_export($this->connectionObject, true).'</pre>'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject<pre>'.print_r($connectionObject, true).'</pre>'.LN;
		#echo __METHOD__.'::'.__LINE__.'$connectionObject->DSN['.$connectionObject->DSN.']'.LN;
		#echo __METHOD__.'::'.__LINE__.'$this->connectionObject->DSN['.$this->connectionObject->DSN.']'.LN;
		
		$valid = FALSE;
		//
		if(isset($this->connectionObject)){
			if(isset($this->connectionObject->DSN) && $this->connectionObject->DSN == $connectionObject->DSN){
				return TRUE;
			}
		}		
		return $valid;
	}
	/**
	* DESCRIPTOR: IE: Stores meta resultArray for table in a traversable form
	* @param mixed $resultArray 
	* @return NULL 
	*/
	private function setProperties($resultArray = NULL){
		#echo '$resultArray['.__FUNCTION__.']<pre>'.print_r($resultArray, true).'</pre>';
		//echo __METHOD__.'::'.__LINE__.'$resultArray<pre>'.print_r($resultArray, true).'</pre>'.LN;
		if($resultArray == NULL){
			$this->logger->trace(LOG_WARNING, __METHOD__, '$resultArray == NULL');
			return;
		}
		foreach($resultArray AS $key => $value){
			$subject = $value["Type"];
			$pattern = '/ ^  (\w*)  \(    (\d*)    \)       /x';
			$matches = array();
			preg_match($pattern, $subject, $matches);
			$this->columnNames->$value["Field"]->index = $key;
			#echo '['.$value["Field"].']matches<pre>'.print_r($matches, true).'</pre>'.LN;
			if( strpos ( $value["Type"], 'enum') === false){
				if(count($matches) == 0){
					$this->tableProperties[$value["Field"]]["type"] 	= $value["Type"];
					#$this->tableProperties[$value["Field"]]["length"] 	= $matches[2];				
				}else{
					$this->tableProperties[$value["Field"]]["type"] 	= $matches[1];
					$this->tableProperties[$value["Field"]]["length"] 	= $matches[2];				
				}
				

			}else{
				$this->tableProperties[$value["Field"]]["type"] 	= 'enum';
				$this->tableProperties[$value["Field"]]["options"] 	= explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",stripslashes($value["Type"])));
			}
			
			$this->tableProperties[$value["Field"]]["default"] 		= $value["Default"];
			$this->tableProperties[$value["Field"]]["allowNull"] 	= $value["Null"];
			if($value["Extra"] == 'auto_increment'){
				$this->tableProperties[$value["Field"]]["autoIncrement"] 	= true;
			}
			if($value["Key"] == 'PRI'){
				$this->tableProperties[$value["Field"]]["key"] 	= 'primary';
			}
			
		}
		#$this->errors[] = '';
	}
	/**
	* DESCRIPTOR: IE: Stores meta data for table in a traversable form
	* @param mixed $data ARG NEEDED??
	* @return NULL 
	*/
	public function getTableProperties($data = NULL){
		echo __METHOD__.'::'.__LINE__.'$data<pre>'.print_r($data, true).'</pre>'.LN;
		if(isset($this->tableProperties) && is_array($this->tableProperties) && count($this->tableProperties) > 0){
			return $this->tableProperties;
		}
		return NULL;
	}
	/**
	* DESCRIPTOR: gets the primary key from the data set in "tableProperties"
	* @param mixed $data 
	* @return NULL 
	*/
	public function getPrimaryKeyField($data = NULL){
		echo __METHOD__.'::'.__LINE__.'$data<pre>'.print_r($data, true).'</pre>'.LN;
		if($data == NULL || !is_array($data)){
			$this->logger->trace(LOG_WARNING, __METHOD__, '$data == '.gettype($data).'');
			return;
		}
		foreach($data AS $Key => $value){
			#echo '$Key=['.$Key.']<pre>'.var_export($value,true).'</pre>';
			//autoIncrement' => true,
			//key' => 'primary'
			if( isset($value["key"]) && $value["key"] == 'primary'){
				return $Key;
			}elseif(isset($value["autoIncrement"]) && $value["key"] == 'primary'){
				return $Key;
			}else{
				return;
			}
		}
	}

	//----------------------------------------------------
	
	//----------------------------------------------------
}
 

?>