<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}?>
	
		<div class="mnu">
			<a href="<?php echo cal_url();?>/index.php?b=2" class="l">Today's Events</a>
			<a href="<?php echo cal_url();?>/index.php?b=0" class="c">This Week</a>
			<a href="<?php echo cal_url();?>/index.php?com=submit" class="r">Submit</a>
		</div>
		
<!--	"Connect with Us" links are just placeholders and do not actually link to anything.
		Add your own Facebook, Twitter, Google+ and LinkedIn profile links to your theme.	-->
		
		<h2>Connect with Us</h2>
		<a href="#" class="side fbs icon">Facebook</a>
		<a href="#" class="side tw icon">Twitter</a>
		<a href="#" class="side gg icon">Google+</a>
		<a href="#" class="side li icon">LinkedIn</a>

		<h2>Our Services</h2>
		<a href="<?php echo cal_url();?>/index.php?com=tools&t=3" class="side mb icon">Mobile</a>
		<a href="<?php echo cal_url();?>/index.php?com=newsletter" class="side nl icon">Newsletter</a>
		<a href="<?php echo cal_url();?>/index.php?com=tools&t=1" class="side rs icon">RSS</a>
		<a href="<?php echo cal_url();?>/link/ical.php" class="side ic icon">iCalendar</a>

		<h2>Calendar Settings</h2>
		<div class="setting">
			<span>Language:</span><?php select_language(0);?>

		</div>
		<div class="setting">
			<span>Theme:</span><?php select_theme(1);?>

		</div>