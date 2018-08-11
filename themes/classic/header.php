<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}?>
<!doctype html>
<html lang="<?php echo get_lang_config('HTMLTemplate');?>">
<head>
	<meta charset="<?php echo get_lang_config('CharSet');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/core.css" />
	<link rel="shortcut icon" href="<?php echo cal_url();?>/favicon.ico">
	<?php hc_header();?>

	<script>
	//<!--
	var listDivs = ["hc_featured","hc_popular","hc_newest"];
	var listLinks = ["hc_l","hc_c","hc_r"];
	function toggleList(show){var i = 0;while(i < listDivs.length){document.getElementById(listDivs[i]).style.display = (i == show) ? "block" : "none";document.getElementById(listLinks[i]).className = (i == show) ? "on" : "off";i++;}}
	//-->
	</script>