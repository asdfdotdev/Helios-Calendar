<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	if(!isset($_SESSION['touchIcon'])){
		$_SESSION['touchIcon'] = strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') ? 'iOS' : 'other';
	}//end if

	header("Cache-Control: maxage=0");
	header("Expires: " . date("D\, d M Y H:i:s"));
	header("Pragma: public");

	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/mobile.php');
	$loc = setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
		
	echo '<?xml version="1.0"  encoding="' . $hc_lang_config['MobileCharSet'] . '"?>';
	echo "\n" . '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
	"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';
	echo "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
	echo "\n" . '<head>';
	echo "\n\t" . '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=' . $hc_lang_config['MobileCharSet'] . '"/>';
	echo "\n\t" . '<meta http-equiv="Cache-Control" content="max-age=0"/>';


	echo "\n\t" . '<link rel="apple-touch-icon-precomposed" href="' . MobileRoot . '/images/' . $_SESSION['touchIcon'] . '.png" type="image/png" />';
	
	echo "\n\t" . '<title>' . CalName . ' ' . $hc_lang_mobile['Mobile'] . '</title>';
	echo "\n\t" . '<link rel="stylesheet" type="text/css" href="' . CalRoot . '/css/mobile.css"/>';
	echo "\n";?>
	<script language="JavaScript" type="text/javascript">
	setTimeout(function () {
	  window.scrollTo(0, 1);
	}, 1000);
	</script>