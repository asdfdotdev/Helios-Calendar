<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body>
	<?php my_menu(0);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=signin">Sign In</a></li>
		</ul>
	</nav>
	
	<section>
		<fieldset id="signin">
			<legend>Select Your Account to Sign In</legend>
		<?php 
			twitter_signin_button();
			facebook_signin_button();
			google_signin_button();?>
			
		</fieldset>
			
		<p>
			Event submissions will be associated with the account you sign in with.<br />Sign in with that account again to update your events.
		</p>
	</section>
		
	<?php get_footer(); ?>