<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	include(HCLANG . '/public/syndication.php');
	include(HCLANG . '/public/locations.php');
	include(HCPATH . HCINC . '/functions/locations.php');
	
	if($hc_cfg[69] == 0)
		exit();
	
	echo "
var map;
var gmarkers = [];
var infowindow;
var loc_list;
var center = new google.maps.LatLng(".$hc_cfg[42].", ".$hc_cfg[43].");

function HomeControl(controlDiv, map){
	controlDiv.style.padding = '5px';
	var controlUI = document.createElement('DIV');
		controlUI.id = 'pb_button';
		controlUI.title = '".$hc_lang_synd['Powered']." Helios Calendar';
		controlDiv.appendChild(controlUI);
	var controlText = document.createElement('DIV');
		controlText.innerHTML = '".$hc_lang_synd['Visit']."';
		controlUI.appendChild(controlText);
	google.maps.event.addDomListener(controlUI, 'click', function() {
		window.location.href = '".CalRoot."';
		});
}

function initialize() {
	var myOptions = {
		zoom: ".$hc_cfg[41].",
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
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.TOP_LEFT
		}};
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	setMarkers(map, locations);
	infowindow = new google.maps.InfoWindow({content: ''});
	var homeControlDiv = document.createElement('DIV');
	var homeControl = new HomeControl(homeControlDiv, map);
	homeControlDiv.index = 1;
	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
}";

gmap_locations();

echo "
function setMarkers(map, markers) {
	for (var i = 0; i < markers.length; i++) {
		if(markers[i] != undefined){
			var location = markers[i];
			var siteLatLng = new google.maps.LatLng(location[2], location[3]);
			var marker = new google.maps.Marker({
				position: siteLatLng,
				map: map,
				title: location[1],
				html: '<div class=\"iw\"><div class=\"iw_menu\">";
gmap_infowindow_menu();
echo "</div><b>'+location[1]+'</b><br />'+setIW(location)+'</div>',
				icon: '".CalRoot."/img/pins/default.png'
				});
			google.maps.event.addListener(marker, 'click', function(){
				infowindow.setContent(this.html);
				infowindow.open(map, this);
			});

			gmarkers.push(marker);
		}
	}
}
function setIW(loc){
	var iw = (loc[5] != '') ? loc[5] : '';
	iw += (loc[6] != '') ? '<br />'+loc[6] : '';
	iw += (loc[7] != '') ? '<br />'+loc[7] : '';
	iw += (loc[8] != '') ? ', '+loc[8] : '';
	iw += (loc[10] != '') ? ' '+loc[10] : '';
	iw += (loc[9] != '') ? '<br />'+loc[9] : '';
	iw += '<br />';
	iw += (loc[13] == 1) ? '<br />".$hc_lang_synd['Website']." <a href=\"".CalRoot."/link/index.php?tID=4&amp;oID='+loc[0]+'\" target=\"_blank\">".$hc_lang_synd['ClickToVisit']."</a>' : '';
	iw += (loc[14] != '') ? '<br />".$hc_lang_synd['Phone']." '+loc[14] : '';
	iw += (loc[2] != '' && loc[3] != '') ? '<br /><br />'+loc[2]+', '+loc[3] : '';
	iw += '<br />';
	iw += (loc[11] != '') ? '<br />".$hc_lang_synd['EventCnt']." '+loc[11] : '';
	iw += (loc[12] != '') ? '<br />".$hc_lang_synd['Next']." '+loc[12] : '';
	return iw;
}
function showIW(loc){
	map.setZoom(16);
	google.maps.event.trigger(gmarkers[loc], 'click');
}
function mnuMsg(what){
	document.getElementById('iw_msg').innerHTML = what;
}";