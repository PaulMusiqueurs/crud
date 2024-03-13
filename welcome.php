<?php
session_start();
include 'includes\fonction_hash.php';

// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: authentification.php");
    exit;
}

// Connexion à la base de données
$host = "localhost";
$dsn = 'mysql:dbname=authentification;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit;
}

// Récupérer tous les utilisateurs
try {
    $query = $dbh->query("SELECT * FROM authentifier");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord des utilisateurs</title>
    <link rel="stylesheet" href="assets/welcome.css">
</head>
<body>
    <div class="container">
        <h1>Tableau de bord des utilisateurs</h1>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_login']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="edit-btn">Modifier</a>
                            <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_user.php" class="add-btn">Ajouter un utilisateur</a>
    </div>
    <script src="assets/welcome.js"></script>
</body>
</html>