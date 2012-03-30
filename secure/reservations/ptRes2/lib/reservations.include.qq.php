<?php ?>
<div id="quote_tabs_content">

<div>

    <form method="post" action="">

	<input type="hidden" name="fromID" />
	<input type="hidden" name="toID" />
	<input type="hidden" name="stopID" />
	<input type="hidden" name="meet_greet" value="0" />
	<input type="hidden" name="vehicle_type" value="P" />
	<input type="hidden" name="trip_type" value="P" />
        
        <input type="hidden" name="id" value="<?php echo !empty($values['id']) ? $values['id'] : uniqid() ?>" />

        <div class="step1"><!-- step1 -->
            <div class="group">
                <div style="display:none">
                    <ol id="quotes_steps" class="group">
                        <!-- These steps are links for the purposes of development use only.  Links should be removed in production code. -->
                        <li class="current">Where</li>
                        <li>Quote</li>
                    </ol>
                </div>

                <div style="width:100%;float:left;clear:both;margin: 5px 0 15px 0;display:none;" id="quote_contents">
                    <h2>Estimated flat-rate fare: $<span class="price"></span></h2>
                    <h3>Additional Information:</h3>
                    <ul style="list-style-type:disc">
                        <li>The quote is based on distance and does not include applicable wait time.</li>
                        <li>The quote does not include vehicle upgrade charges or charges for infant or booster seats. Fares include tolls. Airport fees are NOT included.</li>
                        <li>PlanetTran does not charge fuel surcharges.</li>
                        <li>Tips are neither expected nor included in our flat-rate pricing.</li>
                    </ul>
                </div>
                <!-- START LEFT COLUMN -->
                
                
                
                <fieldset id="pickup" class="half_column">

                    <legend>From</legend>
                    
                <div class="radio_buttons">
                    <a href="#" onclick="$('#quote_saved_locations_from, [name=apts_to]', $(this).parent().parent()).find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
                    <div style='float:right;'>
		              <input type="radio" name="quote_from_type" value="1" id="quote_from_address" class="quote_from_toggle" checked="checked" /><label for="from_address">Address</label>
		              <input type="radio" name="quote_from_type" value="2" id="quote_from_airport" class="quote_from_toggle" /><label for="from_airport">Airport</label>
		            </div>
                </div>
                
                <div id="from_address_wrap1" class="from_location_option1">
                    <select id="quote_saved_locations_from" name="from_location" class="saved_locations" style='margin-bottom: 10px;'>
                        <option value="">Saved locations</option>
                        <?php foreach(Account::getSavedLocations() as $location): if(strstr($location['machid'], 'airport') !== false) continue ?>
                        <option <?php if($location['machid'] == $values['from_location'] || $location['machid'] === $_GET['from']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                                 data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                                 data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                            <?php echo $location['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="saved_locations_from_wrap1">
                            <div class="row group">
                                <label for="from_street_address">Street Address</label><br />
                                <input name="from_address" type="text" id="quote_from_street_address" value="<?php echo $values['from_address'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="from_city">City</label><br />
                                <input name="from_city" type="text" id="quote_from_city" value="<?php echo $values['from_city'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="from_state">State</label><br />
                                <input name="from_state" type="text" id="quote_from_state" value="<?php echo $values['from_state'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="from_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
                                <input name="from_zip" type="text" id="quote_from_zipcode" value="<?php echo $values['from_zip'] ?>" />
                        </div><!-- /from_address -->
                    </div>
                </div>
                
                <div id="from_airport_wrap" class="from_location_option1">
			          <!-- Conditionally shown based on radio selection above -->
			          <div class="row group">
			              <select name="apts_from" style='width: 100%;'>
			                  <option value="">Select an airport</option>
			                  <?php echo get_airports_options($_REQUEST['apts_from'] ? $_REQUEST['apts_from'] : ($_REQUEST['from'] ? $_REQUEST['from'] : $values['from_location'])) ?>
			              </select>
			          </div>
			      </div><!-- /from_poi -->

                </fieldset>
                <!-- END LEFT COLUMN -->


                <!-- START RIGHT COLUMN -->
                <fieldset id="dropoff" class="half_column" style="float:right">

                    <legend>To</legend>

                <div class="radio_buttons">
                    <a href="#" onclick="$('#quote_saved_locations_to, [name=apts_to]', $(this).parent().parent()).find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
                    <div style='float:right;'>
		              <input type="radio" name="quote_to_type" value="1" id="quote_to_address" class="quote_to_toggle" checked="checked" /><label for="from_address">Address</label>
		              <input type="radio" name="quote_to_type" value="2" id="quote_to_airport" class="quote_to_toggle" /><label for="from_airport">Airport</label>
		            </div>
                </div>

				<div id="to_address_wrap1" class="to_location_option1">                
                    <select id="quote_saved_locations_to" name="to_location" class="saved_locations" style='margin-bottom: 10px;'>
                        <option value="">Saved locations</option>
                        <?php foreach(Account::getSavedLocations() as $location): if(strstr($location['machid'], 'airport') !== false) continue ?>
                        <option <?php if($location['machid'] == $values['to_location'] || $location['machid'] === $_GET['to']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                             data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                             data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                            <?php echo $location['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="saved_locations_to_wrap1">

                            <!-- Conditionally shown based on radio selection above -->
                            <div class="row group">
                                <label for="to_street_address">Street Address</label><br />
                                <input name="to_address" type="text" id="quote_to_street_address" value="<?php echo $values['to_address'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="to_city">City</label><br />
                                <input name="to_city" type="text" id="quote_to_city" value="<?php echo $values['to_city'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="to_state">State</label><br />
                                <input name="to_state" type="text" id="quote_to_state" value="<?php echo $values['to_state'] ?>" />
                            </div>
                            <div class="row group">
                                <label for="to_zipcode">Zip Code <span class="popover"><a href="#">(look up)</a></span></label><br />
                                <input name="to_zip" type="text" id="quote_to_zipcode" value="<?php echo $values['to_zip'] ?>" />
                            </div>
                    </div>
				</div>
				
				<div id="to_poi_wrap" class="to_location_option1">
		          <!-- Conditionally shown based on radio selection above -->
		          <div class="row group">
		              <select name="apts_to" style='width: 100%;'>
		                  <option value="">Select an airport</option>
		                  <?php echo get_airports_options($_REQUEST['apts_to'] ? $_REQUEST['apts_to']  : ($_REQUEST['to'] ? $_REQUEST['to'] : $values['to_location'])) ?>
		              </select>
		          </div>
		      </div><!-- /to_poi -->
                </fieldset>

            </div><!-- /group -->

            <div id="step_navigation" class="hr_top">
                <input type="button" value="Get a quote &raquo;" id="get_a_quote_button" class="button" />
            </div>
        </div><!-- /step1 -->
    </form>
</div>

<div>
<div style="display: none">
    <ol id="order_steps" class="group">
        <!-- These steps are links for the purposes of development use only.  Links should be removed in production code. -->
        <li class="current">When/Where</li>
        <li>Vehicle Selection</li>
        <li>Passenger Info</li>
        <li>Payment</li>
    </ol>
</div>

<!-- </div>
</div> -->
