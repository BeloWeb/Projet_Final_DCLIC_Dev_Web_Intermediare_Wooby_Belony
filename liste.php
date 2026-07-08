<?php
require __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_lecteur = (int)$_SESSION['utilisateur_id'];

// --- GESTION DE L'AJOUT D'UN LIVRE A LA LISTE (Depuis catalogue.php) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $id_livre_a_ajouter = (int)($_POST['id_livre'] ?? 0);
    
    // 1. On vérifie si le livre n'est pas déjà présent dans la liste de ce lecteur
    $check = $pdo->prepare('SELECT COUNT(*) FROM Liste_lecture WHERE id_lecteur = :id_lecteur AND id_livre = :id_livre');
    $check->execute(['id_lecteur' => $id_lecteur, 'id_livre' => $id_livre_a_ajouter]);
    
    if ($check->fetchColumn() == 0) {
        // 2. S'il n'y est pas, on l'ajoute. 
        // On laisse date_emprunt et date_retour à NULL pour que le statut initial soit "À lire"
        $stmt = $pdo->prepare('INSERT INTO Liste_lecture (id_lecteur, id_livre, date_emprunt, date_retour) VALUES (:id_lecteur, :id_livre, NULL, NULL)');
        $stmt->execute([
            'id_lecteur' => $id_lecteur,
            'id_livre'   => $id_livre_a_ajouter
        ]);
    }
    
    // Redirection pour rafraîchir la page et éviter les doublons au rafraîchissement (F5)
    header('Location: liste.php');
    exit;
}

// --- GESTION DE LA SUPPRESSION D'UN LIVRE DE LA LISTE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'retirer') {
    $id_livre_a_retirer = (int)($_POST['id_livre'] ?? 0);
    
    // Requête de suppression de l'entrée spécifique dans Liste_lecture
    $stmt = $pdo->prepare('DELETE FROM Liste_lecture WHERE id_lecteur = :id_lecteur AND id_livre = :id_livre');
    $stmt->execute([
        'id_lecteur' => $id_lecteur,
        'id_livre'   => $id_livre_a_retirer
    ]);
    
    // Redirection pour rafraîchir la page
    header('Location: liste.php');
    exit;
}

// --- RÉCUPÉRATION DES LIVRES DE L'UTILISATEUR CONNECTÉ ---
$stmt = $pdo->prepare('
    SELECT l.id, l.titre, l.auteur, ll.date_emprunt, ll.date_retour 
    FROM Liste_lecture ll
    JOIN Livres l ON ll.id_livre = l.id
    WHERE ll.id_lecteur = :id_lecteur
');
$stmt->execute(['id_lecteur' => $id_lecteur]);
$maListe = $stmt->fetchAll();

$pageTitle = 'Ma liste de lecture — LIVRES & VOUS';
$currentPage = 'liste.php';
require __DIR__ . '/includes/head.php';

$statutLabels = [
    'a_lire'   => 'À lire',
    'en_cours' => 'En cours',
    'termine'  => 'Terminé',
];
?>
        <header>
            <p class="eyebrow">Ma liste</p>
            <h1>Votre liste de lecture</h1>
            <p class="lead">Retrouvez ici les livres que vous avez ajoutés depuis le catalogue.</p>
        </header>

        <section class="list-section" aria-label="Livres de votre liste">
            <?php if (empty($maListe)): ?>
            <div class="empty-state">
                <p class="eyebrow">Liste vide</p>
                <h2>Vous n'avez pas encore ajouté de livre</h2>
                <p>Parcourez le <a href="catalogue.php">catalogue</a> pour commencer votre liste.</p>
            </div>
            <?php else: ?>
            <ul class="list-rows">
                <?php foreach ($maListe as $livre): 
                    // Détermination dynamique du badge selon les dates de la base de données
                    $statut = 'a_lire';
                    if (!empty($livre['date_retour'])) {
                        $statut = 'termine';
                    } elseif (!empty($livre['date_emprunt'])) {
                        $statut = 'en_cours';
                    }
                ?>
                <li class="list-row">
                    <div class="list-row-main">
                        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p><?= htmlspecialchars($livre['auteur']) ?></p>
                    </div>
                    
                    <span class="status-badge status-<?= $statut ?>">
                        <?= htmlspecialchars($statutLabels[$statut]) ?>
                    </span>
                    
                    <form action="liste.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="id_livre" value="<?= (int)$livre['id'] ?>" />
                        <input type="hidden" name="action" value="retirer" />
                        <button type="submit" class="list-row-remove" aria-label="Retirer <?= htmlspecialchars($livre['titre']) ?> de la liste">Retirer</button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>