<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$myLoc = location_fetch();
	$crmbAdd[$myLoc['Link_This']] = $myLoc['Name'];
	
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
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>	
		<article itemscope itemtype="http://schema.org/Place">
			<header>
				<div class="social">
					<div id="cmnt_cnt"><span class="arrow"><span>&nbsp;</span></span><?php get_comments_link($myLoc['CommentsID'],$myLoc['CommentsURL'],1)?></div>
					<div class="socialT">
						<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $myLoc['Link_This'];?>" data-text="<?php build_tweet($myLoc['Name']);?>" data-count="horizontal">Tweet</a>
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
				<h1 itemprop="name"><?php echo $myLoc['Name'];?></h1>
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
					<div id="map_canvas_single"></div>
				</div>
				<span itemprop="description"><?php echo ($myLoc['Image'] != '') ? '<img src="'.$myLoc['Image'].'" id="limage_p" />':'';?><?php echo $myLoc['Description'];?></span>
			</div>
			<div id="detail_left">
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
			</div>
			<div id="detail_right">
				<h2><?php echo location_lang('LocationTools');?></h2>
				
				<a href="<?php echo $myLoc['Link_Weather'];?>" class="icon weather" target="_blank"><?php echo location_lang('Weather');?></a><br />
				<a href="<?php echo $myLoc['Link_Directions'];?>" target="_blank" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates" class="icon directions">
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
				
				<a target="_blank" class="share" href="http://del.icio.us/post?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="img/share/delicious.png" alt="Delicious" title="Delicious" /></a>
				<a target="_blank" class="share" href="http://digg.com/submit?phase=2&amp;url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/digg.png" alt="Digg" title="Digg" /></a>
				<a href="javascript:" class="share" onclick="Evernote.doClip({title: '<?php echo $myLoc['Name'];?>',url: '<?php echo $myLoc['Link_This'];?>',code: 'Refr6223',contentId: 'evernote',footer: '<img src=&quot;/about/media/img/logo.png&quot; />',suggestNotebook: '<?php echo location_lang('Events');?>',suggestTags: '<?php echo location_lang('Events').','.cal_name();?>',providerName: '<?php echo cal_name();?>',<?php echo ($myLoc['Lat'] != '' && $myLoc['Lon'] != '') ? 'latitude: '.$myLoc['Lat'].',longitude: '.$myLoc['Lon'].',' : '';?>styling: 'full' });return false;"><img src="<?php echo cal_url();?>/img/share/evernote.png" alt="Evernote" title="Evernote" /></a>
				<script type="text/javascript" src="http://static.evernote.com/noteit.js"></script>
				<a target="_blank" class="share" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/google.png" alt="Google Bookmarks" title="Google Bookmarks" /></a>
				<a target="_blank" class="share" href="https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=<?php echo $link;?>&amp;title=<?php echo $title;?>&amp;top=1"><img src="<?php echo cal_url();?>/img/share/live.png" alt="Live" title="Live" /></a>
				<a target="_blank" class="share" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo $link;?>&amp;t=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/myspace.png" alt="MySpace" title="MySpace" /></a>
				<a target="_blank" class="share" href="http://reddit.com/submit?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/reddit.png" alt="Reddit" title="Reddit" /></a>
				<a target="_blank" class="share" href="http://www.stumbleupon.com/submit?title=<?php echo $title;?>&amp;url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/stumbleit.png" alt="Stumble Upon" title="Stumble Upon" /></a>
				<a target="_blank" class="share" href="http://technorati.com/faves?add=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/technorati.png" alt="Technorati" title="Technorati" /></a>
				<a target="_blank" class="share" href="http://www.tumblr.com/share?v=3&amp;t=<?php echo $title;?>&amp;s=&amp;u=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/tumblr.png" alt="tumblr" title="tumblr" /></a>
				<a href="javascript:;" onclick="togThis('share_more', 'share_link');" id="share_link"><?php echo location_lang('More');?></a>

				<div id="share_more" style="display:none;">
					<a target="_blank" class="share" href="http://barrapunto.com/submit.pl?subj=<?php echo $title;?>&amp;story=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/barrapunto.png" alt="barrapunto" title="barrapunto" /></a>
					<a target="_blank" class="share" href="http://bitacoras.com/anotaciones/<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/bitacoras.png" alt="bitacoras" title="bitacoras" /></a>
					<a target="_blank" class="share" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=<?php echo $link;?>&amp;Title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/blinklist.png" alt="blinklist" title="blinklist" /></a>
					<a target="_blank" class="share" href="http://www.buddymarks.com/add_bookmark.php?bookmark_title=<?php echo $title;?>&amp;bookmark_url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/buddymarks.png" alt="buddymarks" title="buddymarks" /></a>
					<a target="_blank" class="share" href="http://www.designfloat.com/submit.php?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/designfloat.png" alt="designfloat" title="designfloat" /></a>
					<a target="_blank" class="share" href="http://www.diigo.com/post?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/diigo.png" alt="diigo" title="diigo" /></a>
					<a target="_blank" class="share" href="http://www.dotnetkicks.com/kick/?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/dotnetkicks.png" alt="dotnetkicks" title="dotnetkicks" /></a>
					<a target="_blank" class="share" href="http://www.dzone.com/links/add.html?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/dzone.png" alt="dzone" title="dzone" /></a>
					<a target="_blank" class="share" href="http://www.ekudos.nl/artikel/nieuw?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/ekudos.png" alt="ekudos" title="ekudos" /></a>
					<a target="_blank" class="share" href="http://faves.com/Authoring.aspx?t=<?php echo $title;?>&amp;u=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/faves.png" alt="faves" title="faves" /></a>
					<a target="_blank" class="share" href="http://globalgrind.com/submission/submit.aspx?url=<?php echo $link;?>&amp;type=Article&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/globalgrind.png" alt="globalgrind" title="globalgrind" /></a>
					<a target="_blank" class="share" href="http://www.gwar.pl/DodajGwar.html?u=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/gwar.png" alt="gwar" title="gwar" /></a>
					<a target="_blank" class="share" href="http://identi.ca/notice/new?status_textarea=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/identica.png" alt="identi.ca" title="identi.ca" /></a>
					<a target="_blank" class="share" href="http://www.kirtsy.com/submit.php?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/kirtsy.png" alt="kirtsy" title="kirtsy" /></a>
					<a target="_blank" class="share" href="http://laaik.it/NewStoryCompact.aspx?uri=<?php echo $link;?>&amp;headline=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/laaikit.png" alt="laaik.it" title="laaik.it" /></a>
					<a target="_blank" class="share" href="http://www.linkagogo.com/go/AddNoPopup?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/linkagogo.png" alt="linkagogo" title="linkagogo" /></a>
					<a target="_blank" class="share" href="http://linkarena.com/bookmarks/addlink/?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/linkarena.png" alt="linkarena" title="linkarena" /></a>
					<a target="_blank" class="share" href="http://www.linkroll.com/index.php?action=insertLink&amp;url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/linkroll.png" alt="linkroll" title="linkroll" /></a>
					<a target="_blank" class="share" href="http://www.linkter.hu/index.php?action=suggest_link&amp;url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/linkter.png" alt="linkter.hu" title="linkter.hu" /></a>
					<a target="_blank" class="share" href="http://meneame.net/submit.php?url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/meneame.png" alt="meneame" title="meneame" /></a>
					<a target="_blank" class="share" href="http://www.mister-wong.com/addurl/?bm_url=<?php echo $link;?>&amp;bm_description=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/misterwong.png" alt="mister-wong" title="mister-wong" /></a>
					<a target="_blank" class="share" href="http://www.mixx.com/submit?page_url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/mixx.png" alt="mixx" title="mixx" /></a>
					<a target="_blank" class="share" href="http://www.muti.co.za/submit?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/muti.png" alt="muti.co.za" title="muti.co.za" /></a>
					<a target="_blank" class="share" href="http://myshare.url.com.tw/index.php?func=newurl&amp;url=<?php echo $link;?>&amp;desc<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/myshare.png" alt="myshare" title="myshare" /></a>
					<a target="_blank" class="share" href="http://www.n4g.com/tips.aspx?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/n4g.png" alt="n4g" title="n4g" /></a>
					<a target="_blank" class="share" href="http://www.netvibes.com/share?title=<?php echo $title;?>&amp;url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/netvibes.png" alt="netvibes" title="netvibes" /></a>
					<a target="_blank" class="share" href="http://www.netvouz.com/action/submitBookmark?url=<?php echo $link;?>&amp;title=<?php echo $title;?>&amp;popup=no"><img src="<?php echo cal_url();?>/img/share/netvouz.png" alt="netvouz" title="netvouz" /></a>
					<a target="_blank" class="share" href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php echo $link;?>&amp;h=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/newsvine.png" alt="newsvine" title="newsvine" /></a>
					<a target="_blank" class="share" href="http://nujij.nl/jij.lynkx?t=<?php echo $title;?>&amp;u=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/nujij.png" alt="nujij" title="nujij" /></a>
					<a target="_blank" class="share" href="http://ping.fm/ref/?link=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/ping.png" alt="ping.fm" title="ping.fm" /></a>
					<a target="_blank" class="share" href="http://ratimarks.org/bookmarks.php/?action=add&amp;address=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/ratimarks.png" alt="ratimarks" title="ratimarks" /></a>
					<a target="_blank" class="share" href="http://segnalo.alice.it/post.html.php?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/segnalo.png" alt="segnalo" title="segnalo" /></a>
					<a target="_blank" class="share" href="http://www.sphinn.com/submit.php?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/sphinn.png" alt="sphinn" title="sphinn" /></a>
					<a target="_blank" class="share" href="http://www.spurl.net/spurl.php?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/spurl.png" alt="spurl" title="spurl" /></a>
					<a target="_blank" class="share" href="http://www.squidoo.com/lensmaster/bookmark?<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/squidoo.png" alt="squidoo" title="squidoo" /></a>
					<a target="_blank" class="share" href="http://www.tagtooga.com/tapp/db.exe?c=jsEntryForm&amp;b=fx&amp;title=<?php echo $title;?>&amp;url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/tagtooga.png" alt="tagtooga" title="tagtooga" /></a>
					<a target="_blank" class="share" href="http://www.thisnext.com/pick/new/submit/sociable/?url=<?php echo $link;?>&amp;name=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/thisnext.png" alt="thisnext" title="thisnext" /></a>
					<a target="_blank" class="share" href="http://tipd.com/submit.php?url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/tipd.png" alt="tipd" title="tipd" /></a>
					<a target="_blank" class="share" href="http://www.upnews.it/submit?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/upnews.png" alt="upnews.it" title="upnews.it" /></a>
					<a target="_blank" class="share" href="http://www.webnews.de/einstellen?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/webnews.png" alt="webnews.de" title="webnews.de" /></a>
					<a target="_blank" class="share" href="http://www.webride.org/discuss/split.php?uri=<?php echo $link;?>&amp;title<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/webride.png" alt="webride" title="webride" /></a>
					<a target="_blank" class="share" href="http://www.wikio.com/vote?url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/wikio.png" alt="wikio" title="wikio" /></a>
					<a target="_blank" class="share" href="http://www.wykop.pl/dodaj?url=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/wykop.png" alt="wykop.pl" title="wykop.pl" /></a>
					<a target="_blank" class="share" href="http://www.xerpi.com/block/add_link_from_extension?url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/xerpi.png" alt="xerpi" title="xerpi" /></a>
					<a target="_blank" class="share" href="http://buzz.yahoo.com/submit/?submitHeadline=<?php echo $title;?>&amp;submitUrl=<?php echo $link;?>"><img src="<?php echo cal_url();?>/img/share/yahoobuzz.png" alt="yahoo buzz" title="yahoo buzz" /></a>
					<a target="_blank" class="share" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo $link;?>&amp;t=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/yahoo.png" alt="yahoo myweb" title="yahoo myweb" /></a>
					<a target="_blank" class="share" href="http://yigg.de/neu?exturl=<?php echo $link;?>&amp;exttitle=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/yiggit.png" alt="yigg.de" title="yigg.de" /></a>
					<a target="_blank" class="share" href="http://tag.zurpy.com/?box=1&amp;url=<?php echo $link;?>&amp;title=<?php echo $title;?>"><img src="<?php echo cal_url();?>/img/share/zurpy.png" alt="zurpy" title="zurpy" /></a>
				</div>
			</div>
						
			<div id="loc_events">
			<h2>&nbsp;<?php location_rss_link($myLoc['LocID']);?></h2>
			<?php location_events(10);?>
			
			</div>
			
			<?php get_comments($myLoc['CommentsID'],$myLoc['CommentsURL'],$myLoc['Name'],1);?>
		</article>
	</section>

	<?php get_side(); ?>
	
	<?php get_footer(); ?>