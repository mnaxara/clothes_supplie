<?php
session_start();
require_once ('../includes/connect.php');
$admin = true;

if ($_SESSION['role'] === "ROLE_ADMIN" || $_SESSION['role'] === "ROLE_VENDOR") {
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
					<tr>
						<td>1</td>
						<td>2</td>
						<td>3</td>
					</tr>
				</table>

			</form>
		</fieldset>

		<?php include ('../includes/footer.php')?>
	</body>

<?php
} //Fin Admin Zone
?>

</html>