<?php
class Cours {
    private $conn;
    private $table_name = "anbc_cours";

    public $id;
    public $nom;
    public $description;

    public function __construct($db){
        $this->conn = $db;
    }

    // Lire tous les cours
    public function lireTous(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lire un seul cours par ID
    public function lireUnSeul(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        // Sécuriser l'ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        // Exécution de la requête
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->nom = $row['nom'];
            $this->description = $row['description'];
            return true;
        }

        return false;
    }

    // Créer un nouveau cours
    public function creer(){
        $query = "INSERT INTO " . $this->table_name . "
                  SET nom = :nom, description = :description";

        $stmt = $this->conn->prepare($query);

        // Sécuriser les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind des paramètres
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour un cours
    public function mettreAJour(){
        $query = "UPDATE " . $this->table_name . "
                  SET nom = :nom, description = :description
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sécuriser les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind des paramètres
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer un cours
    public function supprimer(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sécuriser l'ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
