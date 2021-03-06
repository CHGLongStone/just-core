<?php
/**
* DATA_API_INTERFACE
* Instances can be created for any DB supported by PHP inc. NoSQL
* 
* 
* @author		Jason Medland<jason.medland@gmail.com>
* @package	JCORE\DATA\API
*/
namespace JCORE\DATA\API;

/**
 * Interface DATA_API_INTERFACE
 *
 * @package JCORE\DATA\API
*/
interface DATA_API_INTERFACE{
	
	
	/**
	* DESCRIPTOR: set_connection
	* @param	persistent
	* @return NULL 
	*/
	public function set_connection($persistent =NULL); 
	
	
	/**
	* DESCRIPTOR: verify_connection
	*
	* @access public 
	* @param NULL
	* @return NULL 
	*/
	public function verify_connection();
	
	
	/**
	* DESCRIPTOR: resultToAssoc 
	* return a raw result as an associative array
	* 
	* @access public 
	* @param mixed result
	* @param string query
	* @return NULL 
	*/
	public function resultToAssoc($result, $query);
 
 	/**
	* DESCRIPTOR: raw
	* execute a raw *string* query 
	* 
	* @access public 
	* @param string query
	* @return NULL 
	*/	public function raw($query);
	
	
	/**
	* DESCRIPTOR: create
	* CRUD shorthand 
	* 
	* @access public 
	* @param string query
	* @param mixed args
	* @return NULL 
	*/	public function create($query, $args=array('returnArray' => true));
	
	
	/**
	* DESCRIPTOR: retrieve
	* CRUD shorthand 
	* 
	* @access public 
	* @param string query
	* @param mixed args
	* @return NULL 
	*/	public function retrieve($query, $args=array('returnArray' => true));
	
	
	/**
	* DESCRIPTOR: update
	* CRUD shorthand 
	* 
	* @access public 
	* @param string query
	* @param mixed args
	* @return NULL 
	*/
	public function update($query, $args=array('returnArray' => true));
	
	
	
	/**
	* DESCRIPTOR: delete
	* @param string query
	* CRUD shorthand 
	* 
	* @access public 
	* @param mixed args
	* @return NULL 
	*/
	public function delete($query, $args=array('returnArray' => true));
	
	#public function introspectTable($DSN, $tableName);
	#public function introspectTable($tableName);
	
	

	
}

?>