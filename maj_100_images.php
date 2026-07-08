<?php
// 1. Connexion à votre base de données
require __DIR__ . '/includes/db.php';

// 2. Sélection de 25 identifiants de magnifiques photos de livres sur Unsplash
// Le script va cycler sur ces images et utiliser l'ID du livre pour rendre chaque couverture unique
$unsplashTemplates = [
    '1544947950-fa07a98d237f', '1543002588-bfa74002ed7e', '1512820790803-83ca734da794',
    '1451187580459-43490279c0fa', '1532012197267-da84d127e765', '1497633762265-9d179a990aa6',
    '1506880018603-83d5b814b5a6', '1614849963640-9cc74b2a826f', '1516979187457-637abb4f9353',
    '1513001900722-370f803f498d', '1531988042231-d39a9cc12a9a', '1476275466078-4007374efbbe',
    '1589829545856-d10d557cf95f', '1541963463532-d68292c34b19', '1524995997946-a1c2e315a42f',
    '1535905557558-afc4877a26fc', '1610116306796-6ebd30d79123', '1546410531-bb4caa6b424d',
    '1495640388908-05fa85288e61', '1509266272358-7701da638078', '1491841573355-22d6d3d573d4',
    '1521587760476-6c12b4b040da', '1519681393784-d120267933ba', '1604866830893-c13cafa515d5',
    '1502134249126-9f3755a50d78'
];

try {
    // 3. Récupérer l'identifiant de tous les livres présents dans votre table
    $stmt = $pdo->query('SELECT id FROM Livres');
    $livres = $stmt->fetchAll();

    if (empty($livres)) {
        die("Votre table 'Livres' est vide. Injectez d'abord vos 105 ouvrages !");
    }

    // 4. Démarrer une transaction pour une exécution ultra-rapide et sécurisée
    $pdo->beginTransaction();
    
    $stmtUpdate = $pdo->prepare('UPDATE Livres SET image = :image WHERE id = :id');
    
    $compteur = 0;
    foreach ($livres as $index => $livre) {
        // Choix d'un template de base de manière cyclique (0 à 24)
        $templateId = $unsplashTemplates[$index % count($unsplashTemplates)];
        
        // Construction d'une URL d'image optimisée pour le web (largeur 400px)
        // L'ajout du paramètre '&sig=' suivi de l'ID garantit que les images ne se ressemblent pas toutes
        $urlImage = "https://images.unsplash.com/photo-" . $templateId . "?w=400&auto=format&fit=crop&q=80&sig=" . $livre['id'];

        // Exécution de la mise à jour pour ce livre précis
        $stmtUpdate->execute([
            'image' => $urlImage,
            'id'    => $livre['id']
        ]);
        
        $compteur++;
    }
    
    // Validation définitive des changements en BDD
    $pdo->commit();
    
    echo "<div style='font-family:sans-serif; padding:2rem; background:#e6f4ea; color:#137333; border-radius:8px; max-width:600px; margin:2rem auto;'>";
    echo "<h2>🎉 Opération réussie !</h2>";
    echo "<p><strong>$compteur livres</strong> ont reçu un lien d'image de couverture unique avec succès.</p>";
    echo "<p><a href='catalogue.php' style='color:#137333; font-weight:bold;'>👉 Aller voir le catalogue mis à jour</a></p>";
    echo "</div>";

} catch (Exception $e) {
    // En cas d'erreur, on annule tout pour ne pas corrompre vos données
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Erreur lors de la génération des images : " . $e->getMessage());
}