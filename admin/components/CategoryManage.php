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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/manage.php');
	
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = $_GET['cID'];
		$whereAmI = $hc_lang_manage['EditCategory'];
	} else {
		$cID = 0;
		$whereAmI = $hc_lang_manage['AddCategory'];
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_manage['Valid08'] . "\\n\\n          " . $hc_lang_manage['Valid09'] . "\\n          " . $hc_lang_manage['Valid10'];?>')){
			window.location.href='<?php echo CalAdminRoot . "/components/CategoryManageAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_manage['Valid11'];?>\n';
		
		if(document.frm.name.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_manage['Valid12'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_manage['Valid13'];?>');
			return false;
		}//end if
	}//end chkFrm()
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed03']);
				break;
				
			case "2" :
				feedback(1, $hc_lang_manage['Feed04']);
				break;
				
			case "3" :
				feedback(1, $hc_lang_manage['Feed05']);
				break;
				
		}//end switch
	}//end if
	
	$category = "";
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
	if(hasRows($result)){
		$category = mysql_result($result,0,1);
	}//end if
	
	appInstructions(0, "Category_Management", $hc_lang_manage['TitleCategory'], $hc_lang_manage['InstructCategory']);	?>
	<br />
	<div style="float:left;width:45%;padding:0px 15px 0px 0px;">
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/CategoryManageAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="cID" id="cID" value="<?php echo $cID;?>" />	
	<fieldset style="padding-top:5px;">
		<legend><?php echo $whereAmI;?><?php if($cID > 0){?>&nbsp;&nbsp;( <a href="<?php echo CalAdminRoot;?>/index.php?com=categorymanage" class="main"><?php echo $hc_lang_manage['AddNew'];?></a> )<?php }?></legend>
		<div class="frmReq">
			<label class="radio"><?php echo $hc_lang_manage['Name'];?></label>
			<input type="text" name="name" id="name" value="<?php echo $category;?>" />
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_manage['SaveCat'];?> " class="button" />
	</form>
	</div>
	<div style="float:left;width:50%;padding-top:15px;">
<?php 	$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 ORDER BY CategoryName");
		$rowCnt = mysql_num_rows($result);
		if(hasRows($result)){	
			$cnt = 0;
			$curCat = "";
			while($row = mysql_fetch_row($result)){
				if($curCat != $row[0]){	?>
				<div class="categoryListTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><?php if($cID == $row[0]){echo "<b>" . cOut($row[1]) . "</b>";} else {echo cOut($row[1]);}//end if?></div>
				<div class="categoryListTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=categorymanage&amp;cID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a><?php if($rowCnt > 1){?>&nbsp;<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a><?php }//end if?></div>
		<?php 	$curCat = $row[0];
				$cnt++;
				}//end if
				
			}//end while
		} else {
			echo "<br />";
			echo $hc_lang_manage['NoCategory'];
 		}//end if	?>
	</div>