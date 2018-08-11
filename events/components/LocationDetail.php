<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$locID = 0;
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$locID = $_GET['lID'];
	}//end if
	
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
		$locTag = "";	?>
		<br />
		<div class="locDetails">
			<div class="locDetailHeader">Location:&nbsp;&nbsp;
	<?php	if($locZip != ''){?>
				<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=0<?php if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconWeather.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=0<?php if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank">Weather</a>&nbsp;&nbsp;
	<?php	}//end if	
			if($locID > 0) {?>
				<a href="<?php echo CalRoot?>/rssL.php?lID=<?php echo $locID;?>" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="" align="absmiddle" /></a>
	<?php	}//end if	?>
			</div>
		
	<?php	if($locName != ''){
				echo $locName . "<br />";
				$locTag .= "<b>" . $locName . "</b><br />";
			}//end if
			if($locAddress != ''){
				echo $locAddress . "<br />";
				$locTag .= $locAddress . "<br />";
			}//end if
			if($locAddress2 != ''){
				echo $locAddress2 . "<br />";
				$locTag .= $locAddress2 . "<br />";
			}//end if
			if($locCity != ''){
				echo $locCity . ", ";
				$locTag .= $locCity . ", ";
			}//end if
			if($locState != ''){
				echo $locState . " ";
				$locTag .= $locState . " ";
			}//end if
			if($locCountry != ''){
				echo $locCountry . " ";
				$locTag .= $locCountry . " ";
			}//end if
			if($locZip != ''){
				echo $locZip . "<br />";
				$locTag .= $locZip;
			}//end if
			if($locEmail != ''){	?>
				<br />Email:
				<script language="JavaScript" type="text/JavaScript">
				//<!--
			<?php 	$eParts = explode("@", $locEmail);?>
					var lname = '<?php echo $eParts[0];?>';
					var ldomain = '<?php echo $eParts[1];?>';
					document.write('<a href="mailto:' + lname + '@' + ldomain + '" class="eventMain">' + lname + '@' + ldomain + '</a>');
				//-->
				</script>
	<?php	}//end if
			if($locPhone != ''){
				echo "<br />Phone: " . $locPhone;
			}//end if
			if($locURL != '' && $locURL != 'http://'){	?>
				<br />Website: <a href="<?php echo CalRoot;?>/link/index.php?tID=4&amp;oID=<?php echo $locID;?>" target="_blank" class="eventMain">Click to Visit</a>
	<?php	}//end if
			if($locDesc != '' && $locDesc != '<br />'){
				echo "<br /><br />" . $locDesc . "<br />";
			}//end if
			
			$locLink = "<a href=\"" . CalRoot . "/link/index.php?tID=2&amp;oID=0&amp;lID=" . $locID . "\" target=\"_blank\" class=\"eventDetailLink\">Click For Directions</a>";	?>
		</div>
<?php	
		if(isset($hc_googleKey)){	?>
			<div id="hc_GmapLocDetail"></div>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			var map;
			var marker;
			
			function createMarker(point, msg){
				marker = new GMarker(point);
				GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(msg);
				});
				return marker;
			}//end createMarker()
			
			function buildGmap(hc_Lat, hc_Lon, hc_gMsg){
				if (GBrowserIsCompatible()) {
					map = new GMap2(document.getElementById("hc_GmapLocDetail"));
					map.addControl(new GSmallMapControl());
					map.addControl(new GMapTypeControl());
					map.setCenter(new GLatLng(hc_Lat, hc_Lon), <?php echo $hc_mapZoom;?>);
					var point = new GLatLng(hc_Lat, hc_Lon);
					map.addOverlay(createMarker(point, hc_gMsg));
					marker.openInfoWindowHtml(hc_gMsg);
				}//end if
			}//end buildGmap()
			//-->
			</script>
<?php	}//end if	
	} else	{
		echo "";
	}//end if	?>