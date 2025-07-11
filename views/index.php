<?php
session_start();
require_once '../config/db.php';

$error = ""; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if (empty($login) || empty($mdp)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $mdp === $user['mdp']) {
                $_SESSION['user'] = $user;
                $id = $user['matricule'];

                $stmtAdmin = $pdo->prepare("SELECT * FROM Administrateurs WHERE id_admin = ?");
                $stmtAdmin->execute([$id]);
                $stmtAgent = $pdo->prepare("SELECT * FROM Agents WHERE id_agent = ?");
                $stmtAgent->execute([$id]);

                if ($stmtAdmin->fetch()) {
                    $_SESSION['role'] = 'admin';
                    header("Location: ../views/dashboard_admin.php");
                    exit;
                } elseif ($stmtAgent->fetch()) {
                    $_SESSION['role'] = 'agent';
                    header("Location: ../views/dashboard_agent.php");
                    exit;
                } else {
                    $_SESSION['role'] = 'utilisateur';
                    header("Location: ../views/dashboard_user.php");
                    exit;
                }
            } else {
                $error = "Login ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion Stock | Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0c2461 0%, #1e3799 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            display: flex;
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .login-panel {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        .logo img {
            width: 80px;
            height: 80px;
        }
        .logo h1 {
            color: #0c2461;
            font-size: 28px;
            font-weight: 800;
            margin: 0;
        }
        .logo p {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 14px;
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }
        .input-group {
            margin-bottom: 25px;
            position: relative;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 15px;
        }
        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e7ff;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .input-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 42px;
            color: #7f8c8d;
            font-size: 18px;
        }
        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, #0c2461, #1e3799);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(12, 36, 97, 0.3);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(12, 36, 97, 0.4);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .login-panel {
                padding: 40px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-panel">
            <div class="logo">
                <img src="../icon/images.jpg" alt="Medis Logo">
                <h1>Gestion Stock</h1>
            </div>
            <form method="post">
                <div class="input-group">
                    <label for="login">Identifiant :</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="login" name="login" placeholder="Entrer votre identifiant" required />
                </div>
                <div class="input-group">
                    <label for="mdp">Mot de passe :</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="mdp" name="mdp" placeholder="Entrer votre mot de passe" required />
                </div>
                <button type="submit">Connexion</button>
                <?php if (!empty($error)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </form>
            <div class="footer">
                <p>&copy; 2025 Les Laboratoires Medis. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
