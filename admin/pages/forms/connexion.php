<?php
require_once dirname(__FILE__).'/../../../../config/config.php';
if(!isset($_POST['mdp'])){
echo'

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Espace administration</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="connexion.php" method="post">
					<span class="login100-form-title">
						Connexion
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Saisissez une adresse mail valide">
						<input class="input100" type="text" name="mail" placeholder="Adresse mail">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Le mot de passe est requis">
						<input class="input100" type="password" name="mdp" placeholder="Mot de passe">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Connexion
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Cet espace est réservé aux administrateurs du AirPods FC.
						</span>
						<a class="txt2" href="https://www.airpodsfc.fr/index.php">
							Retour à l\'accueil
						</a>
					</div>

				</form>
			</div>
		</div>
	</div>




<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$(\'.js-tilt\').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>';}else{

  try {
    $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
  } catch(Exception $e) {
  	exit ('Erreur while connecting to database: '.$e->getMessage());
  }

// Hachage du mot de passe
$pass_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

// Vérification des identifiants
$req = $bdd->prepare('SELECT * FROM administrators WHERE email = ?;');
$req->execute(array($_POST['mail']));
$test = $req->fetch();


$verify = password_verify($_POST['mdp'], $test['password']);
if ($verify)
{
    session_start();
    $_SESSION['id'] = $test['id'];
    $_SESSION['nom'] = $test['nom'];
    $_SESSION['email'] = $test['email'];
    header( "refresh:5;url=backhome.php" );
    echo '<center><h1><b><font size="7" face="verdana">Bienvenue ', $test['nom'], ' !</font></b></h1><br>Reading data from the database, this might take up to 15 seconds.</p><img src=https://storage.googleapis.com/gweb-uniblog-publish-prod/original_images/SID_FB_001.gif height="450" width="600"></center>';
}
else
{
    header( "refresh:5;url=backco.php" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Identifiant ou mot de passe incorrect !</font></b><br><br></h1><p>error: could not check identical password between pass and hash.</p>

<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}


}

?>
