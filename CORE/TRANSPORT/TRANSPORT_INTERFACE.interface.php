<?php
/**
 * TRANSPORT_INTERFACE
 * couple good ideas here http://www.flickr.com/services/api/
HTTP
	REQUEST
		method
		headers
			mime
			empty line
		body

	RESPONSE
		header
			mime
		status code
		body

 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	TRANSPORT
*/
namespace JCORE\TRANSPORT;
/**
 * interface TRANSPORT_INTERFACE
 *
 * @package JCORE\DAO\TREE
*/
interface TRANSPORT_INTERFACE{
	/*INTERFACE DEFINES REQUEST ACCESS TO JSONRPC SERVICES 
	 */
	public function parseRequest($raw_data);
	public function compileResponse($dataSet); //returns $this->RPCResponse;
}


?>