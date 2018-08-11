 <?php 
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/locations.php');
	
 	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(41,42,43);");
	$mapZoom = mysql_result($result,0,0);
	$mapCenterLat = mysql_result($result,1,0);
	$mapCenterLon = mysql_result($result,2,0);
	
	if(!is_numeric($mapCenterLat) || !is_numeric($mapCenterLon) || $mapCenterLat == '' || $mapCenterLon == ''){
 		echo("<br />" . $hc_lang_locations['NoGeo']);
	} elseif(isset($hc_googleKey) && $hc_googleKey != ''){	?>
 		<script language="JavaScript" type="text/JavaScript">
		//<!--
		var markers = [];
		var msgs = [];
		var i = 0;
		var map;
		var marker;
		
		function goHome(){
			map.setCenter(new GLatLng(<?php echo $mapCenterLat . "," . $mapCenterLon;?>), <?php echo $mapZoom;?>);
		}//end goHome
		
		function toggleLoc(show,hide){
			document.getElementById(show).style.display = 'block';
			document.getElementById(hide).style.display = 'none';
			return false;
		}//end toggleLoc()
		
		function showLoc(i){
			map.setZoom(16);
			map.setMapType(G_NORMAL_MAP);
			markers[i].openInfoWindowHtml(msgs[i]);
		}//end showLoc()
		
		function createMarker(i, point, msg){
			var marker = new GMarker(point);
			GEvent.addListener(marker, "click", function() {
			marker.openInfoWindowHtml(msg);
			});
			markers[i] = marker;
 			msgs[i] = msg;

			return marker;
		}//end createMarker()
		
		function buildGmap(){
			if (GBrowserIsCompatible()) {
				map = new GMap2(document.getElementById("hc_GmapLoc"));
				goHome();
				map.addControl(new GSmallMapControl());
				map.addControl(new GMapTypeControl());
				
		<?php
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE Lat IS NOT NULL AND Lon IS NOT NULL AND Lat != '' AND Lon != '' AND IsActive = 1 ORDER BY Name");
				$linkList = "No Geocoded Locations Currently Available";
				if(hasRows($result)){
					$linkList = "";
					$x = 0;
					while($row = mysql_fetch_row($result)){
						$locID = cOut($row[0]);
						$resultLE = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND LocID = '" . $locID . "' AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate LIMIT 15");
						if(hasRows($resultLE)){
							$locName = str_replace("'", "&#39;", cOut($row[1]));
							$locAddress = str_replace("'", "&#39;", cOut($row[2]));
							$locAddress2 = str_replace("'", "&#39;", cOut($row[3]));
							$locCity = str_replace("'", "&#39;", cOut($row[4]));
							$locState = str_replace("'", "&#39;", cOut($row[5]));
							$locZip = cOut($row[7]);
							$locURL = cOut($row[8]);
							$locPhone = cOut($row[9]);
							$locEmail = cOut($row[10]);
							$locDesc = str_replace("'", "&#39;", cOut($row[11]));
							$locCountry = str_replace("'", "&#39;", cOut($row[6]));
							$locLat = cOut($row[15]);
							$locLon = cOut($row[16]);
							$locTag = "";
							$locEvents = "";
							$locLink = "<a href=\"" . CalRoot . "/link/index.php?tID=2&amp;oID=0&amp;lID=" . $row[0] . "\" target=\"_blank\" class=\"eventDetailLink\">" . $hc_lang_locations['Directions'] . "</a>";
							if($locName != ''){
								$locTag .= "<b>" . $locName . "</b><br />";
							}//end if
							if($locAddress != ''){
								$locTag .= $locAddress . "<br />";
							}//end if
							if($locAddress2 != ''){
								$locTag .= $locAddress2 . "<br />";
							}//end if
							if($locCity != ''){
								$locTag .= $locCity . ", ";
							}//end if
							if($locState != ''){
								$locTag .= $locState . " ";
							}//end if
							if($locCountry != ''){
								$locTag .= $locCountry . " ";
							}//end if
							if($locZip != ''){
								$locTag .= $locZip;
							}//end if
							if($locPhone != ''){
								$locTag .= "<br /><br />Phone: " . $locPhone;
							}//end if
							if($locURL != '' && $locURL != 'http://'){
								$locTag .= "<br /><br />Website: <a href=\"" . CalRoot . "/link/index.php&#63;tID=4&amp;oID=" . $locID . "\" target=\"_blank\" class=\"eventMain\">" . $hc_lang_locations['Visit'] . "</a>";
							}//end if
							$locTag .= "<br /><br />" . $locLink;
							$locTag .= "<br /><br />" . $locLat . ", " . $locLon;
						
							$linkList .= "<li><a href=\"javascript:;\" onclick=\"javascript:showLoc(" . $x . ");\" class=\"locList\">" . $locName . "</a></li>";
							while($rowLE = mysql_fetch_row($resultLE)){
								if($rowLE[11] == 0){
									$timeparts = explode(":", $rowLE[10]);
									$startTime = strftime($hc_timeFormat, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
								} else {
									if($rowLE[11] == 1){
										$startTime = $hc_lang_locations['AllDay'];
									} elseif($rowLE[11] == 2) {
										$startTime = $hc_lang_locations['TBA'];
									}//end if
								}//end if
								$locEvents .= "<a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $rowLE[0] . "\" class=\"eventMain\" target=\"_blank\"><b>" . str_replace("'", "&#39;", htmlspecialchars($rowLE[1])) . "</b></a><br />" . stampToDate($rowLE[9], $hc_dateFormat) . " - " . $startTime . "<br /><br />";
							}//end while
							$locOutput = "<div class=\"GmapLocMenu\"><a href=\"javascript:;\" onclick=\"toggleLoc(\'details" . $locID . "\',\'events" . $locID . "\');\" class=\"locMenu\">" . $hc_lang_locations['Details'] . "</a> | <a href=\"javascript:;\" onclick=\"toggleLoc(\'events" . $locID . "\',\'details" . $locID . "\');\" class=\"locMenu\">" . $hc_lang_locations['Events'] . "</a>&nbsp;|&nbsp;<a href=\"" . CalRoot . "/index.php?lID=" . $locID . "\" class=\"locMenu\">" . $hc_lang_locations['Browse'] . "</a>&nbsp;|&nbsp;<a href=\"webcal://" . substr(CalRoot, 7) . "/link/SaveLocation.php?lID=" . $locID . "\" class=\"locMenu\">" . $hc_lang_locations['Subscribe'] . "</a>&nbsp;&nbsp;<a href=\"" . CalRoot . "/rssL.php?lID=" . $locID . "\" target=\"_blank\"><img src=\"" . CalRoot . "/images/rss/feedIcon.gif\" width=\"16\" height=\"16\" alt=\"\" style=\"vertical-align:middle;\" /></a></div>";
							$locOutput .= "<div class=\"GmapLocPane\">";
							$locOutput .= "<div id=\"details" . $locID . "\">" . $locTag . "</div><div id=\"events" . $locID . "\" style=\"display:none;\">" . $locEvents . "</div>";
							$locOutput .= "</div>";
							
							echo "var point = new GLatLng(" . $locLat . "," . $locLon . ");";
							echo "map.addOverlay(createMarker(" . $x . ",point, '" . $locOutput . "'));";
							$x++;
						}//end if
					}//end while
					if($x == 0){
						$linkList = $hc_lang_locations['NoEvents'];
					}//end if
				}//end if	?>
			}//end if
		}//end buildGmap()
	//-->
	</script>
	<br />
	<?php
	if($linkList != ''){
		echo "<div id=\"locList\"><a href=\"javascript:;\" onclick=\"goHome();\" class=\"locListReset\"><img src=\"" . CalRoot . "/images/icons/iconMap.png\" width=\"16\" height=\"16\" alt=\"\" border=\"0\" class=\"locList\" /> " . $hc_lang_locations['Reset'] . "</a><br /><br /><b><i>" . $hc_lang_locations['Locations'] . "</i></b><br /><ul class=\"locList\">" . $linkList . "</ul></div>";
	}//end if	?>

	<div id="hc_GmapLoc"></div>
<?php
 	}//end if	?>