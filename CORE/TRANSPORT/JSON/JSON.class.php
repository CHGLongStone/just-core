<?
/**
 * JSONRPC_1_0_API 
 *
$filePath = JCORE_BASE_DIR.'TRANSPORT/TRANSPORT_INTERFACE.interface.php';
require_once($filePath);
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT\JSON
 * @subpackage	JCORE\TRANSPORT\JSON
 */
namespace JCORE\TRANSPORT\JSON;
#use JCORE\TRANSPORT\TRANSPORT_INTERFACE as TRANSPORT_INTERFACE;


/**
 * Class JSON
 *
 * @package JCORE\TRANSPORT\JSON
*/
class JSON{


	
	
	/**
	* DESCRIPTOR: 
	* enforce a method to parse the request in the sub class
	* @param mixed raw_data 
	* @return return NULL  
	*/
	public function __construct(){
	}
/**
code	message	meaning
-32700	Parse error	Invalid JSON was received by the server.
An error occurred on the server while parsing the JSON text.
-32600	Invalid Request	The JSON sent is not a valid Request object.
-32601	Method not found	The method does not exist / is not available.
-32602	Invalid params	Invalid method parameter(s).
-32603	Internal error	Internal JSON-RPC error.
-32000 to -32099	Server error	Reserved for implementation-defined server-errors.
*/
	public static function json_decode($JSON, $toArray = true){
		return $this::validateJSON($JSON, $toArray = true);
	}
		
	public static function json_encode($JSON, $toArray = true){
		if($response = json_encode($JSON)){
			
		}else{
			$response = $this::validateJSON($JSON, $toArray = true);
		}
		return $response;
	}
		
		

	public static function validateJSON($JSON, $toArray = true){
		$JSONtoPHPVal = json_decode($JSON, $toArray);
		$error = null;
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				#$error =  ' - No errors';
			break;
			case JSON_ERROR_DEPTH:
				$error =  ' - Maximum stack depth exceeded';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				$error =  ' - Underflow or the modes mismatch';
			break;
			case JSON_ERROR_CTRL_CHAR:
				$error =  ' - Unexpected control character found';
			break;
			case JSON_ERROR_SYNTAX:
				$error =  ' - Syntax error, malformed JSON';
			break;
			case JSON_ERROR_UTF8:
				$error =  ' - Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
			default:
				$error =  ' - Unknown error';
			break;
		}
		if(NULL != $error){
			return  $error;
		}else{
			return $JSONtoPHPVal;

		}
	}

			

		
		
	
	
	
}

?>