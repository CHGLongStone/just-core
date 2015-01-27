<?
/**
 * CONFIG_MANAGER (JCORE) CLASS
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	DEFAULT_API
 */
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
#<meta name="{META_TAGS.NAME}" http-equiv="{META_TAGS.HTTP_EQUIV}" content="{META_TAGS.CONTENT}" />
$TEMPLATER->assign_block_vars('META_TAGS', array(	
	'NAME'			=> 'robots',
	'HTTP_EQUIV'	=> null,
	'CONTENT' 		=> 'noindex,nofollow'
));

$TEMPLATER->assign_block_vars('META_TAGS', array(	
	'NAME'			=> null,
	'HTTP_EQUIV'	=> 'Content-Type',
	'CONTENT' 		=> 'text/html; charset=UTF-8'
));
$TEMPLATER->assign_block_vars('META_TAGS', array(	
	'NAME'			=> 'description',
	'HTTP_EQUIV'	=> null,
	'CONTENT' 		=> 'Energy related discussions and news. We want to hear your ideas. Click here to participate!'
));

#//<meta name="{META_TAGS.NAME}" http-equiv="{META_TAGS.HTTP_EQUIV}" content="{META_TAGS.CONTENT}" />
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
#<link rel="{HEAD_CSS.REL}" type="{HEAD_CSS.TYPE}" href="{HEAD_CSS.HREF}" media="{HEAD_CSS.MEDIA}"/>
$TEMPLATER->assign_block_vars('HEAD_CSS', array(	
	'REL'	=> 'canonical',
	'TYPE'	=> null,
	'HREF'	=> 'thesun.selfip.org',
	'MEDIA' => null
));
$TEMPLATER->assign_block_vars('HEAD_CSS', array(	
	'REL'	=> 'stylesheet',
	'TYPE'	=> 'text/css',
	'HREF'	=> 'domain.com/stylesheet.css',
	'MEDIA' => 'screen'
));

#//<link rel="{HEAD_CSS.REL}" type="{HEAD_CSS.TYPE}" href="{HEAD_CSS.HREF}" media="{HEAD_CSS.MEDIA}"/>
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
#<script type="{HEAD_SCRIPT.TYPE}" src="{HEAD_SCRIPT.SRC}">{HEAD_SCRIPT.BODY}</script>
/*
$TEMPLATER->assign_block_vars('HEAD_SCRIPT', array(	
	'SRC'	=> 'stylesheet',
	'TYPE'	=> 'text/javascript',
	'BODY'	=> '//CDATA[???]'."\n". 'var tb_pathToImage = "http://makamashi.com/wp-includes/js/thickbox/loadingAnimation.gif"'
));
*/

$TEMPLATER->assign_block_vars('HEAD_SCRIPT', array(	
	'SRC'	=> 'http://thesun.selfip.org/default_api/assets/scripts/default_http.js',
	'TYPE'	=> 'text/javascript',
	'BODY'	=> ''
));

#//<script type="{HEAD_SCRIPT.TYPE}" src="{HEAD_SCRIPT.SRC}">{HEAD_SCRIPT.BODY}</script>
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------
?>