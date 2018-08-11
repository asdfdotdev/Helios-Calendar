<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="ISO-8859-1">
	<meta http-equiv="preview-refresh" content="3600" />
	<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/core.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/style.css" />
	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/coreIE.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/ie.css" />
	<![endif]-->
	<!--[if IE 8]>
		<script src="<?php echo theme_dir();?>/js/ie8.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/ie8.css" />
	<![endif]-->
	<!--[if IE 9]>
		<meta name="msapplication-task" content="name=Powered by Helios Calendar;action-uri=http://www.HeliosCalendar.com;icon-uri=<?php echo cal_url();?>/favicon.ico"/>
		<meta name="msapplication-task" content="name=askrefresh;action-uri=http://twitter.com/askrefresh;icon-uri=<?php echo theme_dir().'/img/jl/t.ico';?>"/>
		<meta name="msapplication-navbutton-color" content="#FF6600;" />
	<![endif]-->
	<link rel="shortcut icon" href="<?php echo cal_url();?>/favicon.ico">
	<?php hc_header();?>
	
	<script>
	//<!--
	var listDivs = ["hc_featured","hc_popular","hc_newest"];
	var listLinks = ["hc_l","hc_c","hc_r"];
	function toggleList(show){var i = 0;while(i < listDivs.length){document.getElementById(listDivs[i]).style.display = (i == show) ? "block" : "none";document.getElementById(listLinks[i]).className = (i == show) ? "on" : "off";i++;}}
	window.onscroll = function (e) {var pos = (window.pageYOffset) ? window.pageYOffset : document.documentElement.scrollTop;document.getElementById('bread_top').style.display = (pos > 0) ? 'block' : 'none';}
	//-->
	</script>
	<script>
	try {
		window.external.msSiteModeClearJumplist();
		window.external.msSiteModeCreateJumplist('<?php echo CalName;?>');
		<?php echo (get_twitter() != '') ? "window.external.msSiteModeAddJumpListItem('".get_twitter()."', 'http://twitter.com/".get_twitter()."', '".theme_dir()."/img/jl/t.ico', 'self');" : "";?>
			
		window.external.msSiteModeAddJumpListItem('Newsletter Archive', '<?php echo cal_url();?>/index.php?com=archive', '<?php echo theme_dir().'/img/jl/6.ico';?>', 'self');
		window.external.msSiteModeAddJumpListItem('Search Events', '<?php echo cal_url();?>/index.php?com=search', '<?php echo theme_dir().'/img/jl/5.ico';?>', 'self');
		window.external.msSiteModeAddJumpListItem('Venue Map', '<?php echo cal_url();?>/index.php?com=location', '<?php echo theme_dir().'/img/jl/4.ico';?>', 'self');
		window.external.msSiteModeAddJumpListItem('Submit Event', '<?php echo cal_url();?>/index.php?com=submit', '<?php echo theme_dir().'/img/jl/3.ico';?>', 'self');
		window.external.msSiteModeAddJumpListItem('Browse By Month', '<?php echo cal_url();?>/index.php?b=1', '<?php echo theme_dir().'/img/jl/2.ico';?>', 'self');
		window.external.msSiteModeAddJumpListItem('Browse By Week', '<?php echo cal_url();?>/index.php?b=0', '<?php echo theme_dir().'/img/jl/1.ico';?>', 'self');
		window.external.msSiteModeShowJumplist();
	} catch(err) {}
	</script>