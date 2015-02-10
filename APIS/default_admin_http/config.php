<?
/**
 * update
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_DEFAULT_ADMIN
 */
/**
*
*/
require_once(JCORE_BASE_DIR.'TEMPLATER/TEMPLATER.class.php');

#echo __FILE__.'@'.__LINE__.'<br>';

	$TEMPLATER = new TEMPLATER();

	/*
	* MUST DEFINE TEMPLATE NAME SPACE
	* subtemplates?
	* 
	*/ 
	$ps_template_header = 'header';
	$ps_template_body 	= 'body';
	$ps_template_body2 	= 'body2';
	$ps_template_footer = 'footer';
	/**
	* whether to render sections of the page
	*/
	$header_footer_set = true;
	/**
	* assign the template namespace to specific file
	*/
	$TEMPLATER->set_filenames(array($ps_template_header => JCORE_TEMPLATES_DIR.'HTML/BASIC/header.html'));
	$TEMPLATER->set_filenames(array($ps_template_footer => JCORE_TEMPLATES_DIR.'HTML/BASIC/footer.html'));
	
	/**
	* break out here to call the header routine
	* we'll do the footer @ the bottom
	* first we can call and see if there is some kind of cache set up
	*/
	#if (!isset($TEMPLATER->compiled_code[$ps_template_header]) || empty($TEMPLATER->compiled_code[$ps_template_header])){	}			
	require_once('header.php');
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	$TEMPLATER->assign_vars( array(	
		'FOOTER' => 'FOOTER'
	));	

	switch($_REQUEST["action"]){
		case "action1":
			#action 
			break;
		case "action2":
			//echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			
			$TEMPLATER->set_filenames(array($ps_template_body 	=> JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal.html'));
			$TEMPLATER->set_filenames(array($ps_template_body2 	=> JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal2.html'));
			
			$subTemplater = new TEMPLATER();
			#$subTemplater->set_filenames(array($ps_template_header => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html'));
			$subTemplater->set_filenames(array('sub_'.$ps_template_body => JCORE_TEMPLATES_DIR.'HTML/BASIC/body_minimal.html'));
			#$subTemplater->set_filenames(array($ps_template_footer  => JCORE_TEMPLATES_DIR.'HTML/BASIC/null.html'));
			
			
			//echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			#EXAMPLE_BASIC.basicAPI::function1($subTemplater);
			$EXAMPLE_BASIC = new EXAMPLE_BASIC($subTemplater);
			$EXAMPLE_BASIC->function1('sub_'.$ps_template_body);
			#call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] )
			#call_user_func (  $function='EXAMPLE_BASIC.basicAPI::function1', $subTemplater);
			//echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			$TEMPLATER->assign_vars(array(	
				#'BODY' 	=> $subTemplater->sparse($ps_template_body, true, $retvar = 'returnString')
				'BODY' 	=> $subTemplater->sparse('sub_'.$ps_template_body, true, $retvar = 'returnString')
			));	
			#echo 'here: '.__FILE__.'@'.__LINE__.'<br>';//------------------------------------------------
			break;
		default:
			#action
			break;
	}
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	
	/**
	* break out here to call the footer routine
	*/
	require_once('footer.php');
	$TEMPLATER->assign_vars( array(	
		'FOOTER' => 'FOOTER'
	));	
	#echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	/*
	* Do the final rendering routine
	*/
	if ($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_header, true, $retvar = 'returnString');
	};
	echo $TEMPLATER->sparse($ps_template_body, true, $retvar = 'returnString');
	#echo '<hr>'.$TEMPLATER->sparse('sub_'.$ps_template_body2, true, $retvar = 'returnString').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, 'BUFFER').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, 'FLUSH').'<hr>';
	#echo '<hr>'.$TEMPLATER->render($ps_template_body2, null).'<hr>';
	if ($header_footer_set){ 
		echo $TEMPLATER->sparse($ps_template_footer, true, $retvar = 'returnString');
	};	
echo 'DONE##########<hr><hr><hr><hr>'.__FILE__.'<br>';

//------------------------------------------------------------------------
//------------------------------------------------------------------------



#phpinfo();
#echo __FILE__.'@'.__LINE__.'$link<pre>'.print_r($link, true).'</pre>';
$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
echo __FILE__.'@'.__LINE__.'FILE<hr><hr><hr><hr>'.$loadFile.'<br>';
require_once($loadFile);

$loadFile = JCORE_BASE_DIR.'DAO/TREE/DAO_TREE.class.php';
echo __FILE__.'@'.__LINE__.'FILE<hr><hr><hr><hr>'.$loadFile.'<br>';
require_once($loadFile);
require_once('tree.php');



















echo '!!!END!!!';

?>