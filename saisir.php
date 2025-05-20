<?php
$zonePrincipale = '';
$recette = new Recette();
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $recette->titre = trim($_POST['titre'] ?? '');
    $recette->description = trim($_POST['description'] ?? '');
    $recette->ingredients = array_filter(array_map('trim', $_POST['ingredients'] ?? []));
    $image = $_FILES['image'] ?? null;

    if (empty($recette->titre)) {
        $erreurs[] = "Le titre est obligatoire";
    }

    if (empty($recette->description)) {
        $erreurs[] = "La description est obligatoire";
    }

    if (empty($recette->ingredients)) {
        $erreurs[] = "Au moins un ingrédient est requis";
    }

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        
        $mimeTypesAutorises = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
        $mimeType = mime_content_type($image['tmp_name']);

        if (!array_key_exists($mimeType, $mimeTypesAutorises)) {
            $erreurs[] = "Type de fichier non supporté (seuls JPG, PNG et GIF sont acceptés)";
        } elseif ($image['size'] > 2 * 1024 * 1024) {
            $erreurs[] = "L'image est trop volumineuse (max 2MB)";
        } else {
            $uploadDir = 'img/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = $mimeTypesAutorises[$mimeType];
            $recette->image = $uploadDir . uniqid() . '.' . $extension;
            $cheminComplet = $recette->image;

            if (!move_uploaded_file($image['tmp_name'], $cheminComplet)) {
                $erreurs[] = "Erreur lors de l'enregistrement de l'image";
                $recette->image = null;
            }
        }
    }

    if (empty($erreurs)) {
        try {
            if ($recette->enregistrer()) {
                header("Location: index.php?action=afficher");
                exit();
            } else {
                $erreurs[] = "Erreur lors de l'enregistrement en base de données";
            }
        } catch (PDOException $e) {
            $erreurs[] = "Erreur de base de données: " . $e->getMessage();
            error_log("Erreur enregistrement recette: " . $e->getMessage());
        }
    }
}

// Affichage des erreurs
if (!empty($erreurs)) {
    $zonePrincipale .= '<div class="error-container">';
    $zonePrincipale .= '<h2>Erreurs lors de l\'enregistrement</h2>';
    $zonePrincipale .= '<ul>';
    foreach ($erreurs as $erreur) {
        $zonePrincipale .= '<li>' . htmlspecialchars($erreur) . '</li>';
    }
    $zonePrincipale .= '</ul>';
    $zonePrincipale .= '</div>';
}

$zonePrincipale .= '
<div class="container">
    <h1>Ajouter une nouvelle recette</h1>
    
    <form method="post" action="index.php?action=saisir" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre de la recette *</label>
            <input type="text" id="titre" name="titre" required 
                   value="' . htmlspecialchars($recette->titre) . '" 
                   placeholder="Ex: Soupe à l\'oignon">
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required 
                      placeholder="Décrivez votre recette...">' . htmlspecialchars($recette->description) . '</textarea>
        </div>
        
        <div class="form-group">
            <label for="image">Image de la recette (max 2MB)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">
            <small>Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
        </div>
        
        <div class="form-group">
            <label>Ingrédients</label>
            <div id="ingredients-container">';
foreach ($recette->ingredients as $ingredient) {
    $zonePrincipale .= "
                <div class='ingredient-item' style='display: flex; gap: 0.5rem; margin-bottom: 0.5rem;'>
                    <input type='text' name='ingredients[]' value='" . htmlspecialchars($ingredient) . "' placeholder='Ex: 2 oignons' required>
                    <button type='button' class='btn btn-delete remove-ingredient'>
                        <i class='fa-solid fa-trash'></i>
                    </button>
                </div>";
}
$zonePrincipale .= '
            </div>
            <button type="button" id="add-ingredient" class="btn" style="margin-top: 0.5rem;">
                <i class="fas fa-plus"></i> Ajouter un ingrédient
            </button>
        </div>
        
        <div class="form-group" style="display: flex; gap: 1rem;">
            <button type="submit" class="btn"><i class="fas fa-save"></i> Enregistrer la recette</button>
            <a href="index.php?action=afficher" class="btn btn-delete"><i class="fas fa-times"></i> Annuler</a>
        </div>
    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Gestion de affichage du nom du fichier
    const fileInput = document.querySelector("input[type=file]");
    const fileName = document.querySelector(".file-upload-name");
    
    fileInput.addEventListener("change", function() {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
            
            // Vérification de la taille du fichier
            if (this.files[0].size > 2 * 1024 * 1024) {
                alert("Le fichier est trop volumineux (max 2MB)");
                this.value = "";
                fileName.textContent = "Aucun fichier sélectionné";
            }
        } else {
            fileName.textContent = "Aucun fichier sélectionné";
        }
    });
    
    // Gestion des ingrédients dynamiques
    const addButton = document.getElementById("add-ingredient");
    const container = document.getElementById("ingredients-container");
    
    function addIngredientField(value = "") {
        const container = document.getElementById("ingredients-container");
        const div = document.createElement("div");
        div.className = "ingredient-item";
        div.innerHTML = `
            <input type="text" name="ingredients[]" value="" placeholder="Ingrédient">
            <button type="button" class="btn btn-delete remove-ingredient">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }
    
    addButton.addEventListener("click", function() {
        addIngredientField();
    });
    
    // Délégation pour les boutons de suppression
    container.addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-ingredient") || 
            e.target.closest(".remove-ingredient")) {
            e.target.closest(".ingredient-item").remove();
        }
    });
    
    // Ajouter un premier ingrédient si vide
    if (container.children.length === 0) {
        addIngredientField();
    }
});
</script>';

include 'squelette.php';
?>