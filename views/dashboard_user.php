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
    }

    /* Main Content */
    .main-content {
      flex: 1;
      margin-left: 260px;
      padding: 20px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 30px;
    }

    .search-bar {
      position: relative;
      width: 300px;
    }

    .search-bar input {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border: 2px solid var(--light-gray);
      border-radius: 30px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .search-bar input:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(12, 36, 97, 0.1);
    }

    .search-bar i {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
      font-size: 18px;
    }

    .user-actions {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .notification {
      position: relative;
      cursor: pointer;
    }

    .notification i {
      font-size: 22px;
      color: var(--gray);
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: var(--danger);
      color: white;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .user-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: var(--primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 18px;
    }

    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 600;
      font-size: 16px;
    }

    .user-role {
      font-size: 13px;
      color: var(--gray);
    }

    .dashboard-title {
      margin-bottom: 25px;
    }

    .dashboard-title h2 {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
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

      .search-bar {
        width: 200px;
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
      <div class="nav-item active">
        <i class="fas fa-shopping-cart"></i>
        <span>Demandes</span>
      </div>
      <div class="nav-item">
        <img src="../icon/image.png" alt="Historique" />
        <span>Historique demandes</span>
      </div>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Dashboard Title -->
    <div class="dashboard-title">
      <h2>Tableau de bord utilisateur</h2>
    </div>
    <!-- Formulaire de création de demande -->
    <div class="form-container" style="margin-top: 20px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); max-width: 600px;">
      <h3 style="margin-bottom: 20px; color: var(--primary); font-size: 22px;">Créer une nouvelle demande</h3>
      <form action="../controllers/demandes.php" method="post">
        <div style="margin-bottom: 25px;">
          <label for="idProduit" style="display: block; font-weight: 600; margin-bottom: 8px;">Nom du produit :</label>
          <select id="idProduit" name="idProduit" required
            style="width: 100%; padding: 10px 15px; border: 2px solid var(--light-gray); border-radius: 8px; font-size: 15px;">
            <option value="">-- Sélectionnez un produit --</option>
            <?php
            require_once '../config/db.php'; // this file must define $pdo
                    
            try {
                $stmt = $pdo->query("SELECT idP, nom FROM Produits");
            
                if ($stmt->rowCount() === 0) {
                    echo '<option disabled>Aucun produit disponible</option>';
                } else {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row['idP'] . '">' . htmlspecialchars($row['nom']) . '</option>';
                    }
                }
            } catch (PDOException $e) {
                echo '<option disabled>Erreur : ' . htmlspecialchars($e->getMessage()) . '</option>';
            }
            ?>
          </select>

        </div>
        <div style="margin-bottom: 20px;">
          <label for="quantite" style="display: block; font-weight: 600; margin-bottom: 8px;">Quantité :</label>
          <input type="number" id="quantite" name="quantite" required
            style="width: 100%; padding: 10px 15px; border: 2px solid var(--light-gray); border-radius: 8px; font-size: 15px;" />
        </div>
      
        <input type="submit" value="Envoyer la demande"
          style="padding: 12px 25px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;" />
      </form>
    </div>

  </main>
</body>
</html>
