<?php
require __DIR__ . '/includes/db.php';

$pageTitle = 'Connexion — LIVRES & VOUS';
$currentPage = 'connexion.php';

$error = null;
$email_saisi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['password'] ?? '';
    $email_saisi = $email; // Permet de laisser l'adresse e-mail dans le champ en cas d'erreur

    if ($email === '' || $motDePasse === '') {
        $error = 'Merci de remplir tous les champs.';
    } else {
        // On récupère le lecteur correspondant à l'adresse e-mail dans la table officielle
        $stmt = $pdo->prepare('SELECT * FROM Lecteurs WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $lecteur = $stmt->fetch();

        // On vérifie si le lecteur existe et si le mot de passe correspond au hash stocké
        if ($lecteur && password_verify($motDePasse, $lecteur['mot_de_passe'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Initialisation des variables de session (identiques à l'inscription)
            $_SESSION['utilisateur_id'] = (int) $lecteur['id'];
            $_SESSION['utilisateur_nom'] = $lecteur['prenom'] . ' ' . $lecteur['nom'];

            // Redirection vers l'espace de liste de lecture
            header('Location: liste.php');
            exit;
        } else {
            $error = "Identifiants incorrects. Réessayez.";
        }
    }
}

require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow">Compte</p>
            <h1>Content de vous revoir</h1>
            <p class="lead">Connectez-vous pour retrouver votre liste de lecture.</p>
        </header>

        <section class="auth-section" aria-label="Formulaire de connexion">
            <form class="form-card auth-card" action="connexion.php" method="POST">
                <?php if ($error): ?>
                <p class="form-error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <div class="form-field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" required autocomplete="email" value="<?= htmlspecialchars($email_saisi) ?>" />
                </div>
                <div class="form-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" />
                </div>
                <div class="form-row-between">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" />
                        Rester connecté
                    </label>
                    <a class="inline-link" href="aide.php">Mot de passe oublié ?</a>
                </div>
                <button type="submit">Se connecter</button>
                <p class="auth-switch">Pas encore de compte ? <a class="inline-link" href="inscription.php">Inscrivez-vous ici</a>.</p>
            </form>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>