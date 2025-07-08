<?php
// Démarrer la session
session_start();

// Détruire toutes les données de session
$_SESSION = array();

// Si vous souhaitez détruire complètement la session, effacez également le cookie de session.
// Note : Cela détruira la session et pas seulement les données de session.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion ou une autre page
header("Location: index.php"); // Assurez-vous que "index.php" est le bon chemin vers votre page de connexion ou d'accueil
exit();
?>
