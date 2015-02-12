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
 * Besides being able to instatiate a "stub" object of the "foundation" Entity/row
 * the object can also extent "stubs" of "child members". a stub of an Entity can be extended by:
 * 		$OBJECT->initializeJoinRecord($database, $tableName, $pk_field, $fk_field)
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
	 * 
	 * @param	string 	database
	 * @param	string 	table
	 * @return	string 	pk_field
	 * @return	int 	pk
	 */
	public function __construct($config){
		#action
		//$database, $table, $pk_field, $pk 
		//echo 'result<pre>'.print_r($result, true).'</pre>'.LN;
		#$config["database"];
		#$config["table"];
		#$config["pk_field"]; 
		#$config["pk"];
		
		$GLOBALS['LOG_DATA']->log(LOG_DEBUG,__METHOD__, '(<pre>'.print_r($config, true).'</pre>)');
		
		if(is_array($config)){
			#echo 'config<pre>'.print_r($config, true).'</pre>'.PHP_EOL;
			if(
				isset($config["DSN"])
				&&
				isset($config["table"])
				&&
				isset($config["pk_field"])
				&&
				isset($config["pk"])
			){
				#echo 'baseObjDDDDD<pre>'.print_r($else, true).'</pre>'.LN;
				#GLOBAL $db;
				$this->config = $config;
				$this->tables[$config["table"]] 				= array();
				$this->tables[$config["table"]]['database'] 	= $config["database"];
				$this->tables[$config["table"]]['foundation'] 	= true;
				$this->tables[$config["table"]]['pk_field'] 	= $config["pk_field"];
				$this->tables[$config["table"]]['pk'] 			= $config["pk"];
				$this->tables[$config["table"]]['values'] 		= array();
				
				$query = 'SELECT * FROM '.$config["table"].' WHERE '.$config["pk_field"].' = '.$config["pk"].' ';
				#$result = $db->SQL_select($config["database"], $query, $returnArray=true);
				$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
				#$result = $db->SQLResultToAssoc($result);
				
				$this->tables[$config["table"]]['values'] = $result[0];
				#echo 'count($result[0])<pre>'.print_r(count($result[0]), true).'</pre>'.LN;
				if(count($result[0]) >= 1){
					$this->initialized = true;
					$this->root_pk = $config["pk"];
				}elseif($result["EXCEPTION"]){
					#echo '$result<pre>'.print_r($result, true).'</pre>'.LN;
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'ERROR MySQL error['.$result["EXCEPTION"]["ID"].']error:'.$result["EXCEPTION"]["MSG"].''); // ID MSG $query 
					$this->tables[$config["table"]]['values'] = $result["EXCEPTION"];
				}else{
					$GLOBALS['LOG_DATA']->log(LOG_CRIT, __METHOD__ , 'result['.print_r($result, true).']'); // ID MSG $query 
				}
						
			}else{
				#echo 'else<pre>'.print_r($else, true).'</pre>'.LN;
			}	
		}

		#$this->initialized;
		#echo '$this->tables<pre>'.print_r($this->tables, true).'</pre>';
		return;
		
	}
	
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	database
	* @param	string 	tableName
	* @return NULL 
	*/
	public function initialize($database, $tableName){ //, $foundation=false
		
		#$this->initialized = true;
		$this->tables[$tableName] = array();
		$this->tables[$tableName]['database'] 	= $database;
		if($foundation===true){
			$this->tables[$tableName]['foundation'] = TRUE; //$foundation;
		}
		$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($database, $tableName, false);
		$this->tables[$tableName]['values']		= $values;
		$this->initialized = true;
		return;
	}
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	database
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeJoinRecord($database, $tableName, $pk_field, $fk_field){
		$this->tables[$tableName] = array();
		$this->tables[$tableName]['database'] 	= $database;
		
		$this->tables[$tableName]['pk_field'] = $pk_field;
		$this->tables[$tableName]['fk_field'] = $fk_field;
		
		$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($database, $tableName);
		$this->tables[$tableName]['values']		= $values;
		return;
	}
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	database
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	public function initializeCollectionRecord($database, $tableName, $pk_field, $fk_field){
		if(!isset($this->tables[$tableName])){
			$this->tables[$tableName] = array();
		}
		
		$this->tables[$tableName]['database'] 	= $database;
		
		$this->tables[$tableName]['pk_field'] = $pk_field;
		$this->tables[$tableName]['fk_field'] = $fk_field;
		
		#$this->tables[$tableName]['pk'] 		= 0;
		$values = $this->initializeFromSchema($database, $tableName);
		$this->tables[$tableName]['values'][0]		= $values;
		$this->tables[$tableName]['values'][0][$this->tables[$tableName]['pk_field']]		= 0;
		return;
	}
	/**
	* DESCRIPTOR: contruct from table def
	* @param	string 	database
	* @param	string 	tableName
	* @return NULL SCHEMA
	*/
	protected function initializeFromSchema($database, $tableName, $set_fk=true){
		GLOBAL $db;
		// go get the table def
		#$db->introspectTable($database, $tableName);
		$result = $GLOBALS["DATA_API"]->introspectTable($database, $tableName);
		$values	= array();
		//modifiedColumns
		#$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		#foreach($db->tableDefinitions[$database][$tableName] AS $key => $value){
		foreach($GLOBALS["DATA_API"]->tableDefinitions[$database][$tableName] AS $key => $value){
			#echo ' key='.$key.' :: value=<pre>'.var_export($value,true).'</pre><br>';
			
			if(isset($value["allowNull"]) && $value["allowNull"] == 'NO'){
				$values[$key] = $value["default"];
			}else{
				$values[$key] = NULL;
			}			
			
			if(isset($value["key"]) && $value["key"] == 'primary'){
				#echo 'PK['.$key.']'.LN;
				$this->tables[$tableName]['pk_field'] 	= $key;
				$values[$key] = $this->tables[$tableName]['pk'];
			}
			if($key == $this->tables[$tableName]['fk_field']){
				$values[$key] = $this->root_pk;
			}
			$this->modifiedColumns[$tableName][$key] = $values[$key];
		}	
		return $values;
	}	
	/**
	* DESCRIPTOR: joins a single record from anothe DB/Table
	* @param	string 	database
	* @param	string 	baseTable
	* @return	string 	pk_field
	* @return	string 	pk_field
	* @return	int 	fk
	* @return NULL 
	*/
	public function joinRecord($database, $tableName, $pk_field, $fk_field, $fk){
		GLOBAL $db;
		#$caller=__CLASS__.'->'.__FUNCTION__;
		$this->tables[$tableName] 				= array();
		$this->tables[$tableName]['database'] 	= $database;
		$this->tables[$tableName]['pk_field'] 	= $pk_field;
		
		$this->tables[$tableName]['fk_field'] 	= $fk_field;
		$this->tables[$tableName]['fk'] 		= $fk;
		
		//$this->tables[$tableName]['tableDef'] = $db->introspectTable($database, $tableName);
		
		$query = 'SELECT * FROM '.$tableName.' WHERE '.$fk_field.' = '.$fk.' ';
		#$result = $db->SQL_select($database, $query, $returnArray=true);
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$this->tables[$tableName]['pk'] 	= $result[0][$pk_field];
		$this->tables[$tableName]['values'] 	= array();
		$this->tables[$tableName]['values'] = $result[0];
		
		return;
	}
	/**
	* DESCRIPTOR: joins a single record from anothe DB/Table
	* @param	string 	database
	* @param	string 	baseTable
	* @return	string 	pk_field
	* @return	string 	pk_field
	* @return	int 	fk
	* @return NULL 
	*/
	public function joinCollection($database, $joinTable, $pk_field, $fk_field, $fk){
		GLOBAL $db;
		
		$this->tables[$joinTable] 				= array();
		$this->tables[$joinTable]['database'] 	= $database;
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
				}elseif(true == is_array($this->tables[$table]['values']) ){
					if(array_key_exists( $column, $this->tables[$table]['values'])){
						return $this->tables[$table]['values'][$column];
					}
				}
				return 'NO SUCH VALUE'; //false
				break;
			case"set":
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
						$this->tables[$table]['values'][$column] = $value;
						// we want to track which fields we've modified for commits
						$this->modifiedColumns[$table][$column] = $value;
						return true;
					}
				}
				return 'NO SUCH VALUE'; //false;
				break;
			case"aaaaaaaa":
				#action some thing else here?
				break;
			case"bbbbbbbbb":
				#action some thing else here?
				break;
			default:
				echo 'default'.__METHOD__.' CALLED ['.$method.'] WITH <pre>'.var_export($args,true).'</pre>'.LN;
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
		foreach($this->modifiedColumns AS $key => $value){
			#echo '$key=['.$key.']<pre>'.var_export($value,true).'</pre>'."\n";
			if(is_array($value)){
				#echo '$value<pre>'.print_r($value, true).'</pre>'.LN;
				$this->generateQueries($key, $value, $table);
			}
		}
		#echo '-----------------------------------------------------'.LN;
		if(count($this->queries) >= 1){
			GLOBAL $db;
			#echo __METHOD__.__LINE__.'$this->queries<pre>'.print_r($this->queries, true).'</pre>'.LN;
			#echo __METHOD__.__LINE__.'$this->tables['.$key.']<pre>'.print_r($this->tables[$key], true).'</pre>'.LN;
			foreach($this->queries AS $key => $value){
				#echo '$key=['.$key.']<pre>'.var_export($value,true).'</pre>'.LN;
				foreach($value AS $key2 => $value2){
					#echo '$key2=['.$key2.']<pre>'.var_export($value2,true).'</pre>'.LN;
					
					if($value2["queryType"] == 'INSERT'){
						#$result[] = $GLOBALS["db"]->SQL_insert($this->tables[$key]['database'], $value2["query"], $returnArray=true);
						if(isset($this->tables[$key]['foundation']) && $this->tables[$key]['foundation'] === true){
							#echo '$result<pre>'.var_export($result,true).'</pre>'.LN;
							$result[] = $GLOBALS["db"]->SQL_insert($this->tables[$key]['database'], $value2["query"], $returnArray=true);
							if(is_int($result[0]["INSERT_ID"])){
								#echo '$result[0]["INSERT_ID"]<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.LN;
								$this->root_pk = $result[0]["INSERT_ID"];
								$this->tables[$key]['values'][$this->tables[$key]['pk_field']] = $this->root_pk;
							}
						}else{
							//replace
							
							#$bodytag = str_replace('$this->root_pk', $this->root_pk, $value2["query"]);
							$result[] = $GLOBALS["db"]->SQL_insert($this->tables[$key]['database'], $value2["query"], $returnArray=true);
							if(is_int($result[0]["INSERT_ID"])){
								
								#echo __METHOD__.__LINE__.'$this->tables['.$key.']["values"][0]['.$this->tables[$key]["pk_field"].']===['.print_r($this->tables[$key]['values'][0][$this->tables[$key]['pk_field']], true).']'.LN;
								if(isset($this->tables[$key]['values'][0][$this->tables[$key]['pk_field']])){
									// HANDLE A COLLECTION
									#echo '$result[0]["INSERT_ID"]COLLECTION<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.LN;
									$this->tables[$key]['values'][0][$this->tables[$key]['pk_field']] = $result[0]["INSERT_ID"];
									$this->tables[$key]['values'][0][$this->tables[$key]['fk_field']] = $this->root_pk;
									// NOW WE HAVE THE PK/FK WE"LL REDEFINE THE ROW INDEX BY THE PK
									$rowData = $this->tables[$key]['values'][0];
									unset($this->tables[$key]['values'][0]);
									$this->tables[$key]['values'][$result[0]["INSERT_ID"]] = $rowData;
									
								}else{
									// HANDLE AN ENTITY
									#echo '$result[0]["INSERT_ID"]ENTITY<pre>'.var_export($result[0]["INSERT_ID"],true).'</pre>'.LN;
									$this->tables[$key]['values'][$this->tables[$key]['pk_field']] = $result[0]["INSERT_ID"];
									// IF ITS A JOIN ADD THE FK FIELD
									if(isset($this->tables[$key]['fk_field'])){
										$this->tables[$key]['values'][$this->tables[$key]['fk_field']] = $this->root_pk;	
									}
									
								
								}
							}
						}
					}else{
						$result[] = $GLOBALS["db"]->SQL_update($this->tables[$key]['database'], $value2["query"], $returnArray=true);
					}
					
					
				}
			}
			
			echo __METHOD__.__LINE__.'$this->tables['.$key.']<pre>'.print_r($this->tables[$key], true).'</pre>'.LN;
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
		#echo 'XXXX $table['.$table.'] $key=['.$key.'] <pre>'.var_export($value,true).'</pre>'."\n";
		$query = '';
		if($this->tables[$key]["values"][$this->tables[$key]["pk_field"]] ==0){
			echo 'DO INSERT'.LN;
			$queryType = 'INSERT';
			// insert into data (user_DbId, view_DbId, type, title) values ($user_DbId, $view_DbId, $type, '$title');
			$this->generateInsertQuery($key,$value);
		}else{
			$queryType = 'UPDATE';
			
			
		}
		foreach($value AS $key2 => $value2){
			/*
			* if the table is not set do everything, if it is do only that table
			*/
			#echo 'XXXXX$key=['.$key.'] $key2=['.$key2.']<pre>'.var_export($value2,true).'</pre>'.LN;
			
			if(is_null($table) || (!is_null($table) && $table == $key)){
				if(is_array($value2)){
					$query2 = '';
					
					foreach($value2 AS $key3 => $value3){
						$query2 .= $key3.' = "'.$value3.'",';
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
					$query .= $key2.' = "'.$value2.'",';
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
		#echo 'XXXXX$table=['.$table.']<pre>'.var_export($value,true).'</pre>'.LN;
		$columnsNames = '';
		$columnsValues = '';
		foreach($value AS $key2 => $value2){
			$columnsNames .= '`'.$key2.'`,';
			$columnsValues .= '"'.$value2.'",';	
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
		#echo '$string['.$string.']'.LN;
		$stringValue = substr  ($stringValue, 0, strlen($stringValue)-1 );
		#echo '$string['.$string.']'.LN;
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