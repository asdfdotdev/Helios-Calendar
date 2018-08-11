<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myLoc = location_fetch();
	
	get_header();?>
	
</head>
<body>
	<?php my_menu(0);?>
	
	<nav class="sub">
		<ul>
			<li><a href="<?php echo cal_url();?>/index.php?com=search" class="">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit" class="">Submit</a></li>
		</ul>
	</nav>
	
	<section>	
		<article itemprop="location" itemscope itemtype="http://data-vocabulary.org/Organization">
			<header>
				<h1 itemprop="name">
				<?php 
					echo '
					<div id="cmnt_cnt"><a href="#disqus_thread" data-disqus-identifier="'.$myLoc['DisqusID'].'">'.location_lang('Comments').'</a><span class="arrow"><span>&nbsp;</span></span></div>
					<script type="text/javascript">
					var disqus_shortname = \''.$hc_cfg[25].'\';
					(function () {
					var s = document.createElement(\'script\'); s.async = true;
					s.type = \'text/javascript\';
					s.src = \'http://\' + disqus_shortname + \'.disqus.com/count.js\';
					(document.getElementsByTagName(\'HEAD\')[0] || document.getElementsByTagName(\'BODY\')[0]).appendChild(s);}());
					</script>';

					echo $myLoc['Name'];?></h1>
			</header>
			<div id="evernote" itemprop="description">
				<div id="location">
					<h2 class="location"><?php echo location_lang('Address');?></h2>
					<div itemprop="address" itemscope="itemscope" itemtype="http://data-vocabulary.org/Address">
						<span itemprop="street-address"><?php echo $myLoc['Address'];?></span><br/>
						<?php echo ($myLoc['Address2'] != '') ? $myLoc['Address2'].'<br />':'';?>
						<span itemprop="locality"><?php echo $myLoc['City'];?></span>, <span itemprop="region"><?php echo $myLoc['Region'];?></span>
						<span itemprop="postal-code"><?php echo $myLoc['Postal'];?></span><br/>
						<span itemprop="country-name"><?php echo $myLoc['Country'];?></span>
					</div>
				</div>
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
			<a href="<?php echo $myLoc['Link_Directions'];?>" target="_blank" itemprop="geo" itemscope="itemscope" itemtype="http://data-vocabulary.org/Geo" class="icon directions">
				<meta itemprop="latitude" content="'.$myLoc['Venue_Lat'].'" />
				<meta itemprop="longitude" content="'.$myLoc['Venue_Lon'].'" /><?php echo location_lang('Driving');?></a><br />
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
				<g:plusone size="medium" count="true" href="<?php echo $myLnks['This'];?>"></g:plusone>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			</div>
			<div class="socialF">
				<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode($myLoc['Link_This']);?>&amp;layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=20" frameborder="0" background="transparent" class="fb_iFrame"></iframe>
			</div>
			<div class="socialL">
				<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
				<script type="in/share" data-url="<?php echo urlencode($myLoc['Link_This']);?>" data-counter="right"></script>
			</div>
						
			<div id="loc_events">
			<h2>&nbsp;<a href="<?php echo cal_url();?>/rss/l.php?lID=<?php echo $myLoc['LocID'];?>" class="icon rss loc_rss" target="_blank" rel="nofollow"></a></h2>
			<?php location_events(10);?>
			
			</div>
			
			<?php get_comments($myLoc['DisqusID'],$myLoc['DisqusURL'],$myLoc['Name'],0);?>
		</article>
	</section>
	
	<?php get_footer(); ?>