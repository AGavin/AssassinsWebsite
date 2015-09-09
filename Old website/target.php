<?php function page_contents() {
    global $users,$teams,$publickey,$error;

    switch(member_type()) {
    case 'umpire': ?>
<p>Welcome Umpire... 
<p>The teams are as follows:

<?php
	foreach ($teams as $team => $players) {
	    print "<h2>$team</h2>\n<table width=\"100%\" border=1>\n";
	    print "<tr><td>Name</td><td>Address</td><td>College</td><td>Allergies</td></tr>\n";
	    foreach ($players as $player) {
	    	print "<tr><td>".$player['name']." (AKA ".$player['alias'].")</td><td>".$player['address']."</td><td>".$player['college']."</td><td>".$player['alergies']."</td></tr>\n";
	    }
	    print "</table>\n";
	}

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

<?php	break;
    case 'playing': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering and returning your disclaimer.

<p>Please <a href="rules.docx">read the rules by following this link</a>.

<p>Your targets are as follows:

<?php
	if(isset($users[$_SESSION['uid']]['target'])) { $targets=explode(",",$users[$_SESSION['uid']]['target']); }
	foreach ($teams as $team => $players) {
	  if($team!="Umpire" && $team!=$users[$_SESSION['uid']]['team'] && (!isset($targets) || in_array($team,$targets))) {
	    print "<h2>$team</h2>\n<table width=\"100%\" border=1>\n";
	    print "<tr><td>Name</td><td>Address</td><td>College</td><td>Allergies</td></tr>\n";
	    foreach ($players as $player) {
	    	print "<tr><td>".$player['name']." (AKA ".$player['alias'].")</td><td>".$player['address']."</td><td>".$player['college']."</td><td>".$player['alergies']."</td></tr>\n";
	    }
	    print "</table>\n";
	  }
	} ?>

<p><p>To contact any other team, E-mail <a href="mailto:umpire@oxfordassassinsguild.org.uk">The Umpire</a> 
<?php
	break;
    case 'nc': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>You have not registered to play the current game. Hope you are having a happy and safe time without having to watch over your shoulder all the time. Be sure to join us again soon...
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
