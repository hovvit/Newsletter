<?php

// Get most recent publication
 $user="";
 $host="";
 $password="";
 $dbname="";

 $cxn = mysql_connect($host,$user,$password)
     	  or die ("Couldn't connect to server");
 
 $link = mysql_select_db($dbname, $cxn);

	$query_getID = "SELECT issue FROM publications WHERE onlyCol=1";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");

	$userIDr = mysql_fetch_assoc($result_getID);
	
	$currentIssue = $userIDr['issue'];
	
function makeName($currentIssue)
{
	if ($currentIssue > 12)
	{
	if ( $i = ($currentIssue % 12) == 0 )
	{
	$i = 12;
	$v = (($currentIssue ) / 12);
	}
	else
	{
	$i = ($currentIssue % 12);
	$v = (($currentIssue - $i) / 12)+1;
	}
	}
	else
	{
	$i = $currentIssue;
	$v = 1;
	}
	
	$str = 'Volume '.$v.', Issue '.$i;
	
	return $str;
}
	
	

echo "
<html>
  <title>Publications</title>
  <head></head>
  
  <body bgcolor=#dde6c6>
  
  <style type='text/css'>
     table {
	       font-family:'Helvetica',serif;
		   font-size: 11;
	       }
     .source {display:block; border:1px solid #c2bba2; background-color:'white';}
	 A:link {text-decoration: none; color: #645d41;}
	A:visited {text-decoration: none; color: #645d41;}
	A:active {text-decoration: none; color: #645d41;}
	A:hover {text-decoration: underline; color: #ddd6ba;}
  </style>  
  
  <form>
  
  <table width=100% border=0 bgcolor=#dde6c6>
  <tr><td align='center'>

  <table width=660px border=0 bgcolor='white'>
  <tr>
  <td style='background-image: url(images/G.jpg)' align='center'><br /><br />Newsletter - a simple monthly publication to keep friends connected<br /><br /></td>
  </tr>
  <tr><td align='center'><a href='signup.php'>[Log in]</a><br /><br /></td></tr>
  </table><br />
  
  <table width=660px border=0 bgcolor='white'>
  
  <tr>
  <td align='center'>
  <br /><br />
  <table width=400px>
  <tr>
  <td align='center' class='source'><br />
  <strong>Most recent publication:</strong>
  <br /><br />";
  
  $currentLink = $currentIssue.'.pdf';
  $currentName = makeName($currentIssue);
  
  echo "<a href='publications/$currentLink'>$currentName</a><br /><br />
  </td></tr>
  </table>
  
  <br /><br />
 
  <strong>Archives:</strong>
  
  <br /><br />";
  
  
  for ($i = ($currentIssue - 1); $i > 0; $i--)
  {
  
    $currentName = makeName($i);
	echo "<a href='publications/$i.pdf'>$currentName</a><br />";
  }
  
  echo "
  <br />
  Send any comments or suggestions to <strong>ejeshelman@gmail.com</strong>.<br /><br /><br />
  
  </td>
  </tr>
  
  </table>
  
  </td></tr>
  </table>
  
  </form>

  </body>

</html>";
?>