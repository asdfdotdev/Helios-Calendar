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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/menu.php');	?>
<div id="menu">
	<ul>
		<li><a href="<?php echo CalRoot;?>/index.php" class="eventMenu"><?php echo $hc_lang_menu['Events'];?></a></li>
<?php 	if($hc_LocBrowse == 1 && isset($hc_googleKey) && $hc_googleKey != ''){	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=location" class="eventMenu"><?php echo $hc_lang_menu['Locations'];?></a></li>
<?php 	}//end if
		if($hc_pubSubmit == 1){	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=submit" class="eventMenu"><?php echo $hc_lang_menu['Submit'];?></a></li>
<?php 	}//end if	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=search" class="eventMenu"><?php echo $hc_lang_menu['Search'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=signup" class="eventMenu"><?php echo $hc_lang_menu['Newsletter'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=tools" class="eventMenu"><?php echo $hc_lang_menu['Tools'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMenu"><?php echo $hc_lang_menu['RSS'];?></a></li>
	</ul>
</div>