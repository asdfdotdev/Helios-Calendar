<select name="locState" id="locState" class="eventInput">
	<option <?if(isset($state) && ($state=="AB")){echo "selected";}?> value="AB">Alberta</option>
	<option <?if(isset($state) && ($state=="BC")){echo "selected";}?> value="BC">British Columbia</option>
	<option <?if(isset($state) && ($state=="MB")){echo "selected";}?> value="MB">Manitoba</option>
	<option <?if(isset($state) && ($state=="NB")){echo "selected";}?> value="NB">New Brunswick</option>
	<option <?if(isset($state) && ($state=="NL")){echo "selected";}?> value="NL">Newfoundland and Labrador</option>
	<option <?if(isset($state) && ($state=="NT")){echo "selected";}?> value="NT">Northwest Territories</option>
	<option <?if(isset($state) && ($state=="NS")){echo "selected";}?> value="NS">Nova Scotia</option>
	<option <?if(isset($state) && ($state=="NU")){echo "selected";}?> value="NU">Nunavut</option>
	<option <?if(isset($state) && ($state=="ON")){echo "selected";}?> value="ON">Ontario</option>
	<option <?if(isset($state) && ($state=="PE")){echo "selected";}?> value="PE">Prince Edward Island</option>
	<option <?if(isset($state) && ($state=="QC")){echo "selected";}?> value="QC">Quebec</option>
	<option <?if(isset($state) && ($state=="SK")){echo "selected";}?> value="SK">Saskatchewan</option>
	<option <?if(isset($state) && ($state=="YT")){echo "selected";}?> value="YT">Yukon</option>
</select>