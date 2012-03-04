<?php
/**
* Catalan (ca) translation file.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator felip alfred galit� i trilla <felipalf_g@catalonia.net>
* @version 08-23-2004
* @package Languages
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
///////////////////////////////////////////////////////////
// INSTRUCTIONS
///////////////////////////////////////////////////////////
// This file contains all of the strings that are used throughout phpScheduleit.
// Please save the translated file as '2 letter language code'.lang.php.  For example, en.lang.php.
// 
// To make phpScheduleIt available in another language, simply translate each
//  of the following strings into the appropriate one for the language.  If there
//  is no direct translation, please provide the closest translation.  Please be sure
//  to make the proper additions the /config/langs.php file (instructions are in the file).
//  Also, please add a help translation for your language using en.help.php as a base.
//
// You will probably keep all sprintf (%s) tags in their current place.  These tags
//  are there as a substitution placeholder.  Please check the output after translating
//  to be sure that the sentences make sense.
//
// + Please use single quotes ' around all $strings.  If you need to use the ' character, please enter it as \'
// + Please use double quotes " around all $email.  If you need to use the " character, please enter it as \"
//
// + For all $dates please use the PHP strftime() syntax
//    http://us2.php.net/manual/en/function.strftime.php
//
// + Non-intuitive parts of this file will be explained with comments.  If you
//    have any questions, please email lqqkout13@users.sourceforge.net
//    or post questions in the Developers forum on SourceForge
//    http://sourceforge.net/forum/forum.php?forum_id=331297
///////////////////////////////////////////////////////////

////////////////////////////////
/* Do not modify this section */
////////////////////////////////
global $strings;			  //
global $email;				  //
global $dates;				  //
global $charset;			  //
global $letters;			  //
global $days_full;			  //
global $days_abbr;			  //
global $days_two;			  //
global $days_letter;		  //
global $months_full;		  //
global $months_abbr;		  //
global $days_letter;		  //
/******************************/

// Charset for this language
// 'iso-8859-1' will work for most languages
$charset = 'iso-8859-1';

/***
  DAY NAMES
  All of these arrays MUST start with Sunday as the first element 
   and go through the seven day week, ending on Saturday
***/
// The full day name
$days_full = array('Diumenge', 'Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres', 'Disssabte');
// The three letter abbreviation
$days_abbr = array('Dg', 'Dl', 'Dm', 'Dc', 'Dj', 'Dv', 'Ds');
// The two letter abbreviation
$days_two  = array('Dg', 'Dl', 'Dm', 'Dc', 'Dj', 'Dv', 'Ds');
// The one letter abbreviation
$days_letter = array('Dg', 'Dl', 'Dm', 'Dc', 'Dj', 'Dv', 'Ds');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Gener', 'Febrer', 'Mar�', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre');
// The three letter month name
$months_abbr = array('Gen', 'Feb', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Des');

// All letters of the alphabet starting with A and ending with Z
$letters = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

/***
  DATE FORMATTING
  All of the date formatting must use the PHP strftime() syntax
  You can include any text/HTML formatting in the translation
***/
// General date formatting used for all date display unless otherwise noted
$dates['general_date'] = '%m/%d/%Y';
// General datetime formatting used for all datetime display unless otherwise noted
// The hour:minute:second will always follow this format
$dates['general_datetime'] = '%m/%d/%Y @';
// Date in the reservation notification popup and email
$dates['res_check'] = '%A %m/%d/%Y';
// Date on the scheduler that appears above the resource links
$dates['schedule_daily'] = '%A,<br/>%m/%d/%Y';
// Date on top-right of each page
$dates['header'] = '%A, %B %d, %Y';
// Jump box format on bottom of the schedule page
// This must only include %m %d %Y in the proper order,
//  other specifiers will be ignored and will corrupt the jump box 
$dates['jumpbox'] = '%m %d %Y';

/***
  STRING TRANSLATIONS
  All of these strings should be translated from the English value (right side of the equals sign) to the new language.
  - Please keep the keys (between the [] brackets) as they are.  The keys will not always be the same as the value.
  - Please keep the sprintf formatting (%s) placeholders where they are unless you are sure it needs to be moved.
  - Please keep the HTML and punctuation as-is unless you know that you want to change it.
***/
$strings['hours'] = 'hores';
$strings['minutes'] = 'minuts';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'dd';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'aaaa';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Administrador';
$strings['Welcome Back'] = 'Benvingut de nou, %s';
$strings['Log Out'] = 'Sortir';
$strings['My Control Panel'] = 'El meu quadre de control';
$strings['Help'] = 'Ajuda';
$strings['Manage Schedules'] = 'Gestiona els calendaris';
$strings['Manage Users'] = 'Gestiona els usuaris';
$strings['Manage Resources'] = 'Gestiona els recursos';
$strings['Manage User Training'] = 'Manage User Training';
$strings['Manage Reservations'] = 'Gestiona les reserves';
$strings['Email Users'] = 'Email Users';
$strings['Export Database Data'] = 'Exporta les dades de la base de dades';
$strings['Reset Password'] = 'Suprimeix la contrasenya';
$strings['System Administration'] = 'Administraci� del sistema';
$strings['Successful update'] = 'L\'actualitzaci� s\'ha realitzat satisfact�riament';
$strings['Update failed!'] = 'No s\'ha pogut realitzar l\'actualitzaci� !';
$strings['Manage Blackout Times'] = 'Gestiona el temps de rep�s';
$strings['Forgot Password'] = 'Has oblidat la contrasenya?';
$strings['Manage My Email Contacts'] = 'Gestiona els meus correus electr�nics de contacte';
$strings['Choose Date'] = 'Escull una data';
$strings['Modify My Profile'] = 'Modifica el meu perfil d\'usuari';
$strings['Register'] = 'Registra';
$strings['Processing Blackout'] = 'S\'est� processant el temps de rep�s';
$strings['Processing Reservation'] = 'S\'est� processant la reserva';
$strings['Online Scheduler [Read-only Mode]'] = 'Calendari en xarxa [Mode de lectura]';
$strings['Online Scheduler'] = 'Calendari en xarxa';
$strings['phpScheduleIt Statistics'] = 'Estad�stiques de phpScheduleIt ';
$strings['User Info'] = 'Informaci� de l\'usuari:';

$strings['Could not determine tool'] = 'No s\'ha pogut determinar el recurs.Si us plau torneu al Meu quadre de control i proveu-ho de nou.';
$strings['This is only accessable to the administrator'] = 'Nom�s �s accessible per l\'administrador';
$strings['Back to My Control Panel'] = 'Torna al Meu quadre de control';
$strings['That schedule is not available.'] = 'Aquest calendari no �s disponible';
$strings['You did not select any schedules to delete.'] = 'No heu seleccionat cap calendari per suprimir.';
$strings['You did not select any members to delete.'] = 'No heu seleccionat cap membre per suprimir.';
$strings['You did not select any resources to delete.'] = 'No heu seleccionat cap recurs per suprimir';
$strings['Schedule title is required.'] = 'Es necessita el nom del calendari';
$strings['Invalid start/end times'] = 'Inici o final inv�lids';
$strings['View days is required'] = 'Es necessita visualitzar els dies';
$strings['Day offset is required'] = 'Day offset is required';
$strings['Admin email is required'] = 'Es necessita el correu electr�nic de l\'administrador';
$strings['Resource name is required.'] = 'Es necessita el nom del recurs';
$strings['Valid schedule must be selected'] = 'S\'ha de seleccionar un calendari v�lid';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'Minimum reservation length must be less than or equal to maximum reservation length.';
$strings['Your request was processed successfully.'] = 'La vostra demanda s\'ha processat satisfact�riament';
$strings['Go back to system administration'] = 'Torna a l\'administraci� del sistema';
$strings['Or wait to be automatically redirected there.'] = 'O espereu a que se us redireccioni aqu�.';
$strings['There were problems processing your request.'] = 'Hem tingut problemes processant la seva demanda.';
$strings['Please go back and correct any errors.'] = 'Si us plau torneu enrera i corregiu els errors';
$strings['Login to view details and place reservations'] = 'Entreu per visualitzar els detalls i els llocs de reserva';
$strings['Memberid is not available.'] = 'L\'identitat de l\'usuari: %s no est� disponible.';

$strings['Schedule Title'] = 'T�tol del calendari';
$strings['Start Time'] = 'Inici';
$strings['End Time'] = 'Final';
$strings['Time Span'] = 'Temps d\'obertura';
$strings['Weekday Start'] = 'La setmana comen�a';
$strings['Admin Email'] = 'Correu electr�nic de l\'adiministrador';

$strings['Default'] = 'Defecte';
$strings['Reset'] = 'Reinicia';
$strings['Edit'] = 'Edita';
$strings['Delete'] = 'Suprimeix';
$strings['Cancel'] = 'Cancel�la';
$strings['View'] = 'Visualitza';
$strings['Modify'] = 'Modifica';
$strings['Save'] = 'Desa';
$strings['Back'] = 'Enrera';
$strings['Next'] = 'Seg�ent';
$strings['Close Window'] = 'Tanca la finestra';
$strings['Search'] = 'Cerca';
$strings['Clear'] = 'Buida';

$strings['Days to Show'] = 'Dies per mostrar';
$strings['Reservation Offset'] = 'Reservation Offset';
$strings['Hidden'] = 'Amagat';
$strings['Show Summary'] = 'Mostra el sumari';
$strings['Add Schedule'] = 'Afegeix un calendari';
$strings['Edit Schedule'] = 'Edita un calendari';
$strings['No'] = 'No';
$strings['Yes'] = 'S�';
$strings['Name'] = 'Nom';
$strings['First Name'] = 'First Name';
$strings['Last Name'] = 'Last Name';
$strings['Resource Name'] = 'Nom del recurs';
$strings['Email'] = 'Adre�a electr�nica';
$strings['Institution'] = 'Instituci�';
$strings['Phone'] = 'Tel�fon';
$strings['Password'] = 'Contrasenya';
$strings['Permissions'] = 'Permisos';
$strings['View information about'] = 'Visualitza l\'informaci� sobre %s %s';
$strings['Send email to'] = 'Envia un correu electr�nic a %s %s';
$strings['Reset password for'] = 'Esborra la contrasenya de %s %s';
$strings['Edit permissions for'] = 'Edita els permisos de %s %s';
$strings['Position'] = 'Posici�';
$strings['Password (6 char min)'] = 'Contrasenya (m�nim 6 car�cters)';
$strings['Re-Enter Password'] = 'Escriviu de nou la contrasenya';

$strings['Sort by descending last name'] = 'Ordena, de forma descendent, per cognom';
$strings['Sort by descending email address'] = 'Ordena, de forma descendent, per adre�a electr�nica';
$strings['Sort by descending institution'] = 'Ordena, de forma descendent, per instituci�';
$strings['Sort by ascending last name'] = 'Ordena, de forma ascendent, per cognom';
$strings['Sort by ascending email address'] = 'Ordena, de forma ascendent, per adre�a electr�nica';
$strings['Sort by ascending institution'] = 'Ordena, de forma ascendent, per instituci�';
$strings['Sort by descending resource name'] = 'Ordena, de forma descendent per nom de recurs';
$strings['Sort by descending location'] = 'Ordena, de forma decendent, per ubicaci�';
$strings['Sort by descending schedule title'] = 'Ordena, de formas descendent, per nom de calendari';
$strings['Sort by ascending resource name'] = 'Ordena, de forma ascendent, per nom';
$strings['Sort by ascending location'] = 'Ordena, de forma ascendent, per ubicaci�';
$strings['Sort by ascending schedule title'] = 'Orldena, de fomra ascendent per nom de calendari';
$strings['Sort by descending date'] = 'Sort by descending date';
$strings['Sort by descending user name'] = 'Sort by descending user name';
$strings['Sort by descending start time'] = 'Sort by descending start time';
$strings['Sort by descending end time'] = 'Sort by descending end time';
$strings['Sort by ascending date'] = 'Sort by ascending date';
$strings['Sort by ascending user name'] = 'Sort by ascending user name';
$strings['Sort by ascending start time'] = 'Sort by ascending start time';
$strings['Sort by ascending end time'] = 'Sort by ascending end time';
$strings['Sort by descending created time'] = 'Sort by descending created time';
$strings['Sort by ascending created time'] = 'Sort by ascending created time';
$strings['Sort by descending last modified time'] = 'Sort by descending last modified time';
$strings['Sort by ascending last modified time'] = 'Sort by ascending last modified time';

$strings['Search Users'] = 'Search Users';
$strings['Location'] = 'Location';
$strings['Schedule'] = 'Schedule';
$strings['Phone'] = 'Phone';
$strings['Notes'] = 'Notes';
$strings['Status'] = 'Status';
$strings['All Schedules'] = 'All Schedules';
$strings['All Resources'] = 'All Resources';
$strings['All Users'] = 'All Users';

$strings['Edit data for'] = 'Edit data for %s';
$strings['Active'] = 'Active';
$strings['Inactive'] = 'Inactive';
$strings['Toggle this resource active/inactive'] = 'Toggle this resource active/inactive';
$strings['Minimum Reservation Time'] = 'Minimum Reservation Time';
$strings['Maximum Reservation Time'] = 'Maximum Reservation Time';
$strings['Auto-assign permission'] = 'Auto-assign permission';
$strings['Add Resource'] = 'Add Resource';
$strings['Edit Resource'] = 'Edit Resource';
$strings['Allowed'] = 'Allowed';
$strings['Notify user'] = 'Notify user';
$strings['User Reservations'] = 'User Reservations';
$strings['Date'] = 'Date';
$strings['User'] = 'User';
$strings['Email Users'] = 'Email Users';
$strings['Subject'] = 'Subject';
$strings['Message'] = 'Message';
$strings['Please select users'] = 'Please select users';
$strings['Send Email'] = 'Send Email';
$strings['problem sending email'] = 'Sorry, there was a problem sending your email. Please try again later.';
$strings['The email sent successfully.'] = 'The email sent successfully.';
$strings['do not refresh page'] = 'Please <u>do not</u> refresh this page. Doing so will send the email again.';
$strings['Return to email management'] = 'Return to email management';
$strings['Please select which tables and fields to export'] = 'Please select which tables and fields to export:';
$strings['all fields'] = '- all fields -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Plain text';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Export Data';
$strings['Reset Password for'] = 'Reset Password for %s';
$strings['Please edit your profile'] = 'Please edit your profile';
$strings['Please register'] = 'Please register';
$strings['Email address (this will be your login)'] = 'Email address (this will be your login)';
$strings['Keep me logged in'] = 'Keep me logged in <br/>(requires cookies)';
$strings['Edit Profile'] = 'Edit Profile';
$strings['Register'] = 'Register';
$strings['Please Log In'] = 'Please Log In';
$strings['Email address'] = 'Email address';
$strings['Password'] = 'Password';
$strings['First time user'] = 'First time user?';
$strings['Click here to register'] = 'Click here to register';
$strings['Register for phpScheduleIt'] = 'Register for phpScheduleIt';
$strings['Log In'] = 'Log In';
$strings['View Schedule'] = 'View Schedule';
$strings['View a read-only version of the schedule'] = 'View a read-only version of the schedule';
$strings['I Forgot My Password'] = 'I Forgot My Password';
$strings['Retreive lost password'] = 'Retreive lost password';
$strings['Get online help'] = 'Get online help';
$strings['Language'] = 'Language';
$strings['(Default)'] = '(Default)';

$strings['My Announcements'] = 'My Announcements';
$strings['My Reservations'] = 'My Reservations';
$strings['My Permissions'] = 'My Permissions';
$strings['My Quick Links'] = 'My Quick Links';
$strings['Announcements as of'] = 'Announcements as of %s';
$strings['There are no announcements.'] = 'There are no announcements.';
$strings['Resource'] = 'Resource';
$strings['Created'] = 'Created';
$strings['Last Modified'] = 'Last Modified';
$strings['View this reservation'] = 'View this reservation';
$strings['Modify this reservation'] = 'Modify this reservation';
$strings['Delete this reservation'] = 'Delete this reservation';
$strings['Go to the Online Scheduler'] = 'Go to the Online Scheduler';
$strings['Change My Profile Information/Password'] = 'Change My Profile Information/Password';
$strings['Manage My Email Preferences'] = 'Manage My Email Preferences';
$strings['Mass Email Users'] = 'Mass Email Users';
$strings['Search Scheduled Resource Usage'] = 'Search Scheduled Resource Usage';
$strings['Export Database Content'] = 'Export Database Content';
$strings['View System Stats'] = 'View System Stats';
$strings['Email Administrator'] = 'Email Administrator';

$strings['Email me when'] = 'Email me when:';
$strings['I place a reservation'] = 'I place a reservation';
$strings['My reservation is modified'] = 'My reservation is modified';
$strings['My reservation is deleted'] = 'My reservation is deleted';
$strings['I prefer'] = 'I prefer:';
$strings['Your email preferences were successfully saved'] = 'Your email preferences were successfully saved!';
$strings['Return to My Control Panel'] = 'Return to My Control Panel';

$strings['Please select the starting and ending times'] = 'Please select the starting and ending times:';
$strings['Please change the starting and ending times'] = 'Please change the starting and ending times:';
$strings['Reserved time'] = 'Reserved time:';
$strings['Minimum Reservation Length'] = 'Minimum Reservation Length:';
$strings['Maximum Reservation Length'] = 'Maximum Reservation Length:';
$strings['Reserved for'] = 'Reserved for:';
$strings['Will be reserved for'] = 'Will be reserved for:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Update all recurring records in group?';
$strings['Delete?'] = 'Delete?';
$strings['Never'] = '-- Never --';
$strings['Days'] = 'Days';
$strings['Weeks'] = 'Weeks';
$strings['Months (date)'] = 'Months (date)';
$strings['Months (day)'] = 'Months (day)';
$strings['First Days'] = 'First Days';
$strings['Second Days'] = 'Second Days';
$strings['Third Days'] = 'Third Days';
$strings['Fourth Days'] = 'Fourth Days';
$strings['Last Days'] = 'Last Days';
$strings['Repeat every'] = 'Repeat every:';
$strings['Repeat on'] = 'Repeat on:';
$strings['Repeat until date'] = 'Repeat until date:';
$strings['Choose Date'] = 'Choose Date';
$strings['Summary'] = 'Summary';

$strings['View schedule'] = 'View schedule:';
$strings['My Reservations'] = 'My Reservations';
$strings['My Past Reservations'] = 'My Past Reservations';
$strings['Other Reservations'] = 'Other Reservations';
$strings['Other Past Reservations'] = 'Other Past Reservations';
$strings['Blacked Out Time'] = 'Blacked Out Time';
$strings['Set blackout times'] = 'Set blackout times for %s on %s'; 
$strings['Reserve on'] = 'Reserve %s on %s';
$strings['Prev Week'] = '&laquo; Prev Week';
$strings['Jump 1 week back'] = 'Jump 1 week back';
$strings['Prev days'] = '&#8249; Prev %d days';
$strings['Previous days'] = '&#8249; Previous %d days';
$strings['This Week'] = 'This Week';
$strings['Jump to this week'] = 'Jump to this week';
$strings['Next days'] = 'Next %d days &#8250;';
$strings['Next Week'] = 'Next Week &raquo;';
$strings['Jump To Date'] = 'Jump To Date';
$strings['View Monthly Calendar'] = 'View Monthly Calendar';
$strings['Open up a navigational calendar'] = 'Open up a navigational calendar';

$strings['View stats for schedule'] = 'View stats for schedule:';
$strings['At A Glance'] = 'At A Glance';
$strings['Total Users'] = 'Total Users:';
$strings['Total Resources'] = 'Total Resources:';
$strings['Total Reservations'] = 'Total Reservations:';
$strings['Max Reservation'] = 'Max Reservation:';
$strings['Min Reservation'] = 'Min Reservation:';
$strings['Avg Reservation'] = 'Avg Reservation:';
$strings['Most Active Resource'] = 'Most Active Resource:';
$strings['Most Active User'] = 'Most Active User:';
$strings['System Stats'] = 'System Stats';
$strings['phpScheduleIt version'] = 'phpScheduleIt version:';
$strings['Database backend'] = 'Database backend:';
$strings['Database name'] = 'Database name:';
$strings['PHP version'] = 'PHP version:';
$strings['Server OS'] = 'Server OS:';
$strings['Server name'] = 'Server name:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt root directory:';
$strings['Using permissions'] = 'Using permissions:';
$strings['Using logging'] = 'Using logging:';
$strings['Log file'] = 'Log file:';
$strings['Admin email address'] = 'Admin email address:';
$strings['Tech email address'] = 'Tech email address:';
$strings['CC email addresses'] = 'CC email addresses:';
$strings['Reservation start time'] = 'Reservation start time:';
$strings['Reservation end time'] = 'Reservation end time:';
$strings['Days shown at a time'] = 'Days shown at a time:';
$strings['Reservations'] = 'Reservations';
$strings['Return to top'] = 'Return to top';
$strings['for'] = 'for';

$strings['Select Search Criteria'] = 'Select Search Criteria';
$strings['Schedules'] = 'Schedules:';
$strings['All Schedules'] = 'All Schedules';
$strings['Hold CTRL to select multiple'] = 'Hold CTRL to select multiple';
$strings['Users'] = 'Users:';
$strings['All Users'] = 'All Users';
$strings['Resources'] = 'Resources:';
$strings['All Resources'] = 'All Resources';
$strings['Starting Date'] = 'Starting Date:';
$strings['Ending Date'] = 'Ending Date:';
$strings['Starting Time'] = 'Starting Time:';
$strings['Ending Time'] = 'Ending Time:';
$strings['Output Type'] = 'Output Type:';
$strings['Manage'] = 'Manage';
$strings['Total Time'] = 'Total Time';
$strings['Total hours'] = 'Total hours:';
$strings['% of total resource time'] = '% of total resource time';
$strings['View these results as'] = 'View these results as:';
$strings['Edit this reservation'] = 'Edit this reservation';
$strings['Search Results'] = 'Search Results';
$strings['Search Resource Usage'] = 'Search Resource Usage';
$strings['Search Results found'] = 'Search Results: %d reservations found';
$strings['Try a different search'] = 'Try a different search';
$strings['Search Run On'] = 'Search Run On:';
$strings['Member ID'] = 'Member ID';
$strings['Previous User'] = '&laquo; Previous User';
$strings['Next User'] = 'Next User &raquo;';

$strings['No results'] = 'No results';
$strings['That record could not be found.'] = 'That record could not be found.';
$strings['This blackout is not recurring.'] = 'This blackout is not recurring.';
$strings['This reservation is not recurring.'] = 'This reservation is not recurring.';
$strings['There are no records in the table.'] = 'There are no records in the %s table.';
$strings['You do not have any reservations scheduled.'] = 'You do not have any reservations scheduled.';
$strings['You do not have permission to use any resources.'] = 'You do not have permission to use any resources.';
$strings['No resources in the database.'] = 'No resources in the database.';
$strings['There was an error executing your query'] = 'There was an error executing your query:';

$strings['That cookie seems to be invalid'] = 'That cookie seems to be invalid';
$strings['We could not find that email in our database.'] = 'We could not find that email in our database.';
$strings['That password did not match the one in our database.'] = 'That password did not match the one in our database.';
$strings['You can try'] = '<br />You can try:<br />Registering an email address.<br />Or:<br />Try logging in again.';
$strings['A new user has been added'] = 'A new user has been added';
$strings['You have successfully registered'] = 'You have successfully registered!';
$strings['Continue'] = 'Continue...';
$strings['Your profile has been successfully updated!'] = 'Your profile has been successfully updated!';
$strings['Please return to My Control Panel'] = 'Please return to My Control Panel';
$strings['Valid email address is required.'] = '- Valid email address is required.';
$strings['First name is required.'] = '- First name is required.';
$strings['Last name is required.'] = '- Last name is required.';
$strings['Phone number is required.'] = '- Phone number is required.';
$strings['That email is taken already.'] = '- That email is taken already.<br />Please try again with a different email address.';
$strings['Min 6 character password is required.'] = '- Min 6 character password is required.';
$strings['Passwords do not match.'] = '- Passwords do not match.';

$strings['Per page'] = 'Per page:';
$strings['Page'] = 'Page:';

$strings['Your reservation was successfully created'] = 'Your reservation was successfully created';
$strings['Your reservation was successfully modified'] = 'Your reservation was successfully modified';
$strings['Your reservation was successfully deleted'] = 'Your reservation was successfully deleted';
$strings['Your blackout was successfully created'] = 'Your blackout was successfully created';
$strings['Your blackout was successfully modified'] = 'Your blackout was successfully modified';
$strings['Your blackout was successfully deleted'] = 'Your blackout was successfully deleted';
$strings['for the follwing dates'] = 'for the follwing dates:';
$strings['Start time must be less than end time'] = 'Start time must be less than end time.';
$strings['Current start time is'] = 'Current start time is:';
$strings['Current end time is'] = 'Current end time is:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'Reservation length does not fall within this resource\'s allowed length.';
$strings['Your reservation is'] = 'Your reservation is:';
$strings['Minimum reservation length'] = 'Minimum reservation length:';
$strings['Maximum reservation length'] = 'Maximum reservation length:';
$strings['You do not have permission to use this resource.'] = 'You do not have permission to use this resource.';
$strings['reserved or unavailable'] = '%s from %s to %s is reserved or unavailable.';
$strings['Reservation created for'] = 'Reservation created for %s';
$strings['Reservation modified for'] = 'Reservation modified for %s';
$strings['Reservation deleted for'] = 'Reservation deleted for %s';
$strings['created'] = 'created';
$strings['modified'] = 'modified';
$strings['deleted'] = 'deleted';
$strings['Reservation #'] = 'Reservation #';
$strings['Contact'] = 'Contact';
$strings['Reservation created'] = 'Reservation created';
$strings['Reservation modified'] = 'Reservation modified';
$strings['Reservation deleted'] = 'Reservation deleted';

$strings['Reservations by month'] = 'Reservations by month';
$strings['Reservations by day of the week'] = 'Reservations by day of the week';
$strings['Reservations per month'] = 'Reservations per month';
$strings['Reservations per user'] = 'Reservations per user';
$strings['Reservations per resource'] = 'Reservations per resource';
$strings['Reservations per start time'] = 'Reservations per start time';
$strings['Reservations per end time'] = 'Reservations per end time';
$strings['[All Reservations]'] = '[All Reservations]';

$strings['Permissions Updated'] = 'Permissions Updated';
$strings['Your permissions have been updated'] = 'Your %s permissions have been updated';
$strings['You now do not have permission to use any resources.'] = 'You now do not have permission to use any resources.';
$strings['You now have permission to use the following resources'] = 'You now have permission to use the following resources:';
$strings['Please contact with any questions.'] = 'Please contact %s with any questions.';
$strings['Password Reset'] = 'Password Reset';

$strings['This will change your password to a new, randomly generated one.'] = 'This will change your password to a new, randomly generated one.';
$strings['your new password will be set'] = 'After entering your email address and clicking "Change Password", your new password will be set in the system and emailed to you.';
$strings['Change Password'] = 'Change Password';
$strings['Sorry, we could not find that user in the database.'] = 'Sorry, we could not find that user in the database.';
$strings['Your New Password'] = 'Your New %s Password';
$strings['Your new passsword has been emailed to you.'] = 'Success!<br />'
    			. 'Your new passsword has been emailed to you.<br />'
    			. 'Please check your mailbox for your new password, then <a href="index.php">Log In</a>'
    			. ' with this new password and promptly change it by clicking the &quot;Change My Profile Information/Password&quot;'
    			. ' link in My Control Panel.';

$strings['You are not logged in!'] = 'You are not logged in!';

$strings['Setup'] = 'Setup';
$strings['Please log into your database'] = 'Please log into your database';
$strings['Enter database root username'] = 'Enter database root username:';
$strings['Enter database root password'] = 'Enter database root password:';
$strings['Login to database'] = 'Login to database';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'Root user is <b>not</b> required. Any database user who has permission to create tables is acceptable.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'This will set up all the necessary databases and tables for phpScheduleIt.';
$strings['It also populates any required tables.'] = 'It also populates any required tables.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!';
$strings['Not a valid database type in the config.php file.'] = 'Not a valid database type in the config.php file.';
$strings['Database user password is not set in the config.php file.'] = 'Database user password is not set in the config.php file.';
$strings['Database name not set in the config.php file.'] = 'Database name not set in the config.php file.';
$strings['Successfully connected as'] = 'Successfully connected as';
$strings['Create tables'] = 'Create tables &gt;';
$strings['There were errors during the install.'] = 'There were errors during the install. It is possible that phpScheduleIt will still work if the errors were minor.<br/><br/>'
	. 'Please post any questions to the forums on <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'You have successfully finished setting up phpScheduleIt and are ready to begin using it.';
$strings['Thank you for using phpScheduleIt'] = 'Please be sure to COMPLETELY REMOVE THE \'install\' DIRECTORY.'
	. ' This is critical because it contains database passwords and other sensitive information.'
	. ' Failing to do so leaves the door wide open for anyone to break into your database!'
	. '<br /><br />'
	. 'Thank you for using phpScheduleIt!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.';
$strings['There is no way to undo this action'] = 'There is no way to undo this action!';
$strings['Click to proceed'] = 'Click to proceed';
$strings['This version has already been upgraded to 1.0.0.'] = 'This version has already been upgraded to 1.0.0.';
$strings['Please delete this file.'] = 'Please delete this file.';
$strings['Successful update'] = 'The update succeeded fully';
$strings['Patch completed successfully'] = 'Patch completed successfully';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'
		. '<br />It is only required to run this if you performed a manual SQL update or are upgrading from 0.9.9';

// @since 1.0.0 RC1
$strings['If no value is specified, the default password set in the config file will be used.'] = 'If no value is specified, the default password set in the config file will be used.';
$strings['Notify user that password has been changed?'] = 'Notify user that password has been changed?';

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s \r\n"
				. "You have successfully registered with the following information:\r\n"
				. "Name: %s %s \r\n"
				. "Phone: %s \r\n"
				. "Institution: %s \r\n"
				. "Position: %s \r\n\r\n"
				. "Please log into the scheduler at this location:\r\n"
				. "%s \r\n\r\n"
				. "You can find links to the online scheduler and to edit your profile at My Control Panel.\r\n\r\n"
				. "Please direct any resource or reservation based questions to %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Administrator,\r\n\r\n"
					. "A new user has registered with the following information:\r\n"
					. "Email: %s \r\n"
					. "Name: %s %s \r\n"
					. "Phone: %s \r\n"
					. "Institution: %s \r\n"
					. "Position: %s \r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "You have successfully %s reservation #%s.\r\n\r\n<br/><br/>"
			. "Please use this reservation number when contacting the administrator with any questions.\r\n\r\n<br/><br/>"
			. "A reservation on %s between %s and %s for %s"
			. " located at %s has been %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "This reservation has been repeated on the following dates:\r\n<br/>";
$email['reservation_activity_3'] = "All recurring reservations in this group were also %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "The following summary was provided for this reservation:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "If this is a mistake, please contact the administrator at: %s"
			. " or by calling %s.\r\n\r\n<br/><br/>"
			. "You can view or modify your reservation information at any time by"
			. " logging into %s at:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "Please direct all technical questions to <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Your %s password has been reset by the administrator.\r\n\r\n"
			. "Your temporary password is:\r\n\r\n %s\r\n\r\n"
			. "Please use this temporary password (copy and paste to be sure it is correct) to log into %s at %s"
			. " and immediately change it using the 'Change My Profile Information/Password' link in the My Quick Links table.\r\n\r\n"
			. "Please contact %s with any questions.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Your new password for your %s account is:\r\n\r\n"
            . "%s\r\n\r\n"
            . "Please Log In at %s "
            . "with this new password "
            . "(copy and paste it to ensure it is correct) "
            . "and promptly change your password by clicking the "
            . "Change My Profile Information/Password "
            . "link in My Control Panel.\r\n\r\n"
            . "Please direct any questions to %s.";
?>