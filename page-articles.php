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


		<div class="col-md-6 form-group d-flex align-items-center">
			<input type="text" name="nom" placeholder="Nom" id="nom">
			
		
			<select name="categorie">
				<option value="0">Catégories</option>
				<?php 
					foreach ($categories as $categorie) {
						?>
						<option value="<?php echo $categorie['id']?>">
						<?=$categorie['category_name']?>
						</option>
						<?php
					}
						?>

			</select>
			<button type="submit" class="btn btn-info" id="search"><i class="fas fa-search"></i></button>
		</div>
		
		<div class="col-md-2 offset-md-4 form-group text-right">
			<select name="tri">
				<option value="0">Ordre</option>
				<option value="ASC">Croissant</option>
				<option value="DSC">Décroissant</option>
			</select>
		</div>
		
    </form>
	
    







    
<?php 
    $select = $connexion->query('SELECT COUNT(id) AS nb  FROM products');
	$count = $select->fetch();
	$nb_products = $count['nb'];

	$nb_par_page = 5;

	$nbPages = ceil($nb_products / $nb_par_page);

	if(empty($_GET['numeroPage']) || !is_numeric($_GET['numeroPage']) || $_GET['numeroPage'] > $nbPages){
     //par défaut on affiche la première page
	$numeroPage = 1;
    }
    else{
	$numeroPage =  $_GET['numeroPage'];
    }

    $offset = ($numeroPage - 1) * $nb_par_page;



	

	if(empty($_GET)){

	$select = $connexion -> query("SELECT *, FROM products INNER JOIN categories_has_products ON products.id = categories_has_products.products_id INNER JOIN categories ON categories_has_products.categories_id = categories.id LIMIT " . $offset . ",". $nb_par_page);
		$products = $select -> fetchAll();

		$selection = $connexion->query("SELECT * FROM products INNER JOIN imgproduct ON products.id = imgproduct.id_product");
		$images = $selection->fetchAll();

		foreach ($products as $product) {
		?>
			<div class="row align-items-center" id="rowArticle">

    			<div class="col-md-4">
    				<img src="img/thumbnails/<?= $images[0]['name']?>" alt="Ma bite">
    			</div>

    			<div class="col-md-3">
    				<h2><?=$product['name']?></h2>
    				<h3>Catégorie</h3>
    			</div>

    			<div class="col-md-3">
    				<p><?= $product['description']?></p>
    			</div>

    			<div class="col-md-2 text-right">
    				<h3><?=$product['price']?>€</h3>
    			</div>
    		</div>
    	<?php
		}
		

	}
	else{

		$errors = [];
		if(!empty($_GET['categorie']) && !is_numeric($_GET['categorie'])){
			$errors[] = 'Categorie invalide';
		}
		if(!empty($_GET['nom']) && !preg_match('#\w{2,20}#', $_GET['nom'])){
			$errors[] = 'Nom invalide';
		}
		if(!empty($_GET['tri']) && ($_GET['tri'] !== "ASC" && $_GET['tri'] !== "DSC")){
			$errors[] = 'tri invalide';
		}
		if(empty($errors)){
			$sql = 'SELECT * FROM products INNER JOIN categories_has_products ON products.id = categories_has_products.products_id INNER JOIN categories ON categories_has_products.categories_id = categories.id';
			if(!empty($_GET['categorie'])){
				$sql .= ' WHERE categories.id = :categorie';
			}
			if(!empty($_GET['nom'])){
				$sql .= ' AND name LIKE :nom ';
			}
			if(!empty($_GET['tri'])){
				$sql .= ' ORDER BY price :ordre';
			}
			$sql .= ' LIMIT ' . $offset . ',' . $nb_par_page;
			echo $sql;
			$select = $connexion -> prepare($sql);
			if(!empty($_GET['categorie'])){
				$select -> bindValue(':categorie', strip_tags($_GET['categorie']));
			}
			if(!empty($_GET['nom'])){
				$select -> bindValue(':nom', strip_tags($_GET['nom']));
			}
			if(!empty($_GET['tri']) && $_GET['tri'] === 'ASC'){
				$select -> bindValue(':ordre', $_GET['tri']);
			}
			if(!empty($_GET['tri']) && $_GET['tri'] === 'DESC'){
				$select -> bindValue(':ordre', $_GET['tri']);
			}
			$select->execute();
        	$products = $select->fetchAll();

        	$selection = $connexion->query("SELECT * FROM products INNER JOIN imgproduct ON products.id = imgproduct.id_product");
			$images = $selection->fetchAll();
			foreach ($products as $product) {
			?>
			<div class="row align-items-center" id="rowArticle">
    			<div class="col-md-4">
    		<img src="img/thumbnails/<?= $images[0]['name'] ?>" alt="Ma bite">
    	</div>
    	<div class="col-md-3">
    		<h2><?=$product['name']?></h2>
    		<h3><?=$product['category_name']?></h3>
    	</div>
    	<div class="col-md-3">
    		<p><?= $product['description']?> <br>
    			Il reste <?=$product['availability']?>
    		</p>
    	</div>
    	<div class="col-md-2 text-right">
    		<h3><?=$product['price']?>€</h3>
    	</div>
    </div>
    	<?php
		}

		}
		else{
			echo implode('<br>', $errors);
		}
	}

    if ($numeroPage > 1) {
   	
   
	?>
	<a href="page-articles.php?numeroPage=<?=$numeroPage -1?>">Page précédente</a>
<?php
  }
      for ($i=1; $i <= $nbPages; $i++) { 
      	?>
      	<a href="page-articles.php?numeroPage=<?= $i ?>">Page <?= $i ?></a>
      	<?php
      }
      if ($numeroPage < $nbPages) {
      
?>
	<a href="page-articles.php?numeroPage=<?=$numeroPage +1 ?>">Page suivante </a>
<?php
}
?>
	
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
