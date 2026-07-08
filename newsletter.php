<?php
// 1. Connexion à la base de données
require __DIR__ . '/includes/db.php';

$email = trim($_POST['email'] ?? '');
$error = null;
$success = false;

// 2. Traitement de l'inscription si un e-mail est reçu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email !== '') {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } else {
        try {
            // Vérifier si l'e-mail existe déjà dans la table newsletter
            $stmt = $pdo->prepare('SELECT id FROM newsletter WHERE email = :email');
            $stmt->execute(['email' => $email]);
            
            if ($stmt->fetch()) {
                $error = "Cette adresse e-mail est déjà inscrite à notre newsletter.";
            } else {
                // Insertion sécurisée dans la base de données
                $stmt = $pdo->prepare('INSERT INTO newsletter (email) VALUES (:email)');
                $stmt->execute(['email' => $email]);
                $success = true;
            }
        } catch (PDOException $e) {
            $error = "Une erreur technique est survenue lors de l'inscription.";
        }
    }
}

// 3. Configuration des balises de structure du site
$pageTitle = 'Inscription confirmée — LIVRES & VOUS';
$currentPage = '';
require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow">Newsletter</p>
            
            <?php if ($success): ?>
                <h1>Merci pour votre inscription</h1>
                <p class="lead">Un e-mail de confirmation a été envoyé à <?= htmlspecialchars($email) ?>.</p>
            <?php elseif ($error): ?>
                <h1>Une erreur est survenue</h1>
                <p class="lead" style="color: var(--form-error-color, #ff3333);"><?= htmlspecialchars($error) ?></p>
            <?php else: ?>
                <h1>Restez informé</h1>
                <p class="lead">Vous recevrez bientôt nos prochaines nouveautés.</p>
            <?php endif; ?>
        </header>

        <section class="doc-section" aria-label="Confirmation d'inscription">
            <article class="doc-page">
                <?php if ($success || (!$success && !$error)): ?>
                    <p>Vous recevrez désormais un e-mail mensuel avec les nouveautés du catalogue et nos coups de cœur de lecture.</p>
                    <p>Vous pouvez vous désinscrire à tout moment depuis le lien présent en bas de chaque e-mail.</p>
                <?php endif; ?>
                <p style="margin-top: 2rem;"><a href="index.php">Retour à l'accueil</a></p>
            </article>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>