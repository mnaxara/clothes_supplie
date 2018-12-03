<?php
session_start();
require_once ('includes/connect.php');
 require_once('vendor/autoload.php');
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 ?> 
 <!DOCTYPE html>
 <html lang="fr">
 <head>
 	<meta charset="utf-8">
 	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
 	<title>contact</title>
 	<link rel="stylesheet" type="text/css" href="css/contact_app.css">
 </head>
 <body>
 	<?php include('includes/header.php'); ?>
 	<div class="container">
 		<form method="POST" action="contact.php" class="c_contact">
 			<div class="row">
 				<div class="col-12 col-md-4">
 					<div class="form-group">					
 						<input type="text" name="name" class="form-control" placeholder="Nom" required>
 					</div>
 					<div class="form-group">					
 						<input type="text" name="firstname" class="form-control"placeholder="Prénom" required>
 					</div>
 					<div class="form-group">					
 						<input type="text" name="email" class="form-control" placeholder="@Email" required>
 					</div>
 					<div class="form-group">					
 						<input type="text" name="tel" class="form-control" placeholder="Téléphone">
 					</div>
 				</div>
 			</div>
 			<div class="form-group row">
 				<div class="col-12 col-md-8">					
 					<textarea name="message" class="form-control" placeholder="Votre message"></textarea>
 				</div>
 			</div>		
 			<button type="submit" class="btn btn-default">Envoyer le message</button>
 		</form>
 		<?php

 		if(!empty($_POST)){

		// si post n'est pas vide c'est que le formulaire a ete envoyer
 			$errors= [];

 			if(empty($_POST['name'])){
 				$errors[]= 'le nom est manquant';
 			}

 			if(empty($_POST['firstname'])){
 				$errors[]= 'le prénom est manquant';
 			}

 			if(empty($_POST['email']) OR !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
 				$errors[] = 'email invalide';
 			}

 			if (empty($_POST['message'])|| mb_strlen($_POST['message']) < 10 ){
 				$errors[] = 'message trop court';
 			}


		// je regarde si j'ai des erreurs
 			if(empty($errors)){
 				$mail = new PHPMailer(true);
                 try{


                     $mail->isSMTP();
                     $mail->Host = "mail.gmx.com";                        

                     $mail->SMTPAuth = true;
                     $mail->Username = 'promo8wf3@gmx.fr';
                        $mail->Password = 'ttttttttt33'; //9 x t
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        $mail->SetFrom ('promo8wf3@gmx.fr', "la_boutique_en_ligne");
                        $mail->addAddress($_POST['email'],'Rplantey');

                        $mail->isHTML(true);

                        $mail->Body = $_POST['message'];

                        $mail->send();
                        echo "Message envoyer";			

                    }catch(Exception $e){
                    	echo "gros probleme : " . $mail->ErrorInfo;;
                    }
                }else{
                   echo implode('<br>', $errors);
               }
           }
           ?>
           <section>
            <div class="row">
               <div class="col-12 col-md-8 map">
                   <?php

                $resultat = $connexion->query('SELECT * FROM shop');

                $adresse = $resultat->fetch();

                   $adresse = $adresse['adress'];

                   $opts = array(
                    'http' => array(
                        'method' => 'GET'
                    )
                );

                   $context = stream_context_create($opts);

                   $url = "https://maps.googleapis.com/maps/api/geocode/json?address={".urlencode(strip_tags($adresse))."}&key=AIzaSyBjslA2cbupRwG-dJvPAKcfZp0ruzEFM38";

                   $resultat = json_decode(file_get_contents($url, false, $context), true);

                   $lat = $resultat['results'][0]['geometry']['location']['lat'];
                   $lng = $resultat['results'][0]['geometry']['location']['lng'];
                   ?>
                   <div id="map" style="height: 400px;"></div>
               </div>
               <div class="col-12 col-md-4 d_contact">
                  <ul class="info">
                     <h2>Infos :</h2>
                     <li><span>Adresse :</span><?=$adresse?></li>
                     <li><span>Téléphone :</span> 05 56 52 23 26</li>
                     <li><span>Email :</span> philo@philo.philo</li>
                 </ul>						
             </div>					
         </div>
     </section>
     <section>
        <form method="GET" action="contact.php" class="c_news">
           <div class="row">
              <div class="col-12 col-md-4">
                 <div class="form-group">					
                    <input type="text" name="email" class="form-control" placeholder="@Email" required>
                </div>
            </div>
            <button type="submit" class="btn btn-default">S'inscrire a la newsletter</button>
        </div>
        <?php

        if(!empty($_GET)){

          $errors= [];

          if(empty($_GET['email']) OR !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
             $errors[] = 'email invalide';
         }


         if(empty($errors)){
             $mail = new PHPMailer(true);
             try{


                $mail->isSMTP();
                $mail->Host = "mail.gmx.com";                        

                $mail->SMTPAuth = true;
                $mail->Username = 'promo8wf3@gmx.fr';
                        $mail->Password = 'ttttttttt33'; //9 x t
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        $mail->SetFrom ('promo8wf3@gmx.fr', "la_boutique_en_ligne");
                        $mail->addAddress($_GET['email'],'Rplantey');

                        $mail->isHTML(true);

                        $mail->Body = "vous etes inscrit a la Newsletter";

                        $mail->send();
                        echo "Vous etes  maintenant abonnes";			

                    }catch(Exception $e){
                    	echo "gros probleme : " . $mail->ErrorInfo;;
                    }

                }else{
                	echo implode('<br>', $errors);
                }
            }
            ?>
        </form>
    </section>
</div>

  <?php
  include('includes/footer.php');
  ?>
<script>
  function initMap() {
    var adresse = {lat: <?= $lat ?>,  lng: <?= $lng ?>};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 17,
      center: adresse
  });
    var marker = new google.maps.Marker({
      position: adresse,
      map: map
  });
}

</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0xJoi5c9MwYIYQlwIEfLqLh95hLtcaYA&callback=initMap">
</script> 

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/app.js"></script>
</body>
</html>