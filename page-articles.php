<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Articles</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

<main class="container">

	<dir class="row">
		
		<div class="col-md-3"></div>
		<div class="col-md-3"></div>
		<div class="col-md-2"></div>
		<div class="col-md-offset-2"></div>
		<div class="col-md-2"></div>

	</dir>
	<?php 
	require_once('includes/connect.php');
	if(!empty($_GET)){
		$select = $connexion -> query('SELECT * FROM categorie');
		$recherche = $select ->fetchAll();
		$errors = [];
		if(!empty($_GET['categorie']) && !in_array($_GET['categorie'], $recherche)){
			$errors[] = 'Categorie invalide';
		}
		if(!empty($_GET['nom']) && !preg_match('#/w{2-20}#')){
			$errors[] = 'Nom invalide';
		}
		if(!empty($_GET['tri']) && $_GET['tri'] === "ASC" || $_GET['tri'] === "DSC"){
			$errors[] = 'ordre invalide';
		}
		if(empty($errors)){
			$sql = 'SELECT * FROM products INNER JOIN categorie_has_products products.id = categorie_has_products.id_products INNER JOIN categories categorie_has_products.categories_id = categories.id';
			if(!empty($_GET['categorie'])){
				$sql .= ' WHERE :categorie';
			}
			if(!empty($_GET['nom'])){
				$sql .= ' AND nom LIKE :nom ';
			}
			if(!empty($_GET['tri'])){
				$sql .= ' ORDER BY price :ordre';
			}
			$select = $connexion -> prepare($sql);
			if(!empty($_GET['categorie'])){
				$select -> bindValue(':categorie', strip_tags($_GET['categorie']));
			}
			if(!empty($_GET['nom'])){
				$select -> bindValue(':nom', strip_tags($_GET['nom']));
			}
			if(isset($_GET['tri'])){
				$select -> bindValue(':ordre', $_GET['tri']);
			}
			$select->execute();
        	$products = $select->fetchAll();
		}
	}
	
	
	?>
</main>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
</body>
</html>
