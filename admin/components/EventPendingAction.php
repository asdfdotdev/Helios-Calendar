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
	
	$editthis = $_POST['editthis'];
	$edittype = $_POST['edittype'];
	$status = $_POST['eventStatus'];
	$billboard = $_POST['eventBillboard'];
	if(isset($_POST['sendmsg']) && $_POST['sendmsg'] != "no" ){
		$sendmsg = 1;
	} else {
		$sendmsg = 0;
	}//end if
	if(isset($_POST['message'])){
		$message = $_POST['message'];
	}//end if
	$subname = $_POST['subname'];
	$subemail = $_POST['subemail'];
	
	$eventTitle = $_POST['eventTitle'];
	$eventDescription = $_POST['eventDescription'];
	
	$query = "UPDATE " . HC_TblPrefix . "events SET
				Title = '" . cIn($eventTitle) . "',
				Description = '" . cIn($eventDescription) . "',
				IsApproved = '" . cIn($status) . "',
				IsBillboard = '" . cIn($billboard) . "',
				PublishDate = NOW()";
	
	if($edittype == 1){
		$query = $query . " WHERE PkID = " . $editthis;
	} else {
		$query = $query . " WHERE SeriesID = '" . $editthis . "'";
	}//end if
	
	doQuery($query);
	
	if($status > 0){
		if($edittype == 1){
			$msg = 1;
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($editthis) . "', '" . cIn($val) . "')");
					}//end for
			}//end if
		} else {
			$msg = 2;
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
			$catID = $_POST['catID'];
			while($row = mysql_fetch_row($result)){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($row[0]));
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($row[0]) . "', '" . cIn($val) . "')");
				}//end for
			}//end while
		}//end if
	} else {
		if($edittype == 1){
			$msg = 3;
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
		} else {
			$msg = 4;
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
			
			while($row = mysql_fetch_row($result)){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($row[0]));
			}//end while
		}//end if
	}//end if
	
	if($sendmsg > 0){
		$headers = "From: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1;\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$subject = CalName . " Event Status Change";
		$message = $subname . ",<br /><br />" . $message . "<br /><br />" . CalAdmin . "<br />" . CalAdminEmail;
		
		mail($subemail, $subject, $message, $headers);
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msg);
?>