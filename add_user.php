<?php
session_start();
include 'includes\fonction_hash.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: authentification.php");
    exit;
}

// Connexion à la base de données
$host = "localhost";
$dsn = 'mysql:dbname=authentification;host=127.0.0.1';
$user = "root";
$password = '';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si les données attendues existent dans $_POST
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Récupérer les données du formulaire
        $email = $_POST['email'];
        $user_password = $_POST['password'];
        $hashed_password = hashPassword($user_password);

        try {
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insérer le nouvel utilisateur dans la base de données
            $stmt = $dbh->prepare("INSERT INTO authentifier (user_login, user_password) VALUES (?, ?)");
            $stmt->execute([$email, $hashed_password]); // Utiliser le mot de passe non haché

            // Rediriger vers la page welcome.php après l'ajout de l'utilisateur
            header("Location: welcome.php");
            exit;
        } catch (PDOException $e) {
            echo 'Erreur lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage();
            exit;
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="assets\add_user.css">
</head>
<body>
    <h1>Ajouter un utilisateur</h1>
    <form action="add_user.php" method="post">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>