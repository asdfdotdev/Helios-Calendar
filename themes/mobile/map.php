<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

	<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=false"></script>
	<script type="text/javascript">
	//<!--
	var map;
	var gmarkers = [];
	var infowindow;
	var loc_list;
	var myLatLng;
	var center = new google.maps.LatLng(<?php gmap_center();?>);
	function initialize() {
		var myOptions = {
			zoom: <?php gmap_zoom(0);?>,
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
				style: google.maps.ZoomControlStyle.LARGE,
				position: google.maps.ControlPosition.TOP_LEFT
			},
			key: ''
			};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		setMarkers(map, locations);
		infowindow = new google.maps.InfoWindow({content: ''});
	}
	<?php gmap_locations();?>
	
	function setMarkers(map, markers) {
		loc_list = '<ul>';
		for (var i = 0; i < markers.length; i++) {
			if(markers[i] != undefined){
				var location = markers[i];
				var siteLatLng = new google.maps.LatLng(location[2], location[3]);
				var marker = new google.maps.Marker({
					position: siteLatLng,
					map: map,
					id: 'info_window',
					title: location[1],
					html: '<div class="iw"><div class="iw_menu"><?php gmap_infowindow_menu();?></div><b>'+location[1]+'</b><br />'+setIW(location)+'</div>',
					icon: '<?php gmap_pin_icon(cal_url().'/img/pins/default.png');?>'
					});
				google.maps.event.addListener(marker, "click", function(){
					infowindow.setContent(this.html);
					infowindow.open(map, this);
				});

				gmarkers.push(marker);
				loc_list += '<li><a href="javascript:;" onclick="javascript:showIW('+(gmarkers.length-1)+');">'+location[1]+'</a></li>';
			}
		}
		loc_list += '</ul>';
		document.getElementById('map_list').innerHTML = loc_list;
	}
	function setIW(loc){
		var iw = (loc[5] != '') ? loc[5] : '';
		iw += (loc[6] != '') ? ', '+loc[6] : '';
		iw += (loc[7] != '') ? '<br />'+loc[7] : '';
		iw += (loc[8] != '') ? ', '+loc[8] : '';
		iw += (loc[10] != '') ? ' '+loc[10] : '';
		iw += (loc[9] != '') ? ' '+loc[9] : '';
		return iw;
	}
	function showIW(loc){
		map.setZoom(16);
		google.maps.event.trigger(gmarkers[loc], "click");
		document.getElementById('location_button').innerHTML = '<?php echo location_lang('ButtonLocShow');?>';
		document.getElementById('map_list').style.display = 'none';
		window.scrollTo(0, 1);
	}
	function mapHome(){
		map.setCenter(center),
		map.setZoom(<?php gmap_zoom(0);?>),
		infowindow.close()
	}
	function nearMe(map){
		if (navigator.geolocation) {
			go = 1;
			function found(position){
				myLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(myLatLng);
				map.setZoom(15);
				var my_marker = new google.maps.Marker({
					position: myLatLng, 
					map: map,
					icon: '<?php gmap_pin_icon(cal_url().'/img/pins/me.png');?>',
					title:""
				});
				document.getElementById('me_button').innerHTML = '<?php echo location_lang('ButtonMe');?>';
			}
			function lost(){
				document.getElementById('me_button').innerHTML = '<?php echo location_lang('ButtonMe');?>';
				alert('<?php echo location_lang('NoNearMe');?>');
			}
			if(myLatLng == undefined){
				document.getElementById('me_button').innerHTML = '<?php echo location_lang('ButtonMeWorking');?>';
				navigator.geolocation.getCurrentPosition(found, lost);
			} else {
				map.setCenter(myLatLng);
				map.setZoom(15);
			}
		} else {
			alert('<?php echo location_lang('CantNearMe');?>');
		}
	}
	function togList(){	
		if(document.getElementById('map_list').style.display == 'none'){
			document.getElementById('location_button').innerHTML = '<?php echo location_lang('ButtonLocHide');?>';
			document.getElementById('map_list').style.display = 'block';
		} else {
			document.getElementById('location_button').innerHTML = '<?php echo location_lang('ButtonLocShow');?>';
			document.getElementById('map_list').style.display = 'none';
		}
	}
	function mnuMsg(what){
		document.getElementById('iw_msg').innerHTML = what;
	}
	//-->
	</script>
</head>
<body onload="initialize()">
	<?php my_menu(1);?>
	
	<nav class="sub">
		<ul>
			<li><a href="javascript:;" id="me_button" onclick="nearMe(map);" class="">Near Me</a></li>
			<li><a href="javascript:;" id="location_button" onclick="togList();" class="">Show List</a></li>
			<li><a href="javascript:;" onclick="mapHome();" class="">Reset</a></li>
		</ul>
	</nav>
	<section>
		<div id="map_list" style="display:none;"></div>
		<div id="map_canvas"></div>
	</section>
	
	<?php get_footer(); ?>