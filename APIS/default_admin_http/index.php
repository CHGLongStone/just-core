<? 
/**
OUTLINE
this is the default layout for a standard API, in this case a regular website
the base structure loads content based on the URI passed (used mod-rewrite for pretty URL's)
for efficiency and change managements sake use different API's for different "classes" of task
use one for generating HTML content, use another as CDN (static assets), use another as Media delivery
and others for "true" API's (AJAX, SOAP, etc.)

the base logic is assumed to be a "branching" style and each branch is handled by a central switch statement

ie. trunk starts in this file, the first branch is defined in another switch statement in another file.
this may seem a bit cumbersome but the trade off for managability is well worth it.
if your logical "branches" are defined effectively you will have clean separation in your code and will be able 
to change parts of the implementation with impacting the whole, you can also leverage a "switching" mechanism
where you can decide the "branch" implemented at run time.

TEMPLATER

 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	API_DEFAULT_ADMIN
*/
/**
*
*/
ini_set('error_reporting', E_WARNING);//E_STRICT	E_NOTICE	E_WARNING
ini_set('display_errors', "1");
#echo __FILE__.'@'.__LINE__.'<br>';
require_once('config.php');
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