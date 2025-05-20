<?php
include('config.php');
class Recette {
    public ?int $id = null;
    public string $titre = '';
    public string $description = '';
    public ?string $image = null;
    public array $ingredients = [];

    public function __construct() {}

    public function __toString(): string {
        return "ID: {$this->id} - Titre: {$this->titre} - Description: {$this->description}";
    }

    public static function obtenirToutes(): array {
        $connection = connecter();
        if (!$connection) return [];

        $query = $connection->prepare("SELECT * FROM recettes ORDER BY titre");
        $query->execute();

        $recettes = $query->fetchAll(PDO::FETCH_CLASS, self::class);

        foreach ($recettes as $recette) {
            $recette->ingredients = self::obtenirIngredients($recette->id);
        }

        return $recettes;
    }

    public function enregistrer(): bool {
        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO recettes (titre, description, image) VALUES (?, ?, ?)");
        $success = $query->execute([$this->titre, $this->description, $this->image]);

        if ($success) {
            $this->id = (int)$connection->lastInsertId();
            foreach ($this->ingredients as $ingredient) {
                $this->ajouterIngredient($ingredient);
            }
        }

        return $success;
    }

    public function modifier(): bool {
        if ($this->id === null) return false;

        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("UPDATE recettes SET titre = ?, description = ?, image = ? WHERE id = ?");
        $success = $query->execute([$this->titre, $this->description, $this->image, $this->id]);

        if ($success) {
            $this->supprimerIngredients();
            foreach ($this->ingredients as $ingredient) {
                $this->ajouterIngredient($ingredient);
            }
        }

        return $success;
    }

    public function supprimer(): bool {
        if ($this->id === null) return false;

        $connection = connecter();
        if (!$connection) return false;

        $this->supprimerIngredients();
        $query = $connection->prepare("DELETE FROM recettes WHERE id = ?");
        return $query->execute([$this->id]);
    }

    public function ajouterIngredient(string $ingredient): bool {
        if ($this->id === null) return false;

        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO ingredients (recette_id, nom) VALUES (?, ?)");
        return $query->execute([$this->id, $ingredient]);
    }

    public function supprimerIngredients(): bool {
        if ($this->id === null) return false;

        $connection = connecter();
        if (!$connection) return false;

        $query = $connection->prepare("DELETE FROM ingredients WHERE recette_id = ?");
        return $query->execute([$this->id]);
    }

    public static function rechercher(string $search): array {
        $connection = connecter();
        if (!$connection) return [];

        $searchTerm = '%' . $search . '%';
        $query = $connection->prepare("
            SELECT DISTINCT r.* FROM recettes r 
            LEFT JOIN ingredients i ON r.id = i.recette_id 
            WHERE r.titre LIKE ? OR r.description LIKE ? OR i.nom LIKE ?
        ");
        $query->execute([$searchTerm, $searchTerm, $searchTerm]);
        $recettes = $query->fetchAll(PDO::FETCH_CLASS, self::class);

        foreach ($recettes as $recette) {
            $recette->ingredients = self::obtenirIngredients($recette->id);
        }

        return $recettes;
    }

    public static function obtenirParId(int $id): ?Recette {
        $connection = connecter();
        if (!$connection) return null;

        $query = $connection->prepare("SELECT * FROM recettes WHERE id = ?");
        $query->execute([$id]);
        $recette = $query->fetchObject(self::class);

        if ($recette) {
            $recette->ingredients = self::obtenirIngredients($id);
        }

        return $recette ?: null;
    }

    private static function obtenirIngredients(int $recetteId): array {
        $connection = connecter();
        if (!$connection) return [];

        $query = $connection->prepare("SELECT nom FROM ingredients WHERE recette_id = ?");
        $query->execute([$recetteId]);

        return $query->fetchAll(PDO::FETCH_COLUMN);
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
