<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
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

if(isset($_GET['date'])){
	$theDate = $_GET['date'];
} else {
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");
}//end if

$datepart = split("-", $theDate);

if(!checkdate($datepart[1], $datepart[2], $datepart[0])){
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");
	$datepart = split("-", $theDate);
}//end if

$theDate = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));
$nextDate = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] + 1, $datepart[0]));
$dateStamp = date("D M j Y", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));

hookDB();
?>

<wml>

<card id="no1" title="<?echo CalName;?> Mobile Site">
<p>
<table columns="3">
	<tr>
		<td><a href="<?echo CalRoot;?>/wml/?date=<?echo $nextDate;?>">Next</a>&nbsp;&nbsp;&nbsp;</td>
		<td><a href="<?echo CalRoot;?>/wml/?date=<?echo date("Y") . "-" . date("m") . "-" . date("d");?>">Today</a>&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
<table columns="1">
	<?php
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $theDate . "' ORDER BY StartDate, TBD, StartTime, Title");
	?>
	<tr>
		<td><b><?echo cleanWMLChars( $dateStamp );?></b></td>
	</tr>
	<?php
		while($row = mysql_fetch_row($result)){
		?>
		<tr>
				<?php
					if($row[10] != ''){
						$timepart = split(":", $row[10]);
						$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
					} else {
						$startTime = "";
					}//end if
					
					//check for valid start time
					if($row[11] == 0){
						$timeStamp = $startTime;
					} elseif($row[11] == 1) {
						$timeStamp = "<i>All Day</i>";
						
					} elseif($row[11] == 2) {
						$timeStamp = "<i>TBA</i>";
						
					}//end if
				?>
			<td><a href="<?echo CalRoot;?>/wml/details.php?eID=<?echo $row[0];?>"><?echo cleanWMLChars(strip_tags($timeStamp)) . "--" . cleanWMLChars(strip_tags($row[1]));?></a><br/><br/></td>
		</tr>
		<?
		}//end while
	?>
</table>
</p>
<p align="center">
	<?echo CalName;?> Mobile Site<br/>
	Powered by Helios <?echo HC_Version;?>
</p>
</card>

</wml>