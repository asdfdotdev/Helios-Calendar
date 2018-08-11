<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
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
	
	$query = "UPDATE " . HC_TblPrefix . "events SET
				IsApproved = '" . $status . "',
				IsBillboard = '" . $billboard . "',
				PublishDate = NOW()";
	
	if($edittype == 1){
		$query = $query . " WHERE PkID = " . $editthis;
	} else {
		$query = $query . " WHERE SeriesID = '" . $editthis . "'";
	}//end if
	
	doQuery($query);
	
	if($status > 0){
		
		if($edittype == 1){
		//	update categories for single event
			$msg = 1;
			
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($editthis) . "', '" . cIn($val) . "')");
					}//end for
				
			}//end if
		
		} else {
		//	update categories for all events in series
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
		//	delete single event
			$msg = 3;
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
			
		} else {
		//	delete all events in series
			$msg = 4;
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
			
			while($row = mysql_fetch_row($result)){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($row[0]));
			}//end while
			
		}//end if
	
	}//end if
	
	if($sendmsg > 0){
		$headers = "From: " . CalAdmin . " <" . CalAdminEmail . ">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Reply-To: " . CalAdmin . " <" . CalAdminEmail . ">\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1;\r\n";
		
		$subject = CalName . " Event Status Change";
		
		$message = $subname . ",<br><br>" . $message . "<br><br>" . CalAdmin . "<br>" . CalAdminEmail;
		
		mail($subemail, $subject, $message, $headers);
		
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msg);
?>