<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myEvnt = event_fetch();
	$myLnks = event_location_links($myEvnt['EventID'], $myEvnt['VenueID']);
	$crumbs = array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar',$myLnks['This'] => $myEvnt['Title']);
	
	get_header();?>

	<meta property="og:title" content="<?php echo $myEvnt['Title'];?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo cal_url().'/?eID='.$myEvnt['EventID'];?>"/>
	<meta property="og:image" content="<?php echo cal_url().'/img/like/event.png';?>"/>
	<meta property="og:site_name" content="<?php echo cal_name();?>"/>
	<meta property="og:description" content="<?php echo str_replace('"',"'",cleanBreaks(strip_tags($myEvnt['Description'])));?>"/>
	<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script>
	//<!--
	function togThis(doTog, doLink){
		if(document.getElementById(doTog).style.display == 'none'){
			document.getElementById(doTog).style.display = 'block';
			document.getElementById(doLink).innerHTML = '<?php echo event_lang('Less');?>';
		} else {
			document.getElementById(doTog).style.display = 'none';
			document.getElementById(doLink).innerHTML = '<?php echo event_lang('More');?>';
		}
	}
	//-->
	</script>
<?php
    get_map_js($myEvnt['Venue_Lat'], $myEvnt['Venue_Lon'], 1, cal_url().'/img/pins/pushpin.png', 1, $myLnks['Venue_Directions']);?>
	
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
		<article itemscope itemtype="http://schema.org/Event">
			<header>
				<h1 itemprop="name"><?php echo $myEvnt['Title'].' at '.$myEvnt['Venue_Name'];?></h1>
				<h2 class="date"><?php echo $myEvnt['Date'];?></h2>
				| <time itemprop="startDate" content="<?php echo $myEvnt['Timestamp'];?>"><?php echo $myEvnt['Time'];?></time>
			</header>
			<div id="tools">
				<div id="cmnt_cnt" class="comment icon"><?php get_comments_link($myEvnt['CommentsID'],$myEvnt['CommentsURL'],1)?></div>
				
				<h2>&nbsp;</h2>
				<a href="<?php echo cal_url() . '/index.php?com=send&amp;eID='.$myEvnt['EventID'];?>" class="icon email"><?php echo event_lang('EmailToFriend');?></a><br />
				<?php echo ($myEvnt['Bitly'] != '') ? '
				<a href="'.$myEvnt['Bitly'].'.qrcode" target="_blank" rel="nofollow" class="icon qr">'.event_lang('QRCode').'</a><br />' : '';?>

		<?php
				if($myEvnt['RSVP'] == 1){?>
				<h2>&nbsp;</h2>
		<?php	event_rsvp_meter($myEvnt['RSVP_Spaces'],$myEvnt['RSVP_Taken'],250);?><br />
				<b><?php echo $myEvnt['RSVP_Taken'];?></b> <?php echo event_lang('Of');?> <b><?php echo $myEvnt['RSVP_Spaces'];?></b> <?php echo event_lang('SpacesTaken');?>
		<?php		if($myEvnt['RSVP_Active'] == 0){?>
				<br /><br />
				<?php event_rsvp_closed($myEvnt['RSVP_Open'],$myEvnt['RSVP_Close']);?>
		<?php		} else {?>
				<br /><br />
				<?php event_rsvp_link($myEvnt['RSVP_Spaces'],$myEvnt['RSVP_Taken'],$myEvnt['RSVP_Close']);?>	
		<?php		}
				}?>
				
				<h2>&nbsp;</h2>
				<a href="<?php echo $myLnks['Event_iCal'];?>" class="icon ical">iCalendar</a><br />
				<a href="<?php echo $myLnks['Event_GoogleCal'];?>" class="icon google_cal" target="_blank"><?php echo event_lang('CalendarG');?></a><br />
				<a href="<?php echo $myLnks['Event_YahooCal'];?>" class="icon yahoo" target="_blank"><?php echo event_lang('CalendarY');?></a><br />
				<a href="<?php echo $myLnks['Event_LiveCal'];?>" class="icon live" target="_blank"><?php echo event_lang('CalendarW');?></a><br />
				
				
		<?php	if($myEvnt['SeriesID'] != ''){?>
				<h2>&nbsp;</h2>
				<h3>Schedule</h3>
		<?php	event_series($myEvnt['SeriesID'],$myEvnt['Date'],3,'%d %B, %Y');
				}?>
				
			</div>
			<div class="social">
				<div class="socialT">
					<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $myLnks['This'];?>" data-text="<?php build_tweet($myEvnt['Title'].' @ '.$myEvnt['Venue_Name'].' - '.$myEvnt['Time'].' '.event_lang('On').' '.stampToDate($myEvnt['DateRaw'],$hc_cfg[24]));?>" data-count="horizontal">Tweet</a>
					<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
				<div class="socialG">
					<g:plusone size="medium" count="true" href="<?php echo $myLnks['This'];?>"></g:plusone>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				</div>
				<div class="socialF">
					<div class="fb-like" data-href="<?php echo $myLnks['This'];?>" data-send="false" data-layout="button_count" data-width="75" data-show-faces="false"></div>
				</div>
				<div class="socialL">
					<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
					<script type="in/share" data-url="<?php echo urlencode($myLnks['This']);?>" data-counter="right"></script>
				</div>
			</div>
			<div class="description" itemprop="description"><?php echo $myEvnt['Description'];?></div>
			
	<?php	if($myEvnt['Cost'] != ''){?>
			<h2>Cost</h2>
			<?php echo $myEvnt['Cost'];?>
	<?php	}?>
			
			<h2>Venue
			<?php echo ($myEvnt['Venue_Address'].$myEvnt['Venue_City'].$myEvnt['Venue_Region'].$myEvnt['Venue_Postal'].$myEvnt['Venue_Lat'].$myEvnt['Venue_Lon'] != '') ? '
				<a href="'.$myLnks['Venue_Directions'].'" target="_blank" itemscope itemtype="http://schema.org/GeoCoordinates">
					<img src="img/icons/car.png" width="16" height="16" alt="" />
					<meta itemprop="latitude" content="'.$myEvnt['Venue_Lat'].'" />
					<meta itemprop="longitude" content="'.$myEvnt['Venue_Lon'].'" /></a>' : '';?>
				<?php echo ($myEvnt['Venue_Address'].$myEvnt['Venue_City'].$myEvnt['Venue_Region'].$myEvnt['Venue_Postal'].$myEvnt['Venue_Lat'].$myEvnt['Venue_Lon'] != '') ? '
				<a href="'.$myLnks['Venue_Weather'].'" target="_blank"><img src="img/icons/weather.png" width="16" height="16" alt="" /></a>' : '';?>
				<?php echo ($myEvnt['VenueID'] > 0) ? '
				<a href="'.$myLnks['Venue_Profile'].'"><img src="img/icons/card.png" width="16" height="16" alt="" /></a>' : '';?>
			</h2>
	<?php	if($myEvnt['VenueID'] > 0){?>
			<div id="map_canvas_single"></div>
	<?php	}?>
			<div id="location" itemprop="location" itemscope itemtype="http://schema.org/Place">
				<span itemprop="name" class="location"><?php echo $myEvnt['Venue_Name'];?></span>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress"><?php echo $myEvnt['Venue_Address'];?><br/>
					<?php echo ($myEvnt['Venue_Address2'] != '') ? $myEvnt['Venue_Address2'].'<br />':'';?></span>
					<span itemprop="addressLocality"><?php echo $myEvnt['Venue_City'];?></span>, <span itemprop="addressRegion"><?php echo $myEvnt['Venue_Region'];?></span>
					<span itemprop="postalCode"><?php echo $myEvnt['Venue_Postal'];?></span><br/>
					<span itemprop="addressCountry"><?php echo $myEvnt['Venue_Country'];?></span>
				</div>
				<br />
				<?php echo ($myEvnt['Venue_Email'] != '') ? cleanEmailLink($myEvnt['Venue_Email'],' ',event_lang('Email').' ').'<br />' : '';?>
				<?php echo ($myEvnt['Venue_Phone'] != '') ? event_lang('Phone').' '.$myEvnt['Venue_Phone'].'<br />' : '';?>
				<?php echo ($myEvnt['Venue_URL'] != '') ? event_lang('Website').' <a href="'.$myLnks['Venue_URL'].'" target="_blank">'.event_lang('ClickToVisit').'</a><br />' : '';?>
			</div>
	<?php			
			if($myEvnt['Contact'].$myEvnt['Contact_Email'].$myEvnt['Contact_Phone'].$myEvnt['Contact_URL'] != ''){?>
			<h2 class="sub">Event Contact</h2>
			<?php echo ($myEvnt['Contact'] != '') ? $myEvnt['Contact'].'<br />' : '';?>
			<?php echo ($myEvnt['Contact_Email'] != '') ? cleanEmailLink($myEvnt['Contact_Email'],$myEvnt['Title'],event_lang('Email').' ').'<br />' : '';?>
			<?php echo ($myEvnt['Contact_Phone'] != '') ? event_lang('Phone').' '.$myEvnt['Contact_Phone'].'<br />' : '';?>
			<?php echo ($myEvnt['Contact_URL'] != '') ? event_lang('Website').' <a href="'.$myLnks['Event_URL'].'" target="_blank">'.event_lang('ClickToVisit').'</a><br />' : '';?>

	<?php	}?>
			
			<h2>Similar Events</h2>
			<div id="categories">
				<?php event_categories($myEvnt['EventID']);?>
			</div>
			
			<h2>&nbsp;</h2>
			<?php get_comments($myEvnt['CommentsID'],$myEvnt['CommentsURL'],$myEvnt['Title'],1);?>
			
		</article>
		
		<aside>
	<?php 
		mini_cal_month($myEvnt['DateRaw']);	
		get_side();?>
		
		</aside>
	</section>
	
	<?php get_footer(); ?>