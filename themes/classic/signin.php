<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
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
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>