<?php
require_once '../config/db.php';
try {
    $sql = "SELECT
                d.numD,
                d.etat,
                p.designation AS nom_produit,
                d.quantite,
                d.description,
                d.date_demande
            FROM demandes d
            JOIN produits p ON d.idProduit = p.idP
            ORDER BY d.numD DESC";
    $stmt = $pdo->query($sql);
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des demandes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #0c2461;
            --light-gray: #e9ecef;
            --danger: #e74c3c;
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--primary), #1e3799);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .sidebar .logo img {
            width: 36px;
            margin-right: 10px;
        }
        .sidebar h1 {
            font-size: 20px;
        }
        .nav-links {
            margin-top: 30px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background 0.2s;
        }
        .nav-item i {
            margin-right: 10px;
        }
        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        main {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        h2 {
            margin-bottom: 15px;
            color: var(--primary);
        }
        #searchInput {
            width: 100%;
            padding: 10px 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            outline: none;
            transition: border-color 0.3s;
        }
        #searchInput:focus {
            border-color: var(--primary);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            cursor: default;
        }
        th {
            background-color: var(--primary);
            color: white;
            cursor: pointer;
            user-select: none;
            position: relative;
        }
        th.sort-asc::after {
            content: " ▲";
            position: absolute;
            right: 10px;
        }
        th.sort-desc::after {
            content: " ▼";
            position: absolute;
            right: 10px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
        }
        .back-link i {
            margin-right: 6px;
        }
        footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 15px 0;
            border-top: 1px solid #ccc;
            background: #fff;
        }
        .status-symbol {
            font-size: 20px;
        }
        .status-pending {
            color: var(--warning);
        }
        .status-approved {
            color: var(--success);
        }
        .status-rejected {
            color: var(--danger);
        }
        .status-in-progress {
            color: var(--info);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="../icon/images.jpg" alt="Medis Logo">
            <h1>Gestion Stock</h1>
        </div>
        <nav class="nav-links">
            <a href="formulaire_demande.php" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Demandes</span>
            </a>
            <a href="historique_demandes.php" class="nav-item active">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Historique demandes</span>
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>
    <!-- Contenu principal -->
    <main>
        <div class="container">
            <!-- Champ de recherche -->
            <input type="text" id="searchInput" placeholder="Rechercher dans les demandes...">
            <table id="demandesTable">
                <thead>
                    <tr>
                        <th data-column="numD">#</th>
                        <th data-column="etat">État</th>
                        <th data-column="nom_produit">Produit</th>
                        <th data-column="quantite">Quantité</th>
                        <th data-column="description">Description</th>
                        <th data-column="date">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($demandes)): ?>
                        <?php foreach ($demandes as $demande): ?>
                            <tr>
                                <td><?= htmlspecialchars($demande['numD']) ?></td>
                                <td>
                                    <?php
                                    if ($demande['etat'] === 'En attente') {
                                        echo '<i class="fas fa-clock status-symbol status-pending"></i>';
                                    } elseif ($demande['etat'] === 'Approuvée') {
                                        echo '<i class="fas fa-check-circle status-symbol status-approved"></i>';
                                    } elseif ($demande['etat'] === 'Rejetée') {
                                        echo '<i class="fas fa-times-circle status-symbol status-rejected"></i>';
                                    } elseif ($demande['etat'] === 'En cours') {
                                        echo '<i class="fas fa-spinner status-symbol status-in-progress"></i>';
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($demande['nom_produit']) ?></td>
                                <td><?= htmlspecialchars($demande['quantite']) ?></td>
                                <td><?= htmlspecialchars($demande['description']) ?></td>
                                <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Aucune demande trouvée.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Footer -->
        <footer>
            <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
            <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
            <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
        </footer>
    </main>
    <script>
        // Tri des colonnes
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) => {
            const v1 = getCellValue(a, idx);
            const v2 = getCellValue(b, idx);
            const n1 = parseFloat(v1.replace(',', '.'));
            const n2 = parseFloat(v2.replace(',', '.'));
            if (!isNaN(n1) && !isNaN(n2)) {
                return (n1 - n2) * (asc ? 1 : -1);
            } else {
                return v1.toString().localeCompare(v2) * (asc ? 1 : -1);
            }
        };
        document.querySelectorAll('#demandesTable th').forEach(th => {
            th.addEventListener('click', () => {
                const table = th.closest('table');
                const tbody = table.querySelector('tbody');
                Array.from(table.querySelectorAll('th')).forEach(th2 => {
                    if (th2 !== th) th2.classList.remove('sort-asc', 'sort-desc');
                });
                const index = Array.from(th.parentNode.children).indexOf(th);
                const asc = !th.classList.contains('sort-asc');
                th.classList.toggle('sort-asc', asc);
                th.classList.toggle('sort-desc', !asc);
                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.sort(comparer(index, asc));
                rows.forEach(row => tbody.appendChild(row));
            });
        });
        // Recherche dans le tableau
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#demandesTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
