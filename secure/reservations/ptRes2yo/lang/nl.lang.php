<?php
/**
* Nederlands (nl) vertaalbestand.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator <gerbrand@teomech.ugent.be>
* @version 08-01-04
* @package Languages
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
///////////////////////////////////////////////////////////
// INSTRUCTIONS
///////////////////////////////////////////////////////////
// This file contains all of the strings that are used throughout phpScheduleit.
// 
// To make phpScheduleIt available in another language, simply translate each
//  of the following strings into the appropriate one for the language.  Please be sure
//  to make the proper additions the /config/langs.php file (instructions are in the file).
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
$days_full = array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag');
// The three letter abbreviation
$days_abbr = array('Zon', 'Maa', 'Din', 'Woe', 'Don', 'Vri', 'Zat');
// The two letter abbreviation
$days_two  = array('Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za');
// The one letter abbreviation
$days_letter = array('Z', 'M', 'D', 'W', 'D', 'V', 'Z');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December');
// The three letter month name
$months_abbr = array('Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec');

// All letters of the alphabet starting with A and ending with Z
$letters = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

/***
  DATE FORMATTING
  All of the date formatting must use the PHP strftime() syntax
  You can include any text/HTML formatting in the translation
***/
// General date formatting used for all date display unless otherwise noted
$dates['general_date'] = '%d/%m/%Y';
// General datetime formatting used for all datetime display unless otherwise noted
// The hour:minute:second will always follow this format
$dates['general_datetime'] = '%d/%m/%Y @';
// Date in the reservation notification popup and email
$dates['res_check'] = '%A %d/%m/%Y';
// Date on the scheduler that appears above the resource links
$dates['schedule_daily'] = '%A,<br/>%d/%m/%Y';
// Date on top-right of each page
$dates['header'] = '%A, %B %d, %Y';
// Jump box format on bottom of the schedule page
// This must only include %m %d %Y in the proper order,
//  other specifiers will be ignored and will corrupt the jump box 
$dates['jumpbox'] = '%d %m %Y';

/***
  STRING TRANSLATIONS
  All of these strings should be translated from the English value (right side of the equals sign) to the new language.
  - Please keep the keys (between the [] brackets) as they are.  The keys will not always be the same as the value.
  - Please keep the sprintf formatting (%s) placeholders where they are unless you are sure it needs to be moved.
  - Please keep the HTML and punctuation as-is unless you know that you want to change it.
***/
$strings['hours'] = 'uren';
$strings['minutes'] = 'minuten';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'dd';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'jjjj';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Beheerder';
$strings['Welcome Back'] = 'Welkom, %s';
$strings['Log Out'] = 'Uitloggen';
$strings['My Control Panel'] = 'Mijn controlepaneel';
$strings['Help'] = 'Help';
$strings['Manage Schedules'] = 'Beheer planningen';
$strings['Manage Users'] ='Beheer gebruiker';
$strings['Manage Resources'] ='Beheer bronnen';
$strings['Manage User Training'] ='Beheer Gebruikersopleiding';
$strings['Manage Reservations'] ='Beheer reservaties';
$strings['Email Users'] ='E-mail gebruikers';
$strings['Export Database Data'] = 'Exporteer data gegevensbank';
$strings['Reset Password'] = 'Wijzig wachtwoord';
$strings['System Administration'] = 'Systeembeheer';
$strings['Successful update'] = 'Update gelukt';
$strings['Update failed!'] = 'Update mislukt!';
$strings['Manage Blackout Times'] = 'Beheer Blackout tijden';
$strings['Forgot Password'] = 'Wachtwoord vergeten';
$strings['Manage My Email Contacts'] = 'Beheer mijn e-mail gegevens';
$strings['Choose Date'] = 'Kies datum';
$strings['Modify My Profile'] = 'Wijzig mijn profiel';
$strings['Register'] = 'Registreer';
$strings['Processing Blackout'] = 'Bezig met verwerken ongeschikbaarheid';
$strings['Processing Reservation'] = 'Bezig met verwerken reservatie';
$strings['Online Scheduler [Read-only Mode]'] = 'Online Planner [Alleen-lezen modus]';
$strings['Online Scheduler'] = 'Online Planner';
$strings['phpScheduleIt Statistics'] = 'phpScheduleIt Statistieken';
$strings['User Info'] = 'Gebruikersinfo:';

$strings['Could not determine tool'] = 'Kan tool niet vinden. Ga terug naar Mijn controlepaneel en probeer later opnieuw.';
$strings['This is only accessable to the administrator'] = 'Dit is enkel toegankelijk voor de beheerder';
$strings['Back to My Control Panel'] = 'Terug naar Mijn controlepaneel';
$strings['That schedule is not available.'] = 'Deze planning is niet toegankelijk.';
$strings['You did not select any schedules to delete.'] = 'Je hebt geen planning gekozen om te verwijderen.';
$strings['You did not select any members to delete.'] = 'Je hebt geen gebruikers gekozen om te verwijderen.';
$strings['You did not select any resources to delete.'] = 'Je hebt geen bronnen gekozen om ter verwijderen.';
$strings['Schedule title is required.'] = 'Planningstitel is vereist.';
$strings['Invalid start/end times'] = 'Ongeldige begin/eindtijden';
$strings['View days is required'] = 'Toon dagen is vereist';
$strings['Day offset is required'] = 'Day offset is required';
$strings['Admin email is required'] = 'E-mail van beheerder is vereist';
$strings['Resource name is required.'] = 'Naam van de bron is vereist.';
$strings['Valid schedule must be selected'] = 'Selecteer een geldige plannning';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'Minimale reservatieduur dient kleiner of gelijk te zijn aan de maximale reservatieduur.';
$strings['Your request was processed successfully.'] = 'Je aanvraag is met succes verwerkt.';
$strings['Go back to system administration'] = 'Ga terug naar het systeembeheer';
$strings['Or wait to be automatically redirected there.'] = 'Of wacht tot je automatisch wordt doorgestuurd.';
$strings['There were problems processing your request.'] = 'Er was een probleem om je aanvraag te verwerken.';
$strings['Please go back and correct any errors.'] = 'Ga terug en corrigeer de fouten.';
$strings['Login to view details and place reservations'] = 'Login om de details te bekijken en reservaties te plaatsen';
$strings['Memberid is not available.'] = 'Leden-id: %s is niet beschikbaar.';

$strings['Schedule Title'] = 'Titel Planning';
$strings['Start Time'] = 'Begintijd';
$strings['End Time'] = 'Eindtijd';
$strings['Time Span'] = 'Tijdsspanne';
$strings['Weekday Start'] = 'Weekdag begin';
$strings['Admin Email'] = 'E-mail beheerder';

$strings['Default'] = 'Standaard';
$strings['Reset'] = 'Wijzig';
$strings['Edit'] = 'Bewerk';
$strings['Delete'] = 'Verwijder';
$strings['Cancel'] = 'Annuleer';
$strings['View'] = 'Bekijk';
$strings['Modify'] = 'Wijzig';
$strings['Save'] = 'Bewaar';
$strings['Back'] = 'Terug';
$strings['Next'] = 'Volgende';
$strings['Close Window'] = 'Sluit venster';
$strings['Search'] = 'Zoeken';
$strings['Clear'] = 'Leeg maken';

$strings['Days to Show'] = 'Aantal dagen te tonen';
$strings['Reservation Offset'] = 'Reservatie Offset';
$strings['Hidden'] = 'Verborgen';
$strings['Show Summary'] = 'Toon samenvatting';
$strings['Add Schedule'] = 'Voeg planning toe';
$strings['Edit Schedule'] = 'Bewerk planning';
$strings['No'] = 'Nee';
$strings['Yes'] = 'Ja';
$strings['Name'] = 'Naam';
$strings['First Name'] = 'Voornaam';
$strings['Last Name'] = 'Naam';
$strings['Resource Name'] = 'Naam van de bron';
$strings['Email'] = 'E-mail';
$strings['Institution'] = 'Organisaties';
$strings['Phone'] = 'Telefoon';
$strings['Password'] = 'Wachtwoord';
$strings['Permissions'] = 'Toegangsrechten';
$strings['View information about'] = 'Bekijk informatie over %s %s';
$strings['Send email to'] = 'Verzend e-mail naar %s %s';
$strings['Reset password for'] = 'Wijzig wachtwoord voor %s %s';
$strings['Edit permissions for'] = 'Bewerk toegangsrechten voor %s %s';
$strings['Position'] = 'Positie';
$strings['Password (6 char min)'] = 'Wachtwoord (min. 6 tekens)';
$strings['Re-Enter Password'] = 'Bevestig je wachtwoord';

$strings['Sort by descending last name'] = 'Sorteer volgens naam (aflopend)';
$strings['Sort by descending email address'] = 'Sorteer volgens e-mailadres (aflopend)';
$strings['Sort by descending institution'] = 'Sorteer volgens organisatie (aflopend)';
$strings['Sort by ascending last name'] = 'Sorteer volgens naam (oplopend)';
$strings['Sort by ascending email address'] = 'Sorteer volgens e-mailadres (oplopend)';
$strings['Sort by ascending institution'] = 'Sorteer volgens organisatie (oplopend)';
$strings['Sort by descending resource name'] = 'Sorteer volgens reservatiebron (aflopend)';
$strings['Sort by descending location'] = 'Sorteer volgens plaats (aflopend)';
$strings['Sort by descending schedule title'] = 'Sorteer volgens planningstitel (aflopend)';
$strings['Sort by ascending resource name'] = 'Sorteer volgens reservatiebron (oplopend)';
$strings['Sort by ascending location'] = 'Sorteer volgens plaats (oplopend)';
$strings['Sort by ascending schedule title'] = 'Sorteer volgens planningstitel (oplopend)';
$strings['Sort by descending date'] = 'Sorteer volgens datum (aflopend)';
$strings['Sort by descending user name'] = 'Sorteer volgens gebruikersnaam (aflopend)';
$strings['Sort by descending start time'] = 'Sorteer volgens begintijd (aflopend)';
$strings['Sort by descending end time'] = 'Sorteer volgens eindtijd (aflopend)';
$strings['Sort by ascending date'] = 'Sorteer volgens datum (oplopend)';
$strings['Sort by ascending user name'] = 'Sorteer volgens gebruikersnaam (oplopend)';
$strings['Sort by ascending start time'] = 'Sorteer volgens begintijd (oplopend)';
$strings['Sort by ascending end time'] = 'Sorteer volgens begintijd (aflopend)';
$strings['Sort by descending created time'] = 'Sorteer volgens datum van aanmaken (aflopend)';
$strings['Sort by ascending created time'] = 'Sorteer volgens datum van aanmaken (oplopend)';
$strings['Sort by descending last modified time'] = 'Sorteer volgens wijzigingsdatum (aflopend)';
$strings['Sort by ascending last modified time'] = 'Sorteer volgens wijzigingsdatum (oplopend)';

$strings['Search Users'] = 'Zoek gebruikers';
$strings['Location'] = 'Plats';
$strings['Schedule'] = 'Planning';
$strings['Phone'] = 'Telefoon';
$strings['Notes'] = 'Opmerkingen';
$strings['Status'] = 'Status';
$strings['All Schedules'] = 'Alle planningen';
$strings['All Resources'] = 'Alle reservatiebronnen';
$strings['All Users'] = 'Alle gebruikers';

$strings['Edit data for'] = 'Bewerk gegevens voor %s';
$strings['Active'] = 'Actief';
$strings['Inactive'] = 'Inactief';
$strings['Toggle this resource active/inactive'] = 'Wissel deze bron actief/inactief';
$strings['Minimum Reservation Time'] = 'Minimale Reservatietijd';
$strings['Maximum Reservation Time'] = 'Maximale Reservatietijd';
$strings['Auto-assign permission'] = 'Auto-assign permission';
$strings['Add Resource'] = 'Voeg een reserveringsbron toe';
$strings['Edit Resource'] = 'Bewerk de reserveringsbron';
$strings['Allowed'] = 'Toegestaan';
$strings['Notify user'] = 'Verwittig de gebruiker';
$strings['User Reservations'] = 'Gebruikerreservaties';
$strings['Date'] = 'Datum';
$strings['User'] = 'Gebruiker';
$strings['Email Users'] = 'E-mail gebruikers';
$strings['Subject'] = 'Onderwerp';
$strings['Message'] = 'Bericht';
$strings['Please select users'] = 'Gelieve de gebruikers te selecteren';
$strings['Send Email'] = 'Verzend e-mail';
$strings['problem sending email'] = 'Sorry, een probleem deed zich voor bij het verzenden van de mail. Probeer later opnieuw.';
$strings['The email sent successfully.'] = 'De e-mail is succesvol verzonden.';
$strings['do not refresh page'] = 'Gelieve deze pagina <u>niet</u> te herladen. De e-mail zal in dat geval opnieuw verzonden worden.';
$strings['Return to email management'] = 'Terugkeren naar e-mailbeheer';
$strings['Please select which tables and fields to export'] = 'Gelieve de tabellen en velden te selecteren die je wil uitvoeren:';
$strings['all fields'] = '- alle velden -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Plain text';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Uitvoer data';
$strings['Reset Password for'] = 'Wijzig wachtwoord voor %s';
$strings['Please edit your profile'] = 'Gelieve je profiel te wijzigen';
$strings['Please register'] = 'Gelieve te registreren';
$strings['Email address (this will be your login)'] = 'e-mailadres (dit wordt je login)';
$strings['Keep me logged in'] = 'Laat me ingelogd blijven <br/>(vereist cookies)';
$strings['Edit Profile'] = 'Bewerk profiel';
$strings['Register'] = 'Registreer';
$strings['Please Log In'] = 'Gelieve aan te melden';
$strings['Email address'] = 'e-mailadres';
$strings['Password'] = 'Wachtwoord';
$strings['First time user'] = 'Nieuwe gebruiker?';
$strings['Click here to register'] = 'Klik hier om je te registreren';
$strings['Register for phpScheduleIt'] = 'Registreer voor phpScheduleIt';
$strings['Log In'] = 'Aanmelden';
$strings['View Schedule'] = 'Bekijk de Planning';
$strings['View a read-only version of the schedule'] = 'Bekijk een alleen-lezen versie van de planning';
$strings['I Forgot My Password'] = 'Wachtwoord vergeten?';
$strings['Retreive lost password'] = 'Vergeten wachtwoord ophalen';
$strings['Get online help'] = 'Vraag online help';
$strings['Language'] = 'Taal';
$strings['(Default)'] = '(Standaard)';

$strings['My Announcements'] = 'Mijn aankondigingen';
$strings['My Reservations'] = 'Mijn Reservaties';
$strings['My Permissions'] = 'Mijn toegangsrechten';
$strings['My Quick Links'] = 'Mijn snelle verwijzingen';
$strings['Announcements as of'] = 'Aankondigingen sinds %s';
$strings['There are no announcements.'] = 'Geen aankondigingen.';
$strings['Resource'] = 'Reservatiebrond';
$strings['Created'] = 'Aangemaakt';
$strings['Last Modified'] = 'Laatst gewijzigd';
$strings['View this reservation'] = 'Bekijk deze reservatie';
$strings['Modify this reservation'] = 'Wijzig deze reservatie';
$strings['Delete this reservation'] = 'Verwijder deze reservatie';
$strings['Go to the Online Scheduler'] = 'Ga naar de Online Planner';
$strings['Change My Profile Information/Password'] = 'Wijzig mijn profiel/wachtwoord';
$strings['Manage My Email Preferences'] = 'Wijzig mijn e-mailvoorkeuren';
$strings['Manage Blackout Times'] = 'Beheer Blackout Tijden';
$strings['Mass Email Users'] = 'E-mail de gebruikers';
$strings['Search Scheduled Resource Usage'] = 'Zoek gepland gebruik van een reservatiebron';
$strings['Export Database Content'] = 'Exporteer de inhoud van de gegevensbank';
$strings['View System Stats'] = 'Systeemstatistieken bekijken';
$strings['Email Administrator'] = 'Beheerder e-mailen';

$strings['Email me when'] = 'Stuur me een e-mail wanneer :';
$strings['I place a reservation'] = 'ik een reservatie doe';
$strings['My reservation is modified'] = 'mijn reservatie wordt gewijzigd';
$strings['My reservation is deleted'] = 'mijn reservatie wordt verwijderd';
$strings['I prefer'] = 'Ik verkies:';
$strings['Your email preferences were successfully saved'] = 'Je e-mailvoorkeuren werden bewaard!';
$strings['Return to My Control Panel'] = 'Terugkeren naar Mijn Controlepaneel';

$strings['Please select the starting and ending times'] = 'Gelieve begin- en eindtijden te kiezen:';
$strings['Please change the starting and ending times'] = 'Gelieve begin- en eindtijden te wijzigen:';
$strings['Reserved time'] = 'Gereserveerde tijd:';
$strings['Minimum Reservation Length'] = 'Minimale reservatietijd:';
$strings['Maximum Reservation Length'] = 'Maximale reservatietijd:';
$strings['Reserved for'] = 'Gereserveerd voor:';
$strings['Will be reserved for'] = 'Zal gereserveerd worden voor:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Alle herhaalde reservaties in deze groep actualiseren?';
$strings['Delete?'] = 'Verwijder?';
$strings['Never'] = '-- Nooit --';
$strings['Days'] = 'Dagen';
$strings['Weeks'] = 'Weken';
$strings['Months (date)'] = 'Maanden (datum)';
$strings['Months (day)'] = 'Maanden (dag)';
$strings['First Days'] = 'First Days';
$strings['Second Days'] = 'Second Days';
$strings['Third Days'] = 'Third Days';
$strings['Fourth Days'] = 'Fourth Days';
$strings['Last Days'] = 'Last Days';
$strings['Repeat every'] = 'Herhaal elke:';
$strings['Repeat on'] = 'Herhaal op:';
$strings['Repeat until date'] = 'Herhaal tot datum:';
$strings['Choose Date'] = 'Kies datum';
$strings['Summary'] = 'Samenvatting';

$strings['View schedule'] = 'Bekijk Planning:';
$strings['My Reservations'] = 'Mijn Reservaties';
$strings['My Past Reservations'] = 'Mijn voorbije reservaties';
$strings['Other Reservations'] = 'Andere reservaties';
$strings['Other Past Reservations'] = 'Andere voorbije reservaties';
$strings['Blacked Out Time'] = 'Blacked Out Time';
$strings['Set blackout times'] = 'Blackout tijden instellen voor %s op %s'; 
$strings['Reserve on'] = 'Reserveer %s op %s';
$strings['Prev Week'] = '&laquo; Vorige week';
$strings['Jump 1 week back'] = 'Keer 1 week terug';
$strings['Prev days'] = '&#8249; Vorige %d dagen';
$strings['Previous days'] = '&#8249; Vorige %d dagen';
$strings['This Week'] = 'Deze week';
$strings['Jump to this week'] = 'Ga naar deze week';
$strings['Next days'] = 'Volgende %d dagen &#8250;';
$strings['Next Week'] = 'Volgende week &raquo;';
$strings['Jump To Date'] = 'Ga naar datum';
$strings['View Monthly Calendar'] = 'Bekijk de maandkalender';
$strings['Open up a navigational calendar'] = 'Open een navigeerbare kalender';

$strings['View stats for schedule'] = 'Bekijk statistieken voor planning:';
$strings['At A Glance'] = 'At A Glance';
$strings['Total Users'] = 'Aantal gebruikers:';
$strings['Total Resources'] = 'Aantal reservatiebronnen:';
$strings['Total Reservations'] = 'Aantal reservaties:';
$strings['Max Reservation'] = 'Max reservatie:';
$strings['Min Reservation'] = 'Min reservatie:';
$strings['Avg Reservation'] = 'Gemiddelde reservatie:';
$strings['Most Active Resource'] = 'Meest gebruikte reserveringsbron:';
$strings['Most Active User'] = 'Meest actieve gebruiker:';
$strings['System Stats'] = 'Systeemstatistieken';
$strings['phpScheduleIt version'] = 'phpScheduleIt versie:';
$strings['Database backend'] = 'Gegevensbank backend:';
$strings['Database name'] = 'Naam gegevensbank:';
$strings['PHP version'] = 'PHP versie:';
$strings['Server OS'] = 'Server OS:';
$strings['Server name'] = 'Servernaam:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt hoofdmap:';
$strings['Using permissions'] = 'Toegangsrechten gebruiken:';
$strings['Using logging'] = 'Logbestanden aanmaken:';
$strings['Log file'] = 'Logbestand:';
$strings['Admin email address'] = 'e-mailadres beheerder:';
$strings['Tech email address'] = 'e-mailadres technicus:';
$strings['CC email addresses'] = 'CC e-mailadressen:';
$strings['Reservation start time'] = 'Begintijd reservatie:';
$strings['Reservation end time'] = 'Eindtijd reservatie:';
$strings['Days shown at a time'] = 'Aantal dagen weergeven:';
$strings['Reservations'] = 'Reservaties';
$strings['Return to top'] = 'Naar boven';
$strings['for'] = 'voor';

$strings['Select Search Criteria'] = 'Selecteer zoekcriteria';
$strings['Schedules'] = 'Planningen:';
$strings['All Schedules'] = 'Alle planningen';
$strings['Hold CTRL to select multiple'] = 'Druk CTRL-toets in om meerdere te selecteren';
$strings['Users'] = 'Gebruikers:';
$strings['All Users'] = 'Alle gebruikers';
$strings['Resources'] = 'Reservatiebronnen:';
$strings['All Resources'] = 'Alle reservatiebronnen';
$strings['Starting Date'] = 'Begindatum:';
$strings['Ending Date'] = 'Einddatum:';
$strings['Starting Time'] = 'Begintijd:';
$strings['Ending Time'] = 'Eindtijd:';
$strings['Output Type'] = 'Type uitvoer:';
$strings['Manage'] = 'Beheer';
$strings['Total Time'] = 'Totale tijd';
$strings['Total hours'] = 'Aantal uren:';
$strings['% of total resource time'] = '% van de totale reservatietijd';
$strings['View these results as'] = 'Bekijk de resultaten als:';
$strings['Edit this reservation'] = 'Bewerk deze reservatie';
$strings['Search Results'] = 'Zoekresultaten';
$strings['Search Resource Usage'] = 'Zoek het gebruik van een reservatiebron';
$strings['Search Results found'] = 'Zoekresultaten: %d reservaties gevonden';
$strings['Try a different search'] = 'Probeer een andere zoekopdracht';
$strings['Search Run On'] = 'Zoeken op:';
$strings['Member ID'] = 'Lid ID';
$strings['Previous User'] = '&laquo; Vorige gebruiker';
$strings['Next User'] = 'Volgende gebruiker &raquo;';

$strings['No results'] = 'Geen resultaten';
$strings['That record could not be found.'] = 'Dit record kon niet gevonden worden.';
$strings['This blackout is not recurring.'] = 'Deze blackout-tijd wordt niet herhaald.';
$strings['This reservation is not recurring.'] = 'Deze reservatie wordt niet herhaald.';
$strings['There are no records in the table.'] = 'Er zijn geen records in de %s tabel.';
$strings['You do not have any reservations scheduled.'] = 'Je hebt geen reservaties gepland.';
$strings['You do not have permission to use any resources.'] = 'Je hebt geen toestemming om een reservatiebron te gebruiken.';
$strings['No resources in the database.'] = 'Geen reservatiebronnen in de gegevensbank.';
$strings['There was an error executing your query'] = 'Er is een fout opgetreden bij het uitvoeren van je zoekopdracht:';

$strings['That cookie seems to be invalid'] = 'Deze cookie lijkt ongeldig';
$strings['We could not find that email in our database.'] = 'Dit e-mailadres kon niet gevonden worden in de gegevensbank.';
$strings['That password did not match the one in our database.'] = 'Het wachtwoord stemt niet overeen met dat in de gegevensbank.';
$strings['You can try'] = '<br />Je kan proberen:<br />Een e-mailadres registreren.<br />Or:<br />Probeer je nogmaals aan te melden.';
$strings['A new user has been added'] = 'Een nieuwe gebruiker is toegevoegd';
$strings['You have successfully registered'] = 'Je bent met succes geregistreerd!';
$strings['Continue'] = 'Ga verder...';
$strings['Your profile has been successfully updated!'] = 'Je profiel is met succes geactualiseerd!';
$strings['Please return to My Control Panel'] = 'Keer terug naar Mijn Controlepaneel';
$strings['Valid email address is required.'] = '- Een geldig e-mailadres is vereist.';
$strings['First name is required.'] = '- Voornaam is vereist.';
$strings['Last name is required.'] = '- Naam is vereist.';
$strings['Phone number is required.'] = '- Telefoonnummer is vereist.';
$strings['That email is taken already.'] = '- Dit e-mailadres is reeds in gebruik.<br />Probeer nogmaals met een ander e-mailadres.';
$strings['Min 6 character password is required.'] = '- Wachtwoord van minimaal 6 tekens is vereist.';
$strings['Passwords do not match.'] = '- Wachtwoorden zijn niet gelijk.';

$strings['Per page'] = 'Per pagina:';
$strings['Page'] = 'Pagina:';

$strings['Your reservation was successfully created'] = 'Je reservatie is met succes aangemaakt';
$strings['Your reservation was successfully modified'] = 'Je reservatie is met succes gewijzigd';
$strings['Your reservation was successfully deleted'] = 'Je reservatie is met succes verwijderd';
$strings['Your blackout was successfully created'] = 'Je blackout is met succes aangemaakt';
$strings['Your blackout was successfully modified'] = 'Je blackout is met succes gewijzigd';
$strings['Your blackout was successfully deleted'] = 'Je blackout is met succes verwijderd';
$strings['for the follwing dates'] = 'voor de volgende data:';
$strings['Start time must be less than end time'] = 'Begintijd dient voor de eindtijd te komen.';
$strings['Current start time is'] = 'Huidige begintijd is:';
$strings['Current end time is'] = 'Huidige eindtijd is:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'Duur van de reservatie valt buiten de toegestane termijn voor deze reserveringsbron.';
$strings['Your reservation is'] = 'Je reservatie is:';
$strings['Minimum reservation length'] = 'Minimale reservatietijd:';
$strings['Maximum reservation length'] = 'Maximale reservatietijd:';
$strings['You do not have permission to use this resource.'] = 'Je hebt geen toegang tot deze reserveringsbron.';
$strings['reserved or unavailable'] = '%s van %s tot %s is gereserveerd of onbeschikbaar.';
$strings['Reservation created for'] = 'Reservatie aangemaakt voor %s';
$strings['Reservation modified for'] = 'Reservatie gewijzigd voor %s';
$strings['Reservation deleted for'] = 'Reservatie verwijderd voor %s';
$strings['created'] = 'aangemaakt';
$strings['modified'] = 'gewijzigd';
$strings['deleted'] = 'verwijderd';
$strings['Reservation #'] = 'Reservatie #';
$strings['Contact'] = 'Contact';
$strings['Reservation created'] = 'Reservatie aangemaakt';
$strings['Reservation modified'] = 'Reservatie gewijzigd';
$strings['Reservation deleted'] = 'Reservatie verwijderd';

$strings['Reservations by month'] = 'Reservaties volgens maand';
$strings['Reservations by day of the week'] = 'Reservaties volgens dag van de week';
$strings['Reservations per month'] = 'Reservatie per maand';
$strings['Reservations per user'] = 'Reservaties per gebruiker';
$strings['Reservations per resource'] = 'Reservaties per reservatiebron';
$strings['Reservations per start time'] = 'Reservations per begintijd';
$strings['Reservations per end time'] = 'Reservations per eindtijd';
$strings['[All Reservations]'] = '[Alle reservaties]';

$strings['Permissions Updated'] = 'Toegangsrechten aangepast';
$strings['Your permissions have been updated'] = 'Je %s toegangsrechten werden aangepast';
$strings['You now do not have permission to use any resources.'] = 'Nu heb je geen rechten om een reserveringsbron te gebruiken.';
$strings['You now have permission to use the following resources'] = 'Nu heb je toegang tot de volgende reserveringsbronnen:';
$strings['Please contact with any questions.'] = 'Contacteer me voor %s vragen.';
$strings['Password Reset'] = 'Wachtwoord wijzigen';

$strings['This will change your password to a new, randomly generated one.'] = 'Dit zal je wachtwoord wijzigen in een nieuw en willekeurig gekozen.';
$strings['your new password will be set'] = 'Als je e-mailadres is ingevuld en je op  "Wijzig wachtwoord" klikt, zal je wachtwoord meteen gewijzigd en via e-mail toegestuurd worden.';
$strings['Change Password'] = 'Wijzig wachtwoord';
$strings['Sorry, we could not find that user in the database.'] = 'Sorry, deze gebruiker is niet terug te vinden in de gegevensbank.';
$strings['Your New Password'] = 'Je nieuwe %s wachtwoord';
$strings['Your new passsword has been emailed to you.'] = 'Success!<br />
    			Je nieuwe wachtwoord werd je toegestuurd.<br />
    			Gelieve je mailbox te checken voor je nieuwe wachtwoord, <a href="index.php">meld je aan</a>
    			met dit nieuwe wachtwoord en wijzig het meteen via de link &quot;Wijzig mijn profiel/Wachtwoord&quot;
    			in Mijn Controlepaneel.';

$strings['You are not logged in!'] = 'Je bent niet aangemeld!';

$strings['Setup'] = 'Instellen';
$strings['Please log into your database'] = 'Meld je aan bij je gegevensbank';
$strings['Enter database root username'] = 'Vul de gebruikersnaam voor je gegevensbank in:';
$strings['Enter database root password'] = 'Vul het wachtwoord voor de gegevensbank in:';
$strings['Login to database'] = 'Meld je aan bij de gegevensbank';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'Root gebruiker is <b>niet</b> vereist. Elke gebruiker die tabellen kan aanmaken in de gegevensbank wordt aanvaard.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Dit zal de nodige gegevensbank en tabellen voor phpScheduleIt instellen.';
$strings['It also populates any required tables.'] = 'Het vult ook alle vereiste tabellen in.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Waarschuwing: DIT ZAL ALLE GEGEVENS VERWIJDEREN UIT EERDER GEINSTALLEERDE phpScheduleIt GEGEVENSBANKEN !';
$strings['Not a valid database type in the config.php file.'] = 'Geen geldig type gegevensbank in het config.php bestand.';
$strings['Database user password is not set in the config.php file.'] = 'Wachtwoord gebruiker gegevensbank is noet opgegeven in het config.php bestand.';
$strings['Database name not set in the config.php file.'] = 'Naam gegevensbank niet opgegeven in het config.php bestand.';
$strings['Successfully connected as'] = 'Succesvol verbonden als';
$strings['Create tables'] = 'Creeer tabellen &gt;';
$strings['There were errors during the install.'] = 'Er zijn fouten opgetreden tijdens de installatie. Mogelijk zal phpScheduleIt werken als het kleine fouten betreft.<br/><br/>'
	. 'Meld je vragen op het forun van <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'Je hebt phpScheduleIt met succes geinstalleerd en kan het nu beginnen gebruiken.';
$strings['Thank you for using phpScheduleIt'] = 'Vergeet zeker niet de MAP \'install\' VOLLEDIG TE VERWIJDEREN.'
	. ' Dit is cruciaal omdat je wachtwoord van de gegevensbank en andere gevoelige informatie in dit bestand staan.'
	. ' Wie dit nalaat riskeert de deur van je gegevensbank voor inbrekers op een kier te laten staan!'
	. '<br /><br />'
	. 'Bedankt om phpScheduleIt te gebruiken!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'Dit zal het programma phpScheduleIt actualiseren van versie 0.9.3 naar 1.0.0.';
$strings['There is no way to undo this action'] = 'Je kan deze actie niet ongedaan maken!';
$strings['Click to proceed'] = 'Klik om verder te gaan';
$strings['This version has already been upgraded to 1.0.0.'] = 'Deze versie is reeds geupdatet naar 1.0.0.';
$strings['Please delete this file.'] = 'Gelieve dit bestand te verwijderen.';
$strings['Successful update'] = 'De update is gelukt';
$strings['Patch completed successfully'] = 'De installatie van de patch is gelukt';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'Dit zal de vereiste velden voor phpScheduleIt 1.0.0 invullen en een gegevenslek in versie 0.9.9. dichten'
		. '<br />Dit is enkel vereist als je een manuele SQL update uitvoerde of wil upgraden van versie 0.9.9';

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s\n\r\n"
				. "Je bent met succes geregistreerd met de volgende informatie:\r\n"
				. "Naam: %s %s\r\n"
				. "Telefoon: %s\r\n"
				. "Organisatie: %s\r\n"
				. "Functie: %s\r\n\r\n"
				. "Gelieve je op deze plaats aan te melden voor de planner:\r\n"
				. "%s\r\n\r\n"
				. "Je kan verwijzingen naar de online planner vinden en je profiel wijzigen op Mijn Controlepaneel.\r\n\r\n"
				. "Gelieve elke vraag omtrent reservatie of reservatiebron te richten aan %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Administrator,\r\n\r\n"
					. "A new user has registered with the following information:\r\n"
					. "Email: %s\r\n"
					. "Name: %s %s\r\n"
					. "Phone: %s\r\n"
					. "Institution: %s\r\n"
					. "Position: %s\r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Je hebt met succes %s gereserveerd #%s.\r\n\r\n<br/><br/>"
			. "Gelieve dit reservatienummer te vermelden wanneer je de beheerder contacteert met vragen.\r\n\r\n<br/><br/>"
			. "Een reservatie op %s tussen %s en %s voor %s"
			. " gelegen te %s is %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "Deze reservatie werd herhaald op volgende data:\r\n<br/>";
$email['reservation_activity_3'] = "Alle herhaalde reservaties in deze groep werden eveneens %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "De volgende commentaar werd toegevoegd aan deze reservatie:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Indien dit een vergissing is, gelieve de beheerder te contacteren op: %s"
			. " of door te bellen %s.\r\n\r\n<br/><br/>"
			. "Je reservatie kan op elk moment bekeken of gewijzigd worden door "
			. " je aan te melden %s op:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "Gelieve alle technische vragen te richten aan <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Je %s wachtwoord werd gewijzigd door de beheerder.\r\n\r\n"
			. "Je tijdelijke wachtwoord is:\r\n\r\n %s\r\n\r\n"
			. "Gebruik dit wachtwoord (kopieer en plak om zeker te zijn dat het correct is) om je aan te melden %s bij %s"
			. " en wijzig het onmiddellijk via de link 'Wijzig mijn profiel/wachtwoord' onder de titel Mijn snelle verwijzingen.\r\n\r\n"
			. "Gelieve %s te contacteren voor al je vragen.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Je nieuwe wachtwoord voor je %s account is:\r\n\r\n"
            . "%s\r\n\r\n"
            . "Meld je aan als bij %s "
            . "met dit nieuwe wachtwoord "
            . "(kopieer en plak om zeker te zijn dat het correct is) "
            . "en wijzig meteen het wachtwoord door te klikken op de "
            . "Wijzig mijn profiel/wachtwoord "
            . "verwijzing in Mijn Controlepaneel.\r\n\r\n"
            . "Gelieve al je vragen te richten naar %s.";
?>
