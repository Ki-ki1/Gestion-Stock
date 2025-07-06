<?php
require_once '../config/db.php';

function getAllProduits() {
    global $pdo;
    return $pdo->query("SELECT * FROM Produits ORDER BY idP ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getAllFactures() {
    global $pdo;
    return $pdo->query("SELECT * FROM factures ORDER BY num DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function getFactureByNum($num) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM factures WHERE num = ?");
    $stmt->execute([$num]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveFacture($num, $fournisseur, $date, $idP_array, $quantite_array, $prixUnitaire_array) {
    global $pdo;
    if ($num === null) {
        $stmt = $pdo->query("SHOW TABLE STATUS LIKE 'factures'");
        $num = $stmt->fetch()['Auto_increment'];
    } else {
        $stmt = $pdo->prepare("DELETE FROM factures WHERE num = ?");
        $stmt->execute([$num]);
    }
    for ($i = 0; $i < count($idP_array); $i++) {
        $idP = $idP_array[$i];
        $quantite = (int)$quantite_array[$i];
        $prix_unitaire = (float)$prixUnitaire_array[$i];
        $prix_total = $quantite * $prix_unitaire;
        if (!$idP || $quantite <= 0 || $prix_unitaire <= 0) continue;
        $stmt = $pdo->prepare("INSERT INTO factures (num, fournisseur, date, idP, quantite, prix_total, prix_unitaire) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$num, $fournisseur, $date, $idP, $quantite, $prix_total, $prix_unitaire]);
    }
    return $num;
}

function supprimerFacture($num) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM factures WHERE num = ?");
    $stmt->execute([$num]);
}

$produits = getAllProduits();
$factures = getAllFactures();
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$numToEdit = $action === 'modifier' ? ($_GET['num'] ?? $_POST['num'] ?? null) : null;
$factureToEdit = $numToEdit ? getFactureByNum($numToEdit) : [];
$fournisseur_edit = $date_edit = '';
$idP_edit = $quantite_edit = $pu_edit = [];

if ($factureToEdit) {
    $fournisseur_edit = $factureToEdit[0]['fournisseur'];
    $date_edit = $factureToEdit[0]['date'];
    foreach ($factureToEdit as $ligne) {
        $idP_edit[] = $ligne['idP'];
        $quantite_edit[] = $ligne['quantite'];
        $pu_edit[] = $ligne['prix_unitaire'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'ajouter' || $action === 'modifier') {
        $num = $action === 'modifier' ? ($_POST['num'] ?? null) : null;
        $fournisseur = $_POST['fournisseur'] ?? '';
        $date = $_POST['date'] ?? '';
        $idP_array = $_POST['idP'] ?? [];
        $quantite_array = $_POST['quantite'] ?? [];
        $prixUnitaire_array = $_POST['prix_unitaire'] ?? [];
        if (!$fournisseur || !$date || empty($idP_array)) {
            echo "<script>alert('Tous les champs sont obligatoires.');</script>";
        } else {
            saveFacture($num, $fournisseur, $date, $idP_array, $quantite_array, $prixUnitaire_array);
            header("Location: gestion_factures.php");
            exit;
        }
    } elseif ($action === 'supprimer') {
        $num = $_POST['num'] ?? null;
        if ($num) {
            supprimerFacture($num);
            header("Location: gestion_factures.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Factures</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0c2461;
            --primary-light: #1e3799;
            --light-gray: #e9ecef;
            --border: #dee2e6;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
        input[type="text"], input[type="date"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        button, input[type="submit"], .action-buttons a {
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
        .produit-row {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            align-items: center;
        }
        .produit-row select,
        .produit-row input[type=number] {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .produit-row button.remove-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            font-weight: 700;
            cursor: pointer;
            border-radius: 6px;
            padding: 8px 12px;
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
                <i class="fas fa-file-invoice"></i>
                <a href="gestion_factures.php">Factures</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-box"></i>
                <a href="gestion_produits.php">Produits</a>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h2>Gestion des Factures – Laboratoires Medis</h2>
        <?php if ($action === 'ajouter' || $action === 'modifier'): ?>
            <div class="form-container">
                <h3><?= $action === 'modifier' ? 'Modifier une Facture' : 'Ajouter une Facture' ?></h3>
                <form method="post">
                    <input type="hidden" name="action" value="<?= $action ?>">
                    <?php if ($numToEdit): ?>
                        <input type="hidden" name="num" value="<?= $numToEdit ?>">
                    <?php endif; ?>
                    <label for="fournisseur">Fournisseur</label>
                    <input type="text" id="fournisseur" name="fournisseur" value="<?= htmlspecialchars($fournisseur_edit) ?>" required>
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($date_edit) ?>" required>
                    <label>Produits</label>
                    <div id="produits-container">
                        <!-- Lignes produits ajoutées via JS -->
                    </div>
                    <button type="button" onclick="ajouterLigneProduit()">➕ Ajouter un produit</button>
                    <br><br>
                    <input type="submit" value="<?= $action === 'modifier' ? 'Modifier la facture' : 'Ajouter la facture' ?>">
                </form>
            </div>
        <?php else: ?>
            <div class="form-container">
                <input type="text" id="search" class="search-input" placeholder="🔍 Rechercher une facture...">
                <div class="title-container">
                    <h3>📋 Liste des Factures</h3>
                    <div class="action-buttons">
                        <a href="gestion_factures.php?action=ajouter">➕ Ajouter une Facture</a>
                    </div>
                </div>
                <table id="factureTable">
                    <thead>
                        <tr>
                            <th data-column="0">N° Facture</th>
                            <th data-column="1">Fournisseur</th>
                            <th data-column="2">Date</th>
                            <th data-column="3">Prix Total (DT)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['num']) ?></td>
                                <td><?= htmlspecialchars($f['fournisseur']) ?></td>
                                <td><?= htmlspecialchars($f['date']) ?></td>
                                <td><?= number_format($f['prix_total'], 2) ?></td>
                                <td>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer cette facture ?');">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="num" value="<?= $f['num'] ?>">
                                        <button style="background-color:#e74c3c;">🗑</button>
                                    </form>
                                    <a href="gestion_factures.php?action=modifier&num=<?= $f['num'] ?>">
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
        const produitsOptions = <?= json_encode($produits) ?>;

        function ajouterLigneProduit(id = '', quantite = '', pu = '') {
            const container = document.getElementById("produits-container");
            const div = document.createElement("div");
            div.classList.add("produit-row");
            let selectHTML = '<select name="idP[]" required>';
            selectHTML += '<option value="">-- Choisir un produit --</option>';
            produitsOptions.forEach(p => {
                const selected = (id == p.idP) ? "selected" : "";
                selectHTML += `<option value="${p.idP}" ${selected}>${p.idP} - ${p.designation}</option>`;
            });
            selectHTML += '</select>';
            div.innerHTML = `
                ${selectHTML}
                <input type="number" name="quantite[]" min="1" placeholder="Quantité" value="${quantite}" required>
                <input type="number" step="0.01" name="prix_unitaire[]" min="0.01" placeholder="Prix Unitaire (DT)" value="${pu}" required>
                <button type="button" class="remove-btn" title="Supprimer ce produit" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(div);
        }

        <?php if ($action === 'modifier' && count($idP_edit) > 0): ?>
            <?php
                for ($i = 0; $i < count($idP_edit); $i++):
                    $id = htmlspecialchars($idP_edit[$i]);
                    $qt = htmlspecialchars($quantite_edit[$i]);
                    $prixu = htmlspecialchars($pu_edit[$i]);
            ?>
            ajouterLigneProduit('<?= $id ?>', '<?= $qt ?>', '<?= $prixu ?>');
            <?php endfor; ?>
        <?php else: ?>
            ajouterLigneProduit();
        <?php endif; ?>

        // Recherche dynamique
        const searchInput = document.getElementById("search");
        searchInput.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#factureTable tbody tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Tri dynamique
        document.querySelectorAll("#factureTable th[data-column]").forEach(th => {
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
