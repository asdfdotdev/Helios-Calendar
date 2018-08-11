<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');

	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 0");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(CalAdminRoot . '/components/MailSubEditAction.php?dID=uc&a=1','iconUserDelete.png',$hc_lang_news['DeleteNoConfirm'] . ' <b>' . $num . '</b>',0);

	$queryS = $saveLink = $term = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$queryS = " AND (FirstName LIKE('%" . $term . "%') OR LastName LIKE('%" . $term . "%') OR Email LIKE('%" . $term . "%'))";
		$saveLink = '&s=' . $term;
	}//end if

	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix  . "subscribers WHERE IsConfirm = 1 $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($pages <= $resOffset && $pages > 0){$resOffset = ($pages - 1);}?>

	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_news['Valid08'] . "\\n\\n          " . $hc_lang_news['Valid09'] . "\\n          " . $hc_lang_news['Valid10'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/MailSubEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed05']);
				break;
			case "2" :
				feedback(1, $hc_lang_news['Feed18']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Manage_Subscribers", $hc_lang_news['TitleBrowseS'], $hc_lang_news['InstructBrowseS']);

	echo '<br /><fieldset style="border:0px;">';
	echo '<div class="frmOpt">';
	echo '<label><b>' . $hc_lang_news['ResPer'] . '</b></label>';
	for($x = 25;$x <= 100;$x = $x + 25){
		if($x > 25){echo "&nbsp;|&nbsp;";}
		echo ($x != $resLimit) ?
			'<a href="' . CalAdminRoot . '/index.php?com=submngt&amp;p=' . $resOffset . '&amp;a=' . $x . $saveLink . '" class="eventMain">' . $x . '</a>':
			'<b>' . $x . '</b>';
	}//end for
	echo '</div>';

	$cnt = 0;
	$x = (($resOffset - $resDiff) < 0) ? 0 : $resOffset - $resDiff;

	echo '<div class="frmOpt"><label><b>' . $hc_lang_news['Page'] . '</b></label>';
	echo (($resOffset > $resDiff) && $pages > 0) ? '<a href="' . CalAdminRoot . '/index.php?com=submngt&p=0&a=' . $resLimit . $saveLink . '" class="eventMain">1</a>&nbsp;...&nbsp;' : '';
	while($cnt <= ($resDiff * 2) && $x < $pages){
		echo ($cnt > 0) ? ' | ' : '';
		echo ($resOffset != $x) ?
			'<a href="' . CalAdminRoot . '/index.php?com=submngt&amp;p=' . $x . '&amp;a=' . $resLimit . $saveLink . '" class="eventMain">' . ($x + 1) . '</a>':
			'<b>' . ($x + 1) . '</b>';
		++$x;
		++$cnt;
	}//end while
	echo ($pages == 0) ? '1' : '';
	echo (($resOffset < ($pages - ($resDiff + 1))) && $pages > 0) ? '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=submngt&p=' . ($pages - 1) . '&a=' . $resLimit . $saveLink . '" class="eventMain">' . $pages . '</a>' : '';
	echo '</div>';
	echo '<div class="frmOpt">';
	echo '<label>&nbsp;</label>';
	echo '<input type="text" name="filter" id="filter" size="30" maxlength="50" value="' . $term . '" />';
	echo '<input type="button" name="go" id="go" value="' . $hc_lang_news['Filter'] . '" class="buttonFilter" onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=submngt&p=0&a=' . $resLimit . '&s=\'+document.getElementById(\'filter\').value;" />';
	echo ($saveLink != '') ? '<div class="frmOpt"><label>&nbsp;</label><a href="' . CalAdminRoot . '/index.php?com=submngt" class="main">' . $hc_lang_news['NoSubLink'] . '</a></div>' : '';
	echo '</div></fieldset>';

	$result = doQuery("SELECT PkID, FirstName, LastName, Email FROM " . HC_TblPrefix  . "subscribers WHERE IsConfirm = 1 $queryS ORDER BY LastName, FirstName LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '<div class="userList">';
		echo '<div class="userListName"><b>' . $hc_lang_news['Name'] . '</b></div>';
		echo '<div class="userListEmail"><b>' . $hc_lang_news['Emailb'] . '</b></div>';
		echo '<div class="userListTools">&nbsp;</div>';
		echo '&nbsp;</div>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			if($cnt >0){echo "<br />";}
			
			echo ($cnt % 2 == 1) ? '<div class="userListNameHL">' : '<div class="userListName">';
			echo '<div class="hc_align">' . cOut($row[1]) . '</div><div class="hc_align">&nbsp;' . cOut($row[2]) . '&nbsp;</div></div>';
			
			echo ($cnt % 2 == 1) ? '<div class="userListEmailHL">' : '<div class="userListEmail">';
			echo '&nbsp;' . cOut($row[3]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="userListToolsHL">' : '<div class="userListTools">';
			echo '<a href="' . CalAdminRoot . '/?com=subedit&amp;uID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="javascript:doDelete(\'' . $row[0] . '\'); return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
			
			++$cnt;
		}//end while
	} else {
		echo '<p>' . $hc_lang_news['NoSub'] . '</p>';
	}//end if	?>