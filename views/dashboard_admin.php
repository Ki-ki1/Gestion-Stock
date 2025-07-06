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
    #searchInput {
      width: 100%;
      padding: 10px 12px;
      font-size: 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      margin-bottom: 15px;
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
    table, th, td {
      border: 1px solid var(--border);
    }
    th, td {
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: var(--light-gray);
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
    tr:nth-child(even) {
      background-color: #f2f2f2;
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
      <div class="nav-item active">
        <i class="fas fa-box"></i>
        <span><a href="gestion_produits.php">Produits</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-file-invoice"></i>
        <span><a href="gestion_factures.php">Factures</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-shopping-cart"></i>
        <span><a href="consulter_agents.php">Demandes</a></span>
      </div>
    </nav>
  </aside>
  <!-- Main Content -->
  <main class="main-content">
    <div class="dashboard-title">
      <h2>Tableau de bord Administrateur</h2>
    </div>
    <!-- Champ de recherche -->
    <input type="text" id="searchInput" placeholder="Rechercher dans les produits...">
    <table id="produitsTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Désignation</th>
          <th>Seuil</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once '../models/produit.php';
        $produits = Produit::getAllProduits();
        if (!empty($produits)) {
          foreach ($produits as $produit) {
            // Récupérer la quantité approuvée pour ce produit
            $quantiteApprouvee = Produit::getQuantiteApprouvee($produit['idP']);
            $seuil = (int)$produit['seuil'];
            $alerte = ($quantiteApprouvee >= $seuil);
        ?>
        <tr>
          <td><?= htmlspecialchars($produit['idP']) ?></td>
          <td><?= htmlspecialchars($produit['designation']) ?></td>
          <td style="color: <?= $alerte ? 'red' : 'inherit' ?>;">
            <?= htmlspecialchars($seuil) ?>
          </td>
        </tr>
        <?php
          }
        } else {
        ?>
        <tr>
          <td colspan="3">Aucun produit trouvé.</td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
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
    document.querySelectorAll('#produitsTable th').forEach((th, index) => {
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        // Enlever les classes de tri sur les autres colonnes
        Array.from(table.querySelectorAll('th')).forEach(th2 => {
          if (th2 !== th) th2.classList.remove('sort-asc', 'sort-desc');
        });
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
      const rows = document.querySelectorAll('#produitsTable tbody tr');
      rows.forEach(row => {
        const cellsText = Array.from(row.children)
          .map(td => td.textContent.toLowerCase())
          .join(' ');
        row.style.display = cellsText.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
