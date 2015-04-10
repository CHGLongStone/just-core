<?php
/**
 * Auth Harness allows a pass through to a specific auth (authentication/authorization) implementation
 * auth mechanisms are meant to be "plugin based"
 * The harness stores internally any of the implementations loaded till the end of the request if they need to be recalled
 * 
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage	AUTH
 */

namespace JCORE\AUTH;

/**
 * Class AUTH_HARNESS
 *
 * @package JCORE\AUTH
*/
class AUTH_HARNESS{
	/**
	 * @access public 
	 * @var string
	 */
	protected $implementation = array();

}