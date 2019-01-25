<?php
session_start();

// Suppression des variables de session et de la session
$_SESSION = array();
session_destroy();

// Suppression des cookies de connexion automatique
setcookie('id', '');
setcookie('email', '');
setcookie('pass_hache', '');

echo 'Déconnexion effective! Au revoir et à bientôt.';

?>