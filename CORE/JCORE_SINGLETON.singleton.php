<?php
/**
* JCORE_SINGLETON
 * 
 * 
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * 
 */
namespace JCORE;
/**
 *
 * Class JCORE_SINGLETON
 * @package JCORE
*/
abstract class JCORE_SINGLETON 
{
	/**
     * Hold an instance of the class
	 * 
	 * @access private 
	 * @var mixed
	 */    
	private static $instance;
    
   /*
   // The singleton method
    public static function singleton() 
    {
        echo '<b>singleton['.__METHOD__.']['.__CLASS__.']['.get_class().']</b>';
		if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
       return self::$instance;
    }
   */ 
    
	/**
    * Example method...make some noise
	* 
	* @param NULL
	* @return string
	*/
    public function bark()
    {
        echo 'Woof!['.__METHOD__.']';
    }

	/**
    * Prevent users to clone the instance
	* 
	* @param NULL
	* @return string
	*/
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

}

#echo __FILE__.':::'.__LINE__.'<br>';
?>