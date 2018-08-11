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

	if(isset($_GET['nID']) && is_numeric($_GET['nID'])){
		$nID = cIn($_GET['nID']);
		$doEdit = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn($_GET['t']) : $hc_cfg30;
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . $nID . "' AND IsActive = 1");
		if(hasRows($result)){
			$name = mysql_result($result,0,1);
			$source = mysql_result($result,0,2);
			$helpText = $hc_lang_news['InstructEditNE'];
		} else {
			$nID = 0;
			$name = $source = '';
			$helpText = $hc_lang_news['InstrcutEditNA'];
		}//end if
		
		appInstructions(0, 'Newsletter_Templates', $hc_lang_news['TitleEditN'], $helpText);	?>
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
		warn = '<?php echo $hc_lang_news['Valid23'];?>';
			
			if(document.getElementById('tempname').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid24'];?>';
			}//end if

			if(<?php echo $hc_cfg30;?> == 1){
				if(tinyMCE.get('tempsource').getContent() == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_news['Valid25'];?>'
				}//end if
			} else {
				if(document.getElementById('tempsource').value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_news['Valid25'];?>';
				}//end if
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
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/MailTemplateEditAction.php";?>" onsubmit="return chkFrm();">
		<input type="hidden" name="nID" id="nID" value="<?php echo cOut($nID);?>" />
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['TempSettings'];?></legend>
			<div class="frmOpt">
				<label for="tempname"><?php echo $hc_lang_news['NameLabel'];?></label>
				<input name="tempname" id="tempname" type="text" size="40" maxlength="250" value="<?php echo cOut($name);?>" />
			</div>
		</fieldset>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['TempVariables'];?></legend>
			<div class="frmOpt">
				<label><?php echo $hc_lang_news['VariableLabel'];?></label>
				<a id="newsLink" href="javascript:;" onclick="togThis('tempVars', 'newsLink');" class="main"><?php echo $hc_lang_news['ShowVariables'];?></a>
			</div>
			<div class="frmOpt" id="tempVars" style="display:none;">
				<div class="varKey">
			<?php
				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelE'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [events]&lt;br /&gt;" . $hc_lang_news['Tip20B']);
				echo ' [events]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [billboard]&lt;br /&gt;" . $hc_lang_news['Tip21B']);
				echo ' [billboard]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [newest]&lt;br /&gt;" . $hc_lang_news['Tip22B']);
				echo ' [newest]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [popular]&lt;br /&gt;" . $hc_lang_news['Tip23B']);
				echo ' [popular]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [today]&lt;br /&gt;" . $hc_lang_news['Tip24B']);
				echo ' [today]</div>';
				
				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelM'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [message]&lt;br /&gt;" . $hc_lang_news['Tip25B']);
				echo ' [message]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [track]&lt;br /&gt;" . $hc_lang_news['Tip26B']);
				echo ' [track]</div>';

				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelS'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [firstname]&lt;br /&gt;" . $hc_lang_news['Tip27B']);
				echo ' [firstname]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [lastname]&lt;br /&gt;" . $hc_lang_news['Tip28B']);
				echo ' [lastname]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [email]&lt;br /&gt;" . $hc_lang_news['Tip29B']);
				echo ' [email]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [postal]&lt;br /&gt;" . $hc_lang_news['Tip30B']);
				echo ' [postal]</div>';

				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelSM'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [facebook]&lt;br /&gt;" . $hc_lang_news['Tip31B']);
				echo ' [facebook]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [twitter]&lt;br /&gt;" . $hc_lang_news['Tip32B']);
				echo ' [twitter]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [buzz]&lt;br /&gt;" . $hc_lang_news['Tip35B']);
				echo ' [buzz]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [follow]&lt;br /&gt;" . $hc_lang_news['Tip40B']);
				echo ' [follow]</div>';


				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelL'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [calendarurl]&lt;br /&gt;" . $hc_lang_news['Tip33B']);
				echo ' [calendarurl]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [editcancel]&lt;br /&gt;" . $hc_lang_news['Tip34B']);
				echo ' [editcancel]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [archive]&lt;br /&gt;" . $hc_lang_news['Tip36B']);
				echo ' [archive]</div>';
				/*
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [forward]&lt;br /&gt;" . $hc_lang_news['Tip37B']);
				echo ' [forward]</div>';
				*/
				echo '<div class="varKeyHeader">' . $hc_lang_news['VarLabelST'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [event-count]&lt;br /&gt;" . $hc_lang_news['Tip38B']);
				echo ' [event-count]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; [location-count]&lt;br /&gt;" . $hc_lang_news['Tip39B']);
				echo ' [location-count]</div>';?>
				<div style="clear:both;"></div>
				</div>
			</div>
		</fieldset>
		<br />
		<fieldset>
			<legend>
<?php		echo $hc_lang_news['TempContents'];
			if($hc_cfg30 == 1){
				echo ($doEdit == 0) ? ' (<a href="' . CalAdminRoot . '/index.php?com=mailtmplt&nID=' . $nID . '&t=1" class="main">' . $hc_lang_news['EnableEditor'] . '</a>)' : 
								' (<a href="' . CalAdminRoot . '/index.php?com=mailtmplt&nID=' . $nID . '&t=0" class="main">' . $hc_lang_news['DisableEditor'] . '</a>)';
			}//end if?>
			</legend>
			<div class="frmOpt">
<?php		$ovrEdit = ($doEdit == 0) ? 1 : 0;
			makeTinyMCE('tempsource', '100%', 'advanced', cOut($source), $ovrEdit);?>
			</div>
		</fieldset>
		<br />
		<input type="submit" name="submit" value=" <?php echo $hc_lang_news['SaveTemplate'];?> " class="button" />&nbsp;&nbsp;
		<input type="button" name="cancel" id="cancel" onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=mailtmplt';return false;" value="    <?php echo $hc_lang_news['Cancel'];?>    " class="button" />
		</form>
<?php
	} else {
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1, $hc_lang_news['Feed07']);
					break;
				case "2" :
					feedback(1, $hc_lang_news['Feed08']);
					break;
				case "3" :
					feedback(1, $hc_lang_news['Feed09']);
					break;
			}//end switch
		}//end if
		
		appInstructions(0, "Newsletter_Templates", $hc_lang_news['TitleEditN'], $hc_lang_news['InstructEditNL']);?>

		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_news['Valid20'] . "\\n\\n          " . $hc_lang_news['Valid21'] . "\\n          " . $hc_lang_news['Valid22'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/MailTemplateEditAction.php";?>?dID=' + dID;
			}//end if
		}//end doDelete

		function templatePreview(pID){
			window.open('<?php echo CalAdminRoot;?>/components/MailTemplatePreview.php?pID=' + pID,'hc_preview','location=1,status=1,scrollbars=1,width=800,height=600,left='+(screen.availWidth/2-400)+',top='+(screen.availHeight/2-300));
		}//end templatePreview()
		//-->
		</script>
<?php
		echo '<br /><a href="' . CalAdminRoot . '/index.php?com=mailtmplt&nID=0" class="icon"><img src="' . CalAdminRoot . '/images/icons/iconAdd.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />&nbsp;' . $hc_lang_news['NewTemplate'] . '</a><br />';
		echo '<div class="newsletterList">';
		echo '<div class="newsletterTitle">'. $hc_lang_news['TemplateName'] . '</div>';
		echo '<div class="newsletterCount">' . $hc_lang_news['TemplateMsg'] . '</div>';
		echo '&nbsp;</div>';

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 1 AND PkID ORDER BY TemplateName");
		if(hasRows($result)){
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				
				echo ($cnt % 2 == 1) ? '<div class="newsletterTitleHL">' : '<div class="newsletterTitle">';
				echo cOut($row[1]) . '</div>';

				echo ($cnt % 2 == 1) ? '<div class="newsletterCountHL">' : '<div class="newsletterCount">';
				echo (strpos($row[2],'[message]') === false) ? $hc_lang_news['No'] . '</div>' : $hc_lang_news['Yes'] . '</div>';

				echo ($cnt % 2 == 1) ? '<div class="newsletterToolsHL">' : '<div class="newsletterTools">';
				echo '<a href="javascript:;" onclick="templatePreview(\'' . $row[0] . '\');" class="main"><img src="' . CalAdminRoot . '/images/icons/iconView.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=mailtmplt&amp;nID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '&nbsp;<a href="javascript:;" onclick="javascript:doDelete(\'' . $row[0] . '\');" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '</div>';
				++$cnt;
			}//end while
		} else {
			echo '<p>' . $hc_lang_news['NoTemplates'] . '</p>';
		}//end if
	}//end if	?>