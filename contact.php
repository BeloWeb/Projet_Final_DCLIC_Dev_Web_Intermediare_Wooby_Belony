<?php
// Connexion à la base de données
require __DIR__ . '/includes/db.php';

$pageTitle = 'Contact — LIVRES & VOUS';
$currentPage = 'contact.php';

$error = null;
$sent = false;

// Initialisation des variables pour conserver les valeurs dans les champs en cas d'erreur
$nom = '';
$email = '';
$sujet = 'question';
$message = '';

// Traitement de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = $_POST['sujet'] ?? 'question';
    $message = trim($_POST['message'] ?? '');

    // Validation stricte des données côté serveur
    if ($nom === '' || $email === '' || $message === '') {
        $error = 'Merci de remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse e-mail saisie n\'est pas valide.';
    } else {
        try {
            // Sauvegarde sécurisée du message dans la base de données
            $stmt = $pdo->prepare('INSERT INTO contacts (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)');
            $stmt->execute([
                'nom' => $nom,
                'email' => $email,
                'sujet' => $sujet,
                'message' => $message
            ]);

            // Marquer comme envoyé pour afficher le bandeau vert de succès
            $sent = true;
            
            // Vider le formulaire pour éviter un double envoi accidentel
            $nom = '';
            $email = '';
            $sujet = 'question';
            $message = '';
            
        } catch (PDOException $e) {
            $error = 'Une erreur technique est survenue lors de l\'enregistrement de votre message.';
        }
    }
}

require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow">Contact</p>
            <h1>Une question, une suggestion ?</h1>
            <p class="lead">Notre équipe vous répond sous 48 heures ouvrées.</p>
        </header>

        <section class="contact-section" aria-label="Formulaire de contact">
            <div class="contact-grid">
                <form class="form-card" action="contact.php" method="POST">
                    <?php if ($sent): ?>
                    <p class="form-success">Votre message a bien été envoyé et enregistré en base de données. Merci !</p>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <p class="form-error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <div class="form-field">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($nom) ?>" />
                    </div>
                    <div class="form-field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>" />
                    </div>
                    <div class="form-field">
                        <label for="sujet">Sujet</label>
                        <select id="sujet" name="sujet">
                            <option value="question" <?= $sujet === 'question' ? 'selected' : '' ?>>Question générale</option>
                            <option value="compte" <?= $sujet === 'compte' ? 'selected' : '' ?>>Problème de compte</option>
                            <option value="catalogue" <?= $sujet === 'catalogue' ? 'selected' : '' ?>>Suggestion de livre</option>
                            <option value="autre" <?= $sujet === 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
                    </div>
                    <button type="submit">Envoyer le message</button>
                </form>

                <aside class="contact-info">
                    <h3>Autres moyens de nous joindre</h3>
                    <ul>
                        <li>
                            <span class="contact-label">E-mail</span>
                            <span>contact@livresetvous.fr</span>
                        </li>
                        <li>
                            <span class="contact-label">Téléphone</span>
                            <span>+33 1 23 45 67 89</span>
                        </li>
                        <li>
                            <span class="contact-label">Horaires</span>
                            <span>Du lundi au vendredi, 9h – 18h</span>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>