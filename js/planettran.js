
function zip_lookup(address, city, state, zip)
{
  window.document.addResource = {};
  window.document.addResource.address1 = address[0];
  window.document.addResource.city = city[0];
  window.document.addResource.state = state[0];
  window.document.addResource.zip = zip[0];
  
  var url = 'checkaddr.php?mode=3&address1=' + encodeURIComponent(address.val()) + '&city=' + encodeURIComponent(city.val()) + '&state=' + encodeURIComponent(state.val());
  var w = 400;
  var h = 500; 
  var o = window.open(url,"checkaddr","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");
}

function ajaxFormSubmission(form, container)
{
  return;
  form = $(form);
  container = $(container);
  
    $.ajax({
      url: form.attr("action"),
      type: 'post',
      data: form.serialize(),
      success: function(r) {
	container.html(r);
      }
    });
  
  return false;
}

$(document).ready(function() {
  
  
//	/* Tooltips (based on jQuery Tools plugin) */
//	$(".tip").tooltip({
//
//		// use div.tooltip as our tooltip
////		tip: '.tooltip',
//
//		// place tooltip on the right edge
//		//position: "center right",
//
//		// a little tweaking of the position
//		offset: [-10, 0]
//
//		// use the built-in fadeIn/fadeOut effect
////		effect: "fade",
//
//		// add dynamic plugin with optional configuration for bottom edge
//	}).dynamic({bottom: {direction: 'down'}});

  $(".tip").each(function(){
    $this = $(this);
    $this.tooltip({
//      tip: "."+$this.attr("id"),
      offset: [-10, 0]
    }).dynamic({bottom: {direction: "down"}});
  });



	/* Popovers (based on jQuery UI) */

	var $loading = $('<img src="/img/loading.gif" alt="loading" class="loading">');


  $('a.popover-edit, a.popover-add').live("click", function(event){
    $link = $(this);
//	$('a.popover-edit, a.popover-add').each(function(){
		var $dialog = $('<div></div>')
			.append($loading.clone());
//		var $link = $(this).click(function(event){
      event.preventDefault();
			$dialog
//				.load($link.attr('href') + ' .popover_content') /* When launching a popover, only show content within the external file's div.popover_content */
				.load($link.attr('href')) /* NO! because we won't be able execute any js in that file!!! */
				.dialog({
					resizable: false,
					modal: false,
					title: $link.attr('title'), /* Use link's title tag for popover title */
					width: 400,
					height: 425,
					buttons: {
						"Save": function(){
              $dialog.find(".popover_content form").submit(function(event){
                event.preventDefault();
		var form = $(this);
		var dataX = form.serializeArray();
		var dataX2 = {};
		
		for(i in dataX) {
		  dataX2[dataX[i].name] = dataX[i].value;
		}
                $.ajax({
                  type: "post",
//                  dataType: "json",
                  url: this.action,
                  data: $(this).serialize(),
                  success: function(data){
                    data = $.trim(data);
                    if(data.substr(0,3) == "glb") {
		      
	  
		      dataX2.machid = data;
		      updateTable(dataX2);
		
                      alert("Saved successfully!");
                      document.location = document.location; // refresh
                      $dialog.dialog("close"); // and close the dialog
                    } else { // refresh the form on failure
                      $dialog.html(data);
                    }
                    
                    
                  },
                  error: function(){
                    alert("Unexpected error!");
                  },
                  beforeSend: function(){
                    $dialog.find(".popover_content").hide().end() // hide content
                      .append($loading.clone()); // show loading
                  }
                });
              }).submit();
              // do nothing when there is no form
						},
						Cancel: function() {
							$(this).dialog("close");
						}
					}
				}).dialog("open");
//		});
	});


//	$('a.popover').each(function(){
//		var $dialog = $('<div></div>')
//			.append($loading.clone());
//		var $link = $(this).one("click", function(event){
//      event.preventDefault();
//			$dialog
//				.load($link.attr('href') + ' .popover_content') /* When launching a popover, only show content within the external file's div.popover_content */
//				.dialog({
//					title: $link.attr('title'), /* Use link's title tag for popover title */
//					width: 400,
//					height: 425
//				});
//
//			$link.click(function(){
//				$dialog.dialog('open');
//
//				return false;
//			});
//			return false;
//		});
//	});






	/* Delete popovers */

  $('a.popover-delete').live("click", function(event){
    $link = $(this);
//	$('a.popover-delete').each(function() {
		var $dialog = $('<div></div>')
			.append($loading.clone());
//		var $link = $(this).click(function(event){
      event.preventDefault();
			$dialog
//				.load($link.attr('href') + ' .popover_content') /* When launching a popover, only show content within the external file's div.popover_content */
				.load($link.attr('href')) /* NO! because we won't be able execute any js in that file!!! */
				.dialog({
					resizable: false,
					modal: false,
					title: $link.attr('title'), /* Use link's title tag for popover title */
					width: 400,
//					height: 200,
					buttons: {
						"Delete": function(){
              $dialog.find(".popover_content form").submit(function(event){
                event.preventDefault();
                $.ajax({
                  type: "post",
//                  dataType: "json",
                  url: this.action,
                  data: $(this).serialize(),
                  success: function(data){
                    data = $.trim(data);
                    if(data == "success"){
                       // remove container on success
                      if($link.hasClass("parentTr")){
                        $link.parents("tr").first().remove();
                      } else if($link.hasClass("parentDiv")){
                        $link.parents("div").first().remove();
                      }
                      $dialog.dialog("close"); // and close the dialog
                    } else if(data == "refresh"){
                      document.location = document.location; // refresh
                    } else {
                      $dialog.html(data);// refresh the form on failure
                    }
                  },
                  error: function(){
                    alert("Unexpected error!");
                  },
                  beforeSend: function(){
                    $dialog.find(".popover_content").hide().end() // hide content
                      .append($loading.clone()); // show loading
                  }
                });
              }).submit();
              // do nothing when there is not form
						},
						Cancel: function() {
							$(this).dialog("close");
						}
					}
				}).dialog("open");
        $(".ui-dialog:last .ui-dialog-buttonpane button:first").focus(); // focus default button
//		});
	});


	/* Remove Driver popover */
//	$('a.popover-removeDriver').each(function() {
//		var $dialog = $('<div></div>')
//			.append($loading.clone());
//		var $link = $(this).one('click', function() {
//			$dialog
//				.load($link.attr('href') + ' .popover_content') /* When launching a popover, only show content within the external file's div.popover_content */
//				.dialog({
//					resizable: false,
//					modal: false,
//					title: $link.attr('title'), /* Use link's title tag for popover title */
//					width: 400,
//					height: 200,
//					buttons: {
//						"Remove": function() {
//							$( this ).dialog("close");
//						},
//						Cancel: function() {
//							$( this ).dialog("close");
//						}
//					}
//				});
//
//			$link.click(function() {
//				$dialog.dialog('open');
//
//				return false;
//			});
//
//			return false;
//		});
//	});

  $("#addFavDriver").click(function(){
    $select = $("#favourite_drivers");
    var memberid = $select.val();
    if(memberid == "") return;
    $.ajax({
      type: "post",
      dataType: "text",
      url: $("#driverAddUrl").val(),
      data: {memberid: memberid},
      success: function(data){
        data = $.trim(data);
        if(data == "success"){
          alert("Favourite driver added successfully!");
          document.location = document.location; // refresh
        } else {
          alert("Unexpected error #1!");
        }
      },
      error: function(){
        alert("Unexpected error! #2");
      }
    });
  });

});

	

/* Date picker on Reservation - step 1 */
$(function() {
	$( "#reservation_date" ).datepicker({minDate: 0, maxDate: "+12M"});
});


/* Popup window plugin settings per http://rip747.github.com/popupwindow/  */

var popup_profiles =
{
	windowPicker:
	{
		height:600,
		width:500,
		status:1,
		scrollbars:1,
		resizable:1, 
		center:1
	},

	window400:
	{
		height:400,
		width:400,
		scrollbars:1,
		resizable:1,
		center:1
	}
};

function unloadcallback(){
	alert("unloaded");
};


$(function() {
	$(".popupwindow").popupwindow(popup_profiles);
});




function locationSwitcher(sel1, sel2, fAnyway){
  $(sel2).hide();
  $(sel1).each(function(index){
    $this = $(this);

    $this
      .unbind('change')
      .change(function(){
	if($(this).is(":checked"))
	{
	  $(sel2).hide();
	  $($(sel2)[parseInt($(this).val())-1]).show();
	}
      });
      
    if(fAnyway || !$(sel2).filter(':visible').length) $this.change();
  });
}
function selectHiderHelper(selectSelector, hideSelector){
  if($(selectSelector).val() != ""){
    $(hideSelector).slideUp();
  } else {
    $(hideSelector).slideDown();
  }
}
function selectHider(selectSelector, hideSelector){
  $(selectSelector).each(function(){
    selectHiderHelper(selectSelector, hideSelector);
    $(this).change(function(){
      selectHiderHelper(selectSelector, hideSelector);
    });
  });
}
function checkboxHiderHelper(checkboxSelector, hideSelector){
  if($(checkboxSelector).is(":checked")){
    $(hideSelector).slideDown();
  } else {
    $(hideSelector).slideUp();
  }
}
function checkboxHider(checkboxSelector, hideSelector){
  $(checkboxSelector).each(function(){
    checkboxHiderHelper(checkboxSelector, hideSelector);
    $(this).change(function(){
      checkboxHiderHelper(checkboxSelector, hideSelector);
    });
  });
}

function paymentLinksHelper(selector){
  $(selector).find("option").each(function(index){
    if($(this).is(":selected")){
      $("#payment_links_wrap a").hide().eq(index).show();
    }
  });
}
function paymentLinks(selector){
  paymentLinksHelper();
  $(selector).change(function(){
    paymentLinksHelper(selector);
  });
}
$(function(){
  locationSwitcher(".from_toggle", ".from_location_option", true);
  locationSwitcher(".to_toggle", ".to_location_option", true);
  //selectHider("#saved_locations_from", "#saved_locations_from_wrap");
  //selectHider("#saved_locations_to", "#saved_locations_to_wrap");
  //selectHider("#saved_locations_stop", "#stop_address_wrap");

  paymentLinks("#payment_method");

  checkboxHider("#intermediate_stop", "#stop_wrap");

  $("#auto_billing").change(function(){
    billingHelper();
  });
  billingHelper();
});

function billingHelper(){
  return;
  if($("#auto_billing").is(":checked")){
    $(".billing_toggle").show();
  } else {
    $(".billing_toggle").hide();
  }
}
