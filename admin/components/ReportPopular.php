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
	
	if(!isset($_GET['mID'])){
		appInstructions(0, "Reports", $hc_lang_reports['TitlePop'], $hc_lang_reports['InstructPop']);
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= '" . date("Y-m-d") . "' 
								AND IsActive = 1 
								AND IsApproved = 1
								AND Views > 0
							ORDER BY Views DESC, StartDate
							LIMIT 50");
	} else {
		appInstructions(0, "Reports", $hc_lang_reports['TitlePopM'], $hc_lang_reports['InstructPopM']);
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= '" . date("Y-m-d") . "' 
								AND IsActive = 1 
								AND IsApproved = 1 
								AND MViews > 0
							ORDER BY MViews DESC, StartDate
							LIMIT 50");
	}//end if
	
	if(hasRows($result)){	?>
		<div class="mostPopularList">
			<div class="mostPopularTitle"><b><?php echo $hc_lang_reports['Event'];?></b></div>
			<div class="mostPopularDate"><b><?php echo $hc_lang_reports['Occurs'];?></b></div>
			<div class="mostPopularViews"><b><?php echo $hc_lang_reports['Views'];?></b></div>
			&nbsp;
		</div>
<?php 	$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(isset($_GET['mID'])){
				$viewCount = $row[34];
			} else {
				$viewCount = $row[28];
			}//end if
			
			if($viewCount == 0){
				break;
			}//end if	?>
			<div class="mostPopularTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
			<div class="mostPopularDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[9], $hc_popDateFormat);?></div>
			<div class="mostPopularViews<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($viewCount);?></div>
			<div class="mostPopularTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;
		<?php 	if($row[18] == 1){	?>
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventbillboard" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconBillboard.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>
		<?php 	} else {	?>
				<img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="15" height="15" alt="" border="0" style="vertical-align:middle;" />
		<?php 	}//end if	?>
			</div>
	<?php
		$cnt = $cnt + 1;
		}//end while
	} else {
		echo "<br /><br />";
		echo $hc_lang_report['NoEvents'];
	}//end if	?>