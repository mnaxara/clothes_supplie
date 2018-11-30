<script type="text/javascript">let $background = [];</script>

<?php

$backgroundSearch = $connexion->query("
	
	SELECT name FROM imgheader WHERE active = 1

	");

$backgrounds = $backgroundSearch->fetchAll();

foreach ($backgrounds as $background) {
	?><script type="text/javascript">
		$background.push('url(img/<?=$background['name']?>)');

	</script>
	<?php
}

?>

<script type="text/javascript">
$(function(){ // Encapsulage
 
	// CHANGEMENT DU BG DU HEADER

	let backActuel = 0; // Declaration d'une variable qui stockera le fond d'ecran actuel (index)


	let changeBackground = () =>{
	    if (backActuel == $background.length-1){// Si le fond d'ecar actuel est le dernier, on reviens au premier fond sinon on incremente de 1 l'index a utiliser comme fond
	        backActuel = 0;
	    }else {backActuel++;

	        }
	    $('header').css("background-image", $background[backActuel]);

	};

	setInterval(changeBackground, 10000);

	$('#burger').click(() =>{

	$('#menuBurger').toggle();

	})

	$('#listBurger').on('click', '#connect', function() {

	$('#connectDiv').toggle();

	$('#connectDiv').css('top', 20);

	})

})

</script>