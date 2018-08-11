<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	
	get_header();
	
	get_map_js(map_venue_lat(), map_venue_lon(), 2, cal_url().'/img/pins/default.png');?>
</head>
<body onload="map_init()" itemscope itemtype="http://schema.org/WebPage">

	<header>
		<span>
			<?php echo cal_name();?>
			
			<div id="tag">Publishing awesome events.</div>
		</span>
		<aside>
			<?php mini_search('Search Events by Keyword',0);?>
		
		</aside>
	</header>
	<nav>
		<?php build_breadcrumb($crumbs);?>
	</nav>
	<section>
		<article>
		<div id="map_menu">
			<?php get_map_menu();?>
		</div>
		<div id="map_list" class="map_list_withoutlist" style="display:none;"></div>
		<div id="map_canvas" class="map_canvas_withoutlist" style="width:auto;"></div>
				
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>