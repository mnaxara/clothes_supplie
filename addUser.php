<?php
include('includes/connect.php');
session_start();

if(!empty($_POST['action']) && $_POST['action'] == "new_user"){

	$errors = [];	
	$post = [];

	foreach($_POST as $key => $value){		
		$post[$key] = strip_tags($value);
	}

	if(!isset($post['mdp']) OR !isset($post['mdp2']) 
		OR mb_strlen($post['mdp']) < 4 
		OR mb_strlen($post['mdp']) > 10 
		OR $post['mdp'] !== $post['mdp2']){
		$errors['mdp'] = 'le mot de passe doit faire entre 4 et 10 caractères et les deux mdp envoyés doivent être identiques';
}
if ($post['role'] === "0" ) {
	$errors[] = 'role non definie';
}


$select = $connexion->prepare('SELECT id, email, password FROM user WHERE email = :email');
$select->bindValue(':email', $post['email']);
$select->execute();
$users = $select->fetchAll();
if(count($users) > 0){
	$errors[] = 'l\'email existe déjà';
}

if(empty($post['email']) OR !filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
	$errors['email'] = 'email invalide';
}

if(empty($errors)){

	$insert = $connexion->prepare('INSERT INTO user (email, password, role) VALUES (:email, :mdp, :role)');

	$insert->bindValue(':email', $post['email']);
	$insert->bindValue(':mdp', password_hash($post['mdp'], PASSWORD_DEFAULT));
	$insert->bindValue(':role' , $_POST['role']);
	if($insert->execute()){
		echo 'Inscription valide';
	}
	else{
		echo 'sql error';
	}
}
else{
	echo implode('<br>', $errors);
}
}

?>