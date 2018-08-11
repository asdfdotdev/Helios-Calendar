<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();
	
	digest_venue_map_js();?>

	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
	<link href="https://plus.google.com/102127593390000388846" rel="publisher" />
	<script src="https://apis.google.com/js/plusone.js">{lang: 'en'}</script>
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
		
		<hr />
		
		<!-- Replace the Refresh Web Development social widgets below with your own Facebook, Twitter & Google+ badges. -->
		
		<h3>Connect with Us:</h3>
		<div class="fb">
			<div class="fb-like-box" data-href="http://www.facebook.com/askrefresh" data-width="300" data-height="270" data-show-faces="true" data-border-color="#CCCCCC" data-stream="false" data-header="false"></div>
		</div>
		<div class="gptw">
			<div class="g-plus" data-href="https://plus.google.com/102127593390000388846" data-width="375" data-height="131" data-theme="light"></div>
			<div class="twtr">
				<script>new TWTR.Widget({version: 2,type: 'profile',rpp: 1,interval: 6000,width: 'auto',height: 300,theme: {shell: {background: '#FFFFFF',color: '#666666'},tweets: {background: '#FFFFFF',color: '#666666',links: '56AA1C'}},features: {scrollbar: false,loop: true,live: false,hashtags: true,timestamp: true,avatars: false,behavior: 'all'}}).render().setUser('askrefresh').start();</script>
				<a href="https://twitter.com/askrefresh" class="twitter-follow-button" data-show-count="true" data-lang="en" data-width="220">Follow @askrefresh</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>
		
		<?php get_comments('',cal_url().'/index.php?com=digest',cal_name().' Recent Updates',1);?>
		
	</section>

	<?php get_side(); ?>
	
	<?php get_footer(); ?>