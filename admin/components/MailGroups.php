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

	if(isset($_GET['gID']) && is_numeric($_GET['gID'])){
		$gID = $isPublic = 0;
		$name = $descript = '';
		$helpText = $hc_lang_news['InstructAddG'];

		if($_GET['gID'] > 0){
			$gID = $_GET['gID'];
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE PkID = '" . $gID . "' AND IsActive = 1");
			if(hasRows($result)){
				$name = mysql_result($result,0,1);
				$descript = mysql_result($result,0,2);
				$isPublic = mysql_result($result,0,3);
				$helpText = $hc_lang_news['InstructEditG'];
			}//end if
		}//end if
		appInstructions(0, 'Subscriber_Groups', $hc_lang_news['TitleGroup'], $helpText);	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function togThis(doTog, doLink){
				if(document.getElementById(doTog).style.display == 'none'){
					document.getElementById(doTog).style.display = 'block';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_news['HideVariables'];?>';
				} else {
					document.getElementById(doTog).style.display = 'none';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_news['ShowVariables'];?>';
				}//end if
			}//end togThis()

		function chkFrm(){
		dirty = 0;
		warn = '<?php echo $hc_lang_news['Valid26'];?>';

			if(document.getElementById('name').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid27'];?>';
			}//end if

			if(dirty > 0){
				alert(warn + '\n\n<?php echo $hc_lang_news['Valid07'];?>');
				return false;
			} else {
				return true;
			}//end if
		}//end chkFrm()
		//-->
		</script>
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/MailGroupsAction.php";?>" onsubmit="return chkFrm();">
		<input type="hidden" name="gID" id="nID" value="<?php echo cOut($gID);?>" />
<?php	if($gID == 1){
			echo '<input type="hidden" name="description" id="description" value="" />';
			echo '<input type="hidden" name="status" id="status" value="0" />';
		}//end if
		?>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['SubGroup'];?></legend>
			<div class="frmOpt">
				<label for="name"><?php echo $hc_lang_news['GroupName'];?></label>
				<input name="name" id="name" type="text" size="40" maxlength="100" value="<?php echo cOut($name);?>" />
			</div>
			<div class="frmOpt">
				<label for="description"><?php echo $hc_lang_news['GroupDesc'];?></label>
				<input <?php echo ($gID == 1) ? 'disabled="disabled"' : '';?> name="description" id="description" type="text" size="75" maxlength="250" value="<?php echo cOut($descript);?>" />
				&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip01A'],$hc_lang_news['Tip01B']);?>
			</div>
			<div class="frmOpt">
				<label for="status"><?php echo $hc_lang_news['GroupStatus'];?></label>
				<select name="status" id="status" <?php echo ($gID == 1) ? 'disabled="disabled"' : '';?>>
					<option <?php echo ($isPublic == 0) ? 'selected="selected" ' : '';?> value="0"><?php echo $hc_lang_news['AdminOnly'];?></option>
					<option <?php echo ($isPublic == 1) ? 'selected="selected" ' : '';?> value="1"><?php echo $hc_lang_news['Public'];?></option>
				</select>
				&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip02A'],$hc_lang_news['Tip02B']);?>
			</div>
		</fieldset>
		<br />
		<input type="submit" name="submit" value=" <?php echo $hc_lang_news['SaveGroup'];?> " class="button" />
		</form>
<?php
	} else {?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_news['Valid08'] . "\\n\\n          " . $hc_lang_news['Valid09'] . "\\n          " . $hc_lang_news['Valid10'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/MailGroupsAction.php";?>?dID=' + dID;
			}//end if
		}//end doDelete
		//-->
		</script>
	<?php
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1, $hc_lang_news['Feed10']);
					break;
				case "2" :
					feedback(1, $hc_lang_news['Feed11']);
					break;
				case "3" :
					feedback(1, $hc_lang_news['Feed12']);
					break;
			}//end switch
		}//end if

		appInstructions(0, "Subscriber_Groups", $hc_lang_news['TitleGroup'], $hc_lang_news['InstructGroup']);
		echo '<br /><a href="' . CalAdminRoot . '/index.php?com=subgrps&gID=0" class="icon"><img src="' . CalAdminRoot . '/images/icons/iconAdd.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />&nbsp;' . $hc_lang_news['NewGroup'] . '</a><br />';

		$result = doQuery("SELECT PkID, Name, IsPublic,
							(SELECT COUNT(sg.UserID)
							FROM " . HC_TblPrefix . "subscribersgroups sg
								LEFT JOIN " . HC_TblPrefix . "subscribers s ON (sg.UserID = s.PkID)
							WHERE sg.GroupID = mg.PkID AND s.IsConfirm = 1) as GrpCnt,
							(SELECT COUNT(s.PkID)FROM " . HC_TblPrefix . "subscribers s WHERE s.IsConfirm = 1) as AllCnt
						FROM " . HC_TblPrefix . "mailgroups mg
						WHERE IsActive = 1 ORDER BY IsPublic, Name");

		if(hasRows($result)){
			echo '<div class="groupList">';
			echo '<div class="groupListName"><b>' . $hc_lang_news['GroupNameLabel'] . '</b></div>';
			echo '<div class="groupStatus"><b>' . $hc_lang_news['GroupStatusLabel'] . '</b></div>';
			echo '<div class="groupCount"><b>' . $hc_lang_news['GroupCountLabel'] . '</b></div>';
			echo '<div class="groupTools">&nbsp;</div>';
			echo '&nbsp;</div>';

			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				if($cnt >0){echo "<br />";}

				echo ($cnt % 2 == 1) ? '<div class="groupListNameHL">' : '<div class="groupListName">';
				echo cOut($row[1]) . '</div>';

				echo ($cnt % 2 == 1) ? '<div class="groupStatusHL">' : '<div class="groupStatus">';
				echo ($row[2] == 1) ? $hc_lang_news['Public'] : $hc_lang_news['AdminOnly'];
				echo '</div>';

				echo ($cnt % 2 == 1) ? '<div class="groupCountHL">' : '<div class="groupCount">';
				echo ($row[0] == 1) ? number_format($row[4],0,'.',',') : number_format($row[3],0,'.',',');
				echo '</div>';

				echo ($cnt % 2 == 1) ? '<div class="groupToolsHL">' : '<div class="groupTools">';
				echo '<a href="' . CalAdminRoot . '/?com=subgrps&amp;gID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
				echo ($row[0] > 1)? '<a href="javascript:;" onclick="javascript:doDelete(\'' . $row[0] . '\'); return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>' : '<img src="' . CalAdminRoot . '/images/spacer.gif" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />';
				echo '</div>';
				++$cnt;
			}//end while
		} else {
			echo '<p>' . $hc_lang_news['NoGroup'] . '</p>';
		}//end if
	}//end if?>