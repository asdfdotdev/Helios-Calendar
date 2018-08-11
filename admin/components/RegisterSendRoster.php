<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();

	$token = (isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : '';
	if(!check_form_token($token))
		go_home();
	
	include(HCLANG.'/admin/register.php');
	
	$target = AdminRoot.'/index.php';
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	$result = doQuery("SELECT e.Title, e.StartDate, e.StartTime, e.TBD, e.ContactName, e.ContactEmail, er.Space, COUNT(r.PkID) as SpacesTaken
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
						LEFT JOIN " . HC_TblPrefix . "registrants r ON (e.PkID = r.EventID)
					WHERE e.PkID = '" . $eID . "' AND r.IsActive = 1
					GROUP BY e.Title, e.StartDate, e.StartTime, e.TBD, e.ContactName, e.ContactEmail, er.Space");
	
	if(hasRows($result)){
		$eName = mysql_result($result,0,4);
		$eEmail = mysql_result($result,0,5);
		$filename = clean_filename(cleanQuotes(strip_tags(mysql_result($result,0,0))));
		if(mysql_result($result,0,3) == 0){
			$eventTime = stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg[23]);
		} elseif(mysql_result($result,0,3) == 1){
			$eventTime = $hc_lang_register['AllDay'];
		} elseif(mysql_result($result,0,3) == 2){
			$eventTime = $hc_lang_register['TBA'];
		}
		
		$rsvps = array(fetch_event_rsvp($eID,$hc_lang_register['CSVHeader']), cIn($filename).".csv", 'text/csv');
		
		if(hasRows($result)){
			$subject = $hc_lang_register['RosterSubject'] . ' - ' . CalName;			
			
			$message = '<p>
	'.$hc_lang_register['RosterEmailA'].'
</p>
<p>
	'.$hc_lang_register['RosterEmailC'].' '.(strftime($hc_cfg[24].' '.$hc_cfg[23],strtotime(SYSDATE.' '.SYSTIME))).'
</p>
<p>
	<b>'.mysql_result($result,0,0).'</b><br />'.stampToDate(mysql_result($result,0,1), $hc_cfg[14]).' - '.$eventTime.'
	<br /><a href="'.CalRoot.'/index.php?eID='.$eID.'">'.CalRoot.'/index.php?eID='.$eID.'</a>
</p>
<p>
	<b>'.$hc_lang_register['SpacesRequested'].'</b> '.mysql_result($result,0,7).' '.$hc_lang_register['Of'].' '.mysql_result($result,0,6).'
</p>';
			
			reMail($eName, $eEmail, $subject, $message, $hc_cfg[79], $hc_cfg[78], $rsvps);

			$target = AdminRoot.'/index.php?com=eventedit&eID='.$eID."&msg=6";
		}
	}

	header("Location: ".$target);
?>