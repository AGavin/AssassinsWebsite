<?php function page_contents() {
    global $users,$teams,$publickey,$error,$ingame,$userid;


    if (is_logged_in() && $_POST['action']=="disclaimer_process") {
	$getid=intval($userid);
	    include("src/connect.php");
	    $sql="update players set disclaimer='".date("Y-m-d H:i:s")."' where id=".$getid;
	    $result=mysql_query($sql,$link) or die("secretary:1: ".mysql_error());
    }

    switch(member_type()) {
    case 'umpire': ?>
<p>Welcome Umpire... 
<p>The Rules can be found by <a href="rules.docx">following this link</a>. E-mail any updates to pilly...
<p>The teams are listed below. You can change which team a person is on by changing what is written in the right hand column and then clicking the "update" button below.
<form action="" method="POST">
<input type="hidden" name="action" value="team_update">
<?php
	foreach ($teams as $team => $players) {
	    print "<h2>$team</h2>\n<p>Targets (a comma separated list...): <input type=\"text\" name=\"targets_".$players[0]['id']."\" size=20 value=\"".$players[0]['target']."\"/><table width=\"100%\" border=1>\n";
	    print "<tr><td>Name</td><td>Address</td><td>College</td><td>Allergies</td><td>&nbsp;</td></tr>\n";
	    foreach ($players as $player) {
	    	print "<tr><td>".$player['name']." (AKA ".$player['alias'].")</td><td>".$player['address']."</td><td>".$player['college']."</td><td>".$player['alergies']."</td><td><input type=\"text\" name=\"team_".$player['id']."\" size=10 value=\"$team\"/></td></tr>\n";
	    }
	    print "</table>";
	}
	print "<input type=\"submit\" value=\"Update\"></form>";
	break;
    case 'unregistered': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering. You appear not to have accepted a disclaimer this year. Please read the following:

<h2>Disclaimer</h2>
<p>The Oxford Guild of Assassins neither commits nor encourages the
inhuming of people in reality! Our assassination games are for
amusement only and are good-willed events. Participants must take
great care not to harm themselves or others, and furthermore not to
damage property or disrupt public, University, or college business.
<br>Even so, there is a risk of accidental injury occurring during
participation in Guild games. This is unlikely, but by joining the Guild
you agree that these injuries may occur, and that you will not hold
the Guild responsible. At all times, you are solely responsible for your
actions.
<br>If you genuinely have a desire to kill people, the Guild is not for you
and suggests you seek psychiatric help. Post-haste.

<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
<p><input type="checkbox" name="action" value="disclaimer_process"> Please tick this box to indicate that you, <?php

        print $users[$userid]['name']." (AKA ".$users[$userid]['alias'].") of ".$users[$userid]['college'].", ";

?> accept the above disclaimer and that you participate in all Guild activities at your own risk.
<br><input type="submit" value="I accept the disclaimer">
</form>

<?php break;
    case 'noteam': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering and returning your disclaimer. You have not yet been assigned a team... If the game should have started and you get this message, please E-mail <a href="mailto:umpire@oxfordassassinsguild.org.uk">The Umpire</a>

<p>Please <a href="rules.docx">read the rules by following this link</a>.

<?php   break;
    case 'playing': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>Thank you for registering and returning your disclaimer.

<p>Please <a href="rules.docx">read the rules by following this link</a>.

<p>Further details about the game can be found above...

<?php
	break;
    case 'nc': 
	print "<h1>The Oxford Guild of Assassins</h1>\n";

	if($ingame) {
	    print "<p>You have not registered to play the current game. Hope you are having a happy and safe time without having to watch over your shoulder all the time. Be sure to join us again soon...\n";
	} else { ?>
<form action="" method="post">
<input type="hidden" name="action" value="gameregister">
<input type="submit" value="Click here to register for the next game..." />
</form>

<?php	}
	break;
    default: ?>

<h1>The Oxford Guild of Assassins</h1>

<p> Ever thought that life at Oxford would be so much more fun if people were trying to kill you? 
<br> Ever wondered if your post might be full of attack animals, ready to rip you to shreds? 
<br> Ever wanted to have a gun battle at 2AM in somebody else's college?
<p> Then the Oxford Guild of Assassins is for you...

<p><a href="Termcard.pdf">Click here for this term's Termcard...</a>

<p>If you are already a member, login above to join in games.

<p>If you would like to register, please <a href="passwd.php?action=newuser">register here</a>

<?php }
}
include("src/main.php");
?>
