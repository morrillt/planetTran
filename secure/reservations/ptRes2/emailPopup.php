<?php
	/*
	 *  The quick and dirty way.  I don't like it either!
	 */
	include_once('lib/Template.class.php');
	include_once('templates/cpanel.template.php');
	
	$t = new Template(translate('Receipt Email | Reservations | PlanetTran'), false);
	$t->printCSSHeader('');
    
    if(isset($_GET['email'])) 
    	$email= $_GET['email'];
	if(isset($_GET['resid'])) 
    	$resid = $_GET['resid'];
	
	if(!$resid || !$email) //if they arent set, something wrong
		die("Missing information to complete email!");
	else {
		// continue on....
		$emailUrl = "../../dispatch/ptRes/emailReceipt.php";
	}	
?>
		<form id="addEmailCopy" action="<?php echo $emailUrl ?>" method="POST">
				<LABEL for="email" align="middle"><h2>List of addresses to send copies of receipt. <br />Single or multiple comma separated addresses. <br />(ex: <i>address@example.com,address2@example.com</i>)</h2></LABEL>
					<INPUT type="text" id="additionalEmail"><br />
				<INPUT type="hidden" value="<?php echo $resid ?>">
				<INPUT type="hidden" value="<?php echo $email ?>">
				<INPUT type="submit" value="Send Receipt Email">
		</form>
	
<?php $t->printHTMLFooter(); ?>

