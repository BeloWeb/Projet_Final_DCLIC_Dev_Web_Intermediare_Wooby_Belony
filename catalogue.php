<?php
// Connexion à la base de données
require __DIR__ . '/includes/db.php';

$pageTitle = 'Catalogue — LIVRES & VOUS';
$currentPage = 'catalogue.php';
require __DIR__ . '/includes/head.php';

$categories = [
    'tous'     => 'Tous',
    'romans'   => 'Romans',
    'sciences' => 'Sciences',
    'jeunesse' => 'Jeunesse',
    'poesie'   => 'Poésie',
];

$selected = $_GET['categorie'] ?? 'tous';
if (!array_key_exists($selected, $categories)) {
    $selected = 'tous';
}

// --- RÉCUPÉRATION DYNAMIQUE DES LIVRES DEPUIS LA BASE DE DONNÉES ---
try {
    if ($selected === 'tous') {
        // Sélectionne tous les livres de la collection
        $stmt = $pdo->query('SELECT * FROM Livres ORDER BY titre ASC');
        $livres = $stmt->fetchAll();
    } else {
        // Sélectionne uniquement les livres de la catégorie choisie (requête sécurisée)
        $stmt = $pdo->prepare('SELECT * FROM Livres WHERE categorie = :categorie ORDER BY titre ASC');
        $stmt->execute(['categorie' => $selected]);
        $livres = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    $livres = [];
    $error = 'Une erreur est survenue lors du chargement du catalogue.';
}
?>
        <header>
            <p class="eyebrow">Catalogue</p>
            <h1>Parcourez l'ensemble des ouvrages</h1>
            <p class="lead">Filtrez par rayon ou lancez une recherche précise depuis l'accueil.</p>
        </header>

        <section class="filter-section" aria-label="Filtrer par catégorie">
            <ul class="filter-chips">
                <?php foreach ($categories as $slug => $label): ?>
                <li>
                    <a
                        class="filter-chip<?= $slug === $selected ? ' active' : '' ?>"
                        href="catalogue.php<?= $slug === 'tous' ? '' : '?categorie=' . urlencode($slug) ?>"
                    ><?= htmlspecialchars($label) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="book-section" aria-label="Résultats du catalogue">
            <?php if (isset($error)): ?>
                <p class="form-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (empty($livres)): ?>
            <div class="empty-state">
                <p class="eyebrow">Rayon vide</p>
                <h2>Aucun livre dans cette catégorie pour l'instant</h2>
                <p>Revenez bientôt, ce rayon se remplit chaque semaine.</p>
            </div>
            <?php else: ?>
            <div class="book-grid">
                <?php foreach ($livres as $livre): ?>
                <article class="book-card">
                    <div class="book-spine" aria-hidden="true"></div>
                    <div class="book-info">
                        <p class="book-category">
                            <?= htmlspecialchars($categories[$livre['categorie']] ?? 'Général') ?>
                        </p>
                        
                        <h3>
                            <a href="details.php?id=<?= (int)$livre['id'] ?>" style="color: inherit; text-decoration: none;">
                                <?= htmlspecialchars($livre['titre']) ?>
                            </a>
                        </h3>
                        
                        <p class="book-author">
                            <?= htmlspecialchars($livre['auteur']) ?> 
                            <?php if (!empty($livre['annee'])): ?>
                                · <?= (int)$livre['annee'] ?>
                            <?php elseif (!empty($livre['maison_edition'])): ?>
                                · <?= htmlspecialchars($livre['maison_edition']) ?>
                            <?php endif; ?>
                        </p>

                        <div style="margin: 0.5rem 0 1rem 0;">
                            <a href="details.php?id=<?= (int)$livre['id'] ?>" class="book-details-link" style="font-size: 0.9rem; color: #666; text-decoration: underline;">
                                Voir les détails
                            </a>
                        </div>
                        
                        <form action="liste.php" method="POST" class="book-add-form">
                            <input type="hidden" name="id_livre" value="<?= (int)$livre['id'] ?>" />
                            <input type="hidden" name="action" value="ajouter" />
                            <button type="submit">Ajouter à ma liste</button>
                        </form>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>