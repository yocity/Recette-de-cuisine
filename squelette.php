<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personne</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <h1>Personne</h1> 
    <hr>
    <div class="Ycontainer">
        <div class="Ymain">
            <?php echo $zonePrincipale; ?>
        </div>
        <div class="Ysidebar">
            <p>
                <a href="index.php?action=tester">Vérifier connection </a>
                <a href="index.php?action=afficher">Affichage des personnes</a>
                <a href="index.php?action=saisir">Saisie d'une personne</a>
            </p>
        </div>
    </div>
    <hr>
</body>
</html>
