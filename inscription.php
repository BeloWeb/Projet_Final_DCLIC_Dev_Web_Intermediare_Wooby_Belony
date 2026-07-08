<?php
require __DIR__ . '/includes/db.php';

$pageTitle = 'Créer un compte — LIVRES & VOUS';
$currentPage = 'inscription.php';

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? ''); // Ajout du prénom requis par la table Lecteurs
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['password'] ?? '';

    if ($nom === '' || $prenom === '' || $email === '' || $motDePasse === '') {
        $erreur = 'Merci de remplir tous les champs.';
    } elseif (strlen($motDePasse) < 8) {
        $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        // Ciblage de la table 'Lecteurs' à la place de 'utilisateurs'
        $stmt = $pdo->prepare('SELECT id FROM Lecteurs WHERE email = :email');
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $erreur = 'Un compte existe déjà avec cette adresse e-mail.';
        } else {
            $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
            
            // Insertion dans la table officielle 'Lecteurs' avec les bons champs
            $stmt = $pdo->prepare('INSERT INTO Lecteurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :hash)');
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'hash' => $hash
            ]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['utilisateur_id'] = (int) $pdo->lastInsertId();
            $_SESSION['utilisateur_nom'] = $prenom . ' ' . $nom;

            header('Location: liste.php');
            exit;
        }
    }
}

require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow">Compte</p>
            <h1>Créer votre compte</h1>
            <p class="lead">Rejoignez LIVRES &amp; VOUS pour garder votre liste de lecture d'une visite à l'autre.</p>
        </header>

        <section class="auth-section" aria-label="Formulaire d'inscription">
            <form class="form-card auth-card" action="inscription.php" method="POST">
                <?php if ($erreur): ?>
                <p class="form-error"><?= htmlspecialchars($erreur) ?></p>
                <?php endif; ?>

                <div class="form-field">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" />
                </div>
                
                <div class="form-field">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" />
                </div>

                <div class="form-field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                </div>
                <div class="form-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" />
                </div>
                <button type="submit">Créer mon compte</button>
                <p class="auth-switch">Déjà inscrit ? <a class="inline-link" href="connexion.php">Connectez-vous</a>.</p>
            </form>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>