<?php
require_once '../config/db.php';

// Récupérer tous les utilisateurs
function getAllUtilisateurs() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Utilisateurs");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter un utilisateur
function addUtilisateur($nom, $prenom, $login, $mdp) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, login, mdp) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $login, $mdp]);
}

// Supprimer un utilisateur
function deleteUtilisateur($matricule) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Utilisateurs WHERE matricule = ?");
    return $stmt->execute([$matricule]);
}

// Mettre à jour un utilisateur
function updateUtilisateur($matricule, $nom, $prenom, $login, $mdp) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE Utilisateurs SET nom = ?, prenom = ?, login = ?, mdp = ? WHERE matricule = ?");
    return $stmt->execute([$nom, $prenom, $login, $mdp, $matricule]);
}

// Récupérer un utilisateur par son matricule
function getUtilisateurByMatricule($matricule) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE matricule = ?");
    $stmt->execute([$matricule]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$utilisateurs = getAllUtilisateurs();
$action = $_GET['action'] ?? '';
$matricule = $_GET['matricule'] ?? null;
$nom = $prenom = $login = $mdp = '';

if ($action === 'modifier' && $matricule) {
    $utilisateur = getUtilisateurByMatricule($matricule);
    if ($utilisateur) {
        $matricule = $utilisateur['matricule'];
        $nom = $utilisateur['nom'];
        $prenom = $utilisateur['prenom'];
        $login = $utilisateur['login'];
        $mdp = $utilisateur['mdp'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = $_POST['matricule'] ?? null;
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $login = $_POST['login'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $action = $_POST['action'] ?? '';

    if (empty($nom) || empty($prenom) || empty($login) || empty($mdp)) {
        echo "<script>alert('Tous les champs sont obligatoires.');</script>";
    } else {
        if ($action === 'ajouter') {
            addUtilisateur($nom, $prenom, $login, $mdp);
        } elseif ($action === 'modifier') {
            updateUtilisateur($matricule, $nom, $prenom, $login, $mdp);
        }
        header("Location: gestion_utilisateurs.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'supprimer') {
    $matricule = $_POST['matricule'] ?? null;
    if ($matricule) {
        deleteUtilisateur($matricule);
        echo "<script>alert('Utilisateur supprimé avec succès.');</script>";
    }
    header("Location: gestion_utilisateurs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
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
        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        input[type="submit"], button, .action-buttons a {
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        .action-buttons {
            margin-bottom: 20px;
        }
        .search-input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
        </nav>
    </aside>
    <main class="main-content">
        <h2>Gestion des Utilisateurs – Laboratoires Medis</h2>
        <?php if ($action === 'ajouter' || $action === 'modifier'): ?>
            <div class="form-container">
                <h3><?= $action === 'modifier' ? 'Modifier un Utilisateur' : 'Ajouter un Utilisateur' ?></h3>
                <form method="post">
                    <input type="hidden" name="action" value="<?= $action ?>">
                    <input type="hidden" name="matricule" value="<?= htmlspecialchars($matricule) ?>">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>
                    <label>Login</label>
                    <input type="text" name="login" value="<?= htmlspecialchars($login) ?>" required>
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" value="<?= htmlspecialchars($mdp) ?>" required>
                    <input type="submit" value="<?= $action === 'modifier' ? 'Modifier' : 'Ajouter' ?>">
                </form>
            </div>
        <?php else: ?>
            <div class="form-container">
                <input type="text" id="search" class="search-input" placeholder="🔍 Rechercher un utilisateur...">
                <div class="title-container">
                    <h3>📋 Liste des Utilisateurs</h3>
                    <div class="action-buttons">
                        <a href="gestion_utilisateurs.php?action=ajouter">➕ Ajouter un Utilisateur</a>
                    </div>
                </div>
                <table id="utilisateurTable">
                    <thead>
                        <tr>
                            <th data-column="0">Matricule</th>
                            <th data-column="1">Nom</th>
                            <th data-column="2">Prénom</th>
                            <th data-column="3">Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['matricule']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['login']) ?></td>
                            <td>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="matricule" value="<?= $utilisateur['matricule'] ?>">
                                    <button style="background-color:#e74c3c;">🗑</button>
                                </form>
                                <a href="gestion_utilisateurs.php?action=modifier&matricule=<?= $utilisateur['matricule'] ?>">
                                    <button style="background-color:#1e3799;">✏️</button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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
            const rows = document.querySelectorAll("#utilisateurTable tbody tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Tri dynamique
        document.querySelectorAll("#utilisateurTable th[data-column]").forEach(th => {
            th.addEventListener("click", () => {
                const table = th.closest("table");
                const tbody = table.querySelector("tbody");
                const index = parseInt(th.getAttribute("data-column"));
                const rows = Array.from(tbody.querySelectorAll("tr"));
                const asc = th.classList.toggle("asc");
                rows.sort((a, b) => {
                    const cellA = a.children[index].textContent.trim();
                    const cellB = b.children[index].textContent.trim();
                    return asc
                        ? cellA.localeCompare(cellB, undefined, { numeric: true })
                        : cellB.localeCompare(cellA, undefined, { numeric: true });
                });
                tbody.innerHTML = "";
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    </script>
</body>
</html>
