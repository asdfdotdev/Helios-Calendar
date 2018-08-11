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
?>
<select <?php if(isset($stateDisabled)){echo "disabled=\"disabled\"";}?> name="locState" id="locState">
	<option <?php if(isset($state) && ($state=="AL")){echo "selected=\"selected\"";}?> value="AL">ALABAMA</option>
	<option <?php if(isset($state) && ($state=="AK")){echo "selected=\"selected\"";}?> value="AK">ALASKA</option>
	<option <?php if(isset($state) && ($state=="AZ")){echo "selected=\"selected\"";}?> value="AZ">ARIZONA</option>
	<option <?php if(isset($state) && ($state=="AR")){echo "selected=\"selected\"";}?> value="AR">ARKANSAS</option>
	<option <?php if(isset($state) && ($state=="CA")){echo "selected=\"selected\"";}?> value="CA">CALIFORNIA</option>
	<option <?php if(isset($state) && ($state=="CO")){echo "selected=\"selected\"";}?> value="CO">COLORADO</option>
	<option <?php if(isset($state) && ($state=="CT")){echo "selected=\"selected\"";}?> value="CT">CONNECTICUT</option>
	<option <?php if(isset($state) && ($state=="DE")){echo "selected=\"selected\"";}?> value="DE">DELAWARE</option>
	<option <?php if(isset($state) && ($state=="DC")){echo "selected=\"selected\"";}?> value="DC">DIST. OF COLUMBIA</option>
	<option <?php if(isset($state) && ($state=="FL")){echo "selected=\"selected\"";}?> value="FL">FLORIDA</option>
	<option <?php if(isset($state) && ($state=="GA")){echo "selected=\"selected\"";}?> value="GA">GEORGIA</option>
	<option <?php if(isset($state) && ($state=="HI")){echo "selected=\"selected\"";}?> value="HI">HAWAII</option>
	<option <?php if(isset($state) && ($state=="ID")){echo "selected=\"selected\"";}?> value="ID">IDAHO</option>
	<option <?php if(isset($state) && ($state=="IL")){echo "selected=\"selected\"";}?> value="IL">ILLINOIS</option>
	<option <?php if(isset($state) && ($state=="IN")){echo "selected=\"selected\"";}?> value="IN">INDIANA</option>
	<option <?php if(isset($state) && ($state=="IA")){echo "selected=\"selected\"";}?> value="IA">IOWA</option>
	<option <?php if(isset($state) && ($state=="KS")){echo "selected=\"selected\"";}?> value="KS">KANSAS</option>
	<option <?php if(isset($state) && ($state=="KY")){echo "selected=\"selected\"";}?> value="KY">KENTUCKY</option>
	<option <?php if(isset($state) && ($state=="LA")){echo "selected=\"selected\"";}?> value="LA">LOUISIANA</option>
	<option <?php if(isset($state) && ($state=="ME")){echo "selected=\"selected\"";}?> value="ME">MAINE</option>
	<option <?php if(isset($state) && ($state=="MD")){echo "selected=\"selected\"";}?> value="MD">MARYLAND</option>
	<option <?php if(isset($state) && ($state=="MA")){echo "selected=\"selected\"";}?> value="MA">MASSACHUSETTS</option>
	<option <?php if(isset($state) && ($state=="MI")){echo "selected=\"selected\"";}?> value="MI">MICHIGAN</option>
	<option <?php if(isset($state) && ($state=="MN")){echo "selected=\"selected\"";}?> value="MN">MINNESOTA</option>
	<option <?php if(isset($state) && ($state=="MS")){echo "selected=\"selected\"";}?> value="MS">MISSISSIPPI</option>
	<option <?php if(isset($state) && ($state=="MO")){echo "selected=\"selected\"";}?> value="MO">MISSOURI</option>
	<option <?php if(isset($state) && ($state=="MT")){echo "selected=\"selected\"";}?> value="MT">MONTANA</option>
	<option <?php if(isset($state) && ($state=="NE")){echo "selected=\"selected\"";}?> value="NE">NEBRASKA</option>
	<option <?php if(isset($state) && ($state=="NV")){echo "selected=\"selected\"";}?> value="NV">NEVADA</option>
	<option <?php if(isset($state) && ($state=="NH")){echo "selected=\"selected\"";}?> value="NH">NEW HAMPSHIRE</option>
	<option <?php if(isset($state) && ($state=="NJ")){echo "selected=\"selected\"";}?> value="NJ">NEW JERSEY</option>
	<option <?php if(isset($state) && ($state=="NM")){echo "selected=\"selected\"";}?> value="NM">NEW MEXICO</option>
	<option <?php if(isset($state) && ($state=="NY")){echo "selected=\"selected\"";}?> value="NY">NEW YORK</option>
	<option <?php if(isset($state) && ($state=="NC")){echo "selected=\"selected\"";}?> value="NC">NORTH CAROLINA</option>
	<option <?php if(isset($state) && ($state=="ND")){echo "selected=\"selected\"";}?> value="ND">NORTH DAKOTA</option>
	<option <?php if(isset($state) && ($state=="OH")){echo "selected=\"selected\"";}?> value="OH">OHIO</option>
	<option <?php if(isset($state) && ($state=="OK")){echo "selected=\"selected\"";}?> value="OK">OKLAHOMA</option>
	<option <?php if(isset($state) && ($state=="OR")){echo "selected=\"selected\"";}?> value="OR">OREGON</option>
	<option <?php if(isset($state) && ($state=="PA")){echo "selected=\"selected\"";}?> value="PA">PENNSYLVANIA</option>
	<option <?php if(isset($state) && ($state=="RI")){echo "selected=\"selected\"";}?> value="RI">RHODE ISLAND</option>
	<option <?php if(isset($state) && ($state=="SC")){echo "selected=\"selected\"";}?> value="SC">SOUTH CAROLINA</option>
	<option <?php if(isset($state) && ($state=="SD")){echo "selected=\"selected\"";}?> value="SD">SOUTH DAKOTA</option>
	<option <?php if(isset($state) && ($state=="TN")){echo "selected=\"selected\"";}?> value="TN">TENNESSEE</option>
	<option <?php if(isset($state) && ($state=="TX")){echo "selected=\"selected\"";}?> value="TX">TEXAS</option>
	<option <?php if(isset($state) && ($state=="UT")){echo "selected=\"selected\"";}?> value="UT">UTAH</option>
	<option <?php if(isset($state) && ($state=="VT")){echo "selected=\"selected\"";}?> value="VT">VERMONT</option>
	<option <?php if(isset($state) && ($state=="VA")){echo "selected=\"selected\"";}?> value="VA">VIRGINIA</option>
	<option <?php if(isset($state) && ($state=="WA")){echo "selected=\"selected\"";}?> value="WA">WASHINGTON</option>
	<option <?php if(isset($state) && ($state=="WV")){echo "selected=\"selected\"";}?> value="WV">WEST VIRGINIA</option>
	<option <?php if(isset($state) && ($state=="WI")){echo "selected=\"selected\"";}?> value="WI">WISCONSIN</option>
	<option <?php if(isset($state) && ($state=="WY")){echo "selected=\"selected\"";}?> value="WY">WYOMING</option>
</select>