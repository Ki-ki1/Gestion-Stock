<?php
require_once '../config/db.php';

class Produit {
    public static function getAllProduits() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM Produits");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching products: " . $e->getMessage());
            return [];
        }
    }

    public static function getQuantiteApprouvee($idP) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT SUM(quantite) AS total FROM demandes WHERE id_produit = ? AND etat = 'Approuvée'");
            $stmt->execute([$idP]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching approved quantity for product $idP: " . $e->getMessage());
            return 0;
        }
    }
}
?>
