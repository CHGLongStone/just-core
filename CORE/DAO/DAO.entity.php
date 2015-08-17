<?php
/**
 * [SUMMARY]
 * DATA ACCESS OBJECT
 * This is meant to be a simple, aggregate DAO, it will manage "parent/child" 
 * relationships with tables. it is meant as a simple scaffold to extend other 
 * more specific implementations. if you need something more complex it is expected
 * that the object could be "decorated" with frther instances of itself to represent
 * a "tree" structure
 * 
 * The DAO is modeled on two concepts "Entities", single row records from the DB;
 * and "Collections", multiple Entities/rows whose foreign key is the primary key of the 
 * "foundation" entity/row. The "foundation" is the single entity record at the 
 * base of the instantiated object. 
 * the object provides some simple default "getters" & "setters". The "setter" 
 * methods ONLY update the object itself. The object tracks any changes to itself 
 * (only "current state", it does not track state changes) and will save its 
 * state on decomposition of the object. The objects state can optionally be saved 
 * to the database at any time
 * 
 * ** conventions used in docs:
 * 		- pk/PK "primary key"
 * 		- fkFK "foreign key"
 * [INSTANTIATION]
 * The object is instantiated by an array or NULL
 * The "config" array must use the following keys:
 * $config["database"]		// the DSN (Data Source Name) i.e. "AUTH"
 * $config["table"]			// the table name i.e. globalUser
 * $config["pk_field"]		// the name of the primary key field
 * $config["pk"]			// the primary key of the record
 *  
 * A correctly instantiated object will be populated by: 
 *(ON $config["database"] 
 * "SELECT * 
 * 		FROM $config["table"] 
 * 		WHERE  $config["pk_field"] = $config["pk"]  "
 *)
 * 
 * [INSTANTIATION ERROR]
 * The $OBJECT->$initialized member is the quickest way to verify that your object 
 * instantiated with a "valid record". if the object does not instatiate with a valid 
 * database record the value of $OBJECT->$initialized will remain FALSE
 * If there is an error on instantiation the exception will be returned as the result here: 
 * $OBJECT->tables[$tableName]["values"]["EXCEPTION"]
 * 
 * 
 * ["stub/NULL" INSTANTIATION]
 * if the object is instantiated with NULL it will then create 
 * an emty "stub" object that can be "initialized" with the method:
 * 
 * $OBJECT->initialize($database, $tableName, $foundation=false)
 * 		$database			// the DSN (Data Source Name) i.e. "AUTH"
 * 		$tableName			// the table name i.e. globalUser
 * 
 * this will populate the $OBJECT->tables[$tableName]["values"] array
 * by using the table definition returned by "SHOW COLUMNS FROM $tableName", 
 * (i.e. this object will ALWAYS return a full representation of the Entity record)
 * any values defined as "NOT NULL" will be populated with the default value 
 * defined in the data base, any values allowing NULL will be populated with NULL.
 * 
 * !!! KNOW YOUR DATA MODEL!!! if the default value is a MySQL function it may be returned as a string (un tested)
 * 
 * The primary key of the new value will be "0", it will be updated to the 
 * value of mysql_insert_id() when the $OBJECT->save() method is called directly 
 * or by destruction of the object.
 *
 * [AGGREGATING THE OBJECT]
 * The oject can be easily "extended" by using the "join" methods:
 * 		- $OBJECT->joinRecord($database, $tableName, $pk_field, $fk_field, $fk)
 * 		- $OBJECT->joinCollection($database, $joinTable, $pk_field, $fk_field, $fk)
 * In both cases you MUST specify the name of the $fk_field on the table to be joined
 * 
 * A correctly "child member" will be populated by: 
 *(ON $database 
 * "SELECT * 
 * 		FROM $joinTable 
 * 		WHERE  $fk_field = $fk/$OBJECT->root_pk  "
 *)
 * The syntax for accessing Entity/Collection "child members" differs see [DEFAULT GETTERS & SETTERS] below
 * 
 * [EXTENDTING AGGREGATION]
 * initializeJoinRecord 
 * Besides being able to instatiate a "stub" object of the "foundation" Entity/row
 * the object can also extent "stubs" of "child members". a stub of an Entity can be extended by:
 * 		$OBJECT->initializeChildRecord($database, $tableName, $pk_field, $fk_field)
 * 		- ALL arguments MUST be specified
 * 		- the "$fk" value of the record will be populated with $OBJECT->root_fk
 * 
 * Like the "initialize" method on the "foundation" member it will create a defalt defintion based 
 * on the results of "SHOW COLUMNS FROM $tableName". The Entity is created using the same rules
 * applied to the ["stub/NULL" INSTANTIATION]. The entity definition can then be modified using 
 * the "setter $OBJECT->set(table, field, value). on $OBJECT->save() the object will update 
 * the primary key references to the result returned by mysql_insert_id().
 * 
 * The object can also extend "stubs" of collection records with the method:
 * 		$OBJECT->initializeCollectionRecord($database, $tableName, $pk_field, $fk_field)
 * 		- ALL arguments MUST be specified
 * 		- the "$fk" value of the record will be populated with $OBJECT->root_fk
 * 
 * The entity definition can then be modified using the "setter $OBJECT->set(table, field, value, pk). 
 * On $OBJECT->save() the object will update the primary key references of the Collection Entity/row.
 * 
 * 
 * [KEY PROPERTIES]
 * $OBJECT->$initialized	// set to bool TRUE when the object is initialized from a valid database record
 * $OBJECT->root_pk			// quick reference to the primary key of the "foundation" record
 * $OBJECT->tables			// an array of member values
 * 		- uses the syntax:
			Entities 	- $OBJECT->tables[$tableName]["values"][$value]
			Collections	- $OBJECT->tables[$tableName]["values"][$primaryKey][$value]
 * $OBJECT->modifiedColumns	// keeps track of what members have changes since instantiation
 * 
 * 
 * [DEFAULT GETTERS & SETTERS]
 * $OBJECT->get(table, field);				//entity
 * $OBJECT->set(table, field, value); 		//entity
 * ALL Entity values can also be access through the public member:
 * 		$OBJECT->tables[$tableName]["values"][$columnName]
 *  
 * $OBJECT->get(table, field, pk);			// collection
 * OBJECT->set(table, field, value, pk);	//collection
 * ALL Collection values can also be access through the public member:
 * 		$OBJECT->tables[$tableName]["values"][$PK][$columnName]
 * 
 * [SAVING THE OBJECT STATE]
 * The $OBJECT->save() method will save any changes to the object back to the database 
 * The $OBJECT->save() can be called on specific Entities/Collections by passing the table name
 * $OBJECT->save($tableName)
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\DAO
 * @subpackage	JCORE\DAO
 */
namespace JCORE\DAO;
use JCORE\DATA\MYSQL\MySQL_TABLE_META as MySQL_TABLE_META;
/**
 * Class DAO
 *
 * @package JCORE\DAO
*/
class DAO{

	/**
	 * @access public 
	 * @var int
	 */
	public $root_pk = 0;
	/**
	 * @access protected 
	 * @var string
	 */
	#public $baseTable = '';
	/**
	 * @access private 
	 * @var string
	 */
	/**
	 * @access public 
	 * @var bool
	 */
	public $initialized = false;
	/**
	 * was the object instantiated with valid parmaters?
	 * if not call the 
	 * @access public 
	 * @var array
	 */
	public $tables = array();
	
	/**
	 * collects a list of queries used in the save() method
	 * @access public 
	 * @var array
	 */
	protected $queries = array();
	
	#public $collection = array();
	/**
	 * tracks the changes internal to the object, used in the save() method
	 * 
	 * @access public 
	 * @var array
	 */
	public $modifiedColumns = array();
	
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 * array config consists of 
	 * string 	DSN
	 * string 	table
	 * string 	pk_field
	 * int 	pk
	 * 
	 * @param	mixed 	config
	 */
	public function __construct($config = null){
		#action
		//$DSN, $table, $pk_field, $pk 
		//echo 'result<pre>'.print_r($result, true).'</pre>'.PHP_EOL;
		#$config["DSN"];
		#$config["table"];
		#$config["pk_field"]; 
		#$config["pk"];
		$this->db = $GLOBALS["DATA_API"];
		$GLOBALS['LOG_DATA']->log(LOG_DEBUG,__METHOD__, '(<pre>'.print_r($config, true).'</pre>)');
		
		if(is_array($config)){
			#echo 'config<pre>'.print_r($config, true).'</pre>'.PHP_EOL;
			if(
				isset($config["DSN"])
				&&
				isset($config["DSN"])
				&&
				isset($config["table"])
				&&
				isset($config["pk_field"])
				&&
				isset($config["pk"])
				&& 
				0 != $config["pk"] 
			){
				#echo 'baseObjDDDDD<pre>'.print_r($else, true).'</pre>'.PHP_EOL;
				#GLOBAL $db;
				$this->config = $config;
				$this->tables[$config["table"]] 				= array();
				$this->tables[$config["table"]]['DSN'] 			= $config["DSN"];
				$this->tables[$config["table"]]['foundation'] 	= true;
				$this->tables[$config["table"]]['pk_field'] 	= $config["pk_field"];
				$this->tables[$config["table"]]['pk'] 			= $config["pk"];
				$this->tables[$config["table"]]['values'] 		= array();
				
				$query = 'SELECT * FROM '.$config["table"].' WHERE '.$config["pk_field"].' = '.$config["pk"].' ';
				#$result = $db->SQL_select($config["DSN"], $query, $returnArray=true);
				$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
				#$result = $db->SQLResultToAssoc($result);
				
				$this->tables[$config["table"]]['values'] = $result[0];
				#echo 'count($result[0])<pre>'.print_r(count($result[0]), true).'</pre>'.PHP_EOL;
				if(count($result[0]) >= 1){
					$this->initialized = true;
					$this->root_pk = $config["pk"];
				}elseif($result["EXCEPTION"]){
					#echo '$result<pre>'.print_r($result, true).'</pre>'.PHP_EOL;
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'ERROR MySQL error['.$result["EXCEPTION"]["ID"].']error:'.$result["EXCEPTION"]["MSG"].''); // ID MSG $query 
					$this->tables[$config["table"]]['values'] = $result["EXCEPTION"];
				}else{
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'result['.print_r($result, true).']'); // ID MSG $query 
				}
						
			}else{
				#echo 'else<pre>'.print_r($else, true).'</pre>'.PHP_EOL;
				$this->config = $config;
				
				if(isset($config["table"])){
					$this->tables[$config["table"]] 				= array();
					if(isset($config["DSN"])){
						$this->tables[$config["table"]]['DSN'] 			= $config["DSN"];
					}
					if(isset($config["pk_field"])){
						$this->tables[$config["table"]]['foundation'] 	= true;
						$this->tables[$config["table"]]['pk_field'] 	= $config["pk_field"];
						$this->tables[$config["table"]]['pk'] 			= 0;
					}
						
				}
			}	
		}

		$this->MYSQL_CONSTANTS = $GLOBALS["CONFIG_MANAGER"]->getSetting('DAO',$config["DSN"],'MYSQL_CONSTANTS');//$this->config["DSN"]
		#$this->initialized;
		#echo '$this->tables<pre>'.print_r($this->tables, true).'</pre>';
		return;
		
	}
	
	/**
	* DESCRIPTOR: construct from table def (information_schema)
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL 
	*/
	public function initialize($DSN, $tableName, $foundation=false){ //
		
		#$this->initialized = true;
		$this->tables[$tableName] = array();
		$this->tables[$tableName]['DSN'] 	= $DSN;
		if(isset($foundation) && $foundation===true){
			$this->tables[$tableName]['foundation'] = TRUE; //$foundation;
		}
		$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($DSN, $tableName, false);
		$this->tables[$tableName]['values']		= $values;
		$this->initialized = true;
		return;
	}
	
	/**
	* DESCRIPTOR: construct from table def (information_schema)
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL 
	*/
	public function initializeBySearch($args){ //, $tableName, $foundation=false
		#echo __METHOD__.'@'.__LINE__.'result<pre>'.print_r($args, true).'</pre>'.PHP_EOL;

		#$this->initialized = true;
		$this->tables[$args["table"]] = array();
		$this->tables[$args["table"]]['DSN'] 	= $args["DSN"];
		if(isset($args["foundation"]) && $args["foundation"]===true){
			$this->tables[$args["table"]]['foundation'] = TRUE; //$foundation;
		}
		$this->tables[$args["table"]]['pk'] 		= 0;
		#$values = $this->initializeFromSchema($DSN, $tableName, false);
		/**
		$this->tables[$args["table"]]['values']		= $values;
		$this->initialized = true;
		return;
		*/
		
		#$result = $GLOBALS["DATA_API"]->introspectTable($args["DSN"], $args["table"]);
		#$values = $this->initializeFromSchema($args["DSN"], $args["table"],$set_fk=false);
		#echo __METHOD__.'@'.__LINE__.'result<pre>'.print_r($result, true).'</pre>'.PHP_EOL;
		if(is_array($args)){
			#echo 'args<pre>'.print_r($args, true).'</pre>'.PHP_EOL;
			if(
				isset($args["DSN"])
				&&
				isset($args["table"])
				&&
				isset($args["search"]) // could be array or string
				&&
				is_array($args["search"])// could be array search_field:search_value
			){
				#echo 'baseObjDDDDD<pre>'.print_r($else, true).'</pre>'.PHP_EOL;
				#GLOBAL $db;
				$this->config = $args;
				$this->initializeFromSchema($args["DSN"], $args["table"], false);
				
				$this->modifiedColumns[$args["table"]] = array();
				
				
				$this->tables[$args["table"]] 				= array();
				$this->tables[$args["table"]]['DSN'] 		= $args["DSN"];
				#$this->tables[$args["table"]]['foundation'] = true;
				#$this->tables[$args["table"]]['pk_field'] 	= $args["pk_field"];
				#$this->tables[$args["table"]]['pk'] 		= $args["pk"];
				$this->tables[$args["table"]]['values'] 	= array();
				$matchType = ' OR';
				if(isset($args["SEARCH_TYPE"]) && $args["SEARCH_TYPE"] == 'MATCH_ALL'){
					$matchType = ' AND';
				}
				
				$query = 'SELECT * FROM '.$args["table"].' WHERE  ';
				$whereClause = '';
				foreach($args["search"] AS $key => $value){
					if('' != $whereClause){
						$whereClause .= '	'.$matchType.PHP_EOL;
					}
					$whereClause .= ' '.$key.' = "'.$value.'" '.PHP_EOL;
					#echo __METHOD__.'@'.__LINE__.$whereClause.PHP_EOL;
				}
				$query = $query.$whereClause;
				#echo __METHOD__.'@'.__LINE__.'query<pre>'.print_r($query, true).'</pre>'.PHP_EOL;
				#$result = $db->SQL_select($config["DSN"], $query, $returnArray=true);
				$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $r_args=array('returnArray' => true));
				#$result = $db->SQLResultToAssoc($result);
				#echo __METHOD__.'@'.__LINE__.'result[0]<pre>'.print_r($result[0], true).'</pre>'.PHP_EOL;
				#echo __METHOD__.'@'.__LINE__.'this->tables<pre>'.print_r($this->tables, true).'</pre>'.PHP_EOL;
				if(!isset($result[0])){
					#echo __METHOD__.'@'.__LINE__.'DO THE FUCKING SCEAMA<pre>'.print_r($result, true).'</pre>'.PHP_EOL;
					
					$schema = $this->initializeFromSchema($args["DSN"], $args["table"], false);
					$this->tables[$args["table"]]["values"] = $schema;
					#echo __METHOD__.'@'.__LINE__.'this->tables['.$args["table"].']<pre>'.print_r($this->tables[$args["table"]], true).'</pre>'.PHP_EOL;
					#echo __METHOD__.'@'.__LINE__.'schema<pre>'.print_r($schema, true).'</pre>'.PHP_EOL;
					return;
				}
				$this->tables[$args["table"]]['values'] = $result[0];
				#$this->tables[$args["table"]]['values'] 	= array();
				#echo __METHOD__.'@'.__LINE__.'this->tables ['.$args["table"].']<pre>'.print_r($this->tables, true).'</pre>'.PHP_EOL;
				#$this->tables[$table]['values']
				#echo 'count($result[0])<pre>'.print_r(count($result[0]), true).'</pre>'.PHP_EOL;
				if(count($result[0]) >= 1){
					$this->initialized = true;
					#echo '$this<pre>'.print_r($this, true).'</pre>'.PHP_EOL;
					$this->root_pk = $result[0][$args["pk_field"]];
					$this->tables[$args["table"]]['pk'] 		= $this->root_pk;

				}elseif($result["EXCEPTION"]){
					#echo '$result<pre>'.print_r($result, true).'</pre>'.PHP_EOL;
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'ERROR MySQL error['.$result["EXCEPTION"]["ID"].']error:'.$result["EXCEPTION"]["MSG"].''); // ID MSG $query 
					$this->tables[$args["table"]]['values'] = $result["EXCEPTION"];
					return $result["EXCEPTION"];
				}else{
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'result['.print_r($result, true).']'); // ID MSG $query 
				}
						
			}else{
				#echo 'else<pre>'.print_r($else, true).'</pre>'.PHP_EOL;
				$this->initializeFromSchema($args["DSN"], $args["table"], $set_fk=false);
			}	
		}
		return;

	}
	
	
	
	/**
	* DESCRIPTOR: construct from table def
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeChildRecord($DSN, $tableName, $pk_field, $fk_field){
		$this->tables[$tableName] = array();
		$this->tables[$tableName]['DSN'] 	= $DSN;
		
		$this->tables[$tableName]['pk_field'] = $pk_field;
		$this->tables[$tableName]['fk_field'] = $fk_field;
		
		$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($DSN, $tableName);
		$this->tables[$tableName]['values']		= $values;
		return;
	}
	
	/**
	* DESCRIPTOR: construct from table def
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeJoinRecord($DSN, $tableName, $args=null){
		#echo __METHOD__.'@'.__LINE__.'$DSN['.$DSN.'] $tableName['.$tableName.']$args<pre>['.var_export($args, true).']</pre>'.'<br>'.PHP_EOL; 
		if(isset($args["JOIN_ON"]) && isset($args["JOIN_ON"]["table"]) ){
			$init = array(
				"DSN" => $DSN,
				"tableName" => $args["JOIN_ON"]["table"],
				"pk_field" => $args["JOIN_ON"]["pk_field"] 
			);
			$classInstance = get_class();
			#echo __METHOD__.'@'.__LINE__.'$classInstance<pre>['.var_export($classInstance, true).'] get_called_class() ['.get_called_class().']</pre>'.'<br>'.PHP_EOL; 
			#$instanceName = $args["JOIN_ON"]["table"];
			$instanceName = $tableName;
			$this->$instanceName = new $classInstance();
			$this->$instanceName->initialize($DSN, $args["JOIN_ON"]["table"], true); //($DSN, $tableName, $foundation=false){ //
			$this->$instanceName->initializeChildRecord($DSN, $tableName, $args["pk_field"], $args["fk_field"]);
			
			
			
			/*
			echo __METHOD__.'@'.__LINE__.'$this->'.$instanceName.'<pre>['.var_export(get_class_methods($this->$instanceName), true).'] </pre>'.'<br>'.PHP_EOL; 
			echo __METHOD__.'@'.__LINE__.'$this->'.$instanceName.'->tables<pre>['.var_export($this->$instanceName->tables, true).'] </pre>'.'<br>'.PHP_EOL; 
			#echo __METHOD__.'@'.__LINE__.'$this->'.$instanceName.'<pre>['.var_export($this->$instanceName, true).'] </pre>'.'<br>'.PHP_EOL; 
			$this->$args["JOIN_ON"]["table"]->init($init);
			*/
		}
		
		/*
		$this->tables[$tableName] = array();
		$this->tables[$tableName]['DSN'] 	= $DSN;
		
		$this->tables[$tableName]['pk_field'] = $args["pk_field"];


		$this->tables[$tableName]['pk'] 		= 0;
		$setFk = false;
		if(isset($args["fk_field"])){
			$this->tables[$tableName]['fk_field'] = $args["fk_field"];
			$setFk = true;
		}
		$values = $this->initializeFromSchema($DSN, $tableName, $setFk);
		$this->tables[$tableName]['values']		= $values;
		
		if(isset($args["JOIN_ON"])){
			$tableName = $args["JOIN_ON"]["table"];
			$this->tables[$tableName] = array();
			$this->tables[$tableName]['DSN'] 	= $DSN;
			
			$this->tables[$tableName]['pk_field'] = $args["pk_field"];
			$this->tables[$tableName]['pk'] 		= 0;
			$values = $this->initializeFromSchema($DSN, $tableName,false);
			$this->tables[$tableName]['values']		= $values;
		}
		
		$this->tables[$tableName]['fk_field'] = $fk_field;
		
		*/
		return;
	}
	
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeCollectionRecord($DSN, $tableName, $pk_field, $fk_field){
		if(!isset($this->tables[$tableName])){
			$this->tables[$tableName] = array();
		}
		
		$this->tables[$tableName]['DSN'] 	= $DSN;
		
		$this->tables[$tableName]['pk_field'] = $pk_field;
		$this->tables[$tableName]['fk_field'] = $fk_field;
		
		#$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($DSN, $tableName);
		$this->tables[$tableName]['values'][0]		= $values;
		$this->tables[$tableName]['values'][0][$this->tables[$tableName]['pk_field']]		= 0;
		return;
	}
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeSchema($DSN, $tableName){	
		if(!isset($this->db->dataSchema[$DSN]["table"][$tableName])){
			$schema = $GLOBALS["DATA_API"]->introspectTable($DSN, $tableName);
		}
		/**
		echo __METHOD__.'::'.__LINE__.'DSN['.$DSN.'] tableName['.$tableName.']<pre>'.print_r($tableName, true).'</pre>';
		echo __METHOD__.'::'.__LINE__.'DSN['.$DSN.'] tableName['.$tableName.']<pre>'.print_r($schema, true).'</pre>';
		$schema = $this->db->dataSchema[$DSN]["table"][$tableName];
		*/
		$schema = $this->db->dataSchema[$DSN]["table"][$tableName];
		return $schema;
	}
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	DSN
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeFromSchema($DSN, $tableName, $set_fk=true){
		#GLOBAL $db;
		/**
		go get the table def
		$db->introspectTable($DSN, $tableName);
		echo __METHOD__.'::'.__LINE__.'tableName<pre>'.print_r($tableName, true).'</pre>';

		$result = $GLOBALS["DATA_API"]->introspectTable($DSN, $tableName);
		*/ 
		if(
			(
				!isset($this->tables[$tableName])
				||
				0 == count($this->tables[$tableName])
			)
			||
			(
				!isset($this->db->dataSchema[$DSN]["table"][$tableName])
				||
				0 == count($this->db->dataSchema[$DSN]["table"][$tableName])
			)
		){
			$result = $this->initializeSchema($DSN, $tableName);
			#echo __METHOD__.'::'.__LINE__.'result<pre>'.print_r($result, true).'</pre>';
		}else{
			$result = $this->db->dataSchema[$DSN]["table"][$tableName];
		}
		/*
		echo __METHOD__.'::'.__LINE__.'result<pre>'.print_r($result, true).'</pre>';
		echo __METHOD__.'::'.__LINE__.'$this->tables<pre>'.print_r($this->tables, true).'</pre>';
		*/
		$values	= array();
		//modifiedColumns
		#$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		#foreach($db->tableDefinitions[$DSN][$tableName] AS $key => $value){
		#echo __METHOD__.'::'.__LINE__.'$GLOBALS["DATA_API"]->dataSchema<pre>'.print_r($GLOBALS["DATA_API"]->dataSchema, true).'</pre>';
		#foreach($GLOBALS["DATA_API"]->dataSchema[$DSN][$tableName] AS $key => $value){
		#foreach($GLOBALS["DATA_API"]->tableDefinitions[$DSN][$tableName] AS $key => $value){
		foreach($result AS $key => $value){
			#echo ' key='.$key.' :: value=<pre>'.var_export($value,true).'</pre><br>';
			
			if(
				(isset($value["allowNull"]) && $value["allowNull"] == 'NO')
				||
				(isset($value["default"]))
			){
				$values[$key] = $value["default"];
				
				
			}else{
				$values[$key] = NULL;

			}
			
			
			if(isset($value["key"]["keytype"]) && $value["key"]["keytype"] == 'PRI'){
				#echo 'PK['.$key.']'.PHP_EOL;
				$this->tables[$tableName]['pk_field'] 	= $key;
				if(!isset($this->tables[$tableName]['pk'])){
					$this->tables[$tableName]['pk'] = 0;
				}
				$values[$key] = $this->tables[$tableName]['pk'];
			}
			if(true === $set_fk){
				#if(isset($value["key"]["keytype"]) && $value["key"]["keytype"] == 'MUL'){} //needs a hook here for sub entities
				if($key == $this->tables[$tableName]['fk_field']){
					$values[$key] = $this->root_pk;
				}
			}
			$this->modifiedColumns[$tableName][$key] = $values[$key];
		}	
		return $values;
	}	
	/**
	* DESCRIPTOR: joins a single record from anothe DB/Table
	* @param	string 	DSN
	* @param	string 	baseTable
	* @return	string 	pk_field
	* @return	string 	pk_field
	* @return	int 	fk
	* @return NULL 
	*/
	public function joinRecord($DSN, $tableName, $pk_field, $fk_field, $fk){
		GLOBAL $db;
		#$caller=__CLASS__.'->'.__FUNCTION__;
		$this->tables[$tableName] 				= array();
		$this->tables[$tableName]['DSN'] 		= $DSN;
		$this->tables[$tableName]['pk_field'] 	= $pk_field;
		
		$this->tables[$tableName]['fk_field'] 	= $fk_field;
		$this->tables[$tableName]['fk'] 		= $fk;
		
		//$this->tables[$tableName]['tableDef'] = $db->introspectTable($DSN, $tableName);
		
		$query = 'SELECT * FROM '.$tableName.' WHERE '.$fk_field.' = '.$fk.' ';
		#$result = $db->SQL_select($DSN, $query, $returnArray=true);
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$this->tables[$tableName]['pk'] 	= $result[0][$pk_field];
		$this->tables[$tableName]['values'] 	= array();
		$this->tables[$tableName]['values'] = $result[0];
		
		return;
	}
	/**
	* DESCRIPTOR: joins a single record from anothe DB/Table
	* @param	string 	DSN
	* @param	string 	baseTable
	* @return	string 	pk_field
	* @return	string 	pk_field
	* @return	int 	fk
	* @return NULL 
	*/
	public function joinCollection($DSN, $joinTable, $pk_field, $fk_field, $fk){
		GLOBAL $db;
		
		$this->tables[$joinTable] 				= array();
		$this->tables[$joinTable]['DSN'] 		= $DSN;
		$this->tables[$joinTable]['pk_field'] 	= $pk_field;
		$this->tables[$joinTable]['fk_field'] 	= $fk_field;
		$this->tables[$joinTable]['fk'] 		= $fk;
		
		$query = 'SELECT * FROM '.$joinTable.' WHERE '.$fk_field.' = '.$fk.' ';
		#$result = $db->SQL_select($database, $query, $returnArray=true);
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		#echo '$result<pre>'.var_export($result,true).'</pre>'."\n";
		foreach($result AS $key => $value){
			$this->tables[$joinTable]['values'][$value[$pk_field]] 	= $value;
		}
		
		return;
	}
	/**
	* DESCRIPTOR: Dynamic GETTER SETTER 
	* SIGNATURE
	* $userObj->get(table, field); entity table
	* $userObj->get(table, field, pk); collection table
	* 
	*$userObj->set(table, field, value); base table
	* $userObj->set(table, field, value, pk); entity table
	* @param	string 	method
	* @param	array 	args
	* @return value/true/false 
	*/
	public function __call($method, $args ){
		$table 	= $args[0];
		$column = $args[1];

		#	$baseObj->get_some_data('XXX');
		switch($method){
			case"get":
				if(isset($args[2]) && $args[2] != ''){
					$pk 	= $args[2];
					if(true == is_array($this->tables[$table]['values'][$pk])){
						if( array_key_exists( $column, $this->tables[$table]['values'][$pk] ) ){
							return $this->tables[$table]['values'][$pk][$column];					
						}
					}
				}elseif(
					isset($this->tables[$table])
					&&
					true == is_array($this->tables[$table]['values']) 
					&&
					is_array($this->tables[$table]['values'])
					
				
				){
					if(array_key_exists( $column, $this->tables[$table]['values'])){
						return $this->tables[$table]['values'][$column];
					}
				}
				return 'NO SUCH VALUE'; //false
				break;
			case"set":
				/*
				echo __METHOD__.'@'.__LINE__.'$method<pre>'.var_export($args, true).'</pre><br>';
				echo __METHOD__.'@'.__LINE__.'$table['.$table.']$column['.$column.']<pre>'.var_export($this->tables, true).'</pre><br>';
				*/
				if(isset($args[3]) && $args[3] != ''){
					$pk 	= $args[3];
				}
				$value 	= $args[2];
				if((isset($pk) && $pk != NULL) && true == is_array($this->tables[$table]['values'][$pk])){
					if(is_array($this->tables[$table]['values'][$pk]) && array_key_exists( $column, $this->tables[$table]['values'][$pk] ) ){
						$this->tables[$table]['values'][$pk][$column] = $value;
						// we want to track which fields we've modified for commits
						
						$this->modifiedColumns[$table][$pk][$column] = $value;
						return true;
					}
				}elseif(true == is_array($this->tables[$table]['values']) ){
					
					if(array_key_exists( $column, $this->tables[$table]['values'])){
						#echo __METHOD__.'@'.__LINE__.'$method<pre>'.var_export($args, true).'</pre><br>';
						$this->tables[$table]['values'][$column] = $value;
						// we want to track which fields we've modified for commits
						$this->modifiedColumns[$table][$column] = $value;
						return true;
					}
				}
				return 'NO SUCH VALUE'; //false;
				break;
				/*
			case"aaaaaaaa":
				#action some thing else here?
				break;
			case"bbbbbbbbb":
				#action some thing else here?
				break;
				*/
			default:
				echo 'default'.__METHOD__.' CALLED ['.$method.'] WITH <pre>'.var_export($args,true).'</pre>'.PHP_EOL;
				break;
		}
		return false;
	}
	/**
	* DESCRIPTOR: STORES CHANGES TO THE DAO TO THE DB(s)
	* @param	string 	table
	* @return outputErrors 
	*/
	public function save($table=null){
		if(count($this->modifiedColumns) >= 1){
			foreach($this->modifiedColumns AS $key => $value){
				#echo __METHOD__.'@'.__LINE__.'$key=['.$key.']<pre>'.var_export($value,true).'</pre>'."\n";
				if(is_array($value)){
					#echo '$value<pre>'.print_r($value, true).'</pre>'.PHP_EOL;
					$this->generateQueries($key, $value, $table);
				}
			}
		}
		#echo __METHOD__.'@'.__LINE__.'<pre>'.print_r($GLOBALS, true).'</pre>'.PHP_EOL;
		#echo '-----------------------------------------------------'.PHP_EOL;
		#echo __METHOD__.'@'.__LINE__.'<pre>'.print_r($this->tables[$key], true).'</pre>'.PHP_EOL;
		#echo __METHOD__.'@'.__LINE__.'$this->modifiedColumns<pre>'.print_r($this->modifiedColumns, true).'</pre>'.PHP_EOL;
		#echo __METHOD__.'@'.__LINE__.'$this->queries<pre>'.print_r($this->queries, true).'</pre>'.PHP_EOL;
		if(isset($this->queries) && count($this->queries) >= 1){
			#GLOBAL $db;
			#echo __METHOD__.'@'.__LINE__.'$this->tables['.$key.']<pre>'.print_r($this->tables[$key], true).'</pre>'.PHP_EOL;
			foreach($this->queries AS $key => $value){
				#echo __METHOD__.'@'.__LINE__.'$key=['.$key.']<pre>'.var_export($value,true).'</pre>'.PHP_EOL;
				foreach($value AS $key2 => $value2){
					#echo '$key2=['.$key2.']<pre>'.var_export($value2,true).'</pre>'.PHP_EOL;
					
					if($value2["queryType"] == 'INSERT'){
						#$result[] = $GLOBALS["DATA_API"]->insert($this->tables[$key]['DSN'], $value2["query"], $returnArray=true);
						if(
							isset($this->tables[$key]['foundation']) 
							&& 
							$this->tables[$key]['foundation'] === true
						){
							#echo '$result<pre>'.var_export($result,true).'</pre>'.PHP_EOL;
							$result[] = $GLOBALS["DATA_API"]->create($this->tables[$key]['DSN'], $value2["query"],  $args=array('returnArray' => true));
							if(
								isset($result[0]["INSERT_ID"]) 
								&& 
								is_int($result[0]["INSERT_ID"])
							){
								#echo '$result[0]["INSERT_ID"]<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.PHP_EOL;
								$this->root_pk = $result[0]["INSERT_ID"];
								$this->tables[$key]['pk'] = $this->root_pk;
								$this->tables[$key]['values'][$this->tables[$key]['pk_field']] = $this->root_pk;
							}
						}else{
							//replace
							
							#$GLOBALS["DATA_API"]->
							#$bodytag = str_replace('$this->root_pk', $this->root_pk, $value2["query"]);
							#$result = $GLOBALS["DATA_API"]->retrieve($DSN, $query, $args=array('returnArray' => true));
							#echo __METHOD__.'@'.__LINE__.'<pre>'.var_export($this,true).'</pre>'.PHP_EOL;
							#echo __METHOD__.'@'.__LINE__.'DSN['.$this->tables[$key]['DSN'].']query['.$value2["query"].'] <pre>'.var_export($this,true).'</pre>'.PHP_EOL;
							
							$result[] = $GLOBALS["DATA_API"]->create($this->tables[$key]['DSN'], $value2["query"], $args=array('returnArray' => true));
							if(
								isset($result[0]["INSERT_ID"]) 
								&& 
								is_int($result[0]["INSERT_ID"])
							){
								
								#echo __METHOD__.'@'.__LINE__.'$this->tables['.$key.']["values"][0]['.$this->tables[$key]["pk_field"].']===['.print_r($this->tables[$key]['values'][0][$this->tables[$key]['pk_field']], true).']'.PHP_EOL;
								if(isset($this->tables[$key]['values'][0][$this->tables[$key]['pk_field']])){
									// HANDLE A COLLECTION
									#echo '$result[0]["INSERT_ID"]COLLECTION<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.PHP_EOL;
									$this->tables[$key]['values'][0][$this->tables[$key]['pk_field']] = $result[0]["INSERT_ID"];
									$this->tables[$key]['values'][0][$this->tables[$key]['fk_field']] = $this->root_pk;
									// NOW WE HAVE THE PK/FK WE"LL REDEFINE THE ROW INDEX BY THE PK
									$rowData = $this->tables[$key]['values'][0];
									unset($this->tables[$key]['values'][0]);
									$this->tables[$key]['values'][$result[0]["INSERT_ID"]] = $rowData;
									
								}else{
									// HANDLE AN ENTITY
									#echo '$result[0]["INSERT_ID"]ENTITY<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.PHP_EOL;
									$this->tables[$key]['values'][$this->tables[$key]['pk_field']] = $result[0]["INSERT_ID"];
									// IF ITS A JOIN ADD THE FK FIELD
									if(isset($this->tables[$key]['fk_field'])){
										$this->tables[$key]['values'][$this->tables[$key]['fk_field']] = $this->root_pk;	
									}
									
								
								}
							}
						}
					}else{
						/*
						echo __METHOD__.'@'.__LINE__.'$this->tables['.$key.']["DSN"]<pre>'.print_r($this->tables[$key]['DSN'], true).'</pre>'.PHP_EOL;
						echo __METHOD__.'@'.__LINE__.'$value2["query"]<pre>'.print_r($value2["query"], true).'</pre>'.PHP_EOL;
						echo __METHOD__.'@'.__LINE__.'<pre>'.print_r($GLOBALS, true).'</pre>'.PHP_EOL;
						
						$result[] = $this->db->update(
							$this->tables[$key]['DSN'], 
							$value2["query"],  
							$args=array('returnArray' => true)
						);
						*/
						
						$result[] = $GLOBALS["DATA_API"]->update(
							$this->tables[$key]['DSN'], 
							$value2["query"],  
							$args=array('returnArray' => true)
						);
					}
					
					
				}
			}
			
			#echo __METHOD__.'@'.__LINE__.'$this->tables['.$key.']<pre>'.print_r($this->tables[$key], true).'</pre>'.PHP_EOL;
			unset($this->queries);
			//foundation
			
			return $result;
		}
		return false;
	}
	/**
	* DESCRIPTOR: checks the "modifiedColumns" array and generates update statements
	* based on the values of that array
	* if the table is not set [NULL] do everything, if it is do only that table
	* @param string $key 
	* @param string $value 
	* @param string $table 
	* @return NULL
	*/
	protected function generateQueries($key,$value,$table){
		#echo __METHOD__.'@'.__LINE__.' $table['.$table.'] $key=['.$key.'] <pre>'.var_export($value,true).'</pre>'."\n";
		if(!isset($table) || 0 == count($value)){
			return false;
		}
		$query = '';
		
		if(
			!isset($this->tables[$key])
			||
			(!isset($this->tables[$key]["values"]) || 0 == count($this->tables[$key]["values"]))
		){
			$this->tables[$key]["values"] = $value;
			#echo __METHOD__.'@'.__LINE__.'$this->tables['.$key.']<pre>'.var_export($this->tables[$key],true).'</pre>'.PHP_EOL;
		}
		if(
			$this->tables[$key]["values"][$this->tables[$key]["pk_field"]] == 0
		){
			#echo __METHOD__.'@'.__LINE__.'DO INSERT'.PHP_EOL;
			$queryType = 'INSERT';
			// insert into data (user_DbId, view_DbId, type, title) values ($user_DbId, $view_DbId, $type, '$title');
			#$this->generateInsertQuery($key,$value);
			if(
				(!isset($this->tables[$key]["foundation"]) || true !== $this->tables[$key]["foundation"])
				&&
				array_key_exists("fk_field", $this->tables[$key])
			){
				$this->tables[$key]["values"][$this->tables[$key]["fk_field"]] = $this->root_pk;
			}
		}else{
			$queryType = 'UPDATE';
			
			
		}
		foreach($value AS $key2 => $value2){
			/*
			* if the table is not set do everything, if it is do only that table
			*/
			#echo 'XXXXX$key=['.$key.'] $key2=['.$key2.']<pre>'.var_export($value2,true).'</pre>'.PHP_EOL;
			
			if(is_null($table) || (!is_null($table) && $table == $key)){
				if(is_array($value2)){
					$query2 = '';
					
					foreach($value2 AS $key3 => $value3){
						if(in_array($value2,$this->MYSQL_CONSTANTS)){//$DEFAULTS
							$query2 .= $key3.' = '.$value3.',';
						}else{
							$query2 .= $key3.' = "'.$value3.'",';
						}
					}
					if($query2 != ''){
						if($key2 ==0){
							$this->queries[$key][$key2]['queryType'] = 'INSERT';
							$this->queries[$key][$key2]['query'] = $this->generateInsertQuery($key, $value);
						}else{
							$query2 = $this->stripTrailingComma($query2);
							$this->queries[$key][$key2]['queryType'] = 'UPDATE';
							$this->queries[$key][$key2]['query'] = 'UPDATE '.$key.' SET '.$query2.' WHERE '.$this->tables[$key]["pk_field"].'="'.$key2.'" ;';
						}
						
					}
				}else{
					#echo __METHOD__.'@'.__LINE__.' $table['.$table.'] $key2=['.$key2.'] <pre>'.var_export($value2,true).'</pre>'."\n";
					if(in_array($value2,$this->MYSQL_CONSTANTS)){//$DEFAULTS
						#$this->queries[$key][$key2]['query'] = 'UPDATE '.$key.' SET '.$query2.' WHERE '.$this->tables[$key]["pk_field"].'='.$key2.' ;';
						$query .= $key2.' = '.$value2.',';
					}else{
						$query .= $key2.' = "'.$value2.'",';
					}
				}
				unset($this->modifiedColumns[$key]);
			}// END IF
			
		}// END FOREACH
		
		if($query != ''){
			$key2 = $this->tables[$key]["values"][$this->tables[$key]["pk_field"]];
			if($key2 ==0){
				$this->queries[$key][$key2]['queryType'] = 'INSERT';
				$this->queries[$key][$key2]['query'] = $this->generateInsertQuery($key, $value);
			}else{
				$query = $this->stripTrailingComma($query);
				$this->queries[$key][$key2]['queryType'] = 'UPDATE';
				$this->queries[$key][$key2]['query'] ='UPDATE '.$key.' SET '.$query.' WHERE '.$this->tables[$key]["pk_field"].'="'.$key2.'" ;';			
			}

			
		}
		return;
	}
	/**
	* DESCRIPTOR: checks the "modifiedColumns" array and generates update statements
	* based on the values of that array
	* if the table is not set [NULL] do everything, if it is do only that table
	* @param string $key 
	* @param string $value 
	* @param string $table 
	* @return NULL
	*/
	protected function generateInsertQuery($table, $value){
		#echo 'XXXXX$table=['.$table.']<pre>'.var_export($value,true).'</pre>'.PHP_EOL;
		$columnsNames = '';
		$columnsValues = '';
		foreach($value AS $key2 => $value2){
			if(in_array($value2,$this->MYSQL_CONSTANTS)){//$DEFAULTS
				#$this->queries[$key][$key2]['query'] = 'UPDATE '.$key.' SET '.$query2.' WHERE '.$this->tables[$key]["pk_field"].'='.$key2.' ;';
				#$query .= $key2.' = '.$value2.',';
				$columnsValues .= ' '.$value2.',';	
			}else{
				#$query .= $key2.' = "'.$value2.'",';
				$columnsValues .= '"'.$value2.'",';	
			}
			$columnsNames .= '`'.$key2.'`,';
			#$columnsValues .= '"'.$value2.'",';
		}
		$columnsNames = $this->stripTrailingComma($columnsNames);
		$columnsValues = $this->stripTrailingComma($columnsValues);
		$query = ' INSERT INTO `'.$table.'` ('.$columnsNames.') values ('.$columnsValues.');';
		#echo $query.LN
		return $query;
	}

	/**
	* DESCRIPTOR: strips column from the end of a string
	* if you're creating values like this:: $query .= $key2.' = "'.$value2.'",';
	* 
	* @param string $stringValue 
	* @return string $stringValue
	*/
	public function stripTrailingComma($stringValue){
		#echo '$string['.$string.']'.PHP_EOL;
		$stringValue = substr  ($stringValue, 0, strlen($stringValue)-1 );
		#echo '$string['.$string.']'.PHP_EOL;
		return $stringValue;
	}
	/**
	* DESCRIPTOR: PUKE SELF
	* @return obj 
	*/
	public function DUMP(){
		echo __CLASS__.'->'.__METHOD__.'<pre>'.print_r($this->tables, true).'</pre>'."\n";
		return;
	}
	//----------------------------------------------------
	
	//----------------------------------------------------
	/**
	* DESCRIPTOR: Saves the changes to the DB IF $commit=true (default)
	* @param bool $commit 
	* @return NULL 
	*/
	public function __destruct(){ //allow an over-ride from inherited classes $commit=true has to be internalized property
		// make this a class property since you can't pass any args here
		//
		$save=true;
		if($save===true){
			$this->save();
		}
		
		return;
	}
	
}
 

?>