<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

include('../includes/include.php');
header("Content-type: text/vnd.wap.wml");
echo("<?xml version=\"1.0\"?>\n");
echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\""
." \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");

if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
	$eID = $_GET['eID'];
} else {
	$eID = 0;
}//end if

$theDate = date("Y") . "-" . date("m") . "-" . date("d");
$datepart = split("-", $theDate);

$dateStamp = date("M jS Y", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));

hookDB();
?>

<wml>

<card id="no1" title="WebCal Mobile">
<p>
<table columns="2">
	<tr>
		<td><a href="<?echo CalRoot;?>/wml/">Events</a>&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
<table columns="1">
	<tr>
		<td>
		<?php
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . $eID);
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0){
				doQuery("UPDATE " . HC_TblPrefix . "events SET MViews = " . (mysql_result($result,0,34) + 1) . " WHERE PkID = " . $eID)
			?>
			
				<?php
					$datepart = split("-", mysql_result($result,0,9));
					echo "<b>" . stampToDate(mysql_result($result,0,9), "D M j Y") . "</b>";
				?>
				<br/>
				<?echo cleanWMLChars( strip_tags(mysql_result($result,0,1)) );?><br/>
				
				<br/><b>Time</b><br/>	
				<?php
					$starTime = "";
					$endTime = "";
					
					//check start time
					if(mysql_result($result,0,10) != ''){
						$timepart = split(":", mysql_result($result,0,10));
						$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
					}//end if
					
					//check end time
					if(mysql_result($result,0,12) != ''){
						$timepart = split(":", mysql_result($result,0,12));
						$endTime = "<br/>to " . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
					}//end if
					
					if(mysql_result($result,0,11) == 0){
						if(strlen($endTime) > 0){
							echo $startTime . $endTime;
						} else {
							echo cleanWMLChars($startTime) . cleanWMLChars($endTime);
						}//end if
					} elseif(mysql_result($result,0,11) == 1){
						echo "This is an All Day Event";
					} elseif(mysql_result($result,0,11) == 2){
						echo "Start Time TBA";
					}//end if
				?>
				
				<br/><br/>
				<b>Location</b><br/>
				<?php
					if(mysql_result($result,0,2) != ''){
						$locName = mysql_result($result,0,2);
						echo $locName;
					}//end if
					if(mysql_result($result,0,3) != ''){
						$locAddress = mysql_result($result,0,3);
						echo "<br/>" . $locAddress;
					}//end if
					if(mysql_result($result,0,4) != ''){
						$locAddress2 = mysql_result($result,0,4);
						echo "<br/>" . $locAddress2;
					}//end if
					if(mysql_result($result,0,5) != ''){
						$locCity = mysql_result($result,0,5);
						echo "<br/>" . $locCity;
						
						if(mysql_result($result,0,6) != ''){
							$locState = mysql_result($result,0,6);
							echo ", " . $locState;
						}//end if
					}//end if
					if(mysql_result($result,0,7) != ''){
						$locZip = mysql_result($result,0,7);
						echo " " . $locZip;
					}//end if
				?>
				
			<?php
			if((mysql_result($result,0,13) != '') OR (mysql_result($result,0,14) != '') OR (mysql_result($result,0,15) != '')){
			?>
				<br/><br/>
				<b>Contact</b><br/>
					<?php
						$conName = "";
						$conEmail = "";
						if(mysql_result($result,0,14) != ''){
							$conEmail = mysql_result($result,0,14);
							
						}//end if
						
						$conName = mysql_result($result,0,13);
						echo $conName;
							
						if(mysql_result($result,0,15) != ''){
							$conPhone = mysql_result($result,0,15);
							echo cleanWMLChars($conPhone);
						}//end if
					?>
			<?
			}//end if
			?>
				<br/><br/>
				<b>Description</b><br/>
				<?
					if(mysql_result($result,0,8) != ''){
						echo cleanWMLChars( strip_tags(mysql_result($result,0,8)) );
					}//end if
				?>
		<?php
			} else {
				echo "<br/>The event you're looking for is unavailable.<br/><br/><a href=\"" . CalRoot . "/wml\" class=\"main\">Click here to browse available events.</a>";
			}//end if
		?>
			<br><br>
		</td>
	</tr>
</table>
<p align="center">
	<?echo CalName;?> Mobile Site<br>
	Powered by Helios <?echo HC_Version;?>
	</p>
</p>
</card>
</wml>