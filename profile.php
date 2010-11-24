<?php
 $user="user";
 $host="localhost";
 $password="";
 $dbname="friendpaper";

 $cxn = mysql_connect($host,$user,$password)
     	  or die ("Couldn't connect to server");
 
 $link = mysql_select_db($dbname, $cxn);

 
 // This will run on any page where the shopping cart
 // accesses the DB. Prevents SQL injection through any
 // POST variable.
 foreach ($_POST as $key => $value) 
 { 
 	$_POST[$key] = mysql_real_escape_string($value); 
 }

 function safe($value)
 { 
 	return mysql_real_escape_string($value); 
 }  

 // If the save button has been pressed, save the form data.
 function saveData($cxn, $userID)
 {
	/*switch ($_POST['postBirthMonth'])
	{
		case 'January': { $p4 = 1; } break;
		case 'February': { $p4 = 2; } break;
		case 'March': { $p4 = 3; } break;
		case 'April': { $p4 = 4; } break;
		case 'May': { $p4 = 5; } break;
		case 'June': { $p4 = 6; } break;
		case 'July': { $p4 = 7; } break;
		case 'August': { $p4 = 8; } break;
		case 'September': { $p4 = 9; } break;
		case 'October': { $p4 = 10; } break;
		case 'November': { $p4 = 11; } break;
		case 'December': { $p4 = 12; } break;
	}*/
	
	$p1 = safe($_POST['postName']);
	$p2 = safe($_POST['postEmail']);
	$p3 = safe($_POST['postContact']);
	$p4 = safe($_POST['postBirthMonth']);
	$p5 = safe($_POST['postBirthDay']);
	$p6 = safe($_POST['postNews']);
	$p7 = safe($_POST['postEvent1']);
	$p8 = safe($_POST['postEvent2']);
	$p9 = safe($_POST['postEvent3']);
	$p10 = safe($_POST['postHeadline']);
	$p11 = safe($_POST['postStory']);
	$p12 = safe($_POST['postBlogName']);
	$p13 = safe($_POST['postBlogURL']);
	$p14 = safe($_POST['postBlogDescription']);
	$p15 = safe($_POST['postMisc1']);
	$p16 = safe($_POST['postMisc2']);

	//$pOld = safe($_POST['login']);

	$query_saveData = "UPDATE userdata SET 
						 nameFull='$p1', emailAddress='$p2', contactInfo='$p3',
						 birthMonth=$p4, birthDay=$p5, newsMonth='$p6', event1='$p7',
						 event2='$p8', event3='$p9', newsHeadline='$p10',
						 newsStory='$p11', blogName='$p12', blogURL='$p13',
						 blogDescription='$p14', misc1='$p15', misc2='$p16'  
	                     WHERE userID='$userID'";
 	$result_saveData = mysql_query($query_saveData, $cxn)
     	  or die ("Couldn't execute query 1");
 }
 
 $userID = 0;
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
 // check for the login info... if none exists, redirect to home
 if (isset ($_POST['login']))
 {
	$currentUser = safe($_POST['login']);
	
	// get the ID
	$query_getID = "SELECT userID FROM userdata WHERE emailAddress='$currentUser'";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");
	
	// If zero rows, create a new user.
	if (mysql_num_rows($result_getID) == 0)
	{
		$qInsert = "INSERT INTO userdata SET emailAddress='$currentUser'";
		$qResult= mysql_query($qInsert, $cxn)
     	  or die ("Couldn't execute query 3");
	}
	
	// Now get the ID again...
	$query_getID = "SELECT userID FROM userdata WHERE emailAddress='$currentUser'";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");

	$userIDr = mysql_fetch_assoc($result_getID);
	
	$userID = $userIDr['userID'];
	
 }
 
 if (isset ($_POST['userID']))
 {
	$userID = $_POST['userID'];
 }
 
 if ($userID == 0)
 {
	header('Location: signup.php');
 }
 
 // save data if neccessary
 $errors = 0;
 if (isset($_POST['saveTrue']))
	{ 
		saveData($cxn, $userID);
		// reset current user (allows you to change your email address)
		$currentUser = safe($_POST['postEmail']);
	}

 //reads the name of the file the user submitted for uploading
 	//$image=$_FILES['image']['name'];
 	//if it is not empty
 	if ( isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')//$image) 
 	{
		//get the original name of the file from the clients machine
			$filename = stripslashes($_FILES['image']['name']);
		//get the extension of the file in a lower case format
			$extension = getExtension($filename);
			$extension = strtolower($extension);
		//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
		//otherwise we will do more tests
	 if (($extension != "jpg")) 
			{
			//print error message
				//echo '<h1>Unknown extension!</h1>';
				$errors=1;
			}
			else
			{
	//get the size of the image in bytes
	 //$_FILES['image']['tmp_name'] is the temporary filename of the file
	 //in which the uploaded file was stored on the server
	 $size=filesize($_FILES['image']['tmp_name']);

	//compare the size with the maxim size we defined and print error if bigger
	if ($size > 1000*1024)
	{
		//echo '<h1>You have exceeded the size limit!</h1>';
		$errors=1;
	}

	//we will give an unique name, for example the time in unix time format
	$image_name=$userID.'.'.$extension;
	//the new name will be containing the full path where will be stored (images folder)
	$newname="userimages/".$image_name;
	//we verify if the image has been uploaded, and print error instead
	$copied = copy($_FILES['image']['tmp_name'], $newname);
	if (!$copied) 
	{
		//echo '<h1>Copy unsuccessfull!</h1>';
		$errors=1;
	}
	}}

	
 // get the info for this user
 $query_getData = "SELECT * FROM userdata WHERE userID=$userID";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");

	$userData = mysql_fetch_assoc($result_getData);
 
 $userID = stripslashes($userData['userID']);
 $userName = stripslashes($userData['nameFull']);
 $userEmail = stripslashes($userData['emailAddress']);
 $userContact = stripslashes($userData['contactInfo']);
 $userBirthMonth = $userData['birthMonth'];
 $userBirthDay = $userData['birthDay'];
 
 $order = array("\\r\\n", "\\n", "\\r", "\\");
 $userNews = str_replace($order, '', $userData['newsMonth']);
 $userStory = str_replace($order, '', $userData['newsStory']);
 
 //$userNews = $userData['newsMonth'];
 $userEvent1 = stripslashes($userData['event1']);
 $userEvent2 = stripslashes($userData['event2']);
 $userEvent3 = stripslashes($userData['event3']);
 $userHeadline = stripslashes($userData['newsHeadline']);
 //$userStory = stripslashes($userData['newsStory']);
 $userBlogName = stripslashes($userData['blogName']);
 $userBlogURL = stripslashes($userData['blogURL']);
 $userBlogDescription = stripslashes($userData['blogDescription']);
 $userMisc1 = stripslashes($userData['misc1']);
 $userImage = stripslashes($userData['misc2']);

echo "
<html>
  <title>My Profile</title>
  <head></head>
  
  <body bgcolor=#dde6c6>
  
  <style type='text/css'>
     table {
	       font-family:'Helvetica',serif;
		   font-size: 11;
	       }
    A:link {text-decoration: none; color: #645d41;}
	A:visited {text-decoration: none; color: #645d41;}
	A:active {text-decoration: none; color: #645d41;}
	A:hover {text-decoration: underline; color: #ddd6ba;}
  </style>  
  
  <form action='profile.php' enctype='multipart/form-data' method='post'>

  <table width=100% border=0 bgcolor=#dde6c6>
  <tr><td align='center'>

  <table width=660px border=0 bgcolor='white'>
  <tr>
  <td style='background-image: url(evan/images/G.jpg)' align='center'><br /><br />Newsletter - a simple monthly publication to keep friends connected<br /><br /></td>
  </tr>
  <tr><td align='center'><a href='publications.php'>[Archive]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='signup.php'>[Log out]</a><br /><br /></td></tr>
  </table><br />
  
  <table width=660px border=0 bgcolor='white'>
  
  <tr>
  <td colspan=3 align='center'><br />
  All fields are optional. To unsubscribe from the newsletter, erase your
  email address and save the form.";
  
  if (isset($_POST['saveTrue']))
	{ 
		//saveData();
		echo "<br /><br /><div style='color: red;'><strong>
		Your information has been submitted to the newspaper.<br />
		You may make further modifications using this page until the publication date.
		</strong></div></br />";
		
		if ($errors != 0)
		{
			echo "<br /><br /><div style='color: red;'><strong>
		Error submitting image. You image must be saved with a .jpg<br />
		extension and be less than 1 MB in size.
		</strong></div></br />";
		}
	}
  
  echo "
  </td>
  </tr>
  
  <!--
  <tr>
  <td colspan=2 align='right'> 
  <br /><input type='submit' value='      Save      '>
  </td>
  </tr>
  -->
  
  <tr>
    <td width=500px>&nbsp;</td>
    <td>
	
	<br />
	<strong>Your information</strong>
	<hr width=100%>
	
	
	Name<br />
	  <input type='input' name='postName' value='$userName' size=50>
	<br />
	
	Email<br />
	  <input type='input' size=50 name='postEmail' value='$userEmail'>* publications will be sent here
	<br />
	
	<!--Other contact information<br />-->
	  <input type='hidden' name='postContact' value='$userContact' size=50>
	 <br />
	 
	<br /> 
	Birthday&nbsp;&nbsp;
	  <select name='postBirthMonth'>";
	  
	if ($userBirthMonth == 1) { echo "<option value=1 selected='selected'>"; }
	else { echo "<option value=1>";}
	echo "January</option>";
	
	if ($userBirthMonth == 2) { echo "<option value=2 selected='selected'>"; }
	else { echo "<option value=2>";}
	echo "February</option>";
	
	if ($userBirthMonth == 3) { echo "<option value=3 selected='selected'>"; }
	else { echo "<option value=3>";}
	echo "March</option>";
	
	if ($userBirthMonth == 4) { echo "<option value=4 selected='selected'>"; }
	else { echo "<option value=4>";}
	echo "April</option>";
	
	if ($userBirthMonth == 5) { echo "<option value=5 selected='selected'>"; }
	else { echo "<option value=5>";}
	echo "May</option>";
	
	if ($userBirthMonth == 6) { echo "<option value=6 selected='selected'>"; }
	else { echo "<option value=6>";}
	echo "June</option>";
	
	if ($userBirthMonth == 7) { echo "<option value=7 selected='selected'>"; }
	else { echo "<option value=7>";}
	echo "July</option>";
	
	if ($userBirthMonth == 8) { echo "<option value=8 selected='selected'>"; }
	else { echo "<option value=8>";}
	echo "August</option>";
	
	if ($userBirthMonth == 9) { echo "<option  value=9 selected='selected'>"; }
	else { echo "<option value=9>";}
	echo "September</option>";
	
	if ($userBirthMonth == 10) { echo "<option value=10 selected='selected'>"; }
	else { echo "<option value=10>";}
	echo "October</option>";
	
	if ($userBirthMonth == 11) { echo "<option value=11 selected='selected'>"; }
	else { echo "<option value=11>";}
	echo "November</option>";
	
	if ($userBirthMonth == 12) { echo "<option value=12 selected='selected'>"; }
	else { echo "<option value=12>";}
	echo "December</option>";
	
	 
	echo "
	  </select>
	  
	  <input type='input' name='postBirthDay' value='$userBirthDay' size=3>
	<br /><br />
	
	<br />
	<strong>Your news</strong>
	<hr width=100%>
	
	Monthly update (<200 words please)<br />
	<i>Write a short paragraph or two about what you've been up to.</i><br />
	  <textarea cols=62 rows=10 name='postNews'>$userNews</textarea>
	<br /><br />
	
	Announcements & Events<br />
	<i>Any announcements / events you are hosting or planning to attend.</i><br />
	  1.<input type='input' name='postEvent1' value='$userEvent1' size=78><br />
	  2.<input type='input' name='postEvent2' value='$userEvent2' size=78><br />
	  3.<input type='input' name='postEvent3' value='$userEvent3' size=78>

	<br />
	
	<br /><br />
	<strong>Write a featured story</strong><br />
	<i>Write a long, blog-style post about anything. Featured stories
	   will be printed on the first page.</i> <!-- Optionally,
	   include images. To include images, first upload the image by pressing
	   the button. Then, at the desired location in the post, enter two pound
	   symbols followed by the image name followed by two pound symbols. The
	   image name is case sensitive. test.JPG is not the same as Test.jpg. 
	   <br />Example: ##image.jpg##</i><br /> -->
	<hr width=100%>
	
	Headline<br />
	  <input type='input' size=80 height=5 name='postHeadline' value='$userHeadline'>
	<br />
	
	Optional image: 
	<i>Upload the image by pressing the button. The image will be displayed
	at the beginning of your post. Only one image can be uploaded. Images
	MUST be saved in a .jpg format and be under 1 mb!
	   <br /></i>
	  <input type='file' name='image'> 
	  <input type='hidden' size=40 height=5 name='postMisc2' value='$userImage'>
	<br />
	
	Story (no size limit)<br />
	  <textarea cols=62 rows=14 name='postStory'>$userStory</textarea>
	<br />
	
	<br /><br />
	<strong>Do you have a personal blog?</strong>
	<hr width=100%>
	
	Blog name<br />
	  <input type='input' size=80 name='postBlogName' value='$userBlogName'>
	<br />
	
	Blog URL<br />
	  <input type='input' size=80 name='postBlogURL' value='$userBlogURL'>
	<br />
	
	Blog description (short)<br />
	  <input type='input' size=80 name='postBlogDescription' value='$userBlogDescription'>
	<br />
	
	<br />
	<strong>Misc</strong>
	<hr width=100%>
	
	Recommended books/music<br />
	  <input type='input' size=80 name='postMisc1' value='$userMisc1'>
	<br />
	
	<br />
			
    </td>
	<td width=500px>&nbsp;</td>
  </tr>
  
  <tr>
  <td colspan=2 align='right'>
  <input type='hidden' name='userID' value='$userID'>
  <input type='submit' name='saveTrue' value='      Save      '><br /><br /><br />
  </td>
  </tr>
  
  </table>
  
  </td></tr>
  </table>
  
  </form>

  </body>

</html>";

?>