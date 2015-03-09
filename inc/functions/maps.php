<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	/**
	 * 
	 */
	function get_map_menu(){
		global $hc_cfg, $hc_lang_locations;
		echo '
	<ul>
		<li><a href="javascript:;" onclick="map_near_me();" rel="nofollow" id="me_link">'.$hc_lang_locations['ButtonMe'].'</a></li>
		'.(($hc_cfg[55] == 1) ? '<li><a href="javascript:;" onclick="map_list();" rel="nofollow" id="list_link">'.$hc_lang_locations['ButtonLocShow'].'</a></li>':'').'
		<li><a href="javascript:;" onclick="map_reset();" rel="nofollow" id="reset_link">'.$hc_lang_locations['Reset'].'</a></li>
	</ul>';
	}
	/**
	 * Output JavaScript required for current embedded map provider (configured within admin console settings). Activate map by adding call to map_init() in page onload event.
	 * @since 2.1.0
	 * @version 2.2.1
	 * @param string $lat Latitude for embedded map centerpoint.
	 * @param string $lon Longitude for embedded map centerpoint.
	 * @param integer $type Embedded map type: 1 = Single Point Map (Pushpin at passed coordinates), 2 = Multiple Point Map (Pushpins from Venues Cache), 3 = Multiple Point Map (Pushpins from Passed Venue Array)
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @param integer $pin_action Pushpin action type. 0 = Do nothing when pushpin is clicked (Default), 1 = Link, Opens $pin_content in new window, 2 = Info Window Dialog, Opens $pin_content in infowindow dialog. (Single Point Map Only)
	 * @param string $pin_content Content of $pin_action. URL, for Link. Text/HTML, for Info Window. (Single Point Map Only)
	 * @param array $venues Array of pushpin coordinates.
	 * @return void
	 */
	function get_map_js($lat,$lon,$type,$pin_icon = '',$pin_action = 0,$pin_content = '', $venues = array()){
		global $hc_cfg;
		
		if(!is_numeric($lat) || !is_numeric($lon))
			return -1;
		
		switch($hc_cfg[55]){
			case 1:
				echo '	<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/mapg.css" />
	<script src="http://maps.google.com/maps/api/js?v='.$hc_cfg[61].'&amp;sensor=false"></script>
	<script>
	//<!--';
				if($type == 1){
					gmap_init($lat,$lon,$pin_icon,$pin_action,$pin_content);
				} elseif($type == 2) {
					gmap_venues_init($hc_cfg[42],$hc_cfg[43],$pin_icon);
				} else {
					gmap_venues_init($lat,$lon,$pin_icon,$venues);
				}
				
				echo '
	//-->
	</script>';
				break;
			case 2:
				switch($hc_cfg[94]){
					case 1:
						echo '
	<script src="http://maps.google.com/maps/api/js?v='.$hc_cfg[61].'&amp;sensor=false"></script>';
						break;
					case 3:
						echo '
	<script src="http://api.maps.yahoo.com/ajaxymap?v=3.8&amp;appid='.$hc_cfg[95].'"></script>';
						break;
				}
				
				echo '
	<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/mapol.css" />
	<script src="'.CalRoot.'/inc/javascript/OpenLayers.js"></script>
	<script>
	//<!--';
				if($type == 1){
					olayers_init($lat,$lon,$pin_icon,$pin_action,$pin_content);
				} elseif($type == 2) {
					olayers_venues_init($lat,$lon,$pin_icon);
				} else {
					olayers_venues_init($lat,$lon,$pin_icon,$venues);
				}
				
				echo '
	//-->
	</script>';
				break;
		}
	}
	/**
	 * Output Google Maps powered map_init() function for single point map.
	 * @param string $lat Latitude for embedded map centerpoint & pushpin.
	 * @param string $lon Longitude for embedded map centerpoint & pushpin.
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @param integer $pin_action Pushpin action type. 0 = Do nothing when pushpin is clicked (Default), 1 = Link, Opens $pin_content in new window, 2 = Info Window Dialog, Opens $pin_content in infowindow dialog. (Single Point Map Only)
	 * @param string $pin_content Content of $pin_action. URL, for Link. Text/HTML, for Info Window. (Single Point Map Only)
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function gmap_init($lat,$lon,$pin_icon,$pin_action,$pin_content){
		$pin_icon = ($pin_icon == '') ? CalRoot.'/img/pins/pushpin.png' : $pin_icon;
		
		echo '
	function map_init() {
		var latlng = new google.maps.LatLng('.$lat.','.$lon.');
		
		var myOptions = {
			zoom: '.map_zoom(1).',
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			
			scaleControl: true,
			scaleControlOptions: {
				position: google.maps.ControlPosition.BOTTOM_LEFT
			},
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL,
				position: google.maps.ControlPosition.TOP_LEFT
			}
			};
		var map = new google.maps.Map(document.getElementById("map_canvas_single"),
			myOptions);
		
		var infowindow = new google.maps.InfoWindow({
		    content: ""
		});

		var marker = new google.maps.Marker({
		    position: latlng,
		    map: map,
		    icon: "'.$pin_icon.'"
		});';
		
		if($pin_action == 1 && $pin_content != ''){
			echo '
		google.maps.event.addListener(marker, "click", function() {
			window.open("'.cOut(cIn(html_entity_decode($pin_content))).'","_blank");
		});';
		} elseif($pin_action == 2 && $pin_content != ''){
			echo '
		var infowindow = new google.maps.InfoWindow({
		    content: "'.html_entity_decode($pin_content).'"
		});
		google.maps.event.addListener(marker, "click", function() {
			infowindow.open(map,marker);
		});';
		}

		echo '
	}';
	}
	/**
	 * Output Google Maps powered map_init() function for multi-point venues map. Uses map_locations() for pin locations cache.
	 * @param string $lat Latitude for embedded map centerpoint.
	 * @param string $lon Longitude for embedded map centerpoint.
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @param array $venues [optional] Array of Venues for uncached multi-pushpin maps. Default:NULL
	 * @since 2.1.0
	 * @version 2.2.1
	 * @return void
	 */
	function gmap_venues_init($lat,$lon,$pin_icon,$venues = array()){
		global $hc_lang_core, $hc_lang_locations;
		
		$cached = (count($venues) == 0) ? true : false;
		$pin_icon = ($pin_icon == '') ? CalRoot.'/img/pins/default.png' : $pin_icon;
		
		echo '
	var map, infowindow, loc_list, near_me, myLatLng;
	var gmarkers = [];
	var center = new google.maps.LatLng('.$lat.','.$lon.');
	function map_init() {
		var myOptions = {
			zoom: '.map_zoom(0).',
			center: center,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
			},
			scaleControl: true,
			scaleControlOptions: {
				position: google.maps.ControlPosition.BOTTOM_LEFT
			},
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.'.($cached ? 'LARGE':'SMALL').',
				position: google.maps.ControlPosition.TOP_LEFT
			},
			key: ""
			};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		setMarkers(map, locations);
		infowindow = new google.maps.InfoWindow({content: ""});
	}
	function map_near_me(){
		if (navigator.geolocation) {
			go = 1;
			function found(position){
				myLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(myLatLng);
				map.setZoom(15);
				var marker = new google.maps.Marker({
					position: myLatLng, 
					map: map,
					icon: "'.CalRoot.'/img/pins/me.png",
					title:""
				});
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMe'].'";
			}
			function lost(){
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMe'].'";
				alert("'.$hc_lang_locations['NoNearMe'].'");
			}
			if(myLatLng == undefined){
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMeWorking'].'";
				navigator.geolocation.getCurrentPosition(found, lost);
			} else {
				map.setCenter(myLatLng);
				map.setZoom(15);
			}
		} else {
			alert("'.$hc_lang_locations['CantNearMe'].'");
		}
	}
	function setMarkers(map, markers) {
		loc_list = "<ul>";		
		for (var i = 0; i < markers.length; i++) {
			if(markers[i] != undefined){
				var location = markers[i];
				var siteLatLng = new google.maps.LatLng(location[2], location[3]);
				var marker = new google.maps.Marker({
					position: siteLatLng,
					map: map,
					title: location[1],
					html: \'<div class="iw"><div class="iw_menu">'.map_infowindow_menu().'</div><b>\'+location[1]+\'</b><br />\'+map_set_iw(location)+\'</div>\',
					icon: "'.$pin_icon.'",
					id: location[0]
					});
				google.maps.event.addListener(marker, "click", function(){
				'.($cached ? '
					infowindow.setContent(this.html);
					infowindow.open(map, this);':'
					window.open("'.CalRoot.'/index.php?com=location&lID='.'"+this.id,"_blank");').'

				});
				gmarkers.push(marker);
				loc_list += \'<li><a href="javascript:;" onclick="javascript:map_show_iw(\'+(gmarkers.length-1)+\');">\'+location[1]+\'</a></li>\';
			}
		}

		loc_list += \'</ul>\';
		'.($cached ? 'document.getElementById("map_list").innerHTML = loc_list;':'').'
	}
	function map_list(){
		if(document.getElementById("map_list").style.display == "none"){
			document.getElementById("list_link").innerHTML = "'.$hc_lang_locations['ButtonLocHide'].'";
			document.getElementById("map_list").style.display = "block";
			document.getElementById("map_canvas").className = "map_canvas_withlist";
		} else {
			document.getElementById("list_link").innerHTML = "'.$hc_lang_locations['ButtonLocShow'].'";
			document.getElementById("map_list").style.display = "none";
			document.getElementById("map_canvas").className = "map_canvas_withoutlist";
		}
	}
	function map_show_iw(loc){
		map.setZoom(16);
		google.maps.event.trigger(gmarkers[loc], "click");
	}
	function map_reset(){
		map.setCenter(center),
		map.setZoom('.map_zoom(0).')
		infowindow.close();
	}
';
		if(count($venues) == 0){
			map_locations();
			map_shared_js();
		} else {		
			echo '
	var locations = '.  json_encode($venues).';
		
	function map_set_iw(loc){
		var iw = "";
		return iw;
	}';
		}
	}	
	/**
	 * Output OpenLayers Library powered map_init() function for single point map.
	 * @param string $lat Latitude for embedded map centerpoint & pushpin.
	 * @param string $lon Longitude for embedded map centerpoint & pushpin.
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @param integer $pin_action Pushpin action type. 0 = Do nothing when pushpin is clicked (Default), 1 = Link, Opens $pin_content in new window, 2 = Info Window Dialog, Opens $pin_content in infowindow dialog. (Single Point Map Only)
	 * @param string $pin_content Content of $pin_action. URL, for Link. Text/HTML, for Info Window. (Single Point Map Only)
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function olayers_init($lat,$lon,$pin_icon,$pin_action,$pin_content){
		global $hc_cfg, $hc_lang_core;
		
		$pin_icon = ($pin_icon == '') ? CalRoot.'/img/pins/pushpin.png' : $pin_icon;
		$layers = olayers_get_baselayers($lat, $lon, $hc_cfg[94]);
		
		echo '
	OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
	OpenLayers.ImgPath = "'.$hc_cfg['OLImages'].'";
	
	var map, layer, popup;

	function map_init(){   
		var map = new OpenLayers.Map("map_canvas_single");
		map.addControl(new OpenLayers.Control.LayerSwitcher());
		map.addControl(new OpenLayers.Control.KeyboardDefaults());
		'.$layers.'
		map.setCenter(mapLonLat,'.map_zoom(1).');
		marker = new OpenLayers.Layer.Markers("'.$hc_lang_core['EventVenue'].'");
		var size = new OpenLayers.Size(32,37);
		var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
		var icon = new OpenLayers.Icon(\''.$pin_icon.'\',size,offset);
		marker.addMarker(new OpenLayers.Marker(mapLonLat,icon));
		map.addLayer(marker);';

		if($pin_action == 1 && $pin_content != ''){
			echo '
		marker.events.register("mousedown", marker, function(evt) { window.open("'.cOut(cIn(html_entity_decode($pin_content))).'","_blank"); OpenLayers.Event.stop(evt); });';
		} elseif($pin_action == 2 && $pin_content != ''){
			echo '
		marker.events.register("mousedown", marker, function(evt) {
			feature = new OpenLayers.Feature(layer,lonLat);
			feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {"autoSize": true});
			if (popup == null) {
				popup = feature.createPopup(true);
				popup.setContentHTML("<div id=\"osm_iw\">'.cOut(cIn(html_entity_decode($pin_content))).'</div>");
				marker.map.addPopup(popup);
			} else {
				popup.toggle();
			}
			OpenLayers.Event.stop(evt);
		});';
		}
		
		echo '
        }';
	}
	/**
	 * Output OpenLayers Library powered map_init() function for multi-point venues map. Uses map_locations() for pin locations cache.
	 * @param string $lat Latitude for embedded map centerpoint.
	 * @param string $lon Longitude for embedded map centerpoint.
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @param array $venues [optional] Array of Venues for uncached multi-pushpin maps. Default:NULL
	 * @since 2.1.0
	 * @version 2.2.0
	 * @return void
	 */
	function olayers_venues_init($lat,$lon,$pin_icon, $venues = array()){
		global $hc_cfg, $hc_lang_core, $hc_lang_locations;

		$cached = (count($venues) == 0) ? true : false;
		$pin_icon = ($pin_icon == '') ? CalRoot.'/img/pins/pushpin.png' : $pin_icon;
		$layers = olayers_get_baselayers($lat, $lon, $hc_cfg[94]);
		
		echo '
	OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
	OpenLayers.ImgPath = "'.$hc_cfg['OLImages'].'";
	
	var map, markers, marker, layer, popup, cur_popup, meLonLat, mapLonLat;
	var omarkers = [];
	
	function map_init(){   
		map = new OpenLayers.Map("map_canvas", {
		controls: [
			new OpenLayers.Control.Navigation(),
			new OpenLayers.Control.PanZoom'.($cached ? 'Bar':'').'(),
			new OpenLayers.Control.LayerSwitcher(),
			new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.KeyboardDefaults()
			'.(($hc_cfg[94] != 3) ? ', new OpenLayers.Control.ScaleLine()':'').'
		]});'.$layers.'

		markers = new OpenLayers.Layer.Markers("'.$hc_lang_core['EventVenues'].'");
		map.addLayer(markers);
		map.setCenter(mapLonLat,'.map_zoom(0).');
		map_add_venues(locations);
	}
	function map_add_venues(locations){
		for(var i = 0; i < locations.length; i++) {
			if(locations[i] != undefined){
				var location = locations[i];
				lonLat = new OpenLayers.LonLat(location[3],location[2]).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
				map_create_marker(lonLat, \'<div class="iw"><div class="iw_menu">'.map_infowindow_menu().'</div><b>\'+location[1]+\'</b><br />\'+map_set_iw(location)+\'</div>\',location[0]);
			}
		}		
	}
	function map_create_marker(lonLat, popupContent, locID) {
		var size = new OpenLayers.Size(32,37);
		var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
		var icon = new OpenLayers.Icon(\''.$pin_icon.'\',size,offset);
		var feature = new OpenLayers.Feature(markers, lonLat); 
		feature.closeBox = true;
		feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {\'autoSize\': true});
		feature.data.popupContentHTML = popupContent;
		feature.data.overflow = "hidden";
		feature.data.icon = icon;
		var marker = feature.createMarker();
		marker.events.register("mousedown", feature, function (evt) {
			'.($cached ? '
			if(cur_popup != null && cur_popup.visible())
				cur_popup.hide();	
			if (this.popup == null) {
				this.popup = this.createPopup(this.closeBox);
				map.addPopup(this.popup);
				this.popup.show();
			} else {
				this.popup.toggle();
			}
			cur_popup = this.popup;
			OpenLayers.Event.stop(evt);' : '
			window.open("'.CalRoot.'/index.php?com=location&lID="+locID,"_blank"); OpenLayers.Event.stop(evt);').'
		});
		markers.addMarker(marker);
	}
	function map_reset(){
		map.setCenter(mapLonLat,'.map_zoom(0).');
		if(cur_popup != null && cur_popup.visible())
			cur_popup.hide();
	}
	function map_near_me(){
		if (navigator.geolocation) {
			go = 1;
			function found(position){
				var size = new OpenLayers.Size(32,37);
				var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
				var icon = new OpenLayers.Icon(\''.CalRoot.'/img/pins/me.png\',size,offset);
				me = new OpenLayers.Layer.Markers("'.$hc_lang_core['NearMe'].'");
				map.addLayer(me);
				meLonLat = new OpenLayers.LonLat(position.coords.longitude,position.coords.latitude).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
				me.addMarker(new OpenLayers.Marker(meLonLat,icon));
				map.setCenter(meLonLat,15);
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMe'].'";
			}
			function lost(){
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMe'].'";
				alert("'.$hc_lang_locations['NoNearMe'].'");
			}
			if(meLonLat == undefined){
				document.getElementById("me_link").innerHTML = "'.$hc_lang_locations['ButtonMeWorking'].'";
				navigator.geolocation.getCurrentPosition(found, lost);
			} else {
				map.setCenter(meLonLat,15);
			}
		} else {
			alert("'.$hc_lang_locations['CantNearMe'].'");
		}
	}
	';
		if(count($venues) == 0){
			map_locations();
			map_shared_js();
		} else {		
			echo '
	var locations = '.  json_encode($venues).';
		
	function map_set_iw(loc){
		var iw = "";
		return iw;
	}';
		}
	}
	/**
	 * Output shared JavaScript code used for both Google Maps & OpenLayers Libraries.
	 * @since 2.1.0
	 * @version 2.2.1
	 * @return void
	 */
	function map_shared_js(){
		global $hc_lang_locations;
		
		echo "
	function map_set_iw(loc){
		var iw = (loc[5] != '') ? '<span class=\"address\">' + loc[5] + '</span>' : '';
		iw += (loc[6] != '') ? '<span class=\"address2\">'+loc[6]+'</span>' : '';
		iw += (loc[7] != '') ? '<span class=\"city\">'+loc[7]+'</span>' : '';
		iw += (loc[8] != '') ? '<span class=\"region\">'+loc[8]+'</span>' : '';
		iw += (loc[10] != '') ? '<span class=\"postal\">'+loc[10]+'</span>' : '';
		iw += (loc[9] != '') ? '<span class=\"country\">'+loc[9]+'</span>' : '';
		iw += (loc[13] == 1) ? '<span class=\"website\">".$hc_lang_locations['Website']." <a href=\"".CalRoot."/link/index.php?tID=4&amp;oID='+loc[0]+'\" target=\"_blank\">".$hc_lang_locations['ClickToVisit']."</a></span>' : '';
		iw += (loc[14] != '') ? '<span class=\"phone\">".$hc_lang_locations['Phone']." '+loc[14]+'</span>' : '';
		iw += (loc[2] != '' && loc[3] != '') ? '<span class=\"geo\">'+loc[2]+', '+loc[3]+'</span>' : '';
		iw += (loc[11] != '') ? '<span class=\"events\">".$hc_lang_locations['EventCnt']." '+loc[11]+'</span>' : '<span class=\"events\">".$hc_lang_locations['EventCnt']." 0</span>';
		iw += (loc[12] != '') ? '<span class=\"next\">".$hc_lang_locations['Next']." '+loc[12]+'</span>' : '';
		return iw;
	}";
	}
	/**
	 * Output JavaScript for single tile provider's layers for use with OpenLayers library. DO NOT modify this function to output multiple tile providers together. Such modification may violate some Third Party's Terms of Service.
	 * @param string $lat Latitude for embedded map centerpoint.
	 * @param string $lon Longitude for embedded map centerpoint.
	 * @param integer $provider map provider to use for generated layers for (1 = Google, 2 = Bing, 3 = Yahoo, 4 = MapQuest)
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function olayers_get_baselayers($lat, $lon, $provider){
		global $hc_cfg;
		
		$attribution = 'Powered by: <a href="http://www.openlayers.org/" rel="nofollow" target="_blank">OpenLayers</a> | ';
		
		switch($provider){
			case 1:
				$layers = '
		var gmap = new OpenLayers.Layer.Google("Google Streets", {numZoomLevels: 20});
		var gphy = new OpenLayers.Layer.Google("Google Physical",{type: google.maps.MapTypeId.TERRAIN});
		var ghyb = new OpenLayers.Layer.Google("Google Hybrid",{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});
		var gsat = new OpenLayers.Layer.Google("Google Satellite",{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22});
		map.addLayers([gmap, gphy, ghyb, gsat]);
		mapLonLat = new OpenLayers.LonLat('.$lon.','.$lat.').transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());';
				break;
			case 2:
				$layers = '
		var apiKey = "'.$hc_cfg[96].'";		
		var road = new OpenLayers.Layer.Bing({name: "Bing (Road)", key: apiKey, type: "Road"});
		var hybrid = new OpenLayers.Layer.Bing({name: "Bing (Hybrid)", key: apiKey, type: "AerialWithLabels"});
		var aerial = new OpenLayers.Layer.Bing({name: "Bing (Aerial)", key: apiKey, type: "Aerial"});
		map.addLayers([road, hybrid, aerial]);
		mapLonLat = new OpenLayers.LonLat('.$lon.','.$lat.').transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());';
				break;
			case 3:
				$layers = '
		var yahoo = new OpenLayers.Layer.Yahoo("Yahoo");
		map.addLayers([yahoo]);
		mapLonLat = new OpenLayers.LonLat('.$lon.','.$lat.');';
				break;
			case 4:
				$layers = '
		arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.jpg","http://otile2.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.jpg","http://otile3.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.jpg","http://otile4.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.jpg"];
		arrayAerial = ["http://oatile1.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg","http://oatile2.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg","http://oatile3.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg","http://oatile4.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg"];
		baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM, {attribution: \'Map data (c) <a href="http://www.openstreetmap.org/" rel="nofollow" target="_blank">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/" rel="nofollow" target="_blank">CC-BY-SA</a><br />Tiles Courtesy of <a href="http://www.mapquest.com/" rel="nofollow" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">\'});
		baseAerial = new OpenLayers.Layer.OSM("MapQuest Open Aerial Tiles", arrayAerial, {attribution: \'<div id="mapquest_aerial">Map data (c) <a href="http://www.openstreetmap.org/" rel="nofollow" target="_blank">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/" rel="nofollow" target="_blank">CC-BY-SA</a><br />Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency<br />Tiles Courtesy of <a href="http://www.mapquest.com/" rel="nofollow" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>\'});
		map.addLayers([baseOSM, baseAerial]);
		mapLonLat = new OpenLayers.LonLat('.$lon.','.$lat.').transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());';
				break;
		}
		
		return $layers;
	}
	/**
	 * Create JavaScript array() variable named "locations" with location entries for use with location Google map. Saves array to cache if cache not present.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @return void
	 */
	function map_locations(){
		global $hc_cfg;		
		
		if(!file_exists(HCPATH.'/cache/lmap'.SYSDATE)){
			purge_cache_map();
			
			$cnt = 0;

			ob_start();
			$fp = fopen(HCPATH.'/cache/lmap'.SYSDATE, 'w');
			
			$result = doQuery("SELECT l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Country, l.Zip, l.Lat, l.Lon, COUNT(e.LocID), MIN(e.StartDate), l.URL, l.Phone
							FROM " . HC_TblPrefix . "locations l
								LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID)
							WHERE l.Lat IS NOT NULL AND l.Lon IS NOT NULL AND l.Lat != '' AND l.Lon != '' AND l.IsActive = 1 AND
								e.LocID > 0 AND e.IsActive = 1 AND e.IsApproved = 1 AND e.PkID IS NOT NULL AND e.StartDate >= '" . cIn(SYSDATE) . "'
							GROUP BY l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Country, l.Zip, l.Lat, l.Lon, l.URL, l.Phone
							HAVING COUNT(e.LocID) > 0
							ORDER BY l.Name");
			if(hasRows($result)){
				echo '
	var locations = [';
				while($row = mysql_fetch_row($result)){
					echo '
		["'.$row[0].'","'.cOut($row[1]).'","'.$row[8].'","'.$row[9].'","'.cOut($row[1]).'","'.cOut($row[2]).'","'.cOut($row[3]).'","'.cOut($row[4]).'","'.cOut($row[5]).'","'.cOut($row[6]).'","'.cOut($row[7]).'","'.$row[10].'","'.stampToDate($row[11], $hc_cfg[14]).'","'.(($row[12] != '' && $row[12] != 'http://') ? '1' : '0').'","'.cOut($row[13]).'"],';
					++$cnt;
				}
				echo '
	];';
			}
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/lmap'.SYSDATE);
	}
	/**
	 * Get map zoom setting.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $type map zoom setting to use, 0 = use location map zoom setting, 1 = use event detail map zoom setting (Default:0)
	 * @return string Map zoom level.
	 */
	function map_zoom($type = 0){
		global $hc_cfg;
		
		return ($type == 0) ? $hc_cfg[41] : $hc_cfg[27];
	}
	/**
	 * Get venue map center point latitude setting from admin console settings.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return string Map center point latitude.
	 */
	function map_venue_lat(){
		global $hc_cfg;
		
		return $hc_cfg[42];
	}
	/**
	 * Get venue map center point longitude setting from admin console settings.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return string Map center point longitude.
	 */
	function map_venue_lon(){
		global $hc_cfg;
		
		return $hc_cfg[43];
	}
	/**
	 * Get menu icons w/links for the location map infowindow.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function map_infowindow_menu(){
		global $hc_cfg, $hc_lang_locations;
		
		return '<a target="_blank" href="'.CalRoot.'/?lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuCalendar'].'" class="calendar" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/index.php?com=location&lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuProfile'].'" class="profile" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/link/index.php?tID=3&oID=0&lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuWeather'].'" class="weather" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/link/index.php?tID=2&oID=0&lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuDirection'].'" class="directions" /></a>'
		.(($hc_cfg[108] == 1) ? '<a href="webcal://'.substr(CalRoot, 7).'/link/SaveLocation.php?lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuiCalendar'].'" class="ical" /></a>':'').''
		.(($hc_cfg[106] == 1) ? '<a target="_blank" href="'.CalRoot.'/rss/l.php?lID=\'+location[0]+\'" title="'.$hc_lang_locations['MnuRSS'].'" class="rss" /></a>':'');
	}
	/**
	 * Deletes all location map related cache files from cache directory.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @return void
	 */
	function purge_cache_map(){
		if(file_exists(HCPATH.'/cache/lmap*') && count(glob(HCPATH.'/cache/lmap*')) > 0){
			foreach(glob(HCPATH.'/cache/lmap*') as $file){
				unlink($file);
			}
		}
	}
	/**
	 * Override admin console map settings for map library & map provider.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $type Library to use. 1 = Google Maps, 2 = OpenLayers (Default: Current Admin Console Setting)
	 * @param integer $map May provider to use (Only Available for OpenLayer Library). 1 = Google Maps, 2 = Bing Maps, 3 = Yahoo Maps, 4 = MapQuest
	 * @return void
	 */
	function set_map_library_type($type, $map){
		global $hc_cfg;
		if(!is_numeric($type) || $type < 1 || $type > 2)
			return 0;
		$hc_cfg[55] = $type;
		
		if(!is_numeric($map) || $map < 0 || $map > 4)
			return 0;
		$hc_cfg[94] = $map;
	}
	/**
	 * Override default OpenLayers image path for custom interface images.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $url URL of images directory. Include trailing slash.
	 * @return void
	 */
	function set_olayers_image_path($url){
		global $hc_cfg;
		
		if($url != '')
			$hc_cfg['OLImages'] = cIn(strip_tags($url));
	}
?>