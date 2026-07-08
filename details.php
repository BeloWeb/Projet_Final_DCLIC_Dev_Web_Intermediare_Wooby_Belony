<?php
require __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération sécurisée de l'ID du livre depuis l'URL
$id_livre = (int)($_GET['id'] ?? 0);

// 1. Requête pour récupérer les informations de l'ouvrage sélectionné
$stmt = $pdo->prepare('SELECT * FROM Livres WHERE id = :id');
$stmt->execute(['id' => $id_livre]);
$livre = $stmt->fetch();

// Si le livre n'existe pas en base de données, retour automatique au catalogue
if (!$livre) {
    header('Location: catalogue.php');
    exit;
}

// 2. Requête pour récupérer TOUS les livres de la base de données pour la barre latérale
try {
    $stmtTous = $pdo->query('SELECT id, titre, auteur FROM Livres ORDER BY titre ASC');
    $tousLesLivres = $stmtTous->fetchAll();
} catch (PDOException $e) {
    $tousLesLivres = [];
}

$categories = [
    'romans'   => 'Romans',
    'sciences' => 'Sciences',
    'jeunesse' => 'Jeunesse',
    'poesie'   => 'Poésie',
];

$pageTitle = htmlspecialchars($livre['titre']) . ' — LIVRES & VOUS';
$currentPage = 'catalogue.php';
require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow"><?= htmlspecialchars($categories[$livre['categorie']] ?? 'Ouvrage') ?></p>
            <h1><?= htmlspecialchars($livre['titre']) ?></h1>
            <p class="lead">Un ouvrage de <?= htmlspecialchars($livre['auteur']) ?></p>
        </header>

        <div class="main-details-container" style="display: grid; grid-template-columns: 280px 1fr; gap: 2.5rem; margin-top: 2rem; align-items: start;">
            
            <aside class="books-sidebar" style="background: #fff; border: 1px solid #eaeaea; border-radius: 12px; padding: 1.25rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                <h2 style="font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #000;">
                    Tous les ouvrages (<?= count($tousLesLivres) ?>)
                </h2>
                
                <ul class="sidebar-links-list" style="list-style: none; padding: 0; margin: 0; max-height: 600px; overflow-y: auto; pr: 5px;">
                    <?php foreach ($tousLesLivres as $unLivre): 
                        $isCurrent = ($unLivre['id'] === $id_livre);
                    ?>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="details.php?id=<?= (int)$unLivre['id'] ?>" 
                               style="display: block; padding: 0.6rem 0.75rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; transition: all 0.2s;
                                      <?= $isCurrent ? 'background: #000; color: #fff; font-weight: 500;' : 'color: #333; background: #f9f9f9;' ?>"
                               onmouseover="if(!<?= $isCurrent ? 'true' : 'false' ?>) this.style.background='#f0f0f0';"
                               onmouseout="if(!<?= $isCurrent ? 'true' : 'false' ?>) this.style.background='#f9f9f9';"
                            >
                                <span style="display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($unLivre['titre']) ?>">
                                    <?= htmlspecialchars($unLivre['titre']) ?>
                                </span>
                                <small style="display: block; font-size: 0.75rem; color: <?= $isCurrent ? '#ccc' : '#777' ?>;">
                                    <?= htmlspecialchars($unLivre['auteur']) ?>
                                </small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="details-section" aria-label="Détails de l'ouvrage choisi" style="margin: 0;">
                <div class="details-grid" style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 2rem; align-items: start;">
                    
                    <div class="book-cover-container" style="background: #f9f9f9; padding: 2rem; border-radius: 12px; border: 1px solid #eaeaea; text-align: center;">
                        <?php if (!empty($livre['image']) && file_exists(__DIR__ . '/images/' . $livre['image'])): ?>
                            <img src="images/<?= htmlspecialchars($livre['image']) ?>" 
                                 alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>" 
                                 style="max-width: 100%; height: auto; border-radius: 6px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);" />
                        <?php else: ?>
                            <div class="book-spine-placeholder" style="font-size: 6rem; padding: 4rem 0; background: #f0f0f0; border-radius: 6px; border: 2px dashed #ccc;">
                                📖
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="book-details-content">
                        <div style="margin-bottom: 2rem;">
                            <h3 style="font-size: 1.4rem; margin-bottom: 1rem;">Résumé de l'œuvre</h3>
                            <p style="line-height: 1.7; color: #4a4a4a; font-size: 1.05rem; margin: 0;">
                                <?= nl2br(htmlspecialchars($livre['description'] ?? 'Aucun résumé disponible pour cet ouvrage.')) ?>
                            </p>
                        </div>

                        <div class="specs-box" style="background: #fcfcfc; border: 1px solid #eee; padding: 1.25rem; border-radius: 8px; margin-bottom: 2rem; font-size: 0.95rem;">
                            <p style="margin: 0 0 0.75rem 0;"><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                            <p style="margin: 0 0 0.75rem 0;"><strong>Éditeur :</strong> <?= htmlspecialchars($livre['maison_edition'] ?? 'Non spécifiée') ?></p>
                            <p style="margin: 0 0 0.75rem 0;"><strong>Rayon :</strong> <?= htmlspecialchars($categories[$livre['categorie']] ?? 'Général') ?></p>
                            <p style="margin: 0;"><strong>Disponibilité :</strong> <?= (int)$livre['nombre_exemplaire'] ?> exemplaire(s) en rayon</p>
                        </div>

                        <div class="details-actions" style="display: flex; gap: 1rem; align-items: center;">
                            <form action="liste.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="id_livre" value="<?= (int)$livre['id'] ?>" />
                                <input type="hidden" name="action" value="ajouter" />
                                <button type="submit" class="btn-add-wishlist" style="padding: 0.85rem 1.75rem; background: #000; color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; transition: background 0.2s;">
                                    Ajouter à ma liste de lecture
                                </button>
                            </form>
                            
                            <a href="catalogue.php" style="color: #666; text-decoration: none; font-size: 0.95rem; margin-left: 0.5rem;">
                                ← Retourner au catalogue
                            </a>
                        </div>
                    </div>

                </div>
            </section>
        </div>

<?php require __DIR__ . '/includes/footer.php'; ?>