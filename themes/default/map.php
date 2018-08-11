<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
		
	get_header();
	
	get_map_js(map_venue_lat(), map_venue_lon(), 2, cal_url().'/img/pins/default.png');?>

</head>
<body onload="map_init()" id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
<?php	
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>
	
		<div id="map_menu">
			<?php get_map_menu();?>
		</div>
		<div id="map_list" class="map_list_withoutlist" style="display:none;"></div>
		<div id="map_canvas" class="map_canvas_withoutlist" style="width:auto;"></div>
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>