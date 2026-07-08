<?php
$query = trim($_GET['search'] ?? '');
$pageTitle = ($query !== '' ? "Résultats pour « {$query} » — " : 'Résultats — ') . 'LIVRES & VOUS';
$currentPage = '';
require __DIR__ . '/includes/head.php';

// Démonstration — dans une vraie application, cette recherche interrogerait la base de données.
$catalogueDemo = [
    ['titre' => 'Les Misérables',        'auteur' => 'Victor Hugo',        'categorie' => 'Romans'],
    ['titre' => 'Le Petit Prince',       'auteur' => 'Antoine de Saint-Exupéry', 'categorie' => 'Jeunesse'],
    ['titre' => 'Une brève histoire du temps', 'auteur' => 'Stephen Hawking', 'categorie' => 'Sciences'],
    ['titre' => 'Les Fleurs du mal',     'auteur' => 'Charles Baudelaire',  'categorie' => 'Poésie'],
    ['titre' => 'Vingt mille lieues sous les mers', 'auteur' => 'Jules Verne', 'categorie' => 'Romans'],
];

$resultats = [];
if ($query !== '') {
    foreach ($catalogueDemo as $livre) {
        $haystack = mb_strtolower($livre['titre'] . ' ' . $livre['auteur']);
        if (str_contains($haystack, mb_strtolower($query))) {
            $resultats[] = $livre;
        }
    }
}
?>
        <header>
            <p class="eyebrow">Recherche</p>
            <h1><?= $query !== '' ? 'Résultats pour « ' . htmlspecialchars($query) . ' »' : 'Recherchez un livre' ?></h1>
            <p class="lead">
                <?= count($resultats) ?> résultat<?= count($resultats) > 1 ? 's' : '' ?> trouvé<?= count($resultats) > 1 ? 's' : '' ?>.
            </p>
        </header>

        <section class="search-section" aria-label="Nouvelle recherche">
            <form action="results.php" method="GET">
                <label for="search" class="sr-only">Rechercher par titre ou auteur</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="<?= htmlspecialchars($query) ?>"
                    placeholder="Rechercher par titre ou auteur"
                    required
                    minlength="2"
                    autocomplete="off"
                />
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="book-section" aria-label="Résultats de recherche">
            <?php if ($query === ''): ?>
            <div class="empty-state">
                <p class="eyebrow">En attente</p>
                <h2>Saisissez un titre ou un auteur</h2>
                <p>Utilisez le champ ci-dessus pour lancer votre recherche.</p>
            </div>
            <?php elseif (empty($resultats)): ?>
            <div class="empty-state">
                <p class="eyebrow">Aucun résultat</p>
                <h2>Aucun livre ne correspond à « <?= htmlspecialchars($query) ?> »</h2>
                <p>Vérifiez l'orthographe ou essayez un autre terme, comme un genre littéraire.</p>
            </div>
            <?php else: ?>
            <div class="book-grid">
                <?php foreach ($resultats as $livre): ?>
                <article class="book-card">
                    <div class="book-spine" aria-hidden="true"></div>
                    <div class="book-info">
                        <p class="book-category"><?= htmlspecialchars($livre['categorie']) ?></p>
                        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p class="book-author"><?= htmlspecialchars($livre['auteur']) ?></p>
                        <form action="liste.php" method="POST" class="book-add-form">
                            <input type="hidden" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" />
                            <button type="submit">Ajouter à ma liste</button>
                        </form>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
