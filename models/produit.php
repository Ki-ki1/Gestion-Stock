<?php
require_once '../config/db.php';

class Produit {
    public static function getAllProduits() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM Produits");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            error_log("Error fetching products: " . $e->getMessage());
            return []; // Return an empty array if there's an error
        }
    }
}
?>
