<script type="text/javascript">let $background = [];</script>

<?php

// On recupere les photo header nn active et on les push dans un tableau

$backgroundSearch = $connexion->query("
	
	SELECT name FROM imgheader WHERE active != 0 ORDER BY active

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
	
	changeBackground();
	setInterval(changeBackground, 15000);

	// Affichage du menu burger

	$('#burger').click(() =>{

	$('#menuBurger').toggle();

	});

	// Affichage de la div de connexion

	$('#listBurger').on('click', '#connect', function() {

	$('#connectDiv').toggle();

	})

	// Gestion des on clic sur les li

	$('#home').click(() =>{

	window.location.replace("index.php");

	});

	$('#article').click(() =>{

	window.location.replace("page-articles.php");

	});

	$('#contact').click(() =>{

	window.location.replace("contact.php");

	});

	$('#listBurger').on('click', '#deconnect', function() {

	window.location.replace("logout.php");

	});

	$('#listBurger').on('click', '#admin', function() {

	window.location.replace("admin/admin.php");

	});

})

</script>