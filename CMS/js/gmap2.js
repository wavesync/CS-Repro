	var map;
	var myPano;
	var panoClient;
	var currentLatLng;
	var latlng;
	var currentYaw = 0;
	var fovMarker;
	var iconSize = 150;
	var panorama;
	
	function viewMap(lat,lng,bMap,bStreet) 
	{
		currentLatLng = new GLatLng(lat, lng);
		latlng = new GLatLng(lat, lng);
		//
		panoClient = new GStreetviewClient(); 
		map = new GMap2(document.getElementById("map"),{draggableCursor:"crosshair"});
		map.addControl(new GSmallMapControl());

		map.addMapType(G_PHYSICAL_MAP);
		map.addControl(new GHierarchicalMapTypeControl(true));

		map.setCenter(currentLatLng, 14);
		myPano = new GStreetviewPanorama(document.getElementById("pano"));
		myPano.setLocationAndPOV(currentLatLng, {yaw:currentYaw, pitch:0});

		var guyIcon = new GIcon(G_DEFAULT_ICON);
		guyIcon.image = "http://www.google.co.jp/mapfiles/markerA.png";
		guyIcon.transparent = "http://maps.google.co.jp/intl/en_us/mapfiles/cb/man-pick.png";
		guyIcon.imageMap = [
		      26,13, 30,14, 32,28, 27,28, 28,36, 18,35, 18,27, 16,26,
		      16,20, 16,14, 19,13, 22,8
		   ];
		guyIcon.iconSize = new GSize(21, 35);
		guyIcon.iconAnchor = new GPoint(25, 35);
		guyIcon.infoWindowAnchor = new GPoint(25, 5);
		marker = new GMarker(latlng, {icon: guyIcon, draggable: true});
		map.addOverlay(marker);
		lastMarkerLocation = latlng;


		GEvent.addListener(marker, "dragend", onDragEnd);
		GEvent.addListener(marker, "click", openPanoramaBubble);
		
		oMap = document.getElementById("map");
		if(bMap){
			oMap.style.visibility = "visible";
		}else{
			oMap.style.visibility = "hidden";
		}
		
		oStreet = document.getElementById("pano");
		if(bStreet){
			oStreet.style.visibility = "visible";
		}else{
			oStreet.style.visibility = "hidden";
		}
		

		return;
	}
	function openPanoramaBubble() 
	{
	  var contentNode = document.createElement('div');
	  contentNode.style.textAlign = 'center';
	  contentNode.style.width = '500px';
	  contentNode.style.height = '300px';
	  contentNode.innerHTML = 'Loading panorama';
	 
	  panorama = new GStreetviewPanorama(document.getElementById("pano"));
	  panorama.setLocationAndPOV(marker.getLatLng(), null);
	  GEvent.addListener(panorama, "newpano", onNewLocation);
	  GEvent.addListener(panorama, "yawchanged", onYawChange); 
	  var iw = map.getInfoWindow();
	  GEvent.addListener(iw, "maximizeend", function() {
	    panorama.setContainer(contentNode);  
	    window.setTimeout("panorama.checkResize()", 5);
	  });

	var myPano = new GStreetviewPanorama(document.getElementById("pano"));
	myPano.setLocationAndPOV(marker.getLatLng());

	}

	function onYawChange(newYaw) 
	{
	  var GUY_NUM_ICONS = 16;
	  var GUY_ANGULAR_RES = 360/GUY_NUM_ICONS;
	  if (newYaw < 0) 
	  {
	    newYaw += 360;
	  }
	  guyImageNum = Math.round(newYaw/GUY_ANGULAR_RES) % GUY_NUM_ICONS;
	  guyImageUrl = "http://maps.google.co.jp/intl/en_us/mapfiles/cb/man_arrow-" + guyImageNum + ".png";
	  marker.setImage(guyImageUrl);
	}
	function placeFovMarker()
	{
		map.removeOverlay(fovMarker);
		fovMarker = new GMarker(currentLatLng, {clickable: false});
		map.addOverlay(fovMarker);
		return;
	}

	function onNewLocation(lat, lng) 
	{
		var latlng = new GLatLng(lat, lng);
		marker.setLatLng(latlng);
	}
	function onDragEnd() 
	{
	  var latlng = marker.getLatLng();
	  if (panorama) 
	  {
	    panoClient.getNearestPanorama(latlng, onResponse);
	  }
	}
	function onResponse(response) 
	{
	  if (response.code != 200) 
	  {
	    marker.setLatLng(lastMarkerLocation);
	  } 
	  else 
	  {
	    var latlng = new GLatLng(response.Location.lat, response.Location.lng);
	    marker.setLatLng(latlng);
	    lastMarkerLocation = latlng;
	    openPanoramaBubble();
	  }
	}
