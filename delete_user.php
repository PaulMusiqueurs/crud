<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: authentification.php");
    exit;
}

// Vérifier si l'ID de l'utilisateur à supprimer est passé dans l'URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "ID de l'utilisateur non fourni.";
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

// Supprimer l'utilisateur de la base de données
try {
    $stmt = $dbh->prepare("DELETE FROM authentifier WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Rediriger vers la page welcome.php après la suppression de l'utilisateur
    header("Location: welcome.php");
    exit;
} catch (PDOException $e) {
    echo 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage();
    exit;
}
?>