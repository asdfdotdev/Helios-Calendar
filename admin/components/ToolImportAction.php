<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$impType = (isset($_POST['impType'])) ? $_POST['impType'] : 0;
	$msg = 1;
	$errNum = 0;
	$enclChar = (isset($_POST['enclChar'])) ? $_POST['enclChar'] : '';
	$termChar = "";
	$eventIn = (isset($_POST['eventIn'])) ? trim($_POST['eventIn']) : '';
	
	if(isset($_POST['termChar'])){
		$termChar = ($_POST['termChar'] == 'tab') ? '\t' : $_POST['termChar'];
	}//end if
		
	if($impType == 0){
		set_time_limit(300);
		if($termChar != '' && $eventIn != ''){

			if($enclChar == "2"){
				$enclChar = "\"";
				$eventIn = str_replace("\\\\","\\",$eventIn);
				$eventIn = str_replace("\\\\'","&#39;",str_replace("\\,","&#44;",$eventIn));
				$eventIn = str_replace("\\\"","\"",$eventIn);
			} else if ($enclChar == "1"){
				$enclChar = "'";
				$eventIn = str_replace("\\\\","\\",$eventIn);
				$eventIn = str_replace("\\\"","&#38;",str_replace("\\,","&#44;",$eventIn));
				$eventIn = str_replace("\'","'",$eventIn);
			} else {
				$enclChar = "";
				$eventIn = str_replace("\\\\","\\",$eventIn);
				$eventIn = str_replace("\\\"","&#38;",str_replace("\\'","&#39;",str_replace("\\,","&#44;",$eventIn)));
			}//end if
			
			$csvRows = explode("\n",$eventIn);
			$x = 0;
			
			foreach($csvRows as $val){
				$csvContent = explode($termChar,$val);
				if(count($csvContent) > 1){
					if(str_replace($enclChar, "",$csvContent[0]) != "EventTitle"){
						$newEvent = "INSERT INTO " . HC_TblPrefix . "events(Title,Description,Cost,StartDate,StartTime,EndTime,TBD,LocID,LocationName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,LocCountry,ContactName,ContactEmail,ContactPhone,ContactURL,IsBillboard,SeriesID,AllowRegister,SpacesAvailable,PublishDate,IsApproved) VALUES(";
						
						foreach($csvContent as $content){

							if($content != ''){
								$newEvent .= "'" . cIn(str_replace($enclChar, "",str_replace("\\","&#92;",$content))) . "',";
							} else {
								$newEvent .= "'',";
							}//end if
						}//end foreach
						
						$newEvent .= "NOW(),1);";
						
						doQuery($newEvent);
						
						$result = doQuery("SELECT LAST_INSERT_ID()");
						$newPkID = mysql_result($result,0,0);
						
						if(isset($_POST['catID'])){
							$catID = $_POST['catID'];
							foreach ($catID as $val){
								doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
							}//end foreach
						}//end if
					}//end if
				} else {
					$msg = 2;
				}//end if
			}//end foreach()
		} elseif(isset($_GET['samp']) && $_GET['samp'] == 1) {
			header('Content-type: application/csv');
			header('Content-Disposition: inline; filename="HeliosImportTemplate.csv"');
			echo "EventTitle,Description,Cost,EventDate,StartTime,EndTime,";
			echo "AllDay,LocationID,LocatioName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,";
			echo "LocationCountry,ContactName,ContactEmail,ContactPhone,ContactURL,Billboard,SeriesID,Registration,SpaceAvailable";
			echo "\n";
			echo "\"CSV Import Sample Event\",\"We\'re using a little html for this one...<br /><br /><b>this is bold</b>\, <i>this is italicized</i>\, <u>this is underlined</u>\, <strike>strike this!</strike> plus this one has commas. You can &quot;quote me on that&quot;\, or \'quote me on that\'. Whichever you prefer.\",\"Free\"," . date("Y-m-d") . ",07:00:00,18:00:00,0,0,\"Helios Calendar\'s Hometown\",\"\",,\"Forest Grove\",\"OR\",97116,\"USA\",\"Event Contact\",\"nothing@fake.edu\",\"555-123-4567\",\"http://www.helioscalendar.com\",0,,0,0";
			exit();
		}//end if
	} else {
		set_time_limit(300);
		$title = "";
		$description = "";
		$startDate = "";
		$startTime = "";
		$endDate = "";
		$endTime = "";
		$location = "";
		$isLoop = false;
		$curDate = "";
		$url = "";
		$events = explode("BEGIN:VEVENT", $eventIn);
		$publishDate = date("Y-m-d H:i:s");
		foreach($events as $val){
			$matches = preg_split("/([A-Z\-\;\=]+.*):/", $val,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
			if(in_array("SUMMARY",$matches)){
				$dates = array();
				$i = 0;
				while($i < count($matches)){
					if(strrpos($matches[$i],"SUMMARY") === 0){
						$title = preg_replace("/\r|\t|\\\\n|\\\\|\n|/","",$matches[$i+1]);
						$title = cleanXMLChars($title);
					}//end if

					if(strrpos($matches[$i],"DESCRIPTION") === 0){
						$description = preg_replace("/\r|\t|\\\\n|\\\\|/","",$matches[$i+1]);
						$description = preg_replace("/\n/"," ",$description);
						$description = preg_replace("/'/","\'",$description);
						$description = preg_replace("/______________________________Calendar Subscription Powered by Helios Calendar/","",$description);
						$description = preg_replace("/______________________________This Event Downloaded From a Helios Calendar Powered Site/","",$description);
					}//end if

					if(strrpos($matches[$i],"DTSTART") === 0 || strrpos($matches[$i],"DTEND") === 0){
						if($startDate == ''){
							$startDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							if(substr($matches[$i+1],9,6) != '' && is_numeric(substr($matches[$i+1],9,6)) && substr($matches[$i+1],9,6) != ''){
								$startTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							}//end if
						} else {
							$endDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							if(substr($matches[$i+1],9,6) != '' && is_numeric(substr($matches[$i+1],9,6)) && substr($matches[$i+1],9,6) != ''){
								$endTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							}//end if
						}//end if
					}//end if

					if(strrpos($matches[$i],"LOCATION") === 0){
						$location = preg_replace("/\\\|/","",$matches[$i+1]);
						$location = preg_replace("/'/","\'",$location);
					}//end if

					if(strrpos($matches[$i],"URL") === 0){
						$url = $matches[$i+1];
					}//end if
					$i++;
				}//end while
				
				if($endDate != ''){
					if(strtotime($startDate) > strtotime($endDate)){
						$flipDate = $startDate;
						$startDate = $endDate;
						$endDate = $flipDate;
					}//end if
					
					if(strtotime($startDate) < strtotime($endDate)){
						$isLoop = true;
					}//end if
				}//end if
					
				$tbd = ($startTime == '') ? 1 : 0;
				
				if($isLoop == true){
					$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
					$curDate = $startDate;
					while(strtotime($curDate) <= strtotime($endDate)){
						$dates[] = $curDate;
						
						$dateParts = explode("-", $curDate);
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}//end while
				} else {
					$seriesID = "";
					$dates[] = $startDate;
				}//end if
				
				foreach($dates as $valAdd){
					$eventDate = $valAdd;
					$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, Description,
											StartDate, StartTime, TBD, EndTime, IsActive, IsApproved,
											SeriesID, PublishDate, ContactURL)
								VALUES('" . substr(cIn($title),0,150) . "', '" . substr(cIn($location),0,50) . "', '" . cIn($description) . "',
										'" . cIn($eventDate) . "', '" . cIn($startTime) . "', " . cIn($tbd) . ", '" . cIn($endTime) . "',
										'1', '1', '" . cIn($seriesID) . "', '" . cIn($publishDate) . "', '" . cIn($url) . "');";
					doQuery($query);
					
					$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
					$newPkID = mysql_result($result,0,0);
					
					if(isset($_POST['catID'])){
						$catID = $_POST['catID'];
						foreach ($catID as $valCat){
							doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($valCat) . "')");
						}//end foreach
					}//end if
				}//end foreach
			}//end if
		}//end foreach
	}//end if	
	
	clearCache();

	header("Location: " . CalAdminRoot . "/index.php?com=import&msg=" . $msg);?>