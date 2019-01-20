<?php
require_once dirname(__FILE__).'/../../../config/config.php';
if(!isset($_POST['mdp'])){
echo'
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>CONNEXION</title>
    </head>
    <body>
        <p>Administrateur du AirPods FC, connectez-vous.</p>
        <form action="connexion.php" method="post">
            <p>
            <input type="email" name="mail" placeholder="Adresse mail" required="yes"/>
            <input type="password" name="mdp" placeholder=\'Mot de passe\' required="yes"/>
            <input type="submit" value="Valider" />
            <br> Pas encore inscrit ? <a href=/inscription.php>Inscrivez-vous maintenant !</a>
            </p>
            </form>
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
    header( "refresh:10;url=backhome.php" );
    echo '<center><h1><b><font size="7" face="verdana">Bienvenue parmi nous ', $test['nom'], ' !</font></b></h1><br>Reading data from the database, this might take up to 15 seconds.</p><img src=https://storage.googleapis.com/gweb-uniblog-publish-prod/original_images/SID_FB_001.gif height="450" width="600"></center>';
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
