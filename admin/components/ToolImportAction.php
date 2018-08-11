<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	
	if(!check_form_token($token))
		go_home();
	
	$impType = (isset($_POST['impType'])) ? $_POST['impType'] : 0;
	$msg = 1;
	$errNum = 0;
	$enclChar = (isset($_POST['enclChar'])) ? $_POST['enclChar'] : '';
	$termChar = "";
	$import = (isset($_POST['import'])) ? trim($_POST['import']) : '';
	$publishDate = SYSDATE . ' ' . SYSTIME;
	
	if(isset($_POST['termChar']))
		$termChar = ($_POST['termChar'] == 'tab') ? '\t' : $_POST['termChar'];
	
	if($impType == 0){
		set_time_limit(300);
		if($termChar != '' && $import != ''){

			if($enclChar == "2"){
				$enclChar = "\"";
				$import = str_replace("\\\\","\\",$import);
				$import = str_replace("\\\\'","&#39;",str_replace("\\,","&#44;",$import));
				$import = str_replace("\\\"","\"",$import);
			} else if ($enclChar == "1"){
				$enclChar = "'";
				$import = str_replace("\\\\","\\",$import);
				$import = str_replace("\\\"","&#38;",str_replace("\\,","&#44;",$import));
				$import = str_replace("\'","'",$import);
			} else {
				$enclChar = "";
				$import = str_replace("\\\\","\\",$import);
				$import = str_replace("\\\"","&#38;",str_replace("\\'","&#39;",str_replace("\\,","&#44;",$import)));
			}
			
			$csvRows = explode("\n",$import);
			$x = 0;
			
			foreach($csvRows as $val){
				$csvContent = explode($termChar,$val);
				if(count($csvContent) > 1){
					if(str_replace($enclChar, "",$csvContent[0]) != "EventTitle"){
						$newEvent = "INSERT INTO " . HC_TblPrefix . "events(Title,Description,Cost,StartDate,StartTime,EndTime,TBD,LocID,LocationName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,LocCountry,ContactName,ContactEmail,ContactPhone,ContactURL,IsBillboard,SeriesID,PublishDate,IsApproved,LastMod) VALUES(";
						foreach($csvContent as $content){
							if($content != '')
								$newEvent .= "'" . cIn(str_replace($enclChar, "",str_replace("\\","&#92;",$content))) . "',";
							else
								$newEvent .= "NULL,";
							
						}
						$newEvent .= "'" . cIn($publishDate). "',1,'" . cIn($publishDate). "');";
						
						doQuery($newEvent);
						
						$result = doQuery("SELECT LAST_INSERT_ID()");
						$newPkID = mysql_result($result,0,0);
						if(isset($_POST['catID'])){
							$catID = $_POST['catID'];
							foreach ($catID as $val){
								doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
							}
						}
					}
				} else {
					$msg = 2;
				}
			}
		} elseif(isset($_GET['samp']) && $_GET['samp'] == 1) {
			header('Content-type: application/csv');
			header('Content-Disposition: inline; filename="HeliosImportTemplate.csv"');
			echo "EventTitle,Description,Cost,EventDate,StartTime,EndTime,";
			echo "AllDay,LocationID,LocatioName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,";
			echo "LocationCountry,ContactName,ContactEmail,ContactPhone,ContactURL,Billboard,SeriesID";
			echo "\n";
			echo "\"CSV Import Sample Event\",\"We\'re using a little html for this one...<br /><br /><b>this is bold</b>\, <i>this is italicized</i>\, <u>this is underlined</u>\, <strike>strike this!</strike> plus this one has commas. You can &quot;quote me on that&quot;\, or \'quote me on that\'. Whichever you prefer.\",\"Free\"," . date("Y-m-d") . ",07:00:00,18:00:00,0,0,\"Helios Calendar\'s Hometown\",\"\",,\"Forest Grove\",\"OR\",97116,\"USA\",\"Event Contact\",\"nothing@fake.edu\",\"555-123-4567\",\"http://www.google.com\",0,";
			exit();
		}
	} else {
		set_time_limit(300);
		$title = $description = $startDate = $startTime = $endDate = $endTime = $location = $curDate = $url = '';
		$isLoop = false;
		$events = explode("BEGIN:VEVENT", $import);
		
		foreach($events as $val){
			$matches = preg_split("/([A-Z\-\;\=]+.*):/", $val,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
			if(in_array("SUMMARY",$matches)){
				$dates = array();
				$i = 0;
				$startDate = $endDate = '';
				while($i < count($matches)){
					if(strrpos($matches[$i],"SUMMARY") === 0){
						$title = preg_replace("/\r|\t|\\\\n|\\\\|\n|/","",$matches[$i+1]);
						$title = cleanXMLChars($title);
					}

					if(strrpos($matches[$i],"DESCRIPTION") === 0){
						$description = preg_replace("/\r|\t|\\\\n|\\\\|/","",$matches[$i+1]);
						$description = preg_replace("/\n/"," ",$description);
						$description = preg_replace("/'/","\'",$description);
						$description = preg_replace("/______________________________Calendar Subscription Powered by Helios Calendar/","",$description);
						$description = preg_replace("/______________________________This Event Downloaded From a Helios Calendar Powered Site/","",$description);
					}

					if(strrpos($matches[$i],"DTSTART") === 0 || strrpos($matches[$i],"DTEND") === 0){
						if($startDate == ''){
							$startDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							if(substr($matches[$i+1],9,6) != '' && is_numeric(substr($matches[$i+1],9,6)) && substr($matches[$i+1],9,6) != ''){
								$startTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							}
						} else {
							$endDate = date("Y-m-d", mktime(0,0,0,substr($matches[$i+1],4,2),substr($matches[$i+1],6,2),substr($matches[$i+1],0,4)));
							if(substr($matches[$i+1],9,6) != '' && is_numeric(substr($matches[$i+1],9,6)) && substr($matches[$i+1],9,6) != ''){
								$endTime = date("H:i:s", mktime(substr($matches[$i+1],9,2),substr($matches[$i+1],11,2),substr($matches[$i+1],13,2),1,1,1971));
							}
						}
					}

					if(strrpos($matches[$i],"LOCATION") === 0){
						$location = preg_replace("/\\\|/","",$matches[$i+1]);
						$location = preg_replace("/'/","\'",$location);
					}

					if(strrpos($matches[$i],"URL") === 0)
						$url = $matches[$i+1];
					
					++$i;
				}
				
				if($endDate != ''){
					if(strtotime($startDate) > strtotime($endDate)){
						$flipDate = $startDate;
						$startDate = $endDate;
						$endDate = $flipDate;
					}
					
					if(strtotime($startDate) < strtotime($endDate))
						$isLoop = true;
				}
					
				$tbd = ($startTime == '') ? 1 : 0;
				if($tbd > 0)
					$startTime = $endTime = '00:00:00';
				
				if($isLoop == true){
					$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
					$curDate = $startDate;
					while(strtotime($curDate) <= strtotime($endDate)){
						$dates[] = $curDate;
						
						$dateParts = explode("-", $curDate);
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}
				} else {
					$seriesID = "";
					$dates[] = $startDate;
				}
				
				foreach($dates as $valAdd){
					$eventDate = $valAdd;
					$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, Description,
											StartDate, StartTime, TBD, EndTime, IsActive, IsApproved,
											SeriesID, PublishDate, ContactURL, LastMod)
								VALUES('" . substr(cIn($title),0,150) . "', '" . substr(cIn($location),0,50) . "', '" . cIn($description) . "',
										'" . cIn($eventDate) . "', '" . cIn($startTime) . "', '" . cIn($tbd) . "', '" . cIn($endTime) . "',
										'1', '1', '" . cIn($seriesID) . "', '" . cIn($publishDate) . "', '" . cIn($url) . "', '" . cIn($publishDate). "');";
					doQuery($query);
					
					$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
					$newPkID = mysql_result($result,0,0);
					
					if(isset($_POST['catID'])){
						$catID = $_POST['catID'];
						foreach ($catID as $valCat){
							doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($valCat) . "')");
						}
					}
				}
			}
		}
	}	
	
	clearCache();

	header("Location: " . AdminRoot . "/index.php?com=import&msg=" . $msg);
?>