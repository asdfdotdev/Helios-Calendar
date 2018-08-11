<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/comment.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case '1':
				feedback(1,$hc_lang_comment['Feed01']);
				break;
		}//end switch
	}//end if
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn($_GET['uID']) : 0;
	$resDiff = 5;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resPage = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$helpDescription = ($uID > 0) ? $hc_lang_comment['InstructBrowseU'] : $hc_lang_comment['InstructBrowse'];
	
	appInstructions(0, "Comment_Management", $hc_lang_comment['TitleBrowse'], $helpDescription);
	
	$doUser = ($uID > 0) ? " AND c.PosterID = '" . cIn($uID) . "'" : '';
	$uLink = ($uID > 0) ? "&uID=" . cIn($uID) : '';
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "comments c WHERE c.IsActive = 1 AND c.TypeID = 1" . $doUser);
	$totPages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($totPages <= $resPage && $totPages > 0){$resPage = ($totPages - 1);}
	
	$result = doQuery("SELECT c.*, e.PkID, e.Title, u.PkID, u.Identity, u.ShortName
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers u ON (c.PosterID = u.PkID)
						WHERE c.IsActive = 1 AND c.TypeID = 1" . $doUser . "
						ORDER BY PostTime DESC
						LIMIT " . $resLimit . " OFFSET " . ($resPage * $resLimit));
	
	if(hasRows($result)){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_comment['Valid01'] . "\\n\\n          " . $hc_lang_comment['Valid02'] . "\\n          " . $hc_lang_comment['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/CommentDelete.php";?>?dID=' + dID + '&uID=<?php echo $uID;?>';
			}//end if
		}//end doDelete
		//-->
		</script>
	<?php
		echo '<br />';
		echo ($uID > 0) ? '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt" class="main">' . $hc_lang_comment['AllUsers'] . '</a><br /><br />' :'';
		echo '<b>' . $hc_lang_comment['PerPage'] . '</b> ';
		for($x = 25;$x <= 100;$x = $x + 25){
			if($x > 25){echo "&nbsp;|&nbsp;";}			
			echo ($x != $resLimit) ?
				'<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&p=' . $resPage . '&a=' . $x . $uLink . '" class="main">' . $x . '</a>':
				'<b>' . $x . '</b>';
		}//end for
		
		$x = (($resPage - $resDiff) > 0) ? ($resPage - $resDiff) : 0;
		$cnt = 0;
		
		echo '<div style="clear:both;padding-top:10px;"><b>' . $hc_lang_comment['Page'] . '</b>&nbsp;';
		if($resPage > ($resDiff)){
			echo '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&p=0&a=' . $resLimit . '" class="main">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff*2) && $x <= ($totPages - 1)){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resPage != $x) ?
				'<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&p=' . $x . '&a=' . $resLimit . $uLink . '" class="main">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resPage < ($totPages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&p=' . ($totPages - 1) . '&a=' . $resLimit . $uLink . '" class="main">' . $totPages . '</a>';
		}//end if
		echo '</div><br />';
		
		while($row = mysql_fetch_row($result)){
			echo '<div class="commentFrame" >';		
			echo '<div class="commentTools">';
			echo '<a href="' . CalRoot . '/index.php?com=detail&eID=' . $row[8] . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconViewPublic.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($adminUserEdit == 1) ? '<a href="' . CalAdminRoot . '/index.php?com=oidedit&amp;uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;' : '';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\',2);return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconCommentDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($uID == 0) ? '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>' : '';
			echo '<br />';
			echo ($row[6] > 0) ? '+' . $row[6] : $row[6];
			echo ' Recomnds <br /></div>';
			echo '<b>' . $hc_lang_comment['About'] . ' <i>' . $row[9] . '</i>, <a href="' . $row[11] . '" target="_blank" class="main">' . $row[12] . '</a> ' . $hc_lang_comment['Said'] . '</b>';
			echo '<br /><br /><div id="comment_' . $row[0] . '" class="comment">';
			echo nl2br($row[1]);
			echo '<div class="commentDate">' . stampToDate($row[3], $hc_cfg24 . ' @ ' . $hc_cfg23) . '</div>';
			echo '</div></div>';
			++$cnt;
		}//end while		
	} else {
		echo '<br />';
		echo ($uID > 0) ? $hc_lang_comment['NoCommentsU'] : $hc_lang_comment['NoComments'];
		echo '<br /><br />';
		echo '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt" class="main">' . $hc_lang_comment['AllUsers'] . '</a><br /><br />';
	}//end if
	
	?>