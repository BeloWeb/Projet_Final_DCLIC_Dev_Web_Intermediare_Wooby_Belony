<?php
// Initialisation sécurisée de la session pour détecter si l'utilisateur est connecté
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'LIVRES & VOUS — Bibliothèque en ligne';
$currentPage = $currentPage ?? '';

function nav_active(string $page, string $current): string {
    return $page === $current ? ' class="active"' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="LIVRES & VOUS — recherchez, découvrez et ajoutez des livres à votre liste de lecture." />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>

    <div class="page-shell">

        <nav class="site-nav">
            <a class="brand" href="index.php">
                <span class="brand-mark">L&amp;V</span>
                <span class="brand-name">LIVRES &amp; VOUS</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php"<?= nav_active('index.php', $currentPage) ?>>Accueil</a></li>
                <li><a href="catalogue.php"<?= nav_active('catalogue.php', $currentPage) ?>>Catalogue</a></li>
                <li><a href="liste.php"<?= nav_active('liste.php', $currentPage) ?>>Ma liste</a></li>
                <li><a href="contact.php"<?= nav_active('contact.php', $currentPage) ?>>Contact</a></li>
            </ul>
            
            <div class="nav-actions">
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <span class="nav-welcome">Bonjour, <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?></span>
                    <a class="nav-cta nav-cta-danger" href="deconnexion.php">Déconnexion</a>
                <?php else: ?>
                    <a class="nav-auth-link" href="connexion.php">Connexion</a>
                    <a class="nav-cta" href="inscription.php">S'inscrire</a>
                <?php endif; ?>
            </div>
        </nav>