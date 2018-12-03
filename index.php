<?php
 require_once ('includes/connect.php');

session_start();
?>
<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title> </title>
	<?php
	include('includes/link.php');
	?>
</head>
<body>
	<?php
	include('includes/header.php');
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="logo">
					<img src="img/LOGO.jpg" alt="logo" class="rounded-circle">
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
		
			<div class="col-md-12">
			<h2><u>Présentation de notre boutique : </u></h2>
				<p> Première  collection débutée en novembre 2018: Cloth Supplies habille petits et grands, du manteau au pantalon en passant par les tee-shirts.
				Ce site vitrine nous permet de mettre en avant les articles vendus en magasin. ...
				</p>
			</div>
			<div class="row">
				<div class="img-responsive"> 
			<?php
			$select = $connexion -> query('SELECT * FROM imgproduct WHERE mainpage > 0 ORDER BY mainpage');
			$resultats = $select ->fetchAll();
			foreach ($resultats as $resultat) {
			?>
			<img src="img/<?= $resultat['name']?>">
			<?php

			}
			?>
		
			
				
				</div>
			</div>

		</div>
	</div>

	<?php
	include('includes/footer.php');
	?>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>