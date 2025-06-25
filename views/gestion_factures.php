<?php
require_once '../config/db.php';

// Fonctions
function getAllFactures() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Factures");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProduits() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Produits");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFactureByNum($num) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Factures WHERE num = ?");
    $stmt->execute([$num]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function supprimerFacture($num) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Factures WHERE num = ?");
    return $stmt->execute([$num]);
}

function saveFacture($fournisseur, $prix, $date, $idP, $num = null) {
    global $pdo;
    if ($num) {
        $stmt = $pdo->prepare("UPDATE Factures SET fournisseur = ?, prix = ?, date = ?, idP = ? WHERE num = ?");
        return $stmt->execute([$fournisseur, $prix, $date, $idP, $num]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Factures (fournisseur, prix, date, idP) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$fournisseur, $prix, $date, $idP]);
    }
}

// Gestion POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'supprimer') {
            $num = $_POST['num'] ?? null;
            if ($num) {
                supprimerFacture($num);
                echo "<script>alert('Facture supprimée avec succès.');</script>";
                header("Refresh:0; url=gestion_factures.php");
                exit;
            }
        } elseif ($_POST['action'] === 'ajouter' || $_POST['action'] === 'modifier') {
            $fournisseur = $_POST['fournisseur'] ?? '';
            $prix = $_POST['prix'] ?? '';
            $date = $_POST['date'] ?? '';
            $idP = $_POST['idP'] ?? '';
            $num = $_POST['num'] ?? null;

            if (empty($fournisseur) || empty($prix) || empty($date) || empty($idP)) {
                echo "<script>alert('Tous les champs sont obligatoires.');</script>";
            } else {
                saveFacture($fournisseur, $prix, $date, $idP, $num);
                header("Location: gestion_factures.php");
                exit;
            }
        }
    }
}

// Variables pour formulaire
$action = $_GET['action'] ?? '';
$num = $_GET['num'] ?? null;
$fournisseur = $prix = $date = $idP = '';

if ($action === 'modifier' && $num) {
    $facture = getFactureByNum($num);
    if ($facture) {
        $fournisseur = $facture['fournisseur'];
        $prix = $facture['prix'];
        $date = $facture['date'];
        $idP = $facture['idP'];
    }
}

$factures = getAllFactures();
$produits = getAllProduits();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des Factures</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #0c2461;
            --primary-light: #1e3799;
            --success: #27ae60;
            --danger: #e74c3c;
            --light-gray: #e9ecef;
            --border: #dee2e6;
            --dark: #343a40;
        }
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f7fb;
            min-height: 100vh;
            color: var(--dark);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            max-width: 900px;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid var(--border);
        }
        th, td {
            padding: 10px 15px;
            text-align: left;
        }
        th {
            background-color: var(--light-gray);
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        button, input[type=submit] {
            cursor: pointer;
            border: none;
            border-radius: 6px;
            padding: 8px 14px;
            font-weight: 600;
            background-color: var(--primary);
            color: white;
            transition: background-color 0.3s ease;
        }
        button:hover, input[type=submit]:hover {
            background-color: var(--primary-light);
        }
        a.button-link {
            text-decoration: none;
            background-color: var(--success);
            padding: 8px 14px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            margin-left: 10px;
        }
        a.button-link:hover {
            background-color: #1e8449;
        }
        .actions-cell {
            min-width: 160px;
        }
        .form-container {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            max-width: 600px;
            width: 100%;
            margin-bottom: 50px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input[type=text], input[type=number], input[type=date], select {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 18px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h2>Gestion des Factures</h2>

    <?php if ($action === 'ajouter' || $action === 'modifier'): ?>
        <!-- Formulaire ajout / modification -->
        <div class="form-container">
            <h3><?= $action === 'modifier' ? 'Modifier une Facture' : 'Ajouter une Facture' ?></h3>
            <form method="post" action="">
                <input type="hidden" name="action" value="<?= $action ?>">
                <?php if ($action === 'modifier'): ?>
                    <input type="hidden" name="num" value="<?= htmlspecialchars($num) ?>">
                <?php endif; ?>

                <label for="fournisseur">Fournisseur :</label>
                <input type="text" id="fournisseur" name="fournisseur" value="<?= htmlspecialchars($fournisseur) ?>" required>

                <label for="prix">Prix :</label>
                <input type="number" step="0.01" id="prix" name="prix" value="<?= htmlspecialchars($prix) ?>" required>

                <label for="date">Date :</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>

                <label for="idP">Produit :</label>
                <select id="idP" name="idP" required>
                    <option value="" disabled <?= $idP === '' ? 'selected' : '' ?>>-- Sélectionner un produit --</option>
                    <?php foreach ($produits as $produit): ?>
                        <option value="<?= $produit['idP'] ?>" <?= $produit['idP'] == $idP ? 'selected' : '' ?>>
                            <?= htmlspecialchars($produit['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="<?= $action === 'modifier' ? 'Modifier' : 'Ajouter' ?>">
                <a href="gestion_factures.php" style="margin-left: 15px; font-weight: 600; color: var(--danger); cursor: pointer;">Annuler</a>
            </form>
        </div>
    <?php else: ?>
        <!-- Liste des factures + bouton Ajouter -->
        <a href="?action=ajouter" style="margin-bottom: 15px; align-self: flex-start;">
            <button><i class="fas fa-plus"></i> Ajouter une facture</button>
        </a>

        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Fournisseur</th>
                    <th>Prix</th>
                    <th>Date</th>
                    <th>Produit</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($factures)): ?>
                    <tr><td colspan="6" style="text-align:center;">Aucune facture trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($factures as $facture): ?>
                        <tr>
                            <td><?= htmlspecialchars($facture['num']) ?></td>
                            <td><?= htmlspecialchars($facture['fournisseur']) ?></td>
                            <td><?= htmlspecialchars($facture['prix']) ?></td>
                            <td><?= htmlspecialchars($facture['date']) ?></td>
                            <td>
                                <?php
                                $prodName = '';
                                foreach ($produits as $p) {
                                    if ($p['idP'] == $facture['idP']) {
                                        $prodName = $p['nom'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($prodName);
                                ?>
                            </td>
                            <td>
                                <form method="post" action="" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                                    <input type="hidden" name="action" value="supprimer" />
                                    <input type="hidden" name="num" value="<?= htmlspecialchars($facture['num']) ?>" />
                                    <button type="submit"><i class="fas fa-trash"></i> Supprimer</button>
                                </form>

                                <a href="?action=modifier&num=<?= htmlspecialchars($facture['num']) ?>" class="button-link" style="margin-left:8px;">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
