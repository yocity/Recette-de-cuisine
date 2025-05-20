<?php

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php?action=afficher');
    exit;
}

$recette = Recette::obtenirParId((int)$id);

if (!$recette) {
    header('Location: index.php?action=afficher');
    exit;
}

$titre = $recette->titre ?? '';
$description = $recette->description ?? '';
$ingredients = $recette->ingredients ?? [];
$messageErreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = array_filter($_POST['ingredients'] ?? [], function($ing) {
            return !empty(trim($ing));
        });

        if (empty($titre) || empty($description)) {
            throw new Exception('Tous les champs obligatoires doivent être remplis');
        }

        $imagePath = $recette->image ?? '';
        if (!empty($_FILES['image']['tmp_name'])) {
            // Validation du fichier
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExtension, $validExtensions)) {
                throw new Exception('Format d\'image non valide. Formats acceptés: ' . implode(', ', $validExtensions));
            }

            if ($_FILES['image']['size'] > 2000000) { // 2MB max
                throw new Exception('L\'image ne doit pas dépasser 2MB');
            }

            $uploadDir = 'img/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFilename = uniqid() . '.' . $fileExtension;
            $imagePath = $uploadDir . $newFilename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                throw new Exception('Erreur lors de l\'upload de l\'image');
            }

            if (!empty($recette->image) && file_exists($recette->image)) {
                unlink($recette->image);
            }
        }

        $recette->titre = $titre;
        $recette->description = $description;
        $recette->ingredients = $ingredients;
        $recette->image = $imagePath;

        $success = $recette->modifier();

        if ($success) {
            header('Location: index.php?action=afficher&id=' . $recette->id);
            exit;
        } else {
            $messageErreur = "Échec de la mise à jour.";
        }
    } catch (Exception $e) {
        error_log('Erreur modification recette: ' . $e->getMessage());
        $messageErreur = $e->getMessage();
    }
}

$escapedTitre = htmlspecialchars($titre, ENT_QUOTES, 'UTF-8');
$escapedDescription = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
$escapedId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

$zonePrincipale = "
<div class='form-container'>
    <h1>Modifier la recette</h1>";

if (!empty($messageErreur)) {
    $zonePrincipale .= "<p class='error'>{$messageErreur}</p>";
}

$zonePrincipale .= "
    <form method='post' action='index.php?action=modifie&id={$escapedId}' enctype='multipart/form-data'>
        <input type='hidden' name='id' value='{$escapedId}'>
        
        <div class='form-group'>
            <label for='titre'>Titre *</label>
            <input type='text' id='titre' name='titre' value='{$escapedTitre}' required>
        </div>
        
        <div class='form-group'>
            <label for='description'>Description *</label>
            <textarea id='description' name='description' required>{$escapedDescription}</textarea>
        </div>
        
        <div class='form-group'>
            <label for='image'>Image</label>
            <input type='file' id='image' name='image' accept='image/jpeg,image/png,image/gif'>
            " . (!empty($recette->image) ? "<p>Image actuelle: <img src='{$recette->image}' alt='Image recette' style='max-width: 200px;'></p>" : "") . "
        </div>
        
        <div class='form-group'>
            <label>Ingrédients</label>
            <div id='ingredients-container'>";

foreach ($ingredients as $ingredient) {
    $escapedIngredient = htmlspecialchars($ingredient, ENT_QUOTES, 'UTF-8');
    $zonePrincipale .= "
                <div class='ingredient-item'>
                    <input type='text' name='ingredients[]' value='{$escapedIngredient}' placeholder='Ingrédient'>
                    <button type='button' class='btn btn-delete remove-ingredient'>
                        <i class='fa-solid fa-trash'></i>
                    </button>
                </div>";
}

$zonePrincipale .= "
            </div>
            <button type='button' id='add-ingredient' class='btn'>
                <i class='fa-solid fa-plus'></i> Ajouter un ingrédient
            </button>
        </div>
        
        <button type='submit' class='btn'>Enregistrer</button>
        <a href='index.php?action=afficher" . ($id ? "&id={$escapedId}" : "") . "' class='btn'>Annuler</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour créer un champ ingrédient
    function createIngredientField(value = '') {
        const container = document.getElementById('ingredients-container');
        const div = document.createElement('div');
        div.className = 'ingredient-item';
        div.innerHTML = `
            <input type='text' name='ingredients[]' value='\${value.replace(/'/g, '&#39;')}' placeholder='Ingrédient'>
            <button type='button' class='btn btn-delete remove-ingredient'>
                <i class='fa-solid fa-trash'></i>
            </button>
        `;
        container.appendChild(div);
    }

    // Bouton d'ajout
    document.getElementById('add-ingredient').addEventListener('click', function() {
        createIngredientField();
    });

    // Délégation d'événement pour la suppression
    document.getElementById('ingredients-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-ingredient')) {
            e.target.closest('.ingredient-item').remove();
        }
    });
});
</script>";