<?php 
require dirname(__FILE__).'/config/paths.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="home/styles/landing_styles.css"  />
<link rel="stylesheet" type="text/css" href="home/TitilliumText-fontfacekit/stylesheet.css"  />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>PlanetTran</title>
<script src="home/SpryAssets/SpryEffects.js" type="text/javascript"></script>
<script src="home/SpryAssets/SpryTooltip.js" type="text/javascript"></script>
<script type="text/javascript">
function MM_effectHighlight(targetElement, duration, startColor, endColor, restoreColor, toggle)
{
	Spry.Effect.DoHighlight(targetElement, {duration: duration, from: startColor, to: endColor, restoreColor: restoreColor, toggle: toggle});
}
</script>
<link href="home/SpryAssets/SpryTooltip.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">

    <div id="header">
    
    	<div id="logo"><a href="index.php" alt="PlanetTran homepage">Home</a></div>
    	
        <div id="navigation" style="font-family:TitilliumText22LRegular;">
        	<ul>
		  <li><a href="<?php echo $sitePrefix ?>/">HOME</a></li>
		  <li><a href="<?php echo $securePrefix ?>/index.php">RESERVATIONS</a></li>
		  <li><a href="<?php echo $sitePrefix ?>/service/">SERVICE</a></li>
		  <!--li><a href="<?php echo $sitePrefix ?>/news/">NEWS</a></li-->
		  <li><a href="<?php echo $sitePrefix ?>/about/">ABOUT</a></li>
		  <li><a href="<?php echo $sitePrefix ?>/Boston-Limo-Services.php">CONTACT</a></li>
            </ul>
        </div><!--END OF container #navigation-->
    
    </div><!--END OF container #header-->
        
    <div id="content">
    	
        <div id="title">
        	<h1>Your Ride is Here</h1>
            <p>Planet Tran brings you SmartTransport: reliable, convenient and eco-friendly chauffeur-driven transportation in greater Boston and the San Francisco Bay area.</p>
        </div><!--END OF #title-->
        
        <div id="left_top_menu">
        	<div id="reserve_button"><a href="<?php echo $securePrefix ?>/reserve.php?type=r&amp;tab=2">RESERVE NOW</a></div>
        	<div id="account_links">
            	<ul>
		    <li><a href="<?php echo $securePrefix ?>/reserve.php?type=r&amp;tab=1">GET A QUICK QUOTE</a></li>
                    <li><a href="<?php echo $securePrefix ?>/">LOG IN</a><!--br /><span>(It's quick and easy!)</span--></li>
                </ul>
           </div><!--END OF container #account_links-->
        </div><!--END OF container #left_top_menu-->
        
        
		<div id="bottom_menu">
        	<div class="left">Call us to reserve now!<br />
			<span>1.888.756.8876</span><br />
			<span>1.617.944.9229</span><br />
            <div style="margin-top:25px; line-height: 120%;"><img src="home/images/mobile_icon.png" alt="Book a ride from your mobile device!"/>Book a ride from<br />
            your mobile device!<br />
            <a href="http://m.planettran.com">GET THE APP</a></div>
            
            </div>
            
            <div class="middle">
            	<!--div id="email_form"><form method="post" id="email_list" action="http://oi.vresp.com?fid=7a06db36ae" target="vr_optin_popup" onsubmit="window.open( 'http://www.verticalresponse.com', 'vr_optin_popup', 'scrollbars=yes,width=600,height=450' ); return true;">
		  <input name="email_address" type="text" class="email" id="email" onclick="MM_effectHighlight(this, 400, '#ffffff', '#c5dc67', '', false)" value="join our mailing list" maxlength="60" /><input type="image" class="formimage" src="home/images/email_button.png" style="margin-left:0; height:auto" />
              </form>
	    </div-->
              <div id="social_media_buttons"><span id="sprytrigger1"><a href="http://facebook.com/planettran"><img src="home/images/facebookbutton.png" alt="Find PlanetTran on Facebook"  /></a></span>
                <a href="http://twitter.com/planettran" id="sprytrigger2"><img src="home/images/twitterbutton.png" alt="Follow PlanetTran on Twitter" /></a></div>
      </div>
            
            <div class="right"><p>"My driver was early and so was my flight and we left immediately, avoiding rush hour! I left my iphone in the car and she discovered it and brought it back to my hotel where she had dropped me off. She was just great!"</p><p class="signature">—Diane G, PlanetTran Customer</p></div>
        
        </div><!--END OF container #bottom_menu-->

    
    </div><!--END OF container #content-->


</div>
<div class="tooltipContent" id="sprytooltip2">Follow PlanetTran on Twitter!</div>
<div class="tooltipContent" id="sprytooltip1">Find PlanetTran on Facebook!</div>
<!--END OF #container-->

<script type="text/javascript">
var sprytooltip1 = new Spry.Widget.Tooltip("sprytooltip1", "#sprytrigger1", {offsetX:"-60px", useEffect:"fade", closeOnTooltipLeave:true});
var sprytooltip2 = new Spry.Widget.Tooltip("sprytooltip2", "#sprytrigger2", {useEffect:"fade", offsetX:"-60px"});
</script>
</body>
</html>
