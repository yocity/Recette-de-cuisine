<?php
$connection = connecter();

$search = $_GET['search'] ?? '';
$isSearchMode = !empty($search);

$corps = "<h1>Résultats de la recherche</h1>
    <div class='actions-bar'>
        <form method='GET' action='index.php?action=afficher' class='search-form'>
            <input type='hidden' name='action' value='search'>
            <input type='text' name='search' placeholder='Rechercher une recette...' value='" . htmlspecialchars($search) . "'>
            <button type='submit' class='btn'>
                <i class='fa-solid fa-search'></i> Rechercher
            </button>
            <a href='index.php?action=afficher' class='btn'>
                <i class='fa-solid fa-arrow-left'></i> Retour à la liste complète
            </a>
        </form>
        <a href='index.php?action=saisir' class='btn'>
            <i class='fa-solid fa-plus'></i> Ajouter une recette
        </a>
    </div>

    <div class='recettes-grid'>
        <div class='grid-header'>ID</div>
        <div class='grid-header'>Image</div>
        <div class='grid-header'>Titre</div>
        <div class='grid-header'>Description</div>
        <div class='grid-header'>Ingrédients</div>
        <div class='grid-header'>Actions</div>
    </div>";

try {
    $recettes = Recette::rechercher($search);

    if (empty($recettes)) {
        $corps .= "<div class='no-results'>Aucun résultat trouvé pour votre recherche.</div>";
    } else {
        foreach ($recettes as $recette) {
            $id = htmlspecialchars($recette->id);
            $titre = htmlspecialchars($recette->titre);
            $description = nl2br(htmlspecialchars($recette->description));
            $image = htmlspecialchars($recette->image ?? '');
            $ingredients = $recette->ingredients;
    
            $corps .= "<div class='recettes-grid'>
                <div class='grid-item'>{$id}</div>
                <div class='grid-item'>";
    
            if (!empty($image)) {
                $corps .= "<img src='{$image}' alt='{$titre}' class='recette-image'>";
            } else {
                $corps .= "<div class='no-image'>Pas d'image</div>";
            }
    
            $corps .= "</div>
                <div class='grid-item'><strong>{$titre}</strong></div>
                <div class='grid-item'>{$description}</div>
                <div class='grid-item'>";
    
            if (!empty($ingredients)) {
                $corps .= "<ul class='ingredients-list'>";
                foreach ($ingredients as $ingredient) {
                    $corps .= "<li>" . htmlspecialchars($ingredient) . "</li>";
                }
                $corps .= "</ul>";
            } else {
                $corps .= "Aucun ingrédient";
            }
    
            $corps .= "</div>
                <div class='grid-item actions'>
                    <a href='index.php?action=modifie&id={$id}' class='btn' title='Modifier'>
                        <i class='fa-solid fa-pen'></i>
                    </a>
                    <a href='index.php?action=supprimer&id={$id}' class='btn btn-delete' title='Supprimer'>
                        <i class='fa-solid fa-trash'></i>
                    </a>
                </div>
            </div>";
        }
    }
} catch (PDOException $e) {
    $corps .= "<div class='error'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

$zonePrincipale = $corps;
?>
