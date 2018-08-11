<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$mySeries = series_fetch();
	$myMeta = series_meta($mySeries);

	get_header();
	
	series_map($mySeries);?>

	<meta property="og:title" content="<?php echo event_lang('SeriesTitle').' '.$myMeta[0]?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo cal_url().'/index.php?com=series&amp;sID='.$myMeta[2];?>"/>
	<meta property="og:image" content="<?php echo cal_url().'/img/like/event.png';?>"/>
	<meta property="og:site_name" content="<?php echo cal_name();?>"/>
	<meta property="og:description" content="<?php echo str_replace('"',"'",cleanBreaks(strip_tags($myMeta[1])));?>"/>
</head>
<body onload="map_init()" id="top" itemscope itemtype="http://schema.org/WebPage">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
	
		<div id="series"><?php series_list($mySeries);?></div>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>