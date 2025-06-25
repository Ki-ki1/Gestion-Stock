<?php
$host = 'localhost';
$db = 'gestion_systeme';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    echo("Erreur connexion DB : " . $e->getMessage());
}
?>