<?php
$hc_lang_oiduser = array(

//	OpenID User Browse
'TitleBrowse'		=>	'OpenID User Management',
'InstructBrowse'	=>	'The list below contains all OpenID identities that have logged into your public calendar.<br /><br /><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Edit User Account (Edit Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconUserDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Delete User (Delete\'s All Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconUserBan.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Ban User (Permanently Blocks Login, Delete\'s All Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconUserUnban.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Unban User (Does NOT Restore Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = View Comments Posted by User',

//	OpenID User Edit
'TitleEdit'			=>	'OpenID User Edit',
'InstructEdit'		=>	'Manage this user, view/edit their account details, statistics and created content.<br /><br /><img src="' . CalAdminRoot . '/images/icons/iconUserDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Delete User (Delete\'s All Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconUserBan.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Ban User (Permanently Blocks Login, Delete\'s All Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconUserUnban.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Unban User (Does NOT Restore Content Created by User)<br /><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = View Comments Posted by User',

'PerPage'			=>	'Show Per Page:',
'Page'				=>	'Page:',
'Show'				=>	'Show:',
'ActiveUsers'		=>	'Active Users',
'BannedUsers'		=>	'Banned Users',
'IdentityLabel'		=>	'Display Name',
'LastLoginLabel'	=>	'Last Login',
'ManageUser'		=>	'Manage User:',
'AccountDetails'	=>	'Account Details',
'Identity'			=>	'Identity:',
'DisplayName'		=>	'Display Name:',
'LoginCnt'			=>	'Login Count:',
'FirstLogin'		=>	'First Login:',
'LastLogin'			=>	'Last Login:',
'LastIP'			=>	'Last Login From:',
'CommentStats'		=>	'User Comments &amp; Recomnds Statistics',
'Comments'			=>	'Comments:',
'TotalRecomnds'		=>	'Tot. Recomnds:',
'OthersFor'			=>	'Others For:',
'OthersAgainst'		=>	'Others Against:',
'ForOthers'			=>	'For Others:',
'AgainstOthers'		=>	'Against Others:',
'NoUsers'			=>	'No Users Available',
'InvalidUser'		=>	'You are attempting to edit an invalid OpenID user.',
'ValidLink'			=>	'Click here to browser OpenID Users.',

//	Buttons
'Cancel'			=>	'Cancel',

//	Validation
'Valid01'			=>	'Deleted Users can still login to your public calendar.\nTo permanently block this users access to your site ban them.\n\nAre you sure you want to delete this user and their created content?',
'Valid02'			=>	'Ok = YES Delete User and Their Content',
'Valid03'			=>	'Cancel = NO Do Not Delete User',
'Valid04'			=>	'OpenID User Ban prevents future logins!\nBanning this user will permanently delete all their created content\n\nAre you sure you want to ban this user and delete their content?',
'Valid05'			=>	'Ok = YES Ban User and Delete Their Content',
'Valid06'			=>	'Cancel = NO Do Not Ban User',
'Valid07'			=>	'This will unban the User and restore their privilege to access the public calendar.\n\nAre you sure you want to UNBAN this user?',
'Valid08'			=>	'Ok = YES UNBAN User',
'Valid09'			=>	'Cancel = NO Maintain User Ban',

//	Feedback
'Feed01'			=>	'OpenID account and all user created content deleted successfully.',
'Feed02'			=>	'OpenID account BANNED and all user created content deleted successfully.',
'Feed03'			=>	'OpenID account unbanned, user log in privilege restored.',

//	Tooltips
'Tip01A'			=>	'Total Recomnds',
'Tip01B'			=>	'The sum score of all Recomnds submitted for comments posted by this user. This statistic provides a general overview of the quality of submissions typically made by this user.<br /><br />A <b>higher positive score</b> is a good indication this user posts quality comments, appreciated by other users.<br /><br />A <b>lower negative score</b> is a good indication this user posts irrelevant or otherwise inappropriate comments, not appreciated by other users.',
'Tip02A'			=>	'Others For',
'Tip02B'			=>	'The number of <b>positive Recomnds</b> submitted by other users for comments posted by this user.',
'Tip03A'			=>	'Others Against',
'Tip03B'			=>	'The number of <b>negative Recomnds</b> submitted by other users for comments posted by this user.',
'Tip04A'			=>	'For Others',
'Tip04B'			=>	'The number of <b>positive Recomnds</b> submitted by this user for comments posted by other users.',
'Tip05A'			=>	'Against Others',
'Tip05B'			=>	'The number of <b>negative Recomnds</b> submitted by this user for comments posted by other users.',
);	?>