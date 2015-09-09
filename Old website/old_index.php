<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>The Oxford Guild of Assassins</title>
  <link href="oga.css" rel="stylesheet" type="text/css">
</head>
<body>
<a name="top"></a>

<h1>The Oxford Guild of Assassins</h1>

<?php
require_once('../recaptcha/recaptchalib.php');

$publickey = "6LdyL9cSAAAAAE8_cRqnrnCRG9005qzgX62jJ1zS";
$privatekey = "6LdyL9cSAAAAAI4scv58ntnHKu1uefe8cgR9QsZf";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

$success=false;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
		$success=true;
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }

}

if($success) { 
	$to='oga-guild-join@lists.oxfordassassinsguild.org.uk';
	$from='Added from Website '.date("Y-m-d").' <'.$_POST['email'].'>';
	$subject='OGA subscription from website';
	$comment='Subscribe';
	$headers="From: $from\r\n" .
	         'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $comment, $headers);
?>
<p>You have been subscribed. A confirmation E-mail has been sent to your E-mail address. Subscription will not be completed until you follow the instructions in the E-mail!!

<?php } else { ?>

<p> Ever thought that life at Oxford would be so much more fun if people were trying to kill you? 
<br> Ever wondered if your post might be full of attack animals, ready to rip you to shreds? 
<br> Ever wanted to have a gun battle at 2AM in somebody else's college?
<p> Then the Oxford Guild of Assassins is for you...

<p>If you would like to find out more, fill in your E-mail address here, complete the recaptcha and you will be subscribed to the mailing list...


<form action="" method="post">
<input type="hidden" name="fullname" value="Added on Website <?php print date("Y-m-d"); ?>">
<input type="hidden" name="digest" value="0">
<p>E-mail address <input type="text" name="email" size=30 />
<?php echo recaptcha_get_html($publickey, $error); ?>
    <br/>
    <input type="submit" name="email-button" value="Join Us..." />
    </form>
<?php } ?>
  </body>
</html>
