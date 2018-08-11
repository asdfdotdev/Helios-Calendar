<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/manage.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed01']);
				break;
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsBillboard = 1 AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate, Views DESC");
	if(hasRows($result)){
		appInstructions(0, "Billboard_Events", $hc_lang_manage['TitleBillboard'], $hc_lang_manage['InstructBillboard']);	?>
		
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(eID){
			if(confirm('<?php echo $hc_lang_manage['Valid01'] . "\\n\\n          " . $hc_lang_manage['Valid02'] . "\\n          " . $hc_lang_manage['Valid03'];?>')){
				window.location.href='<?php echo CalAdminRoot . "/components/EventBillboardAction.php";?>?eID=' + eID;
			}//end if
		}//end doDelete
		//-->
		</script>
<?php
		echo '<div class="billboardList">';
		echo '<div class="billboardListTitle"><b>' . $hc_lang_manage['Title'] . '</b></div>';
		echo '<div class="billboardListDate"><b>' . $hc_lang_manage['Date'] . '</b></div>';
		echo '<div class="billboardListViews"><b>' . $hc_lang_manage['Views'] . '</b></div>';
		echo '<div class="billboardListTools">&nbsp;</div>&nbsp;';
		echo '</div>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="billboardListTitleHL">' : '<div class="billboardListTitle">';
			echo cOut($row[1]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="billboardListDateHL">' : '<div class="billboardListDate">';
			echo stampToDate($row[9], $hc_cfg24) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="billboardListViewsHL">' : '<div class="billboardListViews">';
			echo cOut($row[28]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="billboardListToolsHL">' : '<div class="billboardListTools">';
			echo '<div class="hc_align">&nbsp;<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div></div>';
			
			++$cnt;
		}//end while
	} else {
		echo "<br />";
		echo "<b>" . $hc_lang_manage['NoBillboard'] . "</b>";
	}//end if	?>