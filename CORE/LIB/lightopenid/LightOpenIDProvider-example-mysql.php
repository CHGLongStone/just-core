<?php
/**
 * This example shows several things:
 * - How a setup interface should look like.
 * - How to use a mysql table for authentication
 * - How to store associations in mysql table, instead of php sessions.
 * - How to store realm authorizations.
 * - How to send AX/SREG parameters.
 * For the example to work, you need to create the necessary tables:
CREATE TABLE Users (
    id INT NOT NULL auto_increment PRIMARY KEY,
    login VARCHAR(32) NOT NULL,
    password CHAR(40) NOT NULL,
    firstName VARCHAR(32) NOT NULL,
    lastName VARCHAR(32) NOT NULL
);

CREATE TABLE AllowedSites (
    user INT NOT NULL,
    realm TEXT NOT NULL,
    attributes TEXT NOT NULL,
    INDEX(user)
);

CREATE TABLE Associations (
    id INT NOT NULL PRIMARY KEY,
    data TEXT NOT NULL
);
 *
 * This is only an example. Don't use it in your code as-is.
 * It has several security flaws, which you shouldn't copy (like storing plaintext login and password in forms).
 *
 * This setup could be very easily flooded with many associations, 
 * since non-private ones aren't automatically deleted.
 * You could prevent this by storing a date of association and removing old ones,
 * or by setting $this->dh = false;
 * However, the latter one would disable stateful mode, unless connecting via HTTPS.
 */
require_once JCORE_BASE_DIR.'LIB/lightopenid/LightOpenIDProvider.php';
/*
require_once OPENID_LIB_PATH.'LightOpenIDProvider.php';
mysql_connect();
mysql_select_db('test');
*/


class MysqlProvider extends LightOpenIDProvider
{
	const USER_TABLE = 'OASIS_ACCOUNTS';
	const USR_PK_COLUMN = 'OasisID';//Primary Key column
	const USR_COLUMN = 'Username'; 
	const PWD_COLUMN = '`Password`';
	private $attrMap = array(
        'namePerson/first'    => 'First name',
        'namePerson/last'     => 'Last name',
        'namePerson/friendly' => 'Nickname (login)'
        );
    
    private $attrFieldMap = array(
        'namePerson/first'    => self::USR_COLUMN,
        'namePerson/last'     => self::USR_COLUMN,
        'namePerson/friendly' => self::USR_COLUMN
        );
	
	function validateUser($handle){
		#echo 'self::USER_TABLE['.self::USER_TABLE.']';
		#$userTable = self::USER_TABLE;
		/**
  'PHP_AUTH_USER' => 'testL',
  'PHP_AUTH_PW' => 'testP',
		*/
		if(isset($_POST['login'],$_POST['password'])) {
			$login = mysql_real_escape_string($_POST['login']);
			$password = sha1($_POST['password']);
			$query = 'SELECT * FROM '.self::USER_TABLE.' WHERE '.self::USR_COLUMN.' = "'.$login.'" AND '.self::PWD_COLUMN.' = "'.$password.'" ;';
			#echo '$query<pre>'.var_export($query,true).'</pre>';
			$q = mysql_query($query);
			if($data = mysql_fetch_assoc($q)) {
				return $data;
			}
			if($handle) {
				echo 'Wrong login/password.';
			}
		}elseif($handle){
			/* to validate the handle we need to hit the associations table
			$query = 'SELECT * FROM '.self::USER_TABLE.' WHERE '.self::USR_COLUMN.' = "'.$this->login.'" AND '.self::PWD_COLUMN.' = "'.$this->password.'" ;';
			#echo '$query<pre>'.var_export($query,true).'</pre>';
			$q = mysql_query($query);
			if($data = mysql_fetch_assoc($q)) {
				return $data;
			}
			*/
		}
		return false;
	}
	function getUserData($handle=null)
	{

		if($handle) {
			echo '
			<form action="'.$this->serverLocation.'" method="post">
			Login: <input type="text" name="login"><br>
			Password: <input type="password" name="password"><br>
			handle: <input type="text" name="openid.assoc_handle" value="'.$handle.'">[hide this]<br>
			<button>Submit</button>
			</form>
			';
			#die();
			return;
		}
	}
    function setup($identity, $realm, $assoc_handle, $attributes)
    {
        #$data = getUserData($assoc_handle);
		#echo __METHOD__.'$attributes<pre>'.var_export($attributes,true).'</pre>';
		/***
		* set the user if we can
		*/
		$login = '';
		if(isset($this->login)){
			$login = $this->login;
		}elseif(isset($_POST["login"])){
			$login = $this->login = $_POST["login"];
		}
		/***
		* set the password if we can
		*/	
		$password = '';
		if(isset($this->password)){
			$password = $this->password;
		}elseif(isset($_POST["password"])){
			$password = $this->password = $_POST["password"];
		}
		
        echo '<form action="#" method="post" name="openidLogin" id="openidLogin">'
           . '<input type="hidden" name="openid.assoc_handle" value="' . $assoc_handle . '">'
           . '<input type="text" name="login" id="login" value="' . $login .'"><br>'
           . '<input type="text" name="password" id="password" value="' . $password .'"><br>'
           . "<b>$realm</b> wishes to authenticate you.<br>";
        if($attributes['required'] || $attributes['optional']) {
            echo " It also requests following information (required fields marked with *):"
               . '<ul>';
            
			#echo '$this->attrMap<pre>'.var_export($this->attrMap,true).'</pre>';
            foreach($attributes['required'] as $attr) {
                #echo '$attr<pre>'.var_export($attr,true).'</pre>';
				if(isset($this->attrMap[$attr])) {
                    echo '<li>'
                       . '<input type="checkbox" name="attributes[' . $attr . ']" checked> '
                       . $this->attrMap[$attr] . '(*)</li>';
                }
            }
            
            foreach($attributes['optional'] as $attr) {
                if(isset($this->attrMap[$attr])) {
                    echo '<li>'
                       . '<input type="checkbox" name="attributes[' . $attr . ']"> '
                       . $this->attrMap[$attr] . '</li>';
                }
            }
            echo '</ul>';
        }
        echo '<br>'
           . '<button name="once" id="once">Allow once</button> '
           . '<button name="always" id="always">Always allow</button> '
           . '<button name="cancel" id="cancel">cancel</button> '
           . '</form>';
		if($login != '' && $password != ''){
			echo '
			<script type="text/javascript">
				var pwd = document.getElementById(\'login\').value;
				var usr = document.getElementById(\'password\').value;
				if(pwd != \'\' && usr != \'\'){
					document.getElementById(\'always\').click();
				}
			</script>
			';
		
		}
    }
    
    function checkid($realm, &$attributes)
    {
        if(isset($_POST['cancel'])) {
            $this->cancel();
        }
        #echo __METHOD__.'::'.__LINE__.'$_POST<pre>'.var_export($_POST,true).'</pre>';
		
        #echo __METHOD__.'::'.__LINE__.'***<br>';
        $data = $this->validateUser($this->assoc_handle());
		#echo __METHOD__.'::'.__LINE__.'$data<pre>'.var_export($data,true).'</pre>';
        if(!$data) {
            return false;
        }
       # echo __METHOD__.'::'.__LINE__.'***<br>';
        $realm = mysql_real_escape_string($realm);
		#self::USR_PK_COLUMN;
		$query = 'SELECT attributes FROM AllowedSites WHERE user = '.$data[self::USR_PK_COLUMN].' AND realm = "'.$realm.'"';
        #echo __METHOD__.'::'.__LINE__.'*['.$query.']<br>';
		/**
		*/
        $q = mysql_query($query);
        
        $attrs = array();
        if($result = mysql_fetch_row($q)) {
			echo __METHOD__.'::'.__LINE__.'LOOKUP<br>';
			#echo __METHOD__.'::'.__LINE__.'$result<pre>'.var_export($result,true).'</pre>';
			#echo __METHOD__.'::'.__LINE__.'$attrs<pre>'.var_export($attrs,true).'</pre>';
            $attrs = explode(',', $result[0]);
        } elseif(isset($_POST['attributes'])) {
			#echo __METHOD__.'::'.__LINE__.'_POST<br>';
            $attrs = array_keys($_POST['attributes']);
			#echo __METHOD__.'::'.__LINE__.'$attrs<pre>'.var_export($attrs,true).'</pre>';
        } elseif(!isset($_POST['once']) && !isset($_POST['always'])) {
            return false;
        }
        #echo __METHOD__.'::'.__LINE__.'***<br>';
		#echo __METHOD__.'::'.__LINE__.'$attrs<pre>'.var_export($attrs,true).'</pre>';
		#echo __METHOD__.'::'.__LINE__.'$this->attrFieldMap<pre>'.var_export($this->attrFieldMap,true).'</pre>';
        $attributes = array();
        foreach($attrs as $attr) {
			#echo __LINE__.'***$attr['.$attr.']';
            if(isset($this->attrFieldMap[$attr])) {
                $attributes[$attr] = $data[$this->attrFieldMap[$attr]];
            }else{
				#echo __METHOD__.'::'.__LINE__.'FAILED LOOKUP['.$attr.']<br>';
			}
        }
        #echo __METHOD__.'::'.__LINE__.'***$attributes<pre>'.var_export($attributes,true).'</pre>';
        #echo __METHOD__.'::'.__LINE__.'***$data<pre>'.var_export($data,true).'</pre>';
        #echo __METHOD__.'::'.__LINE__.'***$this->attrFieldMap<pre>'.var_export($this->attrFieldMap,true).'</pre>';
        #echo __METHOD__.'::'.__LINE__.'<hr><hr><hr><hr><hr><hr>';
		
        if(isset($_POST['always'])) {
           # echo '$attributes<pre>'.var_export($attributes,true).'</pre>';
			#$attrs = $attributes;
			$attrs = mysql_real_escape_string(implode(',', array_keys($attributes)));
           #echo '$attrs<pre>'.var_export($attrs,true).'</pre>';
			if(isset($attrs) && $attrs != ''){
				$query = 'REPLACE INTO AllowedSites (user, realm, attributes) VALUES('.$data[self::USR_PK_COLUMN].', "'.$realm.'", "'.$attrs.'")';
			#	echo __METHOD__.'::'.__LINE__.'*['.$query.']<br>';
				mysql_query($query);
			
			}
        }
        
       # echo __METHOD__.'::'.__LINE__.'['.$this->serverLocation . '?' . $data[self::USR_COLUMN].']<br>';
        return $this->serverLocation . '?' . $data[self::USR_COLUMN];
    }
    
    function assoc_handle($handle=null, $UserID=null)
    {
		# We generate an integer assoc handle, because it's just faster to look up an integer later.
        if(is_numeric($UserID)){
			$query = 'SELECT hash_id FROM Associations WHERE OasisID = "'.$UserID.'"';
			$q = mysql_query($query);
			$result = mysql_fetch_row($q);
			#echo __METHOD__.'::'.__LINE__.'$result<pre>'.var_export($result,true).'</pre>';
			if(isset($result[0])){
				return $result[0];
			}
		}
        if(is_string($handle)){
			#echo __METHOD__.'::'.__LINE__.'is_string($handle)<br>';
			$q = mysql_query('SELECT id FROM Associations WHERE hash_id = "'.$handle.'"');
			$result = mysql_fetch_row($q);
			#echo __METHOD__.'::'.__LINE__.'$result<pre>'.var_export($result,true).'</pre>';
			
		}
		$q = mysql_query("SELECT MAX(id) FROM Associations");
        $result = mysql_fetch_row($q);
		#echo '$result<pre>'.var_export($result,true).'</pre>';
		$handle = $result[0]+1;
        return $handle;
    }
    
    function setAssoc($handle, $data, $UserID=null)
    {
		$this->assoc_handle = $handle;
		$data = mysql_real_escape_string(serialize($data));
		#	$data = serialize($data);
		if(is_numeric($UserID)){
			$query = 'REPLACE INTO Associations (OasisID, hash_id, data) VALUES( '.$UserID.', "'.$handle.'", "'.$data.'")';		
		}else{
			if(is_string($handle)){
				#echo __METHOD__.'is_string($handle)<br>';
				$query = 'REPLACE INTO Associations (hash_id, data) VALUES("'.$handle.', "'.$data.'")';		
			}else{
				$query ='REPLACE INTO Associations (id, data) VALUES("'.$handle.', "'.$data.'")")';		
			}			
		}
		#$query = mysql_real_escape_string($query);
		#echo '$query['.$query.']<br>';
		$result = mysql_query($query);
		#echo '$result<pre>'.var_export($result,true).'</pre>';
		return $result;
    }
    
    function getAssoc($handle, $UserID=null)
    {
        if(is_numeric($UserID)){
			#$query = 'REPLACE INTO Associations (OasisID, hash_id, data) VALUES( '.$UserID.', "'.$handle.', "'.$data.'")';
			$query = 'SELECT data FROM Associations WHERE OasisID='.$UserID.' ';
		}else{
			if(is_string($handle)){
				#echo __METHOD__.'is_string($handle)<br>';
				$query = 'SELECT data FROM Associations WHERE hash_id = "'.$handle.'" ';
			}elseif(is_numeric($handle)) {
				$query= 'SELECT data FROM Associations WHERE id = '.$handle.' ';
			}
		}
		#echo '$UserID['.$UserID.']<br>';
		#echo __METHOD__.'::'.__LINE__.'$query['.$query.']<br>';
		$q = mysql_query($query);
		#echo '$q['.$q.']<br>';
        $data = mysql_fetch_row($q);
        if(!$data) {
            return false;
        }
		#throw new ErrorException('SHOW ME THE STACK');
		$unpack = unserialize($data[0]);
		#echo __METHOD__.'::'.__LINE__.'$unpack<pre>'.var_export($unpack,true).'</pre>$$$$$$$$';
        return $unpack;
    }
    
    function delAssoc($handle)
    {
        if(!is_numeric($handle)) {
            return false;
        }
        mysql_query("DELETE FROM Associations WHERE id = '$handle' OR hash_id = '$handle'");
    }
    function setResponse($identity, $attributes){
		parent::positiveResponse($identity, $attributes);
	
	
	}
    function generateAssociation($handle=null, $private=null)
    {
		#echo __METHOD__.'::'.__LINE__.'<br>';
		#echo '$this->assoc_handle<pre>'.var_export($this->assoc_handle,true).'</pre>';
		#echo '$this<pre>'.var_export($this,true).'</pre>';
		#die;
        $this->assoc = array();
        # We use sha1 by default.
        $this->assoc['hash']   = 'sha1';
        $this->assoc['mac']    = $this->shared_secret('sha1');
        #$this->assoc['handle'] = $this->assoc_handle();
		 $this->assoc['nonce'] =  gmdate("Y-m-d\TH:i:s\Z");
		if(isset($handle) && !isset($this->assoc_handle)){
			$this->assoc_handle = $handle;
		}
		if(isset($private)){
			$this->assoc['private'] = 'private';
		}
		$this->assoc['data'] = $this->data;
		
		$this->assoc['handle'] = $this->assoc_handle;
		return $this->assoc;
    }
	function generateAssociationzzzzzzzzzz(){
		die;
	}
}

