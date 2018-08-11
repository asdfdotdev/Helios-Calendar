<?php
/*
Helios Calendar - Professional Event Management System
Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]

Developed By: Chris Carlevato <chris@refreshwebdev.com>

For the most recent version, visit the Helios Calendar website:
[http://www.helioscalendar.com]

License Information is found in docs/license.html
*/
$hc_popDateFormat = "m/d/Y";
$hc_popDateValid = "MM/dd/yyyy";
$hc_timeFormat = "h:i A";

if(!isset($_POST['eID'])){	
	include('../events/includes/headerAdmin.php');	?>
<style type="text/css">
body {
	font-family: Verdana, sans-serif;
	font-size: 11px;
	margin: 0px;
	padding: 10px;
	}
fieldset {
	clear: both;
    border: none;
    border-top: 1px solid #CCCCCC;
    border-bottom: 1px solid #CCCCCC;
	width: 550px;
	padding-top:15px;
	}
fieldset legend {
	font-size: 11px;
	font-weight: bold;
	color: #000000;
	background: transparent;
	margin: 1px;
	padding: 0px 10px 2px 10px;
	}
fieldset label {
    float: left;
    width: 80px;
    padding: 2px 10px 0px 0px;
    text-align: left;
	}
fieldset label.category {
    font-family: Verdana, sans-serif;
	font-size: 11px;
   	padding: 0px 0px 0px 0px;
    text-align: left;
	width: 175px;
	} 
fieldset div { 
    margin-bottom: 7px;
	}
fieldset div input, textarea, select {
	font-family: Verdana, sans-serif;
	font-size: 11px;
    border-top: 1px solid #555555;
    border-left: 1px solid #555555;
    border-bottom: 1px solid #cccccc;
    border-right: 1px solid #cccccc;
    padding: 1px;
    color: #333333;
	}
fieldset input:focus, textarea:focus, select:focus {
    background: #efefef;
    color: #000;
	}
input[type="submit"] {
	font-family: verdana, sans-serif;
	font-size: 11px;
	padding-left: 0px;
	text-align: left;
	vertical-align: middle;
	}
input.noBorderIE{
	font-family: Verdana, sans-serif;
	font-size: 11px;
	border: 0px;
	}
</style>
<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
<script language="JavaScript" type="text/JavaScript">
//<!--
function chkFrm(){
dirty = 0;
warn = "Your search could not be completed because of the following reasons:";
startDate = document.frmEventSearch.startDate.value;
endDate = document.frmEventSearch.endDate.value;
	
	if(!isDate(document.frmEventSearch.startDate.value, '<?echo $hc_popDateValid;?>')){
		dirty = 1;
		warn = warn + '\n*Start Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
	} else if(document.frmEventSearch.startDate.value == ''){
		dirty = 1;
		warn = warn + "\n*Start Date is Required";
	}//end if 
	
	if(!isDate(document.frmEventSearch.endDate.value, '<?echo $hc_popDateValid;?>')){
		dirty = 1;
		warn = warn + '\n*End Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
	} else if(document.frmEventSearch.endDate.value == ''){
		dirty = 1;
		warn = warn + "\n*End Date is Required";
	}//end if
	
	if(compareDates(startDate, '<?echo $hc_popDateValid;?>', endDate, '<?echo $hc_popDateValid;?>') == 1){
		dirty = 1;
		warn = warn + "\n*Start Date Cannot Occur After End Date";
	}//end if

	if(validateCheckArray('frmEventSearch','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and try again.');
		return false;
	} else {
		return true;
	}//end if
}//end chkFrm

var calx = new CalendarPopup();
//-->
</script>
<title><?echo CalName;?> -- Event Export</title>
</head>
<?	//if(!isset($_SESSION['AdminLoggedIn'])){
	if(1 == 2){
		exit("You must be logged in to access Helios export.");
	} else {	?>
			<form name="frmEventSearch" id="frmEventSearch" method="post" action="export.php" onsubmit="return chkFrm();">
			<input type="hidden" name="dateFormat" id="dateFormat" value="<?echo $hc_popDateFormat;?>">
			<fieldset>
				<legend>Select Your Export Type</legend>
				<div class="frmReq">
					<select name="eID" id="eID">
						<option value="1">Text Export</option>
						<option value="2">CSV Export</option>
					</select>
				</div>
			</fieldset>
			<fieldset>
				<legend>Select Delivery Method</legend>
				<div class="frmReq">
					<select name="mID" id="mID">
						<option value="1">Display Output to Screen</option>
						<option value="2">Download as File</option>
					</select>
				</div>
			</fieldset>
			<fieldset>
				<legend>Events Ocurring From</legend>
				<div class="frmReq">
					<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?echo date($hc_popDateFormat);?>">
					&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.startDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" align="middle" /></a>&nbsp;
					&nbsp;to&nbsp;&nbsp;
					<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" class="input">
					&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.endDate,'anchor2','<?echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" align="middle" /></a>
				</div>
			</fieldset>
			<fieldset>
				<legend>In the Following Categories</legend>
				<div class="frmReg">
				<?	$query = "SELECT " . HC_TblPrefix . "categories.*, NULL as EventID FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName";	?>
					<table cellpadding="0" cellspacing="0" border="0"><tr>
					<?	$result = doQuery($query);
						$cnt = 0;
						
						while($row = mysql_fetch_row($result)){
							if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
							<td><label for="catID_<?echo $row[0];?>" class="category"><input <?if($row[6] != ''){echo "checked=\"checked\"";}//end if?> name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
						<?	$cnt = $cnt + 1;
						}//end while?>
					</tr></table>
				<?	if($cnt > 1){	?>
						<div style="text-align:right;padding:10px 0px 10px 0px;">
						[ <a class="main" href="javascript:;" onclick="checkAllArray('frmEventSearch', 'catID[]');">Select All Categories</a> 
						&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmEventSearch', 'catID[]');">Deselect All Categories</a> ]
						</div>
				<?	}//end if	?>
				</div>
				<input type="submit" name="submit" id="submit" value=" Generate Output " />
				<br /><br />
			</fieldset>
			</form>
<?	}//end if
} else {
	$catID = $_POST['catID'];
	$catIDWhere = "0";
	$cnt = 0;
	foreach ($catID as $val){
		$catIDWhere = $catIDWhere . "," . $val;
		$cnt++;
	}//end while
	switch($_POST['eID']){
		case 1:	
			header ('Content-Type:text/plain; charset=utf-8');
			include('../events/includes/include.php');
			$result = doQuery("SELECT DISTINCT e.Title, c.CategoryName, MIN(e.StartDate), MAX(e.StartDate), e.Description, 
								e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
								e.LocationZip, e.ContactName, e.ContactEmail, e.ContactPhone, e.ContactURL, e.StartTime, e.EndTime
								FROM hc_events e
									LEFT JOIN hc_eventcategories ON (e.PkID = hc_eventcategories.EventID)
									LEFT JOIN hc_categories c ON (c.PkID = hc_eventcategories.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
								WHERE e.IsActive = 1 AND
											e.IsApproved = 1 AND
											(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], "/", $_POST['dateFormat']) . "' AND '" . dateToMySQL($_POST['endDate'], "/", $_POST['dateFormat']) . "') AND
											c.IsActive = 1
								GROUP BY e.Title
								ORDER BY CategoryName, StartDate, Title");
			if(hasRows($result)){
				if($_POST['mID'] == 2){
					header('Content-Disposition:attachment; filename=' . date("Y-m-d") . 'HeliosOutput.rtf');
				}//end if
				echo "<v1.7>" . chr(13) . chr(10);
				$curCat = "";
				while($row = mysql_fetch_row($result)){
					if($curCat != $row[1]){
						$curCat = $row[1];
						echo "@event head:" . $row[1] . chr(13) . chr(10);
					}//end if
					
					if($row[2] != $row[3]){
						echo "@date head:" . stampToDate($row[2], "F j") . " through " . stampToDate($row[3], "F j") . chr(13) . chr(10);
					} else {
						echo "@date head:" . stampToDate($row[2], "F j") . chr(13) . chr(10);
					}//end if
					
					$output = "@calendar copy:<B>" . unhtmlentities(preg_replace(array('/\r/', '/\n/'), "", strip_tags($row[0]))) . "</B>,";
					$output .= " Location: " . $row[5] . " " . $row[6] . " " . $row[7] . " " . $row[8] . " " . $row[9] . " " . $row[10];
					$output .= ". " . unhtmlentities(preg_replace(array('/\r/', '/\n/'), "", strip_tags($row[4])));
					if($row[15] != ''){
						$timepart = split(":", $row[15]);
						$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
						$output .= " Time: " .  $startTime;
						if($row[16] != ''){
							$timepart = split(":", $row[16]);
							$endTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
							$output .= " - " . $endTime;
						}//end if
					}//end if
					$output .= " Contact: " . $row[11] . " " . $row[12] . " " . $row[13] . " " . $row[14];
					echo str_replace("   ", "", $output);
					echo chr(13) . chr(10) . chr(13) . chr(10);
				}//end while
			} else {
				echo "Their were no events found for that criteria.";
			}//end if
			break;
			
		case 2:
			header('Content-Type:text/plain; charset=utf-8');
			include('../events/includes/include.php');
			$result = doQuery("SELECT c.CategoryName, e.*
								FROM hc_events e
									LEFT JOIN hc_eventcategories ON (e.PkID = hc_eventcategories.EventID)
									LEFT JOIN hc_categories c ON (c.PkID = hc_eventcategories.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
								WHERE e.IsActive = 1 AND
											e.IsApproved = 1 AND
											(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], "/", $_POST['dateFormat']) . "' AND '" . dateToMySQL($_POST['endDate'], "/", $_POST['dateFormat']) . "') AND
											c.IsActive = 1
								ORDER BY CategoryName, StartDate, Title, SeriesID");
			if(hasRows($result)){
				if($_POST['mID'] == 2){
					header('Content-Disposition:attachment; filename=' . date("Y-m-d") . 'HeliosOutput.csv');
				}//end if
				echo "title,eventdate,startdate,stopdate,starttime,stoptime,repeats,special,eventtext,location,laddress,laddress2,lcity,lstate,lzip,uri,contactname,contactemail,contactphone\n";
				while($row = mysql_fetch_row($result)){
					echo str_replace(",", "", $row[2]) . ",";
					echo str_replace(",", "", $row[10]) . ",";
					echo str_replace(",", "", $row[10]) . ",";
					echo ",";
					echo str_replace(",", "", $row[11]) . ",";
					echo str_replace(",", "", $row[13]) . ",";
					echo ",";
					if($row[19] == 0){
						echo "no,";
					} else {
						echo "yes,";
					}//end if
					echo str_replace(",", "",strip_tags($row[9])) . ",";
					echo $row[3] . ",";
					echo $row[4] . ",";
					echo $row[5] . ",";
					echo $row[6] . ",";
					echo $row[7] . ",";
					echo $row[8] . ",";
					if($row[25] != 'http://' && $row[25] != ''){
						echo $row[25] . ",";
					} else {
						echo ",";
					}//end if
					echo str_replace(",", "", $row[14]) . ",";
					echo str_replace(",", "", $row[15]) . ",";
					echo str_replace(",", "", $row[16]);
					echo "\n";
				}//end while
			} else {
				echo "Their were no events found for that criteria.";
			}//end if
			break;
	}//end switch
}//end if	


function unhtmlentities($text) {
   $text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
   $text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text); #decimal notation
   $text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);  #hex notation
   return $text;
}//end unhtmlentities()	?>