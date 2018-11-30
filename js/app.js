$(function(){ // Encapsulage
 
	// CHANGEMENT DU BG DU HEADER

	let $background = [// Tableau contenant tous les fonds possible
		'url(../img/boutique1.jpg)',
		'url(../img/boutique2.jpg)',
		'url(../img/boutique3.jpg)',
		'url(../img/boutique4.jpg)'

	];

	let backActuel = 0; // Declaration d'une variable qui stockera le fond d'ecran actuel (index)


	let changeBackground = () =>{
	    if (backActuel == $background.length-1){// Si le fond d'ecar actuel est le dernier, on reviens au premier fond sinon on incremente de 1 l'index a utiliser comme fond
	        backActuel = 0;
	    }else {backActuel++;

	        }
	    $('header').css("background-image", $background[backActuel]);

	};

	setInterval(changeBackground, 5000);

	$('#burger').click(() =>{

	$('#menuBurger').toggle();

	})

	$('#menuBurger').on('click', $('#connect'), (e) =>{

	$('#connectDiv').toggle();

	$('#connectDiv').css('top', 20);
	
	console.log($menuHeight/2);

	})

})