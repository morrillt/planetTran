<?php
/**
* Italian (it) translation file.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator <emiliano.meneghin@tin.it>
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
$days_full = array('Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato');
// The three letter abbreviation
$days_abbr = array('Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab');
// The two letter abbreviation
$days_two  = array('Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa');
// The one letter abbreviation
$days_letter = array('D', 'L', 'M', 'M', 'G', 'V', 'S');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');

// The three letter month name
$months_abbr = array('Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic');

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
$strings['hours'] = 'ore';
$strings['minutes'] = 'minuti';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'dd';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'yyyy';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Amministratore';
$strings['Welcome Back'] = 'Bentornato, %s';
$strings['Log Out'] = 'Scollegati';
$strings['My Control Panel'] = 'Pannello di Controllo';
$strings['Help'] = 'Aiuto';
$strings['Manage Schedules'] = 'Amministrazione Programma';
$strings['Manage Users'] ='Amministrazione Utenti';
$strings['Manage Resources'] ='Amministrazione Risorse';
$strings['Manage User Training'] ='Amministrazione Addrestramento Utenti';
$strings['Manage Reservations'] ='Amministrazione Prenotazioni';
$strings['Email Users'] ='Email Utente';
$strings['Export Database Data'] = 'Esportazione Dati Database';
$strings['Reset Password'] = 'Reset Password';
$strings['System Administration'] = 'Amministrazione Sistema';
$strings['Successful update'] = 'Aggiornamento riuscito';
$strings['Update failed!'] = 'Aggiornamento Fallito!';
$strings['Manage Blackout Times'] = 'Amministrazione Blackout';
$strings['Forgot Password'] = 'Password Dimenticata';
$strings['Manage My Email Contacts'] = 'Amministrazione Miei Contatti Email';
$strings['Choose Date'] = 'Scegli Data';
$strings['Modify My Profile'] = 'Modifica il Mio Profilo';
$strings['Register'] = 'Registrazione';
$strings['Processing Blackout'] = 'Elaborazione Blackout';
$strings['Processing Reservation'] = 'Elaborazione Prenotazione';
$strings['Online Scheduler [Read-only Mode]'] = 'Programma Online [Modalit� solo-lettura]';
$strings['Online Scheduler'] = 'Programma online';
$strings['phpScheduleIt Statistics'] = 'Statistiche phpScheduleIt';
$strings['User Info'] = 'Informazioni Utente:';

$strings['Could not determine tool'] = 'Non riesco a trovare lo strumento. Si prega di tornare al Mio Pannello di Controllo e provare pi� tardi.';
$strings['This is only accessable to the administrator'] = 'Questo � accessibile solo per l\'amministratore';
$strings['Back to My Control Panel'] = 'Ritorna al Mio Pannello di Controllo';
$strings['That schedule is not available.'] = 'Questo programma non � disponibile.';
$strings['You did not select any schedules to delete.'] = 'Non puoi selezionare nessun programma per la cancellazione.';
$strings['You did not select any members to delete.'] = 'Non puoi selezionare nessun membro per la cancellazione.';
$strings['You did not select any resources to delete.'] = 'Non puoi salezionare nessuna risorsa per la cancellazione.';
$strings['Schedule title is required.'] = 'Il titolo del Programma � richiesto.';
$strings['Invalid start/end times'] = 'I tempi di inizio/fine sono invalidi';
$strings['View days is required'] = 'Sono richieste le viste giornaliere';
$strings['Day offset is required'] = 'L\'offset del giorno � richiesto';
$strings['Admin email is required'] = 'L\'email dell Amministratore � richiesta';
$strings['Resource name is required.'] = 'Il nome della Risorsa � richiesta.';
$strings['Valid schedule must be selected'] = 'Un programma valido deve essere selezionato';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'La prenotazione minima deve essere minore o uguale alla prenotazione massima.';
$strings['Your request was processed successfully.'] = 'La tua richiesta � stata processato correttamente.';
$strings['Go back to system administration'] = 'Ritorna all\'amministrazione del sistema';
$strings['Or wait to be automatically redirected there.'] = 'O aspetta e sarai automaticamente reindirizzato.';
$strings['There were problems processing your request.'] = 'Ci sono dei problemi nel processare la richiesta.';
$strings['Please go back and correct any errors.'] = 'Per favore tornare indietro e correggere gli errori.';
$strings['Login to view details and place reservations'] = 'Accedi per vedere i dettagli e fare le prenotazioni';
$strings['Memberid is not available.'] = 'Memberid: %s non � disponibile.';

$strings['Schedule Title'] = 'Titolo del Programma';
$strings['Start Time'] = 'Inizio';
$strings['End Time'] = 'Fine';
$strings['Time Span'] = 'Intervallo';
$strings['Weekday Start'] = 'Inizio giorno della settimana';
$strings['Admin Email'] = 'Admin Email';

$strings['Default'] = 'Default';
$strings['Reset'] = 'Reset';
$strings['Edit'] = 'Edita';
$strings['Delete'] = 'Cancella';
$strings['Cancel'] = 'Annulla';
$strings['View'] = 'Mostra';
$strings['Modify'] = 'Modifica';
$strings['Save'] = 'Salva';
$strings['Back'] = 'Indietro';
$strings['Next'] = 'Prossimo';
$strings['Close Window'] = 'Chiudi Finestra';
$strings['Search'] = 'Cerca';
$strings['Clear'] = 'Pulisci';

$strings['Days to Show'] = 'Giorni da Mostrare';
$strings['Reservation Offset'] = 'Prenotazione Offset';
$strings['Hidden'] = 'Nascosto';
$strings['Show Summary'] = 'Mostra Sommario';
$strings['Add Schedule'] = 'Aggiungi Programma';
$strings['Edit Schedule'] = 'Edita Programma';
$strings['No'] = 'No';
$strings['Yes'] = 'Si';
$strings['Name'] = 'Nome';
$strings['First Name'] = 'Nome';
$strings['Last Name'] = 'Cognome';
$strings['Resource Name'] = 'Nome Risorsa';
$strings['Email'] = 'Email';
$strings['Institution'] = 'Istituzione';
$strings['Phone'] = 'Telefono';
$strings['Password'] = 'Password';
$strings['Permissions'] = 'Permessi';
$strings['View information about'] = 'Vedi informazione circa %s %s';
$strings['Send email to'] = 'Manda email a %s %s';
$strings['Reset password for'] = 'Reset password per %s %s';
$strings['Edit permissions for'] = 'Edita permessi per %s %s';
$strings['Position'] = 'Posizione';
$strings['Password (6 char min)'] = 'Password (6 caratteri minimo)';
$strings['Re-Enter Password'] = 'Ri-Entra la Password';

$strings['Sort by descending last name'] = 'Ordina per cognome decrescente';
$strings['Sort by descending email address'] = 'Ordina per indirizzo email cognome decrescente';
$strings['Sort by descending institution'] = 'Ordina per istituzione decrescente';
$strings['Sort by ascending last name'] = 'Ordina per cognome crescente';
$strings['Sort by ascending email address'] = 'Ordina per indirizzo email crescente';
$strings['Sort by ascending institution'] = 'Ordina per istituzione crescente';
$strings['Sort by descending resource name'] = 'Ordina per nome risorsa decrescente';
$strings['Sort by descending location'] = 'Ordina per posizione decrescente';
$strings['Sort by descending schedule title'] = 'Ordina per titolo programma decrescente';
$strings['Sort by ascending resource name'] = 'Ordina per nome risorsa crescente';
$strings['Sort by ascending location'] = 'Ordina per posizione crescente';
$strings['Sort by ascending schedule title'] = 'Ordina per titolo programma crescente';
$strings['Sort by descending date'] = 'Ordina per data decrescente';
$strings['Sort by descending user name'] = 'Ordina per nome utente decrescente';
$strings['Sort by descending start time'] = 'Ordina per ora inizio decrescente';
$strings['Sort by descending end time'] = 'Ordina per fine ore decrescente';
$strings['Sort by ascending date'] = 'Ordina per data crescente';
$strings['Sort by ascending user name'] = 'Ordina per nome utente crescente';
$strings['Sort by ascending start time'] = 'Ordina per inizio ora crescente';
$strings['Sort by ascending end time'] = 'Ordina per fine ora crescente';
$strings['Sort by descending created time'] = 'Ordina per data di creazione decrescente';
$strings['Sort by ascending created time'] = 'Ordina per data di creazione crescente';
$strings['Sort by descending last modified time'] = 'Ordina per data ultima modifica decrescente';
$strings['Sort by ascending last modified time'] = 'Ordina per data ultima modifica crescente';

$strings['Search Users'] = 'Cerca Utenti';
$strings['Location'] = 'Posizione';
$strings['Schedule'] = 'Programma';
$strings['Phone'] = 'Telefono';
$strings['Notes'] = 'Note';
$strings['Status'] = 'Status';
$strings['All Schedules'] = 'Tutte i Programmi';
$strings['All Resources'] = 'Tutte le Risorse';
$strings['All Users'] = 'Tutti gli Utenti';

$strings['Edit data for'] = 'Edita dati per %s';
$strings['Active'] = 'Attivo';
$strings['Inactive'] = 'Inattivo';
$strings['Toggle this resource active/inactive'] = 'Cambia questa risorsa Toggle attiva/inattiva';
$strings['Minimum Reservation Time'] = 'Tempo Minino di Prenotazione';
$strings['Maximum Reservation Time'] = 'Tempo Massimo di Prenotazione';
$strings['Auto-assign permission'] = 'Auto-assegnazione permessi';
$strings['Add Resource'] = 'Aggiungi Risorsa';
$strings['Edit Resource'] = 'Edita Risorsa';
$strings['Allowed'] = 'Permesso';
$strings['Notify user'] = 'Notifica utente';
$strings['User Reservations'] = 'Prenotazioni Utente';
$strings['Date'] = 'Data';
$strings['User'] = 'Utente';
$strings['Email Users'] = 'Email Utente';
$strings['Subject'] = 'Oggetto';
$strings['Message'] = 'Messaggio';
$strings['Please select users'] = 'Prego selezionare gli utenti';
$strings['Send Email'] = 'Spedisci Email';
$strings['problem sending email'] = 'Spiacente, un problema � intercorso nella spesizione dell email. Prego provare pi� tardi.';
$strings['The email sent successfully.'] = 'L\'email � stata spedita con successo.';
$strings['do not refresh page'] = 'Prego <u>NON </u> aggiornare questa pagina. Facendolo si spediranno pi� email.';
$strings['Return to email management'] = 'Ritorno all\'amministrazione delle email';
$strings['Please select which tables and fields to export'] = 'Prego selezionare quali tabelle e campi saranno esportate:';
$strings['all fields'] = '- tutti i campi -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Plain text';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Esporta Dati';
$strings['Reset Password for'] = 'Azzera password per %s';
$strings['Please edit your profile'] = 'Prego editare il proprio profilo';
$strings['Please register'] = 'Prego registrasi';
$strings['Email address (this will be your login)'] = 'Email address (questo sar� il tuo login)';
$strings['Keep me logged in'] = 'Tienimi connesso <br/>(richiede cookies)';
$strings['Edit Profile'] = 'Edita Profilo';
$strings['Register'] = 'Registrazione';
$strings['Please Log In'] = 'Prego Accedi';
$strings['Email address'] = 'Indirizzo Email';
$strings['Password'] = 'Password';
$strings['First time user'] = 'La tua prima volta con questo utente?';
$strings['Click here to register'] = 'Clicca qui per registrarti';
$strings['Register for phpScheduleIt'] = 'Registrazione per phpScheduleIt';
$strings['Log In'] = 'Log In';
$strings['View Schedule'] = 'Vedi Programma';
$strings['View a read-only version of the schedule'] = 'Vedi programma in sola-lettura';
$strings['I Forgot My Password'] = 'Ho dimenticato la Mia Password';
$strings['Retreive lost password'] = 'Ottieni la password dimenticata';
$strings['Get online help'] = 'Ottieni aiuto online ';
$strings['Language'] = 'Lingua';
$strings['(Default)'] = '(Default)';

$strings['My Announcements'] = 'I Miei Annunci';
$strings['My Reservations'] = 'Le Mie Prenotazioni';
$strings['My Permissions'] = 'I Miei Permessi';
$strings['My Quick Links'] = 'I Miei Link Veloci';
$strings['Announcements as of'] = 'Annunci come del %s';
$strings['There are no announcements.'] = 'Non ci sono annunci.';
$strings['Resource'] = 'Risorse';
$strings['Created'] = 'Creato';
$strings['Last Modified'] = 'Ultima Modifica';
$strings['View this reservation'] = 'Vedi questa prenotazione';
$strings['Modify this reservation'] = 'Modifica questa prenotazione';
$strings['Delete this reservation'] = 'Cancella questa prenotazione';
$strings['Go to the Online Scheduler'] = 'Vai alla Prenotazione Online';
$strings['Change My Profile Information/Password'] = 'Cambia il Mio Profilo/Password';
$strings['Manage My Email Preferences'] = 'Amministra le Mie preferenze Email';
$strings['Manage Blackout Times'] = 'Amministrazione Blackout';
$strings['Mass Email Users'] = 'Email di Massa agli Utenti';
$strings['Search Scheduled Resource Usage'] = 'Cerca utilizzo delle risorse prenotate';
$strings['Export Database Content'] = 'Esporta contenuto Database';
$strings['View System Stats'] = 'Vedi Statistiche di Sistema';
$strings['Email Administrator'] = 'Email Amministratore';

$strings['Email me when'] = 'Manda un Email quando:';
$strings['I place a reservation'] = 'Faccio una prenotazione';
$strings['My reservation is modified'] = 'La Mia prenotazione � modificata';
$strings['My reservation is deleted'] = 'La Mia prenotazione � cancellata';
$strings['I prefer'] = 'Io preferisco:';
$strings['Your email preferences were successfully saved'] = 'Le tue preferenze email sono state salvate con successo!';
$strings['Return to My Control Panel'] = 'Ritorna al Mio Pannello di Controllo';

$strings['Please select the starting and ending times'] = 'Prego selezionare inizio e fine:';
$strings['Please change the starting and ending times'] = 'Prego cambiare inizio e fine:';
$strings['Reserved time'] = 'Periodo Riservato:';
$strings['Minimum Reservation Length'] = 'Durata Minima della Prenotazione:';
$strings['Maximum Reservation Length'] = 'Durata Massima della Prenotazione:';
$strings['Reserved for'] = 'Riservato per:';
$strings['Will be reserved for'] = 'Sar� riservato per:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Aggiornare tutti i record ricorrenti nel gruppo?';
$strings['Delete?'] = 'Cancello?';
$strings['Never'] = '-- Mai --';
$strings['Days'] = 'Giorni';
$strings['Weeks'] = 'Settimane';
$strings['Months (date)'] = 'Mesi (data)';
$strings['Months (day)'] = 'Mesi (giorno)';
$strings['First Days'] = 'Primo Giorno';
$strings['Second Days'] = 'Secondo Giorno';
$strings['Third Days'] = 'Terzo Giorno';
$strings['Fourth Days'] = 'Qaurto Giorno';
$strings['Last Days'] = 'Ultimo Giorno';
$strings['Repeat every'] = 'Ripeti ogni:';
$strings['Repeat on'] = 'Ripeti su:';
$strings['Repeat until date'] = 'Ripeti fino alla data:';
$strings['Choose Date'] = 'Scegli data';
$strings['Summary'] = 'Sommario';

$strings['View schedule'] = 'Vedi programma:';
$strings['My Reservations'] = 'Le Mie Prenotazioni';
$strings['My Past Reservations'] = 'Le Mie Prenotazioni Passate';
$strings['Other Reservations'] = 'Altre Prenotazioni';
$strings['Other Past Reservations'] = 'Altre Prenotazioni Passate';
$strings['Blacked Out Time'] = 'Blacked Out Time';
$strings['Set blackout times'] = 'Imposta blackout per %s su %s'; 
$strings['Reserve on'] = 'Riserva %s su %s';
$strings['Prev Week'] = '&laquo; Settimana Prec';
$strings['Jump 1 week back'] = 'Salta 1 settimana indietro';
$strings['Prev days'] = '&#8249; Prec %d giorni';
$strings['Previous days'] = '&#8249; Precedenti %d giorni';
$strings['This Week'] = 'Questa settimana';
$strings['Jump to this week'] = 'Salta a questa settimana';
$strings['Next days'] = 'Prossimi %d giorni &#8250;';
$strings['Next Week'] = 'Seguente settimana &raquo;';
$strings['Jump To Date'] = 'Salta alla data';
$strings['View Monthly Calendar'] = 'Vedi Calendario Mensile';
$strings['Open up a navigational calendar'] = 'Apri un calendario di navigazione';

$strings['View stats for schedule'] = 'Vedi statistiche per programma:';
$strings['At A Glance'] = 'At A Glance';
$strings['Total Users'] = 'Totale Utenti:';
$strings['Total Resources'] = 'Totale Risorse:';
$strings['Total Reservations'] = 'Totale Prenotazioni:';
$strings['Max Reservation'] = 'Prenotazioni Max:';
$strings['Min Reservation'] = 'Prenotazioni Min:';
$strings['Avg Reservation'] = 'Media Prenotazioni:';
$strings['Most Active Resource'] = 'Risorsa Pi� Attiva:';
$strings['Most Active User'] = 'Utente Pi� Attivo:';
$strings['System Stats'] = 'Statistiche Sistema';
$strings['phpScheduleIt version'] = 'phpScheduleIt versione:';
$strings['Database backend'] = 'Database backend:';
$strings['Database name'] = 'Nome Database:';
$strings['PHP version'] = 'PHP versione:';
$strings['Server OS'] = 'Server OS:';
$strings['Server name'] = 'Server nome:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt root directory:';
$strings['Using permissions'] = 'Using permissions:';
$strings['Using logging'] = 'Using logging:';
$strings['Log file'] = 'Log file:';
$strings['Admin email address'] = 'Indirizzo email Amministrazione:';
$strings['Tech email address'] = 'Indirizzo email Tecnici:';
$strings['CC email addresses'] = 'Indirizzo email CC:';
$strings['Reservation start time'] = 'Inizio Prenotazione:';
$strings['Reservation end time'] = 'Fine Prenotazione:';
$strings['Days shown at a time'] = 'Giorni mostrati alla volta:';
$strings['Reservations'] = 'Prenotazioni';
$strings['Return to top'] = 'Ritorna all inizio';
$strings['for'] = 'per';

$strings['Select Search Criteria'] = 'Seleziona un Criterio di Ricerca';
$strings['Schedules'] = 'Programmi:';
$strings['All Schedules'] = 'Tutti i Programmi';
$strings['Hold CTRL to select multiple'] = 'Mantieni premuto CTRL per selezioni multiple';
$strings['Users'] = 'Utenti:';
$strings['All Users'] = 'Tutti gli Utenti';
$strings['Resources'] = 'Risorse:';
$strings['All Resources'] = 'Tutte le Risorse';
$strings['Starting Date'] = 'Data inizio:';
$strings['Ending Date'] = 'Data Fine:';
$strings['Starting Time'] = 'Ora inizio:';
$strings['Ending Time'] = 'Ora Fine:';
$strings['Output Type'] = 'Tipo di Output:';
$strings['Manage'] = 'Amministrazione';
$strings['Total Time'] = 'Tempo Totale';
$strings['Total hours'] = 'Totale Ore:';
$strings['% of total resource time'] = '% del tempo totale della risorsa';
$strings['View these results as'] = 'Vedi risultati come:';
$strings['Edit this reservation'] = 'Edita questa prenotazione';
$strings['Search Results'] = 'Cerca Risultati';
$strings['Search Resource Usage'] = 'Cerca uso della Risorsa';
$strings['Search Results found'] = 'Risultati Ricerca: %d prenotazioni trovate';
$strings['Try a different search'] = 'Prova una ricerca differente';
$strings['Search Run On'] = 'Cerca su:';
$strings['Member ID'] = 'Member ID';
$strings['Previous User'] = '&laquo; Utente Precedente';
$strings['Next User'] = 'Prossimo Utente &raquo;';

$strings['No results'] = 'Nessun Risultato';
$strings['That record could not be found.'] = 'Questa record potrebbe non essere stato trovato.';
$strings['This blackout is not recurring.'] = 'Questo blackout non � ricorrente.';
$strings['This reservation is not recurring.'] = 'Questa prenotazione non � ricorrente.';
$strings['There are no records in the table.'] = 'Non ci sono records in questa  %s tabella.';
$strings['You do not have any reservations scheduled.'] = 'Non hai prenotazioni programmate.';
$strings['You do not have permission to use any resources.'] = 'Non hai i permessi per usare queste risorse.';
$strings['No resources in the database.'] = 'Nessuna risorsa nel database.';
$strings['There was an error executing your query'] = 'C � stato un errore eseguendo questa query:';

$strings['That cookie seems to be invalid'] = 'Questo cookie sembra non essere valido';
$strings['We could not find that email in our database.'] = 'Non riusciamo a trovare questa email nel nostro database.';
$strings['That password did not match the one in our database.'] = 'Questa password non corrisponde a quella nel nostro database.';
$strings['You can try'] = '<br />Puoi provare:<br />Registrando un indirizzo email.<br />Or:<br />Riprova a loggarti ancora.';
$strings['A new user has been added'] = 'Un nuovo utente � stato aggiunto';
$strings['You have successfully registered'] = 'Ti sei registrato correttamente!';
$strings['Continue'] = 'Continua...';
$strings['Your profile has been successfully updated!'] = 'Il tuo profilo � stato aggiornato correttamente!';
$strings['Please return to My Control Panel'] = 'Prego ritona al Mio Pannello di Controllo';
$strings['Valid email address is required.'] = '- Un indirizzo email valido � richiesto.';
$strings['First name is required.'] = '- Il nome � richiesto.';
$strings['Last name is required.'] = '- Il cognome � richiesto.';
$strings['Phone number is required.'] = '- Il numero di telefono � richiesto.';
$strings['That email is taken already.'] = '- Questo email � gi� stata registrata.<br />Prego provare ancora con un indirizzo email diverso.';
$strings['Min 6 character password is required.'] = '- La pasword deve essere minimo di 6 caratteri.';
$strings['Passwords do not match.'] = '- La password non corrisponde.';

$strings['Per page'] = 'Per pagina:';
$strings['Page'] = 'Pagina:';

$strings['Your reservation was successfully created'] = 'La tua prenotazione � stata creata con successo';
$strings['Your reservation was successfully modified'] = 'La tua prenotazione � stata modificata con successo';
$strings['Your reservation was successfully deleted'] = 'La tua prenotazione � stata cancellata con successo';
$strings['Your blackout was successfully created'] = 'Il tuo blackout � stato creato con successo';
$strings['Your blackout was successfully modified'] = 'Il tuo blackout � stato modificato con successo';
$strings['Your blackout was successfully deleted'] = 'Il tuo blackout � stato cancellato con successo';
$strings['for the follwing dates'] = 'per le seguenti date:';
$strings['Start time must be less than end time'] = 'L\'ora di inizio deve essere minore di quella finale.';
$strings['Current start time is'] = 'Ora di inizio corrente �:';
$strings['Current end time is'] = 'Ora finale corrente �:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'La durata della prenotazione va oltre a quella permessa per questa risorsa.';
$strings['Your reservation is'] = 'La tua prenotazione �:';
$strings['Minimum reservation length'] = 'Durata Minima della prenotazione:';
$strings['Maximum reservation length'] = 'Durata Massima della prenotazione:';
$strings['You do not have permission to use this resource.'] = 'Non hai i permessi per usare questa risorsa.';
$strings['reserved or unavailable'] = '%s da %s a %s � riservata o indisponibile.';
$strings['Reservation created for'] = 'Prenotazione creataper  %s';
$strings['Reservation modified for'] = 'Prenotazione modificata per %s';
$strings['Reservation deleted for'] = 'Prenotazione cancellata per %s';
$strings['created'] = 'creata';
$strings['modified'] = 'modificata';
$strings['deleted'] = 'cancellata';
$strings['Reservation #'] = 'Prenotazione #';
$strings['Contact'] = 'Contatto';
$strings['Reservation created'] = 'Prenotazione creata';
$strings['Reservation modified'] = 'Prenotazione modificata';
$strings['Reservation deleted'] = 'Prenotazione cancellata';

$strings['Reservations by month'] = 'Prenotazioni per mese';
$strings['Reservations by day of the week'] = 'Prenotazioni per giorno delle settimana';
$strings['Reservations per month'] = 'Prenotazioni per mese';
$strings['Reservations per user'] = 'Prenotazioni per utente';
$strings['Reservations per resource'] = 'Prenotazioni per risorsa';
$strings['Reservations per start time'] = 'Prenotazioni per ora d\'inizio';
$strings['Reservations per end time'] = 'Prenotazioni per ora di fine';
$strings['[All Reservations]'] = '[Tutte le prenotazioni]';

$strings['Permissions Updated'] = 'Permessi aggiornati';
$strings['Your permissions have been updated'] = 'I tuoi %s permessi sono stati aggiornati';
$strings['You now do not have permission to use any resources.'] = 'Ora non hai i permessi per usare qualsiasi risorsa.';
$strings['You now have permission to use the following resources'] = 'Ora hai i permessi per usare le seguenti risorse:';
$strings['Please contact with any questions.'] = 'Prego contattare %s per qualsiasi domanda.';
$strings['Password Reset'] = 'Azzera password';

$strings['This will change your password to a new, randomly generated one.'] = 'Questo cambier� la tua password in una nuova, generata casualmente.';
$strings['your new password will be set'] = 'Dopo aver inserito il tuo indirizzo email e cliccato su "Cambia Password", la tua nuova password sar� impostata dal sistema e ti sar� spedita per email.';
$strings['Change Password'] = 'Cambia Password';
$strings['Sorry, we could not find that user in the database.'] = 'Spiacente, non troviamo questo utente nel database.';
$strings['Your New Password'] = 'La tua Nuova %s Password';
$strings['Your new passsword has been emailed to you.'] = 'Successo!<br />
    			La tua nuova password ti verr� spedita per email.<br />
    			Prego controlla la tua casella di posta per la tua nuova password, poi <a href="index.php">Loggati Qui</a>
    			con questa nuova e si pu� cambiare cliccando il link &quot;Cambia il Mio Profilo/Password&quot;
    			in Mio Pannello di Controllo.';

$strings['You are not logged in!'] = 'Non sei loggato';

$strings['Setup'] = 'Setup';
$strings['Please log into your database'] = 'Prego loggati nel tuo database';
$strings['Enter database root username'] = 'Enter database root username:';
$strings['Enter database root password'] = 'Enter database root password:';
$strings['Login to database'] = 'Login to database';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'L\'utente <b>non � </b> richiesto. Qualsiasi utente del database con i permessi di creare tabelle � accettabile.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Questo imposter� tutti i database e le tabelle necessari per phpScheduleIt.';
$strings['It also populates any required tables.'] = 'Inoltre popoler� ogni tabella richiesta.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Attenzione: QUESTO CANCELLERA\' TUTTI I DATI NEI DATABASE phpScheduleIt PRECEDENTI!';
$strings['Not a valid database type in the config.php file.'] = 'Non c\'� un tipo di database valido nel file config.php.';
$strings['Database user password is not set in the config.php file.'] = 'La password dell\'utente del database non � impostata nel file config.php.';
$strings['Database name not set in the config.php file.'] = 'Il nome del Database non � configurato nel fileconfig.php.';
$strings['Successfully connected as'] = 'Connesso con successo come';
$strings['Create tables'] = 'Creo tabelle &gt;';
$strings['There were errors during the install.'] = 'Ci sono stati errori durante l\'installazione. E\' possibile che  phpScheduleIt possa funziona lo stesso se gli errori sono minimi.<br/><br/>'
	. 'Prego postare qualsiasi domanda nei forum su <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'Hai completato con successo l\'installazione di phpScheduleIt e ora � pronto per essere usato.';
$strings['Thank you for using phpScheduleIt'] = 'Prego assicurarsi di RIMUOVERE COMPLETAMENTE LA CARTELLA \'install\' .'
	. ' Questo � importante perch� contiene le password del database e altre informazioni sensibili.'
	. ' La non cancellazione lascia le porte aperte a chiunque per entrare nel tuo database!'
	. '<br /><br />'
	. 'Grazie per usare phpScheduleIt!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'Questo aggiornamento porter� la tua versione di phpScheduleIt dalla 0.9.3 alla 1.0.0.';
$strings['There is no way to undo this action'] = 'Non c\'� possibilit� di tornare indietro!';
$strings['Click to proceed'] = 'Premi per procedere';
$strings['This version has already been upgraded to 1.0.0.'] = 'Questa versione � gi� stata aggiornata alla 1.0.0.';
$strings['Please delete this file.'] = 'Prego cancella questo file.';
$strings['Successful update'] = 'Aggiornamento completato con successo';
$strings['Patch completed successfully'] = 'Patch applicata con successo';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'Questo popoler� i campi richiesti per phpScheduleIt 1.0.0 e applicher� le patch per i bug della versione 0.9.9.'
		. '<br />Questo � richiesto solo se viene eseguito un aggiornamento SQL manuale o se stai aggiornando dalla versione 0.9.9';

// @since 1.0.0 RC1
$strings['If no value is specified, the default password set in the config file will be used.'] = 'Se non � specificato un valore, verr� usata la password di default settata nel file di configurazione.';
$strings['Notify user that password has been changed?'] = 'Avvisare l\'utente che la password � stata cambiata?';
/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s\n\r\n"
				. "You have successfully registered with the following information:\r\n"
				. "Nome: %s %s\r\n"
				. "Telefono: %s\r\n"
				. "Istituzione: %s\r\n"
				. "Posizione: %s\r\n\r\n"
				. "Prego connettiti a phpsheduleit da questa posizionethis location:\r\n"
				. "%s\r\n\r\n"
				. "Troverai i link per fare le prenotazioni e per editare il tuo profilo in Mio Pannello di Controllo.\r\n\r\n"
				. "Ogni domanda sulle prenotazioni e le risorse va posta a  %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Amministratore,\r\n\r\n"
					. "Un nuovo utente � stato registrato con le seguenti informazioni:\r\n"
					. "Email: %s\r\n"
					. "Nome: %s %s\r\n"
					. "Telefono: %s\r\n"
					. "Istituzione: %s\r\n"
					. "Posizione: %s\r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "You have successfully %s reservation #%s.\r\n\r\n<br/><br/>"
			. "Usa questo numero di prenotozione quando contatterai l'amministatore per qualsiasi domanda.\r\n\r\n<br/><br/>"
			. "Una pronotazione %s tra %s e %s per %s"
			. " situata a %s � stata fatta %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "Questa prenotazione � stata ripetuta nella seguenti date:\r\n<br/>";
$email['reservation_activity_3'] = "Tutte le prenotazioni di questo gruppo sono inoltre %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "Il seguente sommatio � stato redatto per questa prenotazione:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Se c'� un errore, prego contattare l'amministratore al: %s"
			. " o chimando il  %s.\r\n\r\n<br/><br/>"
			. "Puoi vedere o modificare la tua prenotazione in qualsiasi momento "
			. " logging in %s at:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "Prego porre tutte le domande tecniche a <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "La tua  %s password � stata resettata dall'amministratore.\r\n\r\n"
			. "La tua password temporanea �:\r\n\r\n %s\r\n\r\n"
			. "Prego usare questa password temporanea (copia e incolla per essere sicuro di non sbagliare) per loggarti in %s a %s"
			. " e cambiala immediatamente usando il link 'Cambia il Mio Profilo/Password' nella sezione I Miei Link Veloci.\r\n\r\n"
			. "Prego contattare %s per ogni domanda.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "La tua nuova password per l account %s �:\r\n\r\n"
            . "%s\r\n\r\n"
            . "Prego loggati in %s "
            . "con questa password "
            . "(copia e incolla per essere sicuro di non sbagliare) "
            . "e cambiala cliccando il link "
            . "Cambia il Mio Profilo/Password "
            . "nel Mio Pannello di Controllo.\r\n\r\n"
            . "Ogni domanda pu� essere rivolta a %s.";
?>