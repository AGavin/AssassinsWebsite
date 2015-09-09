<?php function page_contents() {
    global $users,$teams,$publickey,$error;

    if (is_logged_in() && $_POST['action']=="game_report") {
	list($junk,$victim)=explode(':',$_POST['victim']);
	list($junk,$victor)=explode(':',$_POST['victor']);
	if(isset($victim)) {
	    if($victim==$_SESSION['uid']) { $confirm_victim='true'; } else { $confirm_victim='false'; }
	    $victim_text=$users[$victim]['name']." (AKA ".$users[$victim]['alias'].")";
	} else {
	    $confirm_victim='null';
	    $victim_text="an unknown individual";
	}
	if(isset($victor)) {
	    if($victor==$_SESSION['uid']) { $confirm_victor='true'; } else { $confirm_victor='false'; }
	    $victor_text=$users[$victor]['name']." (AKA ".$users[$victor]['alias'].")";
	} else {
	    $confirm_victor='null';
	    $victor_text="an unknown individual";
	}
	$timestamp=date("Y-m-d H:i:s",mktime($_POST['when_hour'],$_POST['when_minute'],0,$_POST['when_month'],$_POST['when_day'],$_POST['when_year']));

    	include("src/connect.php");
        $sql="INSERT INTO activity (reporter,timestamp,victor,victim,comment,contested,confirmed_victor,confirmed_victim)";
	$sql.=" values ('".$_SESSION['uid']."','$timestamp','$victor','$victim','".$_POST['comment']."',false,$confirm_victor,$confirm_victim)";
        $result=mysql_query($sql,$link) or die("user_admin:9: ".mysql_error());

	if($confirm_victor=='false') {
            $to=$users[$victor]['email'];
            $from='Oxford Guild of Assassins <webmaster@oxfordassassinsguild.org.uk>';
            $subject='A kill requires your confirmation';
            $comment=$users[$_SESSION['uid']]['name']."(AKA ".$users[$_SESSION['uid']]['alias'].") has reported that you carried out the killing of $victim_text.\n\nThe following commentary was supplied:\n\"".$_POST['comment']."\"\n\nPlease visit http://oxfordassassinsguild.org.uk/report.php to confirm or contest kill.\n\nThe IT Daemon.\n";
            $headers="From: $from\r\n" .
                "BCc: $from\r\n" .
             	'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $comment, $headers);
	}

	if($confirm_victim=='false') {
            $to=$users[$victim]['email'];
            $from='Oxford Guild of Assassins <webmaster@oxfordassassinsguild.org.uk>';
            $subject='A kill requires your confirmation';
            $comment=$users[$_SESSION['uid']]['name']."(AKA ".$users[$_SESSION['uid']]['alias'].") has reported that you were killed by $victor_text.\n\nThe following commentary was supplied:\n\"".$_POST['comment']."\"\n\nPlease visit http://oxfordassassinsguild.org.uk/report.php to confirm or contest kill.\n\nThe IT Daemon.\n";
            $headers="From: $from\r\n" .
                "BCc: $from\r\n" .
             	'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $comment, $headers);
	}
    }

    if (is_logged_in() && $_GET['action']=="victim_confirm") {
        $getid=intval($_GET['id']);
        if($getid>0) {
            include("src/connect.php");
            $sql="update activity set confirmed_victim=true where id=".$getid;
            $result=mysql_query($sql,$link) or die("victim:1: ".mysql_error());
        }
	print "<p>Your death has been confirmed. You may take no further part in the game until you are resurrected (see game rules for details)\n";
    }

    if (is_logged_in() && $_GET['action']=="victor_confirm") {
        $getid=intval($_GET['id']);
        if($getid>0) {
            include("src/connect.php");
            $sql="update activity set confirmed_victor=true where id=".$getid;
            $result=mysql_query($sql,$link) or die("victor:1: ".mysql_error());
        }
	print "<p>Your kill has been confirmed. Happy Hunting...\n";
    }

    if (is_logged_in() && $_GET['action']=="contest") {
        $getid=intval($_GET['id']);
        if($getid>0) {
            include("src/connect.php");
            $sql="update activity set contested=true where id=".$getid;
            $result=mysql_query($sql,$link) or die("contest:1: ".mysql_error());
        }
	print "<p>Your wish to contest the kill has been recorded. The Umpire will be in touch shortly to resolve.\n";
    }

    include("src/connect.php");
    $sql="SELECT * FROM activity";
    $result=mysql_query($sql,$link) or die("report:6: ".mysql_error());

    while ($line = mysql_fetch_array($result)) {
	if($line['contested']) {
	    $contested[]=$line;
	} else {
	    if (!$line['confirmed_victim']) {
		$uc_victim[$line['victim']][]=$line;
	    }
	    if (!$line['confirmed_victor']) {
		$uc_victor[$line['victor']][]=$line;
	    }
	}
    }

	foreach ($users as $player) {
	    $who_to_email['pn:'.$player['id']]=$player['name'].' (AKA '.$player['alias'].')';
	    $who_to_email['pa:'.$player['id']]=$player['alias'].' (AKA '.$player['name'].')';
	}
    asort($who_to_email);

    switch(member_type()) {
    case 'umpire': ?>
<p>Welcome Umpire... 

<p>The following reports are being contested:
<br>
<?php
	foreach ($contested as $c) {
	    print "<br>".$users[$c['reporter']]['name']."(AKA ".$users[$c['reporter']]['alias'].")'s report that ".$users[$c['victor']]['name']."(AKA ".$users[$c['victor']]['alias'].") killed ".$users[$c['victim']]['name']."(AKA ".$users[$c['victim']]['alias'].") at ".$c['timestamp'];
	}
	break;
    case 'playing': ?>

<h1>The Oxford Guild of Assassins</h1>

<p>You have the following unconfirmed kills:
<table width="100%" border=1>
<tr><td>Date and time</td><td>Victim</td><td>Kill report</td><td>&nbsp;</td></tr>
<?php
	foreach ($uc_victor[$_SESSION['uid']] as $c) {
	    print "<tr><td>".$c['timestamp']."</td><td>".$users[$c['victim']]['name']."(AKA ".$users[$c['victim']]['alias'].")</td><td>".$c['comment']."(As reported by ".$users[$c['reporter']]['name']."(AKA ".$users[$c['reporter']]['alias']."))</td>";
	    print '<td><a href="?action=victor_confirm&id='.$c['id'].'">Confirm</a><br><a href="?action=contest&id='.$c['id'].'">Contest</a></td></tr>';
	}
?>
</table>

<p>You have the following unconfirmed deaths:
<table width="100%" border=1>
<tr><td>Date and time</td><td>Victor</td><td>Kill report</td><td>&nbsp;</td></tr>
<?php
	foreach ($uc_victim[$_SESSION['uid']] as $c) {
	    print "<tr><td>".$c['timestamp']."</td><td>".$users[$c['victor']]['name']."(AKA ".$users[$c['victor']]['alias'].")</td><td>".$c['comment']."(As reported by ".$users[$c['reporter']]['name']."(AKA ".$users[$c['reporter']]['alias']."))</td>";
	    print '<td><a href="?action=victim_confirm&id='.$c['id'].'">Confirm</a><br><a href="?action=contest&id='.$c['id'].'">Contest</a></td></tr>';
	}
?>
</table>

<p><p>Use this form to make a game report:
<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="game_report">
<br>Victor: <?php selector('victor',$who_to_email,'pn:'.$_SESSION['uid']); ?> 
<br>Victim: <?php selector('victim',$who_to_email,''); ?>
<br>When did this happen? <?php dropdown_date('when'); ?> 
<br>Comment:<br><textarea name="comment" cols=60 rows=10></textarea>
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
