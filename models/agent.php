<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'agent') {
    header("Location: ../index.html");
    exit();
}

$agent = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etat'], $_POST['numD'])) {
    $etat = $_POST['etat'];
    $numD = intval($_POST['numD']);

    $stmt = $pdo->prepare("UPDATE demandes SET etat = ? WHERE numD = ?");
    $stmt->execute([$etat, $numD]);
}

$demandes = $pdo->query("SELECT d.numD, u.nom, u.prenom, d.description, d.quantite, d.etat FROM demandes d JOIN utilisateurs u ON d.utilisateur_id = u.matricule ORDER BY d.numD DESC")->fetchAll(PDO::FETCH_ASSOC);
$produits = $pdo->query("SELECT * FROM produits ORDER BY idP ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StockPro | Tableau de bord Agent</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .hidden { display: none; }
    .fade {
      animation: fadein 0.3s ease-in-out;
    }
    @keyframes fadein {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    table thead {
      background: #f0f2f5;
    }
    table th, table td {
      padding: 12px 15px;
      text-align: left;
    }
    table tr:not(:last-child) {
      border-bottom: 1px solid #e9ecef;
    }
    select, button {
      padding: 6px 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #0c2461;
      color: white;
      cursor: pointer;
    }
    button:hover {
      background-color: #1e3799;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const demandesSection = document.getElementById("demandes-section");
      const produitsSection = document.getElementById("produits-section");
      const navItems = document.querySelectorAll(".nav-item");

      navItems.forEach(item => {
        item.addEventListener("click", function() {
          navItems.forEach(nav => nav.classList.remove("active"));
          this.classList.add("active");

          if (this.dataset.section === 'demandes') {
            demandesSection.classList.remove("hidden");
            produitsSection.classList.add("hidden");
          } else {
            produitsSection.classList.remove("hidden");
            demandesSection.classList.add("hidden");
          }
        });
      });
    });
  </script>
</head>
<body>
  <aside class="sidebar">
    <div class="logo">
      <i class="fas fa-warehouse"></i>
      <h1>StockPro</h1>
    </div>
    <nav class="nav-links">
      <div class="nav-item active" data-section="demandes">
        <i class="fas fa-tasks"></i>
        <span>Demandes</span>
      </div>
      <div class="nav-item" data-section="produits">
        <i class="fas fa-boxes"></i>
        <span>Produits</span>
      </div>
    </nav>
  </aside>

  <main class="main-content">
    <div class="top-bar">
      <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Rechercher une demande ou produit...">
      </div>
      <div class="user-actions">
        <div class="notification">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">3</span>
        </div>
        <div class="user-profile">
          <div class="user-avatar">
            <?= strtoupper(substr($agent['prenom'], 0, 1) . substr($agent['nom'], 0, 1)) ?>
          </div>
          <div class="user-info">
            <div class="user-name">
              <?= htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']) ?>
            </div>
            <div class="user-role">Agent</div>
          </div>
        </div>
      </div>
    </div>

    <div id="demandes-section">
      <div class="dashboard-title">
        <h2>Demandes à traiter</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Quantité</th>
            <th>État</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($demandes as $d): ?>
            <tr>
              <td><?= $d['numD'] ?></td>
              <td><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></td>
              <td><?= htmlspecialchars($d['description']) ?></td>
              <td><?= $d['quantite'] ?></td>
              <td><?= $d['etat'] ?></td>
              <td>
                <form method="post" style="display:flex;gap:5px;">
                  <input type="hidden" name="numD" value="<?= $d['numD'] ?>">
                  <select name="etat">
                    <option value="En attente" <?= $d['etat'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="Validée" <?= $d['etat'] === 'Validée' ? 'selected' : '' ?>>Validée</option>
                    <option value="Rejetée" <?= $d['etat'] === 'Rejetée' ? 'selected' : '' ?>>Rejetée</option>
                  </select>
                  <button type="submit">Mettre à jour</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div id="produits-section" class="hidden">
      <div class="dashboard-title">
        <h2>Produits disponibles</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Désignation</th>
            <th>Quantité</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produits as $p): ?>
            <tr>
              <td><?= $p['idP'] ?></td>
              <td><?= htmlspecialchars($p['designation']) ?></td>
              <td><?= $p['quantite'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
