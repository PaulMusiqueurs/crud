<?php
session_start();
include 'includes\fonction_hash.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: authentification.php");
    exit;
}

// Vérifier si l'ID de l'utilisateur à modifier est passé dans l'URL
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

// Récupérer les informations de l'utilisateur à modifier
try {
    $stmt = $dbh->prepare("SELECT * FROM authentifier WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage();
    exit;
}

// Vérifier si le formulaire est soumis pour mettre à jour l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si les données attendues existent dans $_POST
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Récupérer les données du formulaire
        $new_email = $_POST['email'];
        $new_password = $_POST['password'];

        // Hasher le nouveau mot de passe
        $hashed_password = hashPassword($new_password); // Remplacez fonction_hash par la fonction de hash réelle que vous utilisez

        // Mettre à jour l'email et le mot de passe de l'utilisateur dans la base de données
        try {
            $stmt = $dbh->prepare("UPDATE authentifier SET user_login = ?, user_password = ? WHERE user_id = ?");
            $stmt->execute([$new_email, $hashed_password, $user_id]);

            // Rediriger vers la page welcome.php après la mise à jour de l'utilisateur
            header("Location: welcome.php");
            exit;
        } catch (PDOException $e) {
            echo 'Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage();
            exit;
        }
    } else {
        echo "L'email et le mot de passe sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
    <link rel="stylesheet" href="assets\welcome.css">
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="post">
        <label for="email">Nouvel email :</label>
        <input type="email" id="email" name="email" value="<?php echo $user['user_login']; ?>" required>
        <label for="password">Nouveau mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>