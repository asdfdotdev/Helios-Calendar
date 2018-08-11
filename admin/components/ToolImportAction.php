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
	
	$msg = 1;
	$errNum = 0;
	$enclChar = "";
	$termChar = "";
	$eventIn = "";
	
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
			
			header("Location: " . CalAdminRoot . "/index.php?com=import&msg=" . $msg);
		}//end foreach()
	} elseif(isset($_GET['samp']) && $_GET['samp'] == 1) {
		header('Content-type: application/csv');
		header('Content-Disposition: inline; filename="HeliosImportTemplate.csv"');
		echo "EventTitle,";
		echo "Description,";
		echo "Cost,";
		echo "EventDate,";
		echo "StartTime,";
		echo "EndTime,";
		echo "AllDay,";
		echo "LocationID,";
		echo "LocatioName,";
		echo "LocationAddress,";
		echo "LocationAddress2,";
		echo "LocationCity,";
		echo "LocationState,";
		echo "LocationZip,";
		echo "LocationCountry,";
		echo "ContactName,";
		echo "ContactEmail,";
		echo "ContactPhone,";
		echo "ContactURL,";
		echo "Billboard,";
		echo "SeriesID,";
		echo "Registration,";
		echo "SpaceAvailable";
	}//end if
?>