<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/locations.php');
	$locID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? $_GET['lID'] : 0;
	
	$result = doQuery("SELECT * From " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
	if(hasRows($result)){
		$locName = cOut(mysql_result($result,0,1));
		$locAddress = cOut(mysql_result($result,0,2));
		$locAddress2 = cOut(mysql_result($result,0,3));
		$locCity = cOut(mysql_result($result,0,4));
		$locState = cOut(mysql_result($result,0,5));
		$locZip = cOut(mysql_result($result,0,7));
		$locURL = cOut(mysql_result($result,0,8));
		$locPhone = cOut(mysql_result($result,0,9));
		$locEmail = cOut(mysql_result($result,0,10));
		$locDesc = cOut(mysql_result($result,0,11));
		$locCountry = cOut(mysql_result($result,0,6));
		$locLat = cOut(mysql_result($result,0,15));
		$locLon = cOut(mysql_result($result,0,16));
		$locTag = '';
		
		echo '<br />';
		echo '<div class="locDetails">';
		echo '<div class="locDetailHeader">' . $hc_lang_locations['LocLabel'] . '&nbsp;';
		echo ($locZip != '') ? '<a href="' . CalRoot . '/link/index.php?tID=3&amp;oID=0&amp;lID=' . $locID . '" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconWeather.png" width="16" height="16" alt="' . $hc_lang_locations['ALTWeather'] . '" /></a>&nbsp;<a href="' . CalRoot . '/link/index.php?tID=3&amp;oID=0&amp;lID=' . $locID . '" class="eventDetailLink" target="_blank">' . $hc_lang_locations['Weather'] . '</a>&nbsp;' : '';
		echo '<a href="' . CalRoot . '/rss/l.php?lID=' . $locID . '" target="_blank"><img src="' . CalRoot . '/images/rss/feedIcon.gif" width="16" height="16" alt="' . $hc_lang_locations['FeedAlt'] . '" style="vertical-align:middle;" /></a>';
		echo '</div>';
		
		if($locName != ''){
			echo $locName . "<br />";
			$locTag .= "<b>" . $locName . "</b><br />";
		}//end if
		
		echo buildAddress($locAddress,$locAddress2,$locCity,$locState,$locZip,$locCountry,1,$hc_lang_config['AddressType']);
		$locTag .= buildAddress($locAddress,$locAddress2,$locCity,$locState,$locZip,$locCountry,0,$hc_lang_config['AddressType']);

		if($locEmail != ''){	
			$eParts = explode("@", $locEmail);
			echo $hc_lang_locations['Email'] . '<br />';
			cleanEmailLink($locEmail);
		}//end if
			
		echo ($locPhone != '') ? '<br />' . $hc_lang_locations['Phone'] . ' ' . $locPhone : '';
		echo ($locURL != '' && $locURL != 'http://') ? '<br />' . $hc_lang_locations['Website'] . ' <a href="' . CalRoot . '/link/index.php?tID=4&amp;oID=' . $locID . '" target="_blank" class="eventMain">' . $hc_lang_locations['Visit'] . '</a>' : '';
		echo ($locDesc != '' && $locDesc != '<br />') ? '<br /><br />' . $locDesc . '<br />' : '';
		$locLink = "<a href=\"" . CalRoot . "/link/index.php?tID=2&amp;oID=0&amp;lID=" . $locID . "\" target=\"_blank\" class=\"eventDetailLink\">" . $hc_lang_locations['Directions'] . "</a>";
		echo '</div>';

		if(isset($hc_cfg26)){
			echo '<div id="hc_GmapLocDetail"></div>';	?>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			var map;
			var marker;
			
			function createMarker(point, msg){marker = new GMarker(point);GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(msg);});return marker;}//end createMarker()
			
			function buildGmap(hc_Lat, hc_Lon, hc_gMsg){
				if (GBrowserIsCompatible()) {
					map = new GMap2(document.getElementById("hc_GmapLocDetail"));
					map.addControl(new GSmallMapControl());
					map.addControl(new GMapTypeControl());
					map.setCenter(new GLatLng(hc_Lat, hc_Lon), <?php echo $hc_cfg27;?>);
					var point = new GLatLng(hc_Lat, hc_Lon);
					map.addOverlay(createMarker(point, hc_gMsg));
					marker.openInfoWindowHtml(hc_gMsg);
				}//end if
			}//end buildGmap()
			//-->
			</script>
<?php	}//end if	
	}//end if	?>