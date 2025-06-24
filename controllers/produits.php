<?php
require_once '../models/Produit.php';

// Fetch all products
$produits = Produit::getAllProduits();

// Debug: Check if products are fetched correctly
var_dump($produits); // This will help you see what data is being fetched

// Include the view and pass the $produits variable
include '../views/produits.php';
?>
