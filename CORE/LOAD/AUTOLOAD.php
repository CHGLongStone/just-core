<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	LOAD
 */
/**
* namespace for the called class is
* CORE_[SUBDIR_(S)].CLASSNAME
* CORE_AUTH_OPENID
*/

function __autoload($calledClass) {
	/**
	echo __FUNCTION__.'func_get_args()<pre>'.var_export(func_get_args(),true).'</pre> @'.__FILE__.'@'.__LINE__.'<br>';	
	if(true === stripos ( $class_name, "." )){
		$myParts = explode(".", $class_name);
		echo __FILE__.'@'.__LINE__.'  myParts()<pre>'.var_export($myParts,true).'</pre> @'.'<br>';	
	}
	echo __FUNCTION__.'calledClass('.$calledClass.') '.__FILE__.'@'.__LINE__.'<br>';
	*/
	$plugins = $GLOBALS['CONFIG_MANAGER']->getRegisteredPlugins();
	#echo __FILE__.'@'.__LINE__.'  plugins()<pre>'.var_export($plugins,true).'</pre> @'.'<br>';	
	$loadFile = JCORE_PLUGINS_DIR.$calledClass.'/'.'CLASS/'.$calledClass.'.class.php';
	#echo __FUNCTION__.'loadFile('.$loadFile.') '.__FILE__.'@'.__LINE__.'<br>';
	/*
	echo __FUNCTION__.'file_exists($loadFile)('.file_exists($loadFile).') '.__FILE__.'@'.__LINE__.'<br>';
	echo __FUNCTION__.'@'.__LINE__.'<br>';
	*/
	
	if(file_exists($loadFile)){
		#echo __FUNCTION__.'---loadFile('.$loadFile.') @'.__LINE__.'<br>';
		#require_once $loadFile;
		include_once $loadFile;
		#echo __FUNCTION__.'@'.__LINE__.'<br>';
	}else{
		#echo __FUNCTION__.'@'.__LINE__.'<br>';
		$e = new Exception();
		echo 'ERROR'.$calledClass.' in File:'.$loadFile.'<br> this plugin is not installed<br>'.$e->getTraceAsString();
	}
	#echo __FUNCTION__.'@'.__LINE__.'<br>';
	/***
	$loadFile = '';
	$strpos = strpos  ( $calledClass, "." );
	#$PLUGIN = substr ($calledClass , 0, $strpos);
	#echo __FUNCTION__.'  PLUGIN('.$PLUGIN.') '.__FILE__.'@'.__LINE__.'<br>';
		
		#echo __FUNCTION__.'strpos('.$strpos.') '.__FILE__.'@'.__LINE__.'<br>';
		#echo __FUNCTION__.'strpos('.substr ($calledClass , 0, $strpos).') '.__FILE__.'@'.__LINE__.'<br>';
		$class_name = substr ($calledClass , $strpos+1 );
		echo __FUNCTION__.'$class_name('.$class_name.') '.__FILE__.'@'.__LINE__.'<br>';
		
	

	
	#/
	#$PACKAGE = strstr  ( $calledClass, ".", true );
	#	echo __FUNCTION__.'PACKAGE('.$PACKAGE.') '.__FILE__.'<br>';
	#$PLUGIN = strstr  ( $calledClass, ".");
	#/
	#JCORE_PLUGINS_DIR;
	switch($class_name){
		
		default:
			#pitch error
			$loadFile = JCORE_PLUGINS_DIR.$PLUGIN.'/'.'CLASS/'.$class_name.'.class.php';
			echo __FUNCTION__.'  loadFile('.$loadFile.');<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.__FILE__.'@'.__LINE__.'<br>';
			break;
	}
	*/

   return;
}
?>