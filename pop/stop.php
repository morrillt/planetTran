<div class="popover_content">
	<form id="add_stop">
		<fieldset>
			<!-- <legend>Enter address of intermediate stop</legend> -->
			
			<div class="radio_buttons">
				<input type="radio" name="location_type" id="add_address" value="address" checked="checked" /><label for="add_address">Address</label>
				<input type="radio" name="location_type" id="add_airport" value="airport" /><label for="add_airport">Airport</label>
				<input type="radio" name="location_type" id="add_poi" value="POI" /><label for="add_poi">Point of Interest</label>
			</div>
			
			<div class="row">
				<label for="street_address">Street Address</label>
				<input type="text" class="text" id="street_address" />
			</div>
			<div class="row">						
				<label for="city_state">City, State</label>
				<input type="text" class="text" id="city_state" />
			</div>
			<div class="row">
				<label for="zip_code">Zip Code <a href="#" target="zip" class="popup">(look up)</a></label>
				<input type="text" class="text" id="zip_code" />
			</div>
			<input type="button" value="Add stop" class="spacious_top" />
		</fieldset>
		<em>Note: An intermediate stop adds $15 plus any wait time over 10 minutes at your intermediate stop to the cost of the trip. Reserve by the Hour to make more than one Intermediate Stop.</em>
	</form>
</div>