<?php
session_start();
require_once ('../includes/connect.php');
$admin = true;

if ($_SESSION['role'] === "ROLE_ADMIN" || $_SESSION['role'] === "ROLE_VENDOR") {

$photosHeader = $connexion->query("
				
	SELECT * FROM imgheader

	");

$photosH = $photosHeader->fetchAll();


?>

<!DOCTYPE html>


<html lang="FR">

	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/footer_app.css">
		<title>Page admin</title>

	</head>

	<body>
		<?php include ('../includes/header_admin.php')?>

		<fieldset>
			<legend>Insérer une image</legend>

			<form action="upload.php" method="POST" enctype="multipart/form-data">
				<input type="file" name="fichier">			
				<select name="table" id="table">
					<option value="imgheader">Slider</option>
					<option value="imgproduct">Articles</option>
				</select>
				<label for="article">Si lié a un article, indiquer l'article</label>
				<select name="article" id="article">
					<option value="">Choisir un article</option>
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

		<fieldset>
			<legend>Modifier image page accueil</legend>
			<form action="admin.php">
				<table>
					<tr>
						<th>Emplacement</th>
						<th>Miniature</th>
						<th>Modifier</th>
					</tr>
					<?php

					$sliderRequest = $connexion->query("
						
						SELECT * FROM imgheader WHERE active != 0

						");

					$slider = $sliderRequest->fetchAll();

					foreach ($slider as $img) {
					?>
					<tr>
						<td><?=$img['active']?></td>
					<form action="admin.php">
						<td>
							<label for="apercu<?=$img['active']?>">
								<img src=
								<?php

									if (empty($_GET['apercu'.$img["active"]])){
										echo "../img/thumbnails/".$img['name'];
									}
									else{
										echo "../img/thumbnails/".$_GET['apercu'.$img["active"]];
									} 

								?>
								>
							</label>
						</td>
						<td>
							<select name="apercu<?=$img['active']?>" id="apercu<?=$img['active']?>">
							<option value="">Choisir une autre image</option>
							<?php
							foreach ($photosH as $photoH) {
								?><option value="<?=$photoH['name']?>"><?=$photoH['name']?></option><?php
							}
							?>
							</select>
						</td>
						<td>
							<button>Apercu</button>
						</form>
						</td>
					</tr>
					<?php
					}
					?>
					</form>
				</table>

			</form>
		</fieldset>

		<?php include ('../includes/footer.php')?>
	</body>

<?php
} //Fin Admin Zone
?>

</html>