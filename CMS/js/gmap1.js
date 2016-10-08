var mapHeight = "350px";

function showMap(){
	document.getElementById("googlemap_image").innerHTML ="";
	url1 = document.getElementById("urlMap").value;
	url2 = document.getElementById("urlStreet").value;
	viewURL(url1,url2,true,true);
	
}

function viewURL(mapURL,streetURL,bMap,bStreet) {	
	tab = "<table border=1 width=100%><tr><TH width=50%>";
	if (bMap){
		//change width
		url1 = mapURL;
		p1 = mapURL.indexOf("width=");
		if(p1 >=0){
			p2 = mapURL.indexOf('"',p1 + 7);
			if(p2 >0){
				url1 = mapURL.substring(0,p1 + 7) + "100%" + mapURL.substring(p2);
			}
		}
		//change height
		url2 = url1;
		p1 = url1.indexOf("height=");
		if(p1 >=0){
			p2 = url1.indexOf('"',p1 + 8);
			if(p2 >0){
				url2 = url1.substring(0,p1 + 8) + mapHeight + url1.substring(p2);
			}
		}
		//
		tab = tab + url2;
	}
	tab = tab + "</TH>";
	
	tab = tab + "<TH width=50%>";
	if (bStreet){
		tab = tab + "<div id='streetView' style='width:100%; height:" + mapHeight + "'></div> <span id='streetURL'></span>";
	}
	tab = tab + "</TH></tr></table>";
	document.getElementById("googlemap_image").innerHTML = tab;
	
	if (bStreet){
		showStreet(streetURL);
	}	
}

function showStreet(streetURL){
	
	var datap, datall;
	
	p1 = streetURL.indexOf("cbp=");
	
	//新しいURL
	if (p1 <0) {
		
		//マップと同じ表示かた		
		var p1 = streetURL.indexOf('width');
		var p2 = streetURL.indexOf(' ', p1);
		var width = streetURL.substring(p1,p2);
		streetURL = streetURL.replace(width, "width=\"100%\"");
		
		p1 = streetURL.indexOf('height');
		p2 = streetURL.indexOf(' ', p1);
		var height = streetURL.substring(p1,p2);
		streetURL = streetURL.replace(height, "height=\"100%\"");
		
		document.getElementById("streetView").innerHTML = streetURL;
		document.getElementById("streetURL").style.display = 'none';
	}
	else {
		p2 = streetURL.indexOf("&amp;",p1);
		if (p2 <0) return;
		s1 = streetURL.substring(p1+4,p2);
		
		datap = s1.split(',');

		ll1 = streetURL.indexOf("cbll=");
		if (ll1 <0) return;
		ll2 = streetURL.indexOf("&amp;",ll1);
		if (ll2 <0) return;
		s2 = streetURL.substring(ll1+5,ll2);
		
		datall = s2.split(',');
		
		var myPano = new GStreetviewPanorama(document.getElementById("streetView"));
		fenwayPark = new GLatLng(datall[0],datall[1]);
		var myPOV = {yaw:Number(datap[1]),pitch:Number(datap[4]),zoom:Number(datap[3])};	
		myPano.setLocationAndPOV(fenwayPark, myPOV);
		
	    //GEvent.addListener(myPano, "error", handleNoFlash);
		  
		p = streetURL.indexOf("<br />");
	
		if(p<0) return;
		//alert('here')
		//alert(streetURL);
		document.getElementById("streetURL").innerHTML = streetURL.substring(p+6);
	}
}
