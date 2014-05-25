<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}?>
<!doctype html>
<html lang="<?php echo get_lang_config('HTMLTemplate');?>">
<head>
	<meta charset="<?php echo get_lang_config('CharSet');?>">
	<meta http-equiv="preview-refresh" content="3600" />
	<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/core.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/style.css" />
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