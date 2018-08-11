<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myEvnt = event_fetch();
	$myLnks = event_location_links($myEvnt['EventID'], $myEvnt['VenueID']);
	
	get_header();?>

	<meta property="og:title" content="<?php echo $myEvnt['Title'];?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo cal_url().'/?eID='.$myEvnt['EventID'];?>"/>
	<meta property="og:image" content="<?php echo cal_url().'/img/like/event.png';?>"/>
	<meta property="og:site_name" content="<?php echo cal_name();?>"/>
	<meta property="og:description" content="<?php echo str_replace('"',"'",cleanBreaks(strip_tags($myEvnt['Description'])));?>"/>
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
		<article itemscope itemtype="http://schema.org/Event">
			<header>
				<h1 itemprop="name">
				<div id="cmnt_cnt"><span class="arrow"><span>&nbsp;</span></span><?php get_comments_link($myEvnt['CommentsID'],$myEvnt['CommentsURL'],1)?></div>
				<?php 
					echo $myEvnt['Title'];?></h1>
			</header>
			<div id="evernote" itemprop="description">
				<div id="location" itemprop="location" itemscope itemtype="http://schema.org/Place">
					<h2 class="location">&nbsp;
					<?php echo ($myEvnt['Venue_Address'].$myEvnt['Venue_City'].$myEvnt['Venue_Region'].$myEvnt['Venue_Postal'].$myEvnt['Venue_Lat'].$myEvnt['Venue_Lon'] != '') ? '
					<a href="'.$myLnks['Venue_Directions'].'" target="_blank" itemprop="geo" itemscope="itemscope" itemtype="http://schema.org/GeoCoordinates">
						<img src="img/icons/car.png" width="16" height="16" alt="" />
						<meta itemprop="latitude" content="'.$myEvnt['Venue_Lat'].'" />
						<meta itemprop="longitude" content="'.$myEvnt['Venue_Lon'].'" /></a>' : '';?>
					<?php echo ($myEvnt['Venue_Address'].$myEvnt['Venue_City'].$myEvnt['Venue_Region'].$myEvnt['Venue_Postal'].$myEvnt['Venue_Lat'].$myEvnt['Venue_Lon'] != '') ? '
					<a href="'.$myLnks['Venue_Weather'].'" target="_blank"><img src="img/icons/weather.png" width="16" height="16" alt="" /></a>' : '';?>
					<?php echo ($myEvnt['VenueID'] > 0) ? '
					<a href="'.$myLnks['Venue_Profile'].'"><img src="img/icons/card.png" width="16" height="16" alt="" /></a>' : '';?>

					</h2>
					<span itemprop="name"><?php echo $myEvnt['Venue_Name'];?></span><br />
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
				<?php echo ($myEvnt['Image'] != '') ? '<img src="'.$myEvnt['Image'].'" id="eimage_d" />':'';?>
				<?php echo $myEvnt['Description'];?>
			
			</div>
			
			<h2 class="date"><?php echo $myEvnt['Date'];?></h2>
			<time itemprop="startDate" datetime="<?php echo $myEvnt['Timestamp'];?>"><?php echo $myEvnt['Time'];?></time>

	<?php	if($myEvnt['SeriesID'] != ''){?>
			<h2><?php echo event_lang('OtherDates');?></h2>
			<?php event_series($myEvnt['SeriesID'],$myEvnt['Date']);?><br />
	<?php	}

			if($myEvnt['Contact'].$myEvnt['Contact_Email'].$myEvnt['Contact_Phone'].$myEvnt['Contact_URL'] != ''){?>
			<h2><?php echo event_lang('Contact');?></h2>
			<?php echo ($myEvnt['Contact'] != '') ? $myEvnt['Contact'].'<br />' : '';?>
			<?php echo ($myEvnt['Contact_Email'] != '') ? cleanEmailLink($myEvnt['Contact_Email'],$myEvnt['Title'],event_lang('Email').' ').'<br />' : '';?>
			<?php echo ($myEvnt['Contact_Phone'] != '') ? event_lang('Phone').' '.$myEvnt['Contact_Phone'].'<br />' : '';?>
			<?php echo ($myEvnt['Contact_URL'] != '') ? event_lang('Website').' <a href="'.$myLnks['Event_URL'].'" target="_blank">'.event_lang('ClickToVisit').'</a><br />' : '';?>

	<?php	}

			if($myEvnt['Cost'] != ''){?>
			<h2><?php echo event_lang('Cost');?></h2>
			<?php echo $myEvnt['Cost'];?>
	<?php	}

			if($myEvnt['RSVP'] == 1){?>
			<h2><?php echo event_lang('RSVP');?></h2>
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

			<h2><?php echo event_lang('Save');?></h2>
			<a href="<?php echo $myLnks['Event_GoogleCal'];?>" class="icon google_cal" target="_blank"><?php echo event_lang('CalendarG');?></a><br />
			<a href="<?php echo $myLnks['Event_YahooCal'];?>" class="icon yahoo" target="_blank"><?php echo event_lang('CalendarY');?></a><br />
			<a href="<?php echo $myLnks['Event_LiveCal'];?>" class="icon live" target="_blank"><?php echo event_lang('CalendarW');?></a><br />

		<?php
			$link = urlencode($myLnks['This']);
			$title = urlencode($myEvnt['Title']);?>
			<h2><?php echo event_lang('Share');?></h2>
			<a href="<?php echo cal_url() . '/index.php?com=send&amp;eID='.$myEvnt['EventID'];?>" class="icon email"><?php echo event_lang('EmailToFriend');?></a><br />
			<?php echo ($myEvnt['Bitly'] != '') ? '
			<a href="'.$myEvnt['Bitly'].'.qrcode" target="_blank" rel="nofollow" class="icon qr">'.event_lang('QRCode').'</a><br />' : '';?>
			
			<h2><?php echo event_lang('Social');?></h2>
			
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
			
			<h2><?php echo event_lang('Categories');?></h2>
			<div id="categories">
				<?php event_categories($myEvnt['EventID']);?>
			</div>
			
			<?php get_comments($myEvnt['CommentsID'],$myEvnt['CommentsURL'],$myEvnt['Title'],1);?>
		</article>
	</section>
	
	<?php get_footer(); ?>