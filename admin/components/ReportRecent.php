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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/reports.php');
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleAdd'], $hc_lang_reports['InstructAdd']);
	$result = doQuery("	SELECT * 
							FROM " . HC_TblPrefix . "events 
						WHERE StartDate >= '" . date("Y-m-d") . "' 
							AND IsActive = 1 
							AND IsApproved = 1
						ORDER BY PublishDate DESC, StartDate
						LIMIT 50");
	
	if(hasRows($result)){	?>
		<div class="recentList">
			<div class="recentTitle"><b><?php echo $hc_lang_reports['Event'];?></b></div>
			<div class="recentDateA"><b><?php echo $hc_lang_reports['Added'];?></b></div>
			<div class="recentDateO"><b><?php echo $hc_lang_reports['Occurs'];?></b></div>
			<div class="recentTools">&nbsp;</div>
			&nbsp;
		</div>
<?php 	$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){	?>
			<div class="recentTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[1]);?></div>
			<div class="recentDateA<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate(cOut($row[27]), $hc_popDateFormat)?></div>
			<div class="recentDateO<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate(cOut($row[9]), $hc_popDateFormat)?></div>
			<div class="recentTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" /></a>&nbsp;&nbsp;
		<?php 	if($row[18] == 1){	?>
					<a href="<?php echo CalAdminRoot;?>/index.php?com=eventbillboard" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconBillboard.png" width="15" height="15" alt="" border="0" /></a>
		<?php 	} else {	?>
					<img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="15" height="15" alt="" border="0" />
		<?php 	}//end if	?>
			</div>
<?php 	$cnt = $cnt + 1;
		}//end while
	} else {
		echo "<br /><br />";
		echo $hc_lang_report['NoEvents'];
	}//end if	?>