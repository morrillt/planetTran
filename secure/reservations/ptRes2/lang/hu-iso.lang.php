<?php
/**
* English (en) translation file.
* This also serves as the base translation file from which to derive
*  all other translations.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator Your Name <your@email.address.net>
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
$charset = 'iso-8859-2';

/***
  DAY NAMES
  All of these arrays MUST start with Sunday as the first element 
   and go through the seven day week, ending on Saturday
***/
// The full day name
$days_full = array('Vas�rnap', 'H�tf�', 'Kedd', 'Szerda', 'Cs�t�rt�k', 'P�ntek', 'Szombat');
// The three letter abbreviation
$days_abbr = array('Vas', 'H�t', 'Ked', 'Sze', 'Cs�', 'P�n', 'Szo');
// The two letter abbreviation
$days_two  = array('Va', 'H�', 'Ke', 'Se', 'Cs', 'P�', 'So');
// The one letter abbreviation
$days_letter = array('V', 'H', 'K', 'S', 'C', 'P', 'Z');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Janu�r', 'Febru�r', 'M�rcius', '�prilis', 'M�jus', 'J�nius', 'J�lius', 'Augusztus', 'Szeptember', 'Okt�ber', 'November', 'December');
// The three letter month name
$months_abbr = array('Jan', 'Feb', 'M�r', '�pr', 'M�j', 'J�n', 'J�l', 'Aug', 'Sze', 'Okt', 'Nov', 'Dec');

// All letters of the alphabet starting with A and ending with Z
$letters = array ('A', '�', 'B', 'C', 'D', 'E', '�', 'F', 'G', 'H', 'I', '�', 'J', 'K', 'L', 'M', 'N', 'O', '�', '�', '�', 'P', 'Q', 'R', 'S', 'T', 'U', '�', '�', '�', 'V', 'W', 'X', 'Y', 'Z');

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
$strings['hours'] = '�ra';
$strings['minutes'] = 'perc';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'hh';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'nn';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = '����';
$strings['am'] = 'de';
$strings['pm'] = 'du';

$strings['Administrator'] = 'Adminisztr�tor';
$strings['Welcome Back'] = '�dv�zlet, %s';
$strings['Log Out'] = 'Kil�p�s';
$strings['My Control Panel'] = 'Ir�ny�t� Pult';
$strings['Help'] = 'Seg�ts�g';
$strings['Manage Schedules'] = 'El�jegyz�s Kezel�s';
$strings['Manage Users'] ='Felhaszn�l� Kezel�s';
$strings['Manage Resources'] ='Kontingens Kezel�s';
$strings['Manage User Training'] ='Felhaszn�l� K�pz�s';
$strings['Manage Reservations'] ='Vizsg�lat Kezel�s';
$strings['Email Users'] ='K�rlev�l a Felhaszn�l�knak';
$strings['Export Database Data'] = 'Adatb�zis Adatok Export�l�sa';
$strings['Reset Password'] = 'Jelsz� Vissza�ll�t�sa';
$strings['System Administration'] = 'Rendszer Adminisztr�ci�';
$strings['Successful update'] = 'Sikeres Friss�t�s!';
$strings['Update failed!'] = 'Sikeretlen Friss�t�s!';
$strings['Manage Blackout Times'] = 'Tiltott Id�pont Kezel�s';
$strings['Forgot Password'] = 'Elfelejtett Jelsz�';
$strings['Manage My Email Contacts'] = 'Email Kapcsolatok Kezel�se';
$strings['Choose Date'] = 'V�lasszon D�tumot';
$strings['Modify My Profile'] = 'Profil M�dos�t�sa';
$strings['Register'] = 'Regisztr�ci�';
$strings['Processing Blackout'] = '�rv�nytelen Id�szak Feldolgoz�sa';
$strings['Processing Reservation'] = 'Vizsg�lat Feldolgoz�sa';
$strings['Online Scheduler [Read-only Mode]'] = 'Online El�jegyz�s [Csak N�zeget�s]';
$strings['Online Scheduler'] = 'Online El�jegyz�s';
$strings['phpScheduleIt Statistics'] = 'phpScheduleIt Statisztik�k';
$strings['User Info'] = 'Felhaszn�l� Inform�ci�:';

$strings['Could not determine tool'] = 'Meghat�rozhatatlan eszk�z. T�rjen vissza a Vez�rl� Pulthoz �s pr�b�lkozzon ism�t.';
$strings['This is only accessable to the administrator'] = 'Csak az adminisztr�tor sz�m�ra el�rhet�';
$strings['Back to My Control Panel'] = 'Vissza a Vez�rl� Pulthoz';
$strings['That schedule is not available.'] = 'A v�lasztott El�jegyz�s nem el�rhet�.';
$strings['You did not select any schedules to delete.'] = 'Nem v�lasztotta ki a t�rlend� El�jegyz�st.';
$strings['You did not select any members to delete.'] = 'Nem v�lasztotta ki a t�rlend� Felhaszn�l�t.';
$strings['You did not select any resources to delete.'] = 'Nem v�lasztotta ki a t�rlend� Kontingenst.';
$strings['Schedule title is required.'] = 'Az El�jegyz�s nev�nek megad�sa k�telez�.';
$strings['Invalid start/end times'] = '�rv�nytelen kezd�s/befejez�s';
$strings['View days is required'] = 'Megjelen�tend� napok sz�m�nak megad�sa k�telez�';
$strings['Day offset is required'] = 'El�jegyz�si Offset megad�sa k�telez�';
$strings['Admin email is required'] = 'Az Admin email megad�sa k�telez�';
$strings['Resource name is required.'] = 'A Kontingens megnevez�se k�telez�.';
$strings['Valid schedule must be selected'] = '�rv�nyes El�jegyz�s nev�nek megad�sa k�telez�';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'A legr�videbb vizsg�lati id� nem haladhatja meg a maxim�lis id�tartamot.';
$strings['Your request was processed successfully.'] = 'A k�r�s�t a Rendszer sikeresen feldolgozta.';
$strings['Go back to system administration'] = 'Vissza a Rendszer Adminisztr�ci�hoz';
$strings['Or wait to be automatically redirected there.'] = 'Vagy v�rja meg, am�g automatikusan �tir�ny�todik.';
$strings['There were problems processing your request.'] = 'Hiba t�rt�nt a k�r�s feldolgoz�s k�zben.';
$strings['Please go back and correct any errors.'] = 'K�rem menjen vissza �s jav�tson minden hib�t.';
$strings['Login to view details and place reservations'] = 'A r�szletek megtekint�s�hez �s vizsg�lat el�jegyz�s�hez jelentkezzen be';
$strings['Memberid is not available.'] = 'Az Azonos�t�: %s nem haszn�lhat�.';

$strings['Schedule Title'] = 'El�jegyz�s Megnevez�se';
$strings['Start Time'] = 'Kezd� id�pont';
$strings['End Time'] = 'Befejez� id�pont';
$strings['Time Span'] = 'Id�tartam';
$strings['Weekday Start'] = 'A H�t Kezd� Napja';
$strings['Admin Email'] = 'Admin Email';

$strings['Default'] = 'Alap�rtelmezett';
$strings['Reset'] = 'Vissza�ll�t';
$strings['Edit'] = 'Szerkeszt�s';
$strings['Delete'] = 'T�rl�s';
$strings['Cancel'] = 'M�gse';
$strings['View'] = 'N�zet';
$strings['Modify'] = 'M�dos�t�s';
$strings['Save'] = 'Ment�s';
$strings['Back'] = 'Vissza';
$strings['Next'] = 'El�re';
$strings['Close Window'] = 'Ablak Bez�r�sa';
$strings['Search'] = 'Keres�s';
$strings['Clear'] = '�res';

$strings['Days to Show'] = 'Megjelen�tend� Napok Sz�ma';
$strings['Reservation Offset'] = 'El�jegyz�si Offset';
$strings['Hidden'] = 'Rejtett';
$strings['Show Summary'] = '�sszegz�s Megjelen�t�se';
$strings['Add Schedule'] = '�j El�jegyz�s Hozz�ad�sa';
$strings['Edit Schedule'] = 'El�jegyz�s Szerkeszt�se';
$strings['No'] = 'Nem';
$strings['Yes'] = 'Igen';
$strings['Name'] = 'N�v';
$strings['First Name'] = 'Vezet�kn�v';
$strings['Last Name'] = 'Keresztn�v';
$strings['Resource Name'] = 'Kontingens Neve';
$strings['Email'] = 'Email';
$strings['Institution'] = 'Int�zm�ny';
$strings['Phone'] = 'Telefon';
$strings['Password'] = 'Jelsz�';
$strings['Permissions'] = 'Permissions';
$strings['View information about'] = 'T�j�koztat�s megtekint�se a k�vetkez�r�l: %s %s';
$strings['Send email to'] = 'Email k�ld�se a k�vetkez�nek: %s %s';
$strings['Reset password for'] = 'Reset password for %s %s';
$strings['Edit permissions for'] = 'Edit permissions for %s %s';
$strings['Position'] = 'Beoszt�s';
$strings['Password (6 char min)'] = 'Jelsz� (minimum 6 bet�)';
$strings['Re-Enter Password'] = 'A jelsz� ism�telt megad�sa';

$strings['Sort by descending last name'] = 'Cs�kken� sorrend a Keresztn�v alapj�n';
$strings['Sort by descending email address'] = 'Cs�kken� sorrend az Email c�m alapj�n';
$strings['Sort by descending institution'] = 'Cs�kken� sorrend az Int�zm�ny megnevez�se alapj�n';
$strings['Sort by ascending last name'] = 'Emelked� sorrend a Keresztn�v alapj�n';
$strings['Sort by ascending email address'] = 'Emelked� sorrend az Email c�m alapj�n';
$strings['Sort by ascending institution'] = 'Emelked� sorrend az Int�zm�ny megnevez�se alapj�n';
$strings['Sort by descending resource name'] = 'Cs�kken� sorrend a Kontingens neve alapj�n';
$strings['Sort by descending location'] = 'Cs�kken� sorrend Helysz�n alapj�n';
$strings['Sort by descending schedule title'] = 'Cs�kken� sorrend az El�jegyz�s megnevez�se alapj�n';
$strings['Sort by ascending resource name'] = 'Emelked� sorrend a Kontingens neve alapj�n';
$strings['Sort by ascending location'] = 'Emelked� sorrend Helysz�n alapj�n';
$strings['Sort by ascending schedule title'] = 'Emelked� sorrend az El�jegyz�s megnevez�se alapj�n';
$strings['Sort by descending date'] = 'Cs�kken� sorrend a d�tum alapj�n';
$strings['Sort by descending user name'] = 'Cs�kken� sorrend Felhaszn�l� n�v alapj�n';
$strings['Sort by descending resource name'] = 'Cs�kken� sorrend a Kontingens neve alapj�n';
$strings['Sort by descending start time'] = 'Cs�kken� sorrend a Kezd� id�pont alapj�n';
$strings['Sort by descending end time'] = 'Cs�kken� sorrend a Befejez� id�pont alapj�n';
$strings['Sort by ascending date'] = 'Emelked� sorrend d�tum alapj�n';
$strings['Sort by ascending user name'] = 'Emelked� sorrend a Felhaszn�l� n�v alapj�n';
$strings['Sort by ascending resource name'] = 'Emelked� sorrend a Kontingens neve alapj�n';
$strings['Sort by ascending start time'] = 'Emelked� sorrend a Kezd� id�pont alapj�n';
$strings['Sort by ascending end time'] = 'Emelked� sorrend a Befejez� id�pont alapj�n';
$strings['Sort by descending created time'] = 'Cs�kken� sorrend a L�trehoz�s d�tuma alapj�n';
$strings['Sort by ascending created time'] = 'Emelked� sorrend a L�trehoz�s d�tuma alapj�n';
$strings['Sort by descending last modified time'] = 'Cs�kken� sorrend az utols� M�dos�t�s ideje alapj�n';
$strings['Sort by ascending last modified time'] = 'Emelked� sorrend az utols� M�dos�t�s ideje alapj�n';

$strings['Search Users'] = 'Felhaszn�l� Keres�se';
$strings['Location'] = 'Helysz�n';
$strings['Schedule'] = 'El�jegyz�s';
$strings['Phone'] = 'Telefon';
$strings['Notes'] = 'Megjegyz�s';
$strings['Status'] = '�llapot';
$strings['All Schedules'] = 'Minden El�jegyz�s';
$strings['All Resources'] = 'Minden Kontingens';
$strings['All Users'] = 'Minden Felhaszn�l�';

$strings['Edit data for'] = 'A k�vetkez� adatainak szerkeszt�se: %s';
$strings['Active'] = 'Akt�v';
$strings['Inactive'] = 'Inakt�v';
$strings['Toggle this resource active/inactive'] = 'A Kontingens �llapot�nak ';
$strings['Minimum Reservation Time'] = 'Minimum Vizsg�lati id�tartam';
$strings['Maximum Reservation Time'] = 'Maximum Vizsg�lati id�tartam';
$strings['Auto-assign permission'] = 'Jogosults�gok Automatikus Kioszt�sa';
$strings['Add Resource'] = 'Kontingens Hozz�ad�sa';
$strings['Edit Resource'] = 'Kontingens Szerkeszt�se';
$strings['Allowed'] = 'Enged�lyezett';
$strings['Notify user'] = 'Felhaszn�l� �rtes�t�se';
$strings['User Reservations'] = 'Felhaszn�l� Viszg�latai';
$strings['Date'] = 'D�tum';
$strings['User'] = 'Felhaszn�l�';
$strings['Email Users'] = 'Email Users';
$strings['Subject'] = 'T�rgy';
$strings['Message'] = 'Sz�veg';
$strings['Please select users'] = 'V�lasszon Felhaszn�l�t';
$strings['Send Email'] = 'Email K�ld�se';
$strings['problem sending email'] = 'Sajnos probl�ma mer�lt fel az email k�ld�se k�zben. K�rem pr�b�lja �jra k�s�bb.';
$strings['The email sent successfully.'] = 'Az emailt siker�lt post�zni.';
$strings['do not refresh page'] = 'K�rem <u>NE</u> friss�tse ezt az oldalt, mert az email �jra elk�ld�sre ker�l.';
$strings['Return to email management'] = 'Visszat�r�s az Email Kezel�shez';
$strings['Please select which tables and fields to export'] = 'K�rem v�lassza ki, hogy melyik t�bl�t �s mez�t k�v�nja export�lni:';
$strings['all fields'] = '- minden mez� -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Sima sz�veg';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Adatok Export�l�sa';
$strings['Reset Password for'] = '%s Jelszav�nak vissza�ll�t�sa';
$strings['Please edit your profile'] = 'K�rem hajtsa v�gre Profilj�n a k�v�nt v�ltoztat�sokat';
$strings['Please register'] = 'K�rem Regisztr�ljon';
$strings['Email address (this will be your login)'] = 'Email c�m (ez lesz az Azonos�t�ja)';
$strings['Keep me logged in'] = 'A Rendszer �rrizen meg bejelentkezett �llapotban <br/>(cookie t�mogat�s sz�ks�ges)';
$strings['Edit Profile'] = 'Profil Szerkeszt�se';
$strings['Register'] = 'Regisztr�ci�';
$strings['Please Log In'] = 'K�rem Jelentkezzen Be';
$strings['Email address'] = 'Email C�m';
$strings['Password'] = 'Jelsz�';
$strings['First time user'] = 'Els� Alkalom?';
$strings['Click here to register'] = 'Kattintson ide a regisztr�ci�hoz';
$strings['Register for phpScheduleIt'] = 'Regisztr�ci� a phpScheduleIt Rendszerbe';
$strings['Log In'] = 'Bejelentkez�s';
$strings['View Schedule'] = 'El�jegyz�sek Megtekint�se';
$strings['View a read-only version of the schedule'] = 'Megtekint�s csak olvashat� m�dban';
$strings['I Forgot My Password'] = 'Elfelejtett Jelsz�';
$strings['Retreive lost password'] = 'Elfelejtett jelsz� elk�r�se';
$strings['Get online help'] = 'Online Seg�ts�g';
$strings['Language'] = 'Nyelv';
$strings['(Default)'] = '(Alap�rtelmezett)';

$strings['My Announcements'] = 'Bejelent�sek';
$strings['My Reservations'] = 'Vizsg�latok';
$strings['My Permissions'] = 'Jogosults�gok';
$strings['My Quick Links'] = 'Gyors Linkek';
$strings['Announcements as of'] = 'Bejelent�sek %s';
$strings['There are no announcements.'] = 'Nincsen Bejelent�s.';
$strings['Resource'] = 'Kontingens';
$strings['Created'] = 'L�trehozva';
$strings['Last Modified'] = 'Utolj�ra M�dos�tva';
$strings['View this reservation'] = 'Viszg�lat megtekint�se';
$strings['Modify this reservation'] = 'Vizsg�lat m�dos�t�sa';
$strings['Delete this reservation'] = 'Viszg�lat t�rl�se';
$strings['Go to the Online Scheduler'] = 'Ugr�s az Online El�jegyz�sre';
$strings['Change My Profile Information/Password'] = 'Profile Szerkeszt�se/Jelsz� Megv�ltoztat�sa';
$strings['Manage My Email Preferences'] = 'Email Be�ll�t�sok Szerkeszt�se';
$strings['Manage Blackout Times'] = 'Tiltott Id�pont Kezel�s';
$strings['Mass Email Users'] = 'Mass Email Users';
$strings['Search Scheduled Resource Usage'] = 'Search Scheduled Resource Usage';
$strings['Export Database Content'] = 'Adatb�zis Tartalom Export�l�sa';
$strings['View System Stats'] = 'Rendszer Statisztika Megtekint�se';
$strings['Email Administrator'] = 'Email K�ld�se az Adminisztr�tornak';

$strings['Email me when'] = 'Email k�ld�se a k�vetkez� esetben:';
$strings['I place a reservation'] = 'Vizsg�lat el�jegyz�se';
$strings['My reservation is modified'] = 'El�jegyzett vizsg�lat m�dos�tt�sa';
$strings['My reservation is deleted'] = 'El�jegyzett vizsg�lat t�rl�se';
$strings['I prefer'] = 'El�nyben r�szes�l:';
$strings['Your email preferences were successfully saved'] = 'Az email be�ll�t�sok sikeresen t�rol�sra ker�ltek!';
$strings['Return to My Control Panel'] = 'Vissza a Vez�rl� Pulthoz';

$strings['Please select the starting and ending times'] = 'K�rem v�lassza ki a kezd� �s befejez� id�pontokat:';
$strings['Please change the starting and ending times'] = 'K�rem m�dos�tsa a kezd� �s a befejez� id�pontokat:';
$strings['Reserved time'] = 'Fenntartott id�tartam:';
$strings['Minimum Reservation Length'] = 'Minimum Vizsg�lati Id�:';
$strings['Maximum Reservation Length'] = 'Maximum Vizsg�lati Id�:';
$strings['Reserved for'] = 'Fenntartva:';
$strings['Will be reserved for'] = 'Nem ker�l kioszt�sra:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Az ism�telt el�fordul�sok ?';
$strings['Delete?'] = 'T�rl�s?';
$strings['Never'] = '-- Soha --';
$strings['Days'] = 'Naponta';
$strings['Weeks'] = 'Hetenk�nt';
$strings['Months (date)'] = 'H�napban (D�tum)';
$strings['Months (day)'] = 'H�napban (Nap)';
$strings['First Days'] = 'Els� nap';
$strings['Second Days'] = 'M�sodik napon';
$strings['Third Days'] = 'Harmadik napon';
$strings['Fourth Days'] = 'Negyedik napon';
$strings['Last Days'] = 'Utols� nap';
$strings['Repeat every'] = 'Ism�telt el�fordul�s minden:';
$strings['Repeat on'] = 'Ism�tl�dj�n:';
$strings['Repeat until date'] = 'Ism�tl�dj�n a k�vetkez� ideig:';
$strings['Choose Date'] = 'V�lasszon d�tumot';
$strings['Summary'] = '�sszegz�s';

$strings['View schedule'] = 'El�jegyz�s megtekint�se:';
$strings['My Reservations'] = 'Saj�t Vizsg�lat';
$strings['My Past Reservations'] = 'Lej�rt Saj�t Vizsg�lat';
$strings['Other Reservations'] = 'Egy�b Vizsg�lat';
$strings['Other Past Reservations'] = 'Lej�rt Egy�b Vizsg�lat';
$strings['Blacked Out Time'] = 'Tiltott Id�pont';
$strings['Set blackout times'] = 'Id�pont Tilt�sa %s %s'; 
$strings['Reserve on'] = 'Reserve %s on %s';
$strings['Prev Week'] = '&laquo; El�z� H�t';
$strings['Jump 1 week back'] = '1 H�ttel Vissza';
$strings['Prev days'] = '&#8249; El�z� %d nap';
$strings['Previous days'] = '&#8249; El�z� %d nap';
$strings['This Week'] = 'Aktu�lis H�t';
$strings['Jump to this week'] = 'Ugr�s erre a h�tre';
$strings['Next days'] = 'K�vetkez� %d nap &#8250;';
$strings['Next Week'] = 'K�vetkez� h�t &raquo;';
$strings['Jump To Date'] = 'Ugr�s erre a napra';
$strings['View Monthly Calendar'] = 'Napt�r Megtekint�se Havi Bont�sban';
$strings['Open up a navigational calendar'] = 'Navig�l� napt�r megnyit�sa';

$strings['View stats for schedule'] = 'El�jegyz�s statisztik�inak megjelen�t�se:';
$strings['At A Glance'] = 'Egy Pillantra';
$strings['Total Users'] = '�sszes Felhaszn�l�:';
$strings['Total Resources'] = '�sszes Kontingens:';
$strings['Total Reservations'] = '�sszes Vizsg�lat:';
$strings['Max Reservation'] = 'Maximum Vizsg�lat:';
$strings['Min Reservation'] = 'Minimum Vizsg�lat:';
$strings['Avg Reservation'] = '�tlagos Vizsg�lat:';
$strings['Most Active Resource'] = 'Legakt�vabb Kontingens:';
$strings['Most Active User'] = 'Legakt�vabb Felhaszn�l�k:';
$strings['System Stats'] = 'Rendszer Statisztika';
$strings['phpScheduleIt version'] = 'phpScheduleIt verzi�:';
$strings['Database backend'] = 'Adatb�zis backend:';
$strings['Database name'] = 'Adatb�zis n�v:';
$strings['PHP version'] = 'PHP verzi�sz�m:';
$strings['Server OS'] = 'Szerver OS:';
$strings['Server name'] = 'Szerver n�v:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt g��k�r k�nyvt�r:';
$strings['Using permissions'] = 'Jogosults�g kezel�s haszn�lata:';
$strings['Using logging'] = 'Napl�z�s haszn�lata:';
$strings['Log file'] = 'Napl� f�jl:';
$strings['Admin email address'] = 'Admin email c�m:';
$strings['Tech email address'] = 'Tech email c�m:';
$strings['CC email addresses'] = 'CC email c�m:';
$strings['Reservation start time'] = 'Vizsg�lat kezd� id�pont:';
$strings['Reservation end time'] = 'Vizsg�lat v�gs� id�pont:';
$strings['Days shown at a time'] = 'Egyszerre megjeln�tett napok:';
$strings['Reservations'] = 'Vizsg�lati el�jegyz�sek';
$strings['Return to top'] = 'Vissza a tetej�re';
$strings['for'] = 'miatt';

$strings['Select Search Criteria'] = 'Select Search Criteria';
$strings['Schedules'] = 'El�jegyz�sek:';
$strings['All Schedules'] = '�sszes El�jegyz�s';
$strings['Hold CTRL to select multiple'] = 'T�bbsz�r�s v�laszt�shoz tartsa lenyomva a CTRL-t';
$strings['Users'] = 'Felhaszn�l�k:';
$strings['All Users'] = '�sszes Felhaszn�l�';
$strings['Resources'] = 'Kontingens:';
$strings['All Resources'] = '�sszes Kontingens';
$strings['Starting Date'] = 'Kezd� D�tum:';
$strings['Ending Date'] = 'Befejez� D�tum:';
$strings['Starting Time'] = 'Kezd� Id�pont:';
$strings['Ending Time'] = 'Befejez� Id�pont:';
$strings['Output Type'] = 'Kijelz�si M�d:';
$strings['Manage'] = 'Kezel�s';
$strings['Total Time'] = '�sszes�tett Id�tartam';
$strings['Total hours'] = '�sszes�tett �r�k:';
$strings['% of total resource time'] = '%-a az �sszes Vizsg�lati Id�nek';
$strings['View these results as'] = 'Az eredm�nyek megtekint�se a k�vetkez�k�ppen:';
$strings['Edit this reservation'] = 'A Vizsg�lat szerkeszt�se';
$strings['Search Results'] = 'Keres�si Ered�nyek';
$strings['Search Resource Usage'] = 'Kontingens Kihaszn�lts�g szerinti keres�s';
$strings['Search Results found'] = 'Keres�si Eredm�nyek: %d Tal�lat';
$strings['Try a different search'] = 'Pr�b�lkozzon m�sik keres�ssel';
$strings['Search Run On'] = 'Keres�s a K�vetkez�n Futott:';
$strings['Member ID'] = 'Felhaszn�l� Azonos�t�';
$strings['Previous User'] = '&laquo; El�z� Felhaszn�l�';
$strings['Next User'] = 'K�vetkez� Felhaszn�l� &raquo;';

$strings['No results'] = 'Nincs Tal�lat';
$strings['That record could not be found.'] = 'Ilyen Bejegyz�s nem tal�lhat�..';
$strings['This blackout is not recurring.'] = 'A Tiltott Id�pont nem ism�tl�dik.';
$strings['This reservation is not recurring.'] = 'A Vizsg�lat nem ism�tl�dik.';
$strings['There are no records in the table.'] = 'Nincs egy Bejegyz�s sem a k�vetkez� T�bl�ban: %s.';
$strings['You do not have any reservations scheduled.'] = 'Nem tal�lhat� �n �ltal el�jegyzett Vizsg�lat.';
$strings['You do not have permission to use any resources.'] = '�nnek egyik Kontingens haszn�lat�hoz sincs joga.';
$strings['No resources in the database.'] = 'Nincs Kontingens az adatb�zisban.';
$strings['There was an error executing your query'] = 'Hiba t�rt�nt a k�r�s feldolgoz�sa k�zben:';

$strings['That cookie seems to be invalid'] = 'A Cookie �rv�nytelennek t�nik';
$strings['We could not find that email in our database.'] = 'A megadott Email c�m nem tal�lhat� az adatb�zisban.';
$strings['That password did not match the one in our database.'] = 'A megadott Jelsz� nem egyezik az adatb�zisban szerepl�vel.';
$strings['You can try'] = '<br />You can try:<br />Registering an email address.<br />Or:<br />Try logging in again.';
$strings['A new user has been added'] = 'Az �j Felhaszn�l� bejegyz�sre ker�lt a Rendszerbe';
$strings['You have successfully registered'] = 'Sikeres Regisztr�ci�!';
$strings['Continue'] = 'Folytat�s...';
$strings['Your profile has been successfully updated!'] = 'A Profil sikeresen friss�t�sre ker�lt!';
$strings['Please return to My Control Panel'] = 'K�rem t�rjen vissza a Vez�rl� Pulthoz';
$strings['Valid email address is required.'] = '- Val�s email c�m megad�sa sz�ks�ges.';
$strings['First name is required.'] = '- Vezet�kn�v megad�sa k�telez�.';
$strings['Last name is required.'] = '- Keresztn�v megad�sa k�telez�.';
$strings['Phone number is required.'] = '- Telefonsz�m megad�sa k�telez�.';
$strings['That email is taken already.'] = '- A megadott email c�m foglalt.<br />K�rem v�lasszon egy m�sikat.';
$strings['Min 6 character password is required.'] = '- Min 6 bet� hossz� jelsz� megad�sa sz�ks�ges.';
$strings['Passwords do not match.'] = '- A jelsz� nem egyezik.';

$strings['Per page'] = 'Oldalank�nt:';
$strings['Page'] = 'Oldal:';

$strings['Your reservation was successfully created'] = 'A Vizsg�lat sikeresen bejegyz�sre ker�lt';
$strings['Your reservation was successfully modified'] = 'A Vizsg�lat sikeresen m�dos�t�sra ker�lt';
$strings['Your reservation was successfully deleted'] = 'A Vizsg�lat sikeresen t�rl�sre ker�lt';
$strings['Your blackout was successfully created'] = 'A Tiltott Id�pont sikeresen bejegyz�sre ker�lt';
$strings['Your blackout was successfully modified'] = 'A Tiltott Id�pont sikeresen m�dos�t�sra ker�lt';
$strings['Your blackout was successfully deleted'] = 'A Tiltott Id�pont sikeresen t�rl�sre ker�lt';
$strings['for the follwing dates'] = 'az al�bbi id�pont(ok)ban:';
$strings['Start time must be less than end time'] = 'A Kezd� Id�pontnak kor�bbinak kell lenni a Befejez� Id�pontn�l';
$strings['Current start time is'] = 'Aktu�lis Kezd� Id�pont:';
$strings['Current end time is'] = 'Aktu�lis Befejez� Id�pont:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'A Vizsg�lat megadott hossza nem teljes�ti az id�tartammal kapcsolatban\ meghat�rozott felt�teleket.';
$strings['Your reservation is'] = 'A Vizsg�lat:';
$strings['Minimum reservation length'] = 'Minim�lis Vizsg�lati Id�tartam:';
$strings['Maximum reservation length'] = 'Maxim�lis Vizsg�lati Id�tartam:';
$strings['You do not have permission to use this resource.'] = 'Nincs Jogosults�ga az adott Kontingens haszn�lat�hoz.';
$strings['reserved or unavailable'] = '%s -t�l %s -ig a(z) %s m�r foglalt vagy nem haszn�lhat�.';
$strings['Reservation created for'] = 'Vizsg�lat l�trehozva %s';
$strings['Reservation modified for'] = 'Vizsg�lat m�dos�tva %s';
$strings['Reservation deleted for'] = 'Vizsg�lat t�r�lve %s';
$strings['created'] = 'l�trehozva';
$strings['modified'] = 'm�dos�tva';
$strings['deleted'] = 't�r�lve';
$strings['Reservation #'] = 'Vizsg�lat #';
$strings['Contact'] = 'Kapcsolat';
$strings['Reservation created'] = 'Viszg�lat l�trehozva';
$strings['Reservation modified'] = 'Vizsg�lat m�dos�tva';
$strings['Reservation deleted'] = 'Vizsg�lat t�r�lve';

$strings['Reservations by month'] = 'Vizsg�latok H�napos Bont�sban';
$strings['Reservations by day of the week'] = 'Vizsg�latok Napos Bont�sban';
$strings['Reservations per month'] = 'Vizsg�latok H�naponk�nt';
$strings['Reservations per user'] = 'Vizsg�latok Felhaszn�l�nk�nt';
$strings['Reservations per resource'] = 'Vizsg�latok Kontingensenk�nt';
$strings['Reservations per start time'] = 'Vizsg�latok Idul� Id�pont Alapj�n';
$strings['Reservations per end time'] = 'Vizsg�latok Befejez� Id�pont Alapj�n';
$strings['[All Reservations]'] = '[Minden Vizsg�lat]';

$strings['Permissions Updated'] = 'Friss�ltek a Jogosults�gok';
$strings['Your permissions have been updated'] = '%s Jogosult�sgok friss�t�sre ker�ltek';
$strings['You now do not have permission to use any resources.'] = 'Egy Kontingens haszn�lat�hoz sincs Jogosults�ga.';
$strings['You now have permission to use the following resources'] = 'Mostant�l Jogosults�ggal rendelkezik a k�vetkez� Kontingens(ek) haszn�lat�ra:';
$strings['Please contact with any questions.'] = 'K�rd�s eset�n k�rem vegye fel a kapcsolatot a k�vetkez�vel: %s.';
$strings['Password Reset'] = 'Jelsz� Vissza�ll�tva';

$strings['This will change your password to a new, randomly generated one.'] = 'Ezzel Jelszava v�letlenszer�re fog v�ltozni.';
$strings['your new password will be set'] = 'Miut�n megadta az Email c�m�t �s a "Jelsz� Megv�ltoztat�sa" gombra kattint, az �jdons�lt Jelszav�t a rendszer regisztr�lja �s elk�ldi �nnek Email-ben.';
$strings['Change Password'] = 'Jelsz� Megv�ltoztat�sa';
$strings['Sorry, we could not find that user in the database.'] = 'Sajnos a megadott Felhaszn�l� nem tal�lhat� meg az adatb�zisban.';
$strings['Your New Password'] = 'Az �n �j %s Jelszava';
$strings['Your new passsword has been emailed to you.'] = 'Elk�sz�lt!<br />'
    			. 'Az �jdons�lt Jelszav�t a Rendszer elk�ldte �nnek.<br />'
    			. 'K�rem ellen�rizze postafi�kj�t �s a ny�t�lapon Kattintson a <a href="index.php">Bejelentkez�s</a> linkre.'
    			. ' Haszn�lja az �j Jelszav�t �s v�ltoztassa meg azonnal a &quot;Profil Szerkeszt�se/Jelsz� Megv�ltoztat�sa&quot;'
    			. ' men�pont alatt a Vez�rl� Pultban.';

$strings['You are not logged in!'] = 'Nincs Bejelentkezve!';

$strings['Setup'] = 'Telep�t�s';
$strings['Please log into your database'] = 'K�rem jelentkezzen be az adatb�zisba';
$strings['Enter database root username'] = 'Adja meg az adatb�zis felhaszn�l�j�nak azonos�t�j�t:';
$strings['Enter database root password'] = 'Adja meg az adatb�zis felhaszn�l�j�nak jelszav�t:';
$strings['Login to database'] = 'Bejelentkez�s az adatb�zisba';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'Az adatb�zis root felhaszn�l�j�nak megad�sa <b>nem</b> sz�ks�ges. B�rmely adatb�zis felhaszn�l� megfelel, akinek van jogosults�ga a t�bl�k l�trehoz�s�ra.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Ezzel telep�t�sre ker�l minden sz�ks�ges phpScheduleIt Adatb�zis �s T�bla.';
$strings['It also populates any required tables.'] = 'Valamint felt�lti a sz�ks�ges T�bl�kat.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Figyelmeztet�s: MINDEN KOR�BBI ADAT T�RL�SRE KER�L A phpScheduleIt ADATB�ZISB�L!';
$strings['Not a valid database type in the config.php file.'] = '�rv�nytelen adatb�zis t�pus szerepel a config.php f�jlban.';
$strings['Database user password is not set in the config.php file.'] = 'Az adatb�zis felhaszn�l�j�nak jelszava nincs megadva a config.php f�jlban.';
$strings['Database name not set in the config.php file.'] = 'Az adatb�zis neve nincs megadva config.php f�jlban.';
$strings['Successfully connected as'] = 'Sikeres kapcsol�d�s a k�vetkez� n�ven';
$strings['Create tables'] = 'T�bl�k l�trehoz�sa &gt;';
$strings['There were errors during the install.'] = 'Hiba t�rt�nt a telep�t�s sor�n. Elk�pzelhet�, hogy a phpScheduleIt m�gis m�k�dni fog, amennyiben ez(ek) csak kis jelent�s�g�ek volt(ak).<br/><br/>'
	. 'K�rd�sekkel keresse fel a projekt f�rum�t a <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>-on.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'A phpScheduleIt telep�t�se sikeresen befejez�d�tt �s a Rendszer haszn�latra k�sz.';
$strings['Thank you for using phpScheduleIt'] = 'K�rem bizonyosodjon meg r�la, hogy a \'install\' K�NYVT�RAT TELJESEN ELT�VOL�TOTTA.'
	. ' A k�nyvt�r t�rl�se alapvet� biztons�gi k�rd�s, mert bizalmas inform�ci�kat (adatb�zis jelsz�) tartalmaz.'
	. ' Elmulaszt�sa sz�les biztons�gi r�st hagy nyitva, melyen �t b�rmikor bet�rhetnek az adatb�zisba!'
	. '<br /><br />'
	. 'K�sz�net, ami�rt a phpScheduleIt-et v�lasztotta!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'A phpScheduleIt Rendszer ennek seg�ts�g�vel 0.9.3-r�l 1.0.0-ra Korszer�s�thet�.';
$strings['There is no way to undo this action'] = 'A k�vetkez� beavatkoz�s vissza�ll�t�s�ra nincs lehet�s�g!';
$strings['Click to proceed'] = 'Kattintson a tov�bbl�p�shez';
$strings['This version has already been upgraded to 1.0.0.'] = 'A jelen Rendszer m�r most is 1.0.0-s verzi�j�.';
$strings['Please delete this file.'] = 'K�rem t�r�lje ezt a f�jlt.';
$strings['Successful update'] = 'A Korszer�s�t�s sikerrel j�rt';
$strings['Patch completed successfully'] = 'A foltoz�s sikeresen befejez�d�tt';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'A k�vetkez� bet�lti a phpScheduleIt 1.0.0 sz�m�ra sz�ks�ges mez�ket �s n�h�ny 0.9.9-es verzi�ban megtal�lhat� data bug-ot foltoz.'
		. '<br />Csak akkor sz�ks�ges lefuttatni, ha k�zi SQL friss�t�st hajtott v�gre, vagy 0.9.9-r�l korszer�s�t';

// @since 1.0.0 RC1
$strings['If no value is specified, the default password set in the config file will be used.'] = 'Amennyiben nem ad meg semmit, a konfigur�ci�s f�jlban szerepl� jelsz� lesz az �rv�nyes.';
$strings['Notify user that password has been changed?'] = 'A felhaszn�l� �rtes�t�se a jelszava megv�ltoz�s�r�l.';

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s \r\n"
				. "Sikeresen regisztr�lt a Rendszerbe a k�vetkez� adatokkal:\r\n"
				. "N�v: %s %s \r\n"
				. "Telefon: %s \r\n"
				. "Int�zm�ny: %s \r\n"
				. "Beoszt�s: %s \r\n\r\n"
				. "K�rem jelentkezzen be a Rendszerbe a k�vetkez� helyen:\r\n"
				. "%s \r\n\r\n"
				. "A Vez�rl� Pultban linket tal�l az Online El�jegyz�sre is �s a Profil-j�t szerkesztheti.\r\n\r\n"
				. "K�rem forduljon a k�vetkez�h�z Viszg�latokkal �s Kontingensekkel kapcsolatos k�rd�seivel: %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Adminisztr�tor,\r\n\r\n"
					. "Egy �j Felhaszn�l� regisztr�lt az al�bbi inform�ci�kkal:\r\n"
					. "Email c�m: %s \r\n"
					. "N�v: %s %s \r\n"
					. "Telefon: %s \r\n"
					. "Int�zm�ny: %s \r\n"
					. "Beoszt�s: %s \r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Sikeres el�jegyz�s %s Vizsg�lat #%s.\r\n\r\n<br/><br/>"
			. "K�rem hivatkozzon a Vizsg�latot azonos�t� sz�mra, amennyiben felveszi a kapcsolatot az Adminisztr�torral.\r\n\r\n<br/><br/>"
			. "K�vetkez� d�tummal: %s %s-t�l %s-ig %s sz�m�ra"
			. " a %s helysz�nen %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "A Vizsg�lat a k�vetkez� napokon fog ism�tl�dni:\r\n<br/>";
$email['reservation_activity_3'] = "A csoportban el�fordul� �sszes ism�tl�d� Viszg�lat szint�n %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "A k�vetkez� �sszegz�st adt�k meg a Vizsg�lat el�jegyz�sekor:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Amennyiben ez egy t�ved�s, k�rem �rtes�tse az Adminisztr�tort: %s"
			. " vagy telefon�ljon a k�vetkez� sz�mra: %s.\r\n\r\n<br/><br/>"
			. "Az el�jegyzett Vizsg�lat r�szleteit b�rmikor megn�zheti vagy m�dos�thatja, ha"
			. " Bejelentkezik %s Rendszerbe a k�vetkez� helyen:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "A technikai jelleg� k�rd�sekkel forduljon a k�vetkez�h�z: <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Az �n %s Jelszav�t az Adminisztr�tor vissza�ll�totta.\r\n\r\n"
			. "Az �n ideiglenes jelszava:\r\n\r\n %s\r\n\r\n"
			. "K�rem haszn�lja ezt (m�solja �s illessze be, hogy biztosan helyes legyen) a Bel�p�shez %s a k�vetkez� helyen: %s"
			. " �s v�ltoztassa meg nyomban a Vez�rl� Pult 'Profil Szerkeszt�se/Jelsz� Megv�ltoztat�sa' men�pontj�ban.\r\n\r\n"
			. "K�rd�seivel k�rem keresse meg a k�vetkez�t: %s.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Az �n �j Jelszava az %s Azonos�t�j�hoz a k�vetkez�:\r\n\r\n"
            . "%s\r\n\r\n"
            . "K�rem jelentkezzen be a k�vetkez� helyen: %s "
            . "�s haszn�lja az �jdons�lt Jelszav�t "
            . "(M�solja �s illessze be, hogy biztosan hib�tlanul ker�lj�n bevitelre) "
            . "majd a jelsz� azonnali megv�ltoztat�s�hoz keresse fel a "
            . "Profil Szerkeszt�se/Jelsz� Megv�ltoztat�sa men�pontot "
            . "a Vez�rl� Pultban.\r\n\r\n"
            . "K�rd�seivel forduljon a k�vetkez�h�z: %s.";
?>