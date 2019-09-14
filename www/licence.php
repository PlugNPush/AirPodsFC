<?php
// Connexion à la base de données
require_once dirname(__FILE__).'/../../config/config.php';
try {
  $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch(Exception $e) {
  exit ('Erreur while connecting to database: '.$e->getMessage());
}
// Démarrage de la session
session_start();

    // Zone de menu statique
echo '<h1><b><font size="15" face="verdana">Groupe MINASTE - EFV AirPods FC</font></b></h1>';
echo '<p>Bienvenue sur l\'Espace de Fin de Vie du AirPods FC. Nous construisons tous nos projets autour d\'une valeur clé : la liberté. Nous avons une vision d\'un Internet libre, auquel on doit pouvoir faire confiance et sur lequel ses acteurs se doivent d\'être transparents avec ses utilisateurs. Nous souhaitons un Internet où quand une entreprise annonce supprimer des données, elle le fait véritablement, sans astérisque. Aucune revente, aucune cession, aucun stockage ailleurs, chez nous supprimer = supprimer. Pour que vous puissiez en être absolument certain, nous avons créé l\'EFV (Espace de Fin de Vie). Il s\'agit d\'une page qui restera active un certain temps après la suppression des données pour que vous puissiez vérifier en temps réel que vos données ont bien été supprimés. Nous espérons vous revoir bientôt.</p>';

if(isset($_POST['username'])){
  $req = $bdd->prepare('SELECT * FROM licences WHERE user = ?;');
  $req->execute(array($_POST['username']));
  $test = $req->fetch();

  $date = date('Y-m-d H:i:s');

  $compareddate = new DateTime($test["purchase"]);
  $now = new DateTime();

  if (isset($test['id'])){
  echo '<h1><center>LICENCE AUTHENTIQUE AIRPODS FC</center></h1>';
  echo '<p>Titulaire de la licence : @' . $test['user'];
  echo '<br>Titulaire depuis ' . $compareddate->diff($now)->format("%y ans, %m mois, %d jours, %h heures et %i minutes");
  echo '<br> IMMATRUCULATION DE LA LICENCE : ' . ltrim($test['number'], '0');
  echo '<br> Statut de la licence : ' . $test['status'];
  $dd = $compareddate->diff($now)->format("%y");
  if ($test['status'] == "basic"){
    if ($dd < 1){
      echo '<br><h2>LICENCE AIRPODS FC GRISE DÉLIVRÉE</h2>';
    } else if ($dd < 2){
      echo '<br><h2>LICENCE AIRPODS FC BRONZE DÉLIVRÉE</h2>';
    }
   else if ($dd < 3){
    echo '<br><h2>LICENCE AIRPODS FC ARGENT DÉLIVRÉE</h2>';
  }
   else if ($dd < 4){
    echo '<br><h2>LICENCE AIRPODS FC OR DÉLIVRÉE</h2>';
  }
   else if ($dd >= 4){
    echo '<br><h2>LICENCE AIRPODS FC PLATINE DÉLIVRÉE</h2>';
  }}
  else if ($test['status'] == "vip"){
    echo '<br><h2>LICENCE AIRPODS FC VIP DÉLIVRÉE</h2>';
  }
  else if ($test['status'] == "red"){
    echo '<br><h2>LICENCE AIRPODS FC (RED) DÉLIVRÉE</h2>';
  }
  else if ($test['status'] == "banned"){
    echo '<br><h2>VOUS AVEZ ÉTÉ BANNI DU AIRPODS FC</h2>';
  }
}
else {
  echo '<br><h2>LICENCE NON TROUVÉE !</h2>';
  echo '<br><p>Aucune licence n\'a été délivrée par l\'équipe de validation du AirPods FC à @' . ltrim($_POST['username'], '0') . '.</p>';
}

} else {
  echo'<br><br><center><form action="licence.php" method="post">
      <p>
      <input type="text" name="username" placeholder="Nom d\'utilisateur Twitter (sans @)" required="yes"/>
      <input type="submit" value="Vérifier la licence" />
      </p>
      </form></center>';
}
  function dateDifference($date_1, $date_2, $differenceFormat = '%a' )
  {
      //$datetime1 = date_create($date_1);
      //$datetime2 = date_create($date_1);

      $interval = date_diff($date_1, $date_2);

      return $interval->format($differenceFormat);

  }
?>
