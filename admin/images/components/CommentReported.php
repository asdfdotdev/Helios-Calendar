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
			case '2':
				feedback(1,$hc_lang_comment['Feed02']);
				break;
		}//end switch
	}//end if
	
	$resDiff = 5;
	$resLimit = 5;
	$resPage = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	
	appInstructions(0, "Reported_Comments", $hc_lang_comment['TitleReport'], $hc_lang_comment['InstructReport']);
	
	$resultC = doQuery("SELECT COUNT(*) 
						FROM " . HC_TblPrefix . "comments c 
							LEFT JOIN " . HC_TblPrefix . "commentsreportlog crl ON (c.PkID = crl.CommentID)
						WHERE c.IsActive = 1 AND c.TypeID = 1 AND crl.PkID IS NOT NULL");
	$totPages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($totPages <= $resPage && $totPages > 0){$resPage = ($totPages - 1);}
	
	$result = doQuery("SELECT c.*, e.PkID, e.Title, u.PkID, u.Identity, u.ShortName
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers u ON (c.PosterID = u.PkID)
							LEFT JOIN " . HC_TblPrefix . "commentsreportlog crl ON (c.PkID = crl.CommentID)
						WHERE c.IsActive = 1 AND c.TypeID = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND crl.IsActive = 1 AND crl.PkID IS NOT NULL
						GROUP BY c.PkID
						ORDER BY PostTime DESC
						LIMIT " . $resLimit . " OFFSET " . ($resPage * $resLimit));
	
	if(hasRows($result)){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_comment['Valid01'] . "\\n\\n          " . $hc_lang_comment['Valid02'] . "\\n          " . $hc_lang_comment['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/CommentDelete.php";?>?dID=' + dID + '&tID=1';
			}//end if
		}//end doDelete
		
		function doDeleteR(dID){
			if(confirm('<?php echo $hc_lang_comment['Valid04'] . "\\n\\n          " . $hc_lang_comment['Valid05'] . "\\n          " . $hc_lang_comment['Valid06'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/CommentDelete.php";?>?dID=' + dID + '&tID=2';
			}//end if
		}//end doDelete
		//-->
		</script>
	<?php
		$x = (($resPage - $resDiff) > 0) ? ($resPage - $resDiff) : 0;
		$cnt = 0;
		
		echo '<div style="clear:both;padding-top:10px;"><b>' . $hc_lang_comment['Page'] . '</b>&nbsp;';
		if($resPage > ($resDiff)){
			echo '<a href="' . CalAdminRoot . '/index.php?com=cmntrep&p=0&a=' . $resLimit . '" class="main">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff*2) && $x <= ($totPages - 1)){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resPage != $x) ?
				'<a href="' . CalAdminRoot . '/index.php?com=cmntrep&p=' . $x . '&a=' . $resLimit . '" class="main">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resPage < ($totPages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntrep&p=' . ($totPages - 1) . '&a=' . $resLimit . '" class="main">' . $totPages . '</a>';
		}//end if
		echo '</div><br />';
		
		while($row = mysql_fetch_row($result)){
			echo '<div class="commentFrame" >';		
			echo '<div class="commentTools">';
			echo '<a href="' . CalRoot . '/index.php?com=detail&eID=' . $row[8] . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconViewPublic.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="' . CalAdminRoot . '/index.php?com=oidedit&amp;uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\',2);return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconCommentDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDeleteR(\'' . $row[0] . '\',2);return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconReportDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
			echo '<br />';
			echo ($row[6] > 0) ? '+' . $row[6] : $row[6];
			echo ' Recomnds <br /></div>';
			echo '<b>' . $hc_lang_comment['About'] . ' <i>' . $row[9] . '</i>, <a href="' . $row[11] . '" target="_blank" class="main">' . $row[12] . '</a> ' . $hc_lang_comment['Said'] . '</b>';
			echo '<br /><br /><div id="comment_' . $row[0] . '" class="comment">';
			echo nl2br($row[1]);
			echo '<div class="commentDate">' . stampToDate($row[3], $hc_cfg24 . ' @ ' . $hc_cfg23) . '</div>';
			
			$resultR = doQuery("SELECT crl.*, oid.Identity, oid.ShortName
								FROM " . HC_TblPrefix . "commentsreportlog crl
									LEFT JOIN " . HC_TblPrefix . "oidusers oid ON (crl.UserID = oid.PkID)
								WHERE crl.IsActive = 1 AND CommentID = " . $row[0]);
			if(hasRows($resultR)){
				echo '<blockquote><div class="commentReport">';
				echo '<div class="commentReportHeader">' . $hc_lang_comment['Report'] . '</div>';
				$cnt = 0;
				while($row = mysql_fetch_row($resultR)){
					echo ($cnt % 2 == 0) ? '<div class="theReport">' : '<div class="theReportHL">';
					echo '<div style="clear:both;float:left;width:12%;font-weight:bold;">' . $hc_lang_comment['By'] . '</div> ' . $row[4] . '<br />';
					echo ($row[3] > 0) ? '<div style="clear:both;float:left;width:12%;font-weight:bold;">' . $hc_lang_comment['OpenID'] . '</div> ' . $row[10] . '&nbsp;&nbsp;<a href="' . CalAdminRoot . '/index.php?com=oidedit&amp;uID=' . $row[3] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a><br />' : '';
					echo '<div style="clear:both;float:left;width:12%;font-weight:bold;">' . $hc_lang_comment['From'] . '</div> ' . $row[7];
					echo '<br />' . nl2br($row[6]);
					echo '</div>';
					++$cnt;
				}//end while
				echo '</div></blockquote>';
			}//end if
			
			echo '</div></div>';
			++$cnt;
		}//end while		
	} else {
		echo '<br />';
		echo $hc_lang_comment['NoComments'];
		echo '<br /><br />';
	}//end if	?>