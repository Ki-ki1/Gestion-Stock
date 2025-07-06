<?php
session_start();
require_once '../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    if (empty($login) || empty($mdp)) {
        die("Veuillez remplir tous les champs.");
    }
    try {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $mdp === $user['mdp']) {
            $_SESSION['user'] = $user;
            // Vérification du rôle
            $id = $user['matricule'];
            // Utilisation de requêtes préparées pour vérifier le rôle
            $stmtAdmin = $pdo->prepare("SELECT * FROM Administrateurs WHERE id_admin = ?");
            $stmtAdmin->execute([$id]);
            $stmtAgent = $pdo->prepare("SELECT * FROM Agents WHERE id_agent = ?");
            $stmtAgent->execute([$id]);
            if ($stmtAdmin->fetch()) {
                $_SESSION['role'] = 'admin';
                header("Location: ../views/dashboard_admin.php");
            } elseif ($stmtAgent->fetch()) {
                $_SESSION['role'] = 'agent';
                header("Location: ../views/dashboard_agent.php");
            } else {
                $_SESSION['role'] = 'utilisateur';
                header("Location: ../views/dashboard_user.php");
            }
            exit;
        } else {
            echo "Login ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée.";
}
?>  