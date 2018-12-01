<?php 

require_once ('connect.php');

?>

<!DOCTYPE html>

<html>
<head>
	<?php include('link.php')?>
	<title>Test Header</title>
</head>
<body>

	<header class="container-fluid mb-5">
		<div class="row d-flex align-items-center">
			<div class="col-10 col-md-3">
		<!-- Search bar  -->
				<form class="p-md-3" action="page-articles.php">
                    <div class="d-flex align-items-center">
                        <input class="form-control form-control-lg form-control-borderless" type="search" placeholder="Chercher un article" name="nom">
                        <button class="btn btnSearch" type="submit"><i class="fas fa-search loupe"></i></button>
                    </div>
                </form>
			</div>
			<!-- Div caché de connexion -->
			<div id="connectDiv">
				<form id="connectForm" class="d-flex flex-column align-items-center" action="connexion.php" method="POST">
					<div class="form-group m-2">
			   			<label for="email">Email address</label>
						<input type="email" class="form-control" name= "email" id="email" placeholder="Enter email">
					</div>
					<div class="form-group m-2">
						<label for="password">Password</label>
						<input type="password" class="form-control" name= "password" id="password" placeholder="Password">
					</div>
					<button type="submit" class="btn btn-primary m-2">Se Connecter</button>
				</form>		

			</div>
			<div class="col-md-3 offset-md-2" id="userInfo" style="<?= !empty($_SESSION) ? 'background-color: rgba(220, 220, 220, 0.5)' : "";?>">
				<?php
				if (!empty($_SESSION) && $_SESSION['role'] === 'ROLE_ADMIN') {
					echo "<span>Connecté en tant que ".$_SESSION['email']." -Admin- </span>";
				}
				if (!empty($_SESSION) && $_SESSION['role'] === 'ROLE_VENDOR') {
					echo "<span>Connecté en tant que ".$_SESSION['email']." -Vendeur- </span>";
				}
				?>
			</div>
		<!-- Menu burger  -->
			<div class="col-2 col-md-2 offset-md-2 text-center">
				<i class="fas fa-bars fa-2x" id="burger"></i>
				<div id="menuBurger">
					<ul class="list-group" id="listBurger">
					  <li class="list-group-item" id="home">Home</li>
					  <li class="list-group-item" id="article">Nos Articles</li>
					  <li class="list-group-item" id="contact">Contact</li>
					  <?php
					  	if (!empty($_SESSION) && ($_SESSION['role'] === "ROLE_ADMIN" || $_SESSION['role'] === "ROLE_VENDOR")){
					  		echo '<li class="list-group-item" id="admin">ADMIN</li>';
					  	}
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
	<?php 

		include ('js/header_js.php');

	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>