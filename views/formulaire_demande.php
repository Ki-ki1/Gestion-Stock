<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de Demande</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #0c2461;
            --light-gray: #e9ecef;
            --danger: #e74c3c;
            --success: #28a745;
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
        footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 15px 0;
            border-top: 1px solid #ccc;
            background: #fff;
        }
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .button-container input[type="submit"],
        .button-container button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .button-container input[type="submit"] {
            background: var(--primary);
            color: white;
        }
        .button-container button {
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
            <a href="formulaire_demande.php" class="nav-item active">
                <i class="fas fa-shopping-cart"></i>
                <span>Demandes</span>
            </a>
            <a href="historique_demandes.php" class="nav-item">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Historique demandes</span>
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>
    <main>
        <div class="container">
            <h2>Formulaire de Demande</h2>
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
                <div class="button-container">
                    <input type="submit" value="Envoyer la demande" />
                    <button type="button" onclick="window.location.href='historique_demandes.php';">Annuler</button>
                </div>
            </form>
        </div>
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
