<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	action_headers();
	header('content-type: text/html; charset=' . $hc_lang_config['CharSet']);
	
	if(isset($_SESSION['hc_cap'])){
		$capEntered = isset($_GET['capEntered']) ? cIn($_GET['capEntered']) : '';
		
		echo $_SESSION['hc_cap'] == md5($capEntered) ?
			'<span style="color:#008000;">'.$hc_lang_core['Correct'].'</span>' :
			'<span style="color:#DC143C;">'.$hc_lang_core['Incorrect'].' <a href="javascript:;" onclick="testCAPTCHA();"  tabindex="-1">'.$hc_lang_core['ConfirmAgain'].'</a>';
	} else {
		echo $hc_lang_core['RefreshPage'];
	}
?>