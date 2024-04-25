<?php
require("connect.php");

$dsn = "mysql:host=" . SERVER . ";dbname=" . BASE;
try {
    $conn = new PDO($dsn, USER, PASSWD);
} catch (PDOException $e) {
    echo "Echec de la connexion : " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['mdp'])) {

    $conn = new PDO($dsn, USER, PASSWD);
        // Définir l'attribut PDO pour signaler les erreurs
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Query the database to check if the email and password match
    $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mdp = :mdp";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->execute();

    // Check if there is a matching record
    if ($stmt->rowCount() > 0) {
        // User is authenticated
        header("Location: post.php");
        exit();
        
        // Redirect to a welcome page or perform further actions
    } else {
        // User credentials are invalid
        echo "Invalid email or password. Please try again.";
        // You can redirect back to the login page or display an error message
    }
}
?>
