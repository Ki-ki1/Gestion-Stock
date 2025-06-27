<?php
require_once '../config/db.php';

function getAllProduits() {
    global $pdo;
    return $pdo->query("SELECT * FROM Produits")->fetchAll(PDO::FETCH_ASSOC);
}

function getAllFactures() {
    global $pdo;
    return $pdo->query("SELECT * FROM factures ORDER BY num DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function getFactureByNum($num) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM factures WHERE num = ?");
    $stmt->execute([$num]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveFacture($num, $fournisseur, $date, $idP_array, $quantite_array, $prixUnitaire_array) {
    global $pdo;

    if ($num === null) {
        $stmt = $pdo->query("SHOW TABLE STATUS LIKE 'factures'");
        $num = $stmt->fetch()['Auto_increment'];
    } else {
        $stmt = $pdo->prepare("DELETE FROM factures WHERE num = ?");
        $stmt->execute([$num]);
    }

    for ($i = 0; $i < count($idP_array); $i++) {
        $idP = $idP_array[$i];
        $quantite = (int)$quantite_array[$i];
        $prix_unitaire = (float)$prixUnitaire_array[$i];
        $prix_total = $quantite * $prix_unitaire;

        if (!$idP || $quantite <= 0 || $prix_unitaire <= 0) continue;

        $stmt = $pdo->prepare("INSERT INTO factures (num, fournisseur, date, idP, quantite, prix_total, prix_unitaire) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$num, $fournisseur, $date, $idP, $quantite, $prix_total, $prix_unitaire]);
    }

    return $num;
}

function supprimerFacture($num) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM factures WHERE num = ?");
    $stmt->execute([$num]);
}

$produits = getAllProduits();
$factures = getAllFactures();

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$numToEdit = $action === 'modifier' ? ($_GET['num'] ?? $_POST['num'] ?? null) : null;
$factureToEdit = $numToEdit ? getFactureByNum($numToEdit) : [];
$fournisseur_edit = $date_edit = '';
$idP_edit = $quantite_edit = $pu_edit = [];

if ($factureToEdit) {
    $fournisseur_edit = $factureToEdit[0]['fournisseur'];
    $date_edit = $factureToEdit[0]['date'];
    foreach ($factureToEdit as $ligne) {
        $idP_edit[] = $ligne['idP'];
        $quantite_edit[] = $ligne['quantite'];
        $pu_edit[] = $ligne['prix_unitaire'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'ajouter' || $action === 'modifier') {
        $num = $action === 'modifier' ? ($_POST['num'] ?? null) : null;
        $fournisseur = $_POST['fournisseur'] ?? '';
        $date = $_POST['date'] ?? '';
        $idP_array = $_POST['idP'] ?? [];
        $quantite_array = $_POST['quantite'] ?? [];
        $prixUnitaire_array = $_POST['prix_unitaire'] ?? [];

        if (!$fournisseur || !$date || empty($idP_array)) {
            echo "<script>alert('Tous les champs sont obligatoires.');</script>";
        } else {
            saveFacture($num, $fournisseur, $date, $idP_array, $quantite_array, $prixUnitaire_array);
            header("Location: gestion_factures.php");
            exit;
        }
    } elseif ($action === 'supprimer') {
        $num = $_POST['num'] ?? null;
        if ($num) {
            supprimerFacture($num);
            header("Location: gestion_factures.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Factures</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<h2><?= $action === 'modifier' ? '✏️ Modifier une facture' : '➕ Ajouter une facture' ?></h2>
<form method="post">
    <input type="hidden" name="action" value="<?= $action === 'modifier' ? 'modifier' : 'ajouter' ?>">
    <?php if ($numToEdit): ?><input type="hidden" name="num" value="<?= $numToEdit ?>"><?php endif; ?>
    Fournisseur: <input type="text" name="fournisseur" value="<?= htmlspecialchars($fournisseur_edit) ?>" required><br>
    Date: <input type="date" name="date" value="<?= htmlspecialchars($date_edit) ?>" required><br><br>
    <div id="produits-container"></div>
    <button type="button" onclick="ajouterLigneProduit()">➕ Ajouter un produit</button><br><br>
    <input type="submit" value="<?= $action === 'modifier' ? 'Modifier la facture' : 'Ajouter la facture' ?>">
</form>

<script>
function ajouterLigneProduit(id='', quantite='', pu='') {
    const container = document.getElementById("produits-container");
    const row = document.createElement("div");
    const produitsOptions = <?php echo json_encode($produits); ?>;
    let selectHTML = '<select name="idP[]">';
    selectHTML += '<option value="">-- Choisir un produit --</option>';
    produitsOptions.forEach(p => {
        const selected = id == p.idP ? 'selected' : '';
        selectHTML += `<option value="${p.idP}" ${selected}>${p.designation}</option>`;
    });
    selectHTML += '</select>';

    row.innerHTML = selectHTML +
        ` Quantité: <input type="number" name="quantite[]" min="1" value="${quantite}" required>` +
        ` Prix unitaire: <input type="number" name="prix_unitaire[]" step="0.01" min="0" value="${pu}" required><br><br>`;
    container.appendChild(row);
}

<?php if ($numToEdit): 
for ($i = 0; $i < count($idP_edit); $i++): ?>
ajouterLigneProduit("<?= $idP_edit[$i] ?>", "<?= $quantite_edit[$i] ?>", "<?= $pu_edit[$i] ?>");
<?php endfor; else: ?>
ajouterLigneProduit();
<?php endif; ?>
</script>

<hr>
<h2>📋 Liste des factures</h2>
<table border="1" cellpadding="6">
    <tr>
        <th>#</th><th>Fournisseur</th><th>Date</th><th>Produit</th>
        <th>Quantité</th><th>PU</th><th>Total</th><th>Actions</th>
    </tr>
    <?php
    $grouped = [];
    foreach ($factures as $f) {
        $grouped[$f['num']][] = $f;
    }
    foreach ($grouped as $num => $lignes):
        $first = true;
        foreach ($lignes as $ligne): ?>
            <tr>
                <?php if ($first): ?>
                    <td rowspan="<?= count($lignes) ?>"><?= $num ?></td>
                    <td rowspan="<?= count($lignes) ?>"><?= htmlspecialchars($ligne['fournisseur']) ?></td>
                    <td rowspan="<?= count($lignes) ?>"><?= $ligne['date'] ?></td>
                <?php endif; ?>
                <td><?= $ligne['idP'] ?></td>
                <td><?= $ligne['quantite'] ?></td>
                <td><?= number_format($ligne['prix_unitaire'], 2) ?> DT</td>
                <td><?= number_format($ligne['prix_total'], 2) ?> DT</td>
                <?php if ($first): ?>
                    <td rowspan="<?= count($lignes) ?>">
                        <form method="post" style="display:inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette facture ?');">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="num" value="<?= $num ?>">
                            <button type="submit">🗑 Supprimer</button>
                        </form>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="modifier">
                            <input type="hidden" name="num" value="<?= $num ?>">
                            <button type="submit">✏️ Modifier</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php $first = false; endforeach;
    endforeach; ?>
</table>
</body>
</html>