<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * this is a Modified Preorder Tree Traversal based on the example given here:
 * http://articles.sitepoint.com/article/hierarchical-data-database
 *
 *
 *
 *
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	EXAMPLE
 * @subpackage	BASIC 
 */
/**
*
 * @package	EXAMPLE
 * @subpackage	BASIC
#echo __FILE__.'@'.__LINE__.'<br>' ;
*/
class renderNestedTree
{
	
	/**
	* 
	*/
	public $wdf = 'SHOW IN FUNCITON LIST?';
	/**
	* 
	*/
	protected $treeArray = array();
	//protected $baseTree = array();
	/**
	* 
	*/
	#protected $config = array();
	public $config = array();
	/**
	* 
	*/
	protected $nodeCount = 0;
	/**
	* 
	*/
	protected $PKID = 'id';
	/**
	* 
	*/
	public $templater;
	
	/**
	* 
	*/
	public function __construct($config=null){
		if(null===$config){
			return false;
			/*
			$this->config = array(
				'templater' => null,
				'templates' => array(
					'header' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
					'footer' => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html',
					'parent' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_parent.html',
					'child' => JCORE_TEMPLATES_DIR.'HTML/TREE/basic_tree_child.html'
				)
			);
			*/
		}
		
		#echo __FILE__.'@'.__LINE__.'  $config["templater"]<pre>'.var_export($config,true).'</pre> @'.'<br>';
		if(isset($config["templater"]) && is_object($config["templater"])){
			$this->templater = $config["templater"];
		}else{
			$this->templater = new TEMPLATER(); //$GLOBALS["TEMPLATER"];
		}
		/*
		if(null!==$config["treeObj"] && is_object($config["treeObj"]) && ($config["treeObj"] instanceof 'DAO_TREE')){
			$this->treeObj = $config["treeObj"];
		}else{
			echo __METHOD__.'@'.__LINE__.'['.$config["treeObj"].'] DAO_TREE?['.($config["treeObj"] instanceof 'DAO_TREE').']<br>';	
		}
		*/
		
		if(isset($config["templates"])){
			$this->config["templates"] = $config["templates"];
		}
		if(isset($config["DSN"])){
			$this->config["DSN"] = $config["DSN"];
		}
		/*
		*/
		if(!isset($config["baseTree"])){
			return false;
		}else{
			$this->config["baseTree"] = $config["baseTree"];
			#echo __METHOD__.'@'.__LINE__.'$config["baseTree"]['.var_export($config["baseTree"], true).']<br>';
		}
		
		return true;
	}
						/**
							array (
							  'pk' => '8',
							  'parent_pk' => '2',
							  'title' => 'DEF2',
							  'leftBound' => '5',
							  'rightBound' => '12',
							  'nodeDepth' => 2,
							)
						*/	
	/**
	* 
	*/
	public function renderNode($tree){
		echo __METHOD__.'@'.__LINE__.' ----------------BEGIN-------------------------<br>';
		#echo __METHOD__.'@'.__LINE__.'$tree<pre>'.var_export($tree,true).'</pre>';
		
		if(!is_array($tree)){
			return false;
		}else{
			$this->treeArray = $tree;
		}
		$selfData = $tree["SELF"];
		$outputString = '
		<br>';
		foreach($tree AS $key => $value){
			echo __METHOD__.'@'.__LINE__. '--==--==--$key['.$key.']<b>array_keys<pre>'.var_export(array_keys($value), true).'</pre></b><br>';
			#echo '$key['.$key.']$value['.$value.']<br>';
			#$this->templater->set_filenames(array($ps_template_footer => $this->config['templates']['body']));
			#$this->config['templates']['body'];

			if(is_array($value)){
				if($key != 'SELF'){
					echo str_repeat('&nbsp;&nbsp;',$value["SELF"]["nodeDepth"]+1).'$key['.$key.']nodeDepth['.$value["SELF"]["nodeDepth"].']<br>';
					#$this->renderNode($value);
					#						if($value["SELF"]["title"] == 'DEF2'){
					if(isset($value["SELF"])){
						if(true){
							echo __METHOD__.'@'.__LINE__.'$value["SELF"]<pre>'.var_export($value["SELF"],true).'</pre>';

							#$value["SELF"]["title"]
							$ID = $value["SELF"][$this->PKID].'_'.$value["SELF"]["title"];
							echo '$ID['.$ID.']$this->config["templates"]["body"]['.$this->config['templates']['body'].']<br>';
							$this->templater->set_filenames(array($ID => $this->config['templates']['body']));
							$this->templater->assign_vars(array(	
								'DESC'			=> $value["SELF"]["title"],
								'SPACE'			=> str_repeat('	',$value["SELF"]["nodeDepth"]),
								'DIV_ID'		=> $ID,
								'DIV_CLASS'		=> 'treenode',
								'DIV_STYLE'		=> 'padding-left: 10px; border: 1px solid #FF0000;',
								'LINK_SCRIPT'	=> " onclick=\"pageSingle('".$ID."')\" ",
								'LINK_ID'		=> $value["SELF"]["title"],
								'LINK_NAME'		=> 'Expand',
								'LINK_STYLE'	=> ''
							));	
							$BODY =  $this->renderNode($value);
						}
					}else{
						$ID = 'WDF';
						echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']<pre>'.var_export($value["SELF"],true).'</pre>';
					}		
					if(true){
						echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']$key['.$key.']
						$value<pre>'.var_export(array_keys($value),true).'</pre>
						<b>RECURSE</b> call the next<br>
						';
						/*
						<b>array_keys<pre>'.var_export(array_keys($value), true).'</pre></b>
						$tree['.$key.']<pre>'.var_export($tree[$key],true).'</pre>
						$this->templater->assign_vars(array(	
							'BODY'		=> $this->renderNode($value)
							#'BODY'		=> $this->renderNode($tree[$key])
						));	
						*/
					}
					#$BODY =  $this->renderNode($value);
				}else{// END IF != SELF
					if(true){
						echo __METHOD__.'@'.__LINE__.' 
						== SELF $ID['.$ID.']$key['.$key.']
						$value<pre>'.var_export($value,true).'</pre>
						$tree['.$key.']<pre>'.var_export($tree[$key],true).'</pre>
						<hr>
						<hr>
						<hr>
						'.$outputString.'
						<hr>
						<hr>
						<br>
						<br>
						';
					}
					$ID = '_SELF_';
					$this->templater->set_filenames(array($ID => $this->config['templates']['body']));
					$BODY = '<b>NULL</b><br>';
					#return '<b>NULL</b><br>';
					#return;
					#$BODY = $outputString;
				}
			}else{
				$ID = '_0_';
				$this->templater->set_filenames(array($ID => $this->config['templates']['body']));
				$BODY =  __METHOD__.'@'.__LINE__.$value;
				
				echo '&nbsp;&nbsp;&nbsp;'.str_repeat('&nbsp;&nbsp;',$value["SELF"]["nodeDepth"]+1).'$value['.$value.']<br>';
			}
			if(isset($BODY) && $BODY != ''){
				echo __METHOD__.'@'.__LINE__.' $BODY['.$BODY.']-----$$$$$<br>';
				$this->templater->assign_vars(array(	
					'BODY'		=> $BODY
				));			
			}else{
				echo __METHOD__.'@'.__LINE__.' NO BODY-----$$$$$<br>';
				return;
			}

			echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']$key['.$key.']$value["title"]<pre>'.var_export($value["title"],true).'</pre><br>';
			#echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']$key['.$key.']<pre>'.var_export($value["title"],true).'</pre><br>';
			$outputString .= $this->templater->sparse($ID, true, $retvar = 'returnString');
		
		}
		
		echo __METHOD__.'@'.__LINE__.'<br>';
		#$outputString = str_replace("\'", "'", $outputString);
		return $outputString;
		#echo __METHOD__.'@'.__LINE__.'$tree<pre>'.var_export($tree,true).'</pre>';
		if(true){
		/*
		$this->templater->assign_block_vars('currentNode', array(	
			'XXXXX' => 'ZZZZZZ',
			'XXXXX' => 'ZZZZZZ',
		));
		foreach($result2 AS $key2 => $value2){

			$templater->assign_block_vars('formatData', array(	
				'ATTRIBUTES'			=> $attributeString
			));
			foreach($result2 AS $key2 => $value2){
				#echo ' key2='.$key2.' :: value2=<pre>'.var_export($value2,true).'</pre><br>';
				$templater->assign_block_vars('formatData.attribute', array(	
					'ATTRIBUTE_VALUE'	=> $value2['attributeValue']
				));	
				#$attributeString .= ' '.$value2['attribute'].'="'.$value2['attributeValue'].'"';
			}
		}
		
		foreach($result2 AS $key2 => $value2){

			$templater->assign_block_vars('formatData', array(	
				
				'RECORD_KEY' 			=> $value['format_id'],
				'RECORD_PK' 			=> $value['DbId'],
				'RECORD_VALUE'			=> urldecode($value['format_id']),
				'ATTRIBUTES'			=> $attributeString
			));
			foreach($result2 AS $key2 => $value2){
				#echo ' key2='.$key2.' :: value2=<pre>'.var_export($value2,true).'</pre><br>';
				$templater->assign_block_vars('formatData.attribute', array(	
					'PK' 				=> $value2['DbId'],
					'ENTRY_DBID'		=> $value2['fw_intl_formatNode_DbId'],
					'ATTRIBUTE' 		=> $value2['attribute'],
					'ATTRIBUTE_VALUE'	=> $value2['attributeValue']
				));	
				#$attributeString .= ' '.$value2['attribute'].'="'.$value2['attributeValue'].'"';
			}
		}
		*/
		}
	
	
	}
	
	/**
	* 
	*/
	public function renderNode2($tree){
		echo __METHOD__.'@'.__LINE__.' ----------------BEGIN-------------------------<br>';
		$templater = new TEMPLATER();
		$BASEID = 'BASEID';
		$returnString = '';
		$outerString = '';
		echo __METHOD__.'@<b>'.__LINE__.'</b>['.$this->config['templates']['parent'].']<br>';
		#$templater->set_filenames(array($BASEID => $this->config['templates']['parent']));
		echo __METHOD__.'@<b>'.__LINE__.'</b><br>';
		/***/
		if(is_array($tree)){
			foreach($tree AS $key => $value){
				$parent = false;
				$innerString = '';

				if($key != 'SELF' && is_array($value) && isset($value['SELF'])){
					$parent = true;
					$ID = $value["SELF"][$this->PKID].'_'.$value["SELF"]["title"];
					$DESC  = $value["SELF"]["title"];
					echo __METHOD__.'@'.__LINE__.'PARENT  <b>'.$ID.'</b><br>';
					$templater->set_filenames(array($ID => $this->config['templates']['parent']));
					if(true){
					
						/*
						//set template data
						echo __METHOD__.'@'.__LINE__.'<br>
						$this->config["templates"]["parent"]['.$this->config['templates']['parent'].']<br>
						$ID<b>['.$ID.']</b><br>
						<b>array_keys($value)<pre>'.var_export(array_keys($value), true).'</pre></b>
						<b>array_keys($value["SELF"])<pre>'.var_export(array_keys($value['SELF']), true).'</pre></b>
						
						<br>
						';
						*/
						$this->templater->assign_vars(array(	
							'DESC'			=> $DESC,
							'SPACE'			=> str_repeat('	',$value["SELF"]["nodeDepth"]),
							'DIV_ID'		=> $ID,
							'DIV_CLASS'		=> 'treenode',
							'DIV_STYLE'		=> 'padding-left: 10px; border: 1px solid #FF0000;',
							'LINK_SCRIPT'	=> " onclick=\"pageSingle('".$ID."')\" ",
							'LINK_ID'		=> $value["SELF"]["title"],
							'LINK_NAME'		=> 'Expand',
							'LINK_STYLE'	=> ''
						));	
						#$BODY =  $this->renderNode($value);
						//END set template data
					}

				}elseif($value["title"]){//END IF
					//child [SELF]
					$ID = $value[$this->PKID].'_'.$value["title"];
					$DESC  = $value["title"];
					echo __METHOD__.'@'.__LINE__.'DO SELF:<b>'.$ID.'</b><br>';
					$templater->set_filenames(array($ID => $this->config['templates']['child']));
					if(true){
						/*
						echo __METHOD__.'@'.__LINE__.'<br>
						$ID['.$ID.']<br>
						$this->config["templates"]["child"]['.$this->config['templates']['child'].']<br>
						<b>CHILD::  array_keys($value)<pre>'.var_export(array_keys($value), true).'</pre></b>
						
						<br>
						';
						*/
						$this->templater->assign_vars(array(	
							'DESC'			=> $DESC,
							'SPACE'			=> str_repeat('	',$value["nodeDepth"]),
							'DIV_ID'		=> $ID,
							'DIV_CLASS'		=> 'treenode',
							'DIV_STYLE'		=> 'padding-left: 10px; border: 1px solid #FF0000;',
							'LINK_SCRIPT'	=> " onclick=\"pageSingle('".$ID."')\" ",
							'LINK_ID'		=> $value["title"],
							'LINK_NAME'		=> 'Expand',
							'LINK_STYLE'	=> ''
						));						
					}
				}else{
					echo __METHOD__.'@'.__LINE__.'<br>';
					echo str_repeat('---GDMF**^^',10);
				}
				if(true===$parent){
					echo __METHOD__.'@<b>'.__LINE__.'</b>['.$this->config['templates']['parent'].']<br>';
					$templater->set_filenames(array($BASEID => $this->config['templates']['parent']));
					
					#echo __METHOD__.'@'.__LINE__.'PARENT<br>';
					#$innerString = '-X-<br>';
					foreach($value AS $key2 => $value2){
						if($key2 != 'SELF'){
							/*
							#output .= DOIT
							echo __METHOD__.'@'.__LINE__.'<br>
							<b>CALL NEW::$key2[<b>'.$key2.'</b>]<br>  array_keys($value2)<pre>'.var_export(array_keys($value2), true).'</pre></b><br>';
							#echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']<pre>'.var_export($value["SELF"],true).'</pre>';
							*/
							$innerString .= '<div id="'.$key2.'" style="padding-left: 10px; border: 1px solid #00FF00;">['.__LINE__.'] '.$key2.$this->renderNode2($value2).'<!-- END '.$key2.'--></div><br>';
						}
					}// END FOREACH
					#$innerString .= $this->templater->sparse($ID, true, $retvar = 'returnString');
					#$outerString .= $innerString; 
					$outerString .= '<div id="'.$key.'" style="padding-left: 10px; border: 1px solid #0000FF;">['.__LINE__.']'.$key.$innerString.'<!-- END '.$key2.'--></div>';					
					#echo __METHOD__.'@'.__LINE__.' ---===DONE THE INNER LOOP<br>';
				}else{
					#$innerString .= '-Y-<br>';
				}
				/*
				echo __METHOD__.'@'.__LINE__.'OUTPUT SELF<br>';
				$templater->assign_vars(array(	
					'DESC'		=> $value["title"],
					'BODY'		=> $value["title"].$innerString
				));	
				$outerString = $templater->sparse($ID, true, $retvar = 'returnString').'-=IIII==---'.$outerString;
				#<br>';
				
				*/
				#unset($innerString);
				//unset($outerString);
			}
			
		}else{//END OUTER IF
			
			echo __METHOD__.'@'.__LINE__.'<br>
			';
			echo str_repeat('---WDF',10);
		}
		#echo __METHOD__.'@'.__LINE__.' ---===DONE THE OUTER LOOP===---<br>';
		/**
		
		$this->templater->set_filenames(array($BASEID => $this->config['templates']['parent']));
		$this->templater->assign_vars(array(	
			'DESC'			=> $BASEID,
			'SPACE'			=> '',
			'DIV_ID'		=> $BASEID,
			'DIV_CLASS'		=> 'treenode',
			'DIV_STYLE'		=> 'padding-left: 10px; border: 1px solid #FF0000;',
			'LINK_SCRIPT'	=> " onclick=\"pageSingle('".$BASEID."')\" ",
			'LINK_ID'		=> 'LINK_ID',
			'LINK_NAME'		=> 'Expand',
			'LINK_STYLE'	=> ''
		));		
		$this->templater->assign_vars(array(	
			'BODY'		=> $outerString
		));		
		$returnString = $this->templater->sparse($BASEID, true, $retvar = 'returnString');		
		$outerString = '
		<div id="'.$ID.'" style="padding-left: 10px; border: 1px solid #FF0000;">
			['.__LINE__.']'.$ID.$outerString.'<!-- END '.$ID.'-->
		</div>
		';
		*/
		return $outerString;
	}
/*
	DOIT
	start
	if the node is an array
	output = ''
	if(is_array(node)){
		foreach(node AS key => value){
			if(key != SELF && is_array(value)){
				set template data
				output = ''
				foreach(value AS key2 => value2){
					if(key != SELF){
						output .= DOIT
					}
				}
			}
		}
	}
	
'CONFIG[1][28] -=[1]=-
  PACKAGE[2][17] -=[1]=-
    DEF[3][4] -=[2]=-
    DEF2[5][12] -=[2]=-
      SET_EXT_CHILD_25497700[6][7] -=[8]=-
      SET_EXT_CHILD2_82233400[8][9] -=[8]=-
    SET_EXT_94500100[11][16] -=[2]=-
      SET_EXT2_80651000[11][14] -=[2]=-
  SERVICE[14][27] -=[1]=-
    CACHE_SOURCE[15][22] -=[3]=-
    DATA[17][24] -=[3]=-
    LOG[19][26] -=[3]=-
'
	
	
*/


	/**
	* 
	*/
	public function renderNode3($tree){
		#echo __METHOD__.'@'.__LINE__.'$templater['.$templater.'] ----------------BEGIN-------------------------<br>';
		#echo '<b>array_keys($TEMPLATER->_tpldata = array())<pre>'.var_export(array_keys($templater->_tpldata), true).'</pre></b>';
		if(!isset($this->templater)){
			$this->templater = new TEMPLATER();
		}
		if(!isset($this->subTemplater)){
			$this->subTemplater = new TEMPLATER();
		}
		#$this->templater->destroy();
		$BASEID = 'BASEID';
		$returnString = '';
		$outerString = '';
		if(is_array($tree)){
			foreach($tree AS $key => $value){
				$parent = false;
				$innerString = '';
				$innerString2 = '';
				if($key != 'SELF' && is_array($value) && isset($value['SELF'])){
					$parent = true;
					$ID = $value["SELF"][$this->PKID].'_'.$value["SELF"]["title"];
					$DESC  = $value["SELF"]["title"];
					#echo __METHOD__.'@'.__LINE__.'PARENT  <b>'.$ID.'</b><br>';
					$this->templater->set_filenames(array($ID => $this->config['templates']['parent']));

					#$innerString = '-X-<br>';
					foreach($value AS $key2 => $value2){
						if($key2 != 'SELF' && isset($value2["SELF"])){
							
							$childID = $value2["SELF"][$this->PKID].'_'.$value2["SELF"]["title"];
							$this->subTemplater->set_filenames(array($childID => $this->config['templates']['parent']));
							#$value2["SELF"]["BODY"] = __LINE__.$this->renderNode3($value2);
							$value2["SELF"]["BODY"] = $this->renderNode3($value2);
							$this->setNodeData( $value2["SELF"], $nameSpace=$childID,$outer=false);//currentNode.childNode
							$innerString2 .=  $this->subTemplater->render($childID); //, true, $retvar = 'returnString'
							$this->subTemplater->destroy();
							$innerString .= '<div id="'.$key2.'" style="padding-left: 10px; border: 1px solid #00FF00;">['.__LINE__.'] 
							'.$key2.$this->renderNode3($value2).'<!-- END '.$key2.'--></div><br>
							';
							/*
							echo __METHOD__.'@'.__LINE__.'<b>IF ['.$value["SELF"]["title"].']IF  IF ['.$value2["SELF"]["title"].']</b><br>';
							if($value2["SELF"]["title"] == 'PACKAGE'){
								echo '<hr><hr><b>PACKAGE</b><hr><hr>';
								echo '<hr><hr><b>PACKAGE</b><hr><hr>';
								echo '<hr><hr><b><pre>'.var_export(array_keys($value2["SELF"]), true).'</pre></b><hr><hr>';
								echo '<hr><hr><b><pre>'.var_export($value2["SELF"], true).'</pre></b><hr><hr>';
							}
							*/
						}else{
							#echo __METHOD__.'@'.__LINE__.'<b>CHILD ELSE ['.$value["SELF"]["title"].']IF  IF ['.$value2["SELF"]["title"].']</b><br>';
						
						}
					}// END INNER FOREACH
					#$this->subTemplater->destroy();
					$value["SELF"]["BODY"] = __LINE__.$innerString2;
					$value["SELF"]["BODY"] = $innerString2;
					$this->templater->set_filenames(array($ID => $this->config['templates']['parent']));
					$this->setNodeData($value["SELF"], $nameSpace=$ID,$outer=true);//currentNode.childNode
					$returnString .=  $this->templater->render($ID);
					$innerString2 = '';
					$this->templater->destroy();
					####
					$outerString .= '<div id="'.$key.'" style="padding-left: 10px; border: 1px solid #0000FF;">'."\n".'['.__LINE__.']'.$key.$innerString.' '."\n".'<!-- END '.$key2.'-->'."\n".'</div>';					
					#echo __METHOD__.'@'.__LINE__.'<b>IF ['.$key.']IF  IF ['.$value["SELF"]["title"].']</b><br>';
				}else{		#echo __METHOD__.'@'.__LINE__.' ---===DONE THE INNER LOOP<br>';
					/*
					echo __METHOD__.'@'.__LINE__.'<b>ELSE ['.$key.']-- ['.$value["title"].']</b><br>';//<pre>'.var_export($value, true).'</pre> <pre>'.var_export(array_keys($value), true).'</pre>
					$this->subTemplater->destroy();
					$value["BODY"] = __LINE__.$innerString2;
					$this->templater->set_filenames(array($ID => $this->config['templates']['parent']));
					$this->setNodeData($value, $nameSpace=$ID,$outer=true);//currentNode.childNode
					$returnString =  $this->templater->render($ID);
					*/
				}
				#break;
			}//END OUTER FOR EACH
			$this->templater->destroy();
		}		//END OUTER IF
		#echo __METHOD__.'@'.__LINE__.' ---===DONE THE OUTER LOOP===---<br>';
		#echo '<b>$this->nodeCount['.$this->nodeCount.'] array_keys($this->templater->_tpldata)<pre>'.var_export(array_keys($this->templater->_tpldata), true).'</pre></b>';
		#echo '<b>array_keys($this->templater->_tpldata)<pre>'.var_export(array_keys($this->templater->_tpldata["currentNode."]), true).'</pre></b>';
		
		return 		$returnString;
		#return $outerString; //.'<hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr>'.$returnString;
	}

	#public function setNodeData(&$templater, $values, $nameSpace,$outer=true){//currentNode.childNode
	/**
	* 
	*/
	public function setNodeData($values, $nameSpace, $outer=true){//currentNode.childNode
		if($this->nodeCount>55){
			return;
		}
		$this->nodeCount++;
		if(true===$outer){
			$nodeSpace = 'currentNode';
			$borderColour = '#FF0000';
		}else{
			$borderColour = '#00FF00';
			$nodeSpace = 'currentNode.childNode';
		}
		$nodeSpace = 'currentNode';
		#$this->templater->set_filenames(array($nameSpace => $this->config['templates']['parent']));
		$DIV_ID = $values["attributes_b_displayName"].'_'.$values[$this->PKID];
		$LINK_ID = $values["attributes_b_displayName"].'__'.$values[$this->PKID];
		$props = $values;
		unset($props["BODY"]);
		
		$args["id"] = 'NEW_PARENT_'.$values[$this->PKID].'';
		$args["class"] = 'class';
		$args["title"] = 'SELECT NEW PARENT';
		$selectString = $this->renderSelectTree($tree=null, $args);
		#$selectString = 'selectString'; //$this->renderSelectTree($tree=null, $args);
		#echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']<pre>'.var_export($selectString,true).'</pre>';
		$blockVars = array(	
			'DESC'			=> $values["attributes_b_displayName"].'['.$values["title"].']',
			'SPACE'			=> '',
			'DIV_ID'		=> $DIV_ID,
			'DIV_CLASS'		=> 'treenode',
			'DIV_STYLE'		=> 'padding-left: 10px; border: 1px solid '.$borderColour.';',
			'LINK_SCRIPT'	=> " onclick=\"pageSingle('".$DIV_ID."')\" ",
			'LINK_ID'		=> $LINK_ID,
			'LINK_NAME'		=> ' Expand', //$values["SELF"]["title"].
			'LINK_STYLE'	=> '',
			'BODY'			=> $values["BODY"],
			#'PROPS'			=> '<pre>'.htmlspecialchars(var_export($values,true)).'</pre>',
			#'PROPS'			=> '<pre>'.var_export($values,true).'</pre>',
			'FORM_ACTION'		=> '../SOA/',
			'PROPS'			=> htmlspecialchars(var_export($props,true)),
			'PROP_PK'			=>	$values[$this->PKID],
			'PROP_PARENT_PK'	=>	$values["parent_id"],
			'PROP_LEFT_BOUND'	=>	$values["leftBound"],
			'PROP_RIGHT_BOUND'	=>	$values["rightBound"],
			'PROP_NODE_DEPTH'	=>	$values["nodeDepth"],//PROP_NODE_DEPTH
			'PROP_SET_PARENT'	=>	$selectString,
			
			'PROP_TreeName'			=>	$values["TreeName"],//PROP_NODE_DEPTH
			'PROP_MapValue'			=>	$values["MapValue"],//PROP_NODE_DEPTH
			#'PROP_MENU_ID'		=>	$values["menuID"],//PROP_NODE_DEPTH
			'PROP_X'			=> ''
		);
		/*
		echo __METHOD__.'@'.__LINE__.'$ID['.$ID.']<br>
		$nameSpace['.$nameSpace.']<br>
		$nodeSpace['.$nodeSpace.']<br>
		$values["SELF"] <pre>'.var_export($values["SELF"],true).'</pre>
		$blockVars <pre>'.var_export($blockVars,true).'</pre>
		';
	
		*/
		if(true===$outer){
			$this->templater->assign_block_vars($nodeSpace, $blockVars);		
			#$returnString = $this->templater->sparse($BASEID, true, $retvar = 'returnString');		
		}else{
			$this->subTemplater->assign_block_vars($nodeSpace, $blockVars);		
			#$returnString = $this->templater->sparse($BASEID, true, $retvar = 'returnString');		
		}
		#echo __METHOD__.'@'.__LINE__.'$returnString<br>['.$returnString.']<br>';
		#echo '<b>array_keys($TEMPLATER->_tpldata = array())<pre>'.var_export(array_keys($templater->_tpldata), true).'</pre></b>';

	}
	

	
	
	
	/**
	* 
	*/
	public function renderNode4($tree){
		#echo __METHOD__.'@'.__LINE__.' ----------------BEGIN-------------------------<br>';
		$BASEID = 'BASEID';
		$returnString = '';
		$outerString = '';
		if(is_array($tree)){
			foreach($tree AS $key => $value){
				$parent = false;
				$innerString = '';
				$innerString2 = '';
				if($key != 'SELF' && is_array($value) && isset($value['SELF'])){
					$parent = true;
					$ID = $value["SELF"][$this->PKID].'_'.$value["SELF"]["title"];
					$DESC  = $value["SELF"]["title"];
					#echo __METHOD__.'@'.__LINE__.'PARENT  <b>'.$ID.'</b><br>';
					#$templater->set_filenames(array($ID => $this->config['templates']['parent']));

					#$innerString = '-X-<br>';
					foreach($value AS $key2 => $value2){
						if($key2 != 'SELF'){

							$innerString .= '<div id="'.$key2.'" style="padding-left: 10px; border: 1px solid #00FF00;">['.__LINE__.'] 
							'.$key2.$this->renderNode4($value2).'<!-- END '.$key2.'--></div><br>
							';
						}
					}// END INNER FOREACH

					$outerString .= '<div id="'.$key.'" style="padding-left: 10px; border: 1px solid #0000FF;">
						['.__LINE__.']'.$key.$innerString.'<!-- END '.$key2.'-->
					</div>';					
					#echo __METHOD__.'@'.__LINE__.' ---===DONE THE INNER LOOP<br>';
				}else{		#echo __METHOD__.'@'.__LINE__.' ---===DONE THE INNER LOOP<br>';
					#echo __METHOD__.'@'.__LINE__.'<b>ELSE ['.$value["title"].']</b><br>';//<pre>'.var_export($value, true).'</pre> ELSE  ELSE  ELSE  ELSE   '.$key.'
				}
				#break;
			}//END OUTER FOR EACH
			
		}		//END OUTER IF

		#return 		$returnString;
		return $outerString; //.'<hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr>'.$returnString;
	}
	
	
	/**
	* render_tree_array
	*/
	public function renderSelectTree($tree=null, $args=null){
		#echo __METHOD__.'@'.__LINE__.'---------------BEGIN-------------------------<br>';
		if(!isset($tree)){
			#$this->optionList = $this->renderSelectTreeOptions($tree);
			#echo __METHOD__.'@'.__LINE__.' ---------------USE $this->config["baseTree"]--<pre>'.var_export($this->config["baseTree"],true).'</pre>-----------------------<br>';
			$tree = $this->config["baseTree"];
		}
		if(!isset($this->optionList)){
			$this->optionList = $this->renderSelectTreeOptions($tree);
			#echo __METHOD__.'@'.__LINE__.' ---------------CREATE $this->optionList['.$this->optionList.']-------------------------<br>';
		}
		#echo __METHOD__.'@'.__LINE__.'$this->optionList<pre>'.var_export($this->optionList,true).'</pre>';
		$id = '';
		if(isset($args["id"])){
			$id = $args["id"];
			
		}
		$class = '';
		if(isset($args["class"])){
			$class = $args["class"];
			
		}
		$title = '';
		if(isset($args["title"])){
			$title = $args["title"];
			
		}
		/*
		*/
		
		#return 		$returnString;
		#echo __METHOD__.'@'.__LINE__.' ---===????<br><b>outerString</b>';
		$outerString = '
		<select name="'.$id.'" id="'.$id.'" class="'.$class.'"  title="'.$title.'" >
		'.$this->optionList.'
		</select>
		';
	
		return $outerString;
	}
	
	
	/**
	* 
	*/
	public function renderSelectTreeOptions($tree, $recurse=false){
		if($recurse===false){
		}
		#echo __METHOD__.'@'.__LINE__.' BEGIN-------------------------<br>';
		#echo __METHOD__.'@'.__LINE__.' ---------------USE $tree--<pre>'.var_export($tree,true).'</pre><br>';
		
		$BASEID = 'BASEID';
		$returnString = '';
		$outerString = '';
		if(is_array($tree)){
			foreach($tree AS $key => $value){
				$parent = false;
				$innerString = '';
				$innerString2 = '';
				#echo __METHOD__.'@'.__LINE__.' $key['.$key.']$value['.var_export(array_keys($value), true).']<br>';
				if($key != 'SELF' && is_array($value) && isset($value['SELF'])){
					$parent = true;
					$ID = $value["SELF"][$this->PKID].'_'.$value["SELF"]["title"];
					$DESC  = $value["SELF"]["title"];
					#echo __METHOD__.'@'.__LINE__.'PARENT  <b>'.$ID.'</b><br>';
					#$templater->set_filenames(array($ID => $this->config['templates']['parent']));

					#$innerString = '-X-<br>';
					foreach($value AS $key2 => $value2){
						if($key2 != 'SELF'){
							#;
							$spacer = str_repeat('&nbsp;&nbsp;',$value2["SELF"]["nodeDepth"]);
							$innerString .= '<option value="'.$value2["SELF"][$this->PKID].'" >'.$spacer.$value2["SELF"]["title"].'</option>'."\n".$this->renderSelectTreeOptions($value2, $sub_recurse=true);
						}
					}// END INNER FOREACH
					$spacer = str_repeat('&nbsp;&nbsp;',$value["SELF"]["nodeDepth"]);
					$outerString .= '<option VALUE="'.$value["SELF"][$this->PKID].'" >'.$spacer.$value["SELF"]["title"].'</option>'."\n".$innerString;
				}
				
			}//END OUTER FOR EACH
			
		}		//END OUTER IF
		#echo __METHOD__.'@'.__LINE__.' $outerString['.$outerString.']-------------------------<br>';
		return $outerString;
	}
	
	
	
	
	
	
}
?>