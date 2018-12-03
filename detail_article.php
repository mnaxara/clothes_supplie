<?php
require_once ('includes/connect.php');

session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>detail produit</title>
	<?php
	include('includes/link.php');
	?>
</head>
<body>
	<?php
	include('includes/header.php');
	?>
	<div class="container">
		<div class="row border_row">
			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-12 col-md-6">
						<img src="https://placehold.it/250x250">
					</div>
					<div class="col-12 col-md-6">
						<?php
								// recup de l'id par url 
						if (!empty($_GET)){
							$errors = [];
							if (!is_numeric($_GET['id'])) {
								$errors[] = 'id invalide';
							}
							if (empty($errors)) {
								// check de l'id
								$resultat = $connexion->prepare('SELECT * FROM products WHERE id  = :id');
								$resultat->bindValue(':id', $_GET['id']);
								$resultat->execute();
								$products = $resultat->fetch();
							}
						}?>
						<!-- affichage depuis id -->
						<span class="name">
							<?=$products['name']?>
						</span><br>
						<span class="dispo"><?php
						if ($products['availability'] == 0) {
							echo "Indisponible <i class='fas fa-times'></i>";
						}else{
							echo "Disponible <i class='fas fa-check'></i>";
						}
						?></span><BR>
						<span class="prix">Prix : <?= $products['price'] ?>€</span>
					</div>
				</div>				
				<div class="row">
					<div class="col-12 col-md-6">
						<img class="miniature" src="https://placehold.it/75x75">
						<img class="miniature" src="https://placehold.it/75x75">
						<img class="miniature" src="https://placehold.it/75x75">
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6">
				<div class="row">					
					<p class="detail"><span class="descrip">description du produit : </span><?= $products['description'] ?></p>
				</div>
				<div class="row">
					<button class="btn btn-primary buy" disabled>Commander</button>
				</div>
			</div>
		</div>			
	</div>		
</div>
<?php
include('includes/footer.php');
?>
</body>
</html>