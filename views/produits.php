<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
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

        /* Sidebar */
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

        .logo i {
            font-size: 28px;
            margin-right: 12px;
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

        /* Main Content */
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

        /* Table styles */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .main-content {
                margin-left: 70px;
            }

            .logo h1,
            .nav-item span,
            .user-info {
                display: none;
            }

            .nav-item {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
            }

            .search-bar {
                width: 100%;
            }

            .user-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-warehouse"></i>
            <h1>Gestion Stock</h1>
        </div>

        <nav class="nav-links">
            <div class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span><a href="demandes.php" style="color: white;">Demandes</a></span>
            </div>
            <div class="nav-item active">
                <i class="fas fa-box"></i>
                <span><a href="produits.php" style="color: white;">Produits</a></span>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-title">
            <h2>Liste des Produits</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Désignation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../models/Produit.php';
                $produits = Produit::getAllProduits();

                if (!empty($produits)) {
                    foreach ($produits as $produit) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($produit['idP']) ?></td>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= htmlspecialchars($produit['quantite']) ?></td>
                    <td><?= htmlspecialchars($produit['designation']) ?></td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                    <td colspan="4">Aucun produit trouvé.</td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
