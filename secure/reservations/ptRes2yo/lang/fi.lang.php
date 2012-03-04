<?php
/**
* Finnish (fi) translation file.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator Veli-Matti Koukeri <vmkoukeri@saunalahti.fi>
* @version 08-05-04
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
$days_full = array('sunnuntai', 'maanantai', 'tiistai', 'keskiviikko', 'torstai', 'perjantai', 'lauantai');
// The three letter abbreviation
$days_abbr = array('sun', 'maa', 'tii', 'kes', 'tor', 'per', 'lau');
// The two letter abbreviation
$days_two  = array('su', 'ma', 'ti', 'ke', 'to', 'pe', 'la');
// The one letter abbreviation
$days_letter = array('S', 'M', 'T', 'K', 'T', 'P', 'L');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('tammikuu', 'helmikuu', 'maaliskuu', 'huhtikuu', 'toukokuu', 'kes�kuuta', 'hein�kuu', 'elokuu', 'syyskuu', 'lokakuu', 'marraskuu', 'joulukuu');
// The three letter month name
$months_abbr = array('tammi', 'helmi', 'maalis', 'huhti', 'touko', 'kes�', 'hein�', 'elo', 'syys', 'loka', 'marras', 'joulu');

// All letters of the alphabet starting with A and ending with Z
$letters = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

/***
  DATE FORMATTING
  All of the date formatting must use the PHP strftime() syntax
  You can include any text/HTML formatting in the translation
***/
// General date formatting used for all date display unless otherwise noted
$dates['general_date'] = '%d.%m.%Y';
// General datetime formatting used for all datetime display unless otherwise noted
// The hour:minute:second will always follow this format
$dates['general_datetime'] = '%d.%m.%Y @';
// Date in the reservation notification popup and email
$dates['res_check'] = '%A %d.%m.%Y';
// Date on the scheduler that appears above the resource links
$dates['schedule_daily'] = '%A,<br/>%d.%m.%Y';
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
$strings['hours'] = 'tuntia';
$strings['minutes'] = 'minuuttia';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'pp';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'vvvv';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Yll�pit�j�';
$strings['Welcome Back'] = 'Tervetuloa, %s';
$strings['Log Out'] = 'Kirjaudu Ulos';
$strings['My Control Panel'] = 'Ohjauspaneeli';
$strings['Help'] = 'Ohjeet';
$strings['Manage Schedules'] = 'Aikataulut';
$strings['Manage Users'] = 'K�ytt�j�t';
$strings['Manage Resources'] = 'Resurssit';
$strings['Manage User Training'] = 'K�ytt�j�koulutukset';
$strings['Manage Reservations'] = 'Varaukset';
$strings['Email Users'] = 'L�het� s�hk�postia';
$strings['Export Database Data'] = 'Vie tietokannan tiedot';
$strings['Reset Password'] = 'Palauta salasana';
$strings['System Administration'] = 'J�rjestelm�nhallinta';
$strings['Successful update'] = 'P�ivitys onnistui!';
$strings['Update failed!'] = 'P�ivitys ep�onnistui!';
$strings['Manage Blackout Times'] = 'K�ytt�katkot';
$strings['Forgot Password'] = 'Unohtunut salasana';
$strings['Manage My Email Contacts'] = 'Osoitekirja';
$strings['Choose Date'] = 'Valitse p�iv�m��r�';
$strings['Modify My Profile'] = 'Muuta profiilia';
$strings['Register'] = 'Rekister�idy';
$strings['Processing Blackout'] = 'K�sitell��n k�yt�st�poistoa';
$strings['Processing Reservation'] = 'K�sitell��n varausta';
$strings['Online Scheduler [Read-only Mode]'] = 'Online-aikataulu [Vain lukuoikeus]';
$strings['Online Scheduler'] = 'Online-aikataulu';
$strings['phpScheduleIt Statistics'] = 'phpScheduleIt -tilastot';
$strings['User Info'] = 'K�ytt�j�n tiedot:';

$strings['Could not determine tool'] = 'Toimintoa ei voitu m��ritt��. Palaa ohjauspaneeliin, ja yrit� uudelleen.';
$strings['This is only accessable to the administrator'] = 'Vain yll�pit�j�n k�yt�ss�.';
$strings['Back to My Control Panel'] = 'Takaisin ohjauspaneeliin.';
$strings['That schedule is not available.'] = 'Aikataulu ei ole saatavissa.';
$strings['You did not select any schedules to delete.'] = 'Et valinnut poistettavia aikatauluja.';
$strings['You did not select any members to delete.'] = 'Et valinnut poistettavia osallistujia.';
$strings['You did not select any resources to delete.'] = 'Et valinnut poistettavia resursseja.';
$strings['Schedule title is required.'] = 'Aikataulun otsikko on pakollinen tieto.';
$strings['Invalid start/end times'] = 'Ep�kelpo aloitus-/lopetus -ajankohta';
$strings['View days is required'] = 'N�ytett�v� ajanjakso on pakollinen tieto.';
$strings['Day offset is required'] = 'P�iv�siirtym� on pakollinen tieto.';
$strings['Admin email is required'] = 'Yll�pit�j�n s�hk�postiosoite on pakollinen tieto.';
$strings['Resource name is required.'] = 'Resurssin nimi on pakollinen tieto.';
$strings['Valid schedule must be selected'] = 'Valitse kelvollinen aikataulu.';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'V�himm�isvarausajan on oltava v�hint��n yht�suuri, kuin enimm�isvarausaika.';
$strings['Your request was processed successfully.'] = 'Sy�tt�m�si tieto k�siteltiin onnistuneesti.';
$strings['Go back to system administration'] = 'Palaa j�rjestelm�nhallintaan';
$strings['Or wait to be automatically redirected there.'] = 'tai odota uudelleenohjausta.';
$strings['There were problems processing your request.'] = 'Sy�tt�m�si tiedon k�sittely ei onnistunut.';
$strings['Please go back and correct any errors.'] = 'Ole hyv�, ja korjaa virheet.';
$strings['Login to view details and place reservations'] = 'Kirjaudu sis��n n�hd�ksesi lis�tietoja, ja lis�t�ksesi varauksia.';
$strings['Memberid is not available.'] = 'K�ytt�j�-id:t� %s ei l�ytynyt.';

$strings['Schedule Title'] = 'Aikataulun otsikko';
$strings['Start Time'] = 'Aloitusaika';
$strings['End Time'] = 'Lopetusaika';
$strings['Time Span'] = 'Ajanjakso';
$strings['Weekday Start'] = 'Aloitusp�iv�';
$strings['Admin Email'] = 'Yll�pit�j�n s�hk�postiosoite';

$strings['Default'] = 'Vakio';
$strings['Reset'] = 'Palauta';
$strings['Edit'] = 'Muokkaa';
$strings['Delete'] = 'Poista';
$strings['Cancel'] = 'Peruuta';
$strings['View'] = 'N�yt�';
$strings['Modify'] = 'Muuta';
$strings['Save'] = 'Tallenna';
$strings['Back'] = 'Edellinen';
$strings['Next'] = 'Seuraava';
$strings['Close Window'] = 'Sulje ikkuna';
$strings['Search'] = 'Etsi';
$strings['Clear'] = 'Tyhjenn�';

$strings['Days to Show'] = 'N�ytett�v� ajanjakso';
$strings['Reservation Offset'] = 'Varaussiirtym�';
$strings['Hidden'] = 'Piilota';
$strings['Show Summary'] = 'N�yt� yhteenveto';
$strings['Add Schedule'] = 'Lis�� aikatauluun';
$strings['Edit Schedule'] = 'Muokkaa aikataulua';
$strings['No'] = 'Ei';
$strings['Yes'] = 'Kyll�';
$strings['Name'] = 'Nimi';
$strings['First Name'] = 'Etunimi';
$strings['Last Name'] = 'Sukunimi';
$strings['Resource Name'] = 'Resurssin nimi';
$strings['Email'] = 'S�hk�postiosoite';
$strings['Institution'] = 'J�rjest�/Yritys';
$strings['Phone'] = 'Puhelin';
$strings['Password'] = 'Salasana';
$strings['Permissions'] = 'Oikeudet';
$strings['View information about'] = 'N�yt� tiedot: %s %s';
$strings['Send email to'] = 'L�het� s�hk�postia k�ytt�j�lle %s %s';
$strings['Reset password for'] = 'Nollaa k�ytt�j�n %s %s salasana';
$strings['Edit permissions for'] = 'Muokkaa k�ytt�j�n %s %s oikeuksia';
$strings['Position'] = 'Ty�nkuva';
$strings['Password (6 char min)'] = 'Salasana (v�hint��n kuusi kirjainta)';
$strings['Re-Enter Password'] = 'Sy�t� salasana uudelleen';

$strings['Sort by descending last name'] = 'J�rjest� k��nt�en, sukunimen mukaan';
$strings['Sort by descending email address'] = 'J�rjest� k��nt�en, s�hk�postiosoitteen mukaan';
$strings['Sort by descending institution'] = 'J�rjest� k��nt�en, j�rjest�n/yrityksen mukaan';
$strings['Sort by ascending last name'] = 'J�rjest� sukunimen mukaan';
$strings['Sort by ascending email address'] = 'J�rjest� s�hk�postiosoitteen mukaan';
$strings['Sort by ascending institution'] = 'J�rjeste� j�rjest�n/yrityksen mukaan';
$strings['Sort by descending resource name'] = 'J�rjest� k��nt�en resurssin nimen mukaan';
$strings['Sort by descending location'] = 'J�rjest� k��nt�en sijainnin mukaan';
$strings['Sort by descending schedule title'] = 'J�rjest� k��nt�en aikataulun mukaan';
$strings['Sort by ascending resource name'] = 'J�rjest� resurssin nimen mukaan';
$strings['Sort by ascending location'] = 'J�rjest� sijainnin mukaan';
$strings['Sort by ascending schedule title'] = 'J�rjest� aikataulun otsikon mukaan';
$strings['Sort by descending date'] = 'J�rjest� k��nt�en p�iv�m��r�n mukaan';
$strings['Sort by descending user name'] = 'J�rjest� k��nt�en k�ytt�j�n nimen mukaan';
$strings['Sort by descending start time'] = 'J�rjest� k��nt�en aloitusajankohdan mukaan';
$strings['Sort by descending end time'] = 'J�rjest� k��nt�en lopetusajankohdan mukaan';
$strings['Sort by ascending date'] = 'J�rjest� p�iv�m��r�n mukaan';
$strings['Sort by ascending user name'] = 'J�rjest� k�ytt�j�n nimen mukaan';
$strings['Sort by ascending start time'] = 'J�rjest� aloitusajankohdan mukaan';
$strings['Sort by ascending end time'] = 'J�rjest� lopetusajankohdan mukaan';
$strings['Sort by descending created time'] = 'J�rjest� k��nt�en luontiajankohdan mukaan';
$strings['Sort by ascending created time'] = 'J�rjest� luontiajankohdan mukaan';
$strings['Sort by descending last modified time'] = 'J�rjest� k��nt�en muokkausajankohdan mukaan';
$strings['Sort by ascending last modified time'] = 'J�rjest� muokkausajankohdan mukaan';

$strings['Search Users'] = 'Etsi k�ytt�ji�';
$strings['Location'] = 'Sijainti';
$strings['Schedule'] = 'Aikataulu';
$strings['Notes'] = 'Muistiinpanot';
$strings['Status'] = 'Tila';
$strings['All Schedules'] = 'Kaikki aikataulut';
$strings['All Resources'] = 'Kaikki resurssit';
$strings['All Users'] = 'Kaikki k�ytt�j�t';

$strings['Edit data for'] = 'Muokkaa tietoja: %s';
$strings['Active'] = 'Aktiivinen';
$strings['Inactive'] = 'Inaktiivinen';
$strings['Toggle this resource active/inactive'] = 'Aseta aktiiviseksi/inaktiiviseksi';
$strings['Minimum Reservation Time'] = 'V�himm�isvarausaika';
$strings['Maximum Reservation Time'] = 'Enimm�isvarausaika';
$strings['Auto-assign permission'] = 'Aseta oikeudet automaattisesti';
$strings['Add Resource'] = 'Lis�� resurssi';
$strings['Edit Resource'] = 'Muokkaa resurssia';
$strings['Allowed'] = 'Sallittu';
$strings['Notify user'] = 'Huomauta k�ytt�j��';
$strings['User Reservations'] = 'K�ytt�j�varaukset';
$strings['Date'] = 'P�iv�m��r�';
$strings['User'] = 'K�ytt�j�';
$strings['Subject'] = 'Aihe';
$strings['Message'] = 'Viesti';
$strings['Please select users'] = 'Valitse k�ytt�j�t';
$strings['Send Email'] = 'L�het� s�hk�postia';
$strings['problem sending email'] = 'S�hk�postin l�hetys ep�onnistui surkeasti. Voit yritt�� uudelleen, mutta perinteisten viestint�menetelmien k�ytt� on luultavasti nopeampaa.';
$strings['The email sent successfully.'] = 'S�hk�postin l�hetys onnistui.';
$strings['do not refresh page'] = '<u>�l�</u> lataa t�t� sivua uudelleen. Uudelleenlataus l�hett�� viestin uudelleen.';
$strings['Return to email management'] = 'Palaa s�hk�postien hallintaan.';
$strings['Please select which tables and fields to export'] = 'Valitse viet�v�t taulut ja kent�t:';
$strings['all fields'] = '- kaikki kent�t -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Plain text';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Vie tietoja';
$strings['Reset Password for'] = 'Nollaa k�ytt�j�n %s salasana';
$strings['Please edit your profile'] = 'Ole hyv�, ja muokkaa profiiliasi';
$strings['Please register'] = 'Ole hyv�, ja rekister�idy';
$strings['Email address (this will be your login)'] = 'S�hk�postiosoite (toimii my�s k�ytt�j�tunnuksenasi)';
$strings['Keep me logged in'] = 'Pysyv� sis��nkirjautuminen <br/>(vaatii keksej�)';
$strings['Edit Profile'] = 'Muokkaa profiilia';
$strings['Please Log In'] = 'Kirjaudu sis��n';
$strings['Email address'] = 'S�hk�postiosoite';
$strings['First time user'] = 'Ensimm�inen k�ytt�kertasi?';
$strings['Click here to register'] = 'Klikkaa t�ss� rekister�ity�ksesi';
$strings['Register for phpScheduleIt'] = 'Rekister�idy phpScheduleIt-k�ytt�j�ksi';
$strings['Log In'] = 'Kirjaudu sis��n';
$strings['View Schedule'] = 'N�yt� aikataulu';
$strings['View a read-only version of the schedule'] = 'N�yt� suojattu versio aikataulusta';
$strings['I Forgot My Password'] = 'Unohdin salasanani';
$strings['Retreive lost password'] = 'Palauta unohtunut salasana';
$strings['Get online help'] = 'Get online help';
$strings['Language'] = 'Kieli';
$strings['(Default)'] = '(Vakio)';

$strings['My Announcements'] = 'Ilmoitukseni';
$strings['My Reservations'] = 'Varaukseni';
$strings['My Permissions'] = 'Oikeuteni';
$strings['My Quick Links'] = 'Pikalinkit';
$strings['Announcements as of'] = '%s menness� ilmestyneet ilmoitukset';
$strings['There are no announcements.'] = 'Ei ilmoituksia';
$strings['Resource'] = 'Resurssi';
$strings['Created'] = 'Luotu';
$strings['Last Modified'] = 'Muutettu viimeksi';
$strings['View this reservation'] = 'N�yt� varaus';
$strings['Modify this reservation'] = 'Muokkaa varausta';
$strings['Delete this reservation'] = 'Poista varaus';
$strings['Go to the Online Scheduler'] = 'Online-aikatauluun';
$strings['Change My Profile Information/Password'] = 'Muuta profiilin tietoja/salasanaa';
$strings['Manage My Email Preferences'] = 'Muokkaa s�hk�postiasetuksia';
$strings['Mass Email Users'] = 'L�het� s�hk�postia monelle k�ytt�j�lle';
$strings['Search Scheduled Resource Usage'] = 'Search Scheduled Resource Usage';
$strings['Export Database Content'] = 'Vie tietokannan sis�lt�';
$strings['View System Stats'] = 'N�yt� j�rjestelm�n tilastot';
$strings['Email Administrator'] = 'L�het� postia yll�pit�j�lle';

$strings['Email me when'] = 'L�het� minulle postia, jos:';
$strings['I place a reservation'] = 'Teen varauksen';
$strings['My reservation is modified'] = 'Varaustani on muokattu';
$strings['My reservation is deleted'] = 'Varaukseni on postettu';
$strings['I prefer'] = 'Mieluiten:';
$strings['Your email preferences were successfully saved'] = 'S�hk�postin asetukset tallennettu!';
$strings['Return to My Control Panel'] = 'Palaa ohjauspaneeliin';

$strings['Please select the starting and ending times'] = 'Valitse aloitus- ja lopetusajankohdat:';
$strings['Please change the starting and ending times'] = 'Muuta aloitus -ja lopetusajankohtia:';
$strings['Reserved time'] = 'Varausaika:';
$strings['Minimum Reservation Length'] = 'V�himm�isvarausaika:';
$strings['Maximum Reservation Length'] = 'Enimm�isvarausaika:';
$strings['Reserved for'] = 'Varattu:';
$strings['Will be reserved for'] = 'Tulee olemaan varattuna:';
$strings['N/A'] = '-- Tyhj� --';
$strings['Update all recurring records in group'] = 'P�ivit� kaikki ryhm�n uusiutuvat tiedot?';
$strings['Delete?'] = 'Poista?';
$strings['Never'] = '-- Ei koskaan --';
$strings['Days'] = 'P�iv��';
$strings['Weeks'] = 'Viikkoa';
$strings['Months (date)'] = 'Kuukausia (pvm)';
$strings['Months (day)'] = 'Kuukausia (p�iv�)';
$strings['First Days'] = 'Ensimm�iset p�iv�t';
$strings['Second Days'] = 'Toiset p�iv�t';
$strings['Third Days'] = 'Kolmannet p�iv�t';
$strings['Fourth Days'] = 'Nelj�nnet p�iv�t';
$strings['Last Days'] = 'Viimeiset p�iv�t';
$strings['Repeat every'] = 'Toista joka:';
$strings['Repeat on'] = 'Toista aina:';
$strings['Repeat until date'] = 'Toista kunnes:';
$strings['Summary'] = 'Yhteenveto';

$strings['View schedule'] = 'N�yt� aikataulu:';
$strings['My Past Reservations'] = 'Menneet varaukset';
$strings['Other Reservations'] = 'Muut varaukset';
$strings['Other Past Reservations'] = 'Muut menneet varaukset';
$strings['Blacked Out Time'] = 'Poissa k�yt�st� ollut aika';
$strings['Set blackout times'] = 'Aseta pois k�yt�st� %s ajaksi, aina %s';
$strings['Reserve on'] = 'Varaa %s aina %s';
$strings['Prev Week'] = '&laquo; Ed. Viikko';
$strings['Jump 1 week back'] = 'Viikko taaksep�in';
$strings['Prev days'] = '&#8249; Ed. %d p�iv��';
$strings['Previous days'] = '&#8249; Edelliset %d p�iv��';
$strings['This Week'] = 'T�m� viikko';
$strings['Jump to this week'] = 'Siirry t�h�n viikkoon';
$strings['Next days'] = 'Seuraavat %d p�iv�� &#8250;';
$strings['Next Week'] = 'Seuraava viikko &raquo;';
$strings['Jump To Date'] = 'Siirry p�iv��n';
$strings['View Monthly Calendar'] = 'N�yt� kuukausikalenteri';
$strings['Open up a navigational calendar'] = 'Avaa navigointikalenteri';

$strings['View stats for schedule'] = 'N�yt� aikataulun tilastot:';
$strings['At A Glance'] = 'Mulkaisu';
$strings['Total Users'] = 'K�ytt�ji� yhteens�:';
$strings['Total Resources'] = 'Resursseja yhteens�:';
$strings['Total Reservations'] = 'Varauksia yhteens�:';
$strings['Max Reservation'] = 'Varauksia enint��n:';
$strings['Min Reservation'] = 'Varauksia v�hint��:';
$strings['Avg Reservation'] = 'Varauksia keskim��rin:';
$strings['Most Active Resource'] = 'Akiivisin resurssi:';
$strings['Most Active User'] = 'Aktiivisimmat k�ytt�j�t:';
$strings['System Stats'] = 'J�rjestelm�tilasto:';
$strings['phpScheduleIt version'] = 'phpScheduleIt version:';
$strings['Database backend'] = 'Tietokantapalvelin:';
$strings['Database name'] = 'Tietokannan nimi:';
$strings['PHP version'] = 'PHP:n versio:';
$strings['Server OS'] = 'Palvelimen k�ytt�j�rjestelm�:';
$strings['Server name'] = 'Palvelimen nimi:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt root-hakemisto:';
$strings['Using permissions'] = 'K�ytet��n oikeuksia:';
$strings['Using logging'] = 'K�ytet��n sis��nkirjausta:';
$strings['Log file'] = 'Logitiedosto:';
$strings['Admin email address'] = 'Yll�pit�j�n s�hk�postiosoite:';
$strings['Tech email address'] = 'Teknisen avun s�hk�postiosoite:';
$strings['CC email addresses'] = '"CC"-s�hk�postiosoitteet:';
$strings['Reservation start time'] = 'Varauksen aloitusaika:';
$strings['Reservation end time'] = 'Varauksen lopetusaika:';
$strings['Days shown at a time'] = 'P�iv�t n�ytet��n ajalla:';
$strings['Reservations'] = 'Varaukset';
$strings['Return to top'] = 'Palaa yl�reunaan';
$strings['for'] = 'kohteelle';

$strings['Select Search Criteria'] = 'Valitse hakuehdot:';
$strings['Schedules'] = 'Aikataulut:';
$strings['Hold CTRL to select multiple'] = 'Pid� pohjassa CTRL-n�pp�int� valitaksesi useampia';
$strings['Users'] = 'K�ytt�j�t';
$strings['Resources'] = 'Resurssit:';
$strings['Starting Date'] = 'Aloitusp�iv�:';
$strings['Ending Date'] = 'Lopetusp�iv�:';
$strings['Starting Time'] = 'Aloitusaika:';
$strings['Ending Time'] = 'Lopetusaika:';
$strings['Output Type'] = 'Tulosteen tyyppi:';
$strings['Manage'] = 'Hallittse';
$strings['Total Time'] = 'K�ytetty aika:';
$strings['Total hours'] = 'Yhteens� tunteja:';
$strings['% of total resource time'] = '% k�ytetyst� resurssiajasta.';
$strings['View these results as'] = 'N�yt� resurssit seuraavasti:';
$strings['Edit this reservation'] = 'Muokkaa varausta';
$strings['Search Results'] = 'Haun tulokset';
$strings['Search Resource Usage'] = 'Etsi resurssien k�yt�st�';
$strings['Search Results found'] = 'Hakutulokset: %d varauksista l�ytynyt';
$strings['Try a different search'] = 'Yrit� toista hakua';
$strings['Search Run On'] = 'K�yt� hakua kohteeseen:';
$strings['Member ID'] = 'K�ytt�j�-id';
$strings['Previous User'] = '&laquo; Edellinen k�ytt�j�';
$strings['Next User'] = 'Seuraava k�ytt�j� &raquo;';

$strings['No results'] = 'Ei tuloksia';
$strings['That record could not be found.'] = 'Tietoa ei l�ytynyt.';
$strings['This blackout is not recurring.'] = 'T�m� k�ytt�katkos ei ole toistuva.';
$strings['This reservation is not recurring.'] = 'T�m� varaus ei ole toistuva.';
$strings['There are no records in the table.'] = 'Taulussa %s ei ole tietoja.';
$strings['You do not have any reservations scheduled.'] = 'Aikataulussasi ei ole varauksia.';
$strings['You do not have permission to use any resources.'] = 'Sinulla ei ole oikeuksia resursseihin.';
$strings['No resources in the database.'] = 'Tietokannassa ei ole resursseja.';
$strings['There was an error executing your query'] = 'Kyselysi k�sittely p��tyi virheeseen.';

$strings['That cookie seems to be invalid'] = 'Keksi ';
$strings['We could not find that email in our database.'] = 'S�hk�postiosoitetta ei l�ytynyt  tietokannasta.';
$strings['That password did not match the one in our database.'] = 'S�hk�posti ei vastannut tietokannassa olevaa.';
$strings['You can try'] = '<br />Voit:<br />Rekister�id� s�hk�postiosoitteen.<br />Tai:<br />Yritt�� kirjautua uudelleen.';
$strings['A new user has been added'] = 'Uusi k�ytt�j� lis�tty.';
$strings['You have successfully registered'] = 'Rekister�inti onnistui!';
$strings['Continue'] = 'Jatka...';
$strings['Your profile has been successfully updated!'] = 'Profiilisi on p�ivitetty!';
$strings['Please return to My Control Panel'] = 'Palaa ohjauspaneeliin';
$strings['Valid email address is required.'] = '- Vaaditaan kelvollinen s�hk�postiosoite';
$strings['First name is required.'] = '- Vaaditaan kelvollinen etunimi';
$strings['Last name is required.'] = '- Vaaditaan kelvollinen sukunimi';
$strings['Phone number is required.'] = '- Vaaditaan puhelinnumero';
$strings['That email is taken already.'] = '- S�hk�postiosoite on jo k�yt�ss�<br />Ole hyv�, ja k�yt� jotain muuta osoitetta.';
$strings['Min 6 character password is required.'] = '- Salasanan on oltava v�hint�� kuusi merkki� pitk�.';
$strings['Passwords do not match.'] = '- Salasanat eiv�t t�sm��.';

$strings['Per page'] = 'Per sivu:';
$strings['Page'] = 'Sivu:';

$strings['Your reservation was successfully created'] = 'Varauksesi luotiin onnistuneesti';
$strings['Your reservation was successfully modified'] = 'Varauksesi muokkaus onnistui';
$strings['Your reservation was successfully deleted'] = 'Varauksesi poistettiin onnistuneesti';
$strings['Your blackout was successfully created'] = 'K�ytt�katko luotiin onnistuneesti';
$strings['Your blackout was successfully modified'] = 'K�ytt�katkon muokkaus onnistui';
$strings['Your blackout was successfully deleted'] = 'K�ytt�katko poistettiin onnistuneesti';
$strings['for the follwing dates'] = 'seuraaville p�iville:';
$strings['Start time must be less than end time'] = 'Aloitusajankohdan pit�� olla pienempi kuin lopetusajankohdan.';
$strings['Current start time is'] = 'T�m�nhetkinen aloitusajankohta on:';
$strings['Current end time is'] = 'T�m�nhetkinen lopetusajankohta on:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'Varauksen pituus ei ole sallitun pituuden rajoissa.';
$strings['Your reservation is'] = 'Varauksesi on:';
$strings['Minimum reservation length'] = 'Pienin sallittu varauksen pituus:';
$strings['Maximum reservation length'] = 'Suurin sallittu varauksen pituus:';
$strings['You do not have permission to use this resource.'] = 'Sinulla ei ole oikeuksia k�ytt�� t�t� resurssia.';
$strings['reserved or unavailable'] = 'Aika v�lill� %s ja %s to %s on varattu tai ei k�ytett�viss�.';
$strings['Reservation created for'] = 'Varaus luotu ajalle %s';
$strings['Reservation modified for'] = 'Varaus muokattu ajalle %s';
$strings['Reservation deleted for'] = 'Varaus poistettu ajalle %s';
$strings['created'] = 'luotu';
$strings['modified'] = 'muokattu';
$strings['deleted'] = 'poistettu';
$strings['Reservation #'] = 'Varaus #';
$strings['Contact'] = 'Yhteys';
$strings['Reservation created'] = 'Varaus luotu';
$strings['Reservation modified'] = 'Varausta muokattu';
$strings['Reservation deleted'] = 'Varaus poistettu';

$strings['Reservations by month'] = 'Varaukset / kuukausi';
$strings['Reservations by day of the week'] = 'Varaukset / viikonp�iv�';
$strings['Reservations per month'] = 'Varauksia per kuukausi';
$strings['Reservations per user'] = 'Varauksia per k�ytt�j�';
$strings['Reservations per resource'] = 'Varauksia per resurssi';
$strings['Reservations per start time'] = 'Varauksia per aloitusajankohta';
$strings['Reservations per end time'] = 'Varauksia per lopetusajankohta';
$strings['[All Reservations]'] = '[Kaikki varaukset]';

$strings['Permissions Updated'] = 'Oukeudet p�ivitetty';
$strings['Your permissions have been updated'] = '%s -oikeutesi on p�ivitetty';
$strings['You now do not have permission to use any resources.'] = 'Sinulla ei ole oikeutta k�ytt�� resursseja.';
$strings['You now have permission to use the following resources'] = 'Sinulla on nyt oikeus k�ytt�� seuraavia resursseja:';
$strings['Please contact with any questions.'] = 'Yhteyshenkil�si ongelmatilanteissa on: %s';
$strings['Password Reset'] = 'Salasana nollattu';

$strings['This will change your password to a new, randomly generated one.'] = 'T�m� vaihtaa salasanasi satunnaisesti luotuun uuteen.';
$strings['your new password will be set'] = 'Sy�tetty�si s�hk�postiosoitteen, ja klikattuasi "Vaihda salasana"-nappia,  salasanasi p�ivitet��n ja l�hetet��n sinulle s�hk�postilla.';
$strings['Change Password'] = 'Vaihda salasana';
$strings['Sorry, we could not find that user in the database.'] = 'K�ytt�j�� ei l�ytynyt tietokannasta.';
$strings['Your New Password'] = 'Uusi %s salasanasi';
$strings['Your new passsword has been emailed to you.'] = 'Uusi salasanasi on l�hetetty sinulle s�hk�postila.<br />Saatuasi salasanan,<a href="index.php">kirjaudu sis��n</a> k�ytt�m�ll� sit�, ja vaihda salasana haluamaksesi klikkaamalla &quot;Muokkaa profiilin tietoja/salasanaa&quot; -linkki� Ohjauspaneelissa.';
$strings['You are not logged in!'] = 'Et ole kirjautunut sis��n!';

$strings['Setup'] = 'Asetukset';
$strings['Please log into your database'] = 'Kirjaudu tietokantaan';
$strings['Enter database root username'] = 'Sy�t� tietokannan root-k�ytt�j�tunnus:';
$strings['Enter database root password'] = 'Sy�t� tietokannan root-salasana:';
$strings['Login to database'] = 'Kirjaudu tietokantaan';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'Root-k�ytt�j�tunnusta <b>ei</b> tarvita. Mik� tahansa k�ytt�j�tunnus, jolla on oikeus luoda tauluja, toimii.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Luodaan kaikki tarpeelliset tietokannat ja taulut phpScheduleIt-ohjelmistolle.';
$strings['It also populates any required tables.'] = 'My�s kaikki tarvittavat taulut alustetaan.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Varoitus: KAIKKI JO OLEMASSA TIETO phpScheduleIt -TIETOKANNOISSA POISTETAAN!';
$strings['Not a valid database type in the config.php file.'] = 'Tietokannan tyyppi tiedostossa config.php ei ole kelvollinen.';
$strings['Database user password is not set in the config.php file.'] = 'Tietokannan k�ytt�j�n salasanaa ei ole annettu config.php -tiedostossa.';
$strings['Database name not set in the config.php file.'] = 'Tietokannan nime� ei ole annettu config.php-tiedostossa.';
$strings['Successfully connected as'] = 'Yhteys onnistui:';
$strings['Create tables'] = 'Luotaulut &gt;';
$strings['There were errors during the install.'] = 'Asennuksen aikana havaittiin virheit�. On mahdollista, ett� phpScheduleIt toimii t�st� huolimatta, jos virheet olivat pieni�.<br/><br/>L�het� mahdolliset kysymykset SourceForge-<a href="http://sourceforge.net/forum/?group_id=95547">keskustelualuelle</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'phpScheduleIt-asennus onnistui. Voit alkaa k�ytt�m��n ohjelmistoa.';
$strings['Thank you for using phpScheduleIt'] = 'Muistathan poistaa \'install\'-hakemisto T�YDELLISESTI. T�m� on erityisen t�rke��, sill� hakemisto sis�lt�� salasanoja, ja muuta t�rke�� tietoa, joka mahdollistaa kenelle tahansa murtautumisen tietokantaan!<br /><br />Kiitos, ett� valitsit phpScheduleIt:n!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'T�m� p�ivitt�� phpScheduleIt-version 0.9.3:sta versioksi 1.0.0';
$strings['There is no way to undo this action'] = 'T�t� toimintoa ei voi peruuttaa!';
$strings['Click to proceed'] = 'Klikkaa jatkaaksesi!';
$strings['This version has already been upgraded to 1.0.0.'] = 'T�m� versio on jo p�ivitetty versioksi 1.0.0';
$strings['Please delete this file.'] = 'Ole hyv�, ja poista t�m� tiedosto.';
$strings['Patch completed successfully'] = 'P�ivitys suoritettu onnistuneesti';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'T�m� alustaa tarvittavat kent�t phpScheduleIt 1.0.0-versiolle, ja p�ivitt�� version 0.9.9 data-bugin.<br />Sinun tarvitsee ajaa t�m� vain, jos suoritit manuaalisen SQL-p�ivityksen, tai olet p�ivitt�m�ss� versiosta 0.9.9';
/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s \r\n\r\n"
				. "Olet rekister�itynyt seuraavin tiedoin:\r\n"
				. "Nimi %s %s \r\n"
				. "Puhelin: %s\r\n"
				. "J�rjest�/yritys: %s \r\n"
				. "Toimenkuva: %s \r\n\r\n"
				. "Kirjaudu aikatauluun seuraavasta sijainnista:\r\n"
				. "%s \r\n\r\n"
				. "Linkit online-aikatauluun ja profiilin muokkaukseen l�ytyv�t Ohjauspaneelista.\r\n\r\n"
				. "Kysymykset resurssien ja varausten k�yt�st� ottaa k�sittelee %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Yll�pit�j�,\r\n\r\n"
				. "Uusi k�ytt�j� on rekister�itynyt seuraavin tiedoin:\r\n"
				. "S�hk�posti: %s \r\n"
				. "Nimi: %s %s \r\n"
				. "Puhelin: %s \r\n"
				. "Yritys/j�rjest�: %s \r\n"
				. "Toimenkuva: %s \r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Olet onnistuneesti %s varauksen #%s.\r\n\r\n<br/><br/>"
			. "K�yt� t�t� varausnumeroa ottaessasi esitt�ess�si kysymyksi� yll�pit�j�lle.\r\n<br/><br/>"
			. "Varaus p�iv�lle %s, v�lill� %s ja %s, kohteena \"%s\", \"%s\" on %s.\r\n<br/><br/>";
$email['reservation_activity_2'] = "T�m� varaus on toistettu seuraaville p�iville:\r\n<br/>";
$email['reservation_activity_3'] = "Kaikki toistuvat varaukset t�ss� ryhm�ss� %s my�s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "Varaukselle on annettu seuraavanlainen yhteenveto:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Jos teit virheen, ole hyv�, ja ota yhteytt� yll�pit�j��n osoitteessa: %s, tai soittamalla numeroon %s.\r\n\r\n<br/><br/>"
			. "Voit tutkia, tai muokata varaustietoja milloin tahansa kirjautumalla %s paikassa:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "L�het� tekniset kysymykset osoitteeseen <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Yll�pit�j� on nollannut %s -salasanasi.\r\n\r\n"
			. "V�liaikainen salasanasi on:\r\n\r\n %s\r\n\r\n"
			. "K�yt� t�t�  v�liaikaista salasanaa (kopio/liimaa ollaksesi varma oikeinkirjoituksesta) kirjautuaksesi %s paikassa %s ja vaihda salasana v�litt�m�sti k�ytt�m�ll� Muokkaa profiilia/salasanaa -linkki� pikalinkit-taulussa.\r\n\r\n"
			. "Ongelmatilanteissa kysymyksiisi vastaa %s.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\nUusi salasana k�ytt�j�tilillesi %s on:\r\n\r\n"
			. "%s\r\n\r\n"
			. "Kirjadu sis��n (%s) t�ll� salasanalla (kopioi/liimaa varmistaaksesi oikeinkirjoituksen), ja vaihda salasana haluamaksesi klikkaamalla Muokkaa profiilia/salasanaa -linkki� Ohjauspaneelissa.\r\n\r\n"
			. "Ongelmatilanteissa kysymyksiisi vastaa %s.";
?>