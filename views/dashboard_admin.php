<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>StockPro | Tableau de bord Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary: #0c2461;
      --primary-light: #1e3799;
      --gray: #6c757d;
      --light-gray: #e9ecef;
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
      color: var(--gray);
    }

    .sidebar {
      width: 260px;
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      color: white;
      padding: 20px 0;
      height: 100vh;
      position: fixed;
      overflow-y: auto;
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
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .nav-item i {
      font-size: 20px;
      margin-right: 15px;
    }

    .nav-item span a {
      color: white;
      font-size: 16px;
      font-weight: 500;
      text-decoration: none;
    }

    .main-content {
      flex: 1;
      margin-left: 260px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .dashboard-title h2 {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 25px;
    }

    footer {
      margin-top: auto;
      text-align: center;
      font-size: 14px;
      color: #666;
      padding: 15px 0;
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
      <div class="nav-item">
        <i class="fas fa-users"></i>
        <span><a href="gestion_utilisateurs.php">Utilisateurs</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-box"></i>
        <span><a href="gestion_produits.php">Produits</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-file-invoice"></i>
        <span><a href="gestion_factures.php">Factures</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-shopping-cart"></i>
        <span><a href="gestion_demandes.php">Demandes</a></span>
      </div>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <div class="dashboard-title">
      <h2>Tableau de bord Administrateur</h2>
    </div>

    <footer>
      <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
      <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
      <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
    </footer>
  </main>
</body>
</html>
