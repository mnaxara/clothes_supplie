<?php
session_start();
require_once ('includes/connect.php');
require_once('vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>
<!DOCTYPE html>
<html>
<head>
	<title>reset mot de passe</title>
	<?php include('includes/link.php');?> 
</head>
<body>
	<?php include('includes/header.php');?> 
	<div class="container">
		<span for="email">Demande de nouveau mot de passe :</span>
		<form method="POST" class="form-group row">
			<input class="form-control col-12 col-md-6" type="text" name="email" placeholder="email">
			<button class="btn btn-primary" type="submit">envoyer</button>
		</form>

		<?php

		$select = $connexion->prepare('SELECT id, email FROM user WHERE email = :email');
		$select->bindValue(':email', $_POST['email']);
		$select->execute();
		$users = $select->fetchAll();

		if(count($users) === 1 ){
			$_SESSION['id'] = $users[0]['id'];    		
			$_SESSION['email'] = $users[0]['email'];
			
			


			$newMdp = md5(uniqid(rand(), true));

			$insert = $connexion->prepare('INSERT INTO reset_password ( id_user, token) VALUES (:id_user, :token)');

			$insert->bindValue(':id_user',$_SESSION['id']);
			$insert->bindValue(':token', $newMdp);
			if($insert->execute()){

				$mail = new PHPMailer(true);
				try{
					
					$mail->isSMTP();
					$mail->Host = 'smtp-mail.outlook.com';

					$mail->SMTPAuth = true;
					$mail->Username = 'wf3test@hotmail.com';
					$mail->Password = 'wf312345678'; 
					$mail->SMTPSecure = 'tsl'; 
					$mail->Port = 587; 

					$mail->setFrom("wf3test@hotmail.com" , "remi");
					$mail->addAddress($_POST['email'],'Rplantey');

					$mail->isHTML(true);

					$mail->Body = "<a href='http://localhost/promo8/mdp_oublie/new_password.php?id_user=" . $_SESSION['id'] . "&token=" . $newMdp. "'>"."clique". "</a>";

					$mail->send();
					echo "message envoyer";			

				}catch(Exception $e){
					echo "gros probleme : " . $mail->ErrorInfo;;
				}

			}
			else{
				echo 'sql error';
			}

		}else{
			echo "pas de compte enregistré";
		}

		?>
		
	</div>




	<?php include('includes/footer.php');?> 

</body>
</html>