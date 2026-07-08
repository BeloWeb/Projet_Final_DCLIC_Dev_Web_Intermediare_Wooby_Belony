<?php
$pageTitle = 'LIVRES & VOUS — Bibliothèque en ligne';
$currentPage = 'index.php';
require __DIR__ . '/includes/head.php';
?>
        <header>
            <p class="eyebrow">Catalogue — recherche</p>
            <h1>Bienvenue à LIVRES &amp; VOUS, votre bibliothèque en ligne</h1>
            <p class="lead">Recherchez, découvrez et ajoutez des livres à votre liste de lecture, où que vous soyez.</p>
        </header>

        <section class="search-section" aria-label="Recherche de livres">
            <form action="results.php" method="GET">
                <label for="search" class="sr-only">Rechercher par titre ou auteur</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Rechercher par titre ou auteur"
                    required
                    minlength="2"
                    autocomplete="off"
                />
                <button type="submit">Rechercher</button>
            </form>
            <p class="search-hint">Exemples : « Victor Hugo », « science-fiction », « Les Misérables »</p>
        </section>

        <section class="categories" aria-labelledby="categories-title">
            <div class="section-heading">
                <p class="eyebrow">Rayons</p>
                <h2 id="categories-title">Explorez par catégorie</h2>
            </div>
            <div class="category-grid">
                <a class="category-card" href="catalogue.php?categorie=romans">
                    <span class="category-index">01</span>
                    <h3>Romans</h3>
                    <p>Fictions contemporaines et classiques de la littérature.</p>
                </a>
                <a class="category-card" href="catalogue.php?categorie=sciences">
                    <span class="category-index">02</span>
                    <h3>Sciences</h3>
                    <p>Vulgarisation, essais et ouvrages de référence.</p>
                </a>
                <a class="category-card" href="catalogue.php?categorie=jeunesse">
                    <span class="category-index">03</span>
                    <h3>Jeunesse</h3>
                    <p>Albums, contes et premières lectures.</p>
                </a>
                <a class="category-card" href="catalogue.php?categorie=poesie">
                    <span class="category-index">04</span>
                    <h3>Poésie</h3>
                    <p>Recueils classiques et voix contemporaines.</p>
                </a>
            </div>
        </section>

        <section class="highlights" aria-labelledby="highlights-title">
            <div class="section-heading">
                <p class="eyebrow">Pourquoi nous choisir</p>
                <h2 id="highlights-title">Une bibliothèque pensée pour les lecteurs</h2>
            </div>
            <div class="highlight-grid">
                <div class="highlight-card">
                    <h3>Catalogue étendu</h3>
                    <p>Des dizaines de milliers de titres, mis à jour chaque semaine.</p>
                </div>
                <div class="highlight-card">
                    <h3>Liste de lecture</h3>
                    <p>Enregistrez vos envies et suivez votre progression de lecture.</p>
                </div>
                <div class="highlight-card">
                    <h3>Recommandations</h3>
                    <p>Des suggestions adaptées à vos genres et auteurs préférés.</p>
                </div>
            </div>
        </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
