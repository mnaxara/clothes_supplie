<?php
session_start()
//$_FILES contient les informations du ou des fichiers envoyés
//var_dump($_FILES);

if(!empty($_FILES)){
	//on m'a envoyé au moins un fichier, je peux faire le traitement

	//je vérifie que le transfert s'est bien passé
	if($_FILES['fichier']['error'] == 0){
		//si error = 0, c'est bon

		//je vérifie la taille du fichier
		//1 ko = 1024 octets
		//1 Mo = 1 048 576 octets

		//je veux limiter mes fichiers à 500Ko
		$maxSize = 1920 * 1080; //500Ko en octets
		if($_FILES['fichier']['size'] <= $maxSize){
			//le fichier a une taille acceptable

			//je vérifie l'extension du fichier
			$fileInfo = pathinfo($_FILES['fichier']['name']);
			//var_dump($fileInfo);
			$extension = strtolower($fileInfo['extension']);

			$extensionsAutorisees = ['jpg', 'jpeg', 'png', 'svg'];

			if(in_array($extension, $extensionsAutorisees)){
				//l'extension est ok, on peut procéder au transfert définitif du fichier
				$newName = md5(uniqid(rand(), true));
				//echo $newName;

				//------Création de la miniature
				//je décide que mes miniatures deront 100px de large
				$newWidth = 100;

				if($extension === 'jpg' || $extension === 'jpeg'){
					$newImage = imagecreatefromjpeg($_FILES['fichier']['tmp_name']);
				}
				elseif($extension === 'png'){
					$newImage = imagecreatefrompng($_FILES['fichier']['tmp_name']);
				}
				elseif($extension === 'gif'){
					$newImage = imagecreatefromgif($_FILES['fichier']['tmp_name']);
				}

				//je vais devoir calculer la hauteur de ma miniature
				//largeur originale (en px)
				$oldWidth = imagesx($newImage);

				//hauteur originale
				$oldHeight = imagesy($newImage);

				//calcul de la nouvelle hauteur
				$newHeight = ($oldHeight * $newWidth) / $oldWidth;

				//je crée une nouvelle image avec les dimensions (nouvelle largeur et nouvelle hauteur)
				$miniature = imagecreatetruecolor($newWidth, $newHeight);

				//on remplit la miniature à partir de l'image originale
				imagecopyresampled($miniature, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

				//chemin vers le dossier où je stocke mes miniatures
				$folder = '../img/thumbnails/';

				if($extension === 'jpg' || $extension === 'jpeg'){
					imagejpeg($miniature, $folder . $newName . '.' . $extension);
				}
				elseif($extension === 'png'){
					imagepng($miniature, $folder . $newName . '.' . $extension);
				}
				elseif($extension === 'gif'){
					imagegif($miniature, $folder . $newName . '.' . $extension);
				}

				//------Fin de la création de miniature

				//Transfére le fichier sur le serveur
				move_uploaded_file($_FILES['fichier']['tmp_name'], '../img/' . $newName . '.' . $extension);

				//Insersion suivant table choisi
				require_once ('../includes/connect.php');

				$errors = [];

				if ($_POST['table'] === 'imgproduct') {
					if (is_numeric($_POST['article'])) {
						$request = 'INSERT INTO imgproduct (name, id_product) VALUES ("' . $newName . '.' . $extension .'", :id)';
					}
					else{
						$errors[] ="article incorrect";
					}
				}
				elseif ($_POST['table'] === 'imgheader') {
					$request = 'INSERT INTO imgheader (name) VALUES ("' . $newName . '.' . $extension .'")';
				}
				else{
					$errors[] = "table inconnue";
				}
		
				$insert = $connexion->prepare($request);

				if ($_POST['table'] === 'imgproduct') {
					$insert->bindValue(':id', strip_tags($_POST['article']));
				}
				// Lancement si pas d'erreur
				if (empty($errors)) {
					if($insert->execute()){
						echo 'image uploadée';
						$logName = date('Y-m-d');
						$log = fopen('../log/'.$logName, 'a+');
						fwrite($log, "UPLOAD IMG -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
				        fclose($log);
						echo '<a href="admin.php">revenir à l\'admin : </a>';
					}
					else{
						echo 'upload ko';
					}
				}
				else{
					echo implode('<br>', $errors);
				}
			}
			else{
				echo 'mauvaise extension';
			}
		}
		else{
			echo 'fichier trop gros (max 400x400';
		}
	}
	else{
		echo 'erreur lors du transfert';
	}
}
?>
