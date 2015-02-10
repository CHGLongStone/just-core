<?
/***
* DATA_API_INTERFACE
* Instances can be created for any DB supported by PHP inc. NoSQL
* 
* 
* @author		Jason Medland<jason.medland@gmail.com>
* @package	JCORE\DATA\API
*/
namespace JCORE\DATA\API;

/***
 * Interface DATA_API_INTERFACE
 *
 * @package JCORE\DATA\API
*/
interface DATA_API_INTERFACE{
	/* 
	* Defines base functions to support public methods of dbInterface 
	*/
	public function set_connection($persistent =NULL); //
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