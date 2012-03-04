<?php
/**
* Hungarian (hu) translation file.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator Attila <atoth@cmr.sote.hu>
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
$charset = 'utf-8';

/***
  DAY NAMES
  All of these arrays MUST start with Sunday as the first element 
   and go through the seven day week, ending on Saturday
***/
// The full day name
$days_full = array('Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat');
// The three letter abbreviation
$days_abbr = array('Vas', 'Hét', 'Ked', 'Sze', 'Csü', 'Pén', 'Szo');
// The two letter abbreviation
$days_two  = array('Va', 'Hé', 'Ke', 'Se', 'Cs', 'Pé', 'So');
// The one letter abbreviation
$days_letter = array('V', 'H', 'K', 'S', 'C', 'P', 'Z');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');
// The three letter month name
$months_abbr = array('Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún', 'Júl', 'Aug', 'Sze', 'Okt', 'Nov', 'Dec');

// All letters of the alphabet starting with A and ending with Z
$letters = array ('A', 'Á', 'B', 'C', 'D', 'E', 'É', 'F', 'G', 'H', 'I', 'Í', 'J', 'K', 'L', 'M', 'N', 'O', 'Ó', 'Ö', 'Ő', 'P', 'Q', 'R', 'S', 'T', 'U', 'Ú', 'Ü', 'Ű', 'V', 'W', 'X', 'Y', 'Z');

/***
  DATE FORMATTING
  All of the date formatting must use the PHP strftime() syntax
  You can include any text/HTML formatting in the translation
***/
// General date formatting used for all date display unless otherwise noted
$dates['general_date'] = '%Y.%m.%d.';
// General datetime formatting used for all datetime display unless otherwise noted
// The hour:minute:second will always follow this format
$dates['general_datetime'] = '%Y.%m.%d. @';
// Date in the reservation notification popup and email
$dates['res_check'] = '%Y.%m.%d. %A';
// Date on the scheduler that appears above the resource links
$dates['schedule_daily'] = '%Y.%m.%d.<br/>%A';
// Date on top-right of each page
$dates['header'] = '%Y. %B %d. %A';
// Jump box format on bottom of the schedule page
// This must only include %m %d %Y in the proper order,
//  other specifiers will be ignored and will corrupt the jump box 
$dates['jumpbox'] = '%Y.%m.%d.';

/***
  STRING TRANSLATIONS
  All of these strings should be translated from the English value (right side of the equals sign) to the new language.
  - Please keep the keys (between the [] brackets) as they are.  The keys will not always be the same as the value.
  - Please keep the sprintf formatting (%s) placeholders where they are unless you are sure it needs to be moved.
  - Please keep the HTML and punctuation as-is unless you know that you want to change it.
***/
$strings['hours'] = 'óra';
$strings['minutes'] = 'perc';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'hh';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'nn';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'éééé';
$strings['am'] = 'de';
$strings['pm'] = 'du';

$strings['Administrator'] = 'Adminisztrátor';
$strings['Welcome Back'] = 'Üdvözlet, %s';
$strings['Log Out'] = 'Kilépés';
$strings['My Control Panel'] = 'Irányító Pult';
$strings['Help'] = 'Segítség';
$strings['Manage Schedules'] = 'Előjegyzés Kezelés';
$strings['Manage Users'] ='Felhasználó Kezelés';
$strings['Manage Resources'] ='Kontingens Kezelés';
$strings['Manage User Training'] ='Felhasználó Képzés';
$strings['Manage Reservations'] ='Vizsgálat Kezelés';
$strings['Email Users'] ='Körlevél a Felhasználóknak';
$strings['Export Database Data'] = 'Adatbázis Adatok Exportálása';
$strings['Reset Password'] = 'Jelszó Visszaállítása';
$strings['System Administration'] = 'Rendszer Adminisztráció';
$strings['Successful update'] = 'Sikeres Frissítés!';
$strings['Update failed!'] = 'Sikeretlen Frissítés!';
$strings['Manage Blackout Times'] = 'Tiltott Időpont Kezelés';
$strings['Forgot Password'] = 'Elfelejtett Jelszó';
$strings['Manage My Email Contacts'] = 'Email Kapcsolatok Kezelése';
$strings['Choose Date'] = 'Válasszon Dátumot';
$strings['Modify My Profile'] = 'Profil Módosítása';
$strings['Register'] = 'Regisztráció';
$strings['Processing Blackout'] = 'Érvénytelen Időszak Feldolgozása';
$strings['Processing Reservation'] = 'Vizsgálat Feldolgozása';
$strings['Online Scheduler [Read-only Mode]'] = 'Online Előjegyzés [Csak Nézegetés]';
$strings['Online Scheduler'] = 'Online Előjegyzés';
$strings['phpScheduleIt Statistics'] = 'phpScheduleIt Statisztikák';
$strings['User Info'] = 'Felhasználó Információ:';

$strings['Could not determine tool'] = 'Meghatározhatatlan eszköz. Térjen vissza a Vezérlő Pulthoz és próbálkozzon ismét.';
$strings['This is only accessable to the administrator'] = 'Csak az adminisztrátor számára elérhető';
$strings['Back to My Control Panel'] = 'Vissza a Vezérlő Pulthoz';
$strings['That schedule is not available.'] = 'A választott Előjegyzés nem elérhető.';
$strings['You did not select any schedules to delete.'] = 'Nem választotta ki a törlendő Előjegyzést.';
$strings['You did not select any members to delete.'] = 'Nem választotta ki a törlendő Felhasználót.';
$strings['You did not select any resources to delete.'] = 'Nem választotta ki a törlendő Kontingenst.';
$strings['Schedule title is required.'] = 'Az Előjegyzés nevének megadása kötelező.';
$strings['Invalid start/end times'] = 'Érvénytelen kezdés/befejezés';
$strings['View days is required'] = 'Megjelenítendő napok számának megadása kötelező';
$strings['Day offset is required'] = 'Előjegyzési Offset megadása kötelező';
$strings['Admin email is required'] = 'Az Admin email megadása kötelező';
$strings['Resource name is required.'] = 'A Kontingens megnevezése kötelező.';
$strings['Valid schedule must be selected'] = 'Érvényes Előjegyzés nevének megadása kötelező';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'A legrövidebb vizsgálati idő nem haladhatja meg a maximális időtartamot.';
$strings['Your request was processed successfully.'] = 'A kérését a Rendszer sikeresen feldolgozta.';
$strings['Go back to system administration'] = 'Vissza a Rendszer Adminisztrációhoz';
$strings['Or wait to be automatically redirected there.'] = 'Vagy várja meg, amíg automatikusan átirányítodik.';
$strings['There were problems processing your request.'] = 'Hiba történt a kérés feldolgozás közben.';
$strings['Please go back and correct any errors.'] = 'Kérem menjen vissza és javítson minden hibát.';
$strings['Login to view details and place reservations'] = 'A részletek megtekintéséhez és vizsgálat előjegyzéséhez jelentkezzen be';
$strings['Memberid is not available.'] = 'Az Azonosító: %s nem használható.';

$strings['Schedule Title'] = 'Előjegyzés Megnevezése';
$strings['Start Time'] = 'Kezdő időpont';
$strings['End Time'] = 'Befejező időpont';
$strings['Time Span'] = 'Időtartam';
$strings['Weekday Start'] = 'A Hét Kezdő Napja';
$strings['Admin Email'] = 'Admin Email';

$strings['Default'] = 'Alapértelmezett';
$strings['Reset'] = 'Visszaállít';
$strings['Edit'] = 'Szerkesztés';
$strings['Delete'] = 'Törlés';
$strings['Cancel'] = 'Mégse';
$strings['View'] = 'Nézet';
$strings['Modify'] = 'Módosítás';
$strings['Save'] = 'Mentés';
$strings['Back'] = 'Vissza';
$strings['Next'] = 'Előre';
$strings['Close Window'] = 'Ablak Bezárása';
$strings['Search'] = 'Keresés';
$strings['Clear'] = 'Üres';

$strings['Days to Show'] = 'Megjelenítendő Napok Száma';
$strings['Reservation Offset'] = 'Előjegyzési Offset';
$strings['Hidden'] = 'Rejtett';
$strings['Show Summary'] = 'Összegzés Megjelenítése';
$strings['Add Schedule'] = 'Új Előjegyzés Hozzáadása';
$strings['Edit Schedule'] = 'Előjegyzés Szerkesztése';
$strings['No'] = 'Nem';
$strings['Yes'] = 'Igen';
$strings['Name'] = 'Név';
$strings['First Name'] = 'Vezetéknév';
$strings['Last Name'] = 'Keresztnév';
$strings['Resource Name'] = 'Kontingens Neve';
$strings['Email'] = 'Email';
$strings['Institution'] = 'Intézmény';
$strings['Phone'] = 'Telefon';
$strings['Password'] = 'Jelszó';
$strings['Permissions'] = 'Permissions';
$strings['View information about'] = 'Tájékoztatás megtekintése a következőről: %s %s';
$strings['Send email to'] = 'Email küldése a következőnek: %s %s';
$strings['Reset password for'] = 'Reset password for %s %s';
$strings['Edit permissions for'] = 'Edit permissions for %s %s';
$strings['Position'] = 'Beosztás';
$strings['Password (6 char min)'] = 'Jelszó (minimum 6 betű)';
$strings['Re-Enter Password'] = 'A jelszó ismételt megadása';

$strings['Sort by descending last name'] = 'Csökkenő sorrend a Keresztnév alapján';
$strings['Sort by descending email address'] = 'Csökkenő sorrend az Email cím alapján';
$strings['Sort by descending institution'] = 'Csökkenő sorrend az Intézmény megnevezése alapján';
$strings['Sort by ascending last name'] = 'Emelkedő sorrend a Keresztnév alapján';
$strings['Sort by ascending email address'] = 'Emelkedő sorrend az Email cím alapján';
$strings['Sort by ascending institution'] = 'Emelkedő sorrend az Intézmény megnevezése alapján';
$strings['Sort by descending resource name'] = 'Csökkenő sorrend a Kontingens neve alapján';
$strings['Sort by descending location'] = 'Csökkenő sorrend Helyszín alapján';
$strings['Sort by descending schedule title'] = 'Csökkenő sorrend az Előjegyzés megnevezése alapján';
$strings['Sort by ascending resource name'] = 'Emelkedő sorrend a Kontingens neve alapján';
$strings['Sort by ascending location'] = 'Emelkedő sorrend Helyszín alapján';
$strings['Sort by ascending schedule title'] = 'Emelkedő sorrend az Előjegyzés megnevezése alapján';
$strings['Sort by descending date'] = 'Csökkenő sorrend a dátum alapján';
$strings['Sort by descending user name'] = 'Csökkenő sorrend Felhasználó név alapján';
$strings['Sort by descending resource name'] = 'Csökkenő sorrend a Kontingens neve alapján';
$strings['Sort by descending start time'] = 'Csökkenő sorrend a Kezdő időpont alapján';
$strings['Sort by descending end time'] = 'Csökkenő sorrend a Befejező időpont alapján';
$strings['Sort by ascending date'] = 'Emelkedő sorrend dátum alapján';
$strings['Sort by ascending user name'] = 'Emelkedő sorrend a Felhasználó név alapján';
$strings['Sort by ascending resource name'] = 'Emelkedő sorrend a Kontingens neve alapján';
$strings['Sort by ascending start time'] = 'Emelkedő sorrend a Kezdő időpont alapján';
$strings['Sort by ascending end time'] = 'Emelkedő sorrend a Befejező időpont alapján';
$strings['Sort by descending created time'] = 'Csökkenő sorrend a Létrehozás dátuma alapján';
$strings['Sort by ascending created time'] = 'Emelkedő sorrend a Létrehozás dátuma alapján';
$strings['Sort by descending last modified time'] = 'Csökkenő sorrend az utolsó Módosítás ideje alapján';
$strings['Sort by ascending last modified time'] = 'Emelkedő sorrend az utolsó Módosítás ideje alapján';

$strings['Search Users'] = 'Felhasználó Keresése';
$strings['Location'] = 'Helyszín';
$strings['Schedule'] = 'Előjegyzés';
$strings['Phone'] = 'Telefon';
$strings['Notes'] = 'Megjegyzés';
$strings['Status'] = 'Állapot';
$strings['All Schedules'] = 'Minden Előjegyzés';
$strings['All Resources'] = 'Minden Kontingens';
$strings['All Users'] = 'Minden Felhasználó';

$strings['Edit data for'] = 'A következő adatainak szerkesztése: %s';
$strings['Active'] = 'Aktív';
$strings['Inactive'] = 'Inaktív';
$strings['Toggle this resource active/inactive'] = 'A Kontingens állapotának ';
$strings['Minimum Reservation Time'] = 'Minimum Vizsgálati időtartam';
$strings['Maximum Reservation Time'] = 'Maximum Vizsgálati időtartam';
$strings['Auto-assign permission'] = 'Jogosultságok Automatikus Kiosztása';
$strings['Add Resource'] = 'Kontingens Hozzáadása';
$strings['Edit Resource'] = 'Kontingens Szerkesztése';
$strings['Allowed'] = 'Engedélyezett';
$strings['Notify user'] = 'Felhasználó Értesítése';
$strings['User Reservations'] = 'Felhasználó Viszgálatai';
$strings['Date'] = 'Dátum';
$strings['User'] = 'Felhasználó';
$strings['Email Users'] = 'Email Users';
$strings['Subject'] = 'Tárgy';
$strings['Message'] = 'Szöveg';
$strings['Please select users'] = 'Válasszon Felhasználót';
$strings['Send Email'] = 'Email Küldése';
$strings['problem sending email'] = 'Sajnos probléma merült fel az email küldése közben. Kérem próbálja újra később.';
$strings['The email sent successfully.'] = 'Az emailt sikerült postázni.';
$strings['do not refresh page'] = 'Kérem <u>NE</u> frissítse ezt az oldalt, mert az email űjra elküldésre kerül.';
$strings['Return to email management'] = 'Visszatérés az Email Kezeléshez';
$strings['Please select which tables and fields to export'] = 'Kérem válassza ki, hogy melyik táblát és mezőt kívánja exportálni:';
$strings['all fields'] = '- minden mező -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Sima szöveg';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Adatok Exportálása';
$strings['Reset Password for'] = '%s Jelszavának visszaállítása';
$strings['Please edit your profile'] = 'Kérem hajtsa végre Profilján a kívánt változtatásokat';
$strings['Please register'] = 'Kérem Regisztráljon';
$strings['Email address (this will be your login)'] = 'Email cím (ez lesz az Azonosítója)';
$strings['Keep me logged in'] = 'A Rendszer őrrizen meg bejelentkezett állapotban <br/>(cookie támogatás szükséges)';
$strings['Edit Profile'] = 'Profil Szerkesztése';
$strings['Register'] = 'Regisztráció';
$strings['Please Log In'] = 'Kérem Jelentkezzen Be';
$strings['Email address'] = 'Email Cím';
$strings['Password'] = 'Jelszó';
$strings['First time user'] = 'Első Alkalom?';
$strings['Click here to register'] = 'Kattintson ide a regisztrációhoz';
$strings['Register for phpScheduleIt'] = 'Regisztráció a phpScheduleIt Rendszerbe';
$strings['Log In'] = 'Bejelentkezés';
$strings['View Schedule'] = 'Előjegyzések Megtekintése';
$strings['View a read-only version of the schedule'] = 'Megtekintés csak olvasható módban';
$strings['I Forgot My Password'] = 'Elfelejtett Jelszó';
$strings['Retreive lost password'] = 'Elfelejtett jelszó elkérése';
$strings['Get online help'] = 'Online Segítség';
$strings['Language'] = 'Nyelv';
$strings['(Default)'] = '(Alapértelmezett)';

$strings['My Announcements'] = 'Bejelentések';
$strings['My Reservations'] = 'Vizsgálatok';
$strings['My Permissions'] = 'Jogosultságok';
$strings['My Quick Links'] = 'Gyors Linkek';
$strings['Announcements as of'] = 'Bejelentések %s';
$strings['There are no announcements.'] = 'Nincsen Bejelentés.';
$strings['Resource'] = 'Kontingens';
$strings['Created'] = 'Létrehozva';
$strings['Last Modified'] = 'Utoljára Módosítva';
$strings['View this reservation'] = 'Viszgálat megtekintése';
$strings['Modify this reservation'] = 'Vizsgálat módosítása';
$strings['Delete this reservation'] = 'Viszgálat törlése';
$strings['Go to the Online Scheduler'] = 'Ugrás az Online Előjegyzésre';
$strings['Change My Profile Information/Password'] = 'Profile Szerkesztése/Jelszó Megváltoztatása';
$strings['Manage My Email Preferences'] = 'Email Beállítások Szerkesztése';
$strings['Manage Schedules'] = 'Előjegyzés Kezelés';
$strings['Manage Resources'] = 'Kontingens Kezelés';
$strings['Manage Users'] = 'Felhasználó Kezelés';
$strings['Manage Reservations'] = 'Viszgálat Kezelés';
$strings['Manage Blackout Times'] = 'Tiltott Időpont Kezelés';
$strings['Mass Email Users'] = 'Mass Email Users';
$strings['Search Scheduled Resource Usage'] = 'Search Scheduled Resource Usage';
$strings['Export Database Content'] = 'Adatbázis Tartalom Exportálása';
$strings['View System Stats'] = 'Rendszer Statisztika Megtekintése';
$strings['Email Administrator'] = 'Email Küldése az Adminisztrátornak';

$strings['Email me when'] = 'Email küldése a következő esetben:';
$strings['I place a reservation'] = 'Vizsgálat előjegyzése';
$strings['My reservation is modified'] = 'Előjegyzett vizsgálat módosíttása';
$strings['My reservation is deleted'] = 'Előjegyzett vizsgálat törlése';
$strings['I prefer'] = 'Előnyben részesül:';
$strings['Your email preferences were successfully saved'] = 'Az email beállítások sikeresen tárolásra kerültek!';
$strings['Return to My Control Panel'] = 'Vissza a Vezérlő Pulthoz';

$strings['Please select the starting and ending times'] = 'Kérem válassza ki a kezdő és befejező időpontokat:';
$strings['Please change the starting and ending times'] = 'Kérem módosítsa a kezdő és a befejező időpontokat:';
$strings['Reserved time'] = 'Fenntartott időtartam:';
$strings['Minimum Reservation Length'] = 'Minimum Vizsgálati Idő:';
$strings['Maximum Reservation Length'] = 'Maximum Vizsgálati Idő:';
$strings['Reserved for'] = 'Fenntartva:';
$strings['Will be reserved for'] = 'Nem kerül kiosztásra:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Az ismételt előfordulások ?';
$strings['Delete?'] = 'Törlés?';
$strings['Never'] = '-- Soha --';
$strings['Days'] = 'Naponta';
$strings['Weeks'] = 'Hetenként';
$strings['Months (date)'] = 'Hónapban (Dátum)';
$strings['Months (day)'] = 'Hónapban (Nap)';
$strings['First Days'] = 'Első nap';
$strings['Second Days'] = 'Második napon';
$strings['Third Days'] = 'Harmadik napon';
$strings['Fourth Days'] = 'Negyedik napon';
$strings['Last Days'] = 'Utolsó nap';
$strings['Repeat every'] = 'Ismételt előfordulás minden:';
$strings['Repeat on'] = 'Ismétlődjön:';
$strings['Repeat until date'] = 'Ismétlődjön a következő ideig:';
$strings['Choose Date'] = 'Válasszon dátumot';
$strings['Summary'] = 'Összegzés';

$strings['View schedule'] = 'Előjegyzés megtekintése:';
$strings['My Reservations'] = 'Saját Vizsgálat';
$strings['My Past Reservations'] = 'Lejárt Saját Vizsgálat';
$strings['Other Reservations'] = 'Egyéb Vizsgálat';
$strings['Other Past Reservations'] = 'Lejárt Egyéb Vizsgálat';
$strings['Blacked Out Time'] = 'Tiltott Időpont';
$strings['Set blackout times'] = 'Időpont Tiltása %s %s'; 
$strings['Reserve on'] = 'Reserve %s on %s';
$strings['Prev Week'] = '&laquo; Előző Hét';
$strings['Jump 1 week back'] = '1 Héttel Vissza';
$strings['Prev days'] = '&#8249; Előző %d nap';
$strings['Previous days'] = '&#8249; Előző %d nap';
$strings['This Week'] = 'Aktuális Hét';
$strings['Jump to this week'] = 'Ugrás erre a hétre';
$strings['Next days'] = 'Következő %d nap &#8250;';
$strings['Next Week'] = 'Következő hét &raquo;';
$strings['Jump To Date'] = 'Ugrás erre a napra';
$strings['View Monthly Calendar'] = 'Naptár Megtekintése Havi Bontásban';
$strings['Open up a navigational calendar'] = 'Navigáló naptár megnyitása';

$strings['View stats for schedule'] = 'Előjegyzés statisztikáinak megjelenítése:';
$strings['At A Glance'] = 'Egy Pillantra';
$strings['Total Users'] = 'Összes Felhasználó:';
$strings['Total Resources'] = 'Összes Kontingens:';
$strings['Total Reservations'] = 'Összes Vizsgálat:';
$strings['Max Reservation'] = 'Maximum Vizsgálat:';
$strings['Min Reservation'] = 'Minimum Vizsgálat:';
$strings['Avg Reservation'] = 'Átlagos Vizsgálat:';
$strings['Most Active Resource'] = 'Legaktívabb Kontingens:';
$strings['Most Active User'] = 'Legaktívabb Felhasználók:';
$strings['System Stats'] = 'Rendszer Statisztika';
$strings['phpScheduleIt version'] = 'phpScheduleIt verzió:';
$strings['Database backend'] = 'Adatbázis backend:';
$strings['Database name'] = 'Adatbázis név:';
$strings['PHP version'] = 'PHP verziószám:';
$strings['Server OS'] = 'Szerver OS:';
$strings['Server name'] = 'Szerver név:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt gíökér könyvtár:';
$strings['Using permissions'] = 'Jogosultság kezelés használata:';
$strings['Using logging'] = 'Naplózás használata:';
$strings['Log file'] = 'Napló fájl:';
$strings['Admin email address'] = 'Admin email cím:';
$strings['Tech email address'] = 'Tech email cím:';
$strings['CC email addresses'] = 'CC email cím:';
$strings['Reservation start time'] = 'Vizsgálat kezdő időpont:';
$strings['Reservation end time'] = 'Vizsgálat végső időpont:';
$strings['Days shown at a time'] = 'Egyszerre megjelnített napok:';
$strings['Reservations'] = 'Vizsgálati előjegyzések';
$strings['Return to top'] = 'Vissza a tetejére';
$strings['for'] = 'miatt';

$strings['Select Search Criteria'] = 'Select Search Criteria';
$strings['Schedules'] = 'Előjegyzések:';
$strings['All Schedules'] = 'Összes Előjegyzés';
$strings['Hold CTRL to select multiple'] = 'Többszörös választáshoz tartsa lenyomva a CTRL-t';
$strings['Users'] = 'Felhasználók:';
$strings['All Users'] = 'Összes Felhasználó';
$strings['Resources'] = 'Kontingens:';
$strings['All Resources'] = 'Összes Kontingens';
$strings['Starting Date'] = 'Kezdő Dátum:';
$strings['Ending Date'] = 'Befejező Dátum:';
$strings['Starting Time'] = 'Kezdő Időpont:';
$strings['Ending Time'] = 'Befejező Időpont:';
$strings['Output Type'] = 'Kijelzési Mód:';
$strings['Manage'] = 'Kezelés';
$strings['Total Time'] = 'Összesített Időtartam';
$strings['Total hours'] = 'Összesített Órák:';
$strings['% of total resource time'] = '%-a az összes Vizsgálati Időnek';
$strings['View these results as'] = 'Az eredmények megtekintése a következőképpen:';
$strings['Edit this reservation'] = 'A Vizsgálat szerkesztése';
$strings['Search Results'] = 'Keresési Eredények';
$strings['Search Resource Usage'] = 'Kontingens Kihasználtság szerinti keresés';
$strings['Search Results found'] = 'Keresési Eredmények: %d Találat';
$strings['Try a different search'] = 'Próbálkozzon másik kereséssel';
$strings['Search Run On'] = 'Keresés a Következőn Futott:';
$strings['Member ID'] = 'Felhasználó Azonosító';
$strings['Previous User'] = '&laquo; Előző Felhasználó';
$strings['Next User'] = 'Következő Felhasználó &raquo;';

$strings['No results'] = 'Nincs Találat';
$strings['That record could not be found.'] = 'Ilyen Bejegyzés nem található..';
$strings['This blackout is not recurring.'] = 'A Tiltott Időpont nem ismétlődik.';
$strings['This reservation is not recurring.'] = 'A Vizsgálat nem ismétlődik.';
$strings['There are no records in the table.'] = 'Nincs egy Bejegyzés sem a következő Táblában: %s.';
$strings['You do not have any reservations scheduled.'] = 'Nem található Ön által előjegyzett Vizsgálat.';
$strings['You do not have permission to use any resources.'] = 'Önnek egyik Kontingens használatához sincs joga.';
$strings['No resources in the database.'] = 'Nincs Kontingens az adatbázisban.';
$strings['There was an error executing your query'] = 'Hiba történt a kérés feldolgozása közben:';

$strings['That cookie seems to be invalid'] = 'A Cookie érvénytelennek tűnik';
$strings['We could not find that email in our database.'] = 'A megadott Email cím nem található az adatbázisban.';
$strings['That password did not match the one in our database.'] = 'A megadott Jelszó nem egyezik az adatbázisban szereplővel.';
$strings['You can try'] = '<br />You can try:<br />Registering an email address.<br />Or:<br />Try logging in again.';
$strings['A new user has been added'] = 'Az Új Felhasználó bejegyzésre került a Rendszerbe';
$strings['You have successfully registered'] = 'Sikeres Regisztráció!';
$strings['Continue'] = 'Folytatás...';
$strings['Your profile has been successfully updated!'] = 'A Profil sikeresen frissítésre került!';
$strings['Please return to My Control Panel'] = 'Kérem térjen vissza a Vezérlő Pulthoz';
$strings['Valid email address is required.'] = '- Valós email cím megadása szükséges.';
$strings['First name is required.'] = '- Vezetéknév megadása kötelező.';
$strings['Last name is required.'] = '- Keresztnév megadása kötelező.';
$strings['Phone number is required.'] = '- Telefonszám megadása kötelező.';
$strings['That email is taken already.'] = '- A megadott email cím foglalt.<br />Kérem válasszon egy másikat.';
$strings['Min 6 character password is required.'] = '- Min 6 betű hosszú jelszó megadása szükséges.';
$strings['Passwords do not match.'] = '- A jelszó nem egyezik.';

$strings['Per page'] = 'Oldalanként:';
$strings['Page'] = 'Oldal:';

$strings['Your reservation was successfully created'] = 'A Vizsgálat sikeresen bejegyzésre került';
$strings['Your reservation was successfully modified'] = 'A Vizsgálat sikeresen módosításra került';
$strings['Your reservation was successfully deleted'] = 'A Vizsgálat sikeresen törlésre került';
$strings['Your blackout was successfully created'] = 'A Tiltott Időpont sikeresen bejegyzésre került';
$strings['Your blackout was successfully modified'] = 'A Tiltott Időpont sikeresen módosításra került';
$strings['Your blackout was successfully deleted'] = 'A Tiltott Időpont sikeresen törlésre került';
$strings['for the follwing dates'] = 'az alábbi időpont(ok)ban:';
$strings['Start time must be less than end time'] = 'A Kezdő Idópontnak korábbinak kell lenni a Befejező Időpontnál';
$strings['Current start time is'] = 'Aktuális Kezdő Időpont:';
$strings['Current end time is'] = 'Aktuális Befejező Időpont:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'A Vizsgálat megadott hossza nem teljesíti az időtartammal kapcsolatban\ meghatározott feltételeket.';
$strings['Your reservation is'] = 'A Vizsgálat:';
$strings['Minimum reservation length'] = 'Minimális Vizsgálati Időtartam:';
$strings['Maximum reservation length'] = 'Maximális Vizsgálati Időtartam:';
$strings['You do not have permission to use this resource.'] = 'Nincs Jogosultsága az adott Kontingens használatához.';
$strings['reserved or unavailable'] = '%s -tól %s -ig a(z) %s már foglalt vagy nem használható.';
$strings['Reservation created for'] = 'Vizsgálat létrehozva %s';
$strings['Reservation modified for'] = 'Vizsgálat módosítva %s';
$strings['Reservation deleted for'] = 'Vizsgálat törölve %s';
$strings['created'] = 'létrehozva';
$strings['modified'] = 'módosítva';
$strings['deleted'] = 'törölve';
$strings['Reservation #'] = 'Vizsgálat #';
$strings['Contact'] = 'Kapcsolat';
$strings['Reservation created'] = 'Viszgálat létrehozva';
$strings['Reservation modified'] = 'Vizsgálat módosítva';
$strings['Reservation deleted'] = 'Vizsgálat törölve';

$strings['Reservations by month'] = 'Vizsgálatok Hónapos Bontásban';
$strings['Reservations by day of the week'] = 'Vizsgálatok Napos Bontásban';
$strings['Reservations per month'] = 'Vizsgálatok Hónaponként';
$strings['Reservations per user'] = 'Vizsgálatok Felhasználónként';
$strings['Reservations per resource'] = 'Vizsgálatok Kontingensenként';
$strings['Reservations per start time'] = 'Vizsgálatok Iduló Időpont Alapján';
$strings['Reservations per end time'] = 'Vizsgálatok Befejező Időpont Alapján';
$strings['[All Reservations]'] = '[Minden Vizsgálat]';

$strings['Permissions Updated'] = 'Frissültek a Jogosultságok';
$strings['Your permissions have been updated'] = '%s Jogosultásgok frissítésre kerültek';
$strings['You now do not have permission to use any resources.'] = 'Egy Kontingens használatához sincs Jogosultsága.';
$strings['You now have permission to use the following resources'] = 'Mostantól Jogosultsággal rendelkezik a következő Kontingens(ek) használatára:';
$strings['Please contact with any questions.'] = 'Kérdés esetén kérem vegye fel a kapcsolatot a következővel: %s.';
$strings['Password Reset'] = 'Jelszó Visszaállítva';

$strings['This will change your password to a new, randomly generated one.'] = 'Ezzel Jelszava véletlenszerűre fog változni.';
$strings['your new password will be set'] = 'Miután megadta az Email címét és a "Jelszó Megváltoztatása" gombra kattint, az újdonsült Jelszavát a rendszer regisztrálja és elküldi önnek Email-ben.';
$strings['Change Password'] = 'Jelszó Megváltoztatása';
$strings['Sorry, we could not find that user in the database.'] = 'Sajnos a megadott Felhasználó nem található meg az adatbázisban.';
$strings['Your New Password'] = 'Az Ön Új %s Jelszava';
$strings['Your new passsword has been emailed to you.'] = 'Elkészült!<br />'
    			. 'Az újdonsült Jelszavát a Rendszer elküldte önnek.<br />'
    			. 'Kérem ellenőrizze postafiókját és a nyítólapon Kattintson a <a href="index.php">Bejelentkezés</a> linkre.'
    			. ' Használja az új Jelszavát és változtassa meg azonnal a &quot;Profil Szerkesztése/Jelszó Megváltoztatása&quot;'
    			. ' menüpont alatt a Vezérlő Pultban.';

$strings['You are not logged in!'] = 'Nincs Bejelentkezve!';

$strings['Setup'] = 'Telepítés';
$strings['Please log into your database'] = 'Kérem jelentkezzen be az adatbázisba';
$strings['Enter database root username'] = 'Adja meg az adatbázis felhasználójának azonosítóját:';
$strings['Enter database root password'] = 'Adja meg az adatbázis felhasználójának jelszavát:';
$strings['Login to database'] = 'Bejelentkezás az adatbázisba';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'Az adatbázis root felhasználójának megadása <b>nem</b> szükséges. Bármely adatbázis felhasználó megfelel, akinek van jogosultsága a táblák létrehozására.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Ezzel telepítésre kerül minden szükséges phpScheduleIt Adatbázis és Tábla.';
$strings['It also populates any required tables.'] = 'Valamint feltölti a szükséges Táblákat.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Figyelmeztetés: MINDEN KORÁBBI ADAT TÖRLÉSRE KERÜL A phpScheduleIt ADATBÁZISBÓL!';
$strings['Not a valid database type in the config.php file.'] = 'Érvénytelen adatbázis típus szerepel a config.php fájlban.';
$strings['Database user password is not set in the config.php file.'] = 'Az adatbázis felhasználójának jelszava nincs megadva a config.php fájlban.';
$strings['Database name not set in the config.php file.'] = 'Az adatbázis neve nincs megadva config.php fájlban.';
$strings['Successfully connected as'] = 'Sikeres kapcsolódás a következő néven';
$strings['Create tables'] = 'Táblák létrehozása &gt;';
$strings['There were errors during the install.'] = 'Hiba történt a telepítés során. Elképzelhető, hogy a phpScheduleIt mégis működni fog, amennyiben ez(ek) csak kis jelentőségűek volt(ak).<br/><br/>'
	. 'Kérdésekkel keresse fel a projekt fórumát a <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>-on.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'A phpScheduleIt telepítése sikeresen befejeződött és a Rendszer használatra kész.';
$strings['Thank you for using phpScheduleIt'] = 'Kérem bizonyosodjon meg róla, hogy a \'install\' KÖNYVTÁRAT TELJESEN ELTÁVOLÍTOTTA.'
	. ' A könyvtár törlése alapvető biztonsági kérdés, mert bizalmas információkat (adatbázis jelszó) tartalmaz.'
	. ' Elmulasztása széles biztonsági rést hagy nyitva, melyen át bármikor betörhetnek az adatbázisba!'
	. '<br /><br />'
	. 'Köszönet, amiért a phpScheduleIt-et választotta!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'A phpScheduleIt Rendszer ennek segítségével 0.9.3-ról 1.0.0-ra Korszerűsíthető.';
$strings['There is no way to undo this action'] = 'A következő beavatkozás visszaállítására nincs lehetőség!';
$strings['Click to proceed'] = 'Kattintson a továbblépéshez';
$strings['This version has already been upgraded to 1.0.0.'] = 'A jelen Rendszer már most is 1.0.0-s verziójú.';
$strings['Please delete this file.'] = 'Kérem törölje ezt a fájlt.';
$strings['Successful update'] = 'A Korszerűsítés sikerrel járt';
$strings['Patch completed successfully'] = 'A foltozás sikeresen befejeződött';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'A következő betölti a phpScheduleIt 1.0.0 számára szükséges mezőket és néhány 0.9.9-es verzióban megtalálható data bug-ot foltoz.'
		. '<br />Csak akkor szökséges lefuttatni, ha kézi SQL frissítést hajtott végre, vagy 0.9.9-ról korszerűsít';

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s \r\n"
				. "Sikeresen regisztrált a Rendszerbe a következő adatokkal:\r\n"
				. "Név: %s %s \r\n"
				. "Telefon: %s \r\n"
				. "Intézmény: %s \r\n"
				. "Beosztás: %s \r\n\r\n"
				. "Kérem jelentkezzen be a Rendszerbe a következő helyen:\r\n"
				. "%s \r\n\r\n"
				. "A Vezérlő Pultban linket talál az Online Előjegyzésre is és a Profil-ját szerkesztheti.\r\n\r\n"
				. "Kérem forduljon a következőhöz Viszgálatokkal és Kontingensekkel kapcsolatos kérdéseivel: %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Adminisztrátor,\r\n\r\n"
					. "Egy új Felhasználó regisztrált az alábbi információkkal:\r\n"
					. "Email cím: %s \r\n"
					. "Név: %s %s \r\n"
					. "Telefon: %s \r\n"
					. "Intézmény: %s \r\n"
					. "Beosztás: %s \r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Sikeres előjegyzés %s Vizsgálat #%s.\r\n\r\n<br/><br/>"
			. "Kérem hivatkozzon a Vizsgálatot azonosító számra, amennyiben felveszi a kapcsolatot az Adminisztrátorral.\r\n\r\n<br/><br/>"
			. "Következő dátummal: %s %s-től %s-ig %s számára"
			. " a %s helyszínen %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "A Vizsgálat a következő napokon fog ismétlődni:\r\n<br/>";
$email['reservation_activity_3'] = "A csoportban előforduló összes ismétlődő Viszgálat szintén %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "A következő Összegzést adták meg a Vizsgálat előjegyzésekor:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Amennyiben ez egy tévedés, kérem értesítse az Adminisztrátort: %s"
			. " vagy telefonáljon a következő számra: %s.\r\n\r\n<br/><br/>"
			. "Az előjegyzett Vizsgálat részleteit bármikor megnézheti vagy módosíthatja, ha"
			. " Bejelentkezik %s Rendszerbe a következő helyen:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "A technikai jellegű kérdésekkel forduljon a következőhöz: <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Az Ön %s Jelszavát az Adminisztrátor visszaállította.\r\n\r\n"
			. "Az Ön ideiglenes jelszava:\r\n\r\n %s\r\n\r\n"
			. "Kérem használja ezt (másolja és illessze be, hogy biztosan helyes legyen) a Belépéshez %s a következő helyen: %s"
			. " és változtassa meg nyomban a Vezérlő Pult 'Profil Szerkesztése/Jelszó Megváltoztatása' menüpontjában.\r\n\r\n"
			. "Kérdéseivel kérem keresse meg a következőt: %s.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Az Ön új Jelszava az %s Azonosítójához a következő:\r\n\r\n"
            . "%s\r\n\r\n"
            . "Kérem jelentkezzen be a következő helyen: %s"
            . "és használja az újdonsült Jelszavát "
            . "(Másolja és illessze be, hogy biztosan hibátlanul kerüljön bevitelre) "
            . "majd a jelszó azonnali megváltoztatásához keresse fel a "
            . "Profil Szerkesztése/Jelszó Megváltoztatása menüpontot "
            . "a Vezérlő Pultban.\r\n\r\n"
            . "Kérdéseivel forduljon a következőhöz: %s.";
?>