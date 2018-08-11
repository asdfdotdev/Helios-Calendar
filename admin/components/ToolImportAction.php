<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
		
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$impType = 0;
	$msg = 1;
	$errNum = 0;
	$enclChar = "";
	$termChar = "";
	$eventIn = "";
	
	if(isset($_POST['impType'])){
		$impType = $_POST['impType'];
	}//end if
	
	if(isset($_POST['enclChar'])){
		$enclChar = $_POST['enclChar'];
	}//end if
	
	if(isset($_POST['termChar'])){
		$termChar = $_POST['termChar'];
		if($termChar == 'tab'){
			$termChar = "\t";
		}//end if
	}//end if

	if(isset($_POST['eventIn'])){
		$eventIn = trim($_POST['eventIn']);
	}//end if
	
	if($impType == 0){
		if($enclChar != '' && $termChar != '' && $eventIn != ''){
			$csvRows = explode("\n",$eventIn);
			$x = 0;
			foreach($csvRows as $val){
				$csvContent = explode($termChar,$val);
				if(count($csvContent) > 1){
					if(str_replace($enclChar, "",$csvContent[0]) != "EventTitle"){
						$newEvent = "INSERT INTO " . HC_TblPrefix . "events(Title,Description,Cost,StartDate,StartTime,EndTime,TBD,LocID,LocationName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,LocCountry,ContactName,ContactEmail,ContactPhone,ContactURL,IsBillboard,SeriesID,AllowRegister,SpacesAvailable,PublishDate,IsApproved) VALUES(";
						
						foreach($csvContent as $content){
							$newEvent .= "'" . str_replace($enclChar, "",$content) . "',";
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
			
			header("Location: " . CalAdminRoot . "/index.php?com=import&msg=" . $msg);
		} elseif(isset($_GET['samp']) && $_GET['samp'] == 1) {
			header('Content-type: application/csv');
			header('Content-Disposition: inline; filename="HeliosImportTemplate.csv"');
			echo "EventTitle,Description,Cost,EventDate,StartTime,EndTime,";
			echo "AllDay,LocationID,LocatioName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,";
			echo "LocationCountry,ContactName,ContactEmail,ContactPhone,ContactURL,Billboard,SeriesID,Registration,SpaceAvailable";
		}//end if
	} else {
		set_time_limit(120);
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
			$matches = preg_split("/([A-Z\-\;\=]+):/", $val,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
			if(in_array("SUMMARY",$matches)){
				$dates = array();
				$i = 0;
				while($i < count($matches)){
					switch($matches[$i]){
						case "SUMMARY":
							$title = preg_replace("/\r|\t|\\\\n|\\\\|\n|/","",$matches[$i+1]);
							$title = cleanXMLChars($title);
							$i++;
							break;
						case "DESCRIPTION":
							$description = preg_replace("/\r|\t|\\\\n|\\\\|/","",$matches[$i+1]);
							$description = preg_replace("/\n/"," ",$description);
							$description = preg_replace("/'/","\'",$description);
							$description = preg_replace("/______________________________Calendar Subscription Powered by Helios Calendar/","",$description);
							$description = preg_replace("/______________________________This Event Downloaded From a Helios Calendar Powered Site/","",$description);
							$i++;
							break;
						case "DTSTART;VALUE=DATE":
						case "DTEND;VALUE=DATE":
							if($startDate == ''){
								$startDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							} else {
								$endDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							}//end if
							$i++;
							break;
						case "DTSTART":
							$startDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							$startTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							$i++;
							break;
						case "DTEND":
							$endDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							$endTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							$i++;
							break;
						case "LOCATION":
							$location = preg_replace("/\\\|/","",$matches[$i+1]);
							$location = preg_replace("/'/","\'",$location);
							$i++;
							break;
						case "URL":
							$url = $matches[$i+1];
							$i++;
							break;
					}//end switch()
					$i++;
				}//end for
				
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
					
				$tbd = 0;
				if ($startTime == '') {
					$tbd = 1;
				}//end if
				
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
								VALUES('" . cIn($title) . "', '" . cIn($location) . "', '" . cIn($description) . "',
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
		header("Location: " . CalAdminRoot . "/index.php?com=import&msg=" . $msg);
	}//end if	?>