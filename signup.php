<?php

echo"
<html>
  <title>Log In / Sign Up</title>
  <head></head>
  
  <body bgcolor=#dde6c6>
  
  <style type='text/css'>
     table {
	       font-family:'Helvetica',serif;
		   font-size: 11;
	       }
  </style>  
  
  <form action='profile.php' method='post'>
  
  <table width=100% border=0 bgcolor=#dde6c6>
  <tr><td align='center'>

  <table width=660px border=0 bgcolor='white'>
  <tr>
  <td style='background-image: url(images/G.jpg)' align='center'><br /><br />Newsletter - a simple monthly publication to keep friends connected<br /><br /><br /><br /></td>
  </tr>
  </table><br />
  
  <table width=660px border=0 bgcolor='white'>
  
  <tr>
  <td width=40px>&nbsp;</td>
  <td align='left'><br /><br />A new way to keep in touch with friends.<br /><br />
  Enter your email address to log in or sign up for the monthly publication. 10 days before the 1st of each month
  you will recieve an email from this service. You'll reply with a short paragraph of what you have been
  up to for the past four weeks.<br /><br />
  Then, on the 1st of each month, responses from all subscribers are combined and published in a PDF, which is sent to your
  email address. 
  <br /><br />
  </td>
  <td align='right' width=280px><br /><br />
  <strong>Email address</strong>
  <input type='input' name='login' size=20><br /><br />
  <input type='submit' value='      Log In / Sign up      '><br /><br /><br /><br />
  </td>
  <td width=40px>&nbsp;</td>
  </tr>
  <tr>
  <td align='center' colspan=4>
  <br />Send any comments or suggestions to <strong>ejeshelman@gmail.com</strong>.<br /><br /><br />
  </td>
  </tr>
  </table>
  
  </td></tr>
  </table>
  
  </form>

  </body>

</html>";
?>