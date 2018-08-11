<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	include(HCLANG . '/public/syndication.php');
	include(HCLANG . '/public/locations.php');
	include(HCPATH . HCINC . '/functions/locations.php');
	include(HCPATH . HCINC . '/functions/maps.php');
	
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

map_locations();

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
				html: '<div class=\"iw\"><div class=\"iw_menu\">".map_infowindow_menu()."</div><span class=\"name\">'+location[1]+'</span>'+setIW(location)+'</div>',
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
	var iw = (loc[5] != '') ? '<span class=\"address\">' + loc[5] + '</span>' : '';
	iw += (loc[6] != '') ? '<span class=\"address2\">'+loc[6]+'</span>' : '';
	iw += (loc[7] != '') ? '<span class=\"city\">'+loc[7]+'</span>' : '';
	iw += (loc[8] != '') ? '<span class=\"region\">'+loc[8]+'</span>' : '';
	iw += (loc[10] != '') ? '<span class=\"postal\">'+loc[10]+'</span>' : '';
	iw += (loc[9] != '') ? '<span class=\"country\">'+loc[9]+'</span>' : '';
	iw += (loc[13] == 1) ? '<span class=\"website\">".$hc_lang_synd['Website']." <a href=\"".CalRoot."/link/index.php?tID=4&amp;oID='+loc[0]+'\" target=\"_blank\">".$hc_lang_synd['ClickToVisit']."</a></span>' : '';
	iw += (loc[14] != '') ? '<span class=\"phone\">".$hc_lang_synd['Phone']." '+loc[14]+'</span>' : '';
	iw += (loc[2] != '' && loc[3] != '') ? '<span class=\"geo\">'+loc[2]+', '+loc[3]+'</span>' : '';
	iw += (loc[11] != '') ? '<span class=\"events\">".$hc_lang_synd['EventCnt']." '+loc[11]+'</span>' : '<span class=\"events\">".$hc_lang_synd['EventCnt']." 0</span>';
	iw += (loc[12] != '') ? '<span class=\"next\">".$hc_lang_synd['Next']." '+loc[12]+'</span>' : '';
	return iw;
}
function showIW(loc){
	map.setZoom(16);
	google.maps.event.trigger(gmarkers[loc], 'click');
}";