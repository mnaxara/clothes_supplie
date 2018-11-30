<?php

try{//pour éviter de donner nos identifiants de connexion en cas d'erreur
    $connexion = new PDO('mysql:host=localhost;dbname=clothsupply;charset=utf8', 'root', '',
    array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
       )
   );
   }
  catch(Exception $e){
 	   die('Erreur : ' .$e->getMessage());
	}
?>
