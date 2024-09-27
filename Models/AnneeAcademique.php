<?php
class AnneeAcademique {
    private $conn;
    private $table_name = "anbc_annee_academique";

    public $id;
    public $annee;

    public function __construct($db){
        $this->conn = $db;
    }

    // Lire toutes les années académiques
    public function lireToutes(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lire une seule année académique par ID
    public function lireUneSeule(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Sécuriser l'ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        // Exécution de la requête
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->annee = $row['annee'];
            return true;
        }

        return false;
    }

    // Créer une nouvelle année académique
    public function creer(){
        $query = "INSERT INTO " . $this->table_name . " SET annee = :annee";

        $stmt = $this->conn->prepare($query);

        // Sécuriser les données
        $this->annee = htmlspecialchars(strip_tags($this->annee));

        // Bind des paramètres
        $stmt->bindParam(":annee", $this->annee);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Mettre à jour une année académique
    public function mettreAJour(){
        $query = "UPDATE " . $this->table_name . " SET annee = :annee WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sécuriser les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->annee = htmlspecialchars(strip_tags($this->annee));

        // Bind des paramètres
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":annee", $this->annee);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Supprimer une année académique
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
