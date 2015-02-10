<? 
/**
 * Basic example to show how plugins work
 * 
 * 
 * @author		Jason Medland
 * @package		EXAMPLE
 * @subpackage	BASIC
 */
/**
* @package		EXAMPLE
* @subpackage	BASIC
*/
class basicAPI{
	
	/**
	* 
	* @access public 
	* @param mixed $subTemplater
	* @return
	*/
	public static function function1(&$subTemplater=null){
		//  		$stringval = '<pre>'.var_export($subTemplater,true).'</pre>';
		$stringval = 'Returned from function1';
		return $stringval;
	}
	/**
	* 
	* @access public 
	* @param mixed $setTime
	* @return string
	*/
	public static function function2($setTime=null){
	
		return 'Returned from function2';
	}
	
}

?>