<?php
require_once '../config/db.php';

// Récupérer tous les agents
function getAllAgents() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Utilisateurs INNER JOIN Agents ON Utilisateurs.matricule = Agents.id_agent");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$agents = getAllAgents();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter les Agents</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background-color: #f5f7fb;
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
            padding: 0 20px;
            margin-bottom: 20px;
        }
        .logo img {
            height: 60px;
            margin-right: 10px;
        }
        .logo h1 {
            font-size: 20px;
        }
        .nav-links {
            padding: 0 15px;
        }
        .nav-item {
            padding: 14px 15px;
            display: flex;
            align-items: center;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        h2 {
            color: var(--primary);
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #666;
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
                <i class="fas fa-users"></i>
                <a href="gestion_utilisateurs.php">Utilisateurs</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-box"></i>
                <a href="gestion_produits.php">Produits</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-file-invoice"></i>
                <a href="gestion_factures.php">Factures</a>
            </div>
            <div class="nav-item">
                <i class="fas fa-users-cog"></i>
                <a href="consulter_agents.php">Demandes</a>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h2>Consulter les Agents</h2>
        <div class="form-container">
            <input type="text" id="search" class="search-input" placeholder="🔍 Rechercher un agent...">
            <table id="agentTable">
                <thead>
                    <tr>
                        <th>ID Agent</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Login</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agents as $agent): ?>
                        <tr>
                            <td><?= htmlspecialchars($agent['id_agent']) ?></td>
                            <td><?= htmlspecialchars($agent['nom']) ?></td>
                            <td><?= htmlspecialchars($agent['prenom']) ?></td>
                            <td><?= htmlspecialchars($agent['login']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <footer>
            <p>&copy; 2025 Laboratoires Medis. Tous droits réservés.</p>
            <p>📍 Rue de l'Innovation, Nabeul, Tunisie</p>
            <p>📞 +216 72 000 000 | 📧 contact@medis.com.tn</p>
        </footer>
    </main>
    <script>
        // Recherche dynamique
        const searchInput = document.getElementById("search");
        searchInput.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#agentTable tbody tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>
</html>
