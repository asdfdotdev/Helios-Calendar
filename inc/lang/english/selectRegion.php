<?php
	$state = (isset($state)) ? $state : '';
?>
<select <?php if(isset($stateDisabled)){echo 'disabled="disabled"';}?> name="locState" id="locState">
	<option value=""><?php echo (isset($regSelect)) ? $regSelect : '';?></option>
	<option <?php echo ($state=="AL") ? 'selected="selected"' : '';?> value="AL">Alabama</option>
	<option <?php echo ($state=="AK") ? 'selected="selected"' : '';?> value="AK">Alaska</option>
	<option <?php echo ($state=="AZ") ? 'selected="selected"' : '';?> value="AZ">Arizona</option>
	<option <?php echo ($state=="AR") ? 'selected="selected"' : '';?> value="AR">Arkansas</option>
	<option <?php echo ($state=="CA") ? 'selected="selected"' : '';?> value="CA">California</option>
	<option <?php echo ($state=="CO") ? 'selected="selected"' : '';?> value="CO">Colorado</option>
	<option <?php echo ($state=="CT") ? 'selected="selected"' : '';?> value="CT">Connecticut</option>
	<option <?php echo ($state=="DE") ? 'selected="selected"' : '';?> value="DE">Delaware</option>
	<option <?php echo ($state=="DC") ? 'selected="selected"' : '';?> value="DC">Dist. Of Columbia</option>
	<option <?php echo ($state=="FL") ? 'selected="selected"' : '';?> value="FL">Florida</option>
	<option <?php echo ($state=="GA") ? 'selected="selected"' : '';?> value="GA">Georgia</option>
	<option <?php echo ($state=="HI") ? 'selected="selected"' : '';?> value="HI">Hawaii</option>
	<option <?php echo ($state=="ID") ? 'selected="selected"' : '';?> value="ID">Idaho</option>
	<option <?php echo ($state=="IL") ? 'selected="selected"' : '';?> value="IL">Illinois</option>
	<option <?php echo ($state=="IN") ? 'selected="selected"' : '';?> value="IN">Indiana</option>
	<option <?php echo ($state=="IA") ? 'selected="selected"' : '';?> value="IA">Iowa</option>
	<option <?php echo ($state=="KS") ? 'selected="selected"' : '';?> value="KS">Kansas</option>
	<option <?php echo ($state=="KY") ? 'selected="selected"' : '';?> value="KY">Kentucky</option>
	<option <?php echo ($state=="LA") ? 'selected="selected"' : '';?> value="LA">Louisiana</option>
	<option <?php echo ($state=="ME") ? 'selected="selected"' : '';?> value="ME">Maine</option>
	<option <?php echo ($state=="MD") ? 'selected="selected"' : '';?> value="MD">Maryland</option>
	<option <?php echo ($state=="MA") ? 'selected="selected"' : '';?> value="MA">Massachusetts</option>
	<option <?php echo ($state=="MI") ? 'selected="selected"' : '';?> value="MI">Michigan</option>
	<option <?php echo ($state=="MN") ? 'selected="selected"' : '';?> value="MN">Minnesota</option>
	<option <?php echo ($state=="MS") ? 'selected="selected"' : '';?> value="MS">Mississippi</option>
	<option <?php echo ($state=="MO") ? 'selected="selected"' : '';?> value="MO">Missouri</option>
	<option <?php echo ($state=="MT") ? 'selected="selected"' : '';?> value="MT">Montana</option>
	<option <?php echo ($state=="NE") ? 'selected="selected"' : '';?> value="NE">Nebraska</option>
	<option <?php echo ($state=="NV") ? 'selected="selected"' : '';?> value="NV">Nevada</option>
	<option <?php echo ($state=="NH") ? 'selected="selected"' : '';?> value="NH">New Hampshire</option>
	<option <?php echo ($state=="NJ") ? 'selected="selected"' : '';?> value="NJ">New Jersey</option>
	<option <?php echo ($state=="NM") ? 'selected="selected"' : '';?> value="NM">New Mexico</option>
	<option <?php echo ($state=="NY") ? 'selected="selected"' : '';?> value="NY">New York</option>
	<option <?php echo ($state=="NC") ? 'selected="selected"' : '';?> value="NC">North Carolina</option>
	<option <?php echo ($state=="ND") ? 'selected="selected"' : '';?> value="ND">North Dakota</option>
	<option <?php echo ($state=="OH") ? 'selected="selected"' : '';?> value="OH">Ohio</option>
	<option <?php echo ($state=="OK") ? 'selected="selected"' : '';?> value="OK">Oklahoma</option>
	<option <?php echo ($state=="OR") ? 'selected="selected"' : '';?> value="OR">Oregon</option>
	<option <?php echo ($state=="PA") ? 'selected="selected"' : '';?> value="PA">Pennsylvania</option>
	<option <?php echo ($state=="RI") ? 'selected="selected"' : '';?> value="RI">Rhode Island</option>
	<option <?php echo ($state=="SC") ? 'selected="selected"' : '';?> value="SC">South Carolina</option>
	<option <?php echo ($state=="SD") ? 'selected="selected"' : '';?> value="SD">South Dakota</option>
	<option <?php echo ($state=="TN") ? 'selected="selected"' : '';?> value="TN">Tennessee</option>
	<option <?php echo ($state=="TX") ? 'selected="selected"' : '';?> value="TX">Texas</option>
	<option <?php echo ($state=="UT") ? 'selected="selected"' : '';?> value="UT">Utah</option>
	<option <?php echo ($state=="VT") ? 'selected="selected"' : '';?> value="VT">Vermont</option>
	<option <?php echo ($state=="VA") ? 'selected="selected"' : '';?> value="VA">Virginia</option>
	<option <?php echo ($state=="WA") ? 'selected="selected"' : '';?> value="WA">Washington</option>
	<option <?php echo ($state=="WV") ? 'selected="selected"' : '';?> value="WV">West Virginia</option>
	<option <?php echo ($state=="WI") ? 'selected="selected"' : '';?> value="WI">Wisconsin</option>
	<option <?php echo ($state=="WY") ? 'selected="selected"' : '';?> value="WY">Wyoming</option>
</select>