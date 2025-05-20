<?php

include('donnees.php');

$action = $_GET['action'] ?? 'accueil';

$fichierAction = "{$action}.php";
if (file_exists($fichierAction)) {
    include($fichierAction);
} else {
    include('accueil.php');
}

include('squelette.php');
?>