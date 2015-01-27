<?
if(!include('../env.php') ){
	$ENVPATH = '';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
	<title>test api</title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<!-- <link rel="canonical" href="http://deluxebusinessservices.com/" /> -->
	<script type='text/javascript' src='https://auth<? echo strtolower($ENVPATH);?>.deluxebusinessservices.com/assets/scripts/prototype.js'></script>
	<script type='text/javascript' >
//-----------------------------------------
//-----------------------------------------
//-----------------------------------------
ENVPATH="<? echo strtolower($ENVPATH);?>";
SERVICE_PAGE = 'https://auth'+ENVPATH+'.deluxebusinessservices.com/AJAX'; //'RPC_PROXY.php'+URIString
URIString = '';
function randomizeRequestID(){
	/**
	This function creates a random number to pass as the request ID
	JSON-RPC spec allows request ID to be any value (exccept null)
	IE requires the ID be unique if sending the request in the GET scope
	as it will cache EVERY request in GET.
	request ID may still include useful info (ID for logging etc) so long as 
	part of the ID is unique for each request (within the same session)
	*/
	randomID = Math.random();
	randomID = randomID.toString();
	//alert(randomID);
	rExp = /0./gi;
	newString = new String ('');
	randomID = randomID.replace(rExp, newString);	
	return randomID;
}

function serverJSONRPCRequest(serviceRequest){
	/**
	Standard service call from prototype. based on JSON-RPC spec (initial version)
	*/
	//traceStatus("serverJSONRPCRequest::"+serviceRequest);
	randomID = randomizeRequestID();
	//alert('serverJSONRPCRequest'+SERVICE_PAGE+URIString);
	//alert('randomID['+randomID+']');
	traceStatus(serviceRequest);
	var myAjax = new Ajax.Request(
			SERVICE_PAGE+URIString, 
			{
				method: 'get', 
				parameters: serviceRequest,
				id: randomID,
				onComplete: function (response) {
					//elementID = 'pushIniToServer';
					//updateStatus(elementID,'http://www.aamappers.com/smf/Themes/babylon/images/tpoptions.gif');
					//alert('?????');
					//alert("RPCResponse ="+Object.toJSON(response.result)); //toJSONString
					//traceStatus("RPCResponse ="+Object.toJSON(response)); //toJSONString
					//alert("RPCResponse ="+response.responseText); //toJSONString
					//RPCResponse = response.responseText.evalJSON();  //parseJSON
					RPCResponse = response.responseText;  //parseJSON
					traceStatus("RPCResponse ="+RPCResponse); //toJSONString
					//handleRPCResponse(RPCResponse);
				}
			});
}
function handleRPCResponse(RPCResponse){
	traceStatus("handleRPCResponse");
	//alert('?????');
	//alert('handleRPCResponse recieved'+RPCResponse);
	
	if(RPCResponse.error != null){
		traceStatus("RPCResponse.error != null"+RPCResponse.error);
		handleRPCResponseError(RPCResponse.error);
	}else{
		traceStatus("RPCResponse.result");
		processRPCResult(RPCResponse.result);
	}
}

function handleRPCResponseError(errorObj){
	traceStatus("handleRPCResponseError");
	traceStatus("handleRPCResponseError VALUE"+Object.toJSON(errorObj));
	
	var errorType 			= errorObj.a.errorType;
	var errorContext		= errorObj.a.errorContext;
	var errorDescription 	= errorObj.a.errorDescription;
	
	var msg = 'Error Type: '+ errorType+"\n";
	msg 	= msg+'Error Context: '+ errorContext+"\n";
	msg 	= msg+errorDescription;
	
	traceStatus("errorType::"+errorType);
	traceStatus("errorContext::"+errorContext);
	traceStatus("errorDescription::"+errorDescription);
	traceStatus("errorDescription::"+errorDescription);
	alert(msg);
	
	/**   Nice idea but the definitioons were never completed, services provide a formatted error msg anyway
	try {
	   if(errorType == "USER_ID"){
	      throw "Error 1"
	   }else if(errorType == "JOB_ID"){
	      throw "Error 2"
	   }
	}
	catch(er) {
	   if(er == "Error 1"){
	      alert("Error 1 Unable to find your user id. Account creation required");
	      // call error handler1
	   }
	   if(er == "Error 2"){
	      alert("Error 2 Unable to find your print job id");
	      // call error handler2
	   }
	} 	
	*/

	
}


function processRPCResult(resultObj){
	traceStatus("processRPCResult");
	//do somthing with the result
	traceStatus("processRPCResult=>resultObj::"+Object.toJSON(resultObj));
	
	if(resultObj.resultHandler){
		//alert('responseValue');
		handler = resultObj.resultHandler;
		traceStatus("processRPCResult=>handler::"+handler);
		//alert('handler=='+handler+'response');
		//WDF = 'WDF';
		//alert(WDF.toString());
		//responseValue = resultObj.toJSONString();
		
		
		//traceStatus("responseValue::"+responseValue);
		//return;
		//traceStatus("processRPCResult=>response::"+responseValue);
		//responseValue = resultObj;
		responseValue = Object.toJSON(resultObj) ;
		//responseValue = resultObj.responses;
		//responseProcessor = handler+'('+resultObj.responses+');';
		responseProcessor = handler+'('+responseValue+');';
		//alert('responseProcessor::'+responseProcessor+'::');
		traceStatus("processRPCResult=>responseProcessor::"+responseProcessor);
		eval(responseProcessor);
		
	}else{
		traceStatus("processRPCResult=>resultHandler:: FAILED");
	}
	
}

function objectToAlertString(alertObject){
	this.alertObject = Object.toJSON(alertObject); //.toSource();
	this.alertString = '';
}

function stringHolder(stringHolder){
	
	this.stringHolder = this.stringHolder + stringHolder;
}

function writeToElement(e_id, e_content){
	e_id.innerHTML =  e_content;
}
function alertResultRec(resultObj){
	//alert('alertResultRec');
	responseString = Object.toJSON(resultObj.responses);//.toSource();
	
	traceStatus("alertResultRec=>responseString::"+responseString);
	responseString = eval(responseString);
	responseHash = $H(responseString);
	result = '';
	stringHolder = new stringHolder();
	for(indexVal in responseHash){
		
		row = 'responseString.'+indexVal+'.response ;';
		updateServerID = indexVal.charCodeAt(0)-96;
		//alert('updateServerID:'+indexVal+"::"+(indexVal.charCodeAt(0))+'::row'+row);
		rowDetail  = eval(row);
		traceStatus("alertResult=>rowDetail::"+rowDetail);
		if(rowDetail != 'undefined'){
			if(typeof(rowDetail) == 'object'){
				rowHash = $H(rowDetail);
				rowHash.each(function(item) {
					validMapElement = "last_updated_server_"+updateServerID+"_"+item.key;
					if(validMap = $(validMapElement)){
						validMap.innerHTML = item.value;
						//setTimeOut(writeToElement(validMap, item.value), 500);
					}
					//alert(item.key + " contains " + item.value);
				});
			}else{}
		}else{}
	}
	
	//updateStatus('UpdateSummary','http://www.aamappers.com/assets/images/tb011.gif');
	//alert('END stringHolder.stringHolder'+stringHolder.stringHolder);
	//alert('END:: Final Result"'+"\n"+ result);
}
function alertResult(resultObj){
	responseString = Object.toJSON(resultObj.responses);//.toSource();
	
	traceStatus("alertResult=>responseString::"+responseString);
	responseString = eval(responseString);
	result = '';
	for(indexVal in responseString){
		
		row = 'responseString.'+indexVal+'.response ;';
		//alert('row'+row);
		rowDetail  = eval(row);
		traceStatus("alertResult=>rowDetail::"+rowDetail);
		if(rowDetail != 'undefined'){
			result = result+rowDetail+"\n";
			if(typeof(rowDetail) == 'object'){
				//alert('rowDetail == object');
			}else{/*alert('rowDetail != object');*/	}
		}else{/*alert('rowDetail = undefined');*/	}
		//alert('row::'+resultObj.responses.indexVal);
		//alert('result::'+result);
	}
	alert('Final Result:'+"\n"+ result);
}

function reloadPage(resultObj){
	alert('reloadPage'+location.search);
	/**
	if(document.all){
		var IEhack = '';
		if(searchParameters['ms_id10t']){
			alert('ms_id10t exists'+location.search.ms_id10t);
			IEhack = '&ms_id10t='+randomizeRequestID();
		}else{
			alert('ms_id10t does not exist');
			IEhack = '&ms_id10t='+randomizeRequestID();
		}
		
	}
	*/
	//window.location = window.location.href;
//window.location.reload(true);
}
//-----------------------------------------
//-----------------------------------------
//-----------------------------------------


function traceStatus(stringValue){
		$("traceStatus").innerHTML = $("traceStatus").innerHTML +"\n<br>"+stringValue;
}

function updateStatus(elementID,imgPath){
	$(elementID).src = imgPath;
}




//-----------------------------------------
//-----------------------------------------
//-----------------------------------------

function pageSingle(id){
	//alert(id);
	if (document.getElementById(id)){
    		//alert('document.getElementById(id).style.display='+document.getElementById(id).style.display);
		(document.getElementById(id).style.display == 'none')? display = 'block': display = 'none' ;
		//alert('display'+display);
		document.getElementById(id).style.display = display;
        //alert('document.getElementById(page_id).style.display: '+document.getElementById(page_id).style.display);
	}
}

//---------------- tree stuff
var defOptList = new Array(0);

function setSelectedOption(id, parentId){
	//NEW_PARENT_
	//alert('id['+id+']'+'parentId['+parentId+']');
	//alert('defOptList.length['+defOptList.length+']');
	
	//alert('FIN!');
	if(defOptList.length == 0){
		DOMName = 'NEW_PARENT_'+id;
		myOpts = document.getElementById(DOMName).length;
		//alert('myOpts['+myOpts+']');
		var i = 0;
		var msg = '';
		while(i< myOpts){
			if(document.getElementById(DOMName).options[i].value == parentId){
				msg = msg +'document.getElementById(DOMName).options['+i+']['+document.getElementById(DOMName).options[i].value+']['+document.getElementById(DOMName).options[i].text+']'+"\n";
				//alert('i['+i+']');
				document.getElementById(DOMName).options[i].selected = true;
			}
			i++;
		}
	}
	//alert('id['+id+']'+'parentId['+parentId+']'+'msg['+msg+']');
	
}


function testAJAXAPI(){
	
	var serviceName = document.getElementById('serviceName').value;
	var serviceMethod = document.getElementById('serviceMethod').value;
	var serviceCall = serviceName+'.'+serviceMethod;
	var callArgs = document.getElementById('serviceParams').value;
	//alert('serviceName'+serviceCall);
	//serviceRequest = 'serviceRequest={"method": "JSONRPC_SERVICE_ADD_ORDER_NAME", "params": {"resultHandler" : "returnOrderPK" , "user_fk" : "'+user_fk+'", "order_name" : "'+order_name+'", "orderType" : "'+orderType+'", "product_fk" : "'+product_fk+'", "need_by_date" : "'+need_by_date+'",   '+customDetails+'"action" : "setAction"}, "id": '+randomizeRequestID()+'}';
	serviceRequest = '{"method": "'+serviceCall+'", "params": '+callArgs+', "id": '+randomizeRequestID()+'}';
	traceStatus('testAJAXAPI'+serviceRequest);
	serverJSONRPCRequest(serviceRequest);
}

	</script>
</head>
<body class="body">

SETTINGS: <a href="javascript:void(0);" onclick="pageSingle('testOptions')">[+]</a>
<button type="button" onclick="testAJAXAPI();" >Test Call</button><br>
<div id="testOptions" style="display:none">
	<table>
		<tr>
			<td>SERVICE:</td>
			<td><input type="text" size="60" value="INTOUCH" id="serviceName"></td>
		</tr>
		<tr>
			<td>SERVICE METHOD:</td>
			<td><input type="text" size="60" value="searchDomains" id="serviceMethod"></td>
		</tr>
		<tr>
			<td>CALL PARAMS: (JSON enc.)</td>
			<td><input type=text size=60 value='{"resultHandler" : "returnOrderPK" , "searchPhrase" : "dom" , "searchLimit" : 5}' id=serviceParams></td>
		</tr>
		
	</table>


</div>

<div id="traceStatus">
</div>
<?
#echo '<pre>'.print_r($GLOBALS, true).'</pre>';
$jsonstring = " {	{ 'domain':'domain1.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain2.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain3.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain4.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain5.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain6.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain7.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain8.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain9.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain10.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain11.com','cID':'DE12345','pID':'DE03333' }};";
$jsonstring = " [	{ 'domain':'domain1.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain2.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain3.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain4.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain5.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain6.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain7.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain8.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain9.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain10.com','cID':'DE12345','pID':'DE03333' },	{ 'domain':'domain11.com','cID':'DE12345','pID':'DE03333' }];";
$jsonstring = '{
	"cols":	[
		{"label":"Type","type":"string"},
		{"label":"Time","type":"date"},
		{"label":"counter","type":"number"}],
	"rows":[
		["Visitors","Wed, 04 Jul 2012 00:00:00 -0400",8],
		["Visits","Wed, 04 Jul 2012 00:00:00 -0400",13],
		["Visitors","Thu, 05 Jul 2012 00:00:00 -0400",29],
		["Visits","Thu, 05 Jul 2012 00:00:00 -0400",40]],
	"meta":[
		{"label":"Visitors","value":239,"delta":7},
		{"label":"Visits","value":397,"delta":-13}]
}';
#echo '$jsonstring <pre>'.var_export($jsonstring, true).'</pre>';
#$testObj = json_decode($jsonstring);
#echo '$testObj<pre>'.var_export($testObj, true).'</pre>';
#$jsonstring = json_encode($testObj);
#echo '$jsonstring <pre>'.var_export($jsonstring, true).'</pre>';

?>

</body>
	
</html>	
