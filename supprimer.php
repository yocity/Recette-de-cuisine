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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $success = $recette->supprimer();

        if ($success) {
            header('Location: index.php?action=afficher');
            exit;
        } else {
            $messageErreur = "Échec de la suppression.";
        }
    } catch (Exception $e) {
        error_log('Erreur suppression recette: ' . $e->getMessage());
        $messageErreur = "Une erreur s'est produite lors de la suppression.";
    }
}

$titre = htmlspecialchars($recette->titre);
$id = htmlspecialchars($recette->id);

$zonePrincipale = "
<div class='confirmation'>
    <h1>Confirmer la suppression</h1>
    <p>Êtes-vous sûr de vouloir supprimer la recette : <strong>{$titre}</strong> ?</p>";

if (!empty($messageErreur)) {
    $zonePrincipale .= "<p class='error'>{$messageErreur}</p>";
}

$zonePrincipale .= "
    <form method='post' action='index.php?action=supprimer&id={$id}'>
        <input type='hidden' name='id' value='{$id}'>
        <button type='submit' class='btn btn-delete'>Confirmer la suppression</button>
        <a href='index.php?action=afficher' class='btn'>Annuler</a>
    </form>
</div>";
?>
