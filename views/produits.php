<?php
require_once '../config/db.php';
require_once '../models/produit.php';
$produits = Produit::getAllProduits();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Liste des Produits</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
      flex-direction: column;
      min-height: 100vh;
      background-color: var(--primary);
      color: white;
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
      margin-bottom: 40px;
      padding: 0 20px;
    }
    .logo img {
      width: 50px;
      height: auto;
      margin-right: 10px;
    }
    .logo h1 {
      margin: 0;
      font-size: 20px;
    }
    .nav-links {
      display: flex;
      flex-direction: column;
    }
    .nav-item {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      margin: 10px 0;
      color: white;
      text-decoration: none;
    }
    .nav-item:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .nav-item i {
      margin-right: 10px;
    }
    .nav-item a {
      color: white;
      text-decoration: none;
    }
    .nav-item.active {
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 5px;
    }
    .main-content {
      flex: 1;
      margin-left: 260px;
      padding: 20px;
      background-color: white;
      color: black;
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
    .search-input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 10px;
    }
    .footer {
      background-color: white;
      color: black;
      text-align: center;
      padding: 10px;
      margin-top: auto;
      font-size: 14px;
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
        <i class="fas fa-cart-shopping"></i>
        <a href="demandes.php">Demandes</a>
      </div>
      <div class="nav-item active">
        <i class="fas fa-box"></i>
        <a href="produits.php">Produits</a>
      </div>
      <div class="nav-item">
        <i class="fas fa-sign-out-alt"></i>
        <a href="logout.php">Déconnexion</a>
      </div>
    </nav>
  </aside>
  <main class="main-content">
    <h2>Liste des Produits – Laboratoires Medis</h2>
    <div class="form-container">
      <input type="text" id="searchInput" class="search-input" placeholder="🔍 Rechercher un produit...">
      <table id="produitsTable">
        <thead>
          <tr>
            <th data-column="0">ID</th>
            <th data-column="1">Désignation</th>
            <th data-column="2">Quantité</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($produits)): ?>
            <?php foreach ($produits as $produit): ?>
              <tr>
                <td><?= htmlspecialchars($produit['idP']) ?></td>
                <td><?= htmlspecialchars($produit['designation']) ?></td>
                <td><?= htmlspecialchars($produit['quantite']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3">Aucun produit trouvé.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <footer class="footer">
    <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
    <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
    <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
  </footer>
  <script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#produitsTable tbody tr");
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
    document.querySelectorAll("#produitsTable th[data-column]").forEach(th => {
      th.addEventListener("click", () => {
        const table = th.closest("table");
        const tbody = table.querySelector("tbody");
        const index = parseInt(th.getAttribute("data-column"));
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const asc = th.classList.toggle("asc");
        rows.sort((a, b) => {
          const cellA = a.children[index].textContent.trim();
          const cellB = b.children[index].textContent.trim();
          return asc ? cellA.localeCompare(cellB, undefined, { numeric: true }) : cellB.localeCompare(cellA, undefined, { numeric: true });
        });
        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));
      });
    });
  </script>
</body>
</html>
