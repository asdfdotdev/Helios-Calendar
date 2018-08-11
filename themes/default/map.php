<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

	<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=true"></script>
	<script type="text/javascript">
	//<!--
	var map;
	var gmarkers = [];
	var infowindow;
	var loc_list;
	var near_me;
	var myLatLng;
	var center = new google.maps.LatLng(<?php gmap_center();?>);
	function HomeControl(controlDiv, map){
		controlDiv.style.padding = '5px';
		var controlUI = document.createElement('DIV');
			controlUI.id = 'home_button';
			controlUI.title = '<?php echo location_lang('ButtonHomeTitle');?>';
			controlDiv.appendChild(controlUI);
		var controlText = document.createElement('DIV');
			controlText.innerHTML = '<?php echo location_lang('ButtonHome');?>';
			controlUI.appendChild(controlText);
		google.maps.event.addDomListener(controlUI, 'click', function() {
			map.setCenter(center),
			map.setZoom(<?php gmap_zoom(0);?>),
			infowindow.close()
			});
	}
	function LocationControl(controlDiv, map){
		controlDiv.style.padding = '5px';
		var controlUI = document.createElement('DIV');
			controlUI.id = 'location_button';
			controlUI.title = '<?php echo location_lang('ButtonLocTitle');?>';
			controlDiv.appendChild(controlUI);
		var controlText = document.createElement('DIV');
			controlText.innerHTML = '<?php echo location_lang('ButtonLocShow');?>';
			controlUI.appendChild(controlText);
		google.maps.event.addDomListener(controlUI, 'click', function() {
			togList();
			});
	}
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
		var homeControlDiv = document.createElement('DIV');
			var homeControl = new HomeControl(homeControlDiv, map);
			homeControlDiv.index = 1;
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
		var locationControlDiv = document.createElement('DIV');
			var locationControl = new LocationControl(locationControlDiv, map);
			locationControlDiv.index = 2;
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationControlDiv);
		var meControlDiv = document.createElement('DIV');
			var meControl = new NearMe(meControlDiv, map);
			meControlDiv.index = 1;
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(meControlDiv);
	}
	function NearMe(controlDiv,map) {
		controlDiv.style.padding = '5px';
		var controlUI = document.createElement('DIV');
			controlUI.id = 'me_button';
			controlUI.title = '<?php echo location_lang('ButtonMeTitle');?>';
			controlDiv.appendChild(controlUI);
		var controlText = document.createElement('DIV');
			controlText.innerHTML = '<?php echo location_lang('ButtonMe');?>';
			controlUI.appendChild(controlText);
		google.maps.event.addDomListener(controlUI, 'click', function() {
			show_near_me(map);
			});	
	}
	function show_near_me(map){
		if (navigator.geolocation) {
			go = 1;
			function found(position){
				myLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(myLatLng);
				map.setZoom(15);
				var marker = new google.maps.Marker({
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
		iw += (loc[6] != '') ? '<br />'+loc[6] : '';
		iw += (loc[7] != '') ? '<br />'+loc[7] : '';
		iw += (loc[8] != '') ? ', '+loc[8] : '';
		iw += (loc[10] != '') ? ' '+loc[10] : '';
		iw += (loc[9] != '') ? '<br />'+loc[9] : '';
		iw += '<br />';
		iw += (loc[13] == 1) ? '<br /><?php echo location_lang('Website');?> <a href="<?php echo cal_url();?>/link/index.php?tID=4&oID='+loc[0]+'" target="_blank"><?php echo location_lang('ClickToVisit');?></a>' : '';
		iw += (loc[14] != '') ? '<br /><?php echo location_lang('Phone');?> '+loc[14] : '';
		iw += (loc[2] != '' && loc[3] != '') ? '<br /><br />'+loc[2]+', '+loc[3] : '';
		iw += '<br />';
		iw += (loc[11] != '') ? '<br /><?php echo location_lang('EventCnt');?> '+loc[11] : '';
		iw += (loc[12] != '') ? '<br /><?php echo location_lang('Next');?> '+loc[12] : '';
		return iw;
	}
	function showIW(loc){
		map.setZoom(16);
		google.maps.event.trigger(gmarkers[loc], "click");
	}
	function togList(){	
		if(document.getElementById('map_list').style.display == 'none'){
			document.getElementById('location_button').innerHTML = '<?php echo location_lang('ButtonLocHide');?>';
			document.getElementById('map_list').style.display = 'block';
			document.getElementById('map_canvas').style.width = '74%';
		} else {
			document.getElementById('location_button').innerHTML = '<?php echo location_lang('ButtonLocShow');?>';
			document.getElementById('map_list').style.display = 'none';
			document.getElementById('map_canvas').style.width = '99%';
		}
	}
	function mnuMsg(what){
		document.getElementById('iw_msg').innerHTML = what;
	}
	//-->
	</script>
</head>
<body onload="initialize()" id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
<?php	
	$crumbs = array_merge(array('/' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>
	
	<div id="map_list" style="display:none;"></div>
	<div id="map_canvas"></div>
  
	
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>