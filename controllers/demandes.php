<?php
require_once '../models/demande.php';

// Fetch all demands
$demandes = Demande::getAllDemandes();

// Debug: Check if demands are fetched correctly
var_dump($demandes); // This will help you see what data is being fetched

// Include the view and pass the $demandes variable
include '../views/demandes.php';
?>
