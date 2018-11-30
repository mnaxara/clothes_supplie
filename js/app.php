<script type="text/javascript">let $background = [];</script>
<?php

require_once ('includes/connect.php');

$backgroundSearch = $connexion->query("
	
	SELECT name FROM imgheader WHERE active = 1

	");

$backgrounds = $backgroundSearch->fetchAll();

foreach ($backgrounds as $background) {
	?><script type="text/javascript">
		$background.push('url(' + <?=$background['name']?>+')');

	</script>
}

?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript">
$(function(){ // Encapsulage
 
	// CHANGEMENT DU BG DU HEADER

	<?php?>

	

	let backActuel = 0; // Declaration d'une variable qui stockera le fond d'ecran actuel (index)


	let changeBackground = () =>{
	    if (backActuel == $background.length-1){// Si le fond d'ecar actuel est le dernier, on reviens au premier fond sinon on incremente de 1 l'index a utiliser comme fond
	        backActuel = 0;
	    }else {backActuel++;

	        }
	    $('header').css("background-image", $background[backActuel]);

	};

	setInterval(changeBackground, 1000);

	$('#burger').click(() =>{

	$('#menuBurger').toggle();

	})

	$('#menuBurger').on('click', $('#connect'), (e) =>{

	$('#connectDiv').toggle();

	$('#connectDiv').css('top', 20);
	
	console.log($menuHeight/2);

	})

})

</script>