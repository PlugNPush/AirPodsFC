<?php
require_once dirname(__FILE__).'/../../config/config.php';
try {
  $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch(Exception $e) {
  exit ('Erreur while connecting to database: '.$e->getMessage());
}



session_start();

if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
    if (isset($_GET['view']) || !isset($_GET['view'])){
    // Récupération des données sur l'utilisateur
    $req = $bdd->prepare('SELECT * FROM administrators WHERE id = ?;');
    $req->execute(array($_SESSION['id']));
    $test = $req->fetch();

    echo '<h1><center>Centre de contrôle du AirPods FC.</center></h1>';
    echo '<h2>Bienvenue ' . $test['nom'] . ' !<br>';

    $req1 = $bdd->prepare('SELECT * FROM licences');
    $req1->execute(array($_SESSION['id']));
    $count = $req1->rowCount();
    $test1 = $req1->fetch();

    echo 'Il y a actuellement ' . $count . ' licences en circulation.</h2><br>';

    echo '<table border="1" width="100%" ID="Table" style="margin: 0px;">
    <tr><td><center>Vérifier le statut d\'une licence</center></td><td><center>Déclarer une nouvelle licence</center></td><td><center>Modifier une licence existante</center></td></tr>
    <tr><td><center><form action="administration.php?view" method="post">
        <p>
        <input type="text" name="username" placeholder="Nom d\'utilisateur Twitter" required="yes"/>
        <input type="submit" value="Vérifier la licence" />
        </p>
        </form></center><br><center><form action="administration.php?view" method="post">
          <p>
          <input type="text" name="licence" placeholder="Numéro de licence" required="yes"/>
          <input type="submit" value="Vérifier la licence" />
          </p>
          </form></center>';

          if(isset($_POST['username'])){
            $req = $bdd->prepare('SELECT * FROM licences WHERE user = ?;');
            $req->execute(array($_POST['username']));
            $test = $req->fetch();

            $date = date('Y-m-d H:i:s');

            $compareddate = new DateTime($test["purchase"]);
            $now = new DateTime();

            if (isset($test['id'])){
            echo '<p>Titulaire de la licence : @' . $test['user'];
            echo '<br>Titulaire depuis ' . $compareddate->diff($now)->format("%y ans, %m mois, %d jours, %h heures et %i minutes");
            echo '<br> IMMATRICULATION DE LA LICENCE : ' . ltrim($test['number'], '0');
            echo '<br> Statut de la licence : ' . $test['status'];
            $dd = $compareddate->diff($now)->format("%y");
            if ($test['status'] == "basic"){
              if ($dd < 1){
                echo '<br>LICENCE AIRPODS FC GRISE DÉLIVRÉE';
              } else if ($dd < 2){
                echo '<br>LICENCE AIRPODS FC BRONZE DÉLIVRÉE';
              }
             else if ($dd < 3){
              echo '<br>LICENCE AIRPODS FC ARGENT DÉLIVRÉE';
            }
             else if ($dd < 4){
              echo '<br>LICENCE AIRPODS FC OR DÉLIVRÉE';
            }
             else if ($dd >= 4){
              echo '<br>LICENCE AIRPODS FC PLATINE DÉLIVRÉE';
            }}
            else if ($test['status'] == "vip"){
              echo '<br>LICENCE AIRPODS FC VIP DÉLIVRÉE';
            }
            else if ($test['status'] == "red"){
              echo '<br>LICENCE AIRPODS FC (RED) DÉLIVRÉE';
            }
            else if ($test['status'] == "banned"){
              echo '<br>UTILISATEUR BANNI DU AIRPODS FC';
            }
          }
          else {
            echo '<br><p>Aucune licence n\'a été délivrée à @' . ltrim($_POST['username'], '0') . '.</p>';
          }} else if(isset($_POST['licence'])){
            $req = $bdd->prepare('SELECT * FROM licences WHERE number = ?;');
            $req->execute(array($_POST['licence']));
            $test = $req->fetch();

            $date = date('Y-m-d H:i:s');

            $compareddate = new DateTime($test["purchase"]);
            $now = new DateTime();
          if (isset($test["id"])){
            echo '<p>Titulaire de la licence : @' . $test['user'];
            echo '<br>Titulaire depuis ' . $compareddate->diff($now)->format("%y ans, %m mois, %d jours, %h heures et %i minutes");
            echo '<br> IMMATRUCULATION DE LA LICENCE : ' . ltrim($test['number'], '0');
            echo '<br> Statut de la licence : ' . $test['status'];
            $dd = $compareddate->diff($now)->format("%y");

            if ($test['status'] == "basic"){
              if ($dd < 1){
                echo '<br>LICENCE AIRPODS FC GRISE DÉLIVRÉE';
              } else if ($dd < 2){
                echo '<br>LICENCE AIRPODS FC BRONZE DÉLIVRÉE';
              }
             else if ($dd < 3){
              echo '<br>LICENCE AIRPODS FC ARGENT DÉLIVRÉE';
            }
             else if ($dd < 4){
              echo '<br>LICENCE AIRPODS FC OR DÉLIVRÉE';
            }
             else if ($dd >= 4){
              echo '<br>LICENCE AIRPODS FC PLATINE DÉLIVRÉE';
            }}
            else if ($test['status'] == "vip"){
              echo '<br>LICENCE AIRPODS FC VIP DÉLIVRÉE';
            }
            else if ($test['status'] == "red"){
              echo '<br>LICENCE AIRPODS FC (RED) DÉLIVRÉE';
            }
            else if ($test['status'] == "banned"){
              echo '<br>MEMBRE BANNI DU AIRPODS FC';
            }}
            else {
              echo '<br><p>Aucune licence n\'a été délivrée avec le numéro ' . ltrim($_POST['licence'], '0') . '.</p>';
            }}

          echo '</td><td><center><form action="administration.php?view" method="post">
              <p>
              <input type="text" name="username2C" placeholder="Nom d\'utilisateur Twitter" required="yes"/>
              <input type="date" name="date" placeholder="Date d\'achat yyyy-mm-dd" required="yes"/>
              <select name="type">
          <option value="basic">Classique</option>
          <option value="vip">VIP</option>
          <option value="red">(RED)</option>
          <option value="banned">Banni</option>
              </select>

              <br><input type="submit" value="Signer la licence" />
              </p>
              </form></center>';

              if (isset($_POST['username2C']) && isset($_POST['date'])){
                $req = $bdd->prepare('INSERT INTO licences(user, purchase, number, status) VALUES(:user, :purchase, :number, :status)');
                if ($_POST['type'] == "basic" || $_POST['type'] == "banned"){
                  $number = rand(3000000, 9999999);
                } else if ($_POST['type'] == "vip" || $_POST['type'] == "red"){
                  // 1st step is to check year gap

                  $reqtwo = $bdd->prepare('SELECT * FROM autoincrement;');
                  $reqtwo->execute();
                  $testtwo = $reqtwo->fetch();

                  $date = date('Y-m-d H:i:s');

                  $compareddate = new DateTime($testtwo["lastincrement"]);
                  $now = new DateTime();

                  $compare = $compareddate->format('Y');
                  $compare2 = $now->format('Y');

                  if ($compare != $compare2){
                    $reqthree = $bdd->prepare('UPDATE autoincrement SET vip = 0;');
                    $reqthree->execute();
                    $reqthree = $bdd->prepare('UPDATE autoincrement SET red = 0;');
                    $reqthree->execute();
                  }

                  if ($_POST['type'] == "vip"){
                    $reqfour = $bdd->prepare('UPDATE autoincrement SET vip = vip + 1;');
                    $reqfour->execute();
                    $countfour = $reqfour->rowCount();
                    $number = $compare2 . str_pad($testtwo['vip'] + 1, 3, '0', STR_PAD_LEFT);
                    $reqfive = $bdd->prepare('UPDATE autoincrement SET lastincrement = ?;');
                    $send = $now->format('Y-m-d H:i:s');
                    $reqfive->execute(array($date));

                  }

                  if ($_POST['type'] == "red"){
                    $reqfour = $bdd->prepare('UPDATE autoincrement SET red = red + 1;');
                    $reqfour->execute();
                    $countfour = $reqfour->rowCount();
                    $number = $compare2 . "PR" . str_pad($testtwo['red'] + 1, 3, '0', STR_PAD_LEFT);
                    $reqfive = $bdd->prepare('UPDATE autoincrement SET lastincrement = ?;');
                    $send = $now->format('Y-m-d H:i:s');
                    $reqfive->execute(array($date));

                  }



                }

                    $req->execute(array(
                    'user' => $_POST['username2C'],
                    'purchase' => $date,
                    'number' => $number,
                    'status' => $_POST['type']
                    ));

                    $count = $req->rowCount();

                    if ($count == 0){
                      if ($_POST['type'] == "red" && $countfour > 0){
                        $reqsix = $bdd->prepare('UPDATE autoincrement SET red = red - 1;');
                        $reqsix->execute();
                      }
                      if ($_POST['type'] == "vip" && $countfour > 0){
                        $reqsix = $bdd->prepare('UPDATE autoincrement SET red = red - 1;');
                        $reqsix->execute();
                      }
                    }

                echo "<center>Licence signée !</center>";
              }

              echo '</td><td>Section 3</td></tr>

     </table>';
    echo '<br><br> <p>Ouvrir l’espace avancé</p>';
    echo '<br><a href=\'logout.php\'>Déconnexion</a>';

}

}
else {
$_SESSION = array();
session_destroy();
setcookie('login', '');
setcookie('pass_hache', '');
header( "refresh:5;url=connexion.php" );
echo '<html></p><center>Connexion requise... Veuillez patienter.</center></html>';
}



/*

// ... TO BE DELETED AFTER THIS LINE.

    if (isset($_GET['edit'])){
    if (isset($_POST['mot_de_passe'])){
$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{
    if (isset($_POST['conf_mot_de_passe']) AND isset($_POST['nouv_mot_de_passe'])) {
    if ($_POST['nouv_mot_de_passe'] == $_POST['conf_mot_de_passe'] AND $_POST['nouv_mot_de_passe'] != ''){
    $pass_hache = password_hash($_POST['conf_mot_de_passe'], PASSWORD_DEFAULT);
    $change = $bdd->prepare('UPDATE utilisateur SET mdp = ? WHERE id = ?');
    $change->execute(array($pass_hache, $test['id']));
    }
    }
    if (isset($_POST['nouveau_email']) AND $_POST['nouveau_email'] != ''){
    $changem = $bdd->prepare('UPDATE utilisateur SET mail = ? WHERE id = ?');
    $changem->execute(array($_POST['nouveau_email'], $test['id']));
    }
    if (isset($_POST['nouveau_nom']) AND $_POST['nouveau_nom'] != ''){
    $changep = $bdd->prepare('UPDATE utilisateur SET nom = ? WHERE id = ?');
    $changep->execute(array($_POST['nouveau_nom'], $test['id']));
    }
    if (isset($_POST['nouveau_serveur']) AND $_POST['nouveau_serveur'] != ''){
    $changesales = $bdd->prepare('UPDATE utilisateur SET adressFTP = ? WHERE id = ?');
    $changesales->execute(array($_POST['nouveau_serveur'], $test['id']));
    }
    if (isset($_POST['nouveau_user']) AND $_POST['nouveau_user'] != ''){
    $changebuys = $bdd->prepare('UPDATE utilisateur SET userFTP = ? WHERE id = ?');
    $changebuys->execute(array($_POST['nouveau_user'], $test['id']));
    }
    if (isset($_POST['nouveau_mdpFTP']) AND $_POST['nouveau_mdpFTP'] != ''){
    $changeblockchain = $bdd->prepare('UPDATE utilisateur SET mdpFTP = ? WHERE id = ?');
    $changeblockchain->execute(array($_POST['nouveau_mdpFTP'], $test['id']));
    }
    $_SESSION = array();
session_destroy();

// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');
    header( "refresh:10;url=connexion.php" );
    echo '<center><h1><b><font size="7" face="verdana">Veuillez patienter...</font></b></h1><p><font size="5" face="verdana">Nous appliquons les changements de votre compte.</font><br>Updating data in the database, this might take up to 15 seconds.</p><img src="https://assets.materialup.com/uploads/53454721-b218-43dc-85ca-cc338ac1915d/webview.gif"></center>';
}
else
{
    header( "refresh:5;url=moncompte.php?edit" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Identifiant ou mot de passe incorrect !</font></b><br><br></h1><p>error: could not check identical password between pass and hash.</p>

<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}

}
        else {
        echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>MODIFICATION D\'INFORMATIONS</title>
    </head>
    <body>
        <p>Merci de saisir les informations à modifier..</p>
        <form action="moncompte.php?edit" method="post">
            <p>
                <br>Obligatoire pour n\'importe quelle modification : <br>
            <input type="password" name="mot_de_passe" placeholder=\'Mot de passe actuel\' required="yes"/>
            <br> Entrez ici uniquement les valeurs à changer : <br>
            <input type="password" name="nouv_mot_de_passe" placeholder=\'Nouveau mot de passe\'/>
            <input type="password" name="conf_mot_de_passe" placeholder=\'Confirmation du nouveau mot de passe\'/>
            <br><input type="text" name="nouveau_nom" placeholder="Nouveau nom" />
            <br><input type="email" name="nouveau_email" placeholder="Nouvelle adresse mail" />
            <br><br>
            <p>Informations sur le serveur FTP</p>
            <br><input type="text" name="nouveau_serveur" placeholder="Nouveau serveur FTP" />
            <br><input type="text" name="nouveau_user" placeholder="Nouveau nom d\'utilisateur FTP" />
            <br><input type="text" name="nouveau_mdpFTP" placeholder="Nouveau mot de passe FTP" />
            <br><input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';
        }
    }

    if (isset($_GET['delete'])){
    if (isset($_POST['mot_de_passe'])){
$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{
    $delete = $bdd->prepare('DELETE FROM utilisateur WHERE id = ?');
    $delete->execute(array($test['id']));

    $deletea = $bdd->prepare('DELETE FROM donnees WHERE id = ?');
    $deletea->execute(array($test['id']));

    $deleteb = $bdd->prepare('DELETE FROM partage WHERE id = ?');
    $deleteb->execute(array($test['id']));

    $deletec = $bdd->prepare('DELETE FROM partage WHERE idreceveur = ?');
    $deletec->execute(array($test['id']));

    $_SESSION = array();
session_destroy();

// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');



    header( "refresh:10;url=index.php" );
    echo '<center><h1><b><font size="7" face="verdana">Suppression du compte...</font></b></h1><p><font size="5" face="verdana">Toutes vos données et les données associées à ce compte seront supprimés.</font><br>Removing data to the database, this might take up to 15 seconds.</p><img src=https://assets.materialup.com/uploads/53454721-b218-43dc-85ca-cc338ac1915d/webview.gif ></center>';

}
else
{
    header( "refresh:5;url=moncompte.php?delete" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Mot de passe incorrect !</font></b><br><br></h1><p>error: could not check identical password between pass and hash.</p>

<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}}
        else {
        echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>SUPPRIMER VOTRE COMPTE</title>
    </head>
    <body>
        <p>Voulez-vous vraiment définitivement supprimer votre compte ?</p>
        <form action="moncompte.php?delete" method="post">
            <p>
            <input type="password" name="mot_de_passe" placeholder=\'Mot de passe\'/>
            <br><input type="checkbox" name="confirmation" value="yes" required="yes" /> Je confirme vouloir supprimer mon compte et toutes les données associées à celui-ci, et je comprends que cette opération est irréversible.<br>
            <input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';}
    }

    if (isset($_GET['view'])){
if (isset($_POST['mot_de_passe'])){


$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{

    echo '<h1><b>Vos données confidentielles.</b></h1>';
    echo '<p><a href=/home.php>Retour à l\'accueil</a></p>';
    echo '<br><p>Nom : ', $test['nom'];
    echo '<br>Adresse e-mail : ', $test['mail'];
    echo '<br>Identifiant d\'utilisateur unique : ', $test['id'];
    echo '<br>Hash du mot de passe : ', $test['mdp'];
    echo '<br>Adresse du serveur FTP : ', $test['adressFTP'];
    echo '<br>Nom d\'utilisateur FTP : ', $test['userFTP'];
    echo '<br>Mot de passe FTP : ', $test['mdpFTP'];
    echo '<br><a href=/moncompte.php?edit>Modifier des informations</a></p>';
    echo '<br><br><a href=/moncompte.php?delete>Supprimer définitivement le compte</a></p>';

}
    else {
    header( "refresh:5;url=moncompte.php?view" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Mot de passe incorrect !</font></b><br><br></h1><p>error: could not verify given pass with hash.</p>

<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
    }} else {
    echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>MON COMPTE</title>
    </head>
    <body>
        <p>Vous devez vous authentifier pour continuer.</p>
        <form action="moncompte.php?view" method="post">
            <p>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required="yes"/>
            <input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';
}}}
else {
$_SESSION = array();
session_destroy();
setcookie('login', '');
setcookie('pass_hache', '');
header( "refresh:5;url=connexion.php" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Vous n\'êtes pas connecté !</font></b><br><br></h1><p>error: could not check session variable.</p>

<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}
*/
?>
