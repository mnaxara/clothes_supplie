<?php
include('includes/connect.php');
session_start();

$select = $connexion->query('SELECT * FROM categories');
$categories = $select->fetchAll();

?>
<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8">
	<?php include ('includes/link.php')?>
	<title>Articles</title>
</head>

<body>

<?php include ('includes/header.php')?>

<main class="container">

	<form method="GET" id="form-search"class="d-flex align-items-center">	


		<div class="col-md-4 form-group d-flex justify-content-center align-items-center">
			<input type="text" name="nom" placeholder="Nom" id="nom">
			<button type="submit" class="btn btn-info" id="search"><i class="fas fa-search"></i></button>
		</div>
		<div class="col-md-3 form-group">
			<select name="categories">
				<option value="0">Catégories</option>
				<?php 
					foreach ($categories as $categorie) {
						?>
						<option value="$categorie['id']"><?= $categorie['category_name']?></option>
						<?php
					}
						?>

			</select>
		</div>
		
		<div class="col-md-2 offset-md-2 form-group text-right">
			<select name="tri">
				<option value="0">Ordre</option>
				<option value="asc">Croissant</option>
				<option value="desc">Décroissant</option>
			</select>
		</div>
		
    </form>
	
    <div class="row align-items-center" id="rowArticle">
    	<div class="col-md-4">
    		<img src="https://placehold.it/300x200" alt="">
    	</div>
    	<div class="col-md-3">
    		<h1>Nom produit</h1>
    		<h2>Catégorie</h2>
    	</div>
    	<div class="col-md-3">
    		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet facilisis velit. Maecenas massa quam, laoreet id tincidunt quis, blandit ut nunc. Sed faucibus convallis odio, non hendrerit ex rhoncus non.</p>
    	</div>
    	<div class="col-md-2 text-right">
    		<h3>50$</h3>
    	</div>
    </div>

	<?php 

	if(!empty($_GET)){
		$select = $connexion -> query('SELECT * FROM categories');
		$recherche = $select ->fetchAll();
		$errors = [];
		if(!empty($_GET['categorie']) && !in_array($_GET['categorie'], $recherche)){
			$errors[] = 'Categorie invalide';
		}
		if(!empty($_GET['nom']) && !preg_match('#\w{2,20}#', $_GET['nom'])){
			$errors[] = 'Nom invalide';
		}
		if(!empty($_GET['tri']) && ($_GET['tri'] !== "ASC" && $_GET['tri'] !== "DSC")){
			$errors[] = 'tri invalide';
		}
		if(empty($errors)){
			$sql = 'SELECT * FROM products INNER JOIN categories_has_products ON products.id = categorie_has_products.id_products INNER JOIN categories ON categorie_has_products.categories_id = categories.id';
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

        	var_dump($products);
		}
		else{
			echo implode('<br>', $errors);
		}
	}
	
	?>
</main>

	<?php
	include('includes/footer.php');
	?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
</body>
</html>
