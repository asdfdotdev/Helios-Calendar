<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();
	
	get_map_js(map_venue_lat(), map_venue_lon(), 2, cal_url().'/img/pins/default.png');?>

</head>
<body onload="map_init()">
	<?php my_menu(2);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="javascript:;" id="me_link" onclick="map_near_me();">Near Me</a></li>
			<li><a href="javascript:;" id="list_link" onclick="map_list();">Show List</a></li>
			<li><a href="javascript:;" id="reset_link" onclick="map_reset();">Reset</a></li>
		</ul>
	</nav>
	<section>
		<div id="map_list" style="display:none;"></div>
		<div id="map_canvas"></div>
	</section>
	
	<?php get_footer(); ?>