<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myLoc = location_fetch();
	
	get_header();?>
	
	<meta property="og:title" content="<?php echo $myLoc['Name'];?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo cal_url().'/index.php?com=location&lID='.$myLoc['LocID'];?>"/>
	<meta property="og:image" content="<?php echo cal_url().'/img/like/venue.png';?>"/>
	<meta property="og:site_name" content="<?php echo cal_name();?>"/>
	<meta property="og:description" content="<?php echo str_replace('"',"'",cleanBreaks(strip_tags($myLoc['Description'])));?>"/>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<?php my_menu(1);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=search">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit">Submit</a></li>
		</ul>
	</nav>
	
	<section>	
		<article itemscope itemtype="http://schema.org/Place">
			<header>
				<h1 itemprop="name">
				<div id="cmnt_cnt"><span class="arrow"><span>&nbsp;</span></span><?php get_comments_link($myLoc['CommentsID'],$myLoc['CommentsURL'],1)?></div>
				<?php 
					echo $myLoc['Name'];?></h1>
			</header>
			<div id="evernote">
				<div id="location">
					<h2 class="location"><?php echo location_lang('Address');?></h2>
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><?php echo $myLoc['Address'];?><br/>
						<?php echo ($myLoc['Address2'] != '') ? $myLoc['Address2'].'<br />':'';?></span>
						<span itemprop="addressLocality"><?php echo $myLoc['City'];?></span>, <span itemprop="addressRegion"><?php echo $myLoc['Region'];?></span>
						<span itemprop="postalCode"><?php echo $myLoc['Postal'];?></span><br/>
						<span itemprop="addressCountry"><?php echo $myLoc['Country'];?></span>
					</div>
				</div>
				<?php echo ($myLoc['Image'] != '') ? '<img src="'.$myLoc['Image'].'" id="limage_p" />':'';?>
				<?php echo $myLoc['Description'];?>
			
			</div>
		<?php			
			if($myLoc['Email'].$myLoc['Phone'].$myLoc['Link_URL'] != ''){?>
			<h2><?php echo location_lang('Contact');?></h2>

			<?php echo ($myLoc['Email'] != '') ? cleanEmailLink($myLoc['Email'],'',location_lang('Email').' ').'<br />' : '';?>
			<?php echo ($myLoc['Phone'] != '') ? location_lang('Phone') . ' '.$myLoc['Phone'].'<br />' : '';?>
			<?php echo ($myLoc['Link_URL'] != '') ? location_lang('Website') . ' <a href="'.$myLoc['Link_URL'].'" target="_blank">'.location_lang('ClickToVisit').'</a><br />' : '';
			}

			if($myLoc['Lat'] != '' && $myLoc['Lon'] != ''){?>
			<h2><?php echo location_lang('Geo');?></h2>

			<?php echo $myLoc['Lat'].', '.$myLoc['Lon'];
			}?>

			<h2><?php echo location_lang('LocationTools');?></h2>

			<a href="<?php echo $myLoc['Link_Weather'];?>" class="icon weather" target="_blank"><?php echo location_lang('Weather');?></a><br />
			<a href="<?php echo $myLoc['Link_Directions'];?>" target="_blank" itemprop="geo" itemscope="itemscope" itemtype="http://http://schema.org/GeoCoordinates" class="icon directions">
				<meta itemprop="latitude" content="<?php echo $myLoc['Lat'];?>" />
				<meta itemprop="longitude" content="<?php echo $myLoc['Lon'];?>" /><?php echo location_lang('Driving');?></a><br />
			<a href="<?php echo $myLoc['Link_Calendar'];?>" class="icon google_cal"><?php echo location_lang('Calendar');?></a><br />

	<?php
			$link = urlencode($myLoc['Link_This']);
			$title = urlencode($myLoc['Name']);?>

			<h2><?php echo location_lang('Share');?></h2>
			<a href="<?php echo cal_url() . '/index.php?com=send&amp;lID='.$myLoc['LocID'];?>" class="icon email"><?php echo location_lang('EmailToFriend');?></a><br />
			<?php echo ($myLoc['Bitly'] != '') ? '
			<a href="'.$myLoc['Bitly'].'.qrcode" target="_blank" rel="nofollow" class="icon qr">'.location_lang('QRCode').'</a><br />' : '';?>

			<h2><?php echo location_lang('Social');?></h2>
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
						
			<div id="loc_events">
			<h2>&nbsp;<a href="<?php echo cal_url();?>/rss/l.php?lID=<?php echo $myLoc['LocID'];?>" class="icon rss loc_rss" target="_blank" rel="nofollow"></a></h2>
			<?php location_events(10);?>
			
			</div>
			
			<?php get_comments($myLoc['CommentsID'],$myLoc['CommentsURL'],$myLoc['Name'],1);?>
		</article>
	</section>
	
	<?php get_footer(); ?>