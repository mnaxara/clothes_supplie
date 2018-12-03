<?php
session_start();
require_once ('../includes/connect.php');
$admin = true;

// ACCESSIBLE UNIQUEMENT AU ADMIN
if (isset($_SESSION['role']) && $_SESSION['role'] === "ROLE_ADMIN") {


// REQUETE DE RECUPERATION DES IMAGES DEJA INTEGRE AU SLIDER (Active est different de 0)
	$sliderRequest = $connexion->query("

		SELECT * FROM imgheader WHERE active != 0 ORDER BY active ASC

		");

	$slider = $sliderRequest->fetchAll();

// REQUETE DE RECUPERATION DES IMAGES HEADER QUI NE SONT PAS DANS LE SLIDER

	$photosHeader = $connexion->query("

		SELECT * FROM imgheader WHERE active = 0

		");

	$photosH = $photosHeader->fetchAll();

// REQUETE D'UPDATE DU SLIDER

	if (!empty($_POST['action']) && $_POST['action'] === 'update') {

	// 4 CONDITIONS POUR TESTE QUELLE EST L'IMAGE UPDATE (PEUT ETRE NON OPTIMAL)
			if (!empty($_POST['img1'])) {
				$name = $_POST['img1'];
			}
			if (!empty($_POST['img2'])) {
				$name = $_POST['img2'];
			}
			if (!empty($_POST['img3'])) {
				$name = $_POST['img3'];
			}
			if (!empty($_POST['img4'])) {
				$name = $_POST['img4'];
			}
	// SI IL A CHOISI UNE IMAGE
			if(!empty($name)){
	// ON PASSE L'ACTIVE DE L'ANCIENNE IMAGE A 0 ET ON ATTRIBUE LA VALEUR NOUVELLE VALEUR ACTIVE A LA NOUVELLE IMAGE	
				$update_old = $connexion->prepare("

					UPDATE imgheader SET active = 0 WHERE active = :active

					");

				$update_old->bindValue(':active', $_POST['active']);
				$update_old->execute();

				$update = $connexion->prepare("

					UPDATE imgheader SET active = :active WHERE name = :name AND active = 0

					");

				$update->bindValue(':active', $_POST['active']);
				$update->bindValue(':name', $name);
				if($update->execute()){
					$logName = date('Y-m-d');
					$log = fopen('../log/'.$logName, 'a+');
					fwrite($log, "SLIDER MODIF -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
					fclose($log);

					header("Refresh:0");
				}
			}
		}

	// REQUETE SUPPRESSION IMAGE DE PRODUIT

	if (!empty($_POST['action']) && $_POST['action'] === 'imgproductDelete') {

		if (is_numeric($_POST['imgIdToDel'])) {
			
		
			$deleteImg = $connexion->prepare("

				DELETE FROM imgproduct WHERE id = :id

				");
			$deleteImg->bindValue('id', $_POST['imgIdToDel']);
			if ($deleteImg->execute()) {

				unlink("../img/".$_POST['imgNameToDel']);
				unlink("../img/thumbnails".$_POST['imgNameToDel']);
				
			};
		}
		else{
			echo "img incorrect";
		}
	}

	// REQUETE MODIFICATION DE PRODUITS

	if (!empty($_POST['action']) && $_POST['action'] === 'updtPdct') {
		
		$errors = [];

		if (mb_strlen($_POST['name']) < 2) {
			$errors[] = 'Nom invalide';
		}

		if (mb_strlen($_POST['description']) < 10) {
			$errors[] = 'Description trop courte';
		}
		if (empty($_POST['categories'])) {
			$errors[] = 'Selectionner une categorie';
		}
		if (!is_numeric($_POST['price'])) {
			$errors[] = 'Prix invalide';
		}
		if (!is_numeric($_POST['availability'])) {
			$errors[] = 'Disponibilité invalide';
		}

		if (empty($errors)) { // SI PAS D'ERREUR

			$post = [];

			foreach ($_POST as $key => $value) {
				if (is_string($value)) {
					$post[$key] = strip_tags($value);
				}
			}
			
			$updateProduct = $connexion->prepare("
				
				UPDATE products SET name = :name, description = :description, availability = :availability, price = :price WHERE
				id = :id

				");

			$updateProduct->bindValue(':name', $post['name']);
			$updateProduct->bindValue(':description', $post['description']);
			$updateProduct->bindValue(':availability', $post['availability']);
			$updateProduct->bindValue(':price', $post['price']);
			$updateProduct->bindValue(':id', $post['id']);
			$updateProduct->execute();

			// ON SUPPRIME TOUTES LES CATEGORIES LIE A L'ARTICLE

			$delCategories = $connexion->prepare("

			DELETE FROM categories_has_products WHERE products_id = :id

				");
			$delCategories->bindValue(':id', $post['id']);
			$delCategories->execute();

			// ON AJOUTE LES NOUVELLES CATEGORIES

			$addCat = "";

			foreach ($_POST['categories'] as $key => $value) {
				$addCat .= " (:id, ".strip_tags($value)."),";
			}

			$addCat = substr($addCat, 0, -1);

			$addCategories = $connexion->prepare("

			INSERT INTO categories_has_products (products_id, categories_id) VALUES ".$addCat);
			$addCategories->bindValue(':id', $post['id']);
			$addCategories->execute();

			$logName = date('Y-m-d');
			$log = fopen('../log/'.$logName, 'a+');
			fwrite($log, "ARTICLE MODIFIE id :".$post['id']." -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
			fclose($log);

		}
		else{
			echo implode('<br>', $errors);
		}
	}

	// REQUETE AJOUT DE PRODUITS

		if (!empty($_POST['action']) && $_POST['action'] === 'newPdct') {
		
		$errors = [];

		if (mb_strlen($_POST['nameNew']) < 2) {
			$errors[] = 'Nom invalide';
		}

		if (mb_strlen($_POST['descriptionNew']) < 10) {
			$errors[] = 'Description trop courte';
		}
		if (empty($_POST['categoriesNew'])) {
			$errors[] = 'Selectionner une categorie';
		}
		if (!is_numeric($_POST['priceNew'])) {
			$errors[] = 'Prix invalide';
		}
		if (!is_numeric($_POST['availabilityNew'])) {
			$errors[] = 'Disponibilité invalide';
		}

		if (empty($errors)) { // SI PAS D'ERREUR

			$post = [];

			foreach ($_POST as $key => $value) {
				if (is_string($value)) {
					$post[$key] = strip_tags($value);
				}
			}
			
			$newProduct = $connexion->prepare("
				
				INSERT INTO products (name, price, availability, description) VALUES (:name, :price, :availability, :description)

				");

			$newProduct->bindValue(':name', $post['nameNew']);
			$newProduct->bindValue(':description', $post['descriptionNew']);
			$newProduct->bindValue(':availability', $post['availabilityNew']);
			$newProduct->bindValue(':price', $post['priceNew']);
			$newProduct->execute();

			// ON AJOUTE LES NOUVELLES CATEGORIES

			$idNew = $connexion->lastInsertId();

			$addCat = "";

			foreach ($_POST['categoriesNew'] as $key => $value) {
				$addCat .= " (:id, ".strip_tags($value)."),";
			}

			$addCat = substr($addCat, 0, -1);

			$addCategories = $connexion->prepare("

			INSERT INTO categories_has_products (products_id, categories_id) VALUES ".$addCat);
			$addCategories->bindValue(':id', $idNew);
			$addCategories->execute();

			$logName = date('Y-m-d');
			$log = fopen('../log/'.$logName, 'a+');
			fwrite($log, "ARTICLE CREE id :".$idNew." -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
			fclose($log);

		}
		else{
			echo implode('<br>', $errors);
		}



	}


	?>

	<!DOCTYPE html>


	<html lang="FR">
	<!--LINK EN DUR POUR LA PARTIE ADMIN DU AU SOUS DOSSIER-->
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/footer_app.css">
		<link rel="stylesheet" type="text/css" href="../css/admin.css">
		<title>Page admin</title>

	</head>

	<body>
		<?php include ('../includes/header_admin.php')?>
		<div class="container">
			<div class="row">
				<fieldset class="col-12">
					<legend>Ajouter un user</legend>
						<form method="post" action="../addUser.php">
							<div class="col-md-8">
								<div class="form-group">
									<label>Email</label>
									<input type="text" name="email" class="form-control">
								</div>
								<div class="form-group">
									<label>Mot de passe</label>
									<input type="password" name="mdp" class="form-control">
								</div>
								<div class="form-group">
									<label>Répéter le mdp</label>
									<input type="password" name="mdp2" class="form-control">
								</div>
								<div class="form-group">
									<select class="custom-select" name="role">
										<option value="0">choisir un role</option>								
										<option value="ROLE_VENDOR">vendeur</option>
										<option value="ROLE_ADMIN">admin</option>
									</select>
								</div>							
								<button name="action" type="submit" value="new_user" class="btn btn-primary">Valider</button>
							</div>
						</form>
				</fieldset>
			</div>
			<!-- // MODIF ADRESSE -->
			<div class="row">
				<fieldset class="col-12">
					<legend>Changer l'adresse</legend>
					<form class="form-group row" action="admin.php" method="POST">
						<?php

						$select = $connexion->query('SELECT adress FROM shop ');
						$users = $select->fetchAll();					


						?>
						<div class="col-12 col-md-4">
							<input type="text" name="adress" placeholder="<?=$users[0]['adress']?>" class="form-control">
						</div>


						<div class="col-12 col-md-6">
							<button name="action" value="update_adress" class="btn btn-primary">Modifier</button>
						</div>


						<?php
						

						if (!empty($_POST['adress']) && !empty($_POST['action']) && $_POST['action'] == "update_adress"){
							
							$update = $connexion->prepare('UPDATE shop SET adress = :adresse WHERE id = 1');
							$update->bindValue(":adresse", $_POST['adress']);



							if($update->execute()){
								echo 'Adresse modifié';
								$logName = date('Y-m-d');
								$log = fopen('../log/'.$logName, 'a+');
								fwrite($log, "ADRESS UPDATE -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
								fclose($log);
							}
							else{
								echo 'pb de modification';
							}
						}

						?>

					</form>
				</fieldset>
			</div>

			<!-- FORM D'INSERSION D'IMAGE -->
			<div class="row">
				<fieldset class="col-12">
					<legend>Insérer une image</legend>

					<form action="upload.php" method="POST" enctype="multipart/form-data">
						<input type="file" name="fichier" id="file">
						<label for="table">Type d'image :</label>			
						<select name="table" id="table">
							<option value="imgheader">Slider</option>
							<option value="imgproduct">Produit</option>
						</select>
						<label for="article">Si lié a un produit, indiquer le produit</label>
						<select name="article" id="article">
							<option value="">Choisir un produit</option>
							<?php
							$articleBase = $connexion->query("

								SELECT id, name FROM products

								");

							$articles = $articleBase->fetchAll();

							foreach ($articles as $article) {
								?>
								<option value="<?=$article['id']?>"><?=$article['name']?></option>
								<?php
							}
							?>
						</select>
						<button>Envoyer</button>
					</form>

				</form>
			</fieldset>
		</div>
		<!-- FORM DE MODIFICATION DU SLIDER -->
		<div class="row">
			<fieldset class="d-flex flex-column align-items-center col-12">
				<legend>Modifier image Slider</legend>
				<!-- REDUIRE ICI POUR MASQUER LE TABLEAU DE MODIF-->
				<table class="col-12 col-md-8 offset-md-2" id="updtSlider">
					<thead>
						<tr>
							<th class="updtSliderTh">Emplacement</th>
							<th class="updtSliderTh">Miniature</th>
							<th class="updtSliderTh">Modifier</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<!-- IMAGE 1 -->
							<td>1</td>
							<form action="admin.php" method="POST">
								<td>
									<label for="img1">
										<img src=
										<?php
													// SI APERCU A ETE CLIQUE, POST AURA DES VALEUR PERMETTANT D'AFFICHER LA MINIATURE
										if (!empty($_POST['img1']) && !empty($_POST['action']) && $_POST['action'] === 'preview'){
											echo "../img/thumbnails/".$_POST['img1'];
										}
										else{
													// SINON CE SERA L'IMAGE ACTUELLEMENT EN PLACE, SLIDER SERA FORCEMENT UN TABLEAU A 4 VALEURS
													// DONC IMAGE 1 = INDEX 0 ETC..... (SLIDER EST RECUPERE EN DEBUT DE PAGE)
											echo "../img/thumbnails/".$slider[0]['name'];
										} 
										?>
										>
									</label>
								</td>
								<td>
									<select name="img1" id="img1">
										<option value="">Choisir une autre image</option>
										<?php
											// POUR CHAQUE PHOTO HEADER QUI N'EST PAS DANS LE SLIDER, UNE OPTION EST CREE
										foreach ($photosH as $photoH) {
											?><option value="<?=$photoH['name']?>"><?=$photoH['name']?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td>
									<button name="action" value="preview">Apercu</button>
								</td>
								<td>
									<input type="hidden" name="active" value="1">
									<button name="action" value="update">Modifier</button>
								</form>
							</td>
						</tr>
						<tr>
							<!-- IMAGE 2 -->
							<td>2</td>
							<form action="admin.php" method="POST">
								<td>
									<label for="img2">
										<img src=
										<?php
										if (!empty($_POST['img2']) && !empty($_POST['action']) && $_POST['action'] === 'preview'){
											echo "../img/thumbnails/".$_POST['img2'];
										}
										else{
											echo "../img/thumbnails/".$slider[1]['name'];
										} 
										?>
										>
									</label>
								</td>
								<td>
									<select name="img2" id="img2">
										<option value="">Choisir une autre image</option>
										<?php
										foreach ($photosH as $photoH) {
											?><option value="<?=$photoH['name']?>"><?=$photoH['name']?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td>
									<button name="action" value="preview">Apercu</button>
								</td>
								<td>
									<input type="hidden" name="active" value="2">
									<button name="action" value="update">Modifier</button>
								</form>
							</td>
						</tr>
						<tr>
							<!-- IMAGE 3 -->
							<td>3</td>
							<form action="admin.php" method="POST">
								<td>
									<label for="img3">
										<img src=
										<?php
										if (!empty($_POST['img3']) && !empty($_POST['action']) && $_POST['action'] === 'preview'){
											echo "../img/thumbnails/".$_POST['img3'];
										}
										else{
											echo "../img/thumbnails/".$slider[2]['name'];
										} 
										?>
										>
									</label>
								</td>
								<td>
									<select name="img3" id="img3">
										<option value="">Choisir une autre image</option>
										<?php
										foreach ($photosH as $photoH) {
											?><option value="<?=$photoH['name']?>"><?=$photoH['name']?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td>
									<button name="action" value="preview">Apercu</button>
								</td>
								<td>
									<input type="hidden" name="active" value="3">
									<button name="action" value="update">Modifier</button>
								</form>
							</td>
						</tr>
						<tr>
							<!-- IMAGE 4 -->
							<td>4</td>
							<form action="admin.php" method="POST">
								<td>
									<label for="img4">
										<img src=
										<?php
										if (!empty($_POST['img4']) && !empty($_POST['action']) && $_POST['action'] === 'preview'){
											echo "../img/thumbnails/".$_POST['img4'];
										}
										else{
											echo "../img/thumbnails/".$slider[3]['name'];
										} 
										?>
										>
									</label>
								</td>
								<td>
									<select name="img4" id="img4">
										<option value="">Choisir une autre image</option>
										<?php
										foreach ($photosH as $photoH) {
											?><option value="<?=$photoH['name']?>"><?=$photoH['name']?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td>
									<button name="action" value="preview">Apercu</button>
								</td>
								<td>
									<input type="hidden" name="active" value="4">
									<button name="action" value="update">Modifier</button>
								</form>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>
		<div class="row">
			<fieldset class="col-12">
				<legend>Modif images d'accueil</legend>
			</fieldset>
		</div>

		<?php
} //Fin Admin Zone
	// Zone Vendeur / Admin
	if (isset($_SESSION['role']) && ($_SESSION['role'] === "ROLE_ADMIN" || $_SESSION['role'] === "ROLE_VENDOR")) {
	
	// EDITION D'ARTICLE

	?>
	<div class="row">
		<fieldset class="col-12">
			<legend>Edition d'article</legend>
			<form action="admin.php" method="POST" id="productChoice">
				<?php
				// LISTE DEROULANTE PRODUIT A MODIFIER
				$productsRequest = $connexion->query("
		
					SELECT * FROM products		

					");

				$products = $productsRequest->fetchAll();

				?>

				<label for="updateP">Modifier un article</label>
				<select name="updateP" id="updateP">

					<?php
					foreach ($products as $product) {
						echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
					}
					?>
				</select>
				<button type="submit" name="action" value="chooseP">Modifier</button>
			</form>

			<?php

			// SI UN ARTICLE EST CHOISI, ECRAN DE MODIF

			if (!empty($_POST['updateP']) && !empty($_POST['action']) && $_POST['action'] == "chooseP") {

				$productChoose = $connexion->prepare("

					SELECT *, products.name AS namep FROM products INNER JOIN categories_has_products ON products.id = categories_has_products.products_id
					INNER JOIN categories ON  categories_has_products.categories_id = categories.id WHERE categories_has_products.products_id = :id

					");

				if (is_numeric($_POST['updateP'])){
					
					$id = $_POST['updateP'];
					$productChoose->bindValue(':id', $id);
					$productChoose->execute();
					$products = $productChoose->fetchAll();

					$name = $products[0]['namep'];
					$desc = $products[0]['description'];
					$price = $products[0]['price'];
					$availability = $products[0]['availability'];

					$requestImg = $connexion->prepare("
							
						SELECT id, name, id_product FROM imgproduct WHERE id_product = :id
	
						");
						$requestImg->bindValue(':id', $id);
						$requestImg->execute();
						$imgs = $requestImg->fetchAll();
				}
				else{
					echo "mauvais produit";
				}
			?>	
					<form class="row align-items-center" id="rowArticle" method="POST">
				    	<div class="col-md-2">
				    		<label for="name">Nom de l'article : </label>
				    		<input type="text" name="name" id="name" value="<?=$name?>"></input>
				    	</div>
				    	<div class="col-md-3">
				    		<div class="row">
				    			<label for="categorie">CATEGORIE : </label>
				    			<ul>
				    		
						    		<?php
						    		// JE RECUPERE TOUTE LES CATEGORIE EXISTANTE

						    		$cats = $connexion->query("SELECT id, category_name FROM categories");
						    		$cats = $cats->fetchAll();

						    		// JE RECUPERE TTE CELLE DE L'ARTICLE
						    		$catsPdcts = $connexion->prepare("

						    			SELECT category_name FROM categories LEFT JOIN categories_has_products ON
						    			categories.id = categories_has_products.categories_id WHERE products_id = :id

						    			");

						    		$catsPdcts->bindValue(':id', $id);
						    		$catsPdcts->execute();
						    		$catsPdcts = $catsPdcts->fetchAll();
						    		$catsOfProducts = [];

						    		foreach ($catsPdcts as $catsPdct) {
						    			$catsOfProducts[] = $catsPdct['category_name'];
						    		}

						    		foreach ($cats as $cat){
						    			echo "<li>";
						    			echo '<label for="categories">'.$cat["category_name"].'</label>';
						    			echo in_array($cat["category_name"], $catsOfProducts) ?
							    			 '<input name="categories[]" id="categories" type="checkbox" value="'.$cat["id"].'" checked>' : 
							    			 '<input name="categories[]" id="categories" type="checkbox" value="'.$cat["id"].'" >';
						    			echo "</li>";
						    		}

						    		?>
				    			</ul>
				    		</div>
				    	</div>
				    	<div class="col-md-3">
				    		<label for="description">Description</label>
				    		<textarea type="text" name="description" id="description" rows="20"><?=$desc?></textarea>
				    	</div>
				    	<div class="col-md-2">
				    		<label for="price">Prix :</label>
				    		<input type="text" name="price" value="<?=$price?>" id="price"></input>
				    	</div>
				    	<div class="col-md-2">
				    		<label for="availability">Disponibilité :</label>
				    		<input type="text" name="availability" value="<?=$availability?>" id="availability"></input>
				    	</div>
				    	<div class="col-12 text-center">
				    		<input type="hidden" name = "id" value="<?=$id?>">
				    		<button name="action" value="updtPdct">Modifier</button>
				    	</div>
				    </form>
				    <hr>
				    <p>Images liée(s) au produits</p>
				    <div class="row">
				    	<div class="col-12 d-flex">
						    <?php
						    foreach ($imgs as $img) {
								echo '<form action="admin.php" method="POST" class="col-md-1">';
						    	echo '<div><img src="../img/thumbnails/'.$img["name"].'" alt="'.$name.'"></div>';
						    	echo '<button name="action" value="imgproductDelete">Supprimer</button>';
						    	echo '<input type="hidden" name="imgIdToDel" value="'.$img["id"].'">';
						    	echo '<input type="hidden" name="imgNameToDel" value="'.$img["name"].'">';
								echo '</form>';
						    }

						    ?>
					    </div>
				    </div>
				    <hr>
			<?php
			}
			?>

			<!-- NOUVEAU PRODUIT -->
			<fieldset>
				    	<legend>Ajouter un produit</legend>
					<form class="row align-items-center" id="rowArticle" method="POST">
				    	<div class="col-md-2">
				    		<label for="name">Nom de l'article : </label>
				    		<input type="text" name="nameNew" id="nameNew"></input>
				    	</div>
				    	<div class="col-md-3">
				    		<div class="row">
				    			<label for="categorieNew">CATEGORIE : </label>
				    			<ul>
				    		
						    		<?php
						    		// JE RECUPERE TOUTE LES CATEGORIE EXISTANTE

						    		$cats = $connexion->query("SELECT id, category_name FROM categories");
						    		$cats = $cats->fetchAll();

						    		foreach ($cats as $cat){
						    			echo "<li>";
						    			echo '<label for="categoriesNew">'.$cat["category_name"].'</label>';
						    			echo '<input name="categoriesNew[]" id="categoriesNew" type="checkbox" value="'.$cat["id"].'" >';
						    			echo "</li>";
						    		}
						    		?>
				    			</ul>
				    		</div>
				    	</div>
				    	<div class="col-md-3">
				    		<label for="description">Description</label>
				    		<textarea type="text" name="descriptionNew" id="descriptionNew" rows="20"></textarea>
				    	</div>
				    	<div class="col-md-2">
				    		<label for="price">Prix :</label>
				    		<input type="text" name="priceNew" id="priceNew"></input>
				    	</div>
				    	<div class="col-md-2">
				    		<label for="availability">Disponibilité :</label>
				    		<input type="text" name="availabilityNew" id="availabilityNew"></input>
				    	</div>
				    	<div class="col-12 text-center">
				    		<button name="action" value="newPdct">Ajouter</button>
				    	</div>
				    </form>
			    </fieldset>

<?php
}// Fin Zone Vendeur / Admin
	if (isset($_SESSION['role'])){
		include ('../includes/footer_admin.php');
		?>
	</body>
	</html>
	<?php
}
?>
