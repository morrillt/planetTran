<?php
/**
* Spanish (es) translation file.
*  
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @translator Josue Rojas <josue_rojas@hotmail.com>
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
$charset = 'iso-8859-1';

/***
  DAY NAMES
  All of these arrays MUST start with Sunday as the first element 
   and go through the seven day week, ending on Saturday
***/
// The full day name
$days_full = array('Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado');
// The three letter abbreviation
$days_abbr = array('Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab');
// The two letter abbreviation
$days_two  = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa');
// The one letter abbreviation
$days_letter = array('D', 'L', 'M', 'M', 'J', 'V', 'S');

/***
  MONTH NAMES
  All of these arrays MUST start with January as the first element
   and go through the twelve months of the year, ending on December
***/
// The full month name
$months_full = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
// The three letter month name
$months_abbr = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');

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
$strings['hours'] = 'horas';
$strings['minutes'] = 'minutos';
// The common abbreviation to hint that a user should enter the month as 2 digits
$strings['mm'] = 'mm';
// The common abbreviation to hint that a user should enter the day as 2 digits
$strings['dd'] = 'dd';
// The common abbreviation to hint that a user should enter the year as 4 digits
$strings['yyyy'] = 'aaaa';
$strings['am'] = 'am';
$strings['pm'] = 'pm';

$strings['Administrator'] = 'Administrador';
$strings['Welcome Back'] = 'Bienvenido, %s';
$strings['Log Out'] = 'Cerrar Sesi�n';
$strings['My Control Panel'] = 'Mi P�nel de Control';
$strings['Help'] = 'Ayuda';
$strings['Manage Schedules'] = 'Administrar Horarios';
$strings['Manage Users'] ='Administrar Usuarios';
$strings['Manage Resources'] ='Administrar Recursos';
$strings['Manage User Training'] ='Administrar Entrenamiento de Usuarios';
$strings['Manage Reservations'] ='Administrar Reservas';
$strings['Email Users'] ='Email a Usuarios';
$strings['Export Database Data'] = 'Exportar Base de Datos';
$strings['Reset Password'] = 'Establecer Contrase�a';
$strings['System Administration'] = 'Administraci�n del Sistema';
$strings['Successful update'] = 'Actualizaci�n exitosa';
$strings['Update failed!'] = 'Fallo la actualizaci�n!';
$strings['Manage Blackout Times'] = 'Administrar Tiempos Muertos';
$strings['Forgot Password'] = 'Olvid� Su Contrase�a';
$strings['Manage My Email Contacts'] = 'Administrar Mis Contactos Email';
$strings['Choose Date'] = 'Escoja la Fecha';
$strings['Modify My Profile'] = 'Modificar Mi Perfil';
$strings['Register'] = 'Registrarse';
$strings['Processing Blackout'] = 'Procesando Tiempo Muerto';
$strings['Processing Reservation'] = 'Procesando Reserva';
$strings['Online Scheduler [Read-only Mode]'] = 'Horario en L�nea [Modo s�lo Consulta]';
$strings['Online Scheduler'] = 'Horario en L�nea';
$strings['phpScheduleIt Statistics'] = 'Estad�sticas Administrativas';
$strings['User Info'] = 'Datos de Usuario:';

$strings['Could not determine tool'] = 'No se puede determinar la herramienta. Por favor vuelva a Mi P�nel de Control e intente de nuevo m�s tarde.';
$strings['This is only accessable to the administrator'] = 'Esto s�lo est� disponible para el Administrador';
$strings['Back to My Control Panel'] = 'Volver a Mi P�nel de Control';
$strings['That schedule is not available.'] = 'Ese horario no est� disponible.';
$strings['You did not select any schedules to delete.'] = 'Usted no ha seleccionado horarios para borrar.';
$strings['You did not select any members to delete.'] = 'Usted no ha seleccionado miembros para borrar.';
$strings['You did not select any resources to delete.'] = 'Usted no ha seleccionado recursos para borrar';
$strings['Schedule title is required.'] = 'Es necesario un nombre para el horario.';
$strings['Invalid start/end times'] = 'Las horas de inicio o fin no son v�lidas.';
$strings['View days is required'] = 'Es necesario el n�mero de d�as visibles';
$strings['Day offset is required'] = 'Es necesario indicar el primer d�a de la semana';
$strings['Admin email is required'] = 'Es necesario el email administrativo';
$strings['Resource name is required.'] = 'Es necesario un nombre para el recurso.';
$strings['Valid schedule must be selected'] = 'Debe seleccionar un horario v�lido';
$strings['Minimum reservation length must be less than or equal to maximum reservation length.'] = 'La duraci�n m�nima de la reserva debe se menor o igual que la duraci�n m�xima de la reserva.';
$strings['Your request was processed successfully.'] = 'Su solicitud fue procesada exitosamente.';
$strings['Go back to system administration'] = 'Vuelva a la administraci�n del sistema';
$strings['Or wait to be automatically redirected there.'] = 'O espere para ser dirigido autom�ticamente.';
$strings['There were problems processing your request.'] = 'Se presentaron inconvenientes procesando su solicitud.';
$strings['Please go back and correct any errors.'] = 'Por favor vuelva atr�s y corrija los errores que existan.';
$strings['Login to view details and place reservations'] = 'Inicie sesi�n para ver los detalles y hacer reservas';
$strings['Memberid is not available.'] = 'El id de usuario: %s no est� disponible.';

$strings['Schedule Title'] = 'Nombre del Horario';
$strings['Start Time'] = 'Hora de Inicio';
$strings['End Time'] = 'Hora de Finalizaci�n';
$strings['Time Span'] = 'Franjas de Tiempo';
$strings['Weekday Start'] = 'Primer d�a de la Semana';
$strings['Admin Email'] = 'Email Administrativo';

$strings['Default'] = 'Por Defecto';
$strings['Reset'] = 'Restablecer';
$strings['Edit'] = 'Editar';
$strings['Delete'] = 'Borrar';
$strings['Cancel'] = 'Cancelar';
$strings['View'] = 'Verificar';
$strings['Modify'] = 'Modificar';
$strings['Save'] = 'Guardar';
$strings['Back'] = 'Volver';
$strings['Next'] = 'Siguiente';
$strings['Close Window'] = 'Cerrar Ventana';
$strings['Search'] = 'Buscar';
$strings['Clear'] = 'Limpiar';

$strings['Days to Show'] = 'D�as visibles';
$strings['Reservation Offset'] = 'Espacio entre Reservas';
$strings['Hidden'] = 'Oculto';
$strings['Show Summary'] = 'Mostrar Descripci�n';
$strings['Add Schedule'] = 'Adicionar Horario';
$strings['Edit Schedule'] = 'Cambiar Horario';
$strings['No'] = 'No';
$strings['Yes'] = 'Si';
$strings['Name'] = 'Nombre';
$strings['First Name'] = 'Nombres';
$strings['Last Name'] = 'Apellidos';
$strings['Resource Name'] = 'Nombre del Recurso';
$strings['Email'] = 'Email';
$strings['Institution'] = 'Instituci�n';
$strings['Phone'] = 'Tel�fono';
$strings['Password'] = 'Password';
$strings['Permissions'] = 'Permisos';
$strings['View information about'] = 'Ver la informaci�n de %s %s';
$strings['Send email to'] = 'Enviar email a %s %s';
$strings['Reset password for'] = 'Restablecer el password para %s %s';
$strings['Edit permissions for'] = 'Editar permisos para %s %s';
$strings['Position'] = 'Posici�n';
$strings['Password (6 char min)'] = 'Password (6 letras min.)';
$strings['Re-Enter Password'] = 'Confirme el Password';

$strings['Sort by descending last name'] = 'Ordenar por apellido en forma descendente';
$strings['Sort by descending email address'] = 'Ordenar por email en forma descendente';
$strings['Sort by descending institution'] = 'Ordenar por instituci�n en forma descendente';
$strings['Sort by ascending last name'] = 'Ordenar por apellido en forma ascendente';
$strings['Sort by ascending email address'] = 'Ordenar por email en forma ascendente';
$strings['Sort by ascending institution'] = 'Ordenar por instituci�n en forma ascendente';
$strings['Sort by descending resource name'] = 'Ordenar por nombre del recurso en forma descendente';
$strings['Sort by descending location'] = 'Ordenar por ubicaci�n en forma descendente';
$strings['Sort by descending schedule title'] = 'Ordenar por nombre del horario en forma descendente';
$strings['Sort by ascending resource name'] = 'Ordenar por nombre del recurso en forma ascendente';
$strings['Sort by ascending location'] = 'Ordenar por ubicaci�n en forma ascendente';
$strings['Sort by ascending schedule title'] = 'Ordenar por nombre del horario en forma ascendente';
$strings['Sort by descending date'] = 'Ordenar por fecha en forma descendente';
$strings['Sort by descending user name'] = 'Ordenar por nombre de usuario en forma descendente';
$strings['Sort by descending start time'] = 'Ordenar por fecha inicial en forma descendente';
$strings['Sort by descending end time'] = 'Ordenar por fecha final en forma descendente';
$strings['Sort by ascending date'] = 'Ordenar por fecha en forma ascendente';
$strings['Sort by ascending user name'] = 'Ordenar por nombre de usuario en forma ascendente';
$strings['Sort by ascending start time'] = 'Ordenar por fecha inicial en forma ascendente';
$strings['Sort by ascending end time'] = 'Ordenar por fecha final en forma descendente';
$strings['Sort by descending created time'] = 'Ordenar por fecha de solicitud en forma ascendente';
$strings['Sort by ascending created time'] = 'Ordenar por fecha de solicitud en forma descendente';
$strings['Sort by descending last modified time'] = 'Ordenar por fecha de modificaci�n en forma ascendente';
$strings['Sort by ascending last modified time'] = 'Ordenar por fecha de modificaci�n en forma descendente';

$strings['Search Users'] = 'Buscar Usuarios';
$strings['Location'] = 'Ubicaci�n';
$strings['Schedule'] = 'Horario';
$strings['Phone'] = 'Tel�fono';
$strings['Notes'] = 'Notas';
$strings['Status'] = 'Estado';
$strings['All Schedules'] = 'Todos los Horarios';
$strings['All Resources'] = 'Todos los Recursos';
$strings['All Users'] = 'Todos los Usuarios';

$strings['Edit data for'] = 'Editar la informaci�n de %s';
$strings['Active'] = 'Activo';
$strings['Inactive'] = 'Inactivo';
$strings['Toggle this resource active/inactive'] = 'Cambiar este Recuso entre activo/inactivo';
$strings['Minimum Reservation Time'] = 'Tiempo M�nimo de Reserva';
$strings['Maximum Reservation Time'] = 'Tiempo M�ximo de Reserva';
$strings['Auto-assign permission'] = 'Permiso de Auto-asignaci�n';
$strings['Add Resource'] = 'A�adir un Recurso';
$strings['Edit Resource'] = 'Editar un Recurso';
$strings['Allowed'] = 'Permitido';
$strings['Notify user'] = 'Notificar al usuario';
$strings['User Reservations'] = 'Reservas de Usuario';
$strings['Date'] = 'Fecha';
$strings['User'] = 'Usuario';
$strings['Email Users'] = 'Enviar un Email a los Usuarios';
$strings['Subject'] = 'Asunto';
$strings['Message'] = 'Mensaje';
$strings['Please select users'] = 'Por favor seleccione los usuarios';
$strings['Send Email'] = 'Enviar Email';
$strings['problem sending email'] = 'Lo siento, hubo un problema enviando el email. Por favor intente m�s tarde.';
$strings['The email sent successfully.'] = 'El email fue enviado exitosamente.';
$strings['do not refresh page'] = 'Por favor <u>no</u> use Actualizar en esta p�gina. Si lo hace, el email se enviar� otra vez.';
$strings['Return to email management'] = 'Volver a administraci�n de emails';
$strings['Please select which tables and fields to export'] = 'Por favor indique cu�les tablas y campos desea exportar:';
$strings['all fields'] = '- todos los campos -';
$strings['HTML'] = 'HTML';
$strings['Plain text'] = 'Texto simple';
$strings['XML'] = 'XML';
$strings['CSV'] = 'CSV';
$strings['Export Data'] = 'Exportar Datos';
$strings['Reset Password for'] = 'Restablecer Password de %s';
$strings['Please edit your profile'] = 'Por favor modifique su perfil';
$strings['Please register'] = 'Por favor reg�strese';
$strings['Email address (this will be your login)'] = 'Direcci�n de Email (Este ser� su nombre de usuario)';
$strings['Keep me logged in'] = 'Mantener la sesi�n abierta <br/>(requiere cookies)';
$strings['Edit Profile'] = 'Editar Perfil';
$strings['Register'] = 'Registrarse';
$strings['Please Log In'] = 'Por favor inicie sesi�n';
$strings['Email address'] = 'Direcci�n de Email';
$strings['Password'] = 'Password';
$strings['First time user'] = 'Usuario por primera vez?';
$strings['Click here to register'] = 'Reg�strese haciendo clic aqu�';
$strings['Register for phpScheduleIt'] = 'Registrarse en phpScheduleIt';
$strings['Log In'] = 'Iniciar Sesi�n';
$strings['View Schedule'] = 'Ver Agenda';
$strings['View a read-only version of the schedule'] = 'Ver la Agenda -S�lo Consulta-';
$strings['I Forgot My Password'] = 'Olvid� mi Password';
$strings['Retreive lost password'] = 'Recuperar password perdido';
$strings['Get online help'] = 'Obtener ayuda en l�nea';
$strings['Language'] = 'Idioma';
$strings['(Default)'] = '(por defecto)';

$strings['My Announcements'] = 'Mis anuncios';
$strings['My Reservations'] = 'Mis Reservas';
$strings['My Permissions'] = 'Mis Permisos';
$strings['My Quick Links'] = 'Mis Accesos Directos';
$strings['Announcements as of'] = 'Anuncios para %s';
$strings['There are no announcements.'] = 'No hay anuncios.';
$strings['Resource'] = 'Recurso';
$strings['Created'] = 'Creado';
$strings['Last Modified'] = 'Modificado por �ltima vez';
$strings['View this reservation'] = 'Ver esta reserva';
$strings['Modify this reservation'] = 'Modificar esta reserva';
$strings['Delete this reservation'] = 'Borrar esta reserva';
$strings['Go to the Online Scheduler'] = 'Ver Agenda en L�nea';
$strings['Change My Profile Information/Password'] = 'Cambiar la Informaci�n de mi Perfil o Password';
$strings['Manage My Email Preferences'] = 'Cambiar mis Preferencias de Email';
$strings['Mass Email Users'] = 'Enviar un Email a todos los Usuarios';
$strings['Search Scheduled Resource Usage'] = 'Examinar el uso de un Recurso';
$strings['Export Database Content'] = 'Exportar el Contenido de la Base de Datos';
$strings['View System Stats'] = 'Ver Estad�sticas del Sistema';
$strings['Email Administrator'] = 'Enviar un Email al Administrador';

$strings['Email me when'] = 'Enviarme un Email cuando:';
$strings['I place a reservation'] = 'Yo haga una reserva';
$strings['My reservation is modified'] = 'Se modifique mi reserva';
$strings['My reservation is deleted'] = 'Se borre mi reserva';
$strings['I prefer'] = 'Prefiero:';
$strings['Your email preferences were successfully saved'] = 'Sus preferencias de email han sido guardadas';
$strings['Return to My Control Panel'] = 'Volver a Mi P�nel de Control';

$strings['Please select the starting and ending times'] = 'Por favor indique las fechas inicial y final:';
$strings['Please change the starting and ending times'] = 'Por favor cambie las fechas inicial y final:';
$strings['Reserved time'] = 'Tiempo reservado:';
$strings['Minimum Reservation Length'] = 'Tiempo M�nimo de Reserva:';
$strings['Maximum Reservation Length'] = 'Tiempo M�ximo de Reserva:';
$strings['Reserved for'] = 'Reservado para:';
$strings['Will be reserved for'] = 'Ser� reservado para:';
$strings['N/A'] = 'N/D';
$strings['Update all recurring records in group'] = 'Actualizar todos los registros recurrentes a la vez?';
$strings['Delete?'] = 'Borrar?';
$strings['Never'] = '-- Nunca --';
$strings['Days'] = 'D�as';
$strings['Weeks'] = 'Semanas';
$strings['Months (date)'] = 'Meses (fecha)';
$strings['Months (day)'] = 'Meses (d�a)';
$strings['First Days'] = 'Primer D�a';
$strings['Second Days'] = 'Segundo D�a';
$strings['Third Days'] = 'Tercer D�a';
$strings['Fourth Days'] = 'Cuarto D�a';
$strings['Last Days'] = '�ltimo D�a';
$strings['Repeat every'] = 'Repetir cada:';
$strings['Repeat on'] = 'Repetir en:';
$strings['Repeat until date'] = 'Repetir hasta esta fecha:';
$strings['Choose Date'] = 'Elegir Fecha';
$strings['Summary'] = 'Descripci�n';

$strings['View schedule'] = 'Ver Agenda:';
$strings['My Reservations'] = 'Mis Reservas';
$strings['My Past Reservations'] = 'Mis Reservas Pasadas';
$strings['Other Reservations'] = 'Otras Reservas';
$strings['Other Past Reservations'] = 'Otras Reservas Pasadas';
$strings['Blacked Out Time'] = 'Tiempo Muerto';
$strings['Set blackout times'] = 'Establecer tiempo muerto para %s en %s'; 
$strings['Reserve on'] = 'Reservar %s en %s';
$strings['Prev Week'] = '� Semana Ant.';
$strings['Jump 1 week back'] = 'Volver 1 semana atr�s';
$strings['Prev days'] = '� %d d�as ant.';
$strings['Previous days'] = '� %d d�as anteriores';
$strings['This Week'] = 'Esta Semana';
$strings['Jump to this week'] = 'Ir a esta semana';
$strings['Next days'] = '%d d�as siguientes �';
$strings['Next Week'] = 'Siguiente Semana �';
$strings['Jump To Date'] = 'Ir a una Fecha';
$strings['View Monthly Calendar'] = 'Ver Calendario Mensual';
$strings['Open up a navigational calendar'] = 'Abrir una Calendario para Navegar';

$strings['View stats for schedule'] = 'Ver estad�sticas del horario:';
$strings['At A Glance'] = 'En Resumen';
$strings['Total Users'] = 'Total de Usuarios:';
$strings['Total Resources'] = 'Total de Recursos:';
$strings['Total Reservations'] = 'Total de Reservas:';
$strings['Max Reservation'] = 'Reserva M�xima:';
$strings['Min Reservation'] = 'Reserva M�nima:';
$strings['Avg Reservation'] = 'Reserva Promedio:';
$strings['Most Active Resource'] = 'Recurso m�s Activo:';
$strings['Most Active User'] = 'Usuario m�s Activo:';
$strings['System Stats'] = 'Estad�sticas del Sistema';
$strings['phpScheduleIt version'] = 'Versi�n de phpScheduleIt:';
$strings['Database backend'] = 'Base de Datos:';
$strings['Database name'] = 'Nombre de Base de Datos:';
$strings['PHP version'] = 'Versi�n de PHP:';
$strings['Server OS'] = 'Sistema del Servidor:';
$strings['Server name'] = 'Nombre del Servidor:';
$strings['phpScheduleIt root directory'] = 'Directorio ra�z de phpScheduleIt:';
$strings['Using permissions'] = 'Permisos de Uso:';
$strings['Using logging'] = 'Log de Uso:';
$strings['Log file'] = 'Archivo de Log:';
$strings['Admin email address'] = 'Direcci�n email del administrador:';
$strings['Tech email address'] = 'Direcci�n email T�cnico:';
$strings['CC email addresses'] = 'Direcciones email para copias (CC):';
$strings['Reservation start time'] = 'Hora inicial de reserva:';
$strings['Reservation end time'] = 'Hora final de reserva:';
$strings['Days shown at a time'] = 'D�as mostrados a la vez:';
$strings['Reservations'] = 'Reservas';
$strings['Return to top'] = 'Volver arriba';
$strings['for'] = 'para';

$strings['Select Search Criteria'] = 'Indique el Criterio de B�squeda';
$strings['Schedules'] = 'Horarios:';
$strings['All Schedules'] = 'Todos los Horarios';
$strings['Hold CTRL to select multiple'] = 'Mantenga la tecla CTRL presionada para seleccionar varios';
$strings['Users'] = 'Usuarios:';
$strings['All Users'] = 'Todos los Usuarios';
$strings['Resources'] = 'Recursos:';
$strings['All Resources'] = 'Todos los Recursos:';
$strings['Starting Date'] = 'Fecha Inicial:';
$strings['Ending Date'] = 'Fechas Final:';
$strings['Starting Time'] = 'Hora Inicial:';
$strings['Ending Time'] = 'Hora Final:';
$strings['Output Type'] = 'Tipo de Salida:';
$strings['Manage'] = 'Administrar';
$strings['Total Time'] = 'Tiempo Total';
$strings['Total hours'] = 'Horas en total:';
$strings['% of total resource time'] = '% del tiempo total del recurso';
$strings['View these results as'] = 'Ver estos resultados como:';
$strings['Edit this reservation'] = 'Editar esta reserva';
$strings['Search Results'] = 'Buscar Resultados';
$strings['Search Resource Usage'] = 'Buscar uso del Recurso';
$strings['Search Results found'] = 'Resultados de la B�squeda: Se encontraron %d reservas';
$strings['Try a different search'] = 'Intente otra B�squeda';
$strings['Search Run On'] = 'Hacer la B�squeda en:';
$strings['Member ID'] = 'ID de Miembro';
$strings['Previous User'] = '� Usuario Anterior';
$strings['Next User'] = 'Usuario Siguiente �';

$strings['No results'] = 'No hay resultados';
$strings['That record could not be found.'] = 'No se encontr� ese registro.';
$strings['This blackout is not recurring.'] = 'Este tiempo muerto no es recurrente.';
$strings['This reservation is not recurring.'] = 'Esta reserva no es recurrente.';
$strings['There are no records in the table.'] = 'No hay registros en la tabla %s.';
$strings['You do not have any reservations scheduled.'] = 'No tiene ninguna reserva programada.';
$strings['You do not have permission to use any resources.'] = 'No tiene permiso para usar ning�n recurso.';
$strings['No resources in the database.'] = 'No hay recursos en la base de datos.';
$strings['There was an error executing your query'] = 'Hubo un error ejecutando el comando en la base de datos:';

$strings['That cookie seems to be invalid'] = 'Esa cookie parece ser inv�lida';
$strings['We could not find that email in our database.'] = 'No se encontr� ese email en la base de datos.';
$strings['That password did not match the one in our database.'] = 'Ese password no coincide con el de nuestra base de datos.';
$strings['You can try'] = '<br />Usted puede:<br />Registrar una direcci�n email.<br />O:<br />Volver a intentar iniciar sesi�n.';
$strings['A new user has been added'] = 'Un nuevo usuario ha sido adicionado';
$strings['You have successfully registered'] = 'Usted se ha registrado exitosamente!';
$strings['Continue'] = 'Continuar...';
$strings['Your profile has been successfully updated!'] = 'Su perfil ha sido actualizado exitosamente!';
$strings['Please return to My Control Panel'] = 'Por favor vuelva a Mi P�nel de Control';
$strings['Valid email address is required.'] = '- Se requiere una direcci�n de email v�lida.';
$strings['First name is required.'] = '- Se requiere el nombre.';
$strings['Last name is required.'] = '- Se requiere el Apellido.';
$strings['Phone number is required.'] = '- Se requiere el tel�fono.';
$strings['That email is taken already.'] = '- Ese email ya est� registrado.<br />Por favor intente de nuevo con otra direcci�n email.';
$strings['Min 6 character password is required.'] = '- Se requiere un password de almento 6 caracteres.';
$strings['Passwords do not match.'] = '- Los passwords no coinciden.';

$strings['Per page'] = 'Por p�gina:';
$strings['Page'] = 'P�gina:';

$strings['Your reservation was successfully created'] = 'Su reserva fue creada exitosamente';
$strings['Your reservation was successfully modified'] = 'Su reserva fue modificada exitosamente';
$strings['Your reservation was successfully deleted'] = 'Su reserva fue borrada exitosamente';
$strings['Your blackout was successfully created'] = 'Su tiempo muerto fue creado exitosamente';
$strings['Your blackout was successfully modified'] = 'Su tiempo muerto fue modificado exitosamente';
$strings['Your blackout was successfully deleted'] = 'Su tiempo muerto fue borrado exitosamente';
$strings['for the follwing dates'] = 'para las siguientes fechas:';
$strings['Start time must be less than end time'] = 'El momento inicial debe ser anterior al momento final.';
$strings['Current start time is'] = 'Fecha inicial actualmente es:';
$strings['Current end time is'] = 'Fecha final actualmente es:';
$strings['Reservation length does not fall within this resource\'s allowed length.'] = 'La duraci�n de la reserva no est� en el rango permitido para este recurso.';
$strings['Your reservation is'] = 'Su reserva es:';
$strings['Minimum reservation length'] = 'Duraci�n m�nima de la reserva:';
$strings['Maximum reservation length'] = 'Duraci�n m�xima de la reserva:';
$strings['You do not have permission to use this resource.'] = 'Usted no tiene permiso para usar este recurso.';
$strings['reserved or unavailable'] = '%s desde %s hasta %s ya est� reservado o no est� disponible.';
$strings['Reservation created for'] = 'Reserva creada para para %s';
$strings['Reservation modified for'] = 'Reserva modificada para %s';
$strings['Reservation deleted for'] = 'Reserva borrada para %s';
$strings['created'] = 'creado';
$strings['modified'] = 'modificado';
$strings['deleted'] = 'borrado';
$strings['Reservation #'] = 'Reserva #';
$strings['Contact'] = 'Contacto';
$strings['Reservation created'] = 'Reserva creada';
$strings['Reservation modified'] = 'Reserva modificada';
$strings['Reservation deleted'] = 'Reserva borrada';

$strings['Reservations by month'] = 'Reservas por mes';
$strings['Reservations by day of the week'] = 'Reservas por d�a de la semana';
$strings['Reservations per month'] = 'Reservas por mes';
$strings['Reservations per user'] = 'Reservas por usuario';
$strings['Reservations per resource'] = 'Reservas por recurso';
$strings['Reservations per start time'] = 'Reservas por fecha inicial';
$strings['Reservations per end time'] = 'Reservas por fecha final';
$strings['[All Reservations]'] = '[Todas las Reservas]';

$strings['Permissions Updated'] = 'Permisos Actualizados';
$strings['Your permissions have been updated'] = 'Sus %s permisos han sido actualizados';
$strings['You now do not have permission to use any resources.'] = 'Usted no tiene permisos para usar ning�n recurso.';
$strings['You now have permission to use the following resources'] = 'Usted no tiene permisos para usar los siguientes recursos:';
$strings['Please contact with any questions.'] = 'Por favor contacte a %s para m�s informaci�n.';
$strings['Password Reset'] = 'Password Restablecido';

$strings['This will change your password to a new, randomly generated one.'] = 'Esto cambiar� su password a uno nuevo, generado aleatoriamente.';
$strings['your new password will be set'] = 'Despu�s de escribir su email y hacer clic en "Cambiar Password", su nuevo password ser� activado en el sistema y enviado a su email.';
$strings['Change Password'] = 'Cambiar Password';
$strings['Sorry, we could not find that user in the database.'] = 'Lo siento, el usuario no se encuentra en la base de datos.';
$strings['Your New Password'] = 'Su Nuevo %s Password';
$strings['Your new passsword has been emailed to you.'] = 'Listo!<br />'
    			. 'Su nuevo password ha sido enviado.<br />'
    			. 'Por favor busque su nuevo password en su correo, y luego <a href="index.php">Inicie Sesi�n</a>'
    			. ' con este nuevo password y c�mbielo enseguida haciendo clic en &quot;Cambiar la Informaci�n de mi Perfil/Password&quot;'
    			. ' en Mi P�nel de Control.';

$strings['You are not logged in!'] = 'Usted no ha iniciado sesi�n!';

$strings['Setup'] = 'Configuraci�n';
$strings['Please log into your database'] = 'Por favor inicie sesi�n en la base de datos';
$strings['Enter database root username'] = 'Ingrese el usuario root de la base de datos:';
$strings['Enter database root password'] = 'Ingrese el password de root:';
$strings['Login to database'] = 'Iniciar sesi�n en la base de datos';
$strings['Root user is not required. Any database user who has permission to create tables is acceptable.'] = '<b>No</b> es necesario el usuario root. Cualquier usuario con permisos para crear tablas funciona.';
$strings['This will set up all the necessary databases and tables for phpScheduleIt.'] = 'Esto crear� las bases de datos y tablas necesarias para phpScheduleIt.';
$strings['It also populates any required tables.'] = 'Tambi�n crear� los datos en las tablas requeridas.';
$strings['Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!'] = 'Advertencia: ESTO BORRAR� TODA LA INFORMACI�N DE BASES DE DATOS ANTERIORES DE phpScheduleIt!';
$strings['Not a valid database type in the config.php file.'] = 'Tipo inv�lido de base de datos en el archivo config.php.';
$strings['Database user password is not set in the config.php file.'] = 'Password de usuario de base de datos no indicado en el archivo config.php.';
$strings['Database name not set in the config.php file.'] = 'Nombre de base de datos no indicado en el archivo config.php.';
$strings['Successfully connected as'] = 'Se conect� exitosamente como';
$strings['Create tables'] = 'Crear tablas &gt;';
$strings['There were errors during the install.'] = 'Hubo errores durante la instalaci�n. Es posible, sin embargo, que phpScheduleIt funcione si los problemas no fueron graves.<br/><br/>'
	. 'Por favor publique sus preguntas en los foros de <a href="http://sourceforge.net/forum/?group_id=95547">SourceForge</a>.';
$strings['You have successfully finished setting up phpScheduleIt and are ready to begin using it.'] = 'Usted ha terminado de instalar phpScheduleIt y est� listo para empezar a usarlo.';
$strings['Thank you for using phpScheduleIt'] = 'Por favor ELIMINE COMPLETAMENTE EL DIRECTORIO \'install\'.'
	. ' Esto es cr�tico ya que contiene los passwords de la base de datos y otra informaci�n importante.'
	. ' El no hacerlo es dejar la puerta abierta para que cualquier persona tome el control de su sistema!'
	. '<br /><br />'
	. 'Gracias por usar phpScheduleIt!';
$strings['This will update your version of phpScheduleIt from 0.9.3 to 1.0.0.'] = 'Esto actualizar� su versi�n de phpScheduleIt de 0.9.3 a 1.0.0.';
$strings['There is no way to undo this action'] = 'No hay forma de deshacer este cambio!';
$strings['Click to proceed'] = 'Clic para iniciar';
$strings['This version has already been upgraded to 1.0.0.'] = 'Esta versi�n ya fue actualizada a 1.0.0.';
$strings['Please delete this file.'] = 'Por favor borre este archivo.';
$strings['Successful update'] = 'La actualizaci�n se hizo exitosamente';
$strings['Patch completed successfully'] = 'La correcci�n se completo exitosamente';
$strings['This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.'] = 'Esto llenar� los campos requeridos para phpScheduleIt 1.0.0 y corregir� el error de datos de 0.9.9.'
		. '<br />S�lo se requiere ejecutar esto si Usted realiz� una actualizaci�n manual de SQL o est� actualizando versi�n desde 0.9.9';

// @since 1.0.0 RC1
$strings['If no value is specified, the default password set in the config file will be used.'] = 'Si no se especifico un valor, se usar� el password por defecto del archivo de configuraci�n.';
$strings['Notify user that password has been changed?'] = 'Notificar al usuario que el password ha cambiado?';

/***
  EMAIL MESSAGES
  Please translate these email messages into your language.  You should keep the sprintf (%s) placeholders
   in their current position unless you know you need to move them.
  All email messages should be surrounded by double quotes "
  Each email message will be described below.
***/
// Email message that a user gets after they register
$email['register'] = "%s, %s \r\n"
				. "Usted se ha registrado exitosamente con la siguiente informaci�n:\r\n"
				. "Nombre: %s %s \r\n"
				. "Tel�fono: %s \r\n"
				. "Instituci�n: %s \r\n"
				. "Cargo: %s \r\n\r\n"
				. "Por favor incie sesi�n en:\r\n"
				. "%s \r\n\r\n"
				. "Encontrar� accesos para la agenda en l�nea y podr� editar su perfil en Mi P�nel de Control.\r\n\r\n"
				. "Para preguntas sobre recursos o reservas escriba a %s";

// Email message the admin gets after a new user registers
$email['register_admin'] = "Administrador,\r\n\r\n"
					. "Un nuevo usuario se ha registrado con la siguiente informaci�n:\r\n"
					. "Email: %s \r\n"
					. "Nombre: %s %s \r\n"
					. "Tel�fono: %s \r\n"
					. "Instituci�n: %s \r\n"
					. "Cargo: %s \r\n\r\n";

// First part of the email that a user gets after they create/modify/delete a reservation
// 'reservation_activity_1' through 'reservation_activity_6' are all part of one email message
//  that needs to be assembled depending on different options.  Please translate all of them.
$email['reservation_activity_1'] = "%s,\r\n<br />"
			. "Usted ha %s la reserva #%s.\r\n\r\n<br/><br/>"
			. "Por favor use este n�mero de reserva al contactar al administrador para alguna consulta.\r\n\r\n<br/><br/>"
			. "Una reserva en %s entre %s y %s para %s"
			. " ubicado en %s ha sido %s.\r\n\r\n<br/><br/>";
$email['reservation_activity_2'] = "Esta reserva se ha repetido en las siguientes fechas:\r\n<br/>";
$email['reservation_activity_3'] = "Todas las reservas recurrentes de esta serie, tambien fueron %ss.\r\n\r\n<br/><br/>";
$email['reservation_activity_4'] = "El siguiente es el resumen de para esta reserva:\r\n<br/>%s\r\n\r\n<br/><br/>";
$email['reservation_activity_5'] = "Si Usted considera esto un error, por favor contacte al administrador en: %s"
			. " o llamando al %s.\r\n\r\n<br/><br/>"
			. "Usted puede ver o modificar su reserva en cualquier momento"
			. " iniciando sesi�n en %s en:\r\n<br/>"
			. "<a href=\"%s\" target=\"_blank\">%s</a>.\r\n\r\n<br/><br/>";
$email['reservation_activity_6'] = "Por favor dirija las preguntas t�cnicas a <a href=\"mailto:%s\">%s</a>.\r\n\r\n<br/><br/>";

// Email that the user gets when the administrator changes their password
$email['password_reset'] = "Su password %s ha sido restablecido por el administrador.\r\n\r\n"
			. "Su password temporal es:\r\n\r\n %s\r\n\r\n"
			. "Por favor use este password temporal (puede usar copiar y pegar para mayor facilidad) para iniciar sesi�n en %s en %s"
			. " y c�mbielo de inmediato usando la opci�n 'Cambiar la Informaci�n de mi Perfil/Password' en la tabla Mis Accesos Directos.\r\n\r\n"
			. "Por favor contacte a %s para mayor informaci�n.";

// Email that the user gets when they change their lost password using the 'Password Reset' form
$email['new_password'] = "%s,\r\n"
            . "Su nuevo password para su cuenta de %s es:\r\n\r\n"
            . "%s\r\n\r\n"
            . "Por favor inicie sesi�n en %s "
            . "con este nuevo password "
            . "(puede usar copiar y pegar para mayor facilidad) "
            . "y c�mbielo de inmediato haciendo clic en "
            . "Cambiar la Informaci�n de mi Perfil/Password "
            . "en Mi Panel de Control.\r\n\r\n"
            . "Por favor contacte a %s para mayor informaci�n.";
?>