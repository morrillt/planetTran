<?php
/**
* English (en) help translation file.
* This also serves as the base translation file from which to derive
*  all other translations.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator Your Name <your@email.com>
* @version 07-10-04
* @package Languages
*
* Copyright (C) 2003 - 2004 PlanetTran Reservations
* License: GPL, see LICENSE
*/
///////////////////////////////////////////////////////////
// INSTRUCTIONS
///////////////////////////////////////////////////////////
// This file contains all the help file for phpScheduleit.  Please save the translated
//  file as '2 letter language code'.help.php.  For example, en.help.php.
// 
// To make PlanetTran Reservations help available in another language, simply translate this
//  file into your language.  If there is no direct translation, please provide the
//  closest translation.
//
// This will be included in the body of the help file.
//
// Please keep the HTML formatting unless you need to change it.  Also, please try
//  to keep the HTML XHTML 1.0 Transitional complaint.
///////////////////////////////////////////////////////////
?>
<div align="center"> 
  <h3><a name="top" id="top"></a>Introduction to PlanetTran Reservations</h3>
  <p><a href="http://www.planettran.com" target="_blank">http://www.planettran.com</a></p>
  <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: solid #CCCCCC 1px">
    <tr> 
      <td bgcolor="#FAFAFA"> 
        <ul>
          <li><b><a href="#overview">Overview</a></b></li>
          <li><b><a href="#getting_started">Getting Started</a></b></li>
          <ul>
            <li><a href="#registering">Registering</a></li>
            <li><a href="#logging_in">Logging In</a></li>
            <li><a href="#resetting_password">Resetting Your Forgotten Password</a></li>
            <li><a href="#getting_support">Getting Support</a></li>
          </ul>
          <li><a href="#my_control_panel"><b>My Control Panel</b></a></li>
          <ul>
            <li><a href="#my_announcements">Announcements</a></li>
            <li><a href="#my_schedules">Schedules</a></li>
            <li><a href="#my_reservations">Reservations</a></li>
            <li><a href="#my_training">Locations</a></li>
            <li><a href="#quick_links">Quick Links</a></li>
            <li><a href="#manage_profile">Changing Profile Information or Password</a></li>
          </ul>
<!--          <li><a href="#using_the_scheduler"><b>Using the Scheduler</b></a></li>
          <ul>
			<li><a href="#read_only">Read-Only Version</a></li>
            <li><a href="#making_a_reservation">Making a Reservation</a></li>
            <li><a href="#modifying_deleting_a_reservation">Modifying/Deleting 
              a Reservation</a></li>
            <li><a href="#navigating">Navigating the Scheduler</a></li>
          </ul>-->
        </ul>
		<hr width="95%" size="1" noshade="noshade" />
        <h4><a name="overview" id="overview"></a>Overview</h4>
	<P>PlanetTran Reservations is a simple on-line database which allows Passengers and Schedule Managers to create Passenger Profiles and Schedules, Company and Passenger Locations, and Passenger Reservations for ground transportation in one of PlanetTran's hybrid vehicles.
        <h4><a name="getting_started" id="getting_started"></a>Getting Started</h4>
        <p>PlanetTran Reservations requires email based registration and login.  There are two different roles a user of PlanetTran Reservations can undertake.
	<ul>
		<li><i>Schedule Manager:</i> this role is primarily for an administrative person who schedules travel for other people within an organization.</li>
		<li><i>Passenger:</i> this role is primarily for a person who travels within an organization, and often has someone else plan that travel.</li>
	</ul></p>
<p>Note that Schedule Manager are automatically Passengers. </p>
          <p align="right"><a href="#top">Top</a></p>
        <h5><a name="registering" id="registering"></a>Registering</h5>
        <p>To register, first navigate to the registration page. This can be reached 
          through a link on the initial login page. 
          The email address that you register with will be your login. The information 
          that you enter can be altered at any time by <a href="#quick_links">changing 
          your profile</a>. If you plan on managing schedules of Passengers other than your own, be sure to select 'Schedule Manager' from the drop-down list.  Selecting the &quot;Keep Me Logged In&quot; option 
          will use cookies to identify you each time you return to the scheduler, 
          bypassing the need to log in each time. <i>You should only use this 
          option if you are the only person using the scheduler on your computer.</i> 
          After registering, you will be redirected to <a href="#my_control_panel">My 
          Control Panel</a>.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="logging_in" id="logging_in"></a>Logging In</h5>
        <p>Logging in is as simple as entering your email address and password. 
          You must <a href="#registering">register</a> before you can log in. 
          This can be accomplished by following the registration link on the log 
          in page. Selecting the &quot;Keep Me Logged In&quot; option will use 
          cookies to identify you each time you return to the scheduler, bypassing 
          the need to log in each time. <i>You should only use this option if 
          you are the only person using the scheduler on your computer.</i> After 
          logging in, you will be redirected to <a href="#my_control_panel">My 
          Control Panel</a>.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="resetting_password" id="resetting_password"></a>Resetting Your Forgotten Password</h5>
        <p>If you have forgotten your password, you can reset it and have a new 
          one emailed to you. To do this, navigate to the login page and click 
          the &quot;I Forgot My Password&quot; link underneath the login form. 
          You will be taken to a new page and asked to enter your email address. 
          After clicking &quot;Submit&quot;, a new, randomly generated password 
          will be created. This new password will be set in the database and emailed 
          to you. After receiving this email, please copy and paste your new password, 
          <a href="#logging_in">log in</a> with it, and promptly <a href="#manage_profile">change 
          your password</a>.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="getting_support" id="getting_support"></a>Getting
          Help</h5>
        <p>If you have questions about
          PlanetTran Reservations, please use the &quot;Email
          Admin&quot; link
          located in <a href="#quick_links">My Quick Links.</a></p>
        <p align="right"><a href="#top">Top</a></p>        <p align="right">&nbsp;</p>
        <hr width="95%" size="1" noshade="noshade" />
        <h4><a name="my_control_panel" id="my_control_panel"></a>My Control Panel</h4>
        <p>The Control Panel is your &quot;home page&quot; for the scheduling 
          system. Here you can review, modify or delete your reservations. My 
          Control Panel also includes a link to the <a href="#using_the_scheduler">Scheduler</a>, 
          a link to <a href="#quick_links">Edit Your Profile</a> and an option 
          to Log Out of the Scheduling System.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="my_announcements" id="my_announcements"></a>My Announcements</h5>
        <p>This table will list any announcements that the system administrator 
          feels are important.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="my_reservations" id="my_reservations"></a>My Reservations</h5>
        <p>The My Reservations table shows all of your upcoming reservations starting 
          with today (by default). This table will list each reservation's Date, 
          Resource, Date/Time of its creation, Date/Time of its last modification, 
          Start Time and End Time. From this table you can also modify a reservation 
          or delete it, simply by clicking on the &quot;Modify&quot; or &quot;Delete&quot; 
          link at the end of the respective reservation's row. Both of these options 
          will bring up a pop-up box where you can confirm your reservation changes. 
          Clicking on a reservation's date will bring up a new window where you 
          can view the reservation's details.</p>
        <p>To sort your reservations by a specific column, click on the &#150; 
          or + link at the top of the column. The minus sign will sort your reservations 
          in descending order by that column name, the plus sign will sort your 
          reservations in ascending order by that column name.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="my_training" id="my_training"></a>My Permissions</h5>
        <p>The My Permissions table shows all the resources that you have been given permission to use.
		  It lists the resource name, its location and a phone number you 
          can call to contact its administrator.</p>
        <p>Upon registration, you will be given not have permission to use any resources unless the administrator
		  has decided to grant users permission automatically.  The administrator is the only person who can give you
		  permission to use a resource. You will not be permitted to reserve a resource on 
          which you have not been given permission, but you will be able to view its schedule 
          and current reservations.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="quick_links" id="quick_links"></a>My Quick Links</h5>
        <p>The Quick Links table will provide you with common application links.
          The first, &quot;Go 
          to the Online Scheduler&quot; will take you to the default schedule.
          Here you can view resource schedules, reserve resources, and edit your
          current reservations.</p>
        <p>&quot;Change My Profile Information/Password &quot; will
           navigate to a page allowing you to edit your personal information,
          such as login email 
          address, name, phone number and password. All of your information will
        be filled in for you. Blank and unchanged values will not be altered.</p>
        <p>&quot;Manage My Email Preferences&quot; will take you to a page
          where you can choose how and when you want to be contacted regarding
          your scheduler usage. By default, you will recieve HTML email alerts
          any time you add, edit or delete a reservation.</p>
        <p>The final link, &quot;Log Out&quot; will log you out of your current 
          session and return you to the log in screen.</p>
        <p align="right"><a href="#top">Top</a></p>
        <hr width="95%" size="1" noshade="noshade" />
       <!-- <h4><a name="using_the_scheduler" id="using_the_scheduler"></a>Using the Scheduler</h4>
        <p>The scheduler is where you can perform all resource scheduling functions.
           The week displayed begins with the current week and extends for 7
          (default) 
          days. Here you can view resource schedules, reserve resources, and
          edit  your current reservations. Reservations will be color coded and
          all will be shown, but only <i>your</i> reservations
          will provide a link to edit the reservation. All other reservations
          will only provide a link
          to
        view them.</p>
        <p>You can change schedules (if more than one exist) using the pull down
          menu at the top of each schedule.</p>
        <p>The system administrator can specify times that are &quot;blacked out&quot;,
          or determined to be unavailable by the admin. Reservations will not
          be placed if they conflict with a blackout time.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="read_only" id="read_only"></a>Read-Only
        Version</h5>
        <p>If you have not yet registered or logged in, you can view a read-only
          version of the schedule by clicking on the &quot;Read-Only Schedule&quot; link
          on the login page. This version of the schedule will show you all resources
          and reservations, but you will not be able to see any details about
          them nor will you be able to place reservations.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="making_a_reservation" id="making_a_reservation"></a>Making
          a Reservation</h5>
        <p>To reserve a resource, first navigate to the table for the day you
          wish  to make the reservation on. Once you have located the table for
          the 
          requested day, click on the resource name. This will bring up a pop-up
           window where you can select the time you wish to reserve the selected
          
          resource for.</p>
        <p>There will be a message below the time selection informing you of
          how long a reservation for this resource can be. If your reservation
          is greater than or less than this allowed time, it will not be accepted.</p>
        <p>You can also select if you want to repeat this reservation. To repeat
          a reservation, select the days you want it to repeat on, then select
          the duration you want to have the reservation repeat for. The reservation
          will be made for your initially selected day, plus all the days you
          selected as repeats. All dates that could not be reserved because of
        a reservation conflict will be listed.</p>
        <p>You can add a summary of this reservation by filling out the summary
          text box. This summary will then be available for all other users to
          read.</p>        
        <p>After setting correct beginning and ending times for the
           reservation and selecting if you want the reservation to repeat, press
          the &quot;Save&quot; button.
           A message will appear if the reservation was not successful, informing
           you of the date(s) that were not successful. If not successful, go
           back and edit the requested times so that they
           do not
           overlap with another
           current 
          reservation. After your reservation has been successfully made, the
            schedule will automatically refresh. This is required to reload all
           
          reservation information from the database.</p>
        <p>You cannot reserve a resource for a date that has passed, for a resource
           that you have not been given permission to use  or for a resource
          that is currently inactive. These resources will be grayed out and
          will
          not
          provide
          a
           reservation link.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="modifying_deleting_a_reservation" id="modifying_deleting_a_reservation"></a>Modifying/Deleting 
          a Reservation</h5>
        <p>There are multiple ways to modify or delete a reservation. One is
          from <a href="#my_control_panel">My
             Control Panel</a> as described above. The other is through the online
              scheduler. As previously noted, only you will be able to modify
             your 
          reservations. All other reservations will be shown, but will not provide
              a link to edit them.</p>
        <p>To edit a reservation through the scheduler, simply click on the
          reservation you wish to change. This will bring up a pop-up window
          very similar to the Reservation window. You have 2 choices;
          you
          can either modify the starting and ending times of the reservation,
          or you can click the &quot;Delete&quot; check box.
           After making your modifications, press the &quot;Modify&quot; 
          button at the bottom of the form. Your new options will be evaluated
           against current reservations and a message will appear letting you
          know 
          the status of your modification. If you need to change times, go back
           to the modification window and select new times which do not overlap
          
          other reservations. After your reservation has been successfully modified,
           the schedule will automatically refresh. This is required to reload
          
          all reservation information from the database.</p>
        <p>To modify a group of recurring reservations, check the box labeled
        &quot;Update all recurring records in group?&quot;. Any conflicting dates
          will be listed.</p>
        <p>You cannot edit a reservation for a date that has passed.</p>
        <p align="right"><a href="#top">Top</a></p>
        <h5><a name="navigating" id="navigating"></a>Navigating the Scheduler</h5>
        <p>There are many ways to navigate to dates in the scheduler.</p>
        <p>Move week by week using the &quot;Previous Week&quot; and &quot;Next Week&quot; links
          at the bottom of the scheduler.</p>
        <p>Jump to any date by entering it in the form at the bottom of the scheduler.</p>
        <p>Bring up a navigational calendar by clicking the &quot;View Calendar&quot; link
          at the bottom of the scheduler. Find your desired date and click on
          it to move the scheduler to that date.</p>
        <p align="right"><a href="#top">Top</a></p>
-->      </td>
    </tr>
  </table>
</div>
