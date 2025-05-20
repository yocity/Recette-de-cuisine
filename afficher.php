<?php
$connection = connecter();
$corps = "<h1>Liste des recettes</h1>
    <div class='actions-bar'>
        <form method='GET' action='index.php?action=search' class='search-form'>
            <input type='hidden' name='action' value='search'>
            <input type='text' name='search' placeholder='Rechercher une recette...'>
            <button type='submit' class='btn'>
                <i class='fa-solid fa-search'></i> Rechercher
            </button>
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
    $recettesParPage = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max($page, 1); 
    
    $listeRecettes = Recette::obtenirToutes();
    $totalRecettes = count($listeRecettes);
    $totalPages = ceil($totalRecettes / $recettesParPage);
    $page = min($page, $totalPages);
    
    $offset = ($page - 1) * $recettesParPage;
    $recettesPage = array_slice($listeRecettes, $offset, $recettesParPage);

    foreach ($recettesPage as $recette) {
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

    $corps .= "<div class='pagination'>";
    if ($page > 1) {
        $corps .= "<a href='index.php?action=afficher&page=".($page - 1)."' class='btn'><i class='fa-solid fa-chevron-left'></i> Précédent</a> ";
    }
    
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            $corps .= "<span class='btn active'>{$i}</span> ";
        } else {
            $corps .= "<a href='index.php?action=afficher&page={$i}' class='btn'>{$i}</a> ";
        }
    }
    
    if ($page < $totalPages) {
        $corps .= "<a href='index.php?action=afficher&page=".($page + 1)."' class='btn'>Suivant <i class='fa-solid fa-chevron-right'></i></a>";
    }
    $corps .= "</div>";

} catch (PDOException $e) {
    $corps .= "<div class='error'>Erreur: " . htmlspecialchars($e->getMessage()) . "</div>";
}

$zonePrincipale = $corps;
?>