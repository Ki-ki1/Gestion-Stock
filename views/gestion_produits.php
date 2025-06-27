<?php
require_once '../config/db.php';

// Fonction pour récupérer tous les produits
function getAllProduits() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Produits");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer un produit par son ID
function getProduitById($idP) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Produits WHERE idP = ?");
    $stmt->execute([$idP]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour ajouter ou modifier un produit
function saveProduit($idP, $designation, $seuil) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Produits WHERE idP = ?");
    $stmt->execute([$idP]);
    $exists = $stmt->fetchColumn() > 0;

    if ($exists) {
        $stmt = $pdo->prepare("UPDATE Produits SET designation = ?, seuil = ? WHERE idP = ?");
        return $stmt->execute([$designation, $seuil, $idP]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Produits (idP, designation, seuil) VALUES (?, ?, ?)");
        return $stmt->execute([$idP, $designation, $seuil]);
    }
}

// Fonction pour supprimer un produit
function supprimerProduit($idP) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Produits WHERE idP = ?");
    return $stmt->execute([$idP]);
}

// Initialiser les variables pour le formulaire
$designation = $idP = $seuil = '';
$action = $_GET['action'] ?? '';
$idP = $_GET['idP'] ?? null;

// Récupérer tous les produits
$produits = getAllProduits();

if ($action === 'modifier' && $idP) {
    $produit = getProduitById($idP);
    if ($produit) {
        $idP = $produit['idP'];
        $designation = $produit['designation'];
        $seuil = $produit['seuil'];
    }
}

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idP = $_POST['idP'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $seuil = $_POST['seuil'] ?? '';
    $action = $_POST['action'] ?? '';

    if (empty($designation) || empty($idP) || empty($seuil)) {
        echo "<script>alert('Tous les champs sont obligatoires.');</script>";
    } else {
        saveProduit($idP, $designation, $seuil);
        header("Location: gestion_produits.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'supprimer') {
    $idP = $_POST['idP'] ?? null;
    if ($idP) {
        supprimerProduit($idP);
        echo "<script>alert('Produit supprimé avec succès.');</script>";
    }
    header("Location: gestion_produits.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0c2461;
            --primary-light: #1e3799;
            --secondary: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            display: flex;
            min-height: 100vh;
            color: var(--dark);
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            z-index: 100;
            transition: all 0.3s ease;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo img {
            height: 60px;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .nav-links {
            padding: 0 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 14px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item i,
        .nav-item img {
            font-size: 20px;
            margin-right: 15px;
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .nav-item span {
            font-size: 16px;
            font-weight: 500;
            color: white;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }

        .dashboard-title {
            margin-bottom: 25px;
        }

        .dashboard-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid var(--border);
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: var(--light-gray);
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            margin-bottom: 20px;
        }

        .action-buttons a {
            padding: 12px 25px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }

        .form-container {
            margin-top: 20px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 600px;
        }

        .form-container h3 {
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 22px;
        }

        .form-container label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container textarea {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .form-container input[type="submit"] {
            padding: 12px 25px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            color: #666;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="../icon\images.jpg" alt="Medis Logo">
            <h1>Gestion Stock</h1>
        </div>
        <nav class="nav-links">
            <div class="nav-item">
                <i class="fas fa-file-invoice"></i>
                <span><a href="gestion_factures.php" style="color: white;">Factures</a></span>
            </div>
            <div class="nav-item active">
                <i class="fas fa-box"></i>
                <span><a href="gestion_produits.php" style="color: white;">Produits</a></span>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-title">
            <h2>Gestion des Produits – Laboratoires Medis</h2>
        </div>

        <?php if ($action === 'ajouter' || $action === 'modifier'): ?>
            <div class="form-container">
                <h3><?= $action === 'modifier' ? 'Modifier un Produit' : 'Ajouter un Produit' ?></h3>
                <form method="post" action="">
                    <input type="hidden" name="action" value="<?= $action ?>">
                    <label for="idP">ID:</label>
                    <input type="text" id="idP" name="idP" value="<?= htmlspecialchars($idP) ?>" required>
                    <label for="designation">Désignation:</label>
                    <textarea id="designation" name="designation" required><?= htmlspecialchars($designation) ?></textarea>
                    <label for="seuil">Seuil:</label>
                    <input type="number" id="seuil" name="seuil" value="<?= htmlspecialchars($seuil) ?>" required>
                    <input type="submit" value="<?= $action === 'modifier' ? 'Modifier' : 'Ajouter' ?>">
                </form>
            </div>
        <?php else: ?>
            <div class="form-container">
                <div class="action-buttons">
                    <a href="gestion_produits.php?action=ajouter">➕ Ajouter un Produit</a>
                </div>
                <h3>📦 Liste des Produits</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Désignation</th>
                            <th>Seuil</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= htmlspecialchars($produit['idP']) ?></td>
                            <td><?= htmlspecialchars($produit['designation']) ?></td>
                            <td><?= htmlspecialchars($produit['seuil']) ?></td>
                            <td>
                                <form method="post" action="" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="idP" value="<?= $produit['idP'] ?>">
                                    <button type="submit" style="background-color:#e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 5px;">🗑 Supprimer</button>
                                </form>
                                <a href="gestion_produits.php?action=modifier&idP=<?= $produit['idP'] ?>" style="display: inline;">
                                    <button style="background-color:#1e3799; color: white; padding: 5px 10px; border: none; border-radius: 5px;">✏️ Modifier</button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <footer>
            <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
            <p>📍 Adresse : Rue de l'Innovation, Nabeul, Tunisie</p>
            <p>📞 Téléphone : +216 72 000 000 | 📧 Email : contact@medis.com.tn</p>
        </footer>
    </main>
</body>
</html>
