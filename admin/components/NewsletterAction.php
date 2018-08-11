<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	checkIt(1);
	
	//	Variables passed from form
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");
	$resultTemplate = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "newsletters WHERE PkID = '" . cIn($_POST['templateID']) . "'");
	
	$message = "<div>" . mysql_result($resultTemplate,0,0) . "</div>";
	
	$message = str_replace('[billboard]', getBillboard(""), $message);
	$message = str_replace('[billboard-today]', getBillboard("today"), $message);
	$message = str_replace('[most-viewed]', getBillboard("most"), $message);
	$message = str_replace('[event-count]', getEventCount(), $message);
	$message = str_replace('[calendarurl]', "<a href=\"" . CalRoot . "\">" . CalRoot . "</a>", $message);
	
	$messageTemplate = $message;
	
	while($row = mysql_fetch_row($result)){
		$resultEvents = doQuery("	SELECT " . HC_TblPrefix . "events.PkID, " . HC_TblPrefix . "events.Title, " . HC_TblPrefix . "events.StartDate, " . HC_TblPrefix . "events.SeriesID 
									FROM " . HC_TblPrefix . "events 
										LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.EventID = " . HC_TblPrefix . "events.PkID) 
										LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID) 
										LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "usercategories.CategoryID) 
									WHERE 
										" . HC_TblPrefix . "usercategories.UserID = " . cIn($row[0]) . " AND " . HC_TblPrefix . "events.StartDate Between '" . cIn($startDate) . "' AND '" . cIn($endDate) . "' 
										AND " . HC_TblPrefix . "categories.CategoryName != ''
										AND	" . HC_TblPrefix . "events.IsApproved = 1
										AND	" . HC_TblPrefix . "events.IsActive = 1
										AND " . HC_TblPrefix . "events.AlertSent = 0
									ORDER BY " . HC_TblPrefix . "events.SeriesID, " . HC_TblPrefix . "events.Title, " . HC_TblPrefix . "events.StartDate");
		if(hasRows($resultEvents)){
			$message = $messageTemplate;
			
			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8;\n";
			
			$subject = CalName . " Event Newsletter";
			
			$message = str_replace('[firstname]', $row[1], $message);
			$message = str_replace('[lastname]', $row[2], $message);
			$message = str_replace('[email]', $row[3], $message);
			$message = str_replace('[zip]', $row[5], $message);
			$message = str_replace('[editregistration]', getLink("editregistration", $row[7]), $message);
			$message = str_replace('[unsubscribe]', getLink("unsubscribe", $row[7]), $message);
			
			$events = "<b>Upcoming Events on the " . CalName . "</b>:";
			
			$eventID = "";
			$seriesID = "";
			$cnt = 0;
			while($row2 = mysql_fetch_row($resultEvents)){
				
				if($row2[3] != ""){
					
					if($seriesID == ""){
						$events .= "<br /><br /><b>Upcoming Multiple Date Events (Click to View All Dates):</b>";
					}//end if
					
					if($row2[3] != $seriesID){
						$seriesID = $row2[3];
						$events .= "<br /><br /><li><a href=\"" . CalRoot . "/index.php?com=serieslist&sID=" . $row2[3] . "\" target=\"new\">" . $row2[1] . "</a>";
					}//end if
					
				} else {
					if($row2[0] != $eventID){
						$eventID = $row2[0];
						$events .= "<br /><br /><li><a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $row2[0] . "\" target=\"new\">" . $row2[1] . " - " . stampToDate($row2[2], "m/d/Y") . "</a>";
					}//end if
				}//end if
				
			}//end while
			
			$message = str_replace('[events]', $events, $message);
			
			mail("$row[3]", "$subject", "$message", "$headers");
		}//end if
	}//end while
	
	doQuery("UPDATE " . HC_TblPrefix . "events set AlertSent = 1 WHERE StartDate Between '" . $startDate . "' AND '" . $endDate . "'");
	
	header("Location: " . CalAdminRoot . "/index.php?com=newsletter&msg=1");
	
?>


<?php
	function getLink($type, $uID){
		switch($type){
			case 'unsubscribe':
				$link = "To unsubscribe use the following link:<br /><a href=\"" . CalRoot . "/index.php?com=unsubscribe&guid=" . $uID . "\">" . CalRoot . "/index.php?com=unsubscribe&guid=" . $uID . "</a>";
				break;
				
			case 'editregistration':
				$link = "To edit your registration use the following link:<br /><a href=\"" . CalRoot . "/index.php?com=editreg&guid=" . $uID . "\">" . CalRoot . "/index.php?com=editreg&guid=" . $uID . "</a>";
				break;
		}//end switch
		
		return $link;
	}//end getLink()
	
	function getBillboard($type){
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 12");
		$maxShow = mysql_result($result,0,0);
		
		switch($type){
			case 'most':
				$query = "SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >=NOW() ORDER BY Views DESC LIMIT " . $maxShow;
				break;
				
			case 'today':
				$query = "SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = NOW() ORDER BY Title LIMIT " . $maxShow;
				break;
				
			default :
				$query = "SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >=NOW() ORDER BY Title";
				break;
				
		}//end switch
		
		$billboard = "";
		
		$resultBillboard = doQuery($query);
		
		if(hasRows($resultBillboard)){
			while($row = mysql_fetch_row($resultBillboard)){
				$billboard .= "<li><a href=\"" . CalRoot . "/index.php?com=detail&eID=" . cOut($row[0]) . "\" target=\"new\">" . cOut($row[1]) . "</a>";
			}//end while
		} else {
			$billboard .= "No events currently available.";
		}//end if
		
		return $billboard;
	}//end getBillboard()
	
	function getEventCount(){
		$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW()");
		
		return mysql_result($result,0,0);
	}//end getEventCount()
?>