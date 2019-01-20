<?php
// Connexion à la base de données
require_once dirname(__FILE__).'/../../../config/config.php';
try {
  $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch(Exception $e) {
  exit ('Erreur while connecting to database: '.$e->getMessage());
}
// Démarrage de la session
session_start();

    // Zone de menu statique
echo '<h1><b><font size="15" face="verdana">AirPods FC </font></b></h1>';
echo '<p align="right"> <a href=/administration.php?view>Administration</a></p>';

echo'<br><br><center><form action="licence.php" method="post">
    <p>
    <input type="text" name="username" placeholder="Nom d\'utilisateur Twitter (sans @)" required="yes"/>
    <input type="submit" value="Vérifier la licence" />
    </p>
    </form></center>';
  // Préparation de l'environement FTP

  echo'<br><br><center><form action="licence-reverse.php" method="post">
      <p>
      <input type="text" name="licence" placeholder="Numéro de licence" required="yes"/>
      <input type="submit" value="Vérifier la licence" />
      </p>
      </form></center>';




?>
