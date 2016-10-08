
var root = "/refine";
function copyVal(from, to){
	if(document.all){
		document.all(to).value = document.all(from).value;
     }
    else {
    	document.getElementById(to).value = document.getElementById(from).value;
    }
}

function clearCheck(elementName){
	if(document.all){
		checks = document.all(elementName);
	}
	else{
    	checks = document.getElementById(elementName);
	}
	for(cnt=0; cnt<checks.length; cnt++){
		checks[cnt].checked=false;
	}
}

function OpenJusho(path,proc){
    var url=path + "POP010.do?proc=" + proc + "&new=";
    win=window.open( url, "ad", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=480,height=200" );
}

function jusyo_callback(result) {

	argv = result.split(":");
	if(document.all){
    	document.all('zip').value = argv[0];
    	document.all('jushoCd').value = argv[1];
    	document.all('pref').value = argv[2];
    	document.all('city').value = argv[3];
    	document.all('cityArea').value = argv[4];

    }
    else {
    	document.getElementById('zip').value = argv[0];
    	document.getElementById('jushoCd').value = argv[1];
    	document.getElementById('pref').value = argv[2];
    	document.getElementById('city').value = argv[3];
    	document.getElementById('cityArea').value = argv[4];
    }
    
}

function OpenZip(path,proc,idName){
	id = "";
	if(document.all){
    	id = document.all(idName).value;
    }
    else {
    	id = document.getElementById(idName).value;
    }
	var url = path + "POP011.do?proc=" + proc + "&step=0&zip1=" + id  + "&select=" ;
    win=window.open(url,"zip","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=580,height=360");
}
function zip_callback(result) {

	argv = result.split(":");
	if(document.all){
    	document.all('zip').value = argv[0];
    	document.all('jushoCd').value = argv[1];
    	document.all('pref').value = argv[2];
    	document.all('city').value = argv[3];
    	document.all('cityArea').value = argv[4];

    }
    else {
    	document.getElementById('zip').value = argv[0];
    	document.getElementById('jushoCd').value = argv[1];
    	document.getElementById('pref').value = argv[2];
    	document.getElementById('city').value = argv[3];
    	document.getElementById('cityArea').value = argv[4];
    }
}

function OpenEnsen(path,proc){
    var url=path + "POP020.do?proc=" + proc + "&new=";
    win=window.open( url, "line", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=400,height=300" );
}

function ensen_callback(result) {

	argv = result.split(":");
	if(document.all){
    	document.all('line').value = argv[0];
    	document.all('station').value = argv[1];
    }
    else {
    	document.getElementById('line').value = argv[0];
    	document.getElementById('station').value = argv[1];
    }
}

function ensen_callback2(result) {

	argv = result.split(":");
	if(document.all){
    	document.all('line2').value = argv[0];
    	document.all('station2').value = argv[1];
    }
    else {
    	document.getElementById('line2').value = argv[0];
    	document.getElementById('station2').value = argv[1];
    }
}

function ensen_callback3(result) {

	argv = result.split(":");
	if(document.all){
    	document.all('line3').value = argv[0];
    	document.all('station3').value = argv[1];
    }
    else {
    	document.getElementById('line3').value = argv[0];
    	document.getElementById('station3').value = argv[1];
    }
}

function owner_callback(result) {

	argv = result.split(":");

	if(document.all){
    	document.all('ownerId').value = argv[0];
    	document.all('ownerKanjiName').value = argv[1];
    }
    else {
    	document.getElementById('ownerId').value = argv[0];
    	document.getElementById('ownerKanjiName').value = argv[1];
    }

}

function OpenFileBrowse(idName){
	id = "";
	if(document.all){
    	id = document.all(idName).value;
    }
    else {
    	id = document.getElementById(idName).value;
    }
url = root + "/ANY_NEW_RF0121-000P.do?bukkenId=" + id + "&search=" ; 
window.open(url,"file", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=540,height=680");

}

function OpenBuildingFileBrowse(idName, msg)
{
	id = "";
	if(document.all){
    	id = document.all(idName).value;
    }
    else {
    	id = document.getElementById(idName).value;
    }
	if(id != '')
	{
		url = root + "/ANY_NEW_RF0131-000P.do?buildingId=" + id + "&search=" ; 
		window.open(url,"file", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=540,height=680");
	}
	else
	{
		alert(msg);
	}
}

function OpenOwnerDetail(idName){
	id = "";
	if(document.all){
    	id = document.all(idName).value;
    }
    else {
    	id = document.getElementById(idName).value;
    }
	if(id == ""){
		alert('オ－ナ－を選択してください。');
		return;
	}
	
	url = root + "/ANY_RF0110-010P.do?id=" + id ; 
	window.open(url,"ownerD", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=830,height=450");
}

function OpenOwnerBrowse(cb){
	url = root + "/ANY_NEW_RF0110-000P.do?cb="+cb;
	window.open(url,"ownerB", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=680,height=450");
}

function OpenBukkenDetail(idName){
	id = "";
	if(document.all){
    	id = document.all(idName).value;
    }
    else {
    	id = document.getElementById(idName).value;
    }
	if(id == ""){
		alert('物件を選択してください。');
		return;
	}
	
	url = root + "/ANY_RF0120-010P.do?id=" + id ; 
	window.open(url,"bukkenD", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550");
}
function OpenBukkenBrowse(cb){
	url = root + "/ANY_NEW_RF0120-000P.do?cb="+cb;
	window.open(url,"bukkenB", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=680,height=450");
}

function ChangeTab(formId, tabNo){

	if(document.all){
		document.all('tabNo').value=tabNo;
	}
	else{
    	document.getElementById('tabNo').value=tabNo;
	}
	
	document.forms[formId].submit();

}

function onSelect(result ,callbackName){
	var url = "window.opener." + callbackName + "('" + result + "');"
	eval(url);
	this.window.close();
}

function buttonClick(formId,param){
	document.getElementById('hdfAct').value = param;
	document.forms[formId].submit();
}

function OpenAthome(bukkenId){
    var url = root + "/RF0121-010_RF0121-020P.do?id=" + bukkenId ;
    win=window.open( url, "athome", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=600,height=400" );
}

function OpenGroupPop(cb) {
	url = root + "/ANY_RF0120-020P.do?search=";
	window.open(url,"group", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=500,height=450");
}

function getSelectedGroup(formId) {
	var pageSize = document.forms[formId].total.value;
	var textReturn = "";
	var dataReturn = "";
	var sDiv = "";
	var checkboxObj = null;
	var gIdObj = null;
	var gTypeObj = null;
	var gNoObj = null;
	var gNameObj = null;
	try
	{
          for (i = 0; i < pageSize; i++) 
          {
	           sDiv = "group[" + i + "].id";
	           	if(document.all){
	           		checkboxObj = document.all(sDiv);
	           	}else{
	           		checkboxObj = document.getElementById(sDiv);
	           	}
	           if (true == checkboxObj.checked) 
	           {
	           		if(document.all){
			           // Get Id object
			           	sDiv = "gId" + i;
			           	gIdObj = document.all(sDiv);
			           	// Get No object
			           	sDiv = "gNo" + i;
			           	gNoObj = document.all(sDiv);
			           	//
			           	sDiv = "gType" + i;
			           	gTypeObj = document.all(sDiv);
			           	//
			           	sDiv = "gName" + i;
			           	gNameObj = document.all(sDiv);	           			
	           		}else{
			           // Get Id object
			           	sDiv = "gId" + i;
			           	gIdObj = document.getElementById(sDiv);
			           	// Get No object
			           	sDiv = "gNo" + i;
			           	gNoObj = document.getElementById(sDiv);
			           	//
			           	sDiv = "gType" + i;
			           	gTypeObj = document.getElementById(sDiv);
			           	//
			           	sDiv = "gName" + i;
			           	gNameObj = document.getElementById(sDiv);
		           	}
		           	// concat string to return
		            textReturn = textReturn + gNameObj.value + "\n";
		            dataReturn = dataReturn + gIdObj.value + "|";
	           }
          }
          opener.document.forms["detail"].groupListData.readonly = false;          
          opener.document.forms["detail"].groupListData.value = textReturn;
          opener.document.forms["detail"].groupListId.value = dataReturn;
          opener.document.forms["detail"].groupListData.readonly = true;
          window.close();
     }
     catch(ex) 
     {
    		// Do nothing
     }
}

function checkValidImage(sFilename){		
	var arrImgExt = new Array();
	arrImgExt[0] = "gif";
	arrImgExt[1] = "jpg";
	arrImgExt[2] = "bmp";
	arrImgExt[3] = "png";
	var sExtend = "";
	var iPos = sFilename.lastIndexOf('.');
	try{
		iPos = iPos + 1;
		sExtend = sFilename.substring(iPos);
	}catch(ex){
		return false;
	}
	for (i=0;i<arrImgExt.length;i++){
		if (arrImgExt[i] == sExtend.toLowerCase() ) return true;
	}
	return false;
}	

function attachImg(type, sMsg){
	var filename = "";
	var oName = null;
	var oSpan = null; 
	//var sMsg = "画像" + type + "のファイル形式をチェックしてください。";
	try{
		if(document.all){
	    	filename = document.all("file" + type).value;
	    	oName = document.all("featureImageName" + type);
	    	oSpan = document.all("spanImage" + type);
	    }
	    else {
	    	filname = document.getElementById("file" + type).value;
	    	oName = document.getElementById("featureImageName" + type);		    	
	    	oSpan = document.getElementById("spanImage" + type);		    	
	    }
	    if (filename == "") {
	    	alert(sMsg);
	    	return;
	    }
	    if (checkValidImage(filename) == false){
	    	alert(sMsg);
	    	return;
	    }
	    oName.value = getFileNameOnly(filename);
	    oSpan.innerHTML = oName.value;
	}catch(ex){
		// do nothing
	}
}

function clearImg(type){
	var oName = null;
	var oSpan = null; 
	var oChange = null;		
	try{
		if(document.all){
	    	oName = document.all("featureImageName" + type);
	    	oSpan = document.all("spanImage" + type);
	    	oChange = document.all("changeFile" + type);		    	
	    }
	    else {
	    	oName = document.getElementById("featureImageName" + type);
	    	oSpan = document.getElementById("spanImage" + type);	    	
	    	oChange = document.getElementById("changeFile" + type);
	    }
	    oName.value = "";
	    oSpan.innerHTML = "";	
	    oChange.value = "1";
	}catch(ex){
		// do nothing
	}	    
}

function getFileNameOnly(pathFileName) {
	var fileName = pathFileName;
	if (null == fileName || fileName == "") {
		return "";
	}
	try {
		var iPos = pathFileName.lastIndexOf('\\');
		if (iPos <= 0){
			iPos = pathFileName.lastIndexOf('/');
		}
		iPos = iPos + 1;
		fileName = pathFileName.substring(iPos);		
	}
	catch(ex) {
		alert(ex.description);
	}

	return fileName;		
} 	

function ImageMouse(obj, link)
{
	obj.src = "images/global/" + link;
}
function ImageMouse2(obj, link)
{
	obj.src = "../images/global/" + link;
}

function LostFocus(obj)
{
	obj.style.textDecoration = 'none';
	obj.style.color = '#0066CC';
}
function Focus(obj)
{
	obj.style.textDecoration = 'underline';
	obj.style.color = '#FF7800';
}