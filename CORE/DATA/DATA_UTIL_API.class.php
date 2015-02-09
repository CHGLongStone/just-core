<?
/**
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DATA
 */
/**
*
*
*
 * @package	JCORE
 * @subpackage	DATA
*/
class DATA_UTIL_API{

	/**
	 * 
	 * @access public 
	 * @param string $stringval 
	 * @return string stringval
	 */
	public static function scrubWhitespace($stringval){
		$stringval = str_replace(array("\n", "\r", "\t"), " ", $stringval);
		$stringval = trim(ereg_replace(" +", " ", $stringval));
		return $stringval;
	}
	/**
	 * 
	 * @static
	 * @access public 
	 * @param float $setTime 
	 * @return int
	 */
	public static function cleanMicrotime($setTime=null){
		if($setTime != null){
			list($usec, $sec) = explode(" ", $setTime);	
		}else{
			list($usec, $sec) = explode(" ", microtime());	
		}
		
		list($zero, $usec)= explode("0.", $usec);
		return $usec;
	}
	/**
	 * 
	 * @access public 
	 * @param string $stringVal 
	 * @return string|bool stringval|false
	 */
	public static function scrubString($stringVal, $invalidList="\"\\?*:/@|<>"){
		if (strlen($stringVal) != strcspn($stringVal,$invalidList)) {
			return false;
		}
		return $stringVal;
	}
	
	/**
	 * 
	 * @access public 
	 * @param string $stringVal 
	 * @return string|bool stringval|false
	 */
	public static function getEnumValues($DSN, $table, $column){
		/*
		if(isset($GLOBALS["getEnumValues"][$TableName][$column])){
			return $GLOBALS["getEnumValues"][$TableName][$column];
		}
		*/
		$query = 'SHOW COLUMNS FROM '.$table.' LIKE "'.$column.'" ';
		
		#$result=mysql_query($query);
		$result = $GLOBALS["DATA_API"]->retrieve($DSN, $query, $args=array('returnArray' => true));
		/*
		if($GLOBALS["J_Debug"]){
			(mysql_error())? debugTrace("getEnumValues",$query.'<br>'.mysql_error()): debugTrace("getEnumValues",$query);
		}
		*/
		#if(mysql_num_rows($result)>0){
		if(count($result)>0){
			#$row=mysql_fetch_row($result);
			#$getEnumValues=explode('","',preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row[1]));
			#$getEnumValues = $row[1];
			$getEnumValues = $result[0]['Type'];
			#echo 'row[0]'.$row[0].'<br>row[1]'.$row[1].'<br>row[2]'.$row[2].'<br>';;
			#echo __METHOD__.'@'.__LINE__.'<b>$result<pre>'.var_export($result, true).'</pre></b>';
		}

		#$getEnumValues = ;
		$getEnumValues = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",stripslashes($getEnumValues)));
		/**/

		#$GLOBALS["getEnumValues"][$TableName][$column] = $getEnumValues;
		return $getEnumValues;
	}
	/**
	 * 
	 * @access public 
	 * @param string $stringVal 
	 * @return string|bool stringval|false
	 */
	public static function _selectEnum($arrayVal,$preSelect,$IDname, $scriptVal=''){
		$selectBox ='';
		$selectList = '';
		foreach($arrayVal AS $key => $val){
			#echo '<pre>key=='.$key.' &&val=='.$val.'</pre>';
			if($val == $preSelect){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$selectList .= '<option value="'.$val.'" '.$selected.'>'.$val.'</option>
			';
		}
		$selectBox .='
		<select name="'.$IDname.'"  id="'.$IDname.'" size="1" class="normalsmallform" '.$scriptVal.'>
			'.$selectList.'
		</select>
		';
		return $selectBox;
	}
	
	
	/**
	 * 
	 * @access public 
	 * @param string $stringVal 
	 * @return string|bool stringval|false
		$args["dspCol"] = 'NodeName';
		$args["idCol"] = 'RuleItemID';
		$args["preSelect"] = 'wdf';
		$args["DOMID"] = $_REQUEST["extFK"];
	 */
	public static function _selectFromResult($result, $args=array() ){ //$keyField=idCol, $descField=dspCol, $preSelect, $IDname=DOMID){
		$selectBox ='';
		$selectList = '';
		#echo __METHOD__.':::'.__LINE__.'args [<pre>'.var_export($args, true).'<pre>]<br>';
		#while ($row = mysql_fetch_object($result)){
		foreach($result AS $key => $val){
			#echo 'val<pre>'.var_export($val,true).'</pre>';
			#echo '<pre>key=='.$row->$args["idCol"].' &&val=='.$row->$args["dspCol"].'</pre>';
			
			if($val[$args["idCol"]] == $args["preSelect"]){
				$selected = 'selected';
				#echo '<pre><b>key</b>=='.$row->$args["idCol"].' &&preSelect=='.$preSelect.'</pre>';
			}else{
				$selected = '';
			}
			$selectList .= '<option value="'.$val[$args["idCol"]].'" '.$selected.'>'.$val[$args["dspCol"]].'</option>
			';
		}
		
		($selected == '')? $selected = 'selected': $selected = '';
		$selectBox .='
		<select name="'.$args["DOMID"].'"  id="'.$args["DOMID"].'" size="1" class="normalsmallform" >
			<option value="" '.$selected.'>Select
			'.$selectList.'
		</select>
		';
		return $selectBox;
	}

	
}


?>