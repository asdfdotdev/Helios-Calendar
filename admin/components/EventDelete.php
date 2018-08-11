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
	$msgID = 1;
	$deleteString = "0";
	if(isset($_POST['eventID'])){
		$eventID = $_POST['eventID'];
		$x = 0;
		while($x < count($eventID)){
			$deleteString = $deleteString . ", " . $eventID[$x];
			$x++;
		}//end while
	} else {
		$deleteString = $_GET['dID'];
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET IsActive = 0 WHERE PkID IN(" . cIn($deleteString) . ")");
	
	$deleteIDs = explode(",", $deleteString);
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){
			$efKey = mysql_result($result,0,1);
			$efUser = mysql_result($result,1,1);
			$efPass = mysql_result($result,2,1);
			
			if($efUser != '' && $efPass != ''){
				foreach($deleteIDs as $val){
					$resultEF = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = " . $val);
					if(hasRows($resultEF)){
						$msgID = 6;
						$ip = gethostbyname("api.evdb.com");
						if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
							$msgID = 5;
						} else {
							$efSend = "/rest/events/withdraw";
							$efSend .= "?app_key=" . $efKey;
							$efSend .= "&user=" . $efUser;
							$efSend .= "&password=" . urlencode($efPass);
							$efSend .= "&id=" . mysql_result($resultEF,0,0);
							
							$request = "GET " . $efSend . " HTTP/1.1\r\n";
							$request .= "Host: api.evdb.com\r\n";
							$request .= "Connection: Close\r\n\r\n";
							
							fwrite($fp, $request);
							$data = "";
							while (!feof($fp)) {
								$data .= fread($fp,1024);
							}//end while
							
							$data = explode("<?xml", $data);
							$data = "<?xml" . $data[1];
							
							global $step;
							global $efID;
							
							$step = "";
							$efID = 0;
							
							require_once('EventfulFunctions.php');
							
							$xml_parser = xml_parser_create();
							xml_set_element_handler($xml_parser, "startTag", "endTag");
							if(!(xml_parse($xml_parser, $data, feof($fp)))){
							    die("Error on line " . xml_get_current_line_number($xml_parser));
							}//end if
							xml_parser_free($xml_parser);
							fclose($fp);
							
							doQuery("UPDATE " . HC_TblPrefix . "eventnetwork SET IsActive = 0 WHERE NetworkID = '" . mysql_result($resultEF,0,0) . "'");
						}//end if
					}//end if
				}//end for
			}//end if
		}//end if
	}//end if
	
	if(isset($_GET['oID']) OR isset($_POST['oID'])) {
		header("Location: " . CalAdminRoot . "/index.php?com=eventorphan&msg=1");
	} elseif(isset($_POST['pID'])) {
		header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=5");
	} else {
		header("Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=2&msg=" . $msgID);
	}//end if
?>