<?
/***
* DATA_API_INTERFACE
* Instances can be created for any DB supported by PHP inc. NoSQL
* 
* 
* @author		Jason Medland<jason.medland@gmail.com>
* @package	JCORE
* @subpackage DATA
*/
/***
* DATA_API_INTERFACE
* Instances can be created for any DB supported by PHP inc. NoSQL
* 
* 
* @author		Jason Medland<jason.medland@gmail.com>
* @package	JCORE
* @subpackage DATA
*/
interface DATA_API_INTERFACE{
	/* 
	* Defines base fuctions to support public methods of dbInterface 
	*/
	public function set_connection($persistant=NULL); //
	public function verify_connection();
	public function resultToAssoc($result, $query);
	public function raw($query);
	public function create($query, $args=array('returnArray' => true));
	public function retrieve($query, $args=array('returnArray' => true));
	public function update($query, $args=array('returnArray' => true));
	public function delete($query, $args=array('returnArray' => true));
	
	#public function introspectTable($DSN, $tableName);
	#public function introspectTable($tableName);
	
	

	
}

?>