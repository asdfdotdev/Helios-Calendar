<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	post_only();
	
	include(HCLANG . '/public/news.php');

	if(!isset($_POST['dID'])){
		$target = '/index.php?com=edit&msg=2';
		$proof = $challenge = '';
		if($hc_cfg[65] == 1){
			$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
			$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
		} elseif($hc_cfg[65] == 2){
			$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
			$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
		}
		spamIt($proof,$challenge,4);

		$email = (isset($_POST['hc_fz'])) ? cIn(strip_tags($_POST['hc_fz'])) : '';
		$do = (isset($_POST['hc_fy'])) ? cIn($_POST['hc_fy']) : '';
		$stop = (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) == 1) ? 0 : 1;
		$stop = (is_numeric($do)) ? 0 : 1;

		if($stop == 0){
			$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE email = '" . $email . "' && IsConfirm = 1");
			if(hasRows($result)){
				doQuery("UPDATE " . HC_TblPrefix . "subscribers SET GUID = MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')) WHERE email = '" . $email . "'");
				$result = doQuery("SELECT FirstName, LastName, GUID FROM " . HC_TblPrefix . "subscribers WHERE email = '" . $email . "'");
				$GUID = (hasRows($result)) ?  mysql_result($result,0,2) : '';
				if($GUID != ''){
					$link = ($do == 0) ? CalRoot . '/index.php?com=signup&u=' . $GUID : CalRoot . '/index.php?com=signup&d=' . $GUID;
					$doMsg = ($do == 0) ? 'Edit' : 'Delete';
					$subject = $hc_lang_news[$doMsg.'Subject'] . ' - ' . CalName;
					$message = '<p>' . $hc_lang_news[$doMsg.'EmailA'] . ' <a href="' . $link . '">' . $link . '</a></p>';
					$message .= '<p>' .  mysql_result($result,0,0) . $hc_lang_news[$doMsg.'EmailB'] . ' ' . $hc_lang_news[$doMsg.'EmailC'] . ' ' . $hc_cfg[78] . '</p>';
					
					reMail(trim(mysql_result($result,0,0).' '.mysql_result($result,0,1)),$email,$subject,$message,$hc_cfg[79],$hc_cfg[78]);

					$target = '/index.php?com=edit&msg=1';
				}
			}
		}

		header('Location: ' . CalRoot . $target);
	} else {
		$dID = cIn(strip_tags($_POST['dID']));
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $dID . "'");
		if(hasRows($result)){
			$dID = mysql_result($result,0,0);
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = '" . $dID . "'");
		}
		
		header('Location: ' . CalRoot . '/index.php?com=signup&t=4');
	}
?>