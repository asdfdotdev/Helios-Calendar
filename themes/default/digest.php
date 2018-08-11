<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();
	
	digest_venue_map_js();?>

</head>
<body onload="map_init()" id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<?php
	$crumbs = array(cal_url().'/index.php?com=digest' => 'Home');
	build_breadcrumb($crumbs);?>
	
	<section id="digest">
		<?php digest_welcome();?>
		
		<hr />
		
		<article class="dgst dgst_bdr">
			<h3>Latest Calendar Updates:</h3>
			<?php digest_event_list(12,1,'%B %d');?>
			
		</article>
		<article class="dgst">
			<div id="map_canvas_single"></div>
			<?php digest_location_list(5);?>
		
		</article>
		
		<hr />
		
		<article class="dgst_news">
			<h3>Recent Newsletters:</h3>
			<?php digest_newsletter_list(5, '%A, %b %d');?>
		
		</article>
		
		<?php get_comments('',cal_url().'/index.php?com=digest',cal_name().' Recent Updates',1);?>
		
	</section>

	<?php get_side(); ?>
	
	<?php get_footer(); ?>