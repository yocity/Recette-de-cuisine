<?php
// Yocoli Konan Jean Epiphane 1A TP10

include('donnees.php');

$action = $_GET['action'] ?? null;
$zonePrincipale = '';

switch ($action) {
    case "tester":
        $connection = connecter();
        $zonePrincipale = $connection ? "<h1>Connexion réussie!</h1>" : "<h1>Échec de connexion</h1>";
        break;

    case "afficher":
        $connection = connecter();
        $corps = "<h1>Liste des personnes</h1><div class='grid-container'>
            <div class='item'>idP</div><div class='item'>Nom</div><div class='item'>Date de naissance</div><div class='item'>Action</div></div>";

        $corps .= "</br>";
        $corps .= "<div class='grid-container'>";
        try {
            $query = $connection->prepare("SELECT * FROM Personne");
            $query->execute();
            $query->setFetchMode(PDO::FETCH_OBJ);
            while( $p = $query->fetch() ){
                $corps .= "
                    <div class='item'>{$p->idP}</div>
                    <div class='item'>{$p->nom}</div>
                    <div class='item'>{$p->dateN}</div>
                    <div class='item'>
                        <a href='index.php?action=modifier&idP={$p->idP}'><i class='fa-solid fa-pen'></i></a>
                        <a href='index.php?action=supprimer&idP={$p->idP}'><i class='fa-solid fa-trash'></i></a>
                    </div>";
            }
        } catch (PDOException $e) {
            $corps .= "Erreur: " . $e->getMessage();
        }
        $corps .="</div>";
        $zonePrincipale = $corps;
        break;

    case "saisir":
        $cible='saisir';
        $idP = '';
        if (!isset($_POST["nom"]) && !isset($_POST["dateN"])) {
            // Affichage du formulaire
            include("formulairePersonne.php");
            $zonePrincipale = $corps;
        }
        else{
            $nom = $_POST['nom'] ?? '';
            $dateN = $_POST['dateN'] ?? '';
            $erreur = [];

            // Validation
            if (empty($nom)) $erreur['nom'] = "Nom manquant";
            if (empty($dateN) || !controlerDate($dateN)) $erreur['dateN'] = "Date invalide";

            if (empty($erreur)) {
                $personne = new Personne($nom, $dateN);
                if ($personne->enregistrer()) {
                    $zonePrincipale = "<h1>Succès: " . $personne->__toString() . "</h1>";
                } else {
                    $zonePrincipale = "<h1>Erreur lors de l'insertion</h1>";
                }
            } else {
                include("formulairePersonne.php");
            }
        }
        break;

    case "modifier":
        $cible='update';
        $idP = $_GET['idP'];
        $connection = connecter();
        $query = $connection->prepare("SELECT * FROM Personne WHERE idP = ?");
        $query->execute([$idP]);
        $query->setFetchMode(PDO::FETCH_OBJ);
        $personne = $query->fetch();
        $nom = $personne->nom;
        $dateN = $personne->dateN;
        
        include("formulairePersonne.php");
        $zonePrincipale = $corps;
       
        break;

    case "update":
        $cible='update';
        $idP = $_GET['idP'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $dateN = $_POST['dateN'] ?? '';

        if ($idP && controlerDate($dateN)) {
            $personne = new Personne($nom, $dateN, $idP);
            if ($personne->modifier($nom, $dateN)) {
                $zonePrincipale = "<h1>Mise à jour réussie: " . $personne->__toString() . "</h1>";
            } else {
                $zonePrincipale = "<h1>Échec de la mise à jour</h1>";
            }
        }
        break;

    case "supprimer":
        $idP = $_GET['idP'] ?? null;
        $zonePrincipale = "<form method='post' action='index.php?action=delete'>
            <input type='hidden' name='idP' value='{$idP}'>
            <p>Confirmer la suppression?</p>
            <input type='submit' value='Confirmer'>
            <a href='index.php'>Annuler</a>
        </form>";
        break;

    case "delete":
        $idP = $_POST['idP'] ?? null;
        $personne = new Personne('', '', $idP);
        if ($personne->supprimer()) {
            $zonePrincipale = "<h1>Suppression réussie</h1>";
        } else {
            $zonePrincipale = "<h1>Échec de la suppression</h1>";
        }
        break;

    default:
        $zonePrincipale = "<h1>Bienvenue</h1>";
        break;
}

include("squelette.php");
?>