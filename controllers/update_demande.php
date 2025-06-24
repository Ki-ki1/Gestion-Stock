<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numD = $_POST['numD'] ?? null;
    $etat = $_POST['etat'] ?? null;

    if ($numD && $etat) {
        try {
            $stmt = $pdo->prepare("UPDATE Demandes SET etat = ? WHERE numD = ?");
            $stmt->execute([$etat, $numD]);
            echo "✅ État de la demande mis à jour avec succès.";
        } catch (PDOException $e) {
            echo "❌ Erreur lors de la mise à jour de l'état de la demande: " . $e->getMessage();
        }
    } else {
        echo "❌ Erreur: Veuillez remplir tous les champs correctement.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
