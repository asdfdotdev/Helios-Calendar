<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$result = doQuery("SELECT PkID, Title, Description, StartDate, PublishDate, Views FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW() ORDER BY Views DESC LIMIT 25");
	
	if(hasRows($result)){	?>
		<br />
		The following are our most popular events.
		<br />
		For more information about an event, click its title.
		<br />
	<?	$cnt = 1;
		$sty = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="HotListTitle<?if($cnt % 2 == 0){echo "HL";}?>"><b><?echo $cnt;?>) <a href="<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $row[0];?>" class="eventMain"><?echo $row[1];?></a></b>
			&nbsp;<b>[&nbsp;<?echo stampToDate($row[3], $hc_dateFormat);?>&nbsp;]</b></div>
		<?	if($cnt < 11){	?>
				<div class="HotListTeaser<?if($cnt % 2 == 0){echo "HL";}?>"><?echo makeTeaser(strip_tags($row[2]), 100);?><br />[ <a href="<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $row[0];?>" class="eventMain">Event Details</a> ]</div>
				<br />
		<?	}//end if
			$cnt++;
		}//end while
	} else {	?>
		<br />
		There are currently no events on the Hot List. 
		<br /><br />
		Please check back soon.
<?	}//end if	?>