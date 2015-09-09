<?php function page_contents() {
    global $users,$teams,$publickey,$error;

    if (is_logged_in() && $_POST['action']=="email_whole_team") {
        $subject="[Assassins Game] ".$_POST['subject'];
        $comment=$_POST['email_text'];
        $headers='From: '.$users[$_SESSION['uid']]['alias'].' <'.$users[$_SESSION['uid']]['email'].'>'. "\r\n" .
                 'X-Mailer: PHP/' . phpversion();
 
        mail('umpire@oxfordassassinsguild.org.uk', $subject, $comment, $headers);

	foreach ($teams as $team => $players) {
	    if($team==$users[$_SESSION['uid']]['team']) {
		foreach ($players as $player) {
		    $to=$player['email'];
        	    mail($to, $subject, $comment, $headers);
		}
	    }
	}
    }

    if ($users[$_SESSION['uid']]['team']=="Umpire" && $_POST['action']=="email_from_umpire") {
	list($email_type,$email_to)=explode(':',$_POST['who_to_email']);
        $subject="[Assassins Game] ".$_POST['subject'];
        $comment=$_POST['email_text'];
        $headers='From: The Umpire <umpire@oxfordassassinsguild.org.uk>'. "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

	foreach ($teams as $team => $players) {
	     foreach ($players as $player) {
		$to=$player['email'];
		if(($email_type=='e') || ($email_type=='t' && $email_to==$team) || ($email_type=='pa' && $email_to==$player['id']) || ($email_type=='pn' && $email_to==$player['id'])) {
        	    mail($to, $subject, $comment, $headers);
		}
	     }
	}
    }

    foreach ($teams as $team => $players) {
	foreach ($players as $player) {
	    $who_to_email['pn:'.$player['id']]=$player['name'].' (AKA '.$player['alias'].')';
	    $who_to_email['pa:'.$player['id']]=$player['alias'].' (AKA '.$player['name'].')';
	}
    }
    arsort($who_to_email);
    $team_names=array_keys($teams);
    rsort($team_names);
    foreach ($team_names as $t) {
	$who_to_email['t:'.$t]="TEAM $t";
    }
    $who_to_email['e:e']="Everyone";
    $who_to_email=array_reverse($who_to_email);

    switch(member_type()) {
    case 'umpire': ?>
<p>Welcome Umpire... 

<p>E-mail people taking part in the game:
<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="email_from_umpire">
<br>Who do you want to E-mail? <?php selector('who_to_email',$who_to_email,'e:e'); ?>
<br>Subject: <input size=60 type="text" name="subject" value="A message from the Umpire">
<br>Text:<br><textarea name="email_text" cols=60 rows=10></textarea>
<br><input type="submit" value="Send"></form>

<?php
	break;
    case 'unregistered': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering. We have not yet received your disclaimer. Until this is received, your plays in the game may not count!
<p>You can download a <a href="disclaimer.pdf">disclaimer form here</a>.
<p>If you think you have supplied a disclaimer within the last 12 months and since 1st October 2012, please contact the <a href="mailto:secretary@oxfordassassinsguild.org.uk">Guild Secretary</a>.

<hr>
<?php
    break;
    case 'noteam': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering and returning your disclaimer. You have not yet been assigned a team... If the game should have started and you get this message, please E-mail <a href="mailto:umpire@oxfordassassinsguild.org.uk">The Umpire</a>

<p>Please <a href="rules.docx">read the rules by following this link</a>.

<?php   break;
    case 'playing': ?>

<h1>The Oxford Guild of Assassins</h1>

<p><p>E-mail your team:
<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="email_whole_team">
<br>Subject: <input size=60 type="text" name="subject" value="A message from <?php print $users[$_SESSION['uid']]['alias']; ?>">
<br>Text:<br><textarea name="email_text" cols=60 rows=10></textarea>
<br><input type="submit" value="Send"></form>

<p><p>To contact any other team, E-mail <a href="mailto:umpire@oxfordassassinsguild.org.uk">The Umpire</a> 
<?php
	break;
    case 'nc': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>You have not registered to play the current game. Hope you are having a happy and safe time without havign to watch over your shoulder all the time. Be sure to join us again soon...
<?php
	break;
    default: ?>

<h1>The Oxford Guild of Assassins</h1>

<p> Ever thought that life at Oxford would be so much more fun if people were trying to kill you? 
<br> Ever wondered if your post might be full of attack animals, ready to rip you to shreds? 
<br> Ever wanted to have a gun battle at 2AM in somebody else's college?
<p> Then the Oxford Guild of Assassins is for you...

<p>If you are already a member, login above to join in games (you will need to register if you have not done so already).

<p>If you would like to register, please <a href="passwd.php?action=newuser">register here</a>
<?php 
    }
}
include("src/main.php");
?>
