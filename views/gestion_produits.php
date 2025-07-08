<?php
require_once '../config/db.php';

function getAllProduits() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Produits ORDER BY idP ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduitById($idP) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Produits WHERE idP = ?");
    $stmt->execute([$idP]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function saveProduit($idP, $designation, $seuil, $quantite) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Produits WHERE idP = ?");
    $stmt->execute([$idP]);
    $exists = $stmt->fetchColumn() > 0;

    if ($exists) {
        $stmt = $pdo->prepare("UPDATE Produits SET designation = ?, seuil = ? WHERE idP = ?");
        return $stmt->execute([$designation, $seuil, $idP]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Produits (idP, designation, seuil, quantite) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$idP, $designation, $seuil, $quantite]);
    }
}

function supprimerProduit($idP) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Produits WHERE idP = ?");
    return $stmt->execute([$idP]);
}

$designation = $idP = $seuil = $quantite = '';
$action = $_GET['action'] ?? '';
$idP = $_GET['idP'] ?? null;
$produits = getAllProduits();

if ($action === 'modifier' && $idP) {
    $produit = getProduitById($idP);
    if ($produit) {
        $idP = $produit['idP'];
        $designation = $produit['designation'];
        $seuil = $produit['seuil'];
        $quantite = $produit['quantite'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idP = $_POST['idP'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $seuil = $_POST['seuil'] ?? '';
    $quantite = $_POST['quantite'] ?? '';
    $action = $_POST['action'] ?? '';

    if (empty($designation) || empty($idP) || empty($seuil)) {
        echo "<script>alert('Les champs ID, Désignation et Seuil sont obligatoires.');</script>";
    } else {
        saveProduit($idP, $designation, $seuil, $quantite);
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
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0c2461;
            --primary-light: #1e3799;
            --light-gray: #e9ecef;
            --border: #dee2e6;
            --danger: #e74c3c;
        }
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            margin: 0;
            display: flex;
            background-color: #f5f7fb;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
        }
        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }
        .logo img {
            height: 60px;
            margin-right: 10px;
        }
        .logo h1 {
            font-size: 20px;
        }
        .nav-links {
            padding: 0 15px;
        }
        .nav-item {
            padding: 14px 15px;
            display: flex;
            align-items: center;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid var(--border);
            text-align: left;
        }
        th {
            background-color: var(--light-gray);
            cursor: pointer;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        input[type="submit"], button, .action-buttons a {
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        .action-buttons {
            margin-bottom: 20px;
        }
        .search-input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .button-container {
            display: flex;
            gap: 10px;
        }
        .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .button-container input[type="submit"] {
            background: var(--primary);
            color: white;
        }
        .button-container button[type="button"] {
            background: var(--danger);
            color: white;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="../icon/images.jpg" alt="Medis Logo">
            <h1>Gestion Stock</h1>
        </div>
        <nav class="nav-links">
            <div class="nav-item">
                <i class="fas fa-users"></i>
                <a href="gestion_utilisateurs.php">Utilisateurs</a>
            </div>
            <div class="nav-item active">
                <i class="fas fa-box"></i>
                <a href="gestion_produits.php">Produits</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-file-invoice"></i>
                <a href="gestion_factures.php">Factures</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <a href="logout.php">Déconnexion</a>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h2>Gestion des Produits – Laboratoires Medis</h2>
        <?php if ($action === 'ajouter' || $action === 'modifier'): ?>
            <div class="form-container">
                <h3><?= $action === 'modifier' ? 'Modifier un Produit' : 'Ajouter un Produit' ?></h3>
                <form method="post">
                    <input type="hidden" name="action" value="<?= $action ?>">
                    <label>ID Produit</label>
                    <input type="text" name="idP" value="<?= htmlspecialchars($idP) ?>" required>
                    <label>Désignation</label>
                    <textarea name="designation" required><?= htmlspecialchars($designation) ?></textarea>
                    <label>Seuil</label>
                    <input type="number" name="seuil" value="<?= htmlspecialchars($seuil) ?>" required>
                    <label>Quantité</label>
                    <input type="number" name="quantite" value="<?= htmlspecialchars($quantite) ?>" <?= $action === 'modifier' ? 'readonly' : '' ?>>
                    <div class="button-container">
                        <input type="submit" value="<?= $action === 'modifier' ? 'Modifier' : 'Ajouter' ?>">
                        <button type="button" onclick="window.location.href='gestion_produits.php'">Annuler</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="form-container">
                <input type="text" id="search" class="search-input" placeholder="🔍 Rechercher un produit...">
                <div class="title-container">
                    <h3>📦 Liste des Produits</h3>
                    <div class="action-buttons">
                        <a href="gestion_produits.php?action=ajouter">➕ Ajouter un Produit</a>
                    </div>
                </div>
                <table id="produitTable">
                    <thead>
                        <tr>
                            <th data-column="0">ID</th>
                            <th data-column="1">Désignation</th>
                            <th data-column="2">Seuil</th>
                            <th data-column="3">Quantité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= htmlspecialchars($produit['idP']) ?></td>
                            <td><?= htmlspecialchars($produit['designation']) ?></td>
                            <td><?= htmlspecialchars($produit['seuil']) ?></td>
                            <td><?= htmlspecialchars($produit['quantite']) ?></td>
                            <td>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer ce produit ?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="idP" value="<?= $produit['idP'] ?>">
                                    <button style="background-color:#e74c3c;">🗑</button>
                                </form>
                                <a href="gestion_produits.php?action=modifier&idP=<?= $produit['idP'] ?>">
                                    <button style="background-color:#1e3799;">✏️</button>
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
            <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
            <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
        </footer>
    </main>
    <script>
        // Recherche dynamique
        const searchInput = document.getElementById("search");
        searchInput.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#produitTable tbody tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Tri dynamique
        document.querySelectorAll("#produitTable th[data-column]").forEach(th => {
            th.addEventListener("click", () => {
                const table = th.closest("table");
                const tbody = table.querySelector("tbody");
                const index = parseInt(th.getAttribute("data-column"));
                const rows = Array.from(tbody.querySelectorAll("tr"));
                const asc = th.classList.toggle("asc");
                rows.sort((a, b) => {
                    const cellA = a.children[index].textContent.trim();
                    const cellB = b.children[index].textContent.trim();
                    return asc
                        ? cellA.localeCompare(cellB, undefined, { numeric: true })
                        : cellB.localeCompare(cellA, undefined, { numeric: true });
                });
                tbody.innerHTML = "";
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    </script>
</body>
</html>
