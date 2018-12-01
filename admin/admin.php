<?php
session_start();
require_once ('../includes/connect.php');
$admin = true;

// ACCESSIBLE UNIQUEMENT AU ADMIN
if ($_SESSION['role'] === "ROLE_ADMIN") {

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
		$update->execute();

		header("Refresh:0");
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
		<title>Page admin</title>

	</head>

	<body>
		<?php include ('../includes/header_admin.php')?>
	<!-- FORM D'INSERSION D'IMAGE -->
		<fieldset>
			<legend>Insérer une image</legend>

			<form action="upload.php" method="POST" enctype="multipart/form-data">
				<input type="file" name="fichier">			
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
	<!-- FORM DE MODIFICATION DU SLIDER -->
		<fieldset>
			<legend>Modifier image Slider</legend>
			<!-- REDUIRE ICI POUR MASQUER LE TABLEAU DE MODIF-->
				<table>
						<tr>
							<th>Emplacement</th>
							<th>Miniature</th>
							<th>Modifier</th>
						</tr>
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
				</table>
		</fieldset>

		<fieldset>
			<legend>Ajouter un user</legend>
		</fieldset>

		<fieldset>
			<legend>Changer l'adresse</legend>
		</fieldset>

<?php
} //Fin Admin Zone
// Zone Vendeur / Admin
if ($_SESSION['role'] === "ROLE_ADMIN" || $_SESSION['role'] === "ROLE_VENDOR") {
?>
<fieldset>
	<legend>Edition / Ajout d'article</legend>
</fieldset>



<?php
}// Fin Zone Veudeur / Admin
include ('../includes/footer_admin.php');
?>
	</body>


</html>