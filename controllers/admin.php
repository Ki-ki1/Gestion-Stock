<?php
require_once '../models/Utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];

        Utilisateur::createUtilisateur($nom, $prenom, $login, $mdp);
        header("Location: ../views/dashboard_admin.php");
    }
}
?>
