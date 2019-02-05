<?php
require_once dirname(__FILE__).'/../../../../config/config.php';
try {
  $bdd = new PDO('mysql:host='.getDBHost().';dbname=AirPodsFC', getDBUsername(), getDBPassword(), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch(Exception $e) {
  exit ('Erreur while connecting to database: '.$e->getMessage());
}


session_start();

if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
    // Récupération des données sur l'utilisateur
    $req = $bdd->prepare('SELECT * FROM administrators WHERE id = ?;');
    $req->execute(array($_SESSION['id']));
    $test = $req->fetch();

    $req1 = $bdd->prepare('SELECT * FROM licences');
    $req1->execute(array($_SESSION['id']));
    $count = $req1->rowCount();
    $test1 = $req1->fetch();

    $req2 = $bdd->prepare('SELECT * FROM licences WHERE status = ?');
    $req2->execute(array('banned'));
    $banned = $req2->rowCount();

    $req3 = $bdd->prepare('SELECT * FROM licences WHERE status = ?');
    $req3->execute(array('vip'));
    $vip = $req3->rowCount();

    $req4 = $bdd->prepare('SELECT * FROM licences WHERE status = ?');
    $req4->execute(array('red'));
    $red = $req4->rowCount();

    $req5 = $bdd->prepare('SELECT * FROM administrators');
    $req5->execute(array('red'));
    $admin = $req5->rowCount();

echo '<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AirPods FC</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/icheck/skins/all.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="../../index.php">
          <img src="../../images/logo.svg" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.php">
          <img src="../../images/logo-mini.svg" alt="logo" />
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-left header-links d-none d-md-flex">

          <li class="nav-item active">
            <a href="#" class="nav-link">
              <i class="mdi mdi-elevation-rise"></i>Administration</a>
          </li>

        </ul>
        <ul class="navbar-nav navbar-nav-right">


          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="profile-text">Bonjour ' . $test['nom'] . ' !</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <a class="dropdown-item p-0">
                <div class="d-flex border-bottom">
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                  </div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                    <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                  </div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                  </div>
                </div>
              </a>
              <a class="dropdown-item mt-2">
                Espace RGPD
              </a>
              <a class="dropdown-item">
                Changer le mot de passe
              </a>
              <a class="dropdown-item">
                Déconnexion
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="text-wrapper">
                  <p class="profile-name">' . $test['nom'] . '</p>
                  <div>
                    <small class="designation text-muted">Administrateur AirPods FC</small>
                    <span class="status-indicator online"></span>
                  </div>
                </div>
              </div>
              <button class="btn btn-success btn-block">Déclarer une licence
                <i class="mdi mdi-plus"></i>
              </button>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
              <i class="menu-icon mdi mdi-television"></i>
              <span class="menu-title">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="menu-icon mdi mdi-content-copy"></i>
              <span class="menu-title">Basic UI Elements</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/ui-features/typography.html">Typography</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/basic_elements.html">
              <i class="menu-icon mdi mdi-backup-restore"></i>
              <span class="menu-title">Form elements</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/charts/chartjs.html">
              <i class="menu-icon mdi mdi-chart-line"></i>
              <span class="menu-title">Charts</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/tables/basic-table.html">
              <i class="menu-icon mdi mdi-table"></i>
              <span class="menu-title">Tables</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/icons/font-awesome.html">
              <i class="menu-icon mdi mdi-sticker"></i>
              <span class="menu-title">Icons</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="menu-icon mdi mdi-restart"></i>
              <span class="menu-title">User Pages</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/login.html"> Login </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/register.html"> Register </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/error-404.html"> 404 </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/error-500.html"> 500 </a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->

      <div class="main-panel">
      <div class="col-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Déclarer une nouvelle licence</h4>
            <p class="card-description">
              Signez la licence éléctroniquement pour la rendre valide.
            </p>';

      if (isset($_GET['username2C'])){
        $req = $bdd->prepare('INSERT INTO licences(user, purchase, number, status) VALUES(:user, :purchase, :number, :status)');
        if ($_GET['type'] == "basic" || $_GET['type'] == "banned"){
          $number = rand(3000000, 9999999);
        } else if ($_GET['type'] == "vip" || $_GET['type'] == "red"){
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

          if ($_GET['type'] == "vip"){
            $reqfour = $bdd->prepare('UPDATE autoincrement SET vip = vip + 1;');
            $reqfour->execute();
            $countfour = $reqfour->rowCount();
            $number = $compare2 . str_pad($testtwo['vip'] + 1, 3, '0', STR_PAD_LEFT);
            $reqfive = $bdd->prepare('UPDATE autoincrement SET lastincrement = ?;');
            $send = $now->format('Y-m-d H:i:s');
            $reqfive->execute(array($date));

          }

          if ($_GET['type'] == "red"){
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
            'user' => $_GET['username2C'],
            'purchase' => $date,
            'number' => $number,
            'status' => $_GET['type']
            ));

            $count = $req->rowCount();

            if ($count == 0){
              if ($_GET['type'] == "red" && $countfour > 0){
                $reqsix = $bdd->prepare('UPDATE autoincrement SET red = red - 1;');
                $reqsix->execute();
              }
              if ($_GET['type'] == "vip" && $countfour > 0){
                $reqsix = $bdd->prepare('UPDATE autoincrement SET red = red - 1;');
                $reqsix->execute();
              }
            }

            echo '<h2 class="card-title"><br><br>La licence a bien été signée pour @' . $_GET['username2C'] . '!</h2><br><h3 class="card-description">LICENCE NUMÉRO ' . $number . '<br><a href="https://www.airpodsfc.fr/sign.php?id=' . $_GET['username2C'] . '"><img src="https://www.airpodsfc.fr/sign.php?id=' . $_GET['username2C'] . '" height="20%" width="30%" style="border-radius: 7px; overflow:hidden;"></a>';
          }


      echo '</div>
    </div>
  </div>
  </div>
    <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="container-fluid clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018
              <a href="http://www.bootstrapdash.com/" target="_blank">Bootstrapdash</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with
              <i class="mdi mdi-heart text-danger"></i>
            </span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>';
} else {
$_SESSION = array();
session_destroy();
setcookie('login', '');
setcookie('pass_hache', '');
header( "refresh:5;url=connexion.php" );
echo '<html></p><center>Connexion requise... Veuillez patienter.</center></html>';
}
?>
