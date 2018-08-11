<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	$hc_Side[] = array(CalRoot . '/index.php?com=archive','email_open.png',$hc_lang_news['ViewArchive'],1);

	$type = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn($_GET['t']) : 0;
	$queryT = ($type == 1) ? " AND n.Status > 2" : " AND n.Status <= 2";
	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$term = $save = $queryS = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$save = '&s='.$term;
		$queryS = " AND MATCH(Subject,Message) AGAINST('" . str_replace("'", "\"", $term) . "' IN BOOLEAN MODE)";
	}
	
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix  . "newsletters n WHERE n.IsActive = 1 $queryT $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	$resOffset = ($pages <= $resOffset && $pages > 0) ? $pages - 1 : $resOffset;
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed16']);
				break;
			case "2" :
				feedback(1, $hc_lang_news['Feed17']);
				break;
			case "3" :
				feedback(1, $hc_lang_news['Feed19']);
				break;
		}
	}

	appInstructions(0, "Newsletter_Queue", $hc_lang_news['TitleQueue'], $hc_lang_news['InstructQueue']);

	echo '
		<fieldset style="border:0px;">
			<label><b>' . $hc_lang_news['View'] . '</b></label>
			<span class="output">
			'.(($type == 1) ?
				'<a href="'.AdminRoot.'/index.php?com=newsqueue&p=0&a='.$resLimit.'&t=0'.$save.'">'.$hc_lang_news['InProgress'].'</a> | <b>'.$hc_lang_news['Complete'].'</b>':
				'<b>'.$hc_lang_news['InProgress'].'</b> | <a href="'.AdminRoot.'/index.php?com=newsqueue&p=0&a='.$resLimit.'&t=1'.$save.'">'.$hc_lang_news['Complete'].'</a>').'
			</span>
			<label><b>'.$hc_lang_news['ResPer'].'</b></label>
			<span class="output">';
			
	for($x = 25;$x <= 100;$x = $x + 25){
		echo ($x > 25) ? '&nbsp;|&nbsp;' : '';			
		echo ($x != $resLimit) ?
			'<a href="'.AdminRoot.'/index.php?com=newsqueue&amp;p='.$resOffset.'&amp;a='.$x.'&t='.$type.$save.'">'.$x.'</a>':
			'<b>'.$x.'</b>';
	}
		
	echo '
			</span>
			<label><b>'.$hc_lang_news['Page'].'</b></label>
			<span class="output">';

	$x = ($resOffset - $resDiff > 0) ? $resOffset - $resDiff : 0;
	$cnt = 0;

	echo ($resOffset > $resDiff) ? '<a href="'.AdminRoot.'/index.php?com=newsqueue&p=0&a='.$resLimit.'">1</a>&nbsp;...&nbsp;' : '';

	while($cnt <= ($resDiff * 2) && $x < $pages){
		echo ($cnt > 0) ? ' | ' : '';
		echo ($resOffset != $x) ?
			'<a href="'.AdminRoot.'/index.php?com=newsqueue&amp;p='.$x.'&amp;a='.$resLimit.'&t='.$type.$save.'">'.($x + 1).'</a>':
			'<b>' . ($x + 1) . '</b>';
		++$x;
		++$cnt;
	}

	echo ($resOffset < ($pages - ($resDiff + 1))) ? '&nbsp;...&nbsp;<a href="'.AdminRoot.'/index.php?com=newsqueue&p='.($pages - 1).'&a='.$resLimit.'">'.$pages.'</a>' : '';
	echo '
			</span>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<input name="filter" id="filter" type="text" size="30" maxlength="50" value="'.$term.'" />
				<input name="filter_go" id="filter_go" type="button" value="'.$hc_lang_news['FilterNews'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=newsqueue&p=0&a='.$resLimit.'&t='.$type.'&s=\'+document.getElementById(\'filter\').value;" />
			</span>
			'.(($term != '') ? '<label>&nbsp;</label><span class="output"><a href="'.AdminRoot.'/index.php?com=newsqueue&p=0&a='.$resLimit.'&t='.$type.'">'.$hc_lang_news['AllNewsLink'].'</a></span>' : '').'
		</fieldset>';
	
	$result = doQuery("SELECT n.PkID, n.SentDate, n.Subject, n.SendCount, n.`Status`, n.IsArchive, n.SendingAdminID,
					(SELECT COUNT(ns.SubscriberID)
						FROM " . HC_TblPrefix . "newssubscribers ns
						WHERE ns.NewsletterID = n.PkID) as ToGo
					FROM " . HC_TblPrefix . "newsletters n
					WHERE n.IsActive = 1 $queryT $queryS
					ORDER BY n.Status, n.SentDate DESC, PkID DESC
					LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:15%;">'.$hc_lang_news['Created'].'</div>
				<div style="width:43%;">'.$hc_lang_news['Subject'].'</div>
				<div style="width:10%;">'.$hc_lang_news['Status'].'</div>
				<div class="number" style="width:10%;">'.$hc_lang_news['Count'].'</div>
				<div class="number" style="width:10%;">'.$hc_lang_news['Progress2'].'</div>
				<div style="width:12%;">&nbsp;</div>
			</li>';

		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
			<li class="row'.$hl.'">
				<div style="width:15%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
				<div class="txt" title="'.cOut($row[2]).'" style="width:43%;">'.cOut($row[2]).'&nbsp;</div>
				<div style="width:10%;">'.$hc_lang_news['Status' . $row[4]].'</div>
				<div class="number" style="width:10%;">'.number_format($row[3],0,'.',',').'</div>
				<div class="number" style="width:10%;">'.(($row[4] == 3) ? '100%' : number_format(100-(($row[7] / $row[3]) * 100),0) . '%').'</div>
				<div class="tools" style="width:12%;">';
				if($type == 0){
					echo '<img src="'.AdminRoot.'/images/spacer.gif" width="16" height="1" alt="" />';
					echo ($row[4] == 1) ?
						'<a href="javascript:;" onclick="doPause(\''.$row[0].'\');return false;""><img src="'.AdminRoot.'/img/icons/pause.png" width="16" height="16" alt="" /></a>':
						'<a href="'.AdminRoot.'/index.php?com=newssend&amp;nID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/play.png" width="16" height="16" alt="" /></a>';
				} else {
					echo (($row[5] == 1) ? 
						'<a href="'.CalRoot.'/newsletter/index.php?n='.md5($row[0]).'" target="_blank"><img src="'.AdminRoot.'/img/icons/email_open.png" width="16" height="16" alt="" /></a>':
						'<img src="' . AdminRoot . '/images/spacer.gif" width="16" height="1" alt="" />').'
					<a href="'.AdminRoot.'/index.php?com=newsreport&amp;rID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/report.png" width="16" height="16" alt="" /></a>';
				}
			echo '
				<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/email_delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</ul>
		
		<script>
		//<!--
		function doPause(nID){
			if(confirm("'.$hc_lang_news['Valid35'].'\\n\\n          '.$hc_lang_news['Valid36'].'\\n          '.$hc_lang_news['Valid37'].'"))
				document.location.href = "'.AdminRoot.'/components/NewsletterPause.php?n=" + nID + "&d=2&g=1";
		}
		function doDelete(dID){
			if(confirm("'.$hc_lang_news['Valid32'].'\\n\\n          '.$hc_lang_news['Valid33'].'\\n          '.$hc_lang_news['Valid34'].'"))
				document.location.href = "'.AdminRoot.'/components/MailQueueAction.php?dID=" + dID + "&tID='.$type.'&tkn='.set_form_token(1).'";
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_news['NoNewsletter' . $type] . '</p>';
	}
?>