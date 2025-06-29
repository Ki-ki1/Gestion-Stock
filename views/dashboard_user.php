<?php
// dashboard_user.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>StockPro | Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #0c2461;
            --primary-light: #1e3799;
            --success: #27ae60;
            --light-gray: #e9ecef;
            --dark: #343a40;
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
            text-decoration: none;
            color: white;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item i {
            font-size: 20px;
            margin-right: 15px;
        }

        .nav-item span {
            font-size: 16px;
            font-weight: 500;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }

        .dashboard-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }

        .form-container {
            margin-top: 20px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 700px;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .main-content {
                margin-left: 70px;
            }

            .logo h1,
            .nav-item span {
                display: none;
            }

            .nav-item {
                justify-content: center;
            }
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
            <a href="dashboard_user.php" class="nav-item <?= $currentPage === 'dashboard_user.php' ? 'active' : '' ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Demandes</span>
            </a>
            <a href="historique_demandes.php" class="nav-item <?= $currentPage === 'historique_demandes.php' ? 'active' : '' ?>">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Historique demandes</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-title">
            <h2>Tableau de bord utilisateur</h2>
        </div>

        <!-- Formulaire de demande -->
        <div class="form-container">
            <h3 style="margin-bottom: 20px; color: var(--primary); font-size: 22px;">Créer une nouvelle demande</h3>
            <form action="../controllers/demandes.php" method="post">
                <div style="margin-bottom: 20px;">
                    <label for="description" style="display: block; font-weight: 600; margin-bottom: 8px;">Description :</label>
                    <textarea name="description" id="description" required style="width: 100%; padding: 10px 15px; border: 2px solid var(--light-gray); border-radius: 8px; font-size: 15px;" rows="3"></textarea>
                </div>

                <div id="produits-container">
                    <div class="produit-block">
                        <label style="font-weight: bold;">Produit 1 :</label>
                        <div style="margin-bottom: 15px;">
                            <select name="produits[]" required style="width: 100%; padding: 10px 15px; margin-top: 8px; border: 2px solid var(--light-gray); border-radius: 8px;">
                                <option value="">-- Sélectionnez un produit --</option>
                                <?php
                                require_once '../config/db.php';
                                $stmt = $pdo->query("SELECT idP, designation FROM Produits");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['idP'] . '">' . htmlspecialchars($row['designation']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-bottom: 25px;">
                            <input type="number" name="quantites[]" placeholder="Quantité" required style="width: 100%; padding: 10px 15px; border: 2px solid var(--light-gray); border-radius: 8px;" />
                        </div>
                    </div>
                </div>

                <button type="button" onclick="ajouterProduit()" style="margin-bottom: 20px; background: var(--success); color: white; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">+ Ajouter un produit</button>

                <input type="submit" value="Envoyer la demande" style="padding: 12px 25px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;" />
            </form>
        </div>

        <!-- Footer avec contact -->
        <footer>
            <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
            <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
            <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
        </footer>
    </main>

    <script>
        function ajouterProduit() {
            const container = document.getElementById('produits-container');
            const count = container.querySelectorAll('.produit-block').length + 1;

            const block = document.createElement('div');
            block.className = 'produit-block';
            block.innerHTML = `
                <label style="font-weight: bold;">Produit ${count} :</label>
                <div style="margin-bottom: 15px;">
                    <select name="produits[]" required style="width: 100%; padding: 10px 15px; margin-top: 8px; border: 2px solid var(--light-gray); border-radius: 8px;">
                        ${container.querySelector('select').innerHTML}
                    </select>
                </div>
                <div style="margin-bottom: 25px;">
                    <input type="number" name="quantites[]" placeholder="Quantité" required style="width: 100%; padding: 10px 15px; border: 2px solid var(--light-gray); border-radius: 8px;" />
                </div>
            `;
            container.appendChild(block);
        }
    </script>
</body>
</html>
