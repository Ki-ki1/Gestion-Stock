<?php
require_once '../config/db.php';

class Demande {
    public static function getAllDemandes() {
        global $pdo;
        try {
            $stmt = $pdo->query("
                SELECT d.*, u.nom AS user_nom, u.prenom AS user_prenom, p.designation AS produit_designation
                FROM Demandes d
                JOIN Utilisateurs u ON d.utilisateur_id = u.matricule
                LEFT JOIN Produits p ON d.idProduit = p.idP
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching demands: " . $e->getMessage());
            return [];
        }
    }
}
?>
