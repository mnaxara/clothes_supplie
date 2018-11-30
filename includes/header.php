<?php

// require_once ('includes/connect.php');



?>

<!DOCTYPE html>

<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<title>Test Header</title>
</head>
<body>

	<header class="container-fluid">
		<div class="row d-flex align-items-center">
			<div class="col-10 col-md-3">
				<form class="p-md-3">
                    <div class="d-flex align-items-center">
                        <input class="form-control form-control-lg form-control-borderless" type="search" placeholder="Chercher un article">
                        <button class="btn btnSearch" type="submit"><i class="fas fa-search loupe"></i></button>
                    </div>
                </form>
			</div>
			<div id="connectDiv">
				<form id="connectForm" class="d-flex flex-column align-items-center">
					<div class="form-group m-2">
			   			<label for="email">Email address</label>
						<input type="email" class="form-control" id="email" placeholder="Enter email">
					</div>
					<div class="form-group m-2">
						<label for="exampleInputPassword1">Password</label>
						<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
					</div>
					<button type="submit" class="btn btn-primary m-2">Se Connecter</button>
				</form>
			</div>
			<div class="col-2 col-md-2 offset-md-7">
				<i class="fas fa-bars fa-2x" id="burger"></i>
				<div id="menuBurger">
					<ul class="list-group" id="listBurger">
					  <li class="list-group-item"><a href="../index.php">Home</a></li>
					  <li class="list-group-item"><a href="../page-articles.php">Nos Articles</a></li>
					  <li class="list-group-item"><a href="../contact.php">Contact</a></li>
					  <?php
					  	if (!empty($_SESSION['connexion'])) {
						  echo '<li class="list-group-item" id="deconnect">Deconnexion</li>';
					  	}
					  	else{
						  ?>
							<li class="list-group-item" id="connect">Connexion</li>
						  <?php
					  	}
					  ?>
					</ul>
				</div>
			</div>
			
		</div>


	</header>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>