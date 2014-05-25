<select name="occupation" id="occupation">
	<option <?php if(isset($occupation) && ($occupation==0)){echo "selected=\"selected\"";}?> value="0">[Select an Occupation]</option>
	<option <?php if(isset($occupation) && ($occupation==1)){echo "selected=\"selected\"";}?> value="1">Accounting/Financial</option>
	<option <?php if(isset($occupation) && ($occupation==2)){echo "selected=\"selected\"";}?> value="3">Computer Related (internet)</option>
	<option <?php if(isset($occupation) && ($occupation==3)){echo "selected=\"selected\"";}?> value="2">Computer Related (other)</option>
	<option <?php if(isset($occupation) && ($occupation==4)){echo "selected=\"selected\"";}?> value="4">Consulting</option>
	<option <?php if(isset($occupation) && ($occupation==5)){echo "selected=\"selected\"";}?> value="5">Customer Service/Support</option>
	<option <?php if(isset($occupation) && ($occupation==6)){echo "selected=\"selected\"";}?> value="6">Education/Training</option>
	<option <?php if(isset($occupation) && ($occupation==7)){echo "selected=\"selected\"";}?> value="7">Engineering</option>
	<option <?php if(isset($occupation) && ($occupation==8)){echo "selected=\"selected\"";}?> value="8">Executive/Senior Management</option>
	<option <?php if(isset($occupation) && ($occupation==9)){echo "selected=\"selected\"";}?> value="9">General Administrative/Supervisor</option>
	<option <?php if(isset($occupation) && ($occupation==10)){echo "selected=\"selected\"";}?> value="10">Government/Military</option>
	<option <?php if(isset($occupation) && ($occupation==11)){echo "selected=\"selected\"";}?> value="11">Homemaker</option>
	<option <?php if(isset($occupation) && ($occupation==12)){echo "selected=\"selected\"";}?> value="12">Manufacturing/Production/Operations</option>
	<option <?php if(isset($occupation) && ($occupation==21)){echo "selected=\"selected\"";}?> value="21">Other</option>
	<option <?php if(isset($occupation) && ($occupation==13)){echo "selected=\"selected\"";}?> value="13">Professional (Medical. Legal. Etc)</option>
	<option <?php if(isset($occupation) && ($occupation==14)){echo "selected=\"selected\"";}?> value="14">Research and Development</option>
	<option <?php if(isset($occupation) && ($occupation==15)){echo "selected=\"selected\"";}?> value="15">Retired</option>
	<option <?php if(isset($occupation) && ($occupation==16)){echo "selected=\"selected\"";}?> value="16">Sales/Marketing/Advertising</option>
	<option <?php if(isset($occupation) && ($occupation==17)){echo "selected=\"selected\"";}?> value="17">Self-Employed/Owner</option>
	<option <?php if(isset($occupation) && ($occupation==18)){echo "selected=\"selected\"";}?> value="18">Student</option>
	<option <?php if(isset($occupation) && ($occupation==19)){echo "selected=\"selected\"";}?> value="19">Tradesman/Craftsman</option>
	<option <?php if(isset($occupation) && ($occupation==20)){echo "selected=\"selected\"";}?> value="20">Unemployed/Between Jobs</option>
</select>