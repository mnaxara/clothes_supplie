$(function(){ // Encapsulage
 
	// CHANGEMENT DU BG DU HEADER

	let $background = [// Tableau contenant tous les fonds possible
		'url(../img/recentpost1.jpg)',
		'url(../img/soloslider.png)'

	];

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

})