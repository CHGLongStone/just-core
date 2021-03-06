<?php 
/**
* TRANSPORT_FILTER
* 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT
 * 
 */

/**
* # run down the chain and process it
* do arg v
* do URI [GET/POST]
* do XML
* do JSON
* 	do REST
* 	do SOA
*/	

	
if(!defined('JCORE_API_TRANSPORT_IN')){
	// set a default
}else{
	#echo 'JCORE_API_TRANSPORT_IN['.JCORE_API_TRANSPORT_IN.']<br>';
	$transportClass = JCORE_API_TRANSPORT_IN;
	#echo __FILE__.'@'.__LINE__.'transportClass['.$transportClass.']<br>';
	if(defined('JCORE_API_TRANSPORT_IN_VER')){
		$transportClass .= JCORE_API_TRANSPORT_IN_VER;
		#echo __FILE__.'@'.__LINE__.'transportClass['.$transportClass.']<br>';
	
	}
	$transportFilter[JCORE_API_TRANSPORT_IN] = JCORE_BASE_DIR.'TRANSPORT/'.JCORE_API_TRANSPORT_IN.'/'.$transportClass.'_API.class.php';
	#echo '$filepath['.$filepath.']<br>';
	
}

if(!defined('JCORE_API_TRANSPORT_OUT')){
	// set a default
}else{
	#echo 'JCORE_API_TRANSPORT_OUT['.JCORE_API_TRANSPORT_OUT.']<br>';
	$transportClass = JCORE_API_TRANSPORT_OUT;
	if(defined('JCORE_API_TRANSPORT_OUT_VER')){
		$transportClass .= JCORE_API_TRANSPORT_OUT_VER;
		#echo __FILE__.'@'.__LINE__.'transportClass['.$transportClass.']<br>';
	
	}
	
	#echo 'JCORE_API_TRANSPORT_OUT['.JCORE_API_TRANSPORT_OUT.']<br>';
	$transportFilter[JCORE_API_TRANSPORT_OUT] = JCORE_BASE_DIR.'TRANSPORT/'.JCORE_API_TRANSPORT_OUT.'/'.$transportClass.'.API.class.php';
}


#echo __FILE__.'@'.__LINE__.'transportFilter<pre>'.var_export($transportFilter, true).'</pre><br>';
#echo __FILE__.'@'.__LINE__.'transportFilters<br>';
if(is_array($transportFilter)){

	foreach($transportFilter AS $key => $value){
		#echo '<br> ----- value['.$value.']<br>';
		require_once($value);
		#include_once($value);
		#echo __FILE__.'@'.__LINE__.'<br>';
	}

}
#echo __FILE__.'@'.__LINE__.'<br>';







?>