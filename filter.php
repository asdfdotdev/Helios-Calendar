<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
		
	if(!isset($_GET['clear'])){
		if(isset($_POST['catID']))
			$_SESSION['hc_favCat'] = implode(',',array_filter($_POST['catID'],'is_numeric'));
		else
			$_SESSION['hc_favCat'] = '0';
		
		if(isset($_POST['cityName']))
			$_SESSION['hc_favCity'] = array_filter(array_filter($_POST['cityName'],'strip_tags'),'cIn');
		else
			unset($_SESSION['hc_favCity']);
		
		if(isset($_POST['cookieme'])){
			if($_SESSION['hc_favCat'])
				setcookie($hc_cfg[201].'_fn', base64_encode($_SESSION['hc_favCat']), time()+604800, '/', false, 0);
			
			if($_SESSION['hc_favCity'])
				setcookie($hc_cfg[201].'_fc', base64_encode(implode(',',$_SESSION['hc_favCity'])), time()+604800, '/', false, 0);
		} else {
			setcookie($hc_cfg[201].'_fn', '', time()-86400, '/', false, 0);
			setcookie($hc_cfg[201].'_fc', '', time()-86400, '/', false, 0);
		}
		
		$msgID = 1;
	} else {
		setcookie($hc_cfg[201] . '_fn', "", time()-86400, '/', false, 0);
		setcookie($hc_cfg[201] . '_fc', "", time()-86400, '/', false, 0);
		unset($_SESSION['hc_favCat']);
		unset($_SESSION['hc_favCity']);
		
		$msgID = 2;
	}
	if(isset($_POST['r']) && (isset($_SERVER['HTTP_REFERER']) && preg_match('(^'.CalRoot.')',$_SERVER['HTTP_REFERER'])))
		$target = cIn(strip_tags($_SERVER['HTTP_REFERER']));
	elseif (isset($_POST['f']))
		$target = CalRoot . '/index.php?com=filter&msg='.$msgID;
	else 
		$target = CalRoot;
	
	header("Location: " . $target);
 ?>