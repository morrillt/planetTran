<html>
<head>
<title>PlanetTran</title>
<style type="text/css">
td { vertical-align: top; }
.button {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	border: solid 1px #7C7A7A;
	color: white;
	background-color: #74B743;
}
</style>
</head>
<body>
	<div align="center"><img src="planettran_logo_pda.gif" border=0></div>
<form name="login" method="post" action="http://www.planettran.com/secure/reservations/ptRes2/m.index.php">
	<table width="100%" cellspacing=0 cellpadding=0>
	<tr>
		<td width="20%">Email</td>
  		<td width="80%"><input type="text" name="email"></td>
	<tr>
		<td>Password</td>
		<td><input type="password" name="password"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Log in"></td>
	</tr>
	</table>

	<!--<a href="register.php?billtype=&biz=&groupid=" class="" style="" onmouseover="javascript: window.status='Register'; return true;" onmouseout="javascript: window.status=''; return true;">First time user?</a>-->
	<input type="hidden" name="setCookie" value="true">
	<input type="hidden" name="resume" value="m.cpanel.php" />
	<input type="hidden" name="login" value="1" />
</form>
<br>
<a href="http://www.planettran.com/secure/reservations/ptRes2/m.index.php">Log In</a>
</body></html>
