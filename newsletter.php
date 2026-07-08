<?php
$pageTitle = 'Inscription confirmée — LIVRES & VOUS';
$currentPage = '';
require __DIR__ . '/includes/head.php';

$email = trim($_POST['email'] ?? '');
?>
        <header>
            <p class="eyebrow">Newsletter</p>
            <h1>Merci pour votre inscription</h1>
            <p class="lead">
                <?php if ($email !== ''): ?>
                    Un e-mail de confirmation a été envoyé à <?= htmlspecialchars($email) ?>.
                <?php else: ?>
                    Vous recevrez bientôt nos prochaines nouveautés.
                <?php endif; ?>
            </p>
        </header>

        <section class="doc-section" aria-label="Confirmation d'inscription">
            <article class="doc-page">
                <p>Vous recevrez désormais un e-mail mensuel avec les nouveautés du catalogue et nos coups de cœur de lecture.</p>
                <p>Vous pouvez vous désinscrire à tout moment depuis le lien présent en bas de chaque e-mail.</p>
                <p><a href="index.php">Retour à l'accueil</a></p>
            </article>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
