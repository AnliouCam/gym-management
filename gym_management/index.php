<?php
session_start();
require_once "config.php"; // Assurez-vous que ce fichier contient la connexion à la base de données

// Redirige si déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $pin = trim($_POST["pin"]);

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND pin_code = ?");
    $stmt->execute([$nom, $pin]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION["user"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Nom ou code PIN incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GYM PRO</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 50px 40px;
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 16px;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            padding: 15px;
            border-radius: var(--radius-md);
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid var(--danger-color);
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group .input-icon {
            position: absolute;
            left: 15px;
            top: 48px;
            font-size: 18px;
            color: var(--text-secondary);
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 15px;
            transition: var(--transition);
            background: var(--bg-light);
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: var(--bg-card);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--text-white);
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--text-secondary);
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-footer a:hover {
            color: #764ba2;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 40px 30px;
            }

            .login-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>GYM PRO</h1>
                <p>Connectez-vous pour continuer</p>
            </div>

            <form action="" method="POST">
                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="nom">Nom d'utilisateur</label>
                    <span class="input-icon">👤</span>
                    <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required autofocus>
                </div>

                <div class="form-group">
                    <label for="pin">Code PIN</label>
                    <span class="input-icon">🔒</span>
                    <input type="password" id="pin" name="pin" placeholder="Entrez votre code PIN" required>
                </div>

                <button type="submit" class="login-btn">Se connecter</button>
            </form>

            <div class="login-footer">
                <p>Pas de compte ? <a href="user.php">Créer un compte</a></p>
            </div>
        </div>
    </div>
</body>
</html>
