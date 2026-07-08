<?php
// includes/db.php

// 1. Paramètres de connexion
$host = '127.0.0.1';       // Ou 'localhost' (selon votre environnement)
$dbname = 'bibliotheque';  // Le nom de votre base de données
$username = 'root';        // Votre nom d'utilisateur (souvent 'root' en local)
$password = '';            // Votre mot de passe (souvent vide sous XAMPP/WAMP, ou 'root' sous MAMP)

// 2. Options de sécurité et de formatage de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL sous forme d'exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les données sous forme de tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Sécurité supplémentaire contre les injections SQL
];

// 3. Tentative de connexion
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // En cas d'échec, on arrête le script et on affiche un message
    // Note : en production, il vaut mieux enregistrer l'erreur dans un fichier de log plutôt que de l'afficher.
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}