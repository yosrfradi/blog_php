<?php
require("connect.php");
require("user.php");

$dsn = "mysql:host=" . SERVER . ";dbname=" . BASE;
try {
    $conn = new PDO($dsn, USER, PASSWD);
} catch (PDOException $e) {
    echo "Echec de la connexion : " . $e->getMessage();
    exit();
}

function chargerClass($classname)
{
    require $classname . '.php';
}
spl_autoload_register("chargerClass");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$manager = new Manager($conn);

if (isset($_POST['ajout'])) {
    $donnees = array (
        'nomUtilisateur' => $_POST['nomUtilisateur'],
        'preUtilisateur' => $_POST['preUtilisateur'],
        'email' => $_POST['email'],
        'mdp' => $_POST['mdp']
    );

    $utilisateur = new Utilisateur($donnees);
    $manager->ajout($utilisateur);
    // Redirection vers une autre page
    header("Location: post.php");
    exit();
}
?>