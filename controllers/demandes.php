<?php
require_once '../config/db.php';
session_start(); // si l'utilisateur est connecté

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $produits = $_POST['produits'];
    $quantites = $_POST['quantites'];

    // Exemple de récupération de l'utilisateur connecté
    $utilisateur_id = $_SESSION['matricule'] ?? 1; // à adapter selon ton système de connexion

    if (count($produits) !== count($quantites)) {
        die("Erreur : les produits et les quantités ne correspondent pas.");
    }

    try {
        $pdo->beginTransaction();

        for ($i = 0; $i < count($produits); $i++) {
            $stmt = $pdo->prepare("INSERT INTO demandes (quantite, etat, description, utilisateur_id, idProduit) VALUES (?, 'En attente', ?, ?, ?)");
            $stmt->execute([
                $quantites[$i],
                $description,
                $utilisateur_id,
                $produits[$i]
            ]);
        }

        $pdo->commit();
        echo "<script>alert('Demande envoyée avec succès !'); window.location.href='../views/dashboard_user.php';</script>";
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors de l'enregistrement de la demande : " . $e->getMessage());
    }
} else {
    echo "Méthode non autorisée.";
}
?>
