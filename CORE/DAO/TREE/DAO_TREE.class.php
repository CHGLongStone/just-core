<?php
/**
 * 
 *
 *
 *
 *
 *
 *
 *
 * @author		Jason Medland
 * @package		JCORE\DAO\TREE
 * 
 */
namespace JCORE\DAO\TREE;
/**
* Class DAO_TREE
* this is a Modified Preorder Tree Traversal based on the example given here:
* http://articles.sitepoint.com/article/hierarchical-data-database
* http://dev.mysql.com/tech-resources/articles/hierarchical-data.html
*
* @package JCORE\DAO\TREE
*/
class DAO_TREE{
	/**
	* DATA_API
	 * @access protected 
	 * @var mixed DATA_API
	 */
	protected $DATA_API;
	/**
	* config
	 * @access protected 
	 * @var mixed config
	 */
	protected $config = array(
		'DSN' => 'JCORE',
		'table' => 'ACLARO',
		'pkField' => 'pk',
		'parentField' => 'parent_pk',
		'leftBound' => 'leftBound',
		'rightBound' => 'rightBound',
		'dspShortName' => 'KeyName',
		'pk' => 1,
		'callBackDisplay' => null,
		'treeStyle'	=> 'EXTENDED' // /TEXT/SIMPLE/FULL/EXTENDED
	);
	/**
	* treeStyle
	 * @access protected 
	 * @var mixed treeStyle
	 */
	protected $treeStyle = array('TEXT','SIMPLE','FULL','EXTENDED');
	/**
	* itCounter
	 * @access public 
	 * @var mixed itCounter
	 */
	public $itCounter = 0;

	/**
	* outputString
	 * @access protected 
	 * @var mixed outputString
	 */
	protected $outputString = '';
	/**
	* whereClause
	 * @access protected 
	 * @var mixed whereClause
	 */
	protected $whereClause = '';
	#public $whereCols = array();
	/**
	* whereCols
	 * @access public 
	 * @var mixed whereCols
	 */
	public $whereCols = null;
	/**
	* treeArray
	 * @access public 
	 * @var mixed treeArray
	 */
	public $treeArray = array();
	/**
	* extensionTables
	 * @access public 
	 * @var mixed extensionTables
	 */
	public $extensionTables = array();
	
	
	/**
	* DESCRIPTOR: __construct
	* 
	* @access public 
	* @param mixed config
	* @return NULL 
	*/
	public function __construct($config=null){
		#action
		
		//$database, $table, $pkField, $pk 
		
		/**
		echo __METHOD__.'@'.__LINE__.'config<pre>'.print_r($config, true).'</pre>';
			$config["DSN"];
			$config["table"];
			$config["pkField"]; 
			$config["pk"];
			$config["parentField"];
			$config["leftBound"];
			$config["rightBound"];
			$config["DATA_API"];
			$config["callBackDisplay"] 
				STATIC: array($classname, $methodname) 
				INSTANCE: array($objectinstance, $methodname) setCallBackDisplay($callBackDisplay)
			$config["DATA_API"];
			
		*/
		if(isset($config["DATA_API"])){
			$this->DATA_API = $config["DATA_API"];
		}else{
			$this->DATA_API = $GLOBALS["DATA_API"];
		}
		if(isset($config["DSN"])){
			$this->config["DSN"] = $config["DSN"];
		}
		if(isset($config["table"])){
			$this->config["table"] = $config["table"];
		}
		if(isset($config["pkField"])){
			$this->config["pkField"] = $config["pkField"];
		}
		if(isset($config["dspShortName"])){
			$this->config["dspShortName"] = $config["dspShortName"];
		}
		if(isset($config["leftBound"])){
			$this->config["leftBound"] = $config["leftBound"];
		}
		
		if(isset($config["leftBoundIdx"])){
			$this->config["leftBoundIdx"] = $config["leftBoundIdx"];
		}else{
			$this->config["leftBoundIdx"] = 'left_bound';
		}
		
		if(isset($config["parentField"])){
			$this->config["parentField"] = $config["parentField"];
		}
		if(isset($config["rightBound"])){
			$this->config["rightBound"] = $config["rightBound"];
		}
		if(isset($config["rightBoundIdx"])){
			$this->config["rightBoundIdx"] = $config["rightBoundIdx"];
		}else{
			$this->config["rightBoundIdx"] = 'right_bound';
		}
		
		if(isset($config["treeStyle"])){
			$this->config["treeStyle"] = $config["treeStyle"];
		}else{
			$this->setTreeStyle();
		}
		
		if(isset($config["callBackDisplay"])){
			$this->config["callBackDisplay"] = $config["callBackDisplay"];
		}
		
		if(isset($config["pk"])){
			$this->config["pk"] = $config["pk"];
		}else{
			$this->pk = 0;
		}
		#echo '$this->config<pre>'.print_r($this->config, true).'</pre>';
		return;
	}
	/**
	* TEXT 		- plain text
	* SIMPLE 	- basic tree array 
	* FULL		- basic tree array with data for each node
	* EXTENDED	- tree array with join data for each node 
	* 
	* @access public 
	* @param string $treeStyle
	* @param array $extArgs
	* @return bool 
	*/
	public function setTreeStyle($treeStyle='TEXT', $extArgs=null){
		#'treeStyle'	=> 'TEXT' 
		#echo __METHOD__.'@'.__LINE__.'$treeStyle<pre>'.var_export($treeStyle,true).'</pre>';
		if(in_array($treeStyle, $this->treeStyle)){
			#echo __METHOD__.'@'.__LINE__.'$treeStyle<pre>'.var_export($treeStyle,true).'</pre>';
			$this->config["treeStyle"] = $treeStyle;// /TEXT/SIMPLE/FULL/EXTENDED
			/**
			* if its not EXTENDED return
			*/
			if(!is_array($extArgs)){ 
				return true;
			}
			#echo __METHOD__.'@'.__LINE__.'$extArgs<pre>'.var_export($extArgs,true).'</pre>';
			if(isset($extArgs["whereCols"])){
				#$this->setWhereClause($extArgs["whereCols"]);
				$this->whereCols = $extArgs["whereCols"];
			}
			if(isset($extArgs["extensionTables"])){
				#$this->setWhereClause($extArgs["whereCols"]);
				$this->extensionTables = $extArgs["extensionTables"];
			}
			return true;
		}
		return false;
	}
	/**
	* DESCRIPTOR: getTreeStyle
	* 
	* @access public 
	* @param mixed config
	* @return NULL 
	*/
	public function getTreeStyle(){
		return $this->config["treeStyle"];
	}
	/**
	* DESCRIPTOR:  getCallBackDisplay
	* 
	* @access public 
	* @param null
	* @return NULL 
	*/
	public function getCallBackDisplay(){
		return $this->config["callBackDisplay"];
	}
	/**
	* DESCRIPTOR: setCallBackDisplay 
	* 
	* @access public 
	* @param mixed callBackDisplay
	* @return NULL 
	*/
	public function setCallBackDisplay($callBackDisplay){
		#'treeStyle'	=> 'TEXT' 
		#echo __METHOD__.'@'.__LINE__.'$callBackDisplay<pre>'.var_export($callBackDisplay[1],true).'</pre>';		
		$this->config["callBackDisplay"] = $callBackDisplay;// STATIC: array($classname, $methodname) INSTANCE: array($objectinstance, $methodname)
		return;
	}	
	/**
	* DESCRIPTOR: setWhereClause 
	* 
	* @access protected 
	* @param mixed setAnd
	* @param string prepend
	* @return NULL 
	*/
	protected function setWhereClause($setAnd=true, $prepend=''){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		
		$this->whereCols;
		if(!is_array($this->whereCols)){
			#echo __METHOD__.'@'.__LINE__.'<br>';
			$this->whereClause = '  ';
			return false;
		}
		if($prepend == ''){
			$prepend = 'ST.';
		}
		$this->whereClause = '';
		$i = 0;
		foreach($this->whereCols AS $key => $value){
			#echo __METHOD__.'@'.__LINE__.'<br>';
			if(false === $setAnd && $i == 0){
				$this->whereClause .= ' WHERE ';
			}else{
				$this->whereClause .= ' AND ';
			}
			
			if(is_numeric($value)){
				$this->whereClause .= ' '.$prepend.''.$key.'='.$value.' ';
				#echo __METHOD__.'@'.__LINE__.'<br>';
			}elseif('' == $value || 'NULL' == $value){
				#$value = 'NULL';
				$this->whereClause .= ' '.$prepend.''.$key.' IS NULL ';
			}else{
				#$value = '"'.$value.'"';
				$this->whereClause .= ' '.$prepend.''.$key.'="'.$value.'" ';
			}
			#$this->whereClause .= ' '.$prepend.''.$key.'='.$value.'';
			$i++;
		}
		#echo __METHOD__.'@'.__LINE__.'$this->whereClause<pre>'.var_export($this->whereClause,true).'</pre>';
		#return true;
		return $this->whereClause;
	}
	/**
	* DESCRIPTOR: setUniqueColumns
	* 
	* @access protected 
	* @param string baseColumn
	* @param mixed asInsert
	* @return NULL 
	*/
	protected function setUniqueColumns($baseColumn='', $asInsert=false){
		
		if(!is_array($this->whereCols)){
			#echo __METHOD__.'@'.__LINE__.'<br>';
			#$this->whereClause = '  ';
			return false;
		}

		if(true===$asInsert){
			#$uniqueColList = '`'.$baseColumn.'`';
			foreach($this->whereCols AS $key => $value){
				if(is_numeric($value)){
					$uniqueColList .= ', '.$key.'='.$value.' ';
				}else{
					if('' == $value){
						$value = 'NULL';
					}else{
						$value = '"'.$value.'"';
					}
					$uniqueColList .= ', '.$key.'='.$value.' ';
				}
			}

		}else{
			if($baseColumn == ''){
				return false;
			}			
			$uniqueColList = '`'.$baseColumn.'`';
			foreach($this->whereCols AS $key => $value){
				#echo __METHOD__.'@'.__LINE__.'<br>';
				$uniqueColList .= ',`'.$key.'` ';
			}
		}

		#echo __METHOD__.'@'.__LINE__.'$uniqueColList['.$uniqueColList.']<br>';
		#return true;
		return $uniqueColList;
	}
	/**
	* joinAttributes
	* must return
	* Select extension (add the fields of multiple attributes tables)
	* From extension (add the attributes table)
	* Where extension (scope the look up to FK join)
	* 
	*	SELECT [$structureScopeTable]leftBound, [$structureScopeTable]rightBound [selectExt]
	*	FROM treeStructure [structureScopeTableFrom] [fromExt]
	*	[setWhereClause()]
	*	[whereExt]
	*	ORDER BY leftBound ASC ; 
	*		
	* @access protected 
	* @param mixed setAnd
	* @return NULL 
	*/
	protected function joinAttributes($setAnd=true){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		$result = array();
		$result["selectExt"] = '';
		$result["fromExt"] = '';
		$result["whereExt"] = '';
		$this->extensionTables;
		
		if(!is_array($this->extensionTables)){
			return false;
		}
		/**
		* build the select extension
		*/
		$i = 0;
		$wherePrepend = ' AND ';
		foreach($this->extensionTables AS $key => $value){
			#echo __METHOD__.'@'.__LINE__.'['.$key.']<pre>'.var_export($value,true).'</pre><br>';
			/**
			foreach($value["fields"] AS $key2 => $value2){
				$result["selectExt"] .= ', '.$key.'.'.$value2.' AS '.$key.'_'.$value2.' '."\n";
			}
			#$result["selectExt"] .= ', "'.$key.'" AS extensionTable_'.$i.' ';
			* hard code for now but these need to be config params &&/|| args
			* extensionFKField 
			* extensionTable
			*/
			$result["selectExt"] .= ', "'.$key.'" AS extensionTable , "'.$value["fkField"].'" AS extensionFKField';
			#$result["fromExt"] .= ', '.$key.' ';
			#$result["whereExt"] .= ' '.$wherePrepend.' '.$key.'.'.$value["fkField"].'=ST.'.$this->config["pkField"].' ';
			$i++;
		}
		#echo __METHOD__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre>';
		return $result;
	}
	
	/**
	* 
	* this is a bias towards selecting the whole menu rather than a sub-branch
	* break out functions for these conditions
	* A) if $root is_int 
	* 	- select the root node data inc whereCols [scope a specific tree] (new func to build "from clause")
	* 	- select all the sub-nodes of that tree/branch
	* 	ELSE
	* 	- select the root node data
	* 	- select all sub-nodes of the tree
	* B) if $this->config["treeStyle"] == EXTENDED
	* 	- 
	* 
	* @access public 
	* @param mixed root
	* @return NULL 
	*/
	public function select_tree($root) { 
		/**
		* retrieve the left and right value of the $root node  
		*
		* DEPRICATED in favour of $extArgs["whereCols"]
		* $this->setFromClause($setAnd=true) 
		* $this->extensionTables
		*/ 
		/**
		* High level check first is if the table tree is EXTENDED
		* we'll do this by checking $this->whereCols has values
		* $this->whereCols
		*/
		if(count($this->whereCols) >=1){
			$structureScopeWhere = $this->setWhereClause($setAnd=false);
			$structureScopeAnd = $this->setWhereClause($setAnd=true);
			/**
				SELECT [$structureScopeTable]leftBound, [$structureScopeTable]rightBound [selectExt]
				FROM treeStructure [structureScopeTableFrom] [fromExt]
				[setWhereClause()]
				[whereExt]
				ORDER BY leftBound ASC ; 
			*/
		}
		/**
		* Next we'll do a check on $this->extensionTables to see if we have to append any data 
		*/
		$extVals = array();
		$extVals["selectExt"] = '';
		$extVals["fromExt"] = '';
		$extVals["whereExt"] = '';
		if(count($this->extensionTables) >=1){
			#echo __METHOD__.'@'.__LINE__.' <b>extensionTables</b><br>';
			$extVals = $this->joinAttributes($setAnd=true);
		}
		
		$selectNodeString = ''; // 
		/**
		* was a pk passed?
		*/
		if(is_numeric($root)){
			#echo __METHOD__.'@'.__LINE__.' <b$root</b> ['.$root.']<br>';
			if($this->setWhereClause($setAnd=false) == ''){
				#echo __METHOD__.'@'.__LINE__.' <b>WHERE CLAUSE</b> ***EMPTY***<br>';
				$selectNodeSting = 'WHERE ST.'.$this->config["pkField"].' = '.$root.' ';
			}else{
				$selectNodeSting = 'AND ST.'.$this->config["pkField"].' = '.$root.' ';
			}
			if(1 === $root){
				#echo 'this->config<pre>'.print_r($this->config, true).'</pre>'.LN;		
				#echo 'this->config["treeStyle"]<pre>'.print_r($this->config["treeStyle"], true).'</pre>'.LN;		
			}
		}
		
		$query = 'SELECT ST.'.$this->config["leftBound"].', ST.'.$this->config["rightBound"].' 
			FROM '.$this->config["table"].' AS ST
			'.$this->setWhereClause($setAnd=false).'
			'.$selectNodeString.'
			ORDER BY '.$this->config["leftBound"].' ASC
			LIMIT 0,1;
	   ';
		
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		
		#echo __FUNCTION__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre><br>';
		// now, retrieve all descendants of the $root node  
		
		$query = 'SELECT ST.*, "'.$this->config["table"].'" AS primaryTable '.$extVals["selectExt"].'
			FROM '.$this->config["table"].' AS ST '.$extVals["fromExt"].'
			WHERE ST.'.$this->config["leftBound"].' 
				BETWEEN 
				'.$result[0][$this->config["leftBound"]].' 
				AND 
				'.$result[0][$this->config["rightBound"]].' 
				'.$this->setWhereClause($setAnd=true).'
				'.$extVals["whereExt"].'
			ORDER BY ST.'.$this->config["leftBound"].' ASC;';
		
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		#echo __FUNCTION__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre><br>';
		return $result;
	}
	/**
	* catch all method to render a tree
	* calls the internal text or array methods 
	* or passes off to a handler method
	* 
	* @access public 
	* @param array result
	* @return string result
	*/
	public function render_tree($result) {  
		#echo __METHOD__.'@'.__LINE__.'<br>';
		$this->config["callBackDisplay"];
		$this->outputString = '';
		if($this->config["treeStyle"] == 'TEXT' ){ ///TEXT/SIMPLE/FULL/EXTENDED
			$result = $this->render_tree_text($result);
		}else{
			$result = $this->render_tree_array($result);
		}
		if(null !== $this->config["callBackDisplay"]){
			return call_user_func($this->config["callBackDisplay"], $result);
		}

		return $result;
	}  	
	/**
	* converts the array from a mysql result into a tree array
	* 
	* 
	* @access public 
	* @param array result
	* @param mixed ACL
	* @return this->treeArray
	*/
	public function render_tree_array($result, $ACL = false) {  
		#echo __METHOD__.'@'.__LINE__.'<br>';
		#echo __FUNCTION__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre><br>';
		$treeArray = array();
		$right = array();
		foreach($result AS $key => $row){
			// only check stack if there is one  
			if (count($right)>0) {
				// check if we should remove a node from the stack  0 > count($right) &&
				while ( $right[count($right)-1]["rightBound"]<$row[$this->config["rightBound"]] && count($right) >0) {  
					array_pop($right);  
				}
				/**
				* build the reference to the index in the tree
				*/
				$shortString ='';
				foreach($right AS $key2 => $value){
					$shortString .= '["'.$value["dspShortName"].'"]';
				}
				$indexRef = $shortString.'["'.$row[$this->config["dspShortName"]].'"]';
				
				if($this->config["treeStyle"] == 'SIMPLE'){
					$evalString = '$treeArray'.$indexRef.' = "";';
				}else{
					if(true === $ACL){
						/**
						unset($row["ParentID"]);
						unset($row["MapValue"]);
						unset($row["LeftBound"]);
						unset($row["RightBound"]);
						unset($row["TreeName"]);
						unset($row["RightBound"]);
						*/
						$row2["NodeName"] 		= trim($row[$this->config["dspShortName"]]);
						$row2["TreeName"] 		= $row["TreeName"];
						$row2["MapValue"] 		= $row["MapValue"];
						$row2["MapType"] 		= $row["MapType"];
						$row2["NodeType"] 		= $row["NodeType"];
						$row2["Action"] 		= $row["Action"];
						$row2["Target"] 		= $row["Target"];
						$row2["Access"] 		= $row["Access"];
						$row2["extensionTable"] = $_REQUEST["extensionTable"];
						$row2["nodeDepth"] 		= $row["nodeDepth"];
						
						$evalString = '$treeArray'.$indexRef.' = array("SELF"=>$row2);';
					}else{
						$row["extensionTable"] = $_REQUEST["extensionTable"];
						$evalString = '$treeArray'.$indexRef.' = array("SELF"=>$row);';
					
					}
				}
				$row["nodeDepth"] = count($right);
				$row2["nodeDepth"] = count($right);
				#echo '------$evalString[---***'.$evalString.'***----]<br>';
				eval($evalString);
			}else{
				if($this->config["treeStyle"] == 'SIMPLE'){
					$treeArray[$row[$this->config["dspShortName"]]] = '';
				}else{
					$row["nodeDepth"] = 0;
					$treeArray[$row[$this->config["dspShortName"]]] = array('SELF'=>$row);
				}
			}
			// display indented node title  
			
			// add this node to the stack  
			$right[] = array(
				'rightBound' => $row[$this->config["rightBound"]],
				'dspShortName' => $row[$this->config["dspShortName"]],
			);
		}  
		$this->treeArray = $treeArray;
		return $this->treeArray;
	}  
	/**
	* output a basic text tree with indentation
	* 
	* @access public 
	* @param mixed config
	* @return NULL 
	*/
	function render_tree_text($result) {  
		#echo __METHOD__.'@'.__LINE__.'<br>';
		$this->outputString = '';
		$right = array();
		// display each row  
		foreach($result AS $key => $row){
			// only check stack if there is one  
			if (count($right)>0) {  
				// check if we should remove a node from the stack  
				while ($right[count($right)-1]["rightBound"]<$row[$this->config["rightBound"]]) {  
					array_pop($right);  
				}
			}
			// display indented node title  
			$this->outputString .= str_repeat('  ',count($right)).'['.count($right).']'.$row[$this->config["dspShortName"]].'<!-- ['.$row[$this->config["pkField"]].']---- -->['.$row[$this->config["leftBound"]].']['.$row[$this->config["rightBound"]].'] -=['.$row[$this->config["parentField"]].']=-'."\n";  
			
			// add this node to the stack  
			$right[] = array(
				'rightBound' => $row[$this->config["rightBound"]],
				'dspShortName' => $row[$this->config["dspShortName"]],
			);
		}  
		return $this->outputString;
	} 

	
	
	/**
	* DESCRIPTOR: addNode
	* loads the cfg internally and returns a value of true if all good 
	*
	* @access public 
	* @param int $nodeId 
	* @param array $values 
	* @param bool $child 
	* @return int|bool  
	*/
	public function addNode($nodeId=null, $values=null, $child=false){
		
		if(null===$nodeId || !is_numeric($nodeId)){
			return false;
		}
		/**
		* need to fix this section
		*/
		if(null===$values){
			$values =  array();
			if(isset($_REQUEST[$this->config["dspShortName"]])){
				$values[$this->config["dspShortName"]] = $_REQUEST[$this->config["dspShortName"]];
			}else{
				$values[$this->config["dspShortName"]] = 'NEW_CHILD_'.DATA_UTIL_API::cleanMicrotime();
			}
			
		}else{
			#echo __METHOD__.'@'.__LINE__.'values<br><pre>'.var_export($values, true).'</pre>';
		}
		$query = 'SELECT 
			'.$this->config["pkField"].',
			'.$this->config["parentField"].', 
			'.$this->config["leftBound"].',
			'.$this->config["rightBound"].'
		FROM '.$this->config["table"].' ST
		WHERE '.$this->config["pkField"].' = '.$nodeId.'
		'.$this->setWhereClause($setAnd=true).'; ';
		#echo __METHOD__.'@'.__LINE__.' $query[<pre>'.$query.'</pre>]<br>';
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$row = $result[0];
		
		if(true===$child){
			$parentID = $row[$this->config["pkField"]];
			$checkID = $row[$this->config["leftBound"]];
		}else{
			$parentID = $row[$this->config["parentField"]];
			$checkID = $row[$this->config["rightBound"]];
		}	
		
		$updateQuery1 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["rightBound"].'='.$this->config["rightBound"].'+2 
			WHERE ST.'.$this->config["rightBound"].'>'.$checkID.'
			'.$this->setWhereClause($setAnd=true).'
			ORDER BY ST.'.$this->config["leftBound"].' DESC; ';
			
		$updateQuery2 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["leftBound"].'='.$this->config["leftBound"].'+2 
			WHERE ST.'.$this->config["leftBound"].'>'.$checkID.'
			'.$this->setWhereClause($setAnd=true).'
			ORDER BY ST.'.$this->config["leftBound"].' DESC; ';
		#echo __METHOD__.'@'.__LINE__.' $updateQuery1[<pre>'.$updateQuery1.'</pre>]<br>';
		#echo __METHOD__.'@'.__LINE__.' $updateQuery2[<pre>'.$updateQuery2.'</pre>]<br>';
		
		$this->lockTable($reIndex=true);
			$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $updateQuery1, $args=array('returnArray' => true));
			$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $updateQuery2, $args=array('returnArray' => true));
		$this->unlockTable($reIndex=true);
		
		$uniqueCols = $this->setUniqueColumns($baseColumn='', $asInsert=true);
		$query = 'INSERT INTO '.$this->config["table"].'
		SET '.$this->config["leftBound"].'='.$checkID.'+1, 
			'.$this->config["rightBound"].'='.$checkID.'+2, 
			'.$this->config["dspShortName"].'="'.$values[$this->config["dspShortName"]].'",
			'.$this->config["parentField"].'='.$parentID.'
			'.$uniqueCols.';';
		$result = $GLOBALS["DATA_API"]->create($this->config["DSN"], $query, $args=array('returnArray' => true));
		$insertID = $result["INSERT_ID"];
		$this->addAttributes($insertID,$values);
		return $insertID;
	}
	/**
	* DESCRIPTOR: adds attribute record 
	*
	* @access public 
	* @param int $nodeId
	* @return null 
	*/
	public function addAttributes($nodeId=null){	
		#echo __METHOD__.'@'.__LINE__.'$nodeId['.$nodeId.']<br>';
		/**
		'attributes_a' => array(
			'pkField' => 'id',
			'fkField' => 'tree_id',
			'fields' => array('id',	'tree_id', 'DOMName', 'displayName', 'actionType',	'actionArgs', 'AKeys' )
		),	
		*/
		#echo __METHOD__.'@'.__LINE__.'$this->extensionTables['.var_export($this->extensionTablesm, true).']<br>';
		foreach($this->extensionTables AS $key => $value){
			$query = 'INSERT INTO '.$key.' SET '.$value["fkField"].'='.$nodeId.' ';
			$result = $GLOBALS["DATA_API"]->create($this->config["DSN"], $query, $args=array('returnArray' => true));
			if(isset($result["EXCEPTION"])){
				return $result;
			}
			#$insertID = mysql_insert_id();
		}
		return;
	}
	/**
	* DESCRIPTOR: loads the ini internally and returns a value of true if all good 
	* 
	* @access public 
	* @param int $nodeId
	* @return null 
	*/
	public function deleteNode($nodeId=null){
		$query = 'SELECT ST.* 
		FROM '.$this->config["table"].' ST
		WHERE ST.'.$this->config["pkField"].'='.$nodeId.'
		'.$this->setWhereClause($setAnd=true).'
		;';
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$row = $result[0];
		
		$query = 'DELETE FROM '.$this->config["table"].' WHERE '.$this->config["pkField"].'='.$nodeId.' '.$this->setWhereClause($setAnd=true,$prepend=' ').';';
		$result = $GLOBALS["DATA_API"]->delete($this->config["DSN"], $query, $args=array('returnArray' => true));
		//--------------------
		$this->removeAttributes($nodeId);
		/*
		*/
		$query1 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["rightBound"].'='.$this->config["rightBound"].'-2 
			WHERE ST.'.$this->config["rightBound"].'>'.$row[$this->config["rightBound"]].'
			'.$this->setWhereClause($setAnd=true).'; ';
			#echo __METHOD__.'@'.__LINE__.'$query['.$query.']<br>';
			
		$query2 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["leftBound"].'='.$this->config["leftBound"].'-2 
			WHERE ST.'.$this->config["leftBound"].'>'.$row[$this->config["rightBound"]].'
			'.$this->setWhereClause($setAnd=true).'; ';
		
		$this->lockTable($deIndex=true);
			#mysql_query($query);
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $query1, $args=array('returnArray' => true));
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $query2, $args=array('returnArray' => true));
		$this->unlockTable($reIndex=true);
		#echo __METHOD__.'@'.__LINE__.'$query['.$query.']<br>';
		#	//--------------------

		
		return;
	}	
	
	/**
	* DESCRIPTOR: removes attribute record 
	* 
	* 
	* @access public 
	* @param int $nodeId 
	* @return null 
	*/
	public function removeAttributes($nodeId=null){	
		#echo __METHOD__.'@'.__LINE__.'$nodeId['.$nodeId.']<br>';
		/**
		'attributes_a' => array(
			'pkField' => 'id',
			'fkField' => 'tree_id',
			'fields' => array('id',	'tree_id', 'DOMName', 'displayName', 'actionType',	'actionArgs', 'AKeys' )
		),	
		*/
		#echo __METHOD__.'@'.__LINE__.'$this->extensionTables['.var_export($this->extensionTablesm, true).']<br>';
		foreach($this->extensionTables AS $key => $value){
			$query = 'DELETE FROM '.$key.' WHERE '.$value["fkField"].'='.$nodeId.' ;';
			$result = $GLOBALS["DATA_API"]->create($this->config["DSN"], $query, $args=array('returnArray' => true));
			if(isset($result["EXCEPTION"])){
				return $result;
			}
		}
		return;
	}
	
	/**
	* DESCRIPTOR: loads the ini internally and returns a value of true if all good 
	*
	* @access public 
	* @param mull nodeData
	* @return null 
	*/
	public function unsetNode($nodeData=null){
		#echo __METHOD__.'@'.__LINE__.'$query['.$query.']<br>';
		if(null===$nodeData){
			return false;
		}
		$nodeID 	= $nodeData[$this->config["pkField"]];
		$rightBound = $nodeData[$this->config["rightBound"]];
		
		$query = '
		UPDATE '.$this->config["table"].'  AS ST
		SET ST.'.$this->config["rightBound"].'=99999999999999999999, 
		ST.'.$this->config["leftBound"].'=99999999999999999999			
		WHERE '.$this->config["pkField"].'='.$nodeID.'
		'.$this->setWhereClause($setAnd=true).';
		';
		#echo __METHOD__.'@'.__LINE__.'$query['.$query.']<br>';
		if($result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $query, $args=array('returnArray' => true))){
			//--------------------
			$query1 = 'UPDATE '.$this->config["table"].' AS ST
				SET ST.'.$this->config["rightBound"].'='.$this->config["rightBound"].'-2 
				WHERE ST.'.$this->config["rightBound"].'>'.$rightBound.'
				'.$this->setWhereClause($setAnd=true).'; ';
				#echo __METHOD__.'@'.__LINE__.'$query['.$query.']<br>';
				
			$query2 = 'UPDATE '.$this->config["table"].' AS ST
				SET ST.'.$this->config["leftBound"].'='.$this->config["leftBound"].'-2 
				WHERE ST.'.$this->config["leftBound"].'>'.$rightBound.'
				'.$this->setWhereClause($setAnd=true).'; ';
			
			$this->lockTable($deIndex=true);
				$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query1, $args=array('returnArray' => true));
				$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query2, $args=array('returnArray' => true));
			$this->unlockTable($reIndex=true);
			return $nodeID;
		}
				

		
		return false;
	}
	/**
	* DESCRIPTOR: loads the cfg internally and returns a value of true if all good 
	*
	* @access public 
	* @param int $nodeId 
	* @param arrray $values 
	* @param bool $child 
	* @return null 
	*/
	public function resetNode($nodeId=null, $values=null, $child=true){
		#echo __METHOD__.'@'.__LINE__.'$nodeId['.$nodeId.']$child['.$child.']<br>';
		if(null===$values){
			return false;
			$values =  array();
			$values[$this->config["dspShortName"]] = 'NEW_CHILD_'.DATA_UTIL_API::cleanMicrotime();
		}else{
			#echo __METHOD__.'@'.__LINE__.'values<br><pre>'.var_export($values, true).'</pre>';
			#$nodeId = $nodeData[$this->config["pkField"]];
		}
		
		$query = 'SELECT 
			ST.'.$this->config["pkField"].',
			ST.'.$this->config["parentField"].', 
			ST.'.$this->config["leftBound"].',
			ST.'.$this->config["rightBound"].'
		FROM '.$this->config["table"].' ST
		WHERE '.$this->config["pkField"].' = '.$nodeId.'
		'.$this->setWhereClause($setAnd=true).'
		;
		';
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		#echo __METHOD__.'@'.__LINE__.'$result<pre>'.var_export($result,true).'</pre><br>';
		$row = $result[0];
		
		if(true===$child){
			$parentID = $nodeId;
			$checkID = $row[$this->config["leftBound"]];
			$childID  = $values[$this->config["pkField"]];
		}else{
			$parentID = $row[$this->config["parentField"]];
			$checkID = $row[$this->config["rightBound"]];
			$childID  = $values[$this->config["pkField"]];
			$e = new \Exception();
			echo '$e->getTraceAsString()'.$e->getTraceAsString().'<br>';
		}	

		$updateQuery1 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["rightBound"].'='.$this->config["rightBound"].'+2 
			WHERE ST.'.$this->config["rightBound"].'>'.$checkID.'
			'.$this->setWhereClause($setAnd=true).'
			ORDER BY ST.'.$this->config["leftBound"].' DESC; ';
			#echo __METHOD__.'@'.__LINE__.'$updateQuery1['.$updateQuery1.']<br>';
			
		$updateQuery2 = 'UPDATE '.$this->config["table"].' AS ST
			SET ST.'.$this->config["leftBound"].'='.$this->config["leftBound"].'+2 
			WHERE ST.'.$this->config["leftBound"].'>'.$checkID.'
			'.$this->setWhereClause($setAnd=true).'
			ORDER BY ST.'.$this->config["leftBound"].' DESC; ';
			
		$updateQuery3 = 'UPDATE '.$this->config["table"].' AS ST
			SET 
			ST.'.$this->config["leftBound"].'='.$checkID.'+1,
			ST.'.$this->config["rightBound"].'='.$checkID.'+2 
			ST.'.$this->config["parentField"].'='.$nodeId.'    
			 WHERE ST.'.$this->config["pkField"].'='.$childID.'
			'.$this->setWhereClause($setAnd=true).'
			
			;';
		
		$this->lockTable($reIndex=true);
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $updateQuery1, $args=array('returnArray' => true));
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $updateQuery2, $args=array('returnArray' => true));
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $updateQuery3, $args=array('returnArray' => true));
		$this->unlockTable($reIndex=true);

		
		return $childID;
	}
	
	/**
	* DESCRIPTOR: sortNode
	* loads the cfg internally and returns a value of true if all good 
	* $sortOrder UP/DOWN
	* 
	* @access public 
	* @param int $nodeId 
	* @param string $sortOrder 
	* @return bool 
	*/
	public function sortNode($nodeId=null, $sortOrder='UP'){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		$query = 'SELECT * 
			FROM '.$this->config["table"].' ST
			WHERE ST.'.$this->config["pkField"].'='.$nodeId.'
			'.$this->setWhereClause($setAnd=true).'
		';
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$row = $result[0];
		
		if($sortOrder == 'UP'){
			$operator = '<';
			$sortSQL = 'DESC';
		}elseif($sortOrder == 'DOWN'){
			$operator = '>';
			$sortSQL = 'ASC';
		}else{
			echo __METHOD__.'@'.__LINE__.'$FAIL  sortOrder['.$sortOrder.']['.$nodeId.']<br>';
			return;
		}
 
		$query = '
		SELECT ST.'.$this->config["pkField"].', 
			ST.'.$this->config["parentField"].', 
			ST.'.$this->config["dspShortName"].', 
			ST.'.$this->config["leftBound"].', 
			ST.'.$this->config["rightBound"].'  
			FROM '.$this->config["table"].' ST
			WHERE ST.'.$this->config["leftBound"].' '.$operator.' '.$row[$this->config["leftBound"]].'
			'.$this->setWhereClause($setAnd=true).'
			ORDER BY '.$this->config["leftBound"].' '.$sortSQL.'
			LIMIT 0, 1
		;';
		#	LIMIT 0, 1
		
		$result2 = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$row2 = $result2[0];
		$msg =null;
		if($sortOrder == 'UP' && ($row2[$this->config["rightBound"]] > ($row2[$this->config["leftBound"]] + 1 ))){
			$msg = 'Top Node, cant replace parent:: ['.$row2[$this->config["dspShortName"]].'] SELF['.$row[$this->config["dspShortName"]].']<br>';
		}elseif($sortOrder == 'DOWN' && ($row[$this->config["parentField"]] != $row2[$this->config["parentField"]])){
			$msg = ':Last Node, cant change parent::['.$row2[$this->config["dspShortName"]].'] SELF['.$row[$this->config["dspShortName"]].']<br>';
			//<pre>'.var_export($row,true).'</pre> $row2<pre>'.var_export($row2,true).'</pre>
		}
		if($msg !== null){
			#echo __METHOD__.'@'.__LINE__.'$msg<pre>'.var_export($msg,true).'</pre><br>';
			return $msg;
		}
		$updateQuery1 = '
		UPDATE '.$this->config["table"].'
			SET 
				'.$this->config["leftBound"].'='.$row2[$this->config["leftBound"]].' ,
				'.$this->config["rightBound"].'='.$row2[$this->config["rightBound"]].' 
			WHERE '.$this->config["pkField"].' = '.$row[$this->config["pkField"]].'
			'.$this->setWhereClause($setAnd=true, $prepend='  ').'
		';
		$updateQuery2 = '
		UPDATE '.$this->config["table"].'
			SET 
				'.$this->config["leftBound"].'='.$row[$this->config["leftBound"]].' ,
				'.$this->config["rightBound"].'='.$row[$this->config["rightBound"]].' 
			WHERE '.$this->config["pkField"].' = '.$row2[$this->config["pkField"]].'
			'.$this->setWhereClause($setAnd=true, $prepend='  ').'
		';
		
		#echo 'LOCK?<br>';
		$this->lockTable($deIndex=true);
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $updateQuery1, $args=array('returnArray' => true));
			$result = $GLOBALS["DATA_API"]->update($this->config["DSN"], $updateQuery2, $args=array('returnArray' => true));
		#echo 'UNLOCK?<br>';
		$this->unlockTable($reIndex=true);

		$query = 'SELECT * 
			FROM '.$this->config["table"].' ST
			WHERE '.$this->config["pkField"].'='.$nodeId.'
			'.$this->setWhereClause($setAnd=true).'
		';
		
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		return $result;
	}
	/**
	* DESCRIPTOR: checkIndex
	* 
	* @access public 
	* @param string indexName
	* @return NULL 
	*/
	public function checkIndex($indexName=null){
		#echo __METHOD__.'@'.__LINE__.'CALLED!<br>';
		$query = 'SHOW INDEXES FROM '.$this->config["table"].' ;';//LIKE '.$this->config["leftBound"].'
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		foreach($result AS $key => $value){
			if($value["Key_name"] == $indexName){
				return true;
			}
		}
		#echo __METHOD__.'@'.__LINE__.' <b>RETURN FAIL</b> ['.$indexName.']<br>';
		return false;
	}
	/**
	* DESCRIPTOR: unlockTable
	* 
	* @access public 
	* @param bool reIndex
	* @return mixed result 
	*/
	public function unlockTable($reIndex=false){
		#echo '<b>'.__METHOD__.'@'.__LINE__.'['.$reIndex.']</b>CALLED!<br>';
		if($reIndex === true){
			if(false === $this->checkIndex($this->config["leftBoundIdx"])){
				$colList = $this->setUniqueColumns($this->config["leftBound"]);
				$query = 'ALTER TABLE '.$this->config["table"].' ADD UNIQUE `'.$this->config["leftBoundIdx"].'` ('.$colList.');';
				$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
			}
			if(false === $this->checkIndex($this->config["rightBoundIdx"])){
				$colList = $this->setUniqueColumns($this->config["rightBound"]);
				$query = 'ALTER TABLE '.$this->config["table"].' ADD UNIQUE `'.$this->config["rightBoundIdx"].'` ('.$colList.');';
				$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
				##echo __METHOD__.'@'.__LINE__.'query['.var_export($result, true).']['.$query.']<br>';
			}
		}
		$query = 'UNLOCK TABLES;';
		$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
		return $result;
	}
	/**
	* DESCRIPTOR: lockTable
	* 
	* @access public 
	* @param bool deIndex
	* @return NULL 
	*/
	public function lockTable($deIndex=false){
		#echo '<b>'.__METHOD__.'@'.__LINE__.'['.$deIndex.']</b>CALLED!<br>';
		$query = 'LOCK TABLE '.$this->config["table"].'  WRITE, '.$this->config["table"].' AS ST WRITE;';
		#echo __METHOD__.'@'.__LINE__.'query['.$query.']<br>';
		$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
		if($deIndex===true){
			if(true === $this->checkIndex($this->config["leftBoundIdx"])){
				$query = 'ALTER TABLE '.$this->config["table"].'  DROP INDEX `'.$this->config["leftBoundIdx"].'`;';
				$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
			}
			if(true === $this->checkIndex($this->config["rightBoundIdx"])){
				$query = 'ALTER TABLE '.$this->config["table"].' DROP INDEX `'.$this->config["rightBoundIdx"].'`;';
				$result = $GLOBALS["DATA_API"]->raw($this->config["DSN"], $query, $args=array('returnArray' => true));
			}
		}
		#echo __METHOD__.'@'.__LINE__.'LOCKED!<br>';
		return;
	}
	/**
	* DESCRIPTOR: changeParent
	* 
	* @access public 
	* @param string nodeId
	* @param string newParentId
	* @return NULL 
	*/
	public function changeParent($nodeId=null, $newParentId=null){
		#echo __METHOD__.'@'.__LINE__.'<br>';
		if(null===$nodeId || null===$newParentId){
			return false;
		}
		
		$query = 'SELECT * FROM '.$this->config["table"].' ST WHERE ST.'.$this->config["pkField"].'='.$nodeId.' '.$this->setWhereClause($setAnd=true).';';
		$result = $GLOBALS["DATA_API"]->retrieve($this->config["DSN"], $query, $args=array('returnArray' => true));
		$nodeData = $result[0];
		if($nodeData[$this->config["rightBound"]] != ($nodeData[$this->config["leftBound"]] +1)){
			echo __METHOD__.'@'.__LINE__.' '.$nodeData[$this->config["dspShortName"]].' is a PARENT cant move<br>';
			return false;
		}

		$this->unsetNode($nodeData);
		$nodeId = $this->resetNode($nodeId=$newParentId, $values=$nodeData, $child=true);
		
		return $nodeId;
	}
	
}
?>