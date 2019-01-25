<?php
require_once dirname(__FILE__).'/../../config/config.php';
try {
  $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch(Exception $e) {
  exit ('Erreur while connecting to database: '.$e->getMessage());
}


session_start();
if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
  // Hachage du mot de passe
  $pass_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

  // Vérification des identifiants
  $req = $bdd->prepare('SELECT * FROM administrators WHERE email = ?;');
  $req->execute(array($_SESSION['email']));
  $test = $req->fetch();


  $verify = password_verify($_POST['mdp'], $test['password']);
  if ($verify)
  {
    $req = $bdd->prepare('DELETE FROM administrators WHERE email = ?;');
    $req->execute(array($_SESSION['email']));

    $_SESSION = array();
    session_destroy();
    setcookie('id', '');
    setcookie('email', '');
    setcookie('pass_hache', '');
    header( "refresh:5;url=\"https://www.airpodsfc.fr\"" );
    echo '<center><h1><b><font size="7" face="verdana">Au revoir ', $test['nom'], '.</font></b></h1><br>Writing data to the database, this might take up to 15 seconds.</p><img src=https://storage.googleapis.com/gweb-uniblog-publish-prod/original_images/SID_FB_001.gif height="450" width="600"></center>';
  } else {
    header( "refresh:5;url=pages/forms/rgpd.php" );
    echo '<center>Mot de passe incorrect. Veuillez réessayer...</center>';
  }

} else {
  $_SESSION = array();
  session_destroy();
  setcookie('login', '');
  setcookie('pass_hache', '');
  header( "refresh:5;url=connexion.php" );
  echo '<html></p><center>Connexion requise... Veuillez patienter.</center></html>';

}


?>
