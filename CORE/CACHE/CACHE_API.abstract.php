<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * no need to abstract here some of the implementations may be static
 * they system cacher 
abstract class CACHE_API_Z implements JSONRPC_REQUEST_HANDLER{
	// set response to null [NOT "NULL" JSON/DOM case sensitivity]/ notification default 
	public $RPCResponse = null;
	public $requestResult = null;
	public $requestError = null;
	abstract protected function create();
	abstract protected function retrieve();
	abstract protected function update();
	abstract protected function delete();
}
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	LOAD
 */

?>