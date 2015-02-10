<?
function getUserData($handle=null, &$obj)
{
    if(isset($_POST['login'],$_POST['password'])) {
        $login = mysql_real_escape_string($_POST['login']);
        $password = sha1($_POST['password']);
        $q = mysql_query("SELECT * FROM Users WHERE login = '$login' AND password = '$password'");
        if($data = mysql_fetch_assoc($q)) {
            return $data;
        }
        if($handle) {
            echo 'Wrong login/password.';
        }
    }
    if($handle) {
    echo '
    <form action="'.$GLOBALS["op"]->serverLocation.'" method="post">
    <input type="text" name="openid.assoc_handle" value="'.$handle.'">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="password"><br>
    <button>Submit</button>
    </form>
    ';
    die();
    }
}


function getOpenIdString($openid =null){
	$conectionString = '?';
	if($openid != null){
		$openIDSessionData = $openid->getAttributes();
		/**
		$conectionString .= 'openid_identity='.$openIDSessionData["openid_identity"].'&';
		$conectionString .= 'openid_assoc_handle='.$openIDSessionData["openid_assoc_handle"].'&';
		$conectionString .= 'openid_signed='.$openIDSessionData["openid_signed"].'&';
		$conectionString .= 'openid_sig='.$openIDSessionData["openid_sig"].'&';
		$conectionString .= 'openid_identity='.$openIDSessionData["openid_identity"].'&';
		$conectionString .= 'openid_return_to='.$openIDSessionData["openid_return_to"].'&';
		*/
		foreach($openid->getAttributes() AS $key => $value){
			$conectionString .= $key.'='.$value.'&';
		
		}
	}
	return $conectionString;
}
?>