<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	hookDB();
	
	if(isset($_GET['tID']) && is_numeric($_GET['tID'])){
		if(isset($_GET['oID']) && is_numeric($_GET['oID'])){
			
			$tID = $_GET['tID'];
			$oID = $_GET['oID'];
			
			$lID = 0;
			if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
				$lID = $_GET['lID'];
			}//end if
			
			switch($tID){
			//	Event URL Clicked
				case 1:
						$result = doQuery("SELECT ContactURL FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($oID));
						if(hasRows($result)){
							doQuery("UPDATE " . HC_TblPrefix . "events SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
							header('Location: ' . mysql_result($result,0,0));
						} else {
							die("Invalid Link");
						}//end if
					break;
			//	Driving Directions Clicked
				case 2:
						if($lID > 0){
							$result = doQUERY("SELECT Address, City, State, Zip FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($_GET['lID']) . "'");
						} else {
							$result = doQuery("SELECT LocationAddress, LocationCity, LocationState, LocationZip FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($oID) . "'");
						}//end if
						if(hasRows($result)){
							doQuery("UPDATE " . HC_TblPrefix . "events SET Directions = Directions + 1 WHERE PkID = '" . cIn($oID) . "'");
							
							$resultSet = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 8");
							switch(mysql_result($resultSet,0,0)){
								case 0:
									header("Location: http://maps.google.com/maps?q=" . urlencode(mysql_result($result,0,0) . ", " . mysql_result($result,0,1) . ", " . mysql_result($result,0,2) . " " . mysql_result($result,0,3)) . "&hl=en&f=d");
									break;
								
								case 1:
									header('Location: http://www.mapquest.com/maps/map.adp?country=US&countryid=250&addtohistory=&searchtab=address&searchtype=address&address=' . urlencode(mysql_result($result,0,0)) . '&city=' . urlencode(mysql_result($result,0,1)) . '&state=' . urlencode(mysql_result($result,0,2)) . '&zipcode=' . urlencode(mysql_result($result,0,3)) . '&search=++Search++');
									break;
									
								case 2:
									header('Location: http://maps.yahoo.com/maps_result?addr=' . urlencode(mysql_result($result,0,0)) . '&csz=' . urlencode(mysql_result($result,0,1)) . '+' . urlencode(mysql_result($result,0,2)) . '+' . urlencode(mysql_result($result,0,3)) . '&country=us&new=1&name=&qty=');
									break;
								
								case 3:
									header("Location: http://maps.google.co.uk/maps?q=" . urlencode(mysql_result($result,0,0) . ", " . mysql_result($result,0,1) . ", " . mysql_result($result,0,2) . " " . mysql_result($result,0,3)) . "&hl=en&f=d");
									break;
								
								case 4:
									header("Location: http://maps.google.com/maps?q=" . urlencode(mysql_result($result,0,0) . ", " . mysql_result($result,0,1) . ", " . mysql_result($result,0,2) . " " . mysql_result($result,0,3)) . ",Australia&hl=en&f=d");
									break;
									
							}//end switch
						} else {
							die("Invalid Link");
						}//end if
					break;
			//	Weather Link Clicked
				case 3:
						if($lID > 0){
							$result = doQUERY("SELECT City, State, Zip FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($_GET['lID']) . "'");
						} else {
							$result = doQuery("SELECT LocationZip, LocationCity, LocationState FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($oID) . "'");
						}//end if
						if(hasRows($result)){
							$resultSet = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 9");
							switch(mysql_result($resultSet,0,0)){
								case 0:
									header('Location: http://www.weather.com/weather/local/' . urlencode(mysql_result($result,0,0)));
									break;
									
								case 1:
									header('Location: http://wwwa.accuweather.com/index-forecast.asp?partner=accuweather&amp;myadc=0&zipcode=' . urlencode(mysql_result($result,0,0)) . '&u=1');
									break;
									
								case 2:
									header('Location: http://www.weatherunderground.com/cgi-bin/findweather/getForecast?query=' . urlencode(mysql_result($result,0,0)));
									break;
									
								case 3:
									header('Location: http://weather.yahoo.com/search/weather2?p=' . urlencode(mysql_result($result,0,0)));
									break;
									
								case 4:
									header('Location: http://wwwa.accuweather.com/canada-weather-forecast.asp?partner=accuweather&amp;myadc=0&postalcode=' . urlencode(mysql_result($result,0,0)));
									break;
									
								case 5:
									$ukCode = explode(" ", mysql_result($result,0,0));
									header('Location: http://uk.weather.com/weather/local/' . urlencode($ukCode[0]) . '?x=0&post=' . urlencode($ukCode[0]) . '+&code=' . urlencode($ukCode[1]) . '&y=0');
									break;
									
								case 6:
									header('Location: http://weather.news.com.au/searchlocal.jsp?name=' . urlencode(mysql_result($result,0,1)) . '&state=' . urlencode(mysql_result($result,0,2)) . '&postcode=' . urlencode(mysql_result($result,0,0)));
									break;
									
								case 7:
									header('Location: http://www.weatherzone.com.au/local/wxSearch.jsp?name=' . urlencode(mysql_result($result,0,1)) . '&state=' . urlencode(mysql_result($result,0,2)) . '&postcode=' . urlencode(mysql_result($result,0,0)));
									break;
							}//end switch
						} else {
							header('Location: ' . CalRoot);
						}//end if
					break;
					
				//	Location URL Clicked
				case 4:
						$result = doQuery("SELECT URL FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($oID));
						if(hasRows($result)){
							doQuery("UPDATE " . HC_TblPrefix . "locations SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
							header('Location: ' . mysql_result($result,0,0));
						} else {
							die("Invalid Link");
						}//end if
					break;
					
				default:
						header('Location: ' . CalRoot);
					break;
					
			}//end switch
			
		} else {
			header('Location: ' . CalRoot);
		}//end if
		
	} else {
		header('Location: ' . CalRoot);
	}//end if
?>