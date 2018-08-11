<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body onload="map_init()">
	<a name="top"></a>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<?php my_menu(0);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
		<?php
			my_menu_user();?>
			
		</ul>
	</nav>
	
	<section id="digest">
		<?php digest_welcome();?>
		
		<hr />
		
		<article class="dgst">
			<h3>Recent Event Updates:</h3>
			<?php digest_event_list(10,1,'%B %d');?>
		</article>
		
		<hr />
		
		<article class="dgst">
			<h3>Recent Venue Updates:</h3>
			<?php digest_location_list(10);?>
		
		</article>
		
		<hr />
		
		<article class="dgst_news">
			<h3>Recent Newsletters:</h3>
			<?php digest_newsletter_list(5, '%B %d');?>
		
		</article>
		
		<?php get_comments('',cal_url().'/index.php?com=digest',cal_name().' Recent Updates',1);?>
		
	</section>
	
	<?php get_footer(); ?>