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
			<a href="http://helioscalendar.org">Powered by Helios Calendar <?php echo HCVersion;?></a>
		</p>
	</footer>
</body>
</html>