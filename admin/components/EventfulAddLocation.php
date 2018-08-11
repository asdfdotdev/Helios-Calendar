<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	if($efUser != '' && $efPass != ''){
		$ip = gethostbyname("api.evdb.com");
		if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
			$msgID = 8;
		} else {
			$efSend = $efRest;
			$efSend .= "?app_key=" . $efKey;
			$efSend .= "&user=" . $efUser;
			$efSend .= "&password=" . $efPass;
			
			if($efRest == "/rest/venues/modify"){
				$resultU = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = " . $lID);
				$efSend .= "&id=" . mysql_result($resultU,0,0);
			}//end if
			
			if($lName != ''){
				$efSend .= "&name=" . urlencode($lName);
			}//end if
			if($address != '' && $address != ''){
				$efSend .= "&address=" . urlencode($address . " " . $address2);
			} elseif($address != '') {
				$efSend .= "&address=" . urlencode($address);
			}//end if
			if($city != ''){
				$efSend .= "&city=" . urlencode($city);
			}//end if
			if($state != ''){
				$efSend .= "&region=" . urlencode($state);
			}//end if
			if($zip != ''){
				$efSend .= "&postal_code=" . urlencode($zip);
			}//end if
			if($country != ''){
				$efSend .= "&country=" . urlencode($country);
			}//end if
			if($url != ''){
				$efSend .= "&url=" . $url;
				$efSend .= "&url_type=19";
			}//end if
			
			$descript .= "<p><br>" . mysql_result($result,3,1) . "<br>";
			$descript .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
			
			if($descript != ''){	
				$efSend .= "&description=" . urlencode(nl2br($descript));
			}//end if
			
			$efSend .= "&privacy=1";	//1 = public, 2 = private
			
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
			
			if($efRest == "/rest/venues/new"){
				doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsActive) VALUES('" . $lID . "','" . cIn($efID) . "',1,1);");
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET NetworkID = '" . cIn($efID) . "' WHERE LocationID = '" . $lID . "'");
			}//end if
		}//end if
	}//end if	?>