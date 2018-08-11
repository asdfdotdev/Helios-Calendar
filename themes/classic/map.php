<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();
	
	get_map_js(map_venue_lat(), map_venue_lon(), 2, cal_url().'/img/pins/default.png');?>
</head>
<body onload="map_init()">
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
		
		<div id="map_menu">
			<?php get_map_menu();?>
		</div>
		<div id="map_list" class="map_list_withoutlist" style="display:none;"></div>
		<div id="map_canvas" class="map_canvas_withoutlist" style="width:auto;"></div>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>