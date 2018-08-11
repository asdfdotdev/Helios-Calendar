<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myLoc = location_fetch();
	$crmbAdd[$myLoc['Link_This']] = $myLoc['Name'];
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	
	get_header();?>

	<meta property="og:title" content="<?php echo $myLoc['Name'];?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo cal_url().'/index.php?com=location&lID='.$myLoc['LocID'];?>"/>
	<meta property="og:image" content="<?php echo cal_url().'/img/like/venue.png';?>"/>
	<meta property="og:site_name" content="<?php echo cal_name();?>"/>
	<meta property="og:description" content="<?php echo str_replace('"',"'",cleanBreaks(strip_tags($myLoc['Description'])));?>"/>
	<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=true"></script>
	<script>
	//<!--
	function togThis(doTog, doLink){
		if(document.getElementById(doTog).style.display == 'none'){
			document.getElementById(doTog).style.display = 'block';
			document.getElementById(doLink).innerHTML = '<?php echo location_lang('Less');?>';
		} else {
			document.getElementById(doTog).style.display = 'none';
			document.getElementById(doLink).innerHTML = '<?php echo location_lang('More');?>';
		}
	}
	//-->
	</script>
<?php
	get_map_js($myLoc['Lat'], $myLoc['Lon'], 1, cal_url().'/img/pins/pushpin.png', 1, $myLoc['Link_Directions']);?>
	
</head>
<body onload="map_init()" itemscope itemtype="http://schema.org/WebPage">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
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
		<article itemscope itemtype="http://schema.org/Organization">
			<header>
				<h1 itemprop="name"><?php echo $myLoc['Name'];?></h1>
			</header>
			<div id="tools">
				<div id="cmnt_cnt" class="comment icon"><?php get_comments_link($myLoc['CommentsID'],$myLoc['CommentsURL'],1)?></div>
				
				<h2>&nbsp;</h2>
				<a href="<?php echo cal_url() . '/index.php?com=send&amp;lID='.$myLoc['LocID'];?>" class="icon email"><?php echo location_lang('EmailToFriend');?></a><br />
				<?php echo ($myLoc['Bitly'] != '') ? '
				<a href="'.$myLoc['Bitly'].'.qrcode" target="_blank" rel="nofollow" class="icon qr">'.location_lang('QRCode').'</a><br />' : '';?>
				
				<h2>&nbsp;</h2>
				<a href="<?php echo $myLoc['Link_Weather'];?>" class="icon weather" target="_blank"><?php echo location_lang('Weather');?></a><br />
				<a href="<?php echo $myLoc['Link_Directions'];?>" target="_blank" itemscope itemtype="http://schema.org/GeoCoordinates" class="icon directions">
					<meta itemprop="latitude" content="<?php echo $myLoc['Lat'];?>" />
					<meta itemprop="longitude" content="<?php echo $myLoc['Lon'];?>" /><?php echo location_lang('Driving');?></a><br />
				<a href="<?php echo $myLoc['Link_Calendar'];?>" class="icon google_cal"><?php echo location_lang('Calendar');?></a><br />
				
				<h2>&nbsp;</h2>
				<a href="<?php echo cal_url();?>/rss/l.php?lID=<?php echo $myLoc['LocID'];?>" class="icon rss loc_rss" target="_blank" rel="nofollow">Venue Events Feed</a>
			</div>
			<div class="social">
				<div class="socialT">
					<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $myLoc['Link_This'];?>" data-text="<?php build_tweet($myLoc['Title'].' @ '.$myLoc['Name'].' - '.$myLoc['Time'].' '.location_lang('On').' '.stampToDate($myLoc['DateRaw'],$hc_cfg[24]));?>" data-count="horizontal">Tweet</a>
					<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
				<div class="socialG">
					<g:plusone size="medium" count="true" href="<?php echo $myLoc['Link_This'];?>"></g:plusone>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				</div>
				<div class="socialF">
					<div class="fb-like" data-href="<?php echo $myLoc['Link_This'];?>" data-send="false" data-layout="button_count" data-width="75" data-show-faces="false"></div>
				</div>
				<div class="socialL">
					<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
					<script type="in/share" data-url="<?php echo urlencode($myLoc['Link_This']);?>" data-counter="right"></script>
				</div>
			</div>
			<div class="description" itemprop="description"><?php echo $myLoc['Description'];?></div>
			
			<h2>Details</h2>
			
			<div id="map_canvas_single"></div>
			<div id="location">
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress"><?php echo $myLoc['Address'];?><br/>
					<?php echo ($myLoc['Address2'] != '') ? $myLoc['Address2'].'<br />':'';?></span>
					<span itemprop="addressLocality"><?php echo $myLoc['City'];?></span>, <span itemprop="addressRegion"><?php echo $myLoc['Region'];?></span>
					<span itemprop="postalCode"><?php echo $myLoc['Postal'];?></span><br/>
					<span itemprop="addressCountry"><?php echo $myLoc['Country'];?></span>
				</div>
			</div>	
		<?php			
			if($myLoc['Email'].$myLoc['Phone'].$myLoc['Link_URL'] != ''){?>
			<h2 class="sub">Contact</h2>

			<?php echo ($myLoc['Email'] != '') ? cleanEmailLink($myLoc['Email'],'',location_lang('Email').' ').'<br />' : '';?>
			<?php echo ($myLoc['Phone'] != '') ? location_lang('Phone') . ' '.$myLoc['Phone'].'<br />' : '';?>
			<?php echo ($myLoc['Link_URL'] != '') ? location_lang('Website') . ' <a href="'.$myLoc['Link_URL'].'" target="_blank">'.location_lang('ClickToVisit').'</a><br />' : '';
			}
			
			if($myLoc['Lat'] != '' && $myLoc['Lon'] != ''){
				echo '
			<br />'.$myLoc['Lat'].', '.$myLoc['Lon'];
			}?>
			
			<div id="events" class="loc_events">
			<?php location_events(5);?>
				
			</div>
			
			<?php get_comments($myLoc['CommentsID'],$myLoc['CommentsURL'],$myLoc['Name'],1);?>
			
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>