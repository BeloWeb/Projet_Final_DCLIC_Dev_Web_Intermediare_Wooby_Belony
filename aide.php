<?php
$pageTitle = "Centre d'aide — LIVRES & VOUS";
$currentPage = 'aide.php';
require __DIR__ . '/includes/head.php';

$faq = [
    [
        'question' => "Comment ajouter un livre à ma liste de lecture ?",
        'reponse'  => "Depuis le catalogue, cliquez sur « Ajouter à ma liste » sous le livre souhaité. Il apparaîtra aussitôt dans la page « Ma liste ».",
    ],
    [
        'question' => "Puis-je changer le statut d'un livre (à lire, en cours, terminé) ?",
        'reponse'  => "Oui, depuis votre liste de lecture vous pouvez faire évoluer le statut de chaque livre au fil de votre lecture.",
    ],
    [
        'question' => "Comment réinitialiser mon mot de passe ?",
        'reponse'  => "Sur la page de connexion, utilisez le lien « Mot de passe oublié » ou contactez notre équipe directement.",
    ],
    [
        'question' => "La bibliothèque est-elle gratuite ?",
        'reponse'  => "La recherche, la consultation du catalogue et la liste de lecture sont entièrement gratuites.",
    ],
];
?>
        <header>
            <p class="eyebrow">Assistance</p>
            <h1>Centre d'aide</h1>
            <p class="lead">Les réponses aux questions les plus fréquentes. Besoin d'autre chose ? <a href="contact.php">Contactez-nous</a>.</p>
        </header>

        <section class="faq-section" aria-label="Questions fréquentes">
            <div class="faq-list">
                <?php foreach ($faq as $item): ?>
                <details class="faq-item">
                    <summary><?= htmlspecialchars($item['question']) ?></summary>
                    <p><?= htmlspecialchars($item['reponse']) ?></p>
                </details>
                <?php endforeach; ?>
            </div>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
