<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Demandes</title>
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
      flex-direction: row;
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
      transition: all 0.3s ease;
    }
    .logo {
      display: flex;
      align-items: center;
      padding: 0 20px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 20px;
    }
    .logo img {
      height: 40px;
      margin-right: 10px;
    }
    .logo h1 {
      font-size: 22px;
      font-weight: 700;
      color: white;
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
    .nav-item i {
      font-size: 20px;
      margin-right: 15px;
      width: 24px;
      height: 24px;
    }
    .nav-item span a {
      font-size: 16px;
      font-weight: 500;
      color: white;
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
    .dashboard-title {
      margin-bottom: 15px;
    }
    .dashboard-title h2 {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
    }

    /* Champ de recherche */
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
      <div class="nav-item active">
        <i class="fas fa-cart-shopping"></i>
        <span><a href="demandes.php">Demandes</a></span>
      </div>
      <div class="nav-item">
        <i class="fas fa-boxes-stacked"></i>
        <span><a href="produits.php">Produits</a></span>
      </div>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <div class="dashboard-title">
      <h2>Liste des Demandes</h2>
    </div>

    <!-- Champ de recherche -->
    <input type="text" id="searchInput" placeholder="Rechercher dans les demandes...">

    <table id="demandesTable">
      <thead>
        <tr>
          <th>Numéro</th>
          <th>Quantité</th>
          <th>État</th>
          <th>Description</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Produit</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once '../models/Demande.php';
        $demandes = Demande::getAllDemandes();

        if (!empty($demandes)) {
          foreach ($demandes as $demande) {
        ?>
        <tr>
          <td><?= htmlspecialchars($demande['numD']) ?></td>
          <td><?= htmlspecialchars($demande['quantite']) ?></td>
          <td><?= htmlspecialchars($demande['etat']) ?></td>
          <td><?= htmlspecialchars($demande['description']) ?></td>
          <td><?= htmlspecialchars($demande['user_nom']) ?></td>
          <td><?= htmlspecialchars($demande['user_prenom']) ?></td>
          <td><?= htmlspecialchars($demande['produit_designation']) ?></td>
          <td>
            <select name="etat" onchange="updateEtat(<?= $demande['numD'] ?>, this.value)">
              <option value="En attente" <?= $demande['etat'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
              <option value="Approuvée" <?= $demande['etat'] === 'Approuvée' ? 'selected' : '' ?>>Approuvée</option>
              <option value="Rejetée" <?= $demande['etat'] === 'Rejetée' ? 'selected' : '' ?>>Rejetée</option>
            </select>
          </td>
        </tr>
        <?php
          }
        } else {
        ?>
        <tr>
          <td colspan="8">Aucune demande trouvée.</td>
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
    // Fonction pour mettre à jour l'état via AJAX
    function updateEtat(numD, etat) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../controllers/update_demande.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          alert(xhr.responseText);
        }
      };
      xhr.send("numD=" + encodeURIComponent(numD) + "&etat=" + encodeURIComponent(etat));
    }

    // Tri des colonnes (sauf la dernière colonne "Action")
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

    document.querySelectorAll('#demandesTable th').forEach((th, index) => {
      // Ne pas activer le tri sur la dernière colonne "Action"
      if (index === 7) return;

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
      const rows = document.querySelectorAll('#demandesTable tbody tr');

      rows.forEach(row => {
        // On teste si la ligne contient le texte dans n'importe quelle cellule (hors colonne action)
        const cellsText = Array.from(row.children)
          .slice(0, 7) // Ignorer la dernière colonne
          .map(td => td.textContent.toLowerCase())
          .join(' ');

        row.style.display = cellsText.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
