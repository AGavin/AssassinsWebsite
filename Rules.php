<?php function page_contents() {
?>
    <div class="container">
      <div class="panel">These are the most general rules of the game. See here for any rules specific
        to the current game</div>
      <!-- add in hyperlink on here when that page is
      created (or have the open accordian thing be the current special rules?)-->
      <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">What is Assassins?</a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">‘Assassins’ is a game of mock killing. It is fun. We do ask, however,
              that for the duration of the game, you live and spend a large proportion
              of your time within 6 miles of Carfax tower and in an environment compatible
              with the game (so not, for example, a prison). Once you have read these
              rules, if you have any questions about them, email the Umpire at umpire@oxfordassassinsguild.org.uk
              - who will be more than happy to help.</div>
          </div>
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo">Killing</a>
            </h4>
          </div>
          <div id="collapsetwo" class="panel-collapse collapse">
            <div class="panel-body">“Killing” is using a weapon allowed in the game (see below) upon another
              person. You should avoid doing this to innocents (people who are not playing
              the game). Both the killer and killed should agree that a kill has taken
              place, and send separate kill/death reports to the Umpire. Don’t insist
              you’re alive when you’re patently not – it’s bad form.</div>
          </div>
          <!-- <div class="panel-heading">
          <h4 class="panel-title text-center">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
            href="#NAME">TITLE</a>
          </h4>
        </div>
        <div id="NAME" class="panel-collapse collapse">
          <div class="panel-body">TEXT</div>
        </div>
		--></div>
      </div>
    </div>
	
<?php }
}
include("src/main.php");
?>