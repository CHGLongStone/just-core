<?php 
/**
 * HTTP UTILITIES 
 *
 * @author	Jason Medland<jason.medland@gmail.com>
 * @package	JCORE\TRANSPORT
 * 	 
 */

namespace JCORE\TRANSPORT\HTTP;


/**
 * Class  HTTP_API
 *
 * @package JCORE\TRANSPORT\HTTP
*/
class HTTP_UTIL {
	
	/**
	* DESCRIPTOR:  
	* 
	* @param param bool NULL
	* @return return bool NULL
	*/
	public function __construct(){

	}

	/**
	 * lifted directly from http://blackbe.lt/advanced-method-to-obtain-the-client-ip-in-php/
	 * implementation needs an update for IPV6 addresses
	 * 
	 * Retrieves the best guess of the client's actual IP address.
	 * Takes into account numerous HTTP proxy headers due to variations
	 * in how different ISPs handle IP addresses in headers between hops.
	 * 
	 * @access public 
	 * @param NULL
	 * @return string serverIP
	 */
	public static function get_ip_address() {
		// check for shared internet/ISP IP
		if (!empty($_SERVER['HTTP_CLIENT_IP']) && \JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		// check for IPs passing through proxies
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// check if multiple ips exist in var
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
				$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				foreach ($iplist as $ip) {
					if (\JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($ip)){
						return $ip;
					}
				}
			} else {
				if (\JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])){
					return $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
			}
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED']) && \JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_X_FORWARDED'])){
			return $_SERVER['HTTP_X_FORWARDED'];
		}
		if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && \JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])){
			return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		}
		if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && \JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_FORWARDED_FOR'])){
			return $_SERVER['HTTP_FORWARDED_FOR'];
		}
		if (!empty($_SERVER['HTTP_FORWARDED']) && \JCORE\TRANSPORT\HTTP\HTTP_UTIL::validate_ip($_SERVER['HTTP_FORWARDED'])){
			return $_SERVER['HTTP_FORWARDED'];
		}

		// return unreliable ip since all else failed
		return $_SERVER['REMOTE_ADDR'];
	}

	
	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 * @access public 
	 * @param string $ip 
	 * @return bool 
	 */
	public static function validate_ip($ip) {
		if (strtolower($ip) === 'unknown'){
			return false;
		}
		// generate ipv4 network address
		$ip = ip2long($ip);

		// if the ip is set and not equivalent to 255.255.255.255
		if ($ip !== false && $ip !== -1) {
			// make sure to get unsigned long representation of ip
			// due to discrepancies between 32 and 64 bit OSes and
			// signed numbers (ints default to signed in PHP)
			$ip = sprintf('%u', $ip);
			// do private network range checking
			if ($ip >= 0 && $ip <= 50331647){ 
				return false;
			}
			if ($ip >= 167772160 && $ip <= 184549375){ 
				return false;
			}
			if ($ip >= 2130706432 && $ip <= 2147483647){ 
				return false;
			}
			if ($ip >= 2851995648 && $ip <= 2852061183){ 
				return false;
			}
			if ($ip >= 2886729728 && $ip <= 2887778303){ 
				return false;
			}
			if ($ip >= 3221225984 && $ip <= 3221226239){ 
				return false;
			}
			if ($ip >= 3232235520 && $ip <= 3232301055){ 
				return false;
			}
			if ($ip >= 4294967040){ 
				return false;
			}
		}
		return true;
	}

	/**
	 * gets a top level domain from a url
	 * @access public 
	 * @param string url 
	 * @return string tld
	 */
	public static function get_tld($url) {
		$tld = parse_url($url,PHP_URL_HOST);
		if('' == $tld){
			$parts = parse_url($url);
			if(isset($parts["host"])){
				$tld = $parts["host"];
			}else{
				$tld = $parts["path"];
			}
		}
		#echo 'tld <pre>'.var_export($tld,true).' </pre>'.PHP_EOL;
		
		/**
		* if usage is as expected the $GLOBALS['CONFIG_MANAGER'] will be available
		* and we will use the TOP_LEVEL_DOMAIN regex filter 
		* if not we set a default
		*/
		$regexPattern = '';
		if(isset($GLOBALS['CONFIG_MANAGER']) && method_exists($GLOBALS['CONFIG_MANAGER'], 'getSetting' )){
			$config = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'REGEX', 'FILTERS');
			if(!isset($config["TOP_LEVEL_DOMAIN"])){
				$regexPattern = '';
			}
		}
		if('' == $regexPattern){
			$regexPattern = '/^(?:(?:http[s]?|ftp):\/)?\/?(?:[^:\/\s]+?\.)*([^:\/\s]+\.[^:\/\s]+)/';
		}
		

		/**
		* if we can't parse cleanly we'll try to return a less degraded example
		*/
		$matches = array();
		preg_match($regexPattern, $tld, $matches);
		#echo 'tld <pre>'.var_export($tld,true).' </pre>'.PHP_EOL;
		#echo 'matches <pre>'.var_export($matches,true).' </pre>'.PHP_EOL;
		if(isset($matches[1]) && '' != $matches[1]){
			return $matches[1];
		}
		if(isset($matches[0]) && '' != $matches[0]){
			return $matches[0];
		}
			
		
		return $tld;
		
	}
	
	/**
	 * gets a top level domain from a url
	 * @access public 
	 * @param string url 
	 * @return string tld
	 */
	public static function stripSubDomain($url) {
		#echo 'config url <pre>'.var_export($url,true).' </pre>---'.PHP_EOL;
		
		$tld = parse_url($url,PHP_URL_HOST);
		#echo 'config tld <pre>'.var_export($tld,true).' </pre>---'.PHP_EOL;
		if('' !== $tld){
			$fragments = explode('.',$tld);
			#echo 'config fragmentsz <pre>'.var_export($fragments,true).' </pre>---'.PHP_EOL;
			$i = 1;
			$tld_string = '';
			
			/*
			*/
			while($i < count($fragments) ){
				#echo 'config $fragments[$i] <pre>'.var_export($fragments[$i],true).' </pre>---'.PHP_EOL;
				$tld_string = $tld_string.'.'.$fragments[$i];
				#echo 'config tld_string <pre>'.var_export($tld_string,true).' </pre>---'.PHP_EOL;
				$i++;
			}
			#echo 'config tld_string <pre>'.var_export($tld_string,true).' </pre>---'.PHP_EOL;
		}
		
		
		
		#$config = $GLOBALS['CONFIG_MANAGER']->getSetting($LOAD_ID = 'REGEX', 'MATCHES');
		
		
		return $tld_string;
		
	}
	
}

?>