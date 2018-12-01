	<!-- GESTION DE CONNEXION -->
<?php
session_start();
include('includes/connect.php');

				if (!empty($_POST)) {

					$post = [];
					foreach ($_POST as $key => $value) {
						$post[$key] = strip_tags($value);
					}

					$errors = [];

					if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
						$errors[] = 'email invalide';
					}

					if (empty($errors)) {
									
						$connectionRequest = $connexion->prepare("

							SELECT * FROM user WHERE email = :email AND password = :password

							");

						$connectionRequest->bindValue(':email', $post['email']);
						$connectionRequest->bindValue(':password', $post['password']); // A hasher

						if ($connectionRequest->execute()) {
							$user = $connectionRequest->fetchAll();
							
							if (count($user) == 1){
								$_SESSION['connexion'] = true;
								$_SESSION['email'] = $user[0]['email'];
								$_SESSION['role'] = $user[0]['role'];
								$_SESSION['id'] = $user[0]['id'];
								$logName = date('Y-m-d');
								$log = fopen('log/'.$logName, 'a+');
            					fwrite($log, "log in -- email -> " . $_SESSION['email'] . " -- date -> " . date('Y-m-d H:i:s') . PHP_EOL);
           						fclose($log);

								header('location: index.php');

							}
							else{
								echo "identifiant invalide";
							}
						}
						else{
							echo "erreur sql";
						}
					}
					
				}
?>