<?
/***
* JCORE_SINGLETON
 * Instances can be created for any DB supported by PHP inc. NoSQL
 * 
 * 
 * @author		Jason Medland<jason.medland@gmail.com>
 * @package	JCORE
 * @subpackage CORE
 */
namespace JCORE;
/**
 * Interface JCORE_SINGLETON
 *
 * @package JCORE
*/
abstract class JCORE_SINGLETON 
{
    // Hold an instance of the class
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
    
    // Example method
    public function bark()
    {
        echo 'Woof!['.__METHOD__.']';
    }

    // Prevent users to clone the instance
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

}

#echo __FILE__.':::'.__LINE__.'<br>';
?>