<?php
/**
* French (fr) translation file.
* This also serves as the base translation file from which to derive
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator J. Pe. <jpe@chez.com>
* @version 08-21-04
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
$days_full = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
// The three letter abbreviation
$days_abbr = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam');
// The two letter abbreviation
$days_two  = array('Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa');
// The one letter abbreviation
$days_letter = array('D', 'L', 'M', 'M', 'J', 'V', 'S');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
// The three letter month name
$months_abbr = array('Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec');

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
$strings['hours'] = 'heures';
$strings['minutes'] = 'minutes';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'jj';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'aaaa';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Administrateur';
$strings['Welcome Back'] = 'Bienvenue, %s';
$strings['Log Out'] = 'Quitter';
$strings['My Control Panel'] = 'Mon panneau de contrôle';
$strings['Help'] = 'Aide';
$strings['Manage Schedules'] = 'Organisation de la planification';
$strings['Manage Users'] ='Gestion utilisateurs';
$strings['Manage Resources'] ='Gestion des Ressources';
$strings['Manage User Training'] ='Gestion de l\'entrainement des utilisateurs';
$strings['Manage Reservations'] ='Gestion des reservations';
$strings['Email Users'] ='Message electronique aux utilisateurs';
$strings['Export Database Data'] = 'Exportation de données';
$strings['Reset Password'] = 'Retablissement de mot de passe';
$strings['System Administration'] = 'Administration du systeme';
$strings['Successful update'] = 'Mise a jour réussie';
$strings['Update failed!'] = 'Modification échouée !';
$strings['Manage Blackout Times'] = 'Gestion du temps caché';
$strings['Forgot Password'] = 'Oubli du mot de passe';
$strings['Manage My Email Contacts'] = 'Gestion des mes correspondants';
$strings['Choose Date'] = 'Choisir une date';
$strings['Modify My Profile'] = 'Modification des mes caractéristiques';
$strings['Register'] = 'Enregistrer';
$strings['Processing Blackout'] = 'Execution du masquage';
$strings['Processing Reservation'] = 'Mise en oeuvre des réservations';
$strings['Online Scheduler [Read-only Mode]'] = 'Planificateur en ligne [Mode lecture-seule]';
$strings['Online Scheduler'] = 'Planificateur en ligne';
$strings['phpScheduleIt Statistics'] = 'statistiques de phpScheduleIt';
$strings['User Info'] = 'Info Utilisateur:';

$strings['Could not determine tool'] = 'Outil non identifiable. S.V.P Essayez encore à partir du panneau de contrôle.';
$strings['This is only accessable to the administrator'] = 'Ceci n\'est seulement accessible qu\'à l\'administrateur';
$strings['Back to My Control Panel'] = 'Retour au panneau de contrôle';
$strings['That schedule is not available.'] = 'Cette planification n\'est pas disponible.';
$strings['You did not select any schedules to delete.'] = 'Vous n\'avez sélectionné aucune planification à effacer.';
$strings['You did not select any members to delete.'] = 'Vous n\'avez séléctionné aucun membre à supprimer.';
$strings['You did not select any resources to delete.'] = 'Vous n\'avez séléctionné aucune ressource à supprimer.';
$strings['Schedule title is required.'] = 'La planification requiert un titre.';
$strings['Invalid start/end times'] = 'Heure de début ou de fin invalide';
$strings['View days is required'] = 'Jours de vision (?) requis';
$strings['Day offset is required'] = 'Plage horaire du jour requise';
$strings['Admin email is required'] = 'L\'adresse électronique de l\'administrateur est requise';
$strings['Resource name is required.'] = 'Le nom de la ressource est requis.';
$strings['Valid schedule must be selected'] = 'Une planification valide est requise';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'Le temps minimum de reservation doit être inférieur ou égal au temps de reservation maxium.';
$strings['Your request was processed successfully.'] = 'Votre demande a été correctement satisfaite.';
$strings['Go back to system administration'] = 'Retour à l\'administration du système.';
$strings['Or wait to be automatically redirected there.'] = 'ou attendre d\'y être automatiquement redirigé.';
$strings['There were problems processing your request.'] = 'Des difficultés ont été rencontrées lors de l\'exécution de votre demande.';
$strings['Please go back and correct any errors.'] = 'Merci de bien vouloir retourner corriger toutes les erreurs.';
$strings['Login to view details and place reservations'] = 'Connexion pour voir les détails et effectuer des reservations';
$strings['Memberid is not available.'] = 'L\'identifiant de membre: %s n\'est pas disponible.';

$strings['Schedule Title'] = 'Titre de la planification';
$strings['Start Time'] = 'Heure de début';
$strings['End Time'] = 'Heure de fin';
$strings['Time Span'] = 'Temps passé';
$strings['Weekday Start'] = 'Jour de semaine de début';
$strings['Admin Email'] = 'Adresse messagerie de l\'administrateur';

$strings['Default'] = 'Defaut';
$strings['Reset'] = 'Ré-initialisation';
$strings['Edit'] = 'Mise à jour';
$strings['Delete'] = 'Effacement';
$strings['Cancel'] = 'Annulation';
$strings['View'] = 'Visualisation';
$strings['Modify'] = 'Modification';
$strings['Save'] = 'Sauvegarde';
$strings['Back'] = 'Retour';
$strings['Next'] = 'Suivant';
$strings['Close Window'] = 'Fermeture de fenêtre';
$strings['Search'] = 'Recherche';
$strings['Clear'] = 'Effacement';

$strings['Days to Show'] = 'Jours à visualiser';
$strings['Reservation Offset'] = 'Période de réservation';
$strings['Hidden'] = 'Caché';
$strings['Show Summary'] = 'Visualisation abrégée';
$strings['Add Schedule'] = 'Ajout d\'une planification';
$strings['Edit Schedule'] = 'Modification d\'une planification';
$strings['No'] = 'Non';
$strings['Yes'] = 'Oui';
$strings['Name'] = 'Nom';
$strings['First Name'] = 'Prénom';
$strings['Last Name'] = 'Patronyme';
$strings['Resource Name'] = 'Nom de la Ressource';
$strings['Email'] = 'Adresse de messagerie';
$strings['Institution'] = 'Institution';
$strings['Phone'] = 'Téléphone';
$strings['Password'] = 'Password';
$strings['Permissions'] = 'Permissions';
$strings['View information about'] = 'Visualisation des information de %s %s';
$strings['Send email to'] = 'Envoi d\'un message électronique à  %s %s';
$strings['Reset password for'] = 'Ré-initialisation du mot de passe de %s %s';
$strings['Edit permissions for'] = 'Modification des privilèges pour %s %s';
$strings['Position'] = 'Position';
$strings['Password (6 char min)'] = 'Password (6 char min)';
$strings['Re-Enter Password'] = 'Re-Entrer Password';

$strings['Sort by descending last name'] = 'Tri décroissant par patronyme';
$strings['Sort by descending email address'] = 'Tri décroissant par adresse électronique';
$strings['Sort by descending institution'] = 'Tri décroissant par institution';
$strings['Sort by ascending last name'] = 'Tri croissant par patronyme';
$strings['Sort by ascending email address'] = 'Tri croissant par adresse éléctronique';
$strings['Sort by ascending institution'] = 'Tri croissant par institution';
$strings['Sort by descending resource name'] = 'Tri décroissant par nom de ressource';
$strings['Sort by descending location'] = 'Tri décroissant par emplacement';
$strings['Sort by descending schedule title'] = 'Tri décroissant par titre de planification';
$strings['Sort by ascending resource name'] = 'Tri croissant par nom de ressource';
$strings['Sort by ascending location'] = 'Tri croissant par emplacement';
$strings['Sort by ascending schedule title'] = 'Tri croissant pat titre de planification';
$strings['Sort by descending date'] = 'Tri décroissant par date';
$strings['Sort by descending user name'] = 'Tri décroissant par nom d\'utilisateur';
// duplicate $strings['Sort by descending resource name'] = 'Tri décroissant par nom de ressource';
$strings['Sort by descending start time'] = 'Tri décroissant par heure de début';
$strings['Sort by descending end time'] = 'Tri décroissant end time';
$strings['Sort by ascending date'] = 'Tri croissant par date';
$strings['Sort by ascending user name'] = 'Tri croissant par nom d\'utilisateur';
$strings['Sort by ascending resource name'] = 'Tri croissant par nom de ressource';
$strings['Sort by ascending start time'] = 'Tri croissant par heure de début';
$strings['Sort by ascending end time'] = 'Tri croissant par heure de fin';
$strings['Sort by descending created time'] = 'Tri décroissant par date de création';
$strings['Sort by ascending created time'] = 'Tri croissant par date de création';
$strings['Sort by descending last modified time'] = 'Tri décroissant par heure de dernière modification';
$strings['Sort by ascending last modified time'] = 'Tri croissant par heure de dernière modification';

$strings['Search Users'] = 'Recherche utilisateur';
$strings['Location'] = 'Emplacement';
$strings['Schedule'] = 'Planification';
$strings['Phone'] = 'Téléphone';
$strings['Notes'] = 'Notes';
$strings['Status'] = 'Etat';
$strings['All Schedules'] = 'Toute planification';
$strings['All Resources'] = 'Toute ressource';
$strings['All Users'] = 'Tout utilisateur';

$strings['Edit data for'] = 'Modification de données pour %s';
$strings['Active'] = 'Actif';
$strings['Inactive'] = 'Inactif';
$strings['Toggle this resource active/inactive'] = 'Bascule cette ressource active/inactive';
$strings['Minimum Reservation Time'] = 'Période minimum de reservation';
$strings['Maximum Reservation Time'] = 'Période maximum de reservation';
$strings['Auto-assign permission'] = 'Accord de permission automatique';
$strings['Add Resource'] = 'Ajout de Ressource';
$strings['Edit Resource'] = 'Modification de Ressource';
$strings['Allowed'] = 'Permis';
$strings['Notify user'] = 'Notification utilisateur';
$strings['User Reservations'] = 'Reservations utilisateur';
$strings['Date'] = 'Date';
$strings['User'] = 'Utilisateur';
$strings['Email Users'] = 'Adresse électronique utilisateur';
$strings['Subject'] = 'Sujet';
$strings['Message'] = 'Message';
$strings['Please select users'] = 'S.V.P. sélectionner un utilisateur';
$strings['Send Email'] = 'Envoi du message électronique';
$strings['problem sending email'] = 'Désolé, des difficultés on étés rencontrées lors de l\'envoi du message. S.V.P. Essayez de nouveau plus tard.';
$strings['The email sent successfully.'] = 'Le message électronique a été envoyé avec succés.';
$strings['do not refresh page'] = 'S.V.P <u>ne pas</u> ré-actualiser cette page. Le faire enverait deux fois le message.';
$strings['Return to email management'] = 'Retour à la gestion des messages électroniques';
$strings['Please select which tables and fields to export'] = 'Merci de choisir les tables et les champs à exporter :';
$strings['all fields'] = '- tous les champs -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Plain text';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Export Data';
$strings['Reset Password for'] = 'Ré-initialisation du mot de passe de  %s';
$strings['Please edit your profile'] = 'Merci de mettre vos identifiants à jour';
$strings['Please register'] = 'Merci de vous enregister';
$strings['Email address (this will be your login)'] = 'Adresse électronique (Ce sera votre nom de connexion)';
$strings['Keep me logged in'] = 'Maintenir ma connexion <br/>(utilisation de cookies requise )';
$strings['Edit Profile'] = 'Modification du profil';
$strings['Register'] = 'Enregistrer';
$strings['Please Log In'] = 'Merci de vous identifier';
$strings['Email address'] = 'Adresse électronique';
$strings['Password'] = 'Mot de passe';
$strings['First time user'] = 'Si vous venez pour la première fois?';
$strings['Click here to register'] = 'Clicker ici pour vous enregistrer';
$strings['Register for phpScheduleIt'] = 'S\'enregistrer pour phpScheduleIt';
$strings['Log In'] = 'Se connecter';
$strings['View Schedule'] = 'Visualisation de planification';
$strings['View a read-only version of the schedule'] = 'Visualisation d\'une version en lecture seule d\'une planification';
$strings['I Forgot My Password'] = 'J\'ai oublié mon mot de passe';
$strings['Retreive lost password'] = 'Récupérer un mot de passe oublié';
$strings['Get online help'] = 'Obtenir de l\'aide en ligne';
$strings['Language'] = 'Langage';
$strings['(Default)'] = '(Defaut)';

$strings['My Announcements'] = 'Mes annonces';
$strings['My Reservations'] = 'Mes reservations';
$strings['My Permissions'] = 'Mes privilèges';
$strings['My Quick Links'] = 'Mes liens rapides';
$strings['Announcements as of'] = 'Annonces de  %s';
$strings['There are no announcements.'] = 'Il n\'y pas d\'annonces.';
$strings['Resource'] = 'Ressource';
$strings['Created'] = 'Créé';
$strings['Last Modified'] = 'Dernière modification';
$strings['View this reservation'] = 'Visualisation de cette reservation';
$strings['Modify this reservation'] = 'Modification de cette reservation';
$strings['Delete this reservation'] = 'Effacement de cette reservation';
$strings['Go to the Online Scheduler'] = 'Allez sur le planificateur en ligne';
$strings['Change My Profile Information/Password'] = 'Modifier mes caractéristiques et/ou mon Password';
$strings['Manage My Email Preferences'] = 'Gestion de mes adresses électroniques préférées';
$strings['Manage Blackout Times'] = 'Gestion du temps masqué';
$strings['Mass Email Users'] = 'Publipostage';
$strings['Search Scheduled Resource Usage'] = 'Recherche de l\'utilisation des ressources planifiées';
$strings['Export Database Content'] = 'Exportation du contenu de la base de données';
$strings['View System Stats'] = 'Visuallisation des statistiques systemes';
$strings['Email Administrator'] = 'Envoi d\'un message électronique à l\'administrateur';

$strings['Email me when'] = 'M\'envoyer un message électronique chaque fois que :';
$strings['I place a reservation'] = 'j\'effectue une reservation';
$strings['My reservation is modified'] = 'ma reservation est modifiée';
$strings['My reservation is deleted'] = 'ma reservation est effacée';
$strings['I prefer'] = 'Je préfère:';
$strings['Your email preferences were successfully saved'] = 'Vos messages électronique ont étés sauvegardés!';
$strings['Return to My Control Panel'] = 'Retour à mon Panneau de contrôle';

$strings['Please select the starting and ending times'] = 'Merci de choisir les heures de début et de fin :';
$strings['Please change the starting and ending times'] = 'Merci de modifier les heures de début et de fin';
$strings['Reserved time'] = 'Heure de réservation :';
$strings['Minimum Reservation Length'] = 'Durée minimum de réservation :';
$strings['Maximum Reservation Length'] = 'Durée maximum de réservation :';
$strings['Reserved for'] = 'Réservé pour :';
$strings['Will be reserved for'] = 'sera réservé pour:';
$strings['N/A'] = 'N/A';
$strings['Update all recurring records in group'] = 'Modifie tous les enregistrements cycliques dans le groupe?';
$strings['Delete?'] = 'Efface ?';
$strings['Never'] = '-- Jamais --';
$strings['Days'] = 'Jours';
$strings['Weeks'] = 'Semaines';
$strings['Months (date)'] = 'Mois (date)';
$strings['Months (day)'] = 'Mois (jour)';
$strings['First Days'] = 'Premiers jour';
$strings['Second Days'] = 'Second jour';
$strings['Third Days'] = 'Troisième jour';
$strings['Fourth Days'] = 'Quatrième jour';
$strings['Last Days'] = 'Dernier jour';
$strings['Repeat every'] = 'Répète tous les :';
$strings['Repeat on'] = 'Répete chaque:';
$strings['Repeat until date'] = 'Répète juqu\'à :';
$strings['Choose Date'] = 'Choisir une date';
$strings['Summary'] = 'Résumé';

$strings['View schedule'] = 'Visualisation de la planification:';
$strings['My Reservations'] = 'Mes réservations';
$strings['My Past Reservations'] = 'Mes anciennes réservations';
$strings['Other Reservations'] = 'Les autres reservations';
$strings['Other Past Reservations'] = 'Les autes anciennes réservations';
$strings['Blacked Out Time'] = 'Temps masqué';
$strings['Set blackout times'] = 'Etablissement du temps %s sur %s'; 
$strings['Reserve on'] = 'Reserve %s sur %s';
$strings['Prev Week'] = '&laquo; Sem. Préc.';
$strings['Next Week'] = 'Sem. Suiv. &raquo;';
$strings['Jump 1 week back'] = 'Sauter 1 Sem. en Arr.';
$strings['Prev days'] = '&#8249; %d jours préc.';
$strings['Previous days'] = '&#8249; %d jours précédents';
$strings['This Week'] = 'Cette semaine';
$strings['Jump to this week'] = 'Sauter à cette semaine';
$strings['Next days'] = 'prochains %d jours &#8250;';
$strings['Jump To Date'] = 'Sauter à cette date';
$strings['View Monthly Calendar'] = 'Visualisation du calendrier mensuel';
$strings['Open up a navigational calendar'] = 'Ouverture du calendrier de survol';

$strings['View stats for schedule'] = 'Visualisation des statistique pour la planification:';
$strings['At A Glance'] = 'D\'un coup d\'oeil';
$strings['Total Users'] = 'Total utilisateur:';
$strings['Total Resources'] = 'Total Ressources:';
$strings['Total Reservations'] = 'Total Reservations:';
$strings['Max Reservation'] = 'Max Reservation:';
$strings['Min Reservation'] = 'Min Reservation:';
$strings['Avg Reservation'] = 'Moy Reservation:';
$strings['Most Active Resource'] = 'Ressource la plus active :';
$strings['Most Active User'] = 'L\'utilisateur le plus actif :';
$strings['System Stats'] = 'Statistiques systèmes';
$strings['phpScheduleIt version'] = 'phpScheduleIt version:';
$strings['Database backend'] = 'Base de données principale :';
$strings['Database name'] = 'Nom de base de données :';
$strings['PHP version'] = 'PHP version:';
$strings['Server OS'] = 'Server OS:';
$strings['Server name'] = 'Server name:';
$strings['phpScheduleIt root directory'] = 'phpScheduleIt root directory:';
$strings['Using permissions'] = 'Using permissions:';
$strings['Using logging'] = 'Using logging:';
$strings['Log file'] = 'Fichier historique :';
$strings['Admin email address'] = 'Adresse de messagerie administrateur:';
$strings['Tech email address'] = 'Adresse de messagerie technique:';
$strings['CC email addresses'] = 'CC email addresses:';
$strings['Reservation start time'] = 'Heure de début de reservation :';
$strings['Reservation end time'] = 'Heure de fin de reservation :';
$strings['Days shown at a time'] = 'Nombre de jours visualisés d\'un coup:';
$strings['Reservations'] = 'Réservations';
$strings['Return to top'] = 'Retour en haut';
$strings['for'] = 'pour';

$strings['Select Search Criteria'] = 'Choix des critères de sélection';
$strings['Schedules'] = 'Planifications:';
$strings['All Schedules'] = 'Toute planification';
$strings['Hold CTRL to select multiple'] = 'Maintenir la touche CTRL pour un choix multiple';
$strings['Users'] = 'Utilisateur :';
$strings['All Users'] = 'Tout utilisateur';
$strings['Resources'] = 'Ressources :';
$strings['All Resources'] = 'Toute Ressource';
$strings['Starting Date'] = 'Date de départ :';
$strings['Ending Date'] = 'Date de fin :';
$strings['Starting Time'] = 'Heure de début :';
$strings['Ending Time'] = 'Heure de fin :';
$strings['Output Type'] = 'Type de sortie :';
$strings['Manage'] = 'Gére';
$strings['Total Time'] = 'Temps total :';
$strings['Total hours'] = 'Heures totales :';
$strings['% of total resource time'] = '% du temps de ressource total';
$strings['View these results as'] = 'Visualisation de ce résultat comme :';
$strings['Edit this reservation'] = 'Modifie cette réservation';
$strings['Search Results'] = 'Cherche les résultats';
$strings['Search Resource Usage'] = 'Cherche l\'utilisation des ressources';
$strings['Search Results found'] = 'Recherche de résultats : %d reservations trouvées';
$strings['Try a different search'] = 'Essayer une recherche différente';
$strings['Search Run On'] = 'La recherche s\'effectue sur :';
$strings['Member ID'] = 'Member ID';
$strings['Previous User'] = '&laquo; Utilisateur précédent';
$strings['Next User'] = 'Utilisateur suivant &raquo;';

$strings['No results'] = 'Pas de résultat';
$strings['That record could not be found.'] = 'Cet enregistrement ne peut être trouvé.';
$strings['This blackout is not recurring.'] = 'Cet arrêt total ne se reproduit pas.';
$strings['This reservation is not recurring.'] = 'Cette réservation n\'est pas cyclique.';
$strings['There are no records in the table.'] = 'Il n\'y a aucun enregistrements dans cette table %s.';
$strings['You do not have any reservations scheduled.'] = 'Vous n\'avez aucune reservation planifiée.';
$strings['You do not have permission to use any resources.'] = 'Vous n\'avez la permission d\'utilser aucune ressource.';
$strings['No resources in the database.'] = 'Il n\'y a aucune ressource définie dans la base de données.';
$strings['There was an error executing your query'] = 'Une erreur s\'est produite lors de l\'exécution du query:';

$strings['That cookie seems to be invalid'] = 'Ce cookie semble invalide';
$strings['We could not find that email in our database.'] = 'Nous ne parvenons pas à trouver cet email dans la base de données.';
$strings['That password did not match the one in our database.'] = 'Le mot de passe n\'est pas identique à celui contenu dans la base de donnée.';
$strings['You can try'] = '<br />Vous pouvez essayer:<br />d\'enregister une adresse email.<br />Ou :<br />Essayer de vous connecter de nouveau.';
$strings['A new user has been added'] = 'Un nouvel utilisateur a été ajouté';
$strings['You have successfully registered'] = 'Vous avez été enregistré correctement!';
$strings['Continue'] = 'Continuer...';
$strings['Your profile has been successfully updated!'] = 'Votre profil a été correctement modifié!';
$strings['Please return to My Control Panel'] = 'Merci de retourner au panneau de contrôle';
$strings['Valid email address is required.'] = '- Une adresse électronique valide est requise.';
$strings['First name is required.'] = '- Le prénom est requis.';
$strings['Last name is required.'] = '- Le patronyme est requis.';
$strings['Phone number is required.'] = '- Le numéro de téléphone est requis.';
$strings['That email is taken already.'] = '- Cet adresse électronique est déjà utilisée.<br />Merci d\'essayer de nouveau avec une autre adresse électronique.';
$strings['Min 6 character password is required.'] = '- Le mot de passe requiert un minimum de 6 caractéres.';
$strings['Passwords do not match.'] = '- Les mots de passe ne correspondent pas.';

$strings['Per page'] = 'Par page:';
$strings['Page'] = 'Page:';

$strings['Your reservation was successfully created'] = 'Votre réservation a été correctement enregistrée';
$strings['Your reservation was successfully modified'] = 'Votre réservation a été correctement modifiée';
$strings['Your reservation was successfully deleted'] = 'Votre réservation a été correctement effacée';
$strings['Your blackout was successfully created'] = 'Votre temps masqué a été correctement créé';
$strings['Your blackout was successfully modified'] = 'Votre temps masqué a été correctement modifié';
$strings['Your blackout was successfully deleted'] = 'Votre temps masqué a été correctement effacé';
$strings['for the follwing dates'] = 'pour les dates suivantes:';
$strings['Start time must be less than end time'] = 'L\'heure de début doit être inférieure à l\'heure de fin.';
$strings['Current start time is'] = 'L\'actuelle heure de début est :';
$strings['Current end time is'] = 'L\'actuelle heure de fin est :';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'La durée de reservation n\'est pas compatible avec la durée de réservation autorisée.';
$strings['Your reservation is'] = 'Votre réservation est :';
$strings['Minimum reservation length'] = 'Durée minimum de réservation :';
$strings['Maximum reservation length'] = 'Durée maximum de réservation :';
$strings['You do not have permission to use this resource.'] = 'Vous n\'avez pas les droits d\'utilisation de cette ressource.';
$strings['reserved or unavailable'] = '%s de %s à %s est reservé ou indisponible.';
$strings['Reservation created for'] = 'Reservation créée pour %s';
$strings['Reservation modified for'] = 'Reservation modifiée pour %s';
$strings['Reservation deleted for'] = 'Reservation effacée %s';
$strings['created'] = 'créé';
$strings['modified'] = 'modifié';
$strings['deleted'] = 'détruit';
$strings['Reservation #'] = 'Reservation #';
$strings['Contact'] = 'Contact';
$strings['Reservation created'] = 'Reservation créée';
$strings['Reservation modified'] = 'Reservation modifiée';
$strings['Reservation deleted'] = 'Reservation effacée';

$strings['Reservations by month'] = 'Réservations par mois';
$strings['Reservations by day of the week'] = 'Réservations par jour de la semaine';
$strings['Reservations per month'] = 'Réservations par mois';
$strings['Reservations per user'] = 'Réservations par utilisateur';
$strings['Reservations per resource'] = 'Réservations par ressource';
$strings['Reservations per start time'] = 'Réservations par date de début';
$strings['Reservations per end time'] = 'Réservations par date de fin';
$strings['[All Reservations]'] = 'Toute réservation';

$strings['Permissions Updated'] = 'Privilèges modifiés';
$strings['Your permissions have been updated'] = 'Vos %s privilèges ont été modifiés';
$strings['You now do not have permission to use any resources.'] = 'Vous n\'avez les droits d\'utilisation d\'aucune ressource.';
$strings['You now have permission to use the following resources'] = 'Vous avez désormais les droits d\'utilisation des ressources suivantes :';
$strings['Please contact with any questions.'] = 'Merci de contacter %s pour toute question.';
$strings['Password Reset'] = 'Ré-initialisation de mot de passes';

$strings['This will change your password to a new, randomly generated one.'] = 'Cela remplacera votre mot de passe par un mot de passe déterminé de façon aléatoire.';
$strings['your new password will be set'] = 'Après avoir indiqué votre adresse électronique et clicker sur "Modification du mot de passe", votre nouveau mot de passe sera effectif et vous sera envoyé.';
$strings['Change Password'] = 'Modification du mot de passe';
$strings['Sorry, we could not find that user in the database.'] = 'Désolé nous ne pouvons trouver cet utilisateur dans notre base de données.';
$strings['Your New Password'] = 'Votre nouveau mot de passe %s ';
$strings['Your new passsword has been emailed to you.'] = 'Succès!<br />
    			Votre nouveau mot de passe vous a été envoyé par message électronique.<br />
    			Merci de contrôler le contenu de votre boite aux lettres, puis <a href="index.php">Connectez vous</a>
    			avec votre nouveau mot de passe et rapidement modifiez le en clickant &quot;Modifie les information de mon profil/Password&quot;
    			dans mon Panneau de contrôle.';

$strings['You are not logged in!'] = 'Vous n\'êtes pas connecté!';

$strings['Setup'] = 'Installation';
$strings['Please log into your database'] = 'Merci de vous connecter à votre base de données';
$strings['Enter database root username'] = 'Indiquez le nom racine de la base de données:';
$strings['Enter database root password'] = 'Indiquez le mot de passe racine de la base de données:';
$strings['Login to database'] = 'Connexion à la base de données';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = 'L\'utilisateur racine  <b>n\'est pas </b> requis. Tout nom d\'utilisateur qui a les droits de création de tables est suffisant.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Cela va installer toutes les bases et tables nécessaire à phpScheduleIt.';
$strings['It also populates any required tables.'] = 'Cela garnit également toutes les tables nécessaires.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Attention: CELA VA EFFACER TOUTES LES DONNEES DANS LES BASES DE DONNEES PRECEDENTES DE phpScheduleIt !';
$strings['Not a valid database type in the config.php file.'] = 'Un type de base de donnée invalide figure dans le script config.php.';
$strings['Database user password is not set in the config.php file.'] = 'Le mot de passe d\'accès à la base de donnée n\'est pas indiqué dans config.php.';
$strings['Database name not set in the config.php file.'] = 'Le nom de la base de donnée n\'est pas indiqué dans config.php.';
$strings['Successfully connected as'] = 'Connecté avec succès en tant que ';
$strings['Create tables'] = 'Créé les tables &gt;';
$strings['There were errors during the install.'] = 'Des erreurs se sont produites durant l\'installation. Il est possible que phpScheduleIt fonctionne correctement si les erreurs sont mineures.<br/><br/>'
	. 'Merci de poser toute question sur le forum <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'Vous avez terminé d\'installer phpScheduleIt avec succès et êtes prêt à l\'utiliser.';
$strings['Thank you for using phpScheduleIt'] = 'Merci de vous assurer de détruire le répertoire \'install\'.'
	. ' C\'est essentiel car il contient des informations confidentielles d\'accès.'
	. ' Ne pas agir ainsi vous expose à des intrusions malveilantes de nature à détruire votre site !'
	. '<br /><br />'
	. 'Merci d\'utiliser phpScheduleIt!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'Ceci va faire passer votre phpScheduleIt de la verion 0.9.3 à 1.0.0.';
$strings['There is no way to undo this action'] = 'Il ne sera pas possible de revenir en arrière après cette action !';
$strings['Click to proceed'] = 'Clicker pour exécuter';
$strings['This version has already been upgraded to 1.0.0.'] = 'Cette version a déjà été modifiée en 1.0.0.';
$strings['Please delete this file.'] = 'Merci de détruire ce fichier.';
$strings['Successful update'] = 'La mise à jour s\'est déroulée avec succès';
$strings['Patch completed successfully'] = 'Le correctif a été appliqué avec succès';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'Cela va garnir les champs nécessaires à  phpScheduleIt 1.0.0 and corriger un  data bug in 0.9.9.'
		. '<br />Il est seulement nécessaire d\'exécuter ceci si vous procédez à une mise à jour manuelle d\' SQL ou que vous venez de 0.9.9';

// @since 1.0.0 RC1
$strings['If no value is specified, the default password set in the config file will be used.'] = 'Si aucune valeur n\'est précisée, le password par défaut spécifié dans le fichier de configuration (config.sys) sera utilisé.';
$strings['Notify user that password has been changed?'] = 'L\'utilisateur doit il être prévenu que son mot de passe a été changé ?'; 

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s\n\r\n"
				. "Vous avez été correctement enregistré avec les informations suivantes :\r\n"
				. "Nom: %s %s\r\n"
				. "Téléphone : %s\r\n"
				. "Institution : %s\r\n"
				. "Position : %s\r\n\r\n"
				. "Merci de vous connecter au planificateur à cet emplacement :\r\n"
				. "%s\r\n\r\n"
				. "Vous pouvez trouvez les liens d'accès au planificateur en ligne et de modification de votre profil dans le panneau de contrôle.\r\n\r\n"
				. "Merci d'adresser les questions relatives aux ressources et aux reservation à %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Administrateur,\r\n\r\n"
					. "Un nouvel utilisateur a été ajouté avec les informations :\r\n"
					. "Email : %s\r\n"
					. "Nom : %s %s\r\n"
					. "Téléphone : %s\r\n"
					. "Institution : %s\r\n"
					. "Position : %s\r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Vous avez %s reservations effectives #%s.\r\n\r\n<br/><br/>"
			. "Merci d'utilliser ce numéro de réservation lorsque vous contactez l'administrateur pour toute question.\r\n\r\n<br/><br/>"
			. "Une résservation le %s entre %s et %s pour %s"
			. " situéé à %s a été %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "Cette réservation a été répétée pour les dates suivantes :\r\n<br/>";
$email['reservation_activity_3'] = "Toutes les reservations cycliques de ce groupe sont aussi %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "Le résumé suivant a été établi pour la réservation suivante :\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "S'il s'agissait d'une erreur, merci de contacter l'administrateur à : %s"
			. " ou en appelant le %s.\r\n\r\n<br/><br/>"
			. "Vous pouvez voir et/ou modifier les informations relatives à vos réservation à tout moment en "
			. " vous connectant %s à :\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "Merci d'adresser toute question technique à <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Your %s password has been reset by the administrator.\r\n\r\n"
			. "Votre mot de passe temporaire est :\r\n\r\n %s\r\n\r\n"
			. "Merci d'utiliser ce mot de passe temporaire (copie et coller pour être sûr qu'il est correct) pour vous connecter %s at %s"
			. " et immédiatement changez le en utilisant 'Modifier les informations de mon profil/password' situé dans la table Mes liens rapides.\r\n\r\n"
			. "Merci de contacter %s pour toute question.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Votre nouveau mot de passe pour votre compte %s est :\r\n\r\n"
            . "%s\r\n\r\n"
            . "Merci de vous connecter à %s "
            . "avec ce nouveau mot de passe "
            . "(copier et coller le afin d'être sûr qu'is sera correct) "
            . "et changer le rapidement en clickant sur "
            . "Modifier les informations de mon profil/Password "
            . "situé dans mon panneau de contrôle.\r\n\r\n"
            . "Merci d'adresser toute question à %s.";
?>