<?php ?>
<div id="quote_tabs_content">

<div>

    <form method="post" action="">

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
                    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
                    <select id="quote_saved_locations_from" name="from_location" class="saved_locations">
                        <option value="">Saved locations</option>
                        <?php foreach(Account::getSavedLocations() as $location): ?>
                        <option <?php if($location['machid'] == $values['from_location'] || $location['machid'] === $_GET['from']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                                 data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                                 data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                            <?php echo $location['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="saved_locations_from_wrap1">

                        <div id="from_address_wrap1" class="from_location_option1">
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
                            </div>
                        </div><!-- /from_address -->

                    </div>

                </fieldset>
                <!-- END LEFT COLUMN -->


                <!-- START RIGHT COLUMN -->
                <fieldset id="dropoff" class="half_column">

                    <legend>To</legend>

                    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
                    <select id="quote_saved_locations_to" name="to_location" class="saved_locations">
                        <option value="">Saved locations</option>
                        <?php foreach(Account::getSavedLocations() as $location): ?>
                        <option <?php if($location['machid'] == $values['to_location'] || $location['machid'] === $_GET['to']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                             data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                             data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                            <?php echo $location['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="saved_locations_to_wrap1">

                        <div id="to_address_wrap1" class="to_location_option1">
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
                        </div><!-- /to_address -->
                    </div>

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

<form name="reserve" id="steps_main" class="group" method="post" action="">

<input type="hidden" name="id" value="<?php echo !empty($values['id']) ? $values['id'] : uniqid() ?>" />

<div class="step1"><!-- step1 -->
<fieldset class="group hr pickup_time">
    <?php if($_POST && !strtotime($_POST['date'])): ?>
    <span style="color:#f00;">You have to choose a date!</span>
    <?php endif ?>
    <legend>Pickup time</legend>
    <label for="reservation_date">Date</label>
    <input name="date" id="reservation_date" type="text" class="date_input" value="<?php echo $values['date'] ?>" />
    <label for="time_hour">Time</label>
    <select name="start_hour" id="time_hour" class="time_picker">
        <?php foreach(range(12, 1) as $v): ?>
        <?php /*<option <?php if($v == date('h', $this->date)) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>*/ ?>
        <option <?php if($v == $values['start_hour']) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>
        <?php endforeach; ?>
    </select>
    :
    <select name="start_minutes" id="time_minutes" class="time_picker">
        <?php foreach(range(0, 55, 5) as $v): ?>
        <?php /*<option <?php if($v == date('i', $this->date)) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>*/ ?>
        <option <?php if($v == $values['start_minutes']) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="ampm" id="time_am_pm" class="time_picker">
        <?php /*<option value="AM" <?php if('am' == date('a', $this->date)) echo 'selected="selected"' ?>>AM</option>
	    <option value="PM" <?php if('pm' == date('a', $this->date)) echo 'selected="selected"' ?>>PM</option>*/ ?>
        <option value="am" <?php if('am' == $values['ampm']) echo 'selected="selected"' ?>>AM</option>
        <option value="pm" <?php if('pm' == $values['ampm']) echo 'selected="selected"' ?>>PM</option>
    </select>
    <label for="check_by_the_hour">
        <input name="wait" type="checkbox" <?php if($values['wait']) echo 'checked="checked"' ?> id="check_by_the_hour" />Book by the hour <span id="tip1" class="tip">(?)</span>
    </label>
    <div class="tooltip tip1">
        <p>Reserve by the hour (minimum of 90 minutes) to direct your driver for a period of time or to more than one intermediate stop. These trips are billed at the following rates:</p>
        <p>
            <strong>Prius:</strong> $65 per hour<br />
            <strong>Prius V:</strong> $70 per hour<br />
            <strong>Camry:</strong> $70 per hour<br />
            <strong>Highlander:</strong> $75 per hour<br />
            <strong>Lexus Sedan:</strong> $75 per hour (Massachusetts only)
        </p>
        <p>If the passenger is running late, PlanetTran will wait the entire trip time before creating a no-show billing for the reserved trip.</p>
    </div>
</fieldset>

<div class="group">

<!-- START LEFT COLUMN -->
<fieldset id="pickup" class="half_column">

    <legend>From</legend>

    <div class="radio_buttons">
        <a href="#" onclick="$('#saved_locations_from, [name=apts_from]', $(this).parent().parent()).find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>

        <?php $fromApt = ($_REQUEST['from_type'] == 2 && $_REQUEST['apts_from']) || strpos($_REQUEST['from'], 'airport') !== false || strpos($values['from_location'], 'airport') !== false || 'from_airport_wrap' == $values['from_type'] ?>
        <div style='float:right;'>
            <input type="radio" name="from_type" <?php if(!$fromApt) echo 'checked="checked"' ?> value="1" id="from_address" class="from_toggle" /><label for="from_address">Address</label>
            <input type="radio" name="from_type" <?php if( $fromApt) echo 'checked="checked"' ?> value="2" id="from_airport" class="from_toggle" /><label for="from_airport">Airport</label>
        </div>
        <?php /*<input type="radio" name="from_type" <?php if($values['from_type'] == 2) echo 'checked="checked"' ?> value="2" id="from_poi" class="from_toggle" /><label for="from_poi">Point of Interest</label> */ ?>
    </div>

    <!-- Conditionally shown instead of select tag above if logged out
           <a href="#">Log in to view saved locations</a>
         -->

    <div id="from_address_wrap" class="from_location_option">

        <select id="saved_locations_from" name="from_location" class="saved_locations">
            <option value="">Saved locations</option>
            <?php foreach(Account::getSavedLocations() as $location): if(strstr($location['machid'], 'airport') !== false) continue ?>
            <option <?php if($location['machid'] == $values['from_location'] || $location['machid'] === $_GET['from']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                     data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                     data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                <?php echo $location['name'] ?></option>
            <?php endforeach; ?>
        </select><br/><br/>

        <div id="saved_locations_from_wrap">
            <!-- Conditionally and dynamically show this based on radio selection above -->
            <div class="row group">
                <label for="from_name">Nickname</label><br />
                <input name="from_name" type="text" id="from_name" value="<?php echo $values['from_name'] ?>" />
            </div>
            <div class="row group">
                <label for="from_street_address">Street Address</label><br />
                <input name="from_address" type="text" id="from_street_address" value="<?php echo $values['from_address'] ?>" />
            </div>
            <div class="row group">
                <label for="from_city">City</label><br />
                <input name="from_city" type="text" id="from_city" value="<?php echo $values['from_city'] ?>" />
            </div>
            <div class="row group">
                <label for="from_state">State</label><br />
                <input name="from_state" type="text" id="from_state" value="<?php echo $values['from_state'] ?>" />
            </div>
            <div class="row group">
                <label for="from_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
                <input name="from_zip" type="text" id="from_zipcode" value="<?php echo $values['from_zip'] ?>" />
            </div>
        </div><!-- /from_address -->
    </div>

    <div id="from_airport_wrap" class="from_location_option">
        <!-- Conditionally shown based on radio selection above -->
        <div class="row group">
            <select name="apts_from" style='width: 100%;'>
                <option value="">Select an airport</option>
                <?php echo get_airports_options($_REQUEST['apts_from'] ? $_REQUEST['apts_from'] : ($_REQUEST['from'] ? $_REQUEST['from'] : $values['from_location'])) ?>
            </select>
        </div>
        <div class="row group">
            <select name="acode_from" style='width: 100%;'>
                <option value="">Select an airline</option>
                <?php foreach(Account::getAirlines() as $key => $v): ?>
                <option value="<?php echo $key ?>" <?php if($key == $values['acode_from']) echo 'selected="selected"' ?>><?php echo $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
	      <span id="flight_details">
		<div class="row  group">
            <label for="fnum">Flight #</label>
            <input name="fnum_from" value="<?php echo $values['fnum_from'] ?>" type="text" id="fnum" class="flight_no" />
        </div>
		<div class="row  group">
            <label for="fdets">Time/Other details</label>
            <input name="fdets_from" type="text" value="<?php echo $values['fdets_from'] ?>" id="fdets" class="flight_details" />
        </div>
	      </span>
    </div><!-- /from_poi -->
    <!-- Conditionally show the Meet and Greet only if it applies to the reservation per Step 1 (if Pickup or Stop location = Logan Airport) -->
    <div class="row group">
        <input name="greet" type="checkbox" id="meet_greet" <?php if($values['greet']) echo 'checked="checked"' ?>/>
        <label for="meet_greet">Logan airport meet and greet $30 <span class="tip">(?)</span></label>
        <div class="tooltip">Massport requires drivers to stay with their vehicles; select the meet and greet to be met at Baggage Claim and escorted to the car.</div>
    </div>
    <div class="row group">
        <!-- Unchecked by default.  Checking this will launch the same popover per the "edit" link below -->
        <input name="stop" value="1" type="checkbox" id="intermediate_stop" <?php if($values['stop']) echo 'checked="checked"' ?> />
        <label for="intermediate_stop">Add an intermediate stop <span class="tip">(?)</span></label>
        <div class="tooltip">Intermediate Stop trips add $20 plus wait time over 10 minutes at your intermediate stop to the cost of the trip. Reserve by the Hour to make more than one Intermediate Stop.</div>

        <div class="intermediate_stop">
            <div id="stop_wrap">
                The intermediate stop location
                <select id="saved_locations_stop" name="stopLoc" class="saved_locations">
                    <option value="">Saved locations</option>
                    <?php foreach(Account::getSavedLocations() as $location): ?>
                    <option <?php if($location['machid'] == $values['stop_location'] || $location['machid'] === $_GET['stop']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                             data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                             data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
                        <?php echo $location['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <div id="stop_address_wrap" class="stop_location_option">
                    <!-- Conditionally and dynamically show this based on radio selection above -->
                    <div class="row group">
                        <label for="stop_name">Nickname</label><br />
                        <input name="stop_name" type="text" id="stop_name" value="<?php echo $values['stop_name'] ?>" />
                    </div>
                    <div class="row group">
                        <label for="stop_street_address">Street Address</label><br />
                        <input name="stop_address" type="text" id="stop_street_address" value="<?php echo $values['stop_address'] ?>" />
                    </div>
                    <div class="row group">
                        <label for="stop_city">City</label><br />
                        <input name="stop_city" type="text" id="stop_city" value="<?php echo $values['stop_city'] ?>" />
                    </div>
                    <div class="row group">
                        <label for="stop_state">State</label><br />
                        <input name="stop_state" type="text" id="stop_state" value="<?php echo $values['stop_state'] ?>" />
                    </div>
                    <div class="row group">
                        <label for="stop_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
                        <input name="stop_zip" type="text" id="stop_zipcode" value="<?php echo $values['stop_zip'] ?>" />
                    </div>
                </div><!-- /stop_address -->
            </div>
        </div>


    </div>
    <?php /*
	      <!-- The intermediate stop location, below, is only shown once the above checkbox is selected and resulting popover is completed -->
	      <p>
		723 Main Street<br />
		Boston, MA 02114<br />
		<a href="/pop/stop.php" class="popover" title="Add an intermediate stop">(Edit)</a><!-- Edit link should launch popover with previously-filled data in it, unlike this demo dialog, which has empty fields -->
	      </p>


		  <?php foreach(array(
		    "Logan Int'l Airport (BOS)",
		    "Manchester Airport (MHT)",
		    "T.F. Green Airport (PVD)",
		  ) as $k=>$v): ?>
		    <option value="<?php echo $v ?>" <?php if($v == $values['to_airport']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
	      <script>
		$(function() {
		  var c = $('[name=acode]');
		  c
		    .change(function() {
		      var det = $('#flight_details');
		      if(c.val() == "") {
			det.hide();
		      } else {
			det.show();
		      }
		    })
		    .change()
		  ;
		});
	      </script>
    */ ?>

</fieldset>
<!-- END LEFT COLUMN -->


<!-- START RIGHT COLUMN -->
<fieldset id="dropoff" class="half_column">

    <legend>To</legend>

    <div class="radio_buttons">
        <a href="#" onclick="$('#saved_locations_to, [name=apts_to]', $(this).parent().parent()).find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>

        <?php $toApt = ($_REQUEST['from_type'] == 2 && $_REQUEST['apts_to']) || strpos($_REQUEST['to'], 'airport') !== false || strpos($values['to_location'], 'airport') !== false || 'to_airport_wrap' == $values['to_type'] ?>
        <div style='float:right;'>
            <input type="radio" <?php if(!$toApt) echo 'checked="checked"' ?> name="to_type"  value="1" id="to_address" class="to_toggle" /><label for="to_address">Address</label>
            <input type="radio" <?php if( $toApt) echo 'checked="checked"' ?> name="to_type" value="2" id="to_airport" class="to_toggle" /><label for="to_airport">Airport</label>
        </div>
        <?php /*<input type="radio" <?php if(2 == $values['to_type']) echo 'checked="checked"' ?> name="to_type" value="2" id="to_poi"  class="to_toggle" /><label for="to_poi">Point of Interest</label>*/ ?>
    </div>

    <!-- Conditionally show the following instead of the preceeding select tag if user is currently logged out
           <a href="#">Log in to view saved locations</a>
         -->


    <div id="to_address_wrap" class="to_location_option">

        <select id="saved_locations_to" name="to_location" class="saved_locations">
            <option value="">Saved locations</option>
            <?php foreach(Account::getSavedLocations() as $location): if(strstr($location['machid'], 'airport') !== false) continue  ?>
            <option <?php if($location['machid'] == $values['to_location'] || $location['machid'] === $_GET['to']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
                                                                                                                                                 data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
                                                                                                                                                 data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>"><?php echo $location['name'] ?></option>
            <?php endforeach; ?>
        </select><br/><br/>

        <div id="saved_locations_to_wrap">
            <!-- Conditionally shown based on radio selection above -->
            <div class="row group">
                <label for="to_name">Nickname</label><br />
                <input name="to_name" type="text" id="to_name" value="<?php echo $values['to_name'] ?>" />
            </div>
            <div class="row group">
                <label for="to_street_address">Street Address</label><br />
                <input name="to_address" type="text" id="to_street_addres" value="<?php echo $values['to_address'] ?>" />
            </div>
            <div class="row group">
                <label for="to_city">City</label><br />
                <input name="to_city" type="text" id="to_city" value="<?php echo $values['to_city'] ?>" />
            </div>
            <div class="row group">
                <label for="to_state">State</label><br />
                <input name="to_state" type="text" id="to_state" value="<?php echo $values['to_state'] ?>" />
            </div>
            <div class="row group">
                <label for="to_zipcode">Zip Code <span class="popover"><a href="#">(look up)</a></span></label><br />
                <input name="to_zip" type="text" id="to_zipcode" value="<?php echo $values['to_zip'] ?>" />
            </div>
        </div><!-- /to_address -->
    </div>

    <div id="to_poi_wrap" class="to_location_option">
        <!-- Conditionally shown based on radio selection above -->
        <div class="row group">
            <select name="apts_to" style='width: 100%;'>
                <option value="">Select an airport</option>
                <?php echo get_airports_options($_REQUEST['apts_to'] ? $_REQUEST['apts_to']  : ($_REQUEST['to'] ? $_REQUEST['to'] : $values['to_location'])) ?>
            </select>
        </div>
        <div class="row group">
            <select name="acode_to" style='width: 100%;'>
                <option value="">Select an airline</option>
                <?php foreach(Account::getAirlines() as $key => $v): ?>
                <option value="<?php echo $key ?>" <?php if($key == $values['acode_to']) echo 'selected="selected"' ?>><?php echo $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
	      <span id="flight_details">
		<div class="row  group">
            <label for="fnum">Flight #</label>
            <input name="fnum_to" value="<?php echo $values['fnum_to'] ?>" type="text" id="fnum" class="flight_no" />
        </div>
		<div class="row  group">
            <label for="fdets">Time/Other details</label>
            <input name="fdets_to" type="text" value="<?php echo $values['fdets_to'] ?>" id="fdets" class="flight_details" />
        </div>
	      </span>
    </div><!-- /to_poi -->

</fieldset>
</div><!-- /group -->

<div id="step_navigation" class="hr_top">
    <input type="button" value="Step 2 &raquo;" class="button next" />
</div>
</div><!-- /step1 -->
<div class="step2"><!-- step2 -->
    <h2>Select a vehicle:</h2>
    <div style="display:none" id="base_estiamte_for_vehicles">60</div>

    <?php $tools = new Tools();
    foreach($tools->car_select_details() as $k=>$v): ?>
        <div class="vehicle_desc group">
            <label for="vehicle<?php echo $k ?>" style="display:block;float: left;width: 95%;">
                <img src="/img/vehicles/<?php echo $v['img'] ?>" width="145" height="60" alt="<?php echo $v['name'] ?>" />
                <div class="vehicle_details">
                    <label for="vehicle1" class="vehicle_name"><?php echo $v['name'] ?></label><br />
                    Seats: <?php echo $v['seats'] ?> passengers<br />
                    Holds: <?php echo $v['suitcases'] ?> suitcases<br/>
                    <?php if($v['extra']) echo $v['extra'] ?>
                </div>
                <div style="display:none"  id="<?=$v['vehicle_type']?>_vehicle_upgrade"><?=$v['price']?></div>
                <div  id="<?=$v['vehicle_type']?>_vehicle_price" class="vehicle_price"></div>
            </label>
            <div class="vehicle_chooser">
                <input type="radio" name="carTypeSelect" id="vehicle<?php echo $k ?>" value="<?php echo $k.'' ?>" <?php if($k == $values['carTypeSelect'] || (!$values['carTypeSelect'] && $k=="P")) echo 'checked="checked"' ?>/>
            </div>
        </div>
        <?php endforeach ?>


    <strong>Details</strong>

    <ul class="order_details">
        <li>Fare Type: <span id="fareType"></span></li>
        <li>Pickup location: Logan Int'l Airport</li>
        <li style="display: none">Pickup location: Logan Int'l Airport</li>
        <li>Drop-off location: Manchester Int'l Airport</li>
    </ul>

    <!--strong>Additional Information</strong>

     <ul class="order_details">
       <li>Fare Type: One way</li>
       <li>Pickup location: Logan Int'l Airport</li>
       <li>Drop-off location: Manchester Int'l Airport</li>
     </ul-->

    <p class="order_note">Prices are based on dropoff and pickup locations shown. Airport fees and applicable discounts are included in estimated fare.</p>


    <div id="step_navigation">
        <input type="button" value="&laquo; Back to Step 1" class="button prev" />
        <input type="button" value="Step 3 &raquo;" class="button next" />
    </div>
</div><!-- /step2 -->
<div class="step3"><!-- step3 -->
    <h2>Passenger Info</h2>
    <div class="row group">
        <div class="labelish">
            <label for="passengers_outgoing">Passengers</label>
        </div>
        <div class="inputs">
            <select name="pax" id="passengers_outgoing">
                <?php foreach(range(1,4) as $v): ?>
                <option value="<?php echo $v ?>" <?php if($v == $values['pax']) echo 'selected="selected"' ?>><?php echo $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row group">
        <div class="labelish">
            <label for="child_seats_outgoing">Child Seats</label>
        </div>
        <div class="inputs">
            <select name="convertible_seats" id="child_seats_outgoing">
                <option value="0">no convertible seats $0</option>
                <option value="1" <?php if(1 == $values['convertible_seats']) echo 'selected="selected"' ?>>1 convertible seat $15</option>
                <option value="2" <?php if(2 == $values['convertible_seats']) echo 'selected="selected"' ?>>2 convertible seats $30</option>
                <option value="3" <?php if(3 == $values['convertible_seats']) echo 'selected="selected"' ?>>3 convertible seats $45</option>
            </select><br />
            <select name="booster_seats" id="booster_seats_outgoing">
                <option value="0">no booster seats $0</option>
                <option value="1" <?php if(1 == $values['booster_seats']) echo 'selected="selected"' ?>>1 booster seat $15</option>
                <option value="2" <?php if(2 == $values['booster_seats']) echo 'selected="selected"' ?>>2 booster seats $30</option>
                <option value="3" <?php if(3 == $values['booster_seats']) echo 'selected="selected"' ?>>3 booster seats $45</option>
            </select>
        </div>
    </div>


    <h2>Reservation For</h2>
    <div class="row group">
        <div class="labelish">
            <label for="passenger_name">Passenger</label>
        </div>
        <div class="inputs">
            <select name="scheduleid" id="passenger_name">
                <option value="<?php echo $this->db->get_user_scheduleid($_SESSION['currentID']) ?>"><?php echo $_SESSION['currentName'] ?></option>
            </select>
        </div>
    </div>
    <div id="opFields">
        <div class="row group">
            <div class="labelish">
                <label for="pname">Other Passenger Name:</label>
            </div>
            <div class="inputs">
                <input name="pname" type="text" id="pname" value="<?php echo $values['pname'] ?>" />
            </div>
        </div>
        <div class="row group">
            <div class="labelish">
                <label for="pname">Other Passenger Cell:</label>
            </div>
            <div class="inputs">
                <input name="cphone" type="text" id="cphone" value="<?php echo $values['cphone'] ?>" />
            </div>
        </div>
    </div>
    <div class="row group">
        <div class="labelish">
            <label for="project_code">Cost or project code</label>
        </div>
        <div class="inputs">
            <input name="cccode" type="text" id="project_code" value="<?php echo $values['cccode'] ?>" />
        </div>
    </div>
    <div class="row group">
        <div class="labelish">
            <label for="special_instructions">Special Instructions</label>
        </div>
        <div class="inputs">
            <textarea name="special" id="special_instructions"><?php echo $values['special'] ?></textarea>
        </div>
    </div>
    <!--div class="row group admin">
	    <div class="labelish">
	      <label for="customer_service_notes">Customer Service Notes</label>
	    </div>
	    <div class="inputs">
	      <textarea name="summary" id="customer_service_notes"><?php echo $values['summary'] ?></textarea>
	    </div>
	  </div-->

    <div id="step_navigation">
        <input type="button" value="&laquo; Back to Step 2" class="button prev" />
        <input type="button" value="Step 4 &raquo;" class="button next" />
    </div>
</div><!-- /step3 -->
<div class="step4"><!-- step4 -->

    <h2>Payment Information</h2>
    <div class="row admin group">
        <div class="labelish">
            <label for="auto_billing">Override auto-billing?</label>
        </div>
        <div class="inputs">
            <input name="autoBillOverride" <?php if($values['autoBillOverride']) echo 'checked="checked"' ?> type="checkbox" id="auto_billing" />
        </div>
    </div>
    <div class="row group billing_toggle">
        <div class="labelish">
            <label for="payment_method">Payment method</label>
        </div>
        <div class="inputs">
            <select name="paymentProfileId" id="payment_method">
                <?php echo Account::getCreditCardsOptions(null, $values['ccard']) ?>
            </select>
            <!-- <a href="/pop/creditcards.php" class="popover-add" title="Add/modify credit cards">Add/Modify cards</a>-->
            <div id="payment_links_wrap">
                <a href="../AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=add&hidesubmit=false" class="popover-add" title="Add Credit Card">Add credit card</a>
                <?php foreach(Account::getCreditCards() as $paymentId => $description): ?>
                <a style="display: none;" href="../AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=edit&hidesubmit=false&paymentProfileId=<?php echo $paymentId ?>" class="popover-edit" title="Edit Credit Card">Modify credit card</a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row parallel group">
        <div class="labelish">
            <label for="coupon_code">Coupon code</label>
        </div>
        <div class="inputs">
            <input name="coupon" type="text" id="coupon_code" value="<?php echo $values['coupon'] ?>" />
        </div>
    </div>
    <div class="row group">
        <div class="labelish">
            <label for="confirmEmail">Email copy of reservation to</label>
        </div>
        <div class="inputs">
            <input name="confirmEmail" type="text" id="confirmEmail" value="<?php echo $values['confirmEmail'] ?>" />
        </div>
    </div>

    <h2>About your reservation</h2>
    <!-- p class="spacious_top">Lorem ipsum dolor sit amet, in sapien erat interdum, sem tristique ullamcorper vitae, interdum dolore, sed fringilla eleifend. Ligula faucibus neque pede, in tempor ultrices, fames nulla dignissim nam consequat ut, libero odio tempus elementum. Tortor tellus duis, odio viverra arcu sint ultricies, in sagittis malesuada, arcu elit vel praesent semper elit.</p>

     <div id="reservation_summary">
       <div class="line_item group">
         <span class="line_description">Estimated fare for Prius Sedan (including applicable tolls)</span>
         <span class="price">$70.00</span>
       </div>
       <div class="line_item group">
         <span class="line_description">Vehicle upgrade (SUV)</span>
         <span class="price">+$30.00</span>
       </div>
       <div class="line_item group">
         <span class="line_description">One child seat</span>
         <span class="price">+$15.00</span>
       </div>
       <div class="line_item group hr">
         <span class="line_description">One intermediate stop</span>
         <span class="price">+$15.00</span>
       </div>
       <div class="line_item group subtotal">
         <span class="line_description ">Estimate subtotal</span>
         <span class="price">$130.00</span>
       </div>
       <div class="line_item group hr">
         <span class="line_description">-20% discount rate (except on $9.50 in tolls)</span>
         <span class="price">-$24.70</span>
       </div>
       <div class="line_item group total">
         <span class="line_description">Total estimated fare:</span>
         <span class="price">$105.30</span>
       </div>
     </div -->

    <div id="reservation_summary">
    </div>

    <div id="step_navigation">
        <input type="button" value="&laquo; Back to Step 3" class="button prev" />
        <input type="submit" name="submit" value="Book Reservation" class="button" />
        <input type="hidden" name="estimate" />
    </div>

</div><!-- /step4 -->
</form>
</div>
</div>
