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
	
	include(HCLANG . '/public/send.php');

	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,2);
	
	$myName = cIn(strip_tags(cleanBreaks($_POST['hc_fx1'])));
	$myEmail = cIn(strip_tags(cleanBreaks($_POST['hc_fx2'])));
	$friendName = cIn(strip_tags(cleanBreaks($_POST['hc_fx3'])));
	$friendEmail = cIn(strip_tags(cleanBreaks($_POST['hc_fx4'])));
	$sendMsg = cleanBreaks(nl2br(strip_tags($_POST['hc_fx5'])));
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? cIn(strip_tags($_POST['eID'])) : 0;
	$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? cIn(strip_tags($_POST['tID'])) : 0;

	if($tID == 0)
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
	else
		$result = doQuery("SELECT Name, Address, Address2, City, State, Zip, Country FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $eID . "'");
	
	if(hasRows($result) && $myName != '' && $myEmail != '' && $friendName != '' && $friendEmail != ''){
		$message = '<p>' . cOut($sendMsg) . '</p>';
		$message .= '<p><b>' . mysql_result($result,0,0) . '</b><br />';
		
		if($tID == 0){
			$where = '/index.php?com=send&eID=';
			$subject = CalName . " " . $hc_lang_sendtofriend['SubjectE']  . " " . $myName;
			$message .=  stampToDate(mysql_result($result,0,1), $hc_cfg[14]) . ' - ';
			if(mysql_result($result,0,3) == 0)
				$message .= stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg[23]);
			elseif(mysql_result($result,0,3) == 1)
				$message .= $hc_lang_sendtofriend['AllDay'];
			elseif(mysql_result($result,0,3) == 2)
				$message .= $hc_lang_sendtofriend['TBA'];
			
			$message .= '<br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . CalRoot . '/index.php?eID=' . $eID . '</a></p>';
		} else {
			$where = '/index.php?com=send&lID=';
			$subject = CalName . " " . $hc_lang_sendtofriend['SubjectL']  . " " . $myName;
			$message .= buildAddress(mysql_result($result,0,1),mysql_result($result,0,2),mysql_result($result,0,3),mysql_result($result,0,4),mysql_result($result,0,5),mysql_result($result,0,6),$hc_lang_config['AddressType']);
			$message .= '<br /><a href="' . CalRoot . '/index.php?com=location&lID=' . $eID . '">' . CalRoot . '/index.php?com=location&lID=' . $eID . '</a></p>';
		}
		
		$message .= '<p>' . $hc_lang_sendtofriend['From'] . '<br />' . $myName . ' (' . $myEmail . ')</p>';
		$message .= '<p>' . $hc_lang_sendtofriend['AutoNotice'] . ' ' . $hc_cfg[78];
		
		reMail($friendName,$friendEmail,$subject,$message,$myName,$myEmail);
		
		doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EntityID, IPAddress, SendDate, TypeID)
				Values('" . $myName . "', '" . $myEmail . "', '" . $friendName . "', '" . $friendEmail . "', '" . cleanSpecialChars(str_replace('<br>','\n',$message)) . "', '" . $eID . "',
				'" . cIn(strip_tags($_SERVER["REMOTE_ADDR"])) . "', '" . date("Y-m-d") . "', '" . $tID . "')");
		doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = '" . $eID . "'");
		
		header("Location: " . CalRoot . $where . $eID . "&msg=1");
	} else {
		header("Location: " . CalRoot . "/");
	}
?>