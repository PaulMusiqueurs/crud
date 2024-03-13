<?php

session_start();
include 'includes\fonction_hash.php';

// Connexion à la base de données
$host = "localhost";
$dsn = 'mysql:dbname=authentification;host=127.0.0.1';
$user = "root";
$password = '';
$message = ""; // Variable pour stocker le message d'erreur

if(!empty($_POST)){

    $dbh = new PDO($dsn, $user, $password);

    // Récupération des données du formulaire
    $login = $_POST['username']; // Utiliser 'username' au lieu de 'email' pour le champ de connexion
    $password = $_POST['password']; // Ne pas hasher le mot de passe pour la comparaison

    // Vérification si l'email et le mot de passe correspondent dans la base de données
    $query = $dbh->prepare("SELECT * FROM authentifier WHERE user_login = ?");
    $query->execute([$login]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['user_password'])) {
        // Définir une variable de session pour indiquer que l'utilisateur est connecté
        $_SESSION['user_id'] = $user['user_login']; // Assurez-vous de remplacer 'id' par le nom de la colonne contenant l'identifiant de l'utilisateur dans votre base de données

        // L'utilisateur est authentifié avec succès
        header("Location: welcome.php");
        exit;
    } else {
        // L'email ou le mot de passe n'est pas bon
        $message = "L'email ou le mot de passe n'est pas bon";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projet1</title>
    <link rel="stylesheet" href="assets\authentification.css">
</head>
<body>
    <div class="container">
        <form action="authentification.php" method="post">
            <h2>Connexion</h2>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" name="username" required>
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" required>
            <button type="submit">Se connecter</button>
            <a href="inscription.php">Pas encore inscrit ?</a>
            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
