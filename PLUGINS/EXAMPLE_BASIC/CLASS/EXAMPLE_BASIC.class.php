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
*
*/
#echo __FILE__.'@'.__LINE__.'<br>';
require_once 'renderNestedTree.class.php';
require_once 'basicAPI.class.php';


/**
*
*
*
*
*
* @package		EXAMPLE
* @subpackage	BASIC
*/
class EXAMPLE_BASIC{

	protected $templater;
	
	public function __construct(&$templater){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		#$e = new Exception();
		#echo '$e<pre>'.var_export($e->getMessage(),true).'</pre>';
		$this->templater = $templater;
		#echo '<pre>'.var_export($this->templater,true).'</pre>';
	}
	
	public function function1($ps_template_body){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		//  
		#$stringval = '<pre>'.var_export($this->templater,true).'</pre>';
		$stringval = 'Returned from function1<br>';
		#echo $stringval;
		/*
		$TEMPLATER->assign_vars(array(	
			'BODY' 	=> $subTemplater->sparse($ps_template_body, true, $retvar = 'returnString')
		));	
		*/
		$this->templater->assign_vars(array(	
			'BODY' 	=> '<hr>'.__METHOD__.'::'.$stringval.'<hr>'
		));	
		#echo __METHOD__.$this->templater->sparse($ps_template_body, true, $retvar = 'returnString');
		#return $stringval;
		#echo '<pre>'.var_export($this->templater,true).'</pre>';
		return;
	}
	
	public function function2($setTime=null){
		//
		return 'Returned from function2<br>';
	}
	
}

#echo __FILE__.'@'.__LINE__.'<br>';
?>