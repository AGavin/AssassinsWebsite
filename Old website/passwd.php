<?php function page_contents() {
	global $aclev,$user_level,$users,$ingame,$recaptcha_success,$privatekey,$publickey;
	include("src/connect.php");
	if (isset($_GET['action'])) {
		$action=$_GET['action'];
	} else {
		$action=$_POST['action'];
	}

	if(isset($_GET['r'])) { $action="do_retrieve2"; }
	switch ($action) {
      case 'retrieve': ?>
              <p>Enter your E-mail address below <span class="small">. You will be sent an E-mail with further instructions
              Don't forget to check your spam folder for the E-mail. Especially if you use GMail...

              <form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
              <table><input type="hidden" name="action" value="do_retrieve">
              <tr><td>E-mail address:</td><td><input size=50 type="text" name="user_name" value="<?php print $_GET['username']; ?>"></td></tr>
              </table>
              <input type="submit" value="Retrieve">
              </form><?php
              break;
      case 'do_change':
         if (is_member()) {
              $oldpw_md5=md5($_POST['oldpw']);
              $newpw_md5=md5($_POST['newpw']);
              if ($_POST['newpw'] != $_POST['newpw2']) {
                  print '<p class="hilight">Passwords do not match. Please try again.';
              } elseif(strlen($_POST['newpw'])<6) {
                  print '<p class="hilight">New password is too short, must be 6 letters or more';
              } elseif($oldpw_md5==$newpw_md5) {
                  print '<p class="hilight">Password hasn\'t changed! Please try again.';
              } else {
                  include("src/connect.php");
                  $sql="SELECT * FROM players WHERE id=".$_SESSION['uid'];
                  $result=mysql_query($sql,$link) or die($mysql_error());
                  $resArray = mysql_fetch_array($result);
                  if ($oldpw_md5==$resArray['password']) {
                      $sql="UPDATE players SET password='$newpw_md5' WHERE id=".$_SESSION['uid'];
                      $result=mysql_query($sql,$link) or die($mysql_error());
                      print '<p class="hilight">Password successfully updated.';
                      break;
                  } else {
                      print '<p class="hilight">Old password incorrect. Please try again.';
                  }
              }
          }
      case 'change':
              if (is_member()) {?>
              <p>Enter your old password and your new password twice. New passwords must match and be at least 6 characters.
              <form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
              <table>
              <table><input type="hidden" name="action" value="do_change">
              <tr><td>Old Password:</td><td><input size=8 type="password" name="oldpw"></td></tr>
              <tr><td>New Password:</td><td><input size=8 type="password" name="newpw"></td></tr>
              <tr><td>Retype New Password:</td><td><input size=8 type="password" name="newpw2"></td></tr>
              </table>
              <input type="submit" value="Change Password">
              </form><?php
              } else { ?>
                      <ul>
                      <li><a href="?action=retrieve">Retrieve lost password</a>
                      <li><a href="?action=newuser">Register New User</a>
                      </ul><?php
              }
              break;
      case 'do_details':
         if (is_member()) {
	      $uid=$_SESSION['uid'];

              $sql="SELECT * FROM players WHERE id!=$uid and email='".$_POST['email']."'";
              $result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());

              if ($_POST['email']=="") { ?>
                      <p>No E-mail address supplied!<?php
              } elseif (mysql_num_rows($result)>0) { ?>
                      <p>Someone (other than you) with that E-mail address has already registered...<?php
              } else {
		      print "<p>Making the following changes:\n<ul>\n";
		      $sql="";
		      foreach(array('alias','name','email','address','college','alergies') as $v) {
			if($_POST[$v]!=str_replace("'","\'",$users[$uid][$v])) {
			    print "<li>Changing $v from &quot;".$users[$uid][$v]."&quot; to &quot;".$_POST[$v]."&quot;...\n";
			    $sql.="$v='".$_POST[$v]."',";
			}
		      }
		      if($sql=="") {
			print "<li>Ooops - don't appear to be changing anything...\n</ul>\n";
		      } else {
                      	$sql="update players set ".substr($sql,0,-1)." where id=$uid";
                      	$result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());

		      	print "</ul>\n<p>Updated sucessfully.";
			break;
		      }
              }
          }
      case 'details':
              if (is_member()) {
	      $uid=$_SESSION['uid']; ?>
              <p>Use the form below to update your details:
              <form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
		<table><input type="hidden" name="action" value="do_details">
		<tr><td>E-mail address:</td><td><input size=30 type="text" name="email" value="<?php print $users[$uid]['email']; ?>"></td><td>Your E-mail address is used to log in and to keep you updated during the game...</td></tr>
		<tr><td>Name:</td><td><input size=30 type="text" name="name" value="<?php print $users[$uid]['name']; ?>"></td><td>Your actual real name</td></tr>
		<tr><td>Alias:</td><td><input size=30 type="text" name="alias" value="<?php print $users[$uid]['alias']; ?>"></td><td>Whatever you wish to be called...</td></tr>
		<tr><td>College / Department:</td><td><input size=30 type="text" name="college" value="<?php print $users[$uid]['college']; ?>"></td><td>Please enter your College (or Department if not affiliated to a College)</td></tr>
		<tr><td>Address:</td><td><input size=30 type="text" name="address" value="<?php print $users[$uid]['address']; ?>"></td><td>The place where you live in Oxford (eg room number, staircase, building etc). We need this so people can find you to inhume you. False information will be penalised...</td></tr>
		<tr><td>Allergies:</td><td><input size=30 type="text" name="alergies" value="<?php print $users[$uid]['alergies']; ?>"></td><td>Because we don't really want to kill you, please let us know anything you are allergic to...</td></tr>
		</table>
		<input type="submit" value="Update">
              </form><?php
              } else { ?>
                      <ul>
                      <li><a href="?action=retrieve">Retrieve lost password</a>
                      <li><a href="?action=newuser">Register New User</a>
                      </ul><?php
              }
              break;
      case 'do_retrieve3':
              $newpw_md5=md5($_POST['newpw']);
              if ($_POST['newpw'] != $_POST['newpw2']) {
                  print '<p class="hilight">Passwords do not match. Please try again.';
              } elseif(strlen($_POST['newpw'])<6) {
                  print '<p class="hilight">New password is too short, must be 6 letters or more';
              } else {
                  include("src/connect.php");
                  $sql="SELECT * FROM pw_ret WHERE part1='".$_POST['r']."'";
                  $result=mysql_query($sql,$link) or die($mysql_error());
                  $resArray = mysql_fetch_array($result);
                  if ($_POST['oldpw']==$resArray['part2']) {
                      $sql="UPDATE players SET password='$newpw_md5' WHERE id=".$resArray['uid'];
                      $result=mysql_query($sql,$link) or die($mysql_error());
                      print '<p class="hilight">Password successfully updated.';
                      $sql="delete from pw_ret where uid=".$resArray['uid'];
                      $result=mysql_query($sql,$link) or die($mysql_error());
                      break;
                  } else {
                      print '<p class="hilight">Either the 8-digit number you typed was wrong or the link E-mailed was not correctly copied... Try again.';
                  }
              }
      case 'do_retrieve2': ?>
              <p>Enter the eight digit number from the screen and your new password twice. New passwords must match and be at least 6 characters.
              <form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
              <table>
              <table><input type="hidden" name="action" value="do_retrieve3">
              <table><input type="hidden" name="r" value="<?php print $_GET['r']; ?>">
              <tr><td>8-digit number:</td><td><input size=8 type="password" name="oldpw"></td></tr>
              <tr><td>New Password:</td><td><input size=8 type="password" name="newpw"></td></tr>
              <tr><td>Retype New Password:</td><td><input size=8 type="password" name="newpw2"></td></tr>
              </table>
              <input type="submit" value="Change Password">
              </form><?php
              break;
      case 'do_retrieve':
              $code_part_2=mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
              $sql="SELECT * FROM players WHERE email='".$_POST['user_name']."'";
              $result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());

              if (mysql_num_rows($result)>0) {
                      $ret_user = mysql_fetch_array($result);
                      $code_part_1=chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96).
				   chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96).
				   chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96).
				   chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96);
		      $uid=$ret_user['id'];
	              $sql="insert into pw_ret (uid,part1,part2) values ($uid,'$code_part_1','$code_part_2')";
              	      $result=mysql_query($sql,$link) or die("user_admin:10: ".mysql_error());
                      $name=$ret_user['name'];
                      $to=$ret_user['email'];
                              $from='Oxford Guild of Assassins Password Retrieval <webmaster@oxfordassassinsguild.org.uk>';
                              $subject='Oxford Guild of Assassins password retrieval for '.$ret_user['username'];
                      $comment="Please visit http://oxfordassassinsguild.org.uk/game/passwd.php?r=$code_part_1 to reset your password. You will need the 8 digit number from the webpage.\n";
                      $headers="From: $from\r\n" .
                               'X-Mailer: PHP/' . phpversion();

                              mail($to, $subject, $comment, $headers); ?>
                      <p>Your request has been processed. You need to take a note of this 8-digit number: <?php print $code_part_2;
              } else { ?>
                      <p>Your request has been processed. You need to take a note of this 8-digit number: <?php print $code_part_2;
              }
              break;
        case 'do_newuser':

              $sql="SELECT * FROM players WHERE email='".$_POST['email']."'";
              $result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());

              if ($_POST['email']=="") { ?>
                      <p>No E-mail address supplied!<?php
              } elseif (mysql_num_rows($result)>0) { ?>
                      <p>Someone with that E-mail address has already registered... <a href="?action=retrieve">Forgotten your password?</a><?php
              } elseif($recaptcha_success && $_POST['email-button']=="Join Us...") {
        		$to='oga-guild-join@lists.oxfordassassinsguild.org.uk';
        		$from='Added from Website '.date("Y-m-d").' <'.$_POST['email'].'>';
        		$subject='OGA subscription from website';
        		$comment='Subscribe';
        		$headers="From: $from\r\n" .
                		 'X-Mailer: PHP/' . phpversion();

	        	mail($to, $subject, $comment, $headers);

                      $name=$_POST['name'];
                      $new_pw=chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96).chr(mt_rand(1,26)+64).chr(mt_rand(1,26)+96).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
                      $new_pw_md5=md5($new_pw);
                      $email=$_POST['email'];

                      $sql="INSERT INTO players (alias,email,college,address,alergies,name,password) values ('".$_POST['alias']."','$email','".$_POST['college']."','".$_POST['address']."','".$_POST['allergies']."','$name','$new_pw_md5')";
                      $result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());
//                    print $sql;

                      $to=$email;
                      $from='Oxford Guild of Assassins <webmaster@oxfordassassinsguild.org.uk>';
                      $subject='New Oxford Guild of Assassins player ID assigned - '.$_POST['alias'];
                      $comment="Your player ID has been assigned.\n\nusername: $email\npassword: $new_pw\n\nThe IT Daemon\nPlease do not reply to this message.\n";
                      $headers="From: $from\r\n" .
                               "BCc: $from\r\n" .
                               'X-Mailer: PHP/' . phpversion();

                              mail($to, $subject, $comment, $headers);
//                            mail($from, $subject, $comment, $headers); 
		      if(!$ingame) {
                      	print "<p>Your account has been created and the password E-mailed to you. Be sure to check your spam folder for the mail!";
                      	print "<p>You have also been subscribed to the mailing list. You'll received an E-mail from the Mail Daemon that manages the list presently...";
                      	print "<p>The IT Daemon reserves the right to remove your account at any point ey sees fit!\n";
		      } else {
		      	print "<p>Registration for the current game has now closed.";
                      	print "<p>You have also been subscribed to the mailing list. You'll received an E-mail from the Mail Daemon that manages the list presently...";
                      	print "<p>However, your account has been created and the password E-mailed to you. Be sure to check your spam folder for the mail!";
                      	print "<p>The IT Daemon reserves the right to remove your account at any point ey sees fit!\n";
		      }
              	      break;
              } else { ?>
                      <p>Recaptcha was incorrect...<?php
	      }
	case 'newuser':?>
		<p>Enter your details below. This will subscribe you to the mailing list and register you an account. Details of your account will be E-mailed to you. 

		<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="fullname" value="Added on Website <?php print date("Y-m-d"); ?>">
		<table><input type="hidden" name="action" value="do_newuser">
		<input type="hidden" name="digest" value="0">
		<tr><td>E-mail address:</td><td><input size=30 type="text" name="email"></td><td>Your E-mail address is used to log in and to keep you updated during the game...</td></tr>
		<tr><td>Name:</td><td><input size=30 type="text" name="name"></td><td>Your actual real name</td></tr>
		<tr><td>Alias:</td><td><input size=30 type="text" name="alias"></td><td>Whatever you wish to be called...</td></tr>
		<tr><td>College / Department:</td><td><input size=30 type="text" name="college"></td><td>Please enter your College (or Department if not affiliated to a College)</td></tr>
		<tr><td>Address:</td><td><input size=30 type="text" name="address"></td><td>The place where you live in Oxford (eg room number, staircase, building etc). We need this so people can find you to inhume you. False information will be penalised...</td></tr>
		<tr><td>Allergies:</td><td><input size=30 type="text" name="allergies"></td><td>Because we don't really want to kill you, please let us know anything you are allergic to...</td></tr>
		</table>
		<?php echo recaptcha_get_html($publickey, $error); ?>
    		<input type="submit" name="email-button" value="Join Us..." />
		</form><?php
		break;
	default: 
		if (is_member()) { ?>
			<ul>
			<li><a href="?action=change">Change password</a>
			<li><a href="index.php">Go back to the main game page</a>
			</ul><?php 
		} else { ?>
			<ul>
			<li><a href="?action=retrieve">Retrieve lost password</a>
			<li><a href="?action=newuser">Register New User</a>
			<li><a href="index.php">Go back to the main game page</a>
			</ul><?php
		}
	}
}

include("src/main.php");
?>

