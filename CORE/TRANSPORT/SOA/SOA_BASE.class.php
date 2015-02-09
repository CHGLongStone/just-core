<?
/**
 * DESCRIPTOR:
 * this class needs to be extended
 * as a primary consideration for SOA, we need to expose the class and it's methods
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	LOAD
 */
/**
* SOA_BASE is an abstract base class to provide introspection into service classes 
* that are extended 
*/
abstract class SOA_BASE { //implements Reflector 
	/**
	* DESCRIPTOR: 
	* empty constructor, this class needs to be extended
	* as a primary consideration for SOA, we need to expose the class and it's methods
	* 
	* @param param NULL
	* @return return  NULL
	*/
	private function __construct(){
		return;
	}
	/**
	* DESCRIPTOR: 
	* introspectService will provide all of the methods, members and arguements 
	* within scope of the call as well as the document comments assotiatiated 
	* with the extended class. This method can be overridden or extended 
	* to parse this data into a WSDL or similar format 
	* 
	* @param param NULL
	* @return string return  NULL
	*/
	public function introspectService(){
		$calledClass = get_called_class();
		#echo __METHOD__.__LINE__.'$calledClass<pre>['.var_export($calledClass, true).']</pre>'.'<br>'; 
		$reflector = new ReflectionClass($calledClass);
		#echo __METHOD__.__LINE__.'$reflector<pre>['.var_export($reflector, true).']</pre>'.'<br>'; 
		$barebones = array();
		#$barebones['methods'] = $reflector->getMethods();
		#$barebones['comments'] = $reflector->getDocComment();
		$barebones['serviceDescription'] = $reflector->export($this, true);
		#$serviceDescription = $reflector->export($this, true);
		#$barebones = Reflection::export($this);
		#$barebones .= Reflection::getDocComment();
		$this->serviceResponse = $barebones;
		#echo __METHOD__.__LINE__.'$barebones<pre>['.var_export($barebones, true).']</pre>'.'<br>'; 
		#return $serviceDescription;
		return TRUE;
	}
	
}
?>