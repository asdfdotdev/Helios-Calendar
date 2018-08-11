<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	if(isset($_GET['nID']) && is_numeric($_GET['nID'])){
		$nID = (isset($_GET['nID']) && is_numeric($_GET['nID'])) ? cIn(strip_tags($_GET['nID'])) : 0;
		$doEdit = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(strip_tags($_GET['t'])) : $hc_cfg[30];
		$ovrEdit = ($doEdit == 0) ? 1 : 0;
		$name = $source = '';
		$helpText = $hc_lang_news['InstrcutEditNA'];
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . $nID . "' AND IsActive = 1");
		if(hasRows($result)){
			$name = cOut(mysql_result($result,0,1));
			$source = cOut(mysql_result($result,0,2));
			$helpText = $hc_lang_news['InstructEditNE'];}
		
		appInstructions(0, 'Newsletter_Templates', $hc_lang_news['TitleEditN'], $helpText);
		
		echo '
	<form name="frmMailTemplate" id="frmMailTemplate" method="post" action="'.AdminRoot.'/components/MailTemplateEditAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="nID" id="nID" value="'.cOut($nID).'" />
	<fieldset>
		<legend>'.$hc_lang_news['TempSettings'].'</legend>
		<label for="tempname">'.$hc_lang_news['NameLabel'].'</label>
		<input name="tempname" id="tempname" type="text" size="40" maxlength="250" required="required" value="'.$name.'" />
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_news['TempVariables'].'</legend>
		<label>'.$hc_lang_news['VariableLabel'].'</label>
		<span class="output">
			<a id="tempLink" href="javascript:;" onclick="togVar(\'tempVars\', \'tempLink\');">'.$hc_lang_news['ShowVariables'].'</a>
		</span>
		<div id="tempVars" style="display:none;">
			<h5>'.$hc_lang_news['VarLabelE'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[events] - '.$hc_lang_news['Tip20'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[events]</span>
				<span><a class="tooltip" data-tip="[billboard] - '.$hc_lang_news['Tip21'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[billboard]</span>
				<span><a class="tooltip" data-tip="[newest] - '.$hc_lang_news['Tip22'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[newest]</span>
				<span><a class="tooltip" data-tip="[updated] - '.$hc_lang_news['Tip33'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[updated]</span>
			</p>
			<p>
				<span><a class="tooltip" data-tip="[popular] - '.$hc_lang_news['Tip23'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[popular]</span>
				<span><a class="tooltip" data-tip="[today] - '.$hc_lang_news['Tip24'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[today]</span>
			</p>
			<h5>'.$hc_lang_news['VarLabelM'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[message] - '.$hc_lang_news['Tip25'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[message]</span>
				<span><a class="tooltip" data-tip="[track] - '.$hc_lang_news['Tip26'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[track]</span>
			</p>
			<h5>'.$hc_lang_news['VarLabelS'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[firstname] - '.$hc_lang_news['Tip27'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[firstname]</span>
				<span><a class="tooltip" data-tip="[lastname] - '.$hc_lang_news['Tip28'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[lastname]</span>
				<span><a class="tooltip" data-tip="[email] - '.$hc_lang_news['Tip29'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[email]</span>
				<span><a class="tooltip" data-tip="[postal] - '.$hc_lang_news['Tip30'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[postal]</span>
			</p>
			<h5>'.$hc_lang_news['VarLabelSM'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[facebook] - '.$hc_lang_news['Tip31'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[facebook]</span>
				<span><a class="tooltip" data-tip="[twitter] - '.$hc_lang_news['Tip32'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[twitter]</span>
				<span><a class="tooltip" data-tip="[follow] - '.$hc_lang_news['Tip40'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[follow]</span>
			</p>
			<h5>'.$hc_lang_news['VarLabelL'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[calendarurl] - '.$hc_lang_news['Tip34'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[calendarurl]</span>
				<span><a class="tooltip" data-tip="[editcancel] - '.$hc_lang_news['Tip35'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[editcancel]</span>
				<span><a class="tooltip" data-tip="[archive] - '.$hc_lang_news['Tip36'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[archive]</span>
			</p>
			<h5>'.$hc_lang_news['VarLabelST'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[event-count] - '.$hc_lang_news['Tip38'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event-count]</span>
				<span><a class="tooltip" data-tip="[location-count] - '.$hc_lang_news['Tip39'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[location-count]</span>
			</p>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_news['TempContents'];
		
		if($hc_cfg[30] == 1)
			echo ($doEdit == 0) ? ' (<a href="' . AdminRoot . '/index.php?com=mailtmplt&nID=' . $nID . '&t=1">' . $hc_lang_news['EnableEditor'] . '</a>)' : 
							' (<a href="' . AdminRoot . '/index.php?com=mailtmplt&nID=' . $nID . '&t=0">' . $hc_lang_news['DisableEditor'] . '</a>)';
	echo '
		</legend>
		<textarea name="tempsource" id="tempsource" rows="30" class="mce_edit">'.$source.'</textarea>
	</fieldset>
	<input type="submit" name="submit" value=" '.$hc_lang_news['SaveTemplate'].' " />
	<input type="button" name="cancel" id="cancel" onclick="window.location.href=\''.AdminRoot.'/index.php?com=mailtmplt\';return false;" value="'.$hc_lang_news['Cancel'].'" />
	</form>
	
	<script>
	//<!--
	function togVar(doTog, doLink){if(document.getElementById("tempVars").style.display == "none"){document.getElementById("tempVars").style.display = "block";document.getElementById("tempLink").innerHTML = "'.$hc_lang_news['HideVariables'].'";} else {document.getElementById("tempVars").style.display = "none";document.getElementById("tempLink").innerHTML = "'.$hc_lang_news['ShowVariables'].'";}}
	
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("tempname"),"'.$hc_lang_news['Valid24'].'\n");

		try{
			err +=chkTinyMCE(tinyMCE.get("tempsource").getContent(),"'.$hc_lang_news['Valid25'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("tempsource"),"'.$hc_lang_news['Valid25'].'\n");}

		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	//-->
	</script>';
	
	makeTinyMCE('99%',1,$ovrEdit,'tempsource');
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
			}
		}
		
		appInstructions(0, "Newsletter_Templates", $hc_lang_news['TitleEditN'], $hc_lang_news['InstructEditNL']);

		echo '
	<a href="'.AdminRoot.'/index.php?com=mailtmplt&nID=0" class="add"><img src="'.AdminRoot.'/img/icons/add.png" width="16" height="16" alt="" />' . $hc_lang_news['NewTemplate'] . '</a>';

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 1 AND PkID ORDER BY TemplateName");
		if(hasRows($result)){
			$cnt = 0;
			echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:55%;">'.$hc_lang_news['TemplateName'].'</div>
			<div style="width:30%;">'.$hc_lang_news['TemplateMsg'].'</div>
			<div style="width:15%;">&nbsp;</div>
		</li>';
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				
				echo '
		<li class="row'.$hl.'">
			<div style="width:55%;">'.cOut($row[1]).'</div>
			<div style="width:30%;">'.((strpos($row[2],'[message]') === false) ? $hc_lang_news['No'] : $hc_lang_news['Yes']).'</div>
			<div class="tools" style="width:12%;">
				<a href="javascript:;" onclick="templatePreview(\'' . $row[0] . '\');"><img src="' . AdminRoot . '/img/icons/view.png" width="16" height="16" alt="" /></a>
				<a href="' . AdminRoot . '/index.php?com=mailtmplt&amp;nID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
				<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
			</div>
		</li>';
				++$cnt;
			}
			echo '
	</ul>
	
	<script>
	//<!--
	function doDelete(dID){
		if(confirm("'.$hc_lang_news['Valid20'].'\\n\\n          '.$hc_lang_news['Valid21'].'\\n          '.$hc_lang_news['Valid22'].'"))
			document.location.href = "'.AdminRoot.'/components/MailTemplateEditAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
	}
	function templatePreview(pID){
		window.open("'.AdminRoot.'/components/MailTemplatePreview.php?pID=" + pID,"hc_preview","location=1,status=1,scrollbars=1,width=800,height=600,left="+(screen.availWidth/2-400)+",top="+(screen.availHeight/2-300));
	}
	//-->
	</script>';
		} else {
			echo '<p>' . $hc_lang_news['NoTemplates'] . '</p>';
		}
	}
?>