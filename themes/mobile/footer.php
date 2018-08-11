<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	global $hc_cfg;
?>
	<footer>
		<p>
			<a href="<?php echo cal_url().'/index.php?theme='.$hc_cfg[83];?>" style="float:right;margin-right:10%;">Desktop Theme</a>	
			<a href="http://www.helioscalendar.com" target="_blank">Helios Calendar <?php echo HCVersion;?></a><br />
			Copyright &copy; 2004&ndash;<?php echo date("Y");?> 
			<br /><br />
			A product of <a href="http://www.refreshmy.com" class="refresh" target="_blank">Refresh Web Development</a>
		</p>
	</footer>
</body>
</html>