<?php
//Yocoli Konan Jean Epiphane 1A TP10

// Définition des types pour les variables

$nom = null;
$dateN = null;
$erreur = ["nom" => null, "dateN" => null];

class Personne { 
    public $idP;
    public $nom;
    public $dateN;

    public function __construct($nom = '', $dateN = '', $idP = null) {
        $this->nom = $nom;
        $this->dateN = $dateN;
        $this->idP = $idP;
    }

    public function __toString() {
        return "ID: {$this->idP} - Nom: {$this->nom} - Date de naissance: {$this->dateN}";
    }

    public function enregistrer(): bool {
        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO Personne (nom, dateN) VALUES (?, ?)");
        $success = $query->execute([$this->nom, $this->dateN]);
        if ($success) {
            $this->idP = $connection->lastInsertId();
        }
        return $success;
    }

    public function modifier(string $nom, string $dateN): bool {
        $this->nom = $nom;
        $this->dateN = $dateN;
        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("UPDATE Personne SET nom = ?, dateN = ? WHERE idP = ?");
        return $query->execute([$this->nom, $this->dateN, $this->idP]);
    }

    public function supprimer(): bool {
        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("DELETE FROM Personne WHERE idP = ?");
        return $query->execute([$this->idP]);
    }
}

function controlerDate(string $valeur): bool {
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $valeur, $regs)) {
        return checkdate((int)$regs[2], (int)$regs[3], (int)$regs[1]);
    }
    return false;
}

function connecter(): ?PDO {
    require_once('config.php');
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
        $connection = new PDO(DB_HOST, DB_USER, DB_PASS, $options);
        $connection->exec("USE yocoli241_0"); 
        return $connection;
    } catch (PDOException $e) {
        error_log("Connexion échouée: " . $e->getMessage());
        return null;
    }
}
?>