<?



class restrequest{
	
	public $requestURL = 'http://auth-dev.deluxebusinessservices.com/REST/';
	protected $extensions  = array(
		'.js'   => 'Content-type: text/javascript',
		'.json' =>  'Content-type: application/json',
		'.flexigrid' => 'Content-type: text/plain',
		'.cookie' => 'Content-type: text/html',
		'.xml'  => 'Content-type: text/xml',
		'.txt'  => 'Content-type: text/plain',
		'.html' => 'Content-type: text/html',
		'.php'  => 'Content-type: text/html',
		'.pdf'  => 'Content-type: application/pdf',
		'.gif'  => 'Content-type: image/gif',
		'.jpg'  => 'Content-type: image/jpeg',
		'.png'  => 'Content-type: image/png'
	);
	
	public function __construct($mimeType, $reqestType, $reqestMessage ){
		if(isset($mimeType)){
			$this->mimeType = $mimeType;
		}else{
			$this->mimeType = $this->extensions[".json"];
		}
		if(isset($reqestType)){
			$this->reqestType = $reqestType;
		}else{
			$this->reqestType = "GET";
		}
		if(isset($reqestMessage)){
			$this->reqestMessage = $reqestMessage;
		}else{
			$this->reqestMessage = NULL;
		}
			
	
		$this->makeCall();
		return;
	}

	
	
	public function makeCall(){
	
		$header[] = $this->extensions[$this->mimeType];
		#$CURLOPT_URL = $this->requestURL.'?'.$this->reqestMessage;
		$CURLOPT_URL = $this->requestURL;
		#echo '$CURLOPT_URL<pre>'.var_export($CURLOPT_URL,true).'</pre>';
		$ch = curl_init();
		#curl_setopt($ch, CURLOPT_USERAGENT, 'XtraDoh xAgent');
		curl_setopt($ch, CURLOPT_URL, $CURLOPT_URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 900);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		#curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		#curl_setopt($ch, CURLOPT_USERPWD, "$this->login:$this->password"); 
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->reqestType);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->reqestMessage);
		
		/**
		CURLOPT_COOKIE
		CURLOPT_ENCODING
		CURLOPT_CUSTOMREQUEST 
		*/
		$data = curl_exec($ch);
		echo '$data<pre>'.var_export($data,true).'</pre>';
		$info = curl_getinfo($ch);
		echo '$info<pre>'.var_export($info,true).'</pre>';
	
		return;
	}
}

$mimeType = ".json";
$reqestType = "GET";  		//curl_setopt($ch, CURLOPT_HTTPGET, true);
#$reqestType = "POST"; 		//curl_setopt($ch, CURLOPT_POST, true);
#$reqestType = "PUT";		//curl_setopt($ch, CURLOPT_PUT, true);
#$reqestType = "DELETE";	//curl_setopt($ch, CURLOPT_PUT, true);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $reqestType);

#$reqestMessage = '{"serviceMethod":"doSomeStuff", "serviceArgs": [1,2,3]}';
$reqestMessage = 'REST_STUB={"serviceMethod":"doSomeStuff", "serviceArgs": [1,2,3]}';
#$reqestMessage = 'REST_STUB=\'{"serviceMethod":"doSomeStuff", "serviceArgs": [1,2,3]}\'';
		
$myRequestObj = new restrequest($mimeType, $reqestType, $reqestMessage );
		
		
?>